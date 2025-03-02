# E-commerce App

## Setup Instructions
1. Clone the repository.
2. Run `composer install`.
3. Configure `.env` database connection.
4. Run `php bin/console doctrine:database:create`.
5. Run `php bin/console doctrine:migrations:migrate`.
6. Start the server with `symfony server:start`.

## Features Implemented
- Product CRUD operations
- API for fetching products
- Responsive UI
