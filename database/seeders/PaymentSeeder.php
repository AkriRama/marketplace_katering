<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\Schema;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Payment::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            'BRI', 'BCA', 'Tunai'
        ];

        foreach($data as $value)
        {
            Payment::insert([
                'name' => $value
            ]);
        }
    }
}
