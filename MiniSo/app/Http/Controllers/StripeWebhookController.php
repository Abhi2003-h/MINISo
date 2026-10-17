<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(
        Request $request,
        OrderService $orderService
    ) {
        $payload = $request->getContent();

        $signature = $request->header('Stripe-Signature');

        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json([
                'message' => 'Invalid payload.',
            ], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json([
                'message' => 'Invalid signature.',
            ], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if (
                empty($session->metadata->user_id) ||
                empty($session->metadata->cart_id)
            ) {
                throw new \Exception(
                    'Stripe session metadata is missing.'
                );
            }

            if ($session->payment_status !== 'paid') {
                throw new \Exception(
                    'Payment has not been completed.'
                );
            }

            $stripe = new StripeClient(
                config('services.stripe.secret')
            );

            $lineItems = $stripe->checkout->sessions->allLineItems(
                $session->id,
                ['limit' => 100]
            );

            $orderService->createFromStripe(
                (int) $session->metadata->user_id,
                (int) $session->metadata->cart_id,
                $session->id,
                $session,
                $lineItems,
                $stripe
            );
        }

        return response()->json([
            'received' => true,
        ]);
    }
}
