<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubdistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all districts to ensure each has at least one subdistrict
        $allDistricts = DB::table('districts')->get();

        // Predefined subdistricts for specific districts
        $subdistricts = [
            // Kebayoran Baru
            ['district_code' => '3171010', 'code' => '3171010001', 'name' => 'GUNUNG'],
            ['district_code' => '3171010', 'code' => '3171010002', 'name' => 'KRAMAT PELA'],
            ['district_code' => '3171010', 'code' => '3171010003', 'name' => 'GANDARIA UTARA'],
            ['district_code' => '3171010', 'code' => '3171010004', 'name' => 'CIPETE UTARA'],
            ['district_code' => '3171010', 'code' => '3171010005', 'name' => 'PULO'],
            ['district_code' => '3171010', 'code' => '3171010006', 'name' => 'PETOGOGAN'],
            ['district_code' => '3171010', 'code' => '3171010007', 'name' => 'MELAWAI'],
            ['district_code' => '3171010', 'code' => '3171010008', 'name' => 'SELONG'],
            ['district_code' => '3171010', 'code' => '3171010009', 'name' => 'RAWA BARAT'],
            ['district_code' => '3171010', 'code' => '3171010010', 'name' => 'SENAYAN'],

            // Cilandak
            ['district_code' => '3171040', 'code' => '3171040001', 'name' => 'CIPETE SELATAN'],
            ['district_code' => '3171040', 'code' => '3171040002', 'name' => 'GANDARIA SELATAN'],
            ['district_code' => '3171040', 'code' => '3171040003', 'name' => 'CILANDAK BARAT'],
            ['district_code' => '3171040', 'code' => '3171040004', 'name' => 'LEBAK BULUS'],
            ['district_code' => '3171040', 'code' => '3171040005', 'name' => 'PONDOK LABU'],

            // Menteng
            ['district_code' => '3173020', 'code' => '3173020001', 'name' => 'MENTENG'],
            ['district_code' => '3173020', 'code' => '3173020002', 'name' => 'PEGANGSAAN'],
            ['district_code' => '3173020', 'code' => '3173020003', 'name' => 'CIKINI'],
            ['district_code' => '3173020', 'code' => '3173020004', 'name' => 'KENARI'],
            ['district_code' => '3173020', 'code' => '3173020005', 'name' => 'GONDANGDIA'],

            // Kelapa Gading
            ['district_code' => '3175050', 'code' => '3175050001', 'name' => 'KELAPA GADING TIMUR'],
            ['district_code' => '3175050', 'code' => '3175050002', 'name' => 'KELAPA GADING BARAT'],
            ['district_code' => '3175050', 'code' => '3175050003', 'name' => 'PEGANGSAAN DUA'],

            // Bandung Kulon
            ['district_code' => '3273010', 'code' => '3273010001', 'name' => 'GEMPOL SARI'],
            ['district_code' => '3273010', 'code' => '3273010002', 'name' => 'CIGONDEWAH KALER'],
            ['district_code' => '3273010', 'code' => '3273010003', 'name' => 'CIGONDEWAH KIDUL'],
            ['district_code' => '3273010', 'code' => '3273010004', 'name' => 'CIGONDEWAH RAHAYU'],
            ['district_code' => '3273010', 'code' => '3273010005', 'name' => 'CIJERAH'],
            ['district_code' => '3273010', 'code' => '3273010006', 'name' => 'WARUNG MUNCANG'],
            ['district_code' => '3273010', 'code' => '3273010007', 'name' => 'CARINGIN'],

            // Lengkong
            ['district_code' => '3273070', 'code' => '3273070001', 'name' => 'CIKAWAO'],
            ['district_code' => '3273070', 'code' => '3273070002', 'name' => 'LINGKAR SELATAN'],
            ['district_code' => '3273070', 'code' => '3273070003', 'name' => 'BURANGRANG'],
            ['district_code' => '3273070', 'code' => '3273070004', 'name' => 'CIJAGRA'],
            ['district_code' => '3273070', 'code' => '3273070005', 'name' => 'CIKAWAO'],
            ['district_code' => '3273070', 'code' => '3273070006', 'name' => 'TURANGGA'],
            ['district_code' => '3273070', 'code' => '3273070007', 'name' => 'MALABAR'],
            ['district_code' => '3273070', 'code' => '3273070008', 'name' => 'PALEDANG'],

            // Kraton Yogyakarta
            ['district_code' => '3471020', 'code' => '3471020001', 'name' => 'KADIPATEN'],
            ['district_code' => '3471020', 'code' => '3471020002', 'name' => 'PATEHAN'],
            ['district_code' => '3471020', 'code' => '3471020003', 'name' => 'PANEMBAHAN'],

            // Umbulharjo Yogyakarta
            ['district_code' => '3471040', 'code' => '3471040001', 'name' => 'GIWANGAN'],
            ['district_code' => '3471040', 'code' => '3471040002', 'name' => 'WARUNGBOTO'],
            ['district_code' => '3471040', 'code' => '3471040003', 'name' => 'TAHUNAN'],
            ['district_code' => '3471040', 'code' => '3471040004', 'name' => 'SEMAKI'],
            ['district_code' => '3471040', 'code' => '3471040005', 'name' => 'SOROSUTAN'],
            ['district_code' => '3471040', 'code' => '3471040006', 'name' => 'MUJA MUJU'],
            ['district_code' => '3471040', 'code' => '3471040007', 'name' => 'PANDEYAN'],
        ];

        // Track which districts have subdistricts
        $districtsWithSubdistricts = [];

        foreach ($subdistricts as $subdistrict) {
            $districtId = DB::table('districts')->where('code', $subdistrict['district_code'])->value('id');

            if ($districtId) {
                DB::table('subdistricts')->insert([
                    'district_id' => $districtId,
                    'code' => $subdistrict['code'],
                    'name' => $subdistrict['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $districtsWithSubdistricts[] = $subdistrict['district_code'];
            }
        }

        // Add default subdistrict for districts that don't have any
        foreach ($allDistricts as $district) {
            if (!in_array($district->code, $districtsWithSubdistricts)) {
                DB::table('subdistricts')->insert([
                    'district_id' => $district->id,
                    'code' => $district->code . '001',
                    'name' => 'SUBDISTRICT 1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
