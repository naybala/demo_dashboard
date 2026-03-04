<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
        $data = $this->prepareData();
        DB::table("own_products")->insert($data);
    }
    
    private function prepareData()
    {
        $insertData = [];
        foreach (range(1,3000) as $a){
            $insertData[] = [
                'name' => $a . 'name',
                'unit_id'=>1,
                'category_id'=>13,
                'price' =>1000,
                'investment'=>800,
                'profit'=>200,
                'image' => '/storage/OwnProducts/F4OZ93fsMr0VBuFYyWxp7m6BQYDkbwHTT22U60V2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        };
        return $insertData;
    }
}
