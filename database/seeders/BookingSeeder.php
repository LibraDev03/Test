<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // TRUNCATE
        Schema::disableForeignKeyConstraints();
        DB::table('bookings')->truncate();
        Schema::enableForeignKeyConstraints();

        $booking_data = [];

        for ($i = 1; $i <= 50; $i++) {

            // tên lặp từ 1 → 10
            $nameIndex = ($i - 1) % 10 + 1;

            $checkin = new DateTime();
            $checkin->modify("+$i days");

            $checkout = clone $checkin;
            $checkout->modify('+1 day');

            // số điện thoại random 090xxxxxxx
            $randomPhone = '090' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);

            $booking_data[] = [
                'hotel_id' => rand(1, 10),  // hotel_id 1 → 10
                'customer_name' => 'HoangSon' . $nameIndex,
                'customer_contact' => $randomPhone,
                'checkin_time' => $checkin,
                'checkout_time' => $checkout,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ];
        }

        // insert dữ liệu
        foreach ($booking_data as $value) {
            DB::table('bookings')->insert($value);
        }
    }
}