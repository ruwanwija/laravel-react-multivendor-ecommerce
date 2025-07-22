<?php

namespace App\Enums;

enum PermissionsEnum: string
{
   case ApproveVendors = 'approve_vendors';
   case SellProducts = 'sell_products';
   case BuyProducts = 'buy_products';
}
