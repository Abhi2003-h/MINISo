# MiniShop — Professional Frontend Design & Styling Instructions

You are a **senior frontend developer and UI/UX designer**. Your task is to redesign and style the existing **MiniShop** Nuxt application into a polished, professional, minimalistic ecommerce website.

## 1. Project Context

MiniShop is a multi-seller ecommerce learning project.

Current stack:

- Nuxt 4
- Vue 3
- TypeScript
- Tailwind CSS
- Laravel REST API backend
- Sanctum authentication
- Stripe Checkout
- MySQL

The existing functionality is already working.

Existing pages/features include:

- Product listing
- Product detail
- Search
- Category filtering
- Authentication
- Cart
- Orders
- Stripe checkout
- Checkout success page
- Checkout cancel page
- Seller/product functionality
- Navigation/layout

### CRITICAL RULE

**Do not break or rewrite existing application functionality.**

The primary task is **frontend design, layout, UX, responsiveness, and styling**.

Do not modify Laravel APIs, database structure, authentication logic, Stripe logic, or business logic unless absolutely necessary for a UI requirement.

Reuse the existing components, composables, API utilities, types, and functionality wherever possible.

---

# 2. Overall Design Direction

Create a design that feels:

- Minimal
- Modern
- Premium
- Clean
- Trustworthy
- Spacious
- Professional
- Fast
- Easy to navigate

The primary visual identity should use:

**Blue + White**

Use blue as the brand/accent color rather than covering the entire interface in blue.

The website should feel similar in quality to a modern startup ecommerce product rather than a generic template.

Avoid:

- Excessive gradients
- Excessive shadows
- Too many colors
- Huge rounded cards everywhere
- Overly decorative animations
- Cluttered navigation
- Excessive borders
- Generic Bootstrap-looking layouts

The design should have plenty of whitespace.

---

# 3. Suggested Color System

Use a restrained palette.

### Primary

Use a modern blue such as:

- Primary blue: `#2563EB`
- Dark blue: `#1D4ED8`
- Very dark blue: `#172554`

### Background

- Main background: white
- Secondary background: very light blue/gray
- Example: `#F8FAFC`

### Text

- Primary text: dark navy/gray
- Secondary text: muted gray
- Avoid pure black for most text.

### Borders

Use subtle light gray borders.

The blue should be reserved primarily for:

- Brand
- Primary buttons
- Links
- Active states
- Important indicators
- Selected filters
- Small visual accents

---

# 4. Typography

Use a clean modern sans-serif font.

Prefer something similar to:

- Inter
- Geist
- system-ui

Typography should have clear hierarchy:

- Large bold hero heading
- Medium/semibold section headings
- Comfortable body text
- Small muted supporting text

Avoid overly large text that consumes the entire screen.

---

# 5. Navigation / Header

Create a professional ecommerce header.

Desktop:

```text
MiniShop        Products     Categories          Search       Cart   Account
```

The MiniShop logo/brand should be visually distinctive.

Suggested idea:

A simple blue geometric "M" or small shopping-bag icon combined with:

**MiniShop**

Keep the logo minimal.

Navigation should have:

- Clean spacing
- Subtle hover states
- Active page indication
- Cart item count badge
- Login/account state

The header should remain visually lightweight.

Consider a subtle bottom border rather than a heavy shadow.

---

# 6. Homepage

The homepage should immediately communicate what MiniShop is.

Create a minimal hero section.

Example structure:

```text
------------------------------------------------

        Discover things you'll love.

        Simple shopping.
        Curated products.
        Trusted sellers.

        [ Explore Products ]

                  [ subtle visual ]

------------------------------------------------
```

Do not make the hero overly complicated.

### Unique idea

Create a subtle **"MiniShop Picks"** visual element.

For example:

A floating arrangement of 2–3 product cards around the main hero content, with very subtle movement.

Keep the animation extremely subtle.

The page should still look good with animations disabled.

---

# 7. Product Listing Page

Create a professional ecommerce product grid.

Structure:

```text
Products

[ Search products... ]

[ All ] [ Electronics ] [ Clothing ] [ ... ]

------------------------------------------------

Product       Product       Product       Product

Card          Card          Card          Card

------------------------------------------------
```

Use a responsive grid:

Desktop:

```text
4 columns
```

Tablet:

```text
2–3 columns
```

Mobile:

```text
2 columns where appropriate
```

Product cards should contain:

- Product image/visual area
- Product name
- Category
- Price
- Stock status where appropriate
- Add to Cart button

Cards should be minimal.

Prefer:

- subtle border
- small radius
- very light hover elevation
- image zoom on hover
- clean typography

Avoid huge shadows.

---

# 8. Product Cards

Make product cards feel premium.

Suggested interaction:

Normal:

```text
┌────────────────────┐
│                    │
│      PRODUCT       │
│       IMAGE        │
│                    │
├────────────────────┤
│ Category           │
│ Product Name       │
│                    │
│ $29.00     + Cart  │
└────────────────────┘
```

On hover:

- Image scales very slightly
- Card rises 2–4px
- Border/accent becomes slightly more visible
- Add-to-cart interaction becomes more prominent

Keep the animation around 150–250ms.

---

# 9. Product Detail Page

Design this as a premium product page.

Desktop:

```text
┌──────────────────┬──────────────────────────┐
│                  │ Category                 │
│                  │                          │
│ Product Image    │ Product Name             │
│                  │                          │
│                  │ Description              │
│                  │                          │
│                  │ $49.00                   │
│                  │                          │
│                  │ Quantity   [-] 1 [+]     │
│                  │                          │
│                  │ [ Add to Cart ]          │
└──────────────────┴──────────────────────────┘
```

Include:

- Breadcrumbs
- Product name
- Category
- Description
- Price
- Stock availability
- Quantity selector
- Add to Cart
- Back to products

Make the purchase CTA visually strong.

---

# 10. Cart Page

The cart should feel extremely clean.

Use:

```text
Your Cart

Product                  Qty       Price

--------------------------------------------

Product A                2         $40
Product B                1         $20

--------------------------------------------

                         Total: $60

                         [ Checkout ]
```

Make the total section visually distinct.

Use a subtle light-blue summary panel.

If the cart is empty, create a polished empty state:

```text
        🛒

     Your cart is empty

  Discover something you'll love.

     [ Browse Products ]
```

Avoid making empty states look like errors.

---

# 11. Orders Page

Create a clean order history interface.

Each order should clearly show:

- Order ID
- Date
- Status
- Total
- Items

Use status badges.

For example:

```text
PAID
```

Use subtle blue/green styling depending on status, but keep the overall palette restrained.

Orders can use expandable sections if useful.

---

# 12. Checkout Success Page

Make the success page feel satisfying but minimal.

Example:

```text
              ✓

       Payment Successful

    Your order has been confirmed.

      Order #123

   [ View Orders ]

   [ Continue Shopping ]
```

Use a simple animated checkmark if appropriate.

Do not use excessive celebration animations.

---

# 13. Checkout Cancel Page

Create a friendly, non-alarming design.

Example:

```text
        Payment cancelled

     No payment was completed.

       Your cart is still safe.

      [ Return to Cart ]
```

Important:

Do not make cancellation look like a system error.

---

# 14. Empty / Loading / Error States

Every page should have polished states.

### Loading

Use skeleton loaders instead of simply displaying:

```text
Loading...
```

Example:

- Product card skeletons
- Text skeletons
- Button loading state

### Error

Use a clean message:

```text
Something went wrong.

We couldn't load the products.

[ Try Again ]
```

### Empty

Every empty state should tell the user what to do next.

---

# 15. Toast Notifications

Add subtle toast notifications for actions such as:

- Product added to cart
- Product removed
- Login successful
- Logout
- Checkout started

Example:

```text
✓ Added to cart
```

Keep notifications small and unobtrusive.

---

# 16. Micro-interactions

Use subtle interactions throughout the site.

Examples:

- Button hover
- Button press
- Card hover
- Image zoom
- Navigation hover
- Cart badge update
- Toast appearance
- Skeleton loading
- Success checkmark

Animations should be:

- subtle
- fast
- purposeful

Avoid animation for the sake of animation.

Prefer CSS transitions where possible.

---

# 17. Mobile Design

The mobile experience is extremely important.

Do not simply shrink the desktop design.

Create proper mobile layouts.

On mobile:

- Compact header
- Hamburger/mobile navigation if necessary
- Product grid optimized for small screens
- Full-width primary buttons
- Comfortable touch targets
- Proper spacing
- Cart summary stacked vertically

Test at approximately:

- 375px
- 390px
- 768px
- 1024px
- 1440px

---

# 18. Accessibility

Follow good accessibility practices.

Ensure:

- Proper semantic HTML
- Buttons are actually buttons
- Links are actually links
- Inputs have labels
- Images have alt text
- Good keyboard navigation
- Visible focus states
- Sufficient text contrast
- Touch targets are large enough

Do not sacrifice accessibility for visual design.

---

# 19. Component Architecture

Keep the frontend maintainable.

Create reusable components where appropriate.

Examples:

```text
components/
├── AppNav.vue
├── ProductCard.vue
├── ProductGrid.vue
├── SearchBar.vue
├── CategoryFilter.vue
├── CartItem.vue
├── OrderCard.vue
├── EmptyState.vue
├── LoadingSkeleton.vue
├── Toast.vue
└── ...
```

Do not create components unnecessarily.

A component should have a clear purpose.

---

# 20. Tailwind Usage

Use Tailwind consistently.

Avoid writing large amounts of custom CSS when Tailwind can handle the requirement.

Create reusable design patterns through components rather than repeating huge class strings everywhere.

Keep the Tailwind classes readable.

If the project already has Tailwind configured, work with the existing configuration instead of replacing it.

---

# 21. Design System Consistency

Establish consistent values for:

### Border radius

Use mostly one or two radius sizes.

### Shadows

Use subtle shadows only where needed.

### Spacing

Use consistent spacing throughout the application.

### Buttons

Create consistent:

- Primary
- Secondary
- Ghost
- Danger

button styles.

### Inputs

All search fields, forms and quantity controls should have a consistent visual language.

---

# 22. A Unique MiniShop Identity

Do not make this look like a generic ecommerce template.

Introduce a subtle identity around the word **"Mini"**.

Possible design concept:

### "Small shop. Big finds."

Use this as an occasional brand message.

For example, on the homepage:

```text
Small shop.
Big finds.

Discover products from independent sellers.
```

Another idea is a small blue **dot/grid motif** used sparingly throughout the interface.

For example:

```text
MiniShop •
```

or tiny blue 2×2 / 3×3 dot patterns in section corners.

This gives MiniShop a recognizable visual identity without making it visually busy.

---

# 23. Product Image Handling

If real product images already exist, use them.

If images do not exist yet:

Create attractive neutral product-image placeholders rather than broken images.

Do not use random unrelated stock images.

The placeholder should still make the product card look intentional.

---

# 24. UX Principles

Always prioritize:

1. Clarity
2. Simplicity
3. Speed
4. Consistency
5. Accessibility
6. Visual polish

The user should immediately understand:

- What MiniShop sells
- Where products are
- How to search
- How to add something to cart
- How much the cart costs
- Where their orders are

---

# 25. Technical Constraints

Do not:

- Rewrite working API logic
- Change API endpoints
- Change authentication behavior
- Change Stripe integration
- Change database models
- Remove working functionality
- Introduce unnecessary dependencies
- Replace Nuxt architecture unnecessarily
- Replace existing composables unnecessarily

Before modifying a component, inspect its current implementation and preserve its existing behavior.

If a visual change requires a structural change, make the smallest safe change possible.

---

# 26. Final Quality Check

After implementing the design:

### Functional

Verify:

- Product listing works
- Search works
- Category filtering works
- Product detail works
- Add to cart works
- Cart works
- Orders work
- Login/logout works
- Stripe checkout works
- Success page works
- Cancel page works

### Visual

Check:

- Desktop
- Tablet
- Mobile
- Empty states
- Loading states
- Error states
- Hover states
- Focus states

### Code

Check:

- No unnecessary duplicated code
- No broken TypeScript types
- No console errors
- No Vue/Nuxt warnings
- No hydration errors
- No broken routes
- Existing API calls remain unchanged

---

# Final Design Goal

The finished MiniShop should look like a **real, production-quality modern ecommerce startup** while remaining intentionally minimal.

The visual impression should be:

> **Clean white space + confident blue accents + excellent typography + subtle interactions + simple product presentation.**

The design should feel **premium without being flashy**.

Most importantly, preserve the existing MiniShop functionality while transforming the frontend from a learning-project interface into something that looks polished enough to showcase in a portfolio.