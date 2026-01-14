# Deploy to Railway (PHP + MySQL)

## Start command
This repo includes a Procfile:
- web: php -S 0.0.0.0:$PORT -t .

## Database
Railway MySQL env vars used in `config/db.php`:
- MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD

Import SQL from `database/` (schema.sql + seed.sql).
