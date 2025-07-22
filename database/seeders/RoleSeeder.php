<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\RolesEnum;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::create(['name'=>RolesEnum::User->value]);
        $vendorRole = Role::create(['name'=>RolesEnum::Vendor->value]);
        $adminRole = Role::create(['name'=>RolesEnum::Admin->value]);

        $approveVendors = Permission::create([
            'name'=>PermissionsEnum::ApproveVendors->value
        ]);

        $SellProducts = Permission::create([
            'name'=>PermissionsEnum::SellProducts->value
        ]);

        $BuyProducts = Permission::create([
            'name'=>PermissionsEnum::BuyProducts->value
        ]);

        $userRole->syncPermissions([
            $BuyProducts,
        ]);
        
        $vendorRole->syncPermissions([
            $SellProducts,
            $BuyProducts,
        ]);

        $adminRole->syncPermissions([
            $approveVendors,
            $SellProducts,
            $BuyProducts,
        ]);
    }
}
