<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Supplier Jaya',
                'email' => 'info@supplierjaya.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12000',
                'notes' => 'Supplier utama untuk barang elektronik',
            ],
            [
                'name' => 'CV Distributor Maju',
                'email' => 'sales@distributormaju.com',
                'phone' => '082345678901',
                'address' => 'Jl. Sudirman No. 456',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60000',
                'notes' => 'Spesialis pakaian dan tekstil',
            ],
            [
                'name' => 'Toko Grosir Sentosa',
                'email' => 'contact@tokosentosa.com',
                'phone' => '083456789012',
                'address' => 'Jl. Hayam Wuruk No. 789',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40000',
                'notes' => 'Grosir dengan harga kompetitif',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
