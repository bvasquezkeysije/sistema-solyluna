<?php

namespace Database\Seeders;

use App\Models\Floor;
use App\Models\Product;
use App\Models\Room;
use Illuminate\Database\Seeder;

class HotelCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $floor1 = Floor::updateOrCreate(['number' => 1], ['code' => 'PIS-001', 'name' => 'Primer piso']);
        $floor2 = Floor::updateOrCreate(['number' => 2], ['code' => 'PIS-002', 'name' => 'Segundo piso']);

        Room::updateOrCreate(['room_number' => '101'], ['code' => 'HAB-101', 'floor_id' => $floor1->id, 'type' => 'Simple', 'hourly_rate' => 25, 'daily_rate' => 120, 'active' => true]);
        Room::updateOrCreate(['room_number' => '102'], ['code' => 'HAB-102', 'floor_id' => $floor1->id, 'type' => 'Doble', 'hourly_rate' => 35, 'daily_rate' => 160, 'active' => true]);
        Room::updateOrCreate(['room_number' => '201'], ['code' => 'HAB-201', 'floor_id' => $floor2->id, 'type' => 'Matrimonial', 'hourly_rate' => 45, 'daily_rate' => 220, 'active' => true]);

        Product::updateOrCreate(['name' => 'Coca Cola'], ['code' => 'PRO-001', 'category' => 'Bebidas', 'price' => 6.00, 'stock' => 100, 'active' => true]);
        Product::updateOrCreate(['name' => 'Papel higienico'], ['code' => 'PRO-002', 'category' => 'Higiene', 'price' => 3.50, 'stock' => 80, 'active' => true]);
        Product::updateOrCreate(['name' => 'Snack mixto'], ['code' => 'PRO-003', 'category' => 'Snacks', 'price' => 4.50, 'stock' => 120, 'active' => true]);
    }
}