<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for major cities/regencies (at least one per province)
        $cities = [
            // Aceh
            ['province_code' => '11', 'code' => '1101', 'name' => 'Kab. Aceh Selatan'],
            ['province_code' => '11', 'code' => '1171', 'name' => 'Kota Banda Aceh'],

            // Sumatera Utara
            ['province_code' => '12', 'code' => '1201', 'name' => 'Kab. Tapanuli Tengah'],
            ['province_code' => '12', 'code' => '1271', 'name' => 'Kota Medan'],

            // Sumatera Barat
            ['province_code' => '13', 'code' => '1301', 'name' => 'Kab. Pesisir Selatan'],
            ['province_code' => '13', 'code' => '1371', 'name' => 'Kota Padang'],

            // Riau
            ['province_code' => '14', 'code' => '1401', 'name' => 'Kab. Kampar'],
            ['province_code' => '14', 'code' => '1471', 'name' => 'Kota Pekanbaru'],

            // Jambi
            ['province_code' => '15', 'code' => '1501', 'name' => 'Kab. Kerinci'],
            ['province_code' => '15', 'code' => '1571', 'name' => 'Kota Jambi'],

            // Sumatera Selatan
            ['province_code' => '16', 'code' => '1601', 'name' => 'Kab. Ogan Komering Ulu'],
            ['province_code' => '16', 'code' => '1671', 'name' => 'Kota Palembang'],

            // Bengkulu
            ['province_code' => '17', 'code' => '1701', 'name' => 'Kab. Bengkulu Selatan'],
            ['province_code' => '17', 'code' => '1771', 'name' => 'Kota Bengkulu'],

            // Lampung
            ['province_code' => '18', 'code' => '1801', 'name' => 'Kab. Lampung Selatan'],
            ['province_code' => '18', 'code' => '1871', 'name' => 'Kota Bandar Lampung'],

            // Kepulauan Bangka Belitung
            ['province_code' => '19', 'code' => '1901', 'name' => 'Kab. Bangka'],
            ['province_code' => '19', 'code' => '1971', 'name' => 'Kota Pangkal Pinang'],

            // Kepulauan Riau
            ['province_code' => '21', 'code' => '2101', 'name' => 'Kab. Bintan'],
            ['province_code' => '21', 'code' => '2171', 'name' => 'Kota Batam'],

            // DKI Jakarta
            ['province_code' => '31', 'code' => '3101', 'name' => 'Kab. Kepulauan Seribu'],
            ['province_code' => '31', 'code' => '3171', 'name' => 'Kota Jakarta Selatan'],
            ['province_code' => '31', 'code' => '3172', 'name' => 'Kota Jakarta Timur'],
            ['province_code' => '31', 'code' => '3173', 'name' => 'Kota Jakarta Pusat'],
            ['province_code' => '31', 'code' => '3174', 'name' => 'Kota Jakarta Barat'],
            ['province_code' => '31', 'code' => '3175', 'name' => 'Kota Jakarta Utara'],

            // Jawa Barat
            ['province_code' => '32', 'code' => '3201', 'name' => 'Kab. Bogor'],
            ['province_code' => '32', 'code' => '3202', 'name' => 'Kab. Sukabumi'],
            ['province_code' => '32', 'code' => '3203', 'name' => 'Kab. Cianjur'],
            ['province_code' => '32', 'code' => '3204', 'name' => 'Kab. Bandung'],
            ['province_code' => '32', 'code' => '3273', 'name' => 'Kota Bandung'],
            ['province_code' => '32', 'code' => '3275', 'name' => 'Kota Bekasi'],
            ['province_code' => '32', 'code' => '3276', 'name' => 'Kota Depok'],
            ['province_code' => '32', 'code' => '3277', 'name' => 'Kota Cimahi'],
            ['province_code' => '32', 'code' => '3278', 'name' => 'Kota Tasikmalaya'],
            ['province_code' => '32', 'code' => '3279', 'name' => 'Kota Banjar'],

            // Jawa Tengah
            ['province_code' => '33', 'code' => '3301', 'name' => 'Kab. Cilacap'],
            ['province_code' => '33', 'code' => '3302', 'name' => 'Kab. Banyumas'],
            ['province_code' => '33', 'code' => '3371', 'name' => 'Kota Magelang'],
            ['province_code' => '33', 'code' => '3372', 'name' => 'Kota Surakarta'],
            ['province_code' => '33', 'code' => '3373', 'name' => 'Kota Salatiga'],
            ['province_code' => '33', 'code' => '3374', 'name' => 'Kota Semarang'],
            ['province_code' => '33', 'code' => '3375', 'name' => 'Kota Pekalongan'],
            ['province_code' => '33', 'code' => '3376', 'name' => 'Kota Tegal'],

            // DI Yogyakarta
            ['province_code' => '34', 'code' => '3401', 'name' => 'Kab. Kulon Progo'],
            ['province_code' => '34', 'code' => '3402', 'name' => 'Kab. Bantul'],
            ['province_code' => '34', 'code' => '3403', 'name' => 'Kab. Gunungkidul'],
            ['province_code' => '34', 'code' => '3404', 'name' => 'Kab. Sleman'],
            ['province_code' => '34', 'code' => '3471', 'name' => 'Kota Yogyakarta'],

            // Jawa Timur
            ['province_code' => '35', 'code' => '3501', 'name' => 'Kab. Pacitan'],
            ['province_code' => '35', 'code' => '3502', 'name' => 'Kab. Ponorogo'],
            ['province_code' => '35', 'code' => '3578', 'name' => 'Kota Surabaya'],
            ['province_code' => '35', 'code' => '3579', 'name' => 'Kota Malang'],

            // Banten
            ['province_code' => '36', 'code' => '3601', 'name' => 'Kab. Pandeglang'],
            ['province_code' => '36', 'code' => '3602', 'name' => 'Kab. Lebak'],
            ['province_code' => '36', 'code' => '3603', 'name' => 'Kab. Tangerang'],
            ['province_code' => '36', 'code' => '3604', 'name' => 'Kab. Serang'],
            ['province_code' => '36', 'code' => '3671', 'name' => 'Kota Tangerang'],
            ['province_code' => '36', 'code' => '3672', 'name' => 'Kota Cilegon'],
            ['province_code' => '36', 'code' => '3673', 'name' => 'Kota Serang'],
            ['province_code' => '36', 'code' => '3674', 'name' => 'Kota Tangerang Selatan'],

            // Bali
            ['province_code' => '51', 'code' => '5101', 'name' => 'Kab. Jembrana'],
            ['province_code' => '51', 'code' => '5102', 'name' => 'Kab. Tabanan'],
            ['province_code' => '51', 'code' => '5103', 'name' => 'Kab. Badung'],
            ['province_code' => '51', 'code' => '5104', 'name' => 'Kab. Gianyar'],
            ['province_code' => '51', 'code' => '5171', 'name' => 'Kota Denpasar'],

            // Nusa Tenggara Barat
            ['province_code' => '52', 'code' => '5201', 'name' => 'Kab. Lombok Barat'],
            ['province_code' => '52', 'code' => '5271', 'name' => 'Kota Mataram'],

            // Nusa Tenggara Timur
            ['province_code' => '53', 'code' => '5301', 'name' => 'Kab. Kupang'],
            ['province_code' => '53', 'code' => '5371', 'name' => 'Kota Kupang'],

            // Kalimantan Barat
            ['province_code' => '61', 'code' => '6101', 'name' => 'Kab. Sambas'],
            ['province_code' => '61', 'code' => '6171', 'name' => 'Kota Pontianak'],

            // Kalimantan Tengah
            ['province_code' => '62', 'code' => '6201', 'name' => 'Kab. Kotawaringin Barat'],
            ['province_code' => '62', 'code' => '6271', 'name' => 'Kota Palangkaraya'],

            // Kalimantan Selatan
            ['province_code' => '63', 'code' => '6301', 'name' => 'Kab. Tanah Laut'],
            ['province_code' => '63', 'code' => '6371', 'name' => 'Kota Banjarmasin'],

            // Kalimantan Timur
            ['province_code' => '64', 'code' => '6401', 'name' => 'Kab. Paser'],
            ['province_code' => '64', 'code' => '6471', 'name' => 'Kota Balikpapan'],

            // Kalimantan Utara
            ['province_code' => '65', 'code' => '6501', 'name' => 'Kab. Bulungan'],
            ['province_code' => '65', 'code' => '6571', 'name' => 'Kota Tarakan'],

            // Sulawesi Utara
            ['province_code' => '71', 'code' => '7101', 'name' => 'Kab. Bolaang Mongondow'],
            ['province_code' => '71', 'code' => '7171', 'name' => 'Kota Manado'],

            // Sulawesi Tengah
            ['province_code' => '72', 'code' => '7201', 'name' => 'Kab. Banggai'],
            ['province_code' => '72', 'code' => '7271', 'name' => 'Kota Palu'],

            // Sulawesi Selatan
            ['province_code' => '73', 'code' => '7301', 'name' => 'Kab. Kepulauan Selayar'],
            ['province_code' => '73', 'code' => '7371', 'name' => 'Kota Makassar'],

            // Sulawesi Tenggara
            ['province_code' => '74', 'code' => '7401', 'name' => 'Kab. Kolaka'],
            ['province_code' => '74', 'code' => '7471', 'name' => 'Kota Kendari'],

            // Gorontalo
            ['province_code' => '75', 'code' => '7501', 'name' => 'Kab. Gorontalo'],
            ['province_code' => '75', 'code' => '7571', 'name' => 'Kota Gorontalo'],

            // Sulawesi Barat
            ['province_code' => '76', 'code' => '7601', 'name' => 'Kab. Mamuju Utara'],
            ['province_code' => '76', 'code' => '7602', 'name' => 'Kab. Mamuju'],

            // Maluku
            ['province_code' => '81', 'code' => '8101', 'name' => 'Kab. Maluku Tengah'],
            ['province_code' => '81', 'code' => '8171', 'name' => 'Kota Ambon'],

            // Maluku Utara
            ['province_code' => '82', 'code' => '8201', 'name' => 'Kab. Halmahera Barat'],
            ['province_code' => '82', 'code' => '8271', 'name' => 'Kota Ternate'],

            // Papua Barat
            ['province_code' => '91', 'code' => '9101', 'name' => 'Kab. Sorong'],
            ['province_code' => '91', 'code' => '9171', 'name' => 'Kota Sorong'],

            // Papua
            ['province_code' => '94', 'code' => '9401', 'name' => 'Kab. Merauke'],
            ['province_code' => '94', 'code' => '9471', 'name' => 'Kota Jayapura'],
        ];

        foreach ($cities as $city) {
            $provinceId = DB::table('provinces')->where('code', $city['province_code'])->value('id');

            if ($provinceId) {
                DB::table('cities')->insert([
                    'province_id' => $provinceId,
                    'code' => $city['code'],
                    'name' => $city['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
