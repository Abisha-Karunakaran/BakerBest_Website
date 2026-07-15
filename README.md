# 🍞 BakerBest - Online Bakery Management System

BakerBest is a full-stack web application for a bakery based in Jaffna, Sri Lanka. It allows customers to browse the menu, view the gallery, place pickup orders online, track order history, and contact the bakery — all through a warm, custom-designed interface.

---

## 📌 Features

- **Home Page** – Rotating hero banner, featured menu categories, about teaser
- **About Us** – Bakery story, core values, target customers, team/specialists
- **Menu** – Dynamic menu pulled from database, live search filter, quantity selector, discounted pricing, product popup preview, add to cart
- **Order / Cart** – Client-side cart (localStorage), pickup date & time selection, payment method selection, AJAX order placement
- **Order History** – Logged-in users can view their past orders with status tracking (Pending / Processing / Ready / Completed / Cancelled)
- **Gallery** – Image gallery with lightbox viewer
- **Contact Us** – Contact form with database storage, displays admin replies to the customer's messages
- **Authentication** – Signup, Login, Logout with session-based auth
- **Responsive Design** – Mobile sidebar navigation, responsive grids for all pages
- **Admin Panel** – Separate admin login and dashboard to manage the entire store:
  - Dashboard overview
  - Category management (add/edit/delete menu categories)
  - Menu management (add/edit/delete menu items, pricing, discounts, images)
  - Order management (view & update order status)
  - Customer management (view registered customers)
  - Gallery management (upload/manage gallery images)
  - Contact messages management (reply to customer messages)

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural) |
| Database | MySQL (via `mysqli`) |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Fonts | Poppins, Great Vibes (Google Fonts) |
| Cart Storage | Browser `localStorage` |
| Session Handling | PHP native sessions |

---

## 📂 Project Structure

```
BakerBest/
│
├── index.php                      # Home page
├── about.php                      # About us page
├── menu.php                       # Menu listing + cart logic
├── order.php                      # Cart summary + checkout flow
├── payment.php                    # Server-side order processing
├── sucess.php                     # Order confirmation page
├── view_order_history.php         # AJAX partial - order history table
├── contact.php                    # Contact form + message history
├── gallery.php                    # Image gallery
├── login.php                      # User login
├── signup.php                     # User registration
├── logout.php                     # Session destroy
├── header.php                     # Shared navbar (included in every page)
├── footer.php                     # Shared footer (included in every page)
│
├── Backend/
│   ├── db.php                     # Database connection
│   ├── save_order.php             # AJAX endpoint to save customer orders
│   │
│   ├── admin_login.php            # Admin login
│   ├── admin_logout.php           # Admin session destroy
│   ├── admin_header.php           # Shared admin panel navbar/sidebar
│   ├── admin_dashboard.php        # Admin dashboard overview
│   ├── category_management.php    # Manage menu categories (CRUD)
│   ├── menu_management.php        # Manage menu items (CRUD, pricing, discounts, images)
│   ├── order_management.php       # View & update customer order status
│   ├── customer_management.php    # View registered customers
│   ├── gallery_management.php     # Upload/manage gallery images
│   ├── admin_messages.php         # View & reply to contact form messages
│   │
│   ├── assets/                    # Backend/admin panel assets
│   └── uploads/gallery/           # Uploaded gallery images (served to gallery.php)
│
└── assets/                        # Images, icons, background used across customer-facing pages
```

---

## 🗄️ Database Overview (`baker_best`)

| Table | Purpose |
|---|---|
| `users` | Customer accounts (name, email, password, phone, address) |
| `menu_categories` | Menu category list |
| `menu_items` | Menu items (name, price, discount, image, category) |
| `cart_items` | Server-side cart per user/session (used at checkout) |
| `orders` | Placed orders (customer info, total, payment method, status, pickup datetime) |
| `order_items` | Line items belonging to each order |
| `contact_messages` | Customer messages + admin replies |
| `gallery_images` | Gallery photo records |
| `admins` *(likely)* | Admin login credentials for `Backend/admin_login.php` |

> ⚠️ Table structures are inferred from the queries in the code. You will need to create these tables manually (see **Setup** below) since no `.sql` schema file was included in this upload.

---

## ⚙️ Setup Instructions

1. **Install requirements**
   - PHP 7.4+ (with `mysqli` extension enabled)
   - MySQL / MariaDB
   - A local server stack (XAMPP / WAMP / LAMP)

2. **Database setup**
   - Create a database named `baker_best`
   - Create the tables listed above matching the columns referenced in the PHP files (`users`, `menu_items`, `menu_categories`, `orders`, `order_items`, `cart_items`, `contact_messages`, `gallery_images`)
   - Update DB credentials in `Backend/db.php` and in `menu.php` / `payment.php` / `sucess.php` (currently hardcoded as `localhost / root / root / baker_best`)

3. **File placement**
   - Place the project folder inside your server's `htdocs` (XAMPP) or `www` (WAMP) directory
   - Ensure the `Backend/` folder is present with `db.php`, `save_order.php`, and all `admin_*` / `*_management.php` files, plus `Backend/assets/` and `Backend/uploads/gallery/`

4. **Run**
   - Start Apache & MySQL from your local server panel
   - Visit `http://localhost/BakerBest/index.php` for the customer site
   - Visit `http://localhost/BakerBest/Backend/admin_login.php` for the admin panel

---

## 🔐 Known Security Issues (To Fix Before Production)

These were found while reviewing the code and should be addressed:

- **Plain-text passwords** – `signup.php` and `login.php` store and compare passwords directly with no hashing. Should use `password_hash()` / `password_verify()`.
- **SQL Injection risk in `login.php`** – The email field is inserted directly into the query without escaping or prepared statements.
- **Hardcoded DB credentials** repeated across multiple files (`menu.php`, `payment.php`, `sucess.php`) instead of a single shared config/include.
- **Client-trusted pricing** – Some cart/order totals rely on values sent from the browser rather than being recalculated server-side.
- **Duplicate session handling** – Some files check `session_status()` before starting a session, others call `session_start()` directly; should be made consistent to avoid "session already started" warnings.

---

## 👥 User Roles

- **Guest** – Can browse menu, gallery, about, and add items to cart, but must log in to check out.
- **Registered Customer** – Can log in, place orders, view order history, and message the bakery.
- **Admin** – Logs in separately via `Backend/admin_login.php` and manages the store through `Backend/admin_dashboard.php`:
  - Add/edit/remove menu categories and menu items
  - Update order statuses (Pending → Processing → Ready → Completed/Cancelled)
  - View registered customers
  - Manage gallery images
  - Reply to customer contact messages (shown back to the customer on `contact.php`)

---

