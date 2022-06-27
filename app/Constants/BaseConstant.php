<?php

namespace App\Constants;

class BaseConstant
{
    // default fields
    const ID_FIELD = 'id';
    const STATUS_FIELD = 'status';
    const CREATEDAT_FIELD = 'created_at';
    const UPDATEDAT_FIELD = 'updated_at';
    const DELETEDAT_FIELD = 'deleted_at';

    // role
    const ADMIN_ROLE = 'admin';
    const CASHIER_ROLE = 'cashier';
    const WAITER_ROLE = 'waiter/waitress';
    const CHEF_ROLE = 'chef';

    // pagination
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 20;
    const PAGE_TEXT = 'page';

    // expression
    const EQUAL = '=';

    // all tables
    const ALL_TABLE = 'all';

    // table status
    const TABLE_AVAILABLE = '0';
    const TABLE_ORDERED = '1';
    const TABLE_PAID = '2';

    // food menu
    const FOOD_SALAD = '1';
    const FOOD_GRILL = '2';
    const FOOD_STEAM = '3';
    const FOOD_DRYING = '4';
    const FOOD_DRINK = '5';
}
