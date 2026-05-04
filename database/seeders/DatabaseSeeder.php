<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndAdminSeeder::class,
            HotelCatalogSeeder::class,
            HotelSalesSeeder::class,
            ProductCategorySeeder::class,
            RoomTypeSeeder::class,
            PaymentTypeSeeder::class,
        ]);
    }
}
