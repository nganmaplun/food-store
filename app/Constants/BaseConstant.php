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
    const CHEF_SALAD_ROLE = 'chef_salad';
    const CHEF_STEAM_ROLE = 'chef_steam';
    const CHEF_GRILL_ROLE = 'chef_grill';
    const CHEF_DRYING_ROLE = 'chef_drying';
    const CHEF_DRINK_ROLE = 'chef_drink';

    // pagination
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 20;
    const PAGE_TEXT = 'page';

    // expression
    const EQUAL = '=';
    const DIFFERENCE = '!=';
    const GREATER_AND_EQUAL_THAN = '>=';
    const LESS_AND_EQUAL_THAN = '<=';

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

    // channel notify
    const CHEF_SALAD_CHANNEL = 'chef-salad-channel';
    const CHEF_GRILL_CHANNEL = 'chef-grill-channel';
    const CHEF_STEAM_CHANNEL = 'chef-steam-channel';
    const CHEF_DRYING_CHANNEL = 'chef-drying-channel';
    const CHEF_DRINK_CHANNEL = 'chef-drink-channel';
    const CASHIER_CHANNEL = 'cashier-channel';
    const WAITER_CHANNEL = 'waiter-channel';

    // message type
    const SEND_CHEF = 'send-chef';
    const SEND_CASHIER = 'send-cashier';
    const SEND_WAITER = 'send-waiter';

    const ARRAY_KITCHEN = [
        self::FOOD_SALAD => "Bếp đồ sống (SASHI)",
        self::FOOD_GRILL => "Bếp chiên (AGE)",
        self::FOOD_STEAM => "Bếp ga(KON)",
        self::FOOD_DRYING => "Bếp nướng (YAKI)",
    ];

    const ARRAY_KITCHEN_SHORT = [
        self::FOOD_SALAD => "SASHI",
        self::FOOD_GRILL => "AGE",
        self::FOOD_STEAM => "KON",
        self::FOOD_DRYING => "YAKI",
    ];

    const ARRAY_KITCHEN_ALL = [
        self::FOOD_SALAD => "SASHI",
        self::FOOD_GRILL => "AGE",
        self::FOOD_STEAM => "KON",
        self::FOOD_DRYING => "YAKI",
        self::FOOD_DRINK => "NOMIMOMO",
    ];

    const FAKE_PASSWORD = '********';
}
