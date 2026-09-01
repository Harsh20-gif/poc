# Proof of Content CRM

A lightweight CRM for certification/compliance services built with Laravel 11.

## Requirements
- PHP 8.2+
- MySQL 8.x
- Composer (for initial framework setup, though dependencies are kept standard)

## Initial Setup

1. **Clone and Configure**
   ```bash
   cp .env.example .env
   # Update DB credentials in .env
   ```

2. **Database Migration and Seeding**
   This command will wipe the existing database, run all migrations in the correct order, and seed the test users.
   ```bash
   php artisan migrate:fresh --seed --seeder=RoleUserSeeder
   ```

3. **Test Users Available**
   All passwords are: `password`
   - `admin@proofofcontent.test` (Role: admin)
   - `sales@proofofcontent.test` (Role: sales)
   - `verifier@proofofcontent.test` (Role: verifier)

4. **Storage Link**
   To make uploaded client documents and certificates publicly accessible, ensure you link the storage:
   ```bash
   php artisan storage:link
   ```

5. **Run the application**
   ```bash
   php artisan serve
   ```

## Scheduled Tasks
To process certification renewals, ensure the Laravel scheduler is running. It checks for expiring certificates and automatically creates renewal leads.
```bash
php artisan schedule:work
```
Or you can manually trigger it to test:
```bash
php artisan certifications:check-expiry --days=30
```
