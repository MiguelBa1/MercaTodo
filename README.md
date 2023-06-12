# MercaTodo

MercaTodo is an e-commerce application developed as a project proposed for the PHP Bootcamp by Evertec. The application
allows users to buy products and make payments with WebCheckOut integration.

## Features

### Version 1.0.0

- Customers can register and login to the application.
- Administrators can update customer information.
- Administrators can enable and disable customer accounts.
- Email verification is required for customers to access special features.

### Version 2.0.0

- Administrators can manage products by creating, updating, enabling, and disabling them.
- Registered customers can see the list of available products, separated by pages, with information such as photo and
  price.
- Customers can perform customized searches to quickly find what they are looking for.

### Version 3.0.0

- Customers will be able to view available products and add them to a shopping cart.
- The customer can check their order and make modifications before confirming the order and proceeding with the payment.
- Payment must be made using the Placetopay payment gateway. The system should redirect the customer to the payment gateway's payment page. Once the user returns to the system, it should display the payment result.
- Orders in the system should have a status consistent with the transaction status in the payment gateway.
- Customers should be able to review their purchase history and retry payment for those that were not successful.

## Configuration

To run the application, follow these steps:

1. Clone the repository.
2. Create the `.env` file from the `.env.example` file.
3. Configure the necessary information in the `.env` file.
4. Run `composer install` to install the dependencies.
5. Run `npm install` to install the dependencies.
6. Run `php artisan key:generate` to set the `APP_KEY` value in the `.env` file.
7. Run `php artisan storage:link` to make the images available to the application.
8. Run `php artisan migrate:fresh --seed` to create the database tables.
9. Run `npm run dev` to compile the assets.
10. Run `php artisan serve` to start the application.

**Note:** In order to use image manipulation features in the application, the PHP `gd` extension must be enabled. Please
ensure that this extension is enabled on your server.

## .Env File

The `.env` file contains the configuration of the application. It is important to configure the following variables:

### Database Connection

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Mail Configuration

- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_ENCRYPTION`
- `MAIL_FROM_ADDRESS`
- `MAIL_FROM_NAME`

### Administrator User Information

- `ADMIN_NAME`
- `ADMIN_SURNAME`
- `ADMIN_DOCUMENT_TYPE`
- `ADMIN_DOCUMENT`
- `ADMIN_EMAIL`
- `ADMIN_PHONE`
- `ADMIN_ADDRESS`
- `ADMIN_PASSWORD`
- `ADMIN_CITY_ID`

**Notes:**
- The `ADMIN_DOCUMENT_TYPE` variable must be one of the following values: `CC`, `CE`, `TI`, `PPN`, `NIT`.
- The `ADMIN_CITY_ID` variable must be the ID of a city in the database.

### PlaceToPay credentials

- `PLACETOPAY_LOGIN`
- `PLACETOPAY_TRANKEY`
- `PLACETOPAY_URL`

**Note**

- These credentials are provided by Placetopay. For more information, visit the [Placetopay documentation](https://docs-gateway.placetopay.com/).

## Migrations and Seeds

The application has migrations and seeds to create the database tables and populate them with the necessary information.
To do this, run the following commands:

- `php artisan migrate` to create the database tables.
- `php artisan db:seed` to populate the database tables.

To run the migrations and seeds in a single command, run:

- `php artisan migrate:fresh --seed`
