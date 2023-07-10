<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CREATE_USERS = 'Create users';
    case READ_USERS = 'Read users';
    case UPDATE_USERS = 'Update users';
    case DELETE_USERS = 'Delete users';

    case CREATE_PRODUCTS = 'Create products';
    case READ_PRODUCTS = 'Read products';
    case UPDATE_PRODUCTS = 'Update products';
    case DELETE_PRODUCTS = 'Delete products';
    case CREATE_CATEGORIES = 'Create categories';
}
