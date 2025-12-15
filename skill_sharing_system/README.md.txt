# Skill Sharing System (MVP)

Tech: PHP (MySQLi OO), MySQL, HTML/CSS, minimal JS

## Setup (XAMPP/WAMP)
1. Put `skill_sharing_system/` into your webroot (e.g., `C:\xampp\htdocs\skill_sharing_system`).
2. Import `sql/schema.sql` into MySQL (phpMyAdmin) to create DB and tables.
3. Edit `config.php` DB credentials if needed.
4. Open browser: `http://localhost/skill_sharing_system/index.php`
5. Register a new user or use demo user (if you seeded one).

## Notes
- Passwords are hashed using `password_hash`.
- CSRF protection available on forms.
- Uses prepared statements (MySQLi OO) for SQL safety.
- MVP: no categories, one user role (can teach & learn).
