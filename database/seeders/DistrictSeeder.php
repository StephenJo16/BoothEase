<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all cities and ensure each has at least one district
        $allCities = DB::table('cities')->get();

        // Predefined districts for specific cities
        $districts = [
            // Jakarta Selatan
            ['city_code' => '3171', 'code' => '3171010', 'name' => 'KEBAYORAN BARU'],
            ['city_code' => '3171', 'code' => '3171020', 'name' => 'KEBAYORAN LAMA'],
            ['city_code' => '3171', 'code' => '3171030', 'name' => 'PESANGGRAHAN'],
            ['city_code' => '3171', 'code' => '3171040', 'name' => 'CILANDAK'],
            ['city_code' => '3171', 'code' => '3171050', 'name' => 'PASAR MINGGU'],
            ['city_code' => '3171', 'code' => '3171060', 'name' => 'JAGAKARSA'],
            ['city_code' => '3171', 'code' => '3171070', 'name' => 'MAMPANG PRAPATAN'],
            ['city_code' => '3171', 'code' => '3171080', 'name' => 'PANCORAN'],
            ['city_code' => '3171', 'code' => '3171090', 'name' => 'TEBET'],
            ['city_code' => '3171', 'code' => '3171100', 'name' => 'SETIABUDI'],

            // Jakarta Timur
            ['city_code' => '3172', 'code' => '3172010', 'name' => 'MATRAMAN'],
            ['city_code' => '3172', 'code' => '3172020', 'name' => 'PULOGADUNG'],
            ['city_code' => '3172', 'code' => '3172030', 'name' => 'JATINEGARA'],
            ['city_code' => '3172', 'code' => '3172040', 'name' => 'KRAMATJATI'],
            ['city_code' => '3172', 'code' => '3172050', 'name' => 'PASAR REBO'],

            // Jakarta Pusat
            ['city_code' => '3173', 'code' => '3173010', 'name' => 'TANAH ABANG'],
            ['city_code' => '3173', 'code' => '3173020', 'name' => 'MENTENG'],
            ['city_code' => '3173', 'code' => '3173030', 'name' => 'SENEN'],
            ['city_code' => '3173', 'code' => '3173040', 'name' => 'JOHAR BARU'],
            ['city_code' => '3173', 'code' => '3173050', 'name' => 'CEMPAKA PUTIH'],

            // Jakarta Barat
            ['city_code' => '3174', 'code' => '3174010', 'name' => 'CENGKARENG'],
            ['city_code' => '3174', 'code' => '3174020', 'name' => 'GROGOL PETAMBURAN'],
            ['city_code' => '3174', 'code' => '3174030', 'name' => 'TAMAN SARI'],
            ['city_code' => '3174', 'code' => '3174040', 'name' => 'TAMBORA'],
            ['city_code' => '3174', 'code' => '3174050', 'name' => 'KEBON JERUK'],

            // Jakarta Utara
            ['city_code' => '3175', 'code' => '3175010', 'name' => 'PENJARINGAN'],
            ['city_code' => '3175', 'code' => '3175020', 'name' => 'PADEMANGAN'],
            ['city_code' => '3175', 'code' => '3175030', 'name' => 'TANJUNG PRIOK'],
            ['city_code' => '3175', 'code' => '3175040', 'name' => 'KOJA'],
            ['city_code' => '3175', 'code' => '3175050', 'name' => 'KELAPA GADING'],

            // Kota Bandung
            ['city_code' => '3273', 'code' => '3273010', 'name' => 'BANDUNG KULON'],
            ['city_code' => '3273', 'code' => '3273020', 'name' => 'BABAKAN CIPARAY'],
            ['city_code' => '3273', 'code' => '3273030', 'name' => 'BOJONGLOA KALER'],
            ['city_code' => '3273', 'code' => '3273040', 'name' => 'BOJONGLOA KIDUL'],
            ['city_code' => '3273', 'code' => '3273050', 'name' => 'ASTANA ANYAR'],
            ['city_code' => '3273', 'code' => '3273060', 'name' => 'REGOL'],
            ['city_code' => '3273', 'code' => '3273070', 'name' => 'LENGKONG'],
            ['city_code' => '3273', 'code' => '3273080', 'name' => 'BANDUNG KIDUL'],
            ['city_code' => '3273', 'code' => '3273090', 'name' => 'BUAHBATU'],
            ['city_code' => '3273', 'code' => '3273100', 'name' => 'RANCASARI'],

            // Kota Yogyakarta
            ['city_code' => '3471', 'code' => '3471010', 'name' => 'MANTRIJERON'],
            ['city_code' => '3471', 'code' => '3471020', 'name' => 'KRATON'],
            ['city_code' => '3471', 'code' => '3471030', 'name' => 'MERGANGSAN'],
            ['city_code' => '3471', 'code' => '3471040', 'name' => 'UMBULHARJO'],
            ['city_code' => '3471', 'code' => '3471050', 'name' => 'KOTAGEDE'],
            ['city_code' => '3471', 'code' => '3471060', 'name' => 'GONDOKUSUMAN'],
            ['city_code' => '3471', 'code' => '3471070', 'name' => 'DANUREJAN'],
            ['city_code' => '3471', 'code' => '3471080', 'name' => 'PAKUALAMAN'],
            ['city_code' => '3471', 'code' => '3471090', 'name' => 'GONDOMANAN'],
            ['city_code' => '3471', 'code' => '3471100', 'name' => 'NGAMPILAN'],
            ['city_code' => '3471', 'code' => '3471110', 'name' => 'WIROBRAJAN'],
            ['city_code' => '3471', 'code' => '3471120', 'name' => 'GEDONGTENGEN'],
            ['city_code' => '3471', 'code' => '3471130', 'name' => 'JETIS'],
            ['city_code' => '3471', 'code' => '3471140', 'name' => 'TEGALREJO'],
        ];

        // Track which cities have districts
        $citiesWithDistricts = [];

        foreach ($districts as $district) {
            $cityId = DB::table('cities')->where('code', $district['city_code'])->value('id');

            if ($cityId) {
                DB::table('districts')->insert([
                    'city_id' => $cityId,
                    'code' => $district['code'],
                    'name' => $district['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $citiesWithDistricts[] = $district['city_code'];
            }
        }

        // Add default district for cities that don't have any
        foreach ($allCities as $city) {
            if (!in_array($city->code, $citiesWithDistricts)) {
                DB::table('districts')->insert([
                    'city_id' => $city->id,
                    'code' => $city->code . '01',
                    'name' => 'DISTRICT 1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
