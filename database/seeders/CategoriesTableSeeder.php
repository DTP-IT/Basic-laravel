<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Giáo trình'
            ],
            [
                'name' => 'Sách tham khảo'
            ],
            [
                'name' => 'Đồ án'
            ],
            [
                'name' => 'Tạp chí'
            ],
            [
                'name' => 'Ngoại văn'
            ],
            
        ]);
    }
}
