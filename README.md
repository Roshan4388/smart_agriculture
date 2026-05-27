# Smart Agriculture

Smart Agriculture is a PHP-based web application for data-driven farming, land tracking, crop recommendations, marketplace management, expert consultation, and security monitoring.

## Project structure

- `assets/` – CSS, JavaScript, images, and frontend assets.
- `includes/` – shared configuration, authentication, database connection, and layout components.
- `modules/` – functional pages for crops, weather, maps, security, experts, and marketplace.
- `admin/` – administrative management pages for users, crops, products, and reports.
- `api/` – lightweight JSON endpoints for weather and map data.
- `database/` – SQL schema and seed data.
- `uploads/` – file upload storage for user assets.

## Setup

1. Place the project folder in your local web server root, for example `c:\xampp\htdocs\smart_agriculture`.
2. Create a MySQL database named `smart_agriculture`.
3. Import `database/smart_agriculture.sql` into your database.
4. Update the database connection in `includes/db.php` if your MySQL username or password differs.
5. Open the application in your browser at `http://localhost/smart_agriculture/`.

## Default admin account

- Email: `admin@smartagri.local`
- Password: `Admin@123`

## Notes

- Replace `YOUR_GOOGLE_MAPS_API_KEY` in `dashboard.php` and `modules/maps/land-map.php` with your actual Maps API key.
- Use `modules/crops/crop-list.php` and `modules/marketplace/products.php` to manage crop and marketplace content.

