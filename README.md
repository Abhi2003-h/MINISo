# MiniShop

A multi-seller e-commerce learning project: a Laravel REST API backed by Sanctum token auth and Stripe Checkout (test mode), with a Nuxt 4 storefront on top.

> Status: the core shopping flow (browse, cart, pay, order history) and a basic seller dashboard work end to end. The Filament admin dashboard, automated tests and product images from the original plan are not built yet. See [Project status](#project-status).

## Repository layout

```
MINISo/
├── MiniSo/            Laravel 13 API (PHP ^8.3, Sanctum, Stripe)
├── frontend/          Nuxt 4 + Vue 3 + TypeScript + Tailwind CSS storefront
├── Miniso_plan.md     Original stack and architecture plan
└── frontend.md        Design/styling brief used for the storefront redesign
```

## Architecture

```
Nuxt storefront  ──  REST / JSON (Bearer token)  ──  Laravel API  ──  MySQL
                                                          │
                                                          └──  Stripe (test mode) + webhook
```

## Tech stack

| Layer | Choice |
| --- | --- |
| Backend | Laravel 13, PHP ^8.3 |
| Auth | Laravel Sanctum personal access tokens |
| Payments | Stripe Checkout (`stripe/stripe-php`), webhook-driven order creation |
| Database | MySQL in the local `.env` (`.env.example` defaults to SQLite) |
| Frontend | Nuxt 4, Vue 3, TypeScript, Tailwind CSS 4, Nuxt UI |
| Frontend tests | Vitest, Playwright (scaffolded only) |
| Backend tests | PHPUnit (scaffold only) |

## Features

**Customers**
- Register, log in and log out (token stored in `localStorage`)
- Browse products, view product details, filter by category, search
- Cart: add items, change quantity, remove items, with stock checks
- Pay through Stripe Checkout; success and cancel pages
- Order history

**Sellers**
- Seller-only routes guarded by a `seller` middleware (`role === 'seller'`)
- Dashboard listing their own products
- Create a product, edit its price, delete it (ownership is enforced)

**Payments**
- `POST /api/checkout/session` builds a Stripe Checkout session from the cart
- `POST /api/stripe/webhook` verifies the Stripe signature and, on `checkout.session.completed`, creates a `paid` order inside a transaction
- Order creation is idempotent per `stripe_session_id`, locks product rows, re-checks stock, checks Stripe line items against the cart, decrements stock and clears the cart

## API

All routes are under `/api`.

| Method | Route | Auth | Purpose |
| --- | --- | --- | --- |
| POST | `/auth/register` | – | Create account, returns user + token |
| POST | `/auth/login` | – | Returns user + token |
| GET | `/auth/user`, `/user` | token | Current user |
| POST | `/auth/logout` | token | Revoke current token |
| GET | `/product`, `/product/{id}` | – | List / show products |
| GET | `/categories` | – | List categories |
| GET | `/seller/products` | seller | Own products |
| POST | `/product` | seller | Create product |
| PUT | `/product/{id}` | seller (owner) | Update price |
| DELETE | `/product/{id}` | seller (owner) | Delete product |
| GET | `/cart` | token | Cart with items and total |
| POST | `/cart/items` | token | Add item |
| PUT | `/cart/items/{id}` | token | Change quantity |
| DELETE | `/cart/items/{id}` | token | Remove item |
| POST | `/checkout/session` | token | Create Stripe Checkout session, returns `url` |
| POST | `/stripe/webhook` | Stripe signature | Create paid order |
| POST | `/checkout` | token | Direct order without payment (earlier flow, still routed) |
| GET | `/orders` | token | Current user's orders |

Prices are stored as integers in cents.

## Getting started

### Backend (`MiniSo/`)

```bash
cd MiniSo
composer install
cp .env.example .env        # then set DB_*, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET
php artisan key:generate
php artisan migrate
php artisan serve           # http://127.0.0.1:8000
```

Forward Stripe webhooks to the local API:

```bash
stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook
```

Copy the printed signing secret into `STRIPE_WEBHOOK_SECRET`.

### Frontend (`frontend/`)

```bash
cd frontend
npm install
npm run dev                 # http://localhost:3000
```

Other scripts: `npm run build`, `npm test`, `npm run test:e2e`.

### Becoming a seller

Registration always creates a `customer` (the `role` column defaults to `customer` and is not mass-assignable). To try the seller features, change the role directly:

```sql
UPDATE users SET role = 'seller' WHERE email = 'you@example.com';
```

## Project status

Measured against `Miniso_plan.md`:

| Plan item | State |
| --- | --- |
| Laravel REST API | Done |
| Sanctum authentication | Done |
| Products, categories, seller ownership | Done (update only changes price) |
| Cart | Done |
| Orders | Done |
| Stripe Checkout, test mode, webhook | Done |
| Nuxt + Vue + TypeScript frontend | Done: home, products, product detail, cart, login, register, orders, checkout success/cancel, seller dashboard/create/edit |
| Tailwind styling | Done: component library and layout built per `frontend.md` |
| MySQL | In use locally |
| Filament admin dashboard | **Not started** (not in `composer.json`) |
| Product images / file storage | **Not started** (no image column or upload endpoint) |
| Pest / PHPUnit tests | **Not started**: only the framework's example tests exist |
| Frontend tests | Scaffolding only (example Vitest and Playwright specs) |

## Known issues and cleanup

- **No way to create a seller** through the app; the role must be set in the database.
- **Hard-coded URLs:** Stripe success/cancel URLs point at `http://localhost:3000`, and the frontend API base is `http://127.0.0.1:8000/api` (`frontend/app/utils/api.ts`). Move both to config before deploying.
- **Legacy `POST /api/checkout`** creates an order without payment; remove it or restrict it now that Stripe is wired up.
- Product `is_active` exists in the original migration but is not used by the API.
- `ProductController::update` only accepts `price`; the edit page can't change other fields.
- `ProductController::index` returns every product with no pagination.
- `DatabaseSeeder` only creates a test user; there are no category or product seeds.
- `frontend/README.md` and `MiniSo/README.md` are still the Nuxt and Laravel boilerplate.

## Suggested next steps

1. Add category and product seeders and a way to promote a user to seller.
2. Write feature tests for auth, cart stock rules, seller ownership and the webhook/`OrderService` path.
3. Add the Filament admin panel for users, products and orders.
4. Add product image upload using the Laravel filesystem.
5. Move hard-coded URLs to env config and paginate `/product`.

## License

MIT, see [LICENSE](LICENSE).
