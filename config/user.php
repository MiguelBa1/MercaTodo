<?php

return [

    /*
     * Values to create the first admin user
     */
    'admin' => [
        'name' => env('ADMIN_NAME'),
        'surname' => env('ADMIN_SURNAME'),
        'document_type' => env('ADMIN_DOCUMENT_TYPE'),
        'document' => env('ADMIN_DOCUMENT'),
        'email' => env('ADMIN_EMAIL'),
        'phone' => env('ADMIN_PHONE'),
        'address' => env('ADMIN_ADDRESS'),
        'password' => env('ADMIN_PASSWORD'),
        'city_id' => env('ADMIN_CITY_ID'),
    ],

    /*
     * Values to seed the database
     */
    'seed' => [
        'brands' => env('SEED_BRANDS'),
        'categories' => env('SEED_CATEGORIES'),
        'products' => env('SEED_PRODUCTS'),
        'users' => env('SEED_USERS'),
        'orders' => env('SEED_ORDERS'),
    ],
];
