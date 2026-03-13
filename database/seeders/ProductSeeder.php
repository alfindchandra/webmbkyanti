<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // 1. UNITS
        //    Sesuaikan dengan migration: units(id, name, code, is_base, ...)
        // =====================================================================
        $units = [
            ['name' => 'Pieces',     'code' => 'pcs', 'is_base' => true],
            ['name' => 'Sachet',     'code' => 'sct', 'is_base' => true],
            ['name' => 'Kilogram',   'code' => 'kg',  'is_base' => true],
            ['name' => 'Gram',       'code' => 'g',   'is_base' => false],
            ['name' => 'Mililiter',  'code' => 'ml',  'is_base' => false],
            ['name' => 'Liter',      'code' => 'ltr', 'is_base' => false],
            ['name' => 'Ons',        'code' => 'ons', 'is_base' => false],
            ['name' => 'Setengah Kg','code' => '1/2kg','is_base' => false],
            ['name' => 'Seperempat Kg','code' => '1/4kg','is_base' => false],
        ];

        $unitMap = [];
        foreach ($units as $u) {
            $unit = Unit::firstOrCreate(
                ['code' => $u['code']],
                ['name' => $u['name'], 'is_base' => $u['is_base']]
            );
            $unitMap[$u['code']] = $unit;
        }

        // =====================================================================
        // 2. CATEGORIES
        //    categories(id, name, slug, ...)
        // =====================================================================
        $categoryNames = [
            'Sabun cuci', 'Obat Nyamuk', 'Pewangi', 'Sampo', 'Sabun Mandi',
            'Odol', 'Sikat Gigi', 'Perawatan Tubuh', 'Popok Bayi', 'Pembalut',
            'Kue', 'Rumah Tangga', 'Rokok', 'Bahan Pokok', 'Minuman',
            'Makanan', 'Wur', 'Telur',
        ];

        $categoryMap = [];
        foreach ($categoryNames as $name) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $categoryMap[$name] = $cat;
        }

        // =====================================================================
        // 3. PRODUK BIASA (1 produk = 1 unit)
        //    Format: ['sku', 'name', 'category', 'cost_price', 'price', 'unit']
        //    product_units: conversion=1, min_qty=1
        // =====================================================================
        $simpleProducts = [
            // --- Sabun Cuci ---
            ['sku' => 'RAK-D0101', 'name' => 'Soklin Deterjen Bubuk 720g',      'category' => 'Sabun cuci',      'cost' => 17000,  'price' => 20000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0102', 'name' => 'Daia Deterjen Bubuk 720g',        'category' => 'Sabun cuci',      'cost' => 17000,  'price' => 20000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0103', 'name' => 'Soklin Deterjen Bubuk 455g',      'category' => 'Sabun cuci',      'cost' => 8500,   'price' => 10000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0104', 'name' => 'Daia Deterjen Bubuk 455g',        'category' => 'Sabun cuci',      'cost' => 8500,   'price' => 10000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0105', 'name' => 'Soklin Deterjen Bubuk 245g',      'category' => 'Sabun cuci',      'cost' => 8500,   'price' => 10000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0106', 'name' => 'Daia Deterjen Bubuk 245g',        'category' => 'Sabun cuci',      'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0107', 'name' => 'Rinso Deterjen Bubuk 245g',       'category' => 'Sabun cuci',      'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0108', 'name' => 'Soklin Deterjen Bubuk 46g',       'category' => 'Sabun cuci',      'cost' => 800,    'price' => 1000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0109', 'name' => 'Daia Deterjen Bubuk 46g',         'category' => 'Sabun cuci',      'cost' => 800,    'price' => 1000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0110', 'name' => 'Rinso Deterjen Bubuk 46g',        'category' => 'Sabun cuci',      'cost' => 800,    'price' => 1000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0111', 'name' => 'Sayang Deterjen Bubuk 260g',      'category' => 'Sabun cuci',      'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0112', 'name' => 'Smart Deterjen Bubuk 1.6kg',      'category' => 'Sabun cuci',      'cost' => 19000,  'price' => 22000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0113', 'name' => 'Gentle Gen Deterjen Cair 80ml',   'category' => 'Sabun cuci',      'cost' => 800,    'price' => 1000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0114', 'name' => 'Garam Asam Bubuk 50g',            'category' => 'Sabun cuci',      'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0203', 'name' => 'Sosol Deterjen Cair 80ml',        'category' => 'Sabun cuci',      'cost' => 800,    'price' => 1000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0204', 'name' => 'Soklin Deterjen Cair 20ml',       'category' => 'Sabun cuci',      'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0205', 'name' => 'Rinso Deterjen Cair 20ml',        'category' => 'Sabun cuci',      'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            // --- Obat Nyamuk ---
            ['sku' => 'RAK-D0201', 'name' => 'Autan Lotion Anti Nyamuk 12ml',   'category' => 'Obat Nyamuk',     'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0202', 'name' => 'Sofel Lotion Anti Nyamuk 13ml',   'category' => 'Obat Nyamuk',     'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-C0401', 'name' => 'Obat Nyamuk Sapi Kuning',         'category' => 'Obat Nyamuk',     'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0402', 'name' => 'Obat Nyamuk Sapi Hitam',          'category' => 'Obat Nyamuk',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0403', 'name' => 'Obat Nyamuk Vape',                'category' => 'Obat Nyamuk',     'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0404', 'name' => 'Obat Nyamuk Kingkong',            'category' => 'Obat Nyamuk',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0405', 'name' => 'Obat Nyamuk Bakar Baygon',        'category' => 'Obat Nyamuk',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0406', 'name' => 'Baygon Silky Lavender 400ml',     'category' => 'Obat Nyamuk',     'cost' => 25000,  'price' => 30000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0407', 'name' => 'Baygon Japanese Peach 600ml',     'category' => 'Obat Nyamuk',     'cost' => 29000,  'price' => 35000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0408', 'name' => 'Hit Anti Nyamuk 400ml',           'category' => 'Obat Nyamuk',     'cost' => 22000,  'price' => 28000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0409', 'name' => 'Hit Anti Nyamuk Elektrik 45 Hari','category' => 'Obat Nyamuk',     'cost' => 17000,  'price' => 21000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0410', 'name' => 'Vape Mat 4.1 MV 21',              'category' => 'Obat Nyamuk',     'cost' => 4500,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0411', 'name' => 'Hit Mat 18+12',                   'category' => 'Obat Nyamuk',     'cost' => 5500,   'price' => 7000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0412', 'name' => 'Hit Piramida',                    'category' => 'Obat Nyamuk',     'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0413', 'name' => 'Vape Elektrik Ekonomis',          'category' => 'Obat Nyamuk',     'cost' => 10000,  'price' => 12000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0414', 'name' => 'Hit Elektrik Ekonomis 6mat',      'category' => 'Obat Nyamuk',     'cost' => 9000,   'price' => 11000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0415', 'name' => 'Hit Anti Nyamuk 180ml',           'category' => 'Obat Nyamuk',     'cost' => 14000,  'price' => 18000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0416', 'name' => 'Hit Refill 45 Hari',              'category' => 'Obat Nyamuk',     'cost' => 13000,  'price' => 16500,  'unit' => 'pcs'],
            // --- Pewangi ---
            ['sku' => 'RAK-D0206', 'name' => 'Downy Pewangi Pakaian 8ml',       'category' => 'Pewangi',         'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0207', 'name' => 'Molto Pewangi Pakaian 11ml',      'category' => 'Pewangi',         'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0208', 'name' => 'Royale Pewangi Pakaian 13ml',     'category' => 'Pewangi',         'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0209', 'name' => 'Soklin Softener 13ml',            'category' => 'Pewangi',         'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            // --- Sampo ---
            ['sku' => 'RAK-D0210', 'name' => 'Pantene Sampo Sachet 9ml',        'category' => 'Sampo',           'cost' => 800,    'price' => 1000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0211', 'name' => 'Dove Sampo Sachet 8ml',           'category' => 'Sampo',           'cost' => 800,    'price' => 1000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0212', 'name' => 'Rejoice Sampo Sachet 10ml',       'category' => 'Sampo',           'cost' => 800,    'price' => 1000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0213', 'name' => 'Clear Sampo Sachet 10ml',         'category' => 'Sampo',           'cost' => 800,    'price' => 1000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0214', 'name' => 'Emeron Sampo Sachet 11ml',        'category' => 'Sampo',           'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0215', 'name' => 'Sunsilk Sampo Sachet 12ml',       'category' => 'Sampo',           'cost' => 800,    'price' => 1000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0216', 'name' => 'Zinc Sampo Sachet 13ml',          'category' => 'Sampo',           'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            ['sku' => 'RAK-D0217', 'name' => 'Lifebuoy Sampo Sachet 14ml',      'category' => 'Sampo',           'cost' => 400,    'price' => 500,    'unit' => 'sct'],
            // --- Sabun Mandi ---
            ['sku' => 'RAK-D0301', 'name' => 'Shinzui Sabun Batang 80g',        'category' => 'Sabun Mandi',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0302', 'name' => 'Lervia Sabun Batang 90g',         'category' => 'Sabun Mandi',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0303', 'name' => 'Sehat Sabun Batang 65g',          'category' => 'Sabun Mandi',     'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0304', 'name' => 'Asepso Sabun Batang 80g',         'category' => 'Sabun Mandi',     'cost' => 5000,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0305', 'name' => 'Harmony Sabun Batang 70g',        'category' => 'Sabun Mandi',     'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0306', 'name' => 'Ayu Sabun Batang 65g',            'category' => 'Sabun Mandi',     'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0307', 'name' => 'Nuvo Sabun Batang 72g',           'category' => 'Sabun Mandi',     'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0308', 'name' => 'Medicare Sabun Batang 73g',       'category' => 'Sabun Mandi',     'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0309', 'name' => 'Zwitsal Sabun Batang 74g',        'category' => 'Sabun Mandi',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0310', 'name' => 'Citra Sabun Batang 75g',          'category' => 'Sabun Mandi',     'cost' => 3000,   'price' => 3500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0311', 'name' => 'My Baby Sabun Batang 76g',        'category' => 'Sabun Mandi',     'cost' => 5000,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0312', 'name' => 'Lifebuoy Sabun Batang 77g',       'category' => 'Sabun Mandi',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0313', 'name' => 'Cussons Baby Sabun Batang 78g',   'category' => 'Sabun Mandi',     'cost' => 5000,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0314', 'name' => 'Lux Sabun Batang 79g',            'category' => 'Sabun Mandi',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0315', 'name' => 'Zen Sabun Batang 80g',            'category' => 'Sabun Mandi',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0316', 'name' => 'Pepaya Sabun Batang 81g',         'category' => 'Sabun Mandi',     'cost' => 6000,   'price' => 7500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0317', 'name' => 'Marina Sabun Batang 82g',         'category' => 'Sabun Mandi',     'cost' => 3000,   'price' => 3500,   'unit' => 'pcs'],
            // --- Odol ---
            ['sku' => 'RAK-D0318', 'name' => 'Pepsodent Pasta Gigi 25g',        'category' => 'Odol',            'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0319', 'name' => 'Pepsodent Pasta Gigi 120g',       'category' => 'Odol',            'cost' => 8500,   'price' => 10000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0320', 'name' => 'Pepsodent Pasta Gigi 75g',        'category' => 'Odol',            'cost' => 5000,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0321', 'name' => 'Pepsodent Pasta Gigi 2x190g',     'category' => 'Odol',            'cost' => 20000,  'price' => 23500,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0322', 'name' => 'Pepsodent Herbal 100g',           'category' => 'Odol',            'cost' => 9000,   'price' => 11000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0323', 'name' => 'Pepsodent Biru 75g',              'category' => 'Odol',            'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0324', 'name' => 'Ciptadent Pasta Gigi 75g',        'category' => 'Odol',            'cost' => 5000,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0325', 'name' => 'Ciptadent Pasta Gigi 190g',       'category' => 'Odol',            'cost' => 10000,  'price' => 12000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0326', 'name' => 'Barakat Pasta Gigi 75g',          'category' => 'Odol',            'cost' => 5000,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0327', 'name' => 'Kodomo Pasta Gigi 45g',           'category' => 'Odol',            'cost' => 6500,   'price' => 8000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0328', 'name' => 'Close Up Pasta Gigi 160g',        'category' => 'Odol',            'cost' => 9000,   'price' => 11000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0329', 'name' => 'Barakat Pasta Gigi 190g',         'category' => 'Odol',            'cost' => 11000,  'price' => 13000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0330', 'name' => 'Close Up Pasta Gigi 110g',        'category' => 'Odol',            'cost' => 13000,  'price' => 15000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0331', 'name' => 'Cussons Kids Pasta Gigi 45g',     'category' => 'Odol',            'cost' => 6000,   'price' => 7000,   'unit' => 'pcs'],
            // --- Sikat Gigi ---
            ['sku' => 'RAK-D0332', 'name' => 'Kodomo Sikat Gigi',               'category' => 'Sikat Gigi',      'cost' => 2500,   'price' => 3500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0333', 'name' => 'Oral B Sikat Gigi',               'category' => 'Sikat Gigi',      'cost' => 2500,   'price' => 3500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0334', 'name' => 'Formula Sikat Gigi',              'category' => 'Sikat Gigi',      'cost' => 2500,   'price' => 3500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0335', 'name' => 'Pepsodent Sikat Gigi',            'category' => 'Sikat Gigi',      'cost' => 2500,   'price' => 3500,   'unit' => 'pcs'],
            // --- Perawatan Tubuh ---
            ['sku' => 'RAK-D0336', 'name' => 'Ponds Pelembab Wajah 10g',        'category' => 'Perawatan Tubuh', 'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0337', 'name' => 'Ponds Cuci Muka 10ml',            'category' => 'Perawatan Tubuh', 'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0338', 'name' => 'Lovely Pelembab Wajah 10g',       'category' => 'Perawatan Tubuh', 'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0339', 'name' => 'Lovely Sabun Batang 80g',         'category' => 'Perawatan Tubuh', 'cost' => 2500,   'price' => 3500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0340', 'name' => 'Garnier Cuci Muka Kuning 10ml',   'category' => 'Perawatan Tubuh', 'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0341', 'name' => 'Garnier Cuci Muka Pink 10ml',     'category' => 'Perawatan Tubuh', 'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0342', 'name' => 'Rexona Deodoran Wanita 10ml',     'category' => 'Perawatan Tubuh', 'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0343', 'name' => 'Rexona Deodoran Pria 10ml',       'category' => 'Perawatan Tubuh', 'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            // --- Popok Bayi ---
            ['sku' => 'RAK-D0401', 'name' => 'Moko Moko Popok Bayi',            'category' => 'Popok Bayi',      'cost' => 3000,   'price' => 4000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0402', 'name' => 'Merries Popok Bayi',              'category' => 'Popok Bayi',      'cost' => 4500,   'price' => 6000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0403', 'name' => 'Mama Mia Popok Bayi',             'category' => 'Popok Bayi',      'cost' => 3000,   'price' => 4000,   'unit' => 'sct'],
            ['sku' => 'RAK-D0404', 'name' => 'Baby Happy Popok Bayi',           'category' => 'Popok Bayi',      'cost' => 1500,   'price' => 2000,   'unit' => 'sct'],
            // --- Pembalut ---
            ['sku' => 'RAK-D0405', 'name' => 'Charm Safe Night 29cm 96/48',     'category' => 'Pembalut',        'cost' => 7500,   'price' => 9250,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0406', 'name' => 'Charm Safe Night 35cm 12s/24',    'category' => 'Pembalut',        'cost' => 13000,  'price' => 16250,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0407', 'name' => 'Charm Safe Night 29cm 56s/24',    'category' => 'Pembalut',        'cost' => 7000,   'price' => 9000,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0408', 'name' => 'Charm BF-Ext MX 20/24',           'category' => 'Pembalut',        'cost' => 10500,  'price' => 12900,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0409', 'name' => 'Charm Safe Night 29cm 18s/24',    'category' => 'Pembalut',        'cost' => 13000,  'price' => 15750,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0410', 'name' => 'Charm BF-Ext MX 23/24',           'category' => 'Pembalut',        'cost' => 11000,  'price' => 13800,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0411', 'name' => 'Charm BF-Ext MW 28/24',           'category' => 'Pembalut',        'cost' => 17000,  'price' => 20750,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0412', 'name' => 'Charm BF-Ext MX 30/48',           'category' => 'Pembalut',        'cost' => 24000,  'price' => 29500,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0413', 'name' => 'Charm BF-Ext MW 40/48',           'category' => 'Pembalut',        'cost' => 6000,   'price' => 7750,   'unit' => 'pcs'],
            ['sku' => 'RAK-D0414', 'name' => 'Charm Cooling Fresh MW 29cm 10',  'category' => 'Pembalut',        'cost' => 13000,  'price' => 16000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0415', 'name' => 'Charm Cooling Fresh MW 29cm 12',  'category' => 'Pembalut',        'cost' => 31000,  'price' => 38000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0416', 'name' => 'Charm Cooling Fresh WL 28cm 13',  'category' => 'Pembalut',        'cost' => 13500,  'price' => 16750,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0417', 'name' => 'Charm BF-Ext MW 20/24',           'category' => 'Pembalut',        'cost' => 12000,  'price' => 15000,  'unit' => 'pcs'],
            ['sku' => 'RAK-D0418', 'name' => 'Charm Herbal 26cr Wings 14s/24',  'category' => 'Pembalut',        'cost' => 13000,  'price' => 16000,  'unit' => 'pcs'],
            // --- Kue / Bahan Kue ---
            ['sku' => 'RAK-C0301', 'name' => 'Pewarna Makanan Alco',            'category' => 'Kue',             'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0302', 'name' => 'Morison',                         'category' => 'Kue',             'cost' => 5500,   'price' => 7000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0303', 'name' => 'Soda Kue',                        'category' => 'Kue',             'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0304', 'name' => 'Metega',                          'category' => 'Kue',             'cost' => 3000,   'price' => 3500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0305', 'name' => 'SP',                              'category' => 'Kue',             'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0306', 'name' => 'Jelly Lucky Ball',                'category' => 'Kue',             'cost' => 7000,   'price' => 9000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0307', 'name' => 'Ovalet',                          'category' => 'Kue',             'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0308', 'name' => 'Vanili Cap Dali Botol',           'category' => 'Kue',             'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0309', 'name' => 'Baking Powder Cap Dali Botol',    'category' => 'Kue',             'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0310', 'name' => 'Ragi',                            'category' => 'Kue',             'cost' => 9500,   'price' => 11500,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0311', 'name' => 'Tepung Beras 500g',               'category' => 'Kue',             'cost' => 6000,   'price' => 7500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0312', 'name' => 'Tepung Ketan 500g',               'category' => 'Kue',             'cost' => 9500,   'price' => 11500,  'unit' => 'pcs'],
            // --- Rumah Tangga ---
            ['sku' => 'RAK-C0417', 'name' => 'Kiriko Lem Tikus',                'category' => 'Rumah Tangga',    'cost' => 11000,  'price' => 13500,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0418', 'name' => 'Stella 42+13g',                   'category' => 'Rumah Tangga',    'cost' => 8000,   'price' => 10000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0419', 'name' => 'Stella 200ml',                    'category' => 'Rumah Tangga',    'cost' => 15000,  'price' => 19000,  'unit' => 'pcs'],
            ['sku' => 'RAK-C0420', 'name' => 'Lilin Naga K',                    'category' => 'Rumah Tangga',    'cost' => 1200,   'price' => 1500,   'unit' => 'pcs'],
            ['sku' => 'RAK-C0421', 'name' => 'Lilin Pinguin B',                 'category' => 'Rumah Tangga',    'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            // --- Rokok ---
            ['sku' => 'RAK-E0501', 'name' => '286',                             'category' => 'Rokok',           'cost' => 7500,   'price' => 9000,   'unit' => 'pcs'],
            ['sku' => 'RAK-E0502', 'name' => '234 Hijau (Kretek)',              'category' => 'Rokok',           'cost' => 17500,  'price' => 20000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0503', 'name' => '234 Premium 12',                  'category' => 'Rokok',           'cost' => 19000,  'price' => 22000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0504', 'name' => '234 Premium 16',                  'category' => 'Rokok',           'cost' => 23000,  'price' => 26000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0505', 'name' => '76 Kretek 10',                    'category' => 'Rokok',           'cost' => 10000,  'price' => 12000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0506', 'name' => '76 Kretek 12',                    'category' => 'Rokok',           'cost' => 13500,  'price' => 16000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0507', 'name' => '76 Madu',                         'category' => 'Rokok',           'cost' => 12000,  'price' => 14000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0508', 'name' => '76 Mangga',                       'category' => 'Rokok',           'cost' => 12000,  'price' => 14000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0509', 'name' => 'A Mild Mentol',                   'category' => 'Rokok',           'cost' => 30000,  'price' => 34000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0510', 'name' => 'Aga+',                            'category' => 'Rokok',           'cost' => 14000,  'price' => 16000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0511', 'name' => 'A-Mild 12',                       'category' => 'Rokok',           'cost' => 28000,  'price' => 32000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0512', 'name' => 'A-Mild 16',                       'category' => 'Rokok',           'cost' => 30000,  'price' => 34000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0513', 'name' => 'AO Bold',                         'category' => 'Rokok',           'cost' => 14500,  'price' => 17000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0514', 'name' => 'Apache Filter',                   'category' => 'Rokok',           'cost' => 18000,  'price' => 21000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0515', 'name' => 'Apache Kretek',                   'category' => 'Rokok',           'cost' => 9000,   'price' => 11000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0516', 'name' => 'Chift Filter',                    'category' => 'Rokok',           'cost' => 14500,  'price' => 17000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0517', 'name' => 'Country',                         'category' => 'Rokok',           'cost' => 23500,  'price' => 27000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0518', 'name' => 'Dunhill Hitam',                   'category' => 'Rokok',           'cost' => 25500,  'price' => 29000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0519', 'name' => 'Dunhill Putih',                   'category' => 'Rokok',           'cost' => 25500,  'price' => 29000,  'unit' => 'pcs'],
            ['sku' => 'RAK-E0520', 'name' => 'Ferro',                           'category' => 'Rokok',           'cost' => 17000,  'price' => 20000,  'unit' => 'pcs'],
            // --- Bahan Pokok ---
            ['sku' => 'RAK-C0201', 'name' => 'Terigu 1/4 Kg',                   'category' => 'Bahan Pokok',     'cost' => 2000,   'price' => 2500,   'unit' => 'kg'],
            ['sku' => 'RAK-C0202', 'name' => 'Terigu 1/2 Kg',                   'category' => 'Bahan Pokok',     'cost' => 3500,   'price' => 4500,   'unit' => 'kg'],
            ['sku' => 'RAK-C0203', 'name' => 'Terigu 1 Kg',                     'category' => 'Bahan Pokok',     'cost' => 7000,   'price' => 8500,   'unit' => 'kg'],
            ['sku' => 'RAK-C0204', 'name' => 'Terigu Cakra Kembar 1 Kg',        'category' => 'Bahan Pokok',     'cost' => 10000,  'price' => 12000,  'unit' => 'kg'],
            ['sku' => 'RAK-C0205', 'name' => 'Terigu Lencana Merah 1 Kg',       'category' => 'Bahan Pokok',     'cost' => 9000,   'price' => 11000,  'unit' => 'kg'],
            ['sku' => 'RAK-C0206', 'name' => 'Terigu Segitiga Biru 1 Kg',       'category' => 'Bahan Pokok',     'cost' => 10500,  'price' => 12500,  'unit' => 'kg'],
            ['sku' => 'RAK-C0207', 'name' => 'Tepung Maizena',                  'category' => 'Bahan Pokok',     'cost' => 4000,   'price' => 5000,   'unit' => 'kg'],
            ['sku' => 'RAK-C0101', 'name' => 'Kopi 1/4 Kg',                     'category' => 'Bahan Pokok',     'cost' => 19000,  'price' => 22500,  'unit' => 'kg'],
            ['sku' => 'RAK-C0102', 'name' => 'Kopi 1/2 Kg',                     'category' => 'Bahan Pokok',     'cost' => 37000,  'price' => 44000,  'unit' => 'kg'],
            ['sku' => 'RAK-C0103', 'name' => 'Kopi 1 Kg',                       'category' => 'Bahan Pokok',     'cost' => 74000,  'price' => 88000,  'unit' => 'kg'],
            ['sku' => 'RAK-C0104', 'name' => 'Gula 1/4 Kg',                     'category' => 'Bahan Pokok',     'cost' => 3500,   'price' => 4500,   'unit' => 'kg'],
            ['sku' => 'RAK-C0105', 'name' => 'Gula 1/2 Kg',                     'category' => 'Bahan Pokok',     'cost' => 7000,   'price' => 9000,   'unit' => 'kg'],
            ['sku' => 'RAK-C0106', 'name' => 'Gula 1 Kg',                       'category' => 'Bahan Pokok',     'cost' => 14000,  'price' => 17500,  'unit' => 'kg'],
            ['sku' => 'RAK-G0101', 'name' => 'Bawang Merah 1/2 Ons',            'category' => 'Bahan Pokok',     'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0102', 'name' => 'Bawang Merah 1 Ons',              'category' => 'Bahan Pokok',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0103', 'name' => 'Bawang Merah 1/4 Kg',             'category' => 'Bahan Pokok',     'cost' => 6000,   'price' => 8000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0104', 'name' => 'Bawang Merah 1/2 Kg',             'category' => 'Bahan Pokok',     'cost' => 12000,  'price' => 15000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0105', 'name' => 'Bawang Merah 1 Kg',               'category' => 'Bahan Pokok',     'cost' => 24000,  'price' => 28000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0106', 'name' => 'Bawang Putih 1/2 Ons',            'category' => 'Bahan Pokok',     'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0107', 'name' => 'Bawang Putih 1 Ons',              'category' => 'Bahan Pokok',     'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0108', 'name' => 'Bawang Putih 1/4 Kg',             'category' => 'Bahan Pokok',     'cost' => 6500,   'price' => 8000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0109', 'name' => 'Bawang Putih 1/2 Kg',             'category' => 'Bahan Pokok',     'cost' => 13000,  'price' => 16000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0110', 'name' => 'Bawang Putih 1 Kg',               'category' => 'Bahan Pokok',     'cost' => 26000,  'price' => 30000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0111', 'name' => 'Ketumbar 1/2 Ons',                'category' => 'Bahan Pokok',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0112', 'name' => 'Ketumbar 1 Ons',                  'category' => 'Bahan Pokok',     'cost' => 5500,   'price' => 7000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0113', 'name' => 'Ketumbar 1/4 Kg',                 'category' => 'Bahan Pokok',     'cost' => 10000,  'price' => 12500,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0114', 'name' => 'Ketumbar 1/2 Kg',                 'category' => 'Bahan Pokok',     'cost' => 19000,  'price' => 22000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0115', 'name' => 'Ketumbar 1 Kg',                   'category' => 'Bahan Pokok',     'cost' => 37000,  'price' => 44000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0120', 'name' => 'Lombok Garing 1/2 Ons',           'category' => 'Bahan Pokok',     'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0121', 'name' => 'Lombok Garing 1 Ons',             'category' => 'Bahan Pokok',     'cost' => 6000,   'price' => 8000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0122', 'name' => 'Lombok Garing 1/4 Kg',            'category' => 'Bahan Pokok',     'cost' => 15000,  'price' => 19000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0123', 'name' => 'Lombok Garing 1/2 Kg',            'category' => 'Bahan Pokok',     'cost' => 30000,  'price' => 37000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0124', 'name' => 'Lombok Garing 1 Kg',              'category' => 'Bahan Pokok',     'cost' => 60000,  'price' => 75000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0125', 'name' => 'Teri 1/2 Ons',                    'category' => 'Bahan Pokok',     'cost' => 4500,   'price' => 6000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0126', 'name' => 'Teri 1 Ons',                      'category' => 'Bahan Pokok',     'cost' => 9000,   'price' => 11500,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0127', 'name' => 'Teri 1/4 Kg',                     'category' => 'Bahan Pokok',     'cost' => 18000,  'price' => 22500,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0128', 'name' => 'Teri 1/2 Kg',                     'category' => 'Bahan Pokok',     'cost' => 36000,  'price' => 45000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0129', 'name' => 'Teri 1 Kg',                       'category' => 'Bahan Pokok',     'cost' => 72000,  'price' => 90000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0130', 'name' => 'Kerupuk Mentah 1/4 Kg',           'category' => 'Bahan Pokok',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0131', 'name' => 'Kerupuk Mentah 1/2 Kg',           'category' => 'Bahan Pokok',     'cost' => 8000,   'price' => 10000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0132', 'name' => 'Kerupuk Mentah 1 Kg',             'category' => 'Bahan Pokok',     'cost' => 16000,  'price' => 20000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0133', 'name' => 'Kerupuk Mateng 1/4 Kg',           'category' => 'Bahan Pokok',     'cost' => 6000,   'price' => 7500,   'unit' => 'pcs'],
            ['sku' => 'RAK-G0134', 'name' => 'Kerupuk Mateng 1/2 Kg',           'category' => 'Bahan Pokok',     'cost' => 12000,  'price' => 15000,  'unit' => 'pcs'],
            ['sku' => 'RAK-G0135', 'name' => 'Kerupuk Mateng 1 Kg',             'category' => 'Bahan Pokok',     'cost' => 24000,  'price' => 30000,  'unit' => 'pcs'],
            // --- Minuman ---
            ['sku' => 'GDG-A0101', 'name' => 'Ale-Ale',                         'category' => 'Minuman',         'cost' => 1000,   'price' => 1500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0102', 'name' => 'Aqua 1500ml',                     'category' => 'Minuman',         'cost' => 28000,  'price' => 34000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0103', 'name' => 'Aqua Besar 1500ml',               'category' => 'Minuman',         'cost' => 5000,   'price' => 6500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0104', 'name' => 'Aqua Galon Asli',                 'category' => 'Minuman',         'cost' => 18000,  'price' => 22000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0105', 'name' => 'Aqua Mini 330ml',                 'category' => 'Minuman',         'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0106', 'name' => 'Aqua 600ml',                      'category' => 'Minuman',         'cost' => 3500,   'price' => 4500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0107', 'name' => 'Aqua Tanggung 750ml',             'category' => 'Minuman',         'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0108', 'name' => 'Cleo Galon',                      'category' => 'Minuman',         'cost' => 15000,  'price' => 19000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0109', 'name' => 'Cleo Gelas 240ml',                'category' => 'Minuman',         'cost' => 18000,  'price' => 23000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0110', 'name' => 'Cleo Mini 330ml',                 'category' => 'Minuman',         'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0111', 'name' => 'Cleo Tanggung 750ml',             'category' => 'Minuman',         'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0112', 'name' => 'Club Gelas 240ml',                'category' => 'Minuman',         'cost' => 17000,  'price' => 22000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0113', 'name' => 'Es Teler',                        'category' => 'Minuman',         'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0115', 'name' => 'Kopikap',                         'category' => 'Minuman',         'cost' => 1000,   'price' => 1500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0116', 'name' => 'Le Minerale Besar 1500ml',        'category' => 'Minuman',         'cost' => 5000,   'price' => 6500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0117', 'name' => 'Le Minerale Galon',               'category' => 'Minuman',         'cost' => 15000,  'price' => 19000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0118', 'name' => 'Le Minerale Mini 330ml',          'category' => 'Minuman',         'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0119', 'name' => 'Le Minerale Tanggung 600ml',      'category' => 'Minuman',         'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0120', 'name' => 'Malika',                          'category' => 'Minuman',         'cost' => 13000,  'price' => 16000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0121', 'name' => 'Milky Jelly',                     'category' => 'Minuman',         'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0122', 'name' => 'Okky Jelly Big',                  'category' => 'Minuman',         'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0123', 'name' => 'Okky Jelly Kecil',                'category' => 'Minuman',         'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0124', 'name' => 'PureLife Mini 330ml',             'category' => 'Minuman',         'cost' => 2000,   'price' => 2500,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0125', 'name' => 'PureLife Tanggung 600ml',         'category' => 'Minuman',         'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0126', 'name' => 'Rio',                             'category' => 'Minuman',         'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0127', 'name' => 'Teh Bandulan',                    'category' => 'Minuman',         'cost' => 500,    'price' => 1000,   'unit' => 'pcs'],
            // --- Makanan ---
            ['sku' => 'GDG-A0128', 'name' => 'Mie Sedap Goreng',                'category' => 'Makanan',         'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0129', 'name' => 'Mie Sedap Kuah',                  'category' => 'Makanan',         'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0133', 'name' => 'Mie Indomie Goreng',              'category' => 'Makanan',         'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0134', 'name' => 'Mie Indomie Kuah',                'category' => 'Makanan',         'cost' => 2500,   'price' => 3000,   'unit' => 'pcs'],
            // --- Wur ---
            ['sku' => 'GDG-A0130', 'name' => 'Wur Ayam Hijau 91',               'category' => 'Wur',             'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0131', 'name' => 'Wur Ayam Hijau 92',               'category' => 'Wur',             'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0132', 'name' => 'Wur Ayam Merah',                  'category' => 'Wur',             'cost' => 3000,   'price' => 4000,   'unit' => 'pcs'],
            // --- Bahan Pokok lain ---
            ['sku' => 'GDG-A0114', 'name' => 'Garam Kasar',                     'category' => 'Bahan Pokok',     'cost' => 1500,   'price' => 2000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0135', 'name' => 'LPG 3 Kg',                        'category' => 'Bahan Pokok',     'cost' => 18000,  'price' => 20000,  'unit' => 'pcs'],
            ['sku' => 'GDG-A0136', 'name' => 'Mie Buwuhan 1 Bandat',            'category' => 'Bahan Pokok',     'cost' => 4000,   'price' => 5000,   'unit' => 'pcs'],
            ['sku' => 'GDG-A0137', 'name' => 'Mie Buwuhan 1 Biji',              'category' => 'Bahan Pokok',     'cost' => 500,    'price' => 700,    'unit' => 'pcs'],
        ];

        foreach ($simpleProducts as $data) {
            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name'          => $data['name'],
                    'category_id'   => $categoryMap[$data['category']]->id,
                    'base_unit_id'  => $unitMap[$data['unit']]->id,
                    'cost_price'    => $data['cost'],
                    'minimum_stock' => 5,
                    'current_stock' => 10,
                ]
            );

            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitMap[$data['unit']]->id],
                [
                    'conversion' => 1,
                    'price'      => $data['price'],
                    'min_qty'    => 1,
                ]
            );
        }

        // =====================================================================
        // 4. PRODUK DENGAN TINGKATAN UNIT (Multi-Unit Products)
        //
        //    Contoh: Telur — 1 produk, 3 unit berbeda (1 Kg, 1/2 Kg, 1/4 Kg)
        //    Stok dicatat dalam unit dasar (kg).
        //    conversion = berapa unit dasar per 1 unit jual ini.
        //    Misal: 1/4 kg = 0.25 kg → conversion = 0.25
        // =====================================================================
        $multiUnitProducts = [
            // ------------------------------------------------------------------
            // TELUR AYAM
            // base_unit: kg | cost_price per kg = 22.000
            // ------------------------------------------------------------------
            [
                'sku'      => 'RAK-F0101',
                'name'     => 'Telur Ayam',
                'category' => 'Telur',
                'cost'     => 22000,   // harga beli per kg
                'units'    => [
                    // [unit_code, conversion (ke kg), harga jual, min_qty]
                    ['kg',    1.000, 28000, 1],   // Telur 1 Kg    → Rp 28.000
                    ['1/2kg', 0.500, 14500, 1],   // Telur 1/2 Kg  → Rp 14.500
                    ['1/4kg', 0.250,  7500, 1],   // Telur 1/4 Kg  → Rp 7.500
                ],
                'base_unit' => 'kg',
            ],
            // ------------------------------------------------------------------
            // TELUR BEBEK
            // base_unit: kg | cost_price per kg = 25.000
            // ------------------------------------------------------------------
            [
                'sku'      => 'RAK-F0102',
                'name'     => 'Telur Bebek',
                'category' => 'Telur',
                'cost'     => 25000,
                'units'    => [
                    ['kg',    1.000, 32000, 1],   // Telur Bebek 1 Kg   → Rp 32.000
                    ['1/2kg', 0.500, 16500, 1],   // Telur Bebek 1/2 Kg → Rp 16.500
                    ['1/4kg', 0.250,  8500, 1],   // Telur Bebek 1/4 Kg → Rp 8.500
                ],
                'base_unit' => 'kg',
            ],
            // ------------------------------------------------------------------
            // GULA PASIR — 1 produk, terjual dalam satuan berbeda
            // base_unit: kg | cost_price per kg = 13.000
            // ------------------------------------------------------------------
            [
                'sku'      => 'RAK-F0201',
                'name'     => 'Gula Pasir',
                'category' => 'Bahan Pokok',
                'cost'     => 13000,
                'units'    => [
                    ['kg',    1.000, 17500, 1],   // 1 Kg   → Rp 17.500
                    ['1/2kg', 0.500,  9000, 1],   // 1/2 Kg → Rp 9.000
                    ['1/4kg', 0.250,  4500, 1],   // 1/4 Kg → Rp 4.500
                ],
                'base_unit' => 'kg',
            ],
            // ------------------------------------------------------------------
            // BERAS MEDIUM — contoh produk curah lain
            // base_unit: kg | cost_price per kg = 11.000
            // ------------------------------------------------------------------
            [
                'sku'      => 'RAK-F0301',
                'name'     => 'Beras Medium',
                'category' => 'Bahan Pokok',
                'cost'     => 11000,
                'units'    => [
                    ['kg',    1.000, 14000,  1],  // 1 Kg    → Rp 14.000
                    ['1/2kg', 0.500,  7500,  1],  // 1/2 Kg  → Rp 7.500
                    ['1/4kg', 0.250,  4000,  1],  // 1/4 Kg  → Rp 4.000
                ],
                'base_unit' => 'kg',
            ],
        ];

        foreach ($multiUnitProducts as $data) {
            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name'          => $data['name'],
                    'category_id'   => $categoryMap[$data['category']]->id,
                    'base_unit_id'  => $unitMap[$data['base_unit']]->id,
                    'cost_price'    => $data['cost'],
                    'minimum_stock' => 5,
                    'current_stock' => 20,
                ]
            );

            foreach ($data['units'] as [$unitCode, $conversion, $price, $minQty]) {
                ProductUnit::updateOrCreate(
                    ['product_id' => $product->id, 'unit_id' => $unitMap[$unitCode]->id],
                    [
                        'conversion' => $conversion,
                        'price'      => $price,
                        'min_qty'    => $minQty,
                    ]
                );
            }
        }
    }
}