# MercaTodo

MercaTodo is an e-commerce application developed as a project proposed for Evertec's PHP Bootcamp. This application is
designed to provide a smooth and efficient online shopping experience for users. Key features include product
management, order tracking, customized search options, and integration with the WebCheckOut payment system. With
MercaTodo, users can explore products, add them to their shopping cart, and make payments securely.

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
- Payment must be made using the Placetopay payment gateway. The system should redirect the customer to the payment
  gateway's payment page. Once the user returns to the system, it should display the payment result.
- Orders in the system should have a status consistent with the transaction status in the payment gateway.
- Customers should be able to review their purchase history and retry payment for those that were not successful.

### Version 4.0.0

- The administrator will be able to mass import a list of products into the system from an Excel file.
- The administrator will be able to download an Excel list of registered products to modify them and upload them back
  into the system in a mass manner.
- The administrator will be able to generate system reports with relevant information for business management.
- The use of system functionalities should only be allowed for users with permissions (ACL).
- The system allows managing products through a REST API. (A complete API documentation is provided via Postman, you can
  access it by
  clicking [here](https://documenter.getpostman.com/view/28423197/2s946fetEB#auth-info-27adae84-e50a-4eb0-bfa1-1bee7ee3b4f5)).

## Configuration

To run the application, follow these steps:

1. Clone the repository.
2. Create the `.env` file from the `.env.example` file.
3. Configure the necessary information in the `.env` file.
4. Run `composer install` to install the dependencies.
5. For this version, frontend assets are precompiled, so you won't need to run `npm install` and `npm run dev`. However,
   if you modify the frontend code, you should run these commands to recompile the assets.
6. Run `php artisan key:generate` to set the `APP_KEY` value in the `.env` file.
7. Run `php artisan storage:link` to make the images available to the application.
8. Run `php artisan migrate:fresh --seed` to create the database tables.
9. The application uses queued jobs for certain tasks. To start processing these jobs, you should
   run `php artisan queue:work`.
10. The application also provides custom commands for monitoring tasks. To start these monitoring tasks, you should
    run `php artisan schedule:work`.
11. Run `php artisan serve` to start the application.

**Note:** To use the image manipulation features in the application, the PHP `gd` extension must be enabled. Please
ensure that this extension is enabled on your server.

## .Env File

The `.env` file contains the application configuration. It is important to configure the following variables:

### Database Connection

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Mail Configuration

The application uses these services for user registration and system report generation. You can register in
any service such as [Mailtrap](https://mailtrap.io/), [Mailgun](https://www.mailgun.com/),
or [Sendgrid](https://sendgrid.com/).

The following variables need to be configured:

- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_ENCRYPTION`
- `MAIL_FROM_ADDRESS`
- `MAIL_FROM_NAME`

### Seed the database

Fill these variables with the number of records you want to create for each table:

- `SEED_USERS`
- `SEED_BRANDS`
- `SEED_CATEGORIES`
- `SEED_PRODUCTS`
- `SEED_ORDERS`

### Administrator User Information

The following variables must be configured with the information of the administrator user:

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

These variables are used to create an initial user with the role of SUPER ADMIN in the system. The SUPER ADMIN role has
full access to all system features. The application supports three roles: SUPER ADMIN, ADMIN, and CUSTOMER. The SUPER
ADMIN role is the most powerful and has full control over all system settings and functionalities.

### PlaceToPay Credentials

- `PLACETOPAY_LOGIN`
- `PLACETOPAY_TRANKEY`
- `PLACETOPAY_URL`

**Note**

- These credentials are provided by Placetopay. For more information, visit
  the [Placetopay documentation](https://docs-gateway.placetopay.com/).

## Migrations and Seeds

The application has migrations and seeds to create the database tables and populate them with the necessary information.
To do this, run the following commands:

- `php artisan migrate` to create the database tables.
- `php artisan db:seed` to populate the database tables.

To run the migrations and seeds in a single command, run:

- `php artisan migrate:fresh --seed`
