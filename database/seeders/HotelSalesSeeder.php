<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Room;
use App\Models\RoomRental;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HotelSalesSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['code' => 'CLI-0001', 'dni' => '45879632', 'full_name' => 'Juan Carlos Rojas Diaz', 'email' => 'juan.rojas@mail.com', 'phone' => '987654321', 'active' => true],
            ['code' => 'CLI-0002', 'dni' => '71345682', 'full_name' => 'Maria Fernanda Soto Ruiz', 'email' => 'maria.soto@mail.com', 'phone' => '956321478', 'active' => true],
            ['code' => 'CLI-0003', 'dni' => '60231459', 'full_name' => 'Luis Alberto Cueva Ramos', 'email' => 'luis.cueva@mail.com', 'phone' => '945632178', 'active' => true],
            ['code' => 'CLI-0004', 'dni' => '75963124', 'full_name' => 'Rosa Elena Paredes Mena', 'email' => 'rosa.paredes@mail.com', 'phone' => '989741236', 'active' => true],
        ];

        foreach ($clients as $data) {
            Client::updateOrCreate(['dni' => $data['dni']], $data);
        }

        $user = User::query()->orderBy('id')->first();
        if (!$user) {
            return;
        }

        $client1 = Client::where('dni', '45879632')->first();
        $client2 = Client::where('dni', '71345682')->first();
        $client3 = Client::where('dni', '60231459')->first();
        $client4 = Client::where('dni', '75963124')->first();

        $room101 = Room::where('room_number', '101')->first();
        $room102 = Room::where('room_number', '102')->first();
        $room201 = Room::where('room_number', '201')->first();

        $cocacola = Product::where('code', 'PRO-001')->first();
        $papel = Product::where('code', 'PRO-002')->first();
        $snack = Product::where('code', 'PRO-003')->first();

        $cash = PaymentType::query()->where('name', 'Efectivo')->first();
        $yape = PaymentType::query()->where('name', 'Yape')->first();
        $plin = PaymentType::query()->where('name', 'Plin')->first();
        $transfer = PaymentType::query()->where('name', 'Transferencia')->first();

        if (!$client1 || !$client2 || !$client3 || !$client4 || !$room101 || !$room102 || !$room201 || !$cocacola || !$papel || !$snack || !$cash || !$yape || !$plin || !$transfer) {
            return;
        }

        $this->seedSaleWithRentalAndProducts(
            code: 'VTA-0001',
            correlative: 1,
            clientId: $client1->id,
            userId: $user->id,
            paymentTypeId: $cash->id,
            roomId: $room101->id,
            startAt: Carbon::now()->subDays(2)->setTime(9, 0),
            endAt: Carbon::now()->subDays(2)->setTime(15, 0),
            rate: 25,
            productLines: [
                ['product' => $cocacola, 'quantity' => 2],
                ['product' => $snack, 'quantity' => 1],
            ],
            status: 'paid'
        );

        $this->seedSaleWithRentalAndProducts(
            code: 'VTA-0002',
            correlative: 2,
            clientId: $client2->id,
            userId: $user->id,
            paymentTypeId: $yape->id,
            roomId: $room201->id,
            startAt: Carbon::now()->subDay()->setTime(22, 0),
            endAt: Carbon::now()->setTime(8, 0),
            rate: 45,
            productLines: [
                ['product' => $papel, 'quantity' => 1],
            ],
            status: 'paid'
        );

        $this->seedProductOnlySale(
            code: 'VTA-0003',
            correlative: 3,
            clientId: $client3->id,
            userId: $user->id,
            paymentTypeId: $transfer->id,
            lines: [
                ['product' => $cocacola, 'quantity' => 1],
                ['product' => $snack, 'quantity' => 3],
            ],
            status: 'paid'
        );

        $this->seedSaleWithRentalAndProducts(
            code: 'VTA-0004',
            correlative: 4,
            clientId: $client4->id,
            userId: $user->id,
            paymentTypeId: $plin->id,
            roomId: $room102->id,
            startAt: Carbon::now()->subHours(8),
            endAt: Carbon::now()->subHours(2),
            rate: 35,
            productLines: [
                ['product' => $papel, 'quantity' => 1],
            ],
            status: 'paid'
        );

        $this->seedProductOnlySale(
            code: 'VTA-0005',
            correlative: 5,
            clientId: $client1->id,
            userId: $user->id,
            paymentTypeId: $cash->id,
            lines: [
                ['product' => $papel, 'quantity' => 2],
                ['product' => $snack, 'quantity' => 2],
            ],
            status: 'paid'
        );

        // Ninguna venta se queda sin tipo de pago.
        Sale::query()->whereNull('payment_type_id')->update(['payment_type_id' => $cash->id]);
    }

    private function seedSaleWithRentalAndProducts(
        string $code,
        int $correlative,
        int $clientId,
        int $userId,
        int $paymentTypeId,
        int $roomId,
        Carbon $startAt,
        Carbon $endAt,
        float $rate,
        array $productLines,
        string $status
    ): void {
        $sale = Sale::updateOrCreate(
            ['code' => $code],
            [
                'document_type' => 'boleta',
                'series' => 'B001',
                'correlative' => $correlative,
                'client_id' => $clientId,
                'user_id' => $userId,
                'payment_type_id' => $paymentTypeId,
                'status' => $status,
                'total' => 0,
                'subtotal' => 0,
                'igv' => 0,
            ]
        );

        SaleItem::where('sale_id', $sale->id)->delete();
        RoomRental::where('sale_id', $sale->id)->delete();

        $hours = max(1, (int) ceil($startAt->diffInMinutes($endAt) / 60));
        $days = max(1, (int) ceil($hours / 24));
        $rentalSubtotal = round($hours * $rate, 2);

        RoomRental::create([
            'sale_id' => $sale->id,
            'room_id' => $roomId,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'hours' => $hours,
            'days' => $days,
            'rate' => $rate,
            'subtotal' => $rentalSubtotal,
        ]);

        $total = $rentalSubtotal;

        foreach ($productLines as $line) {
            $product = $line['product'];
            $qty = (int) $line['quantity'];
            $subtotal = round($qty * (float) $product->price, 2);

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        [$base, $igv] = $this->splitTax($total);
        $sale->update([
            'total' => $total,
            'subtotal' => $base,
            'igv' => $igv,
        ]);
    }

    private function seedProductOnlySale(string $code, int $correlative, int $clientId, int $userId, int $paymentTypeId, array $lines, string $status): void
    {
        $sale = Sale::updateOrCreate(
            ['code' => $code],
            [
                'document_type' => 'boleta',
                'series' => 'B001',
                'correlative' => $correlative,
                'client_id' => $clientId,
                'user_id' => $userId,
                'payment_type_id' => $paymentTypeId,
                'status' => $status,
                'total' => 0,
                'subtotal' => 0,
                'igv' => 0,
            ]
        );

        SaleItem::where('sale_id', $sale->id)->delete();
        RoomRental::where('sale_id', $sale->id)->delete();

        $total = 0;

        foreach ($lines as $line) {
            $product = $line['product'];
            $qty = (int) $line['quantity'];
            $subtotal = round($qty * (float) $product->price, 2);

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        [$base, $igv] = $this->splitTax($total);
        $sale->update([
            'total' => $total,
            'subtotal' => $base,
            'igv' => $igv,
        ]);
    }

    private function splitTax(float $total): array
    {
        $base = round($total / 1.18, 2);
        $igv = round($total - $base, 2);

        return [$base, $igv];
    }
}
