NITYA HERBAL ADMIN PANEL — XAMPP + PHP + MySQL

REQUIREMENTS
- Windows with XAMPP (Apache + MySQL/MariaDB)
- PHP extensions: pdo_mysql, fileinfo (usually enabled in XAMPP)

INSTALL
1. Extract the nitya_herbal_admin folder into:
   C:\xampp\htdocs\nitya-admin
   Make sure config.php is directly inside that folder.
2. Open XAMPP Control Panel and Start Apache and MySQL.
3. Open http://localhost/phpmyadmin/
4. Import database.sql using the Import tab.
   Alternatively, open the SQL tab and paste the contents of database.sql.
5. Check config.php. Default XAMPP username is root and password is blank.
   If your MySQL root account has a password, set $dbpass in config.php.
6. Visit http://localhost/nitya-admin/install.php
7. Create your admin username and password (minimum 10 characters).
8. DELETE install.php after creating the admin.
9. Visit http://localhost/nitya-admin/login.php and sign in.

FEATURES
- Admin login with password hashing and session regeneration
- Dashboard counts
- Add, edit, delete, search products
- Product image uploads (JPG/PNG/WEBP, max 4 MB)
- Product selling price, MRP, stock and active/inactive status
- Orders and customer listing (orders are shown after data is entered into the database)

IMPORTANT
- This package runs locally on your PC. It does not automatically connect to your live Netlify site.
- Netlify static hosting cannot run PHP. To connect the live site, deploy the PHP app and MySQL database to a PHP-capable server and connect through a secure API.
- Do not expose your local XAMPP or phpMyAdmin to the public internet.
- This starter admin does not include customer checkout, payment gateway, shipping integration, or automatic order capture from Netlify.
