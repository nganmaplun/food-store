<?php

namespace App\Constants;

class OrderConstant
{
    const TABLE_NAME = 'order';
    const EMPLOYEE_ID_FIELD = 'employee_id';
    const TABLE_ID_FIELD = 'table_id';
    const PARENT_TABLE_ID_FIELD = 'sub_order_id';
    const CUSTOMER_TYPE_FIELD = 'customer_type';
    const NUMBER_OF_CUSTOMER_FIELD = 'number_of_customers';
    const TOTAL_PRICE_FIELD = 'total_price';
    const IS_PAID_FIELD = 'is_paid';
    const DESCRIPTION_FIELD = 'description';
    const ORDER_DATE_FIELD = 'order_date';
    const PAID_TYPE_FIELD = 'paid_type';
    const DRAFT_FIELD = 'is_draft';
    const DISCOUNT_FIELD = 'discount';
}
