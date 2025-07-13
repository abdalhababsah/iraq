<?php

namespace Database\Seeders;

use App\Models\Constituency;
use Illuminate\Database\Seeder;

class ConstituencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $constituencies = [
            ['name' => 'بغداد', 'description' => 'محافظة بغداد'],
            ['name' => 'البصرة', 'description' => 'محافظة البصرة'],
            ['name' => 'نينوى', 'description' => 'محافظة نينوى'],
            ['name' => 'أربيل', 'description' => 'محافظة أربيل'],
            ['name' => 'السليمانية', 'description' => 'محافظة السليمانية'],
            ['name' => 'النجف', 'description' => 'محافظة النجف'],
            ['name' => 'كربلاء', 'description' => 'محافظة كربلاء'],
            ['name' => 'الأنبار', 'description' => 'محافظة الأنبار'],
            ['name' => 'ديالى', 'description' => 'محافظة ديالى'],
            ['name' => 'صلاح الدين', 'description' => 'محافظة صلاح الدين'],
            ['name' => 'بابل', 'description' => 'محافظة بابل'],
            ['name' => 'واسط', 'description' => 'محافظة واسط'],
            ['name' => 'ميسان', 'description' => 'محافظة ميسان'],
            ['name' => 'ذي قار', 'description' => 'محافظة ذي قار'],
            ['name' => 'المثنى', 'description' => 'محافظة المثنى'],
            ['name' => 'القادسية', 'description' => 'محافظة القادسية'],
            ['name' => 'دهوك', 'description' => 'محافظة دهوك'],
            ['name' => 'كركوك', 'description' => 'محافظة كركوك'],
        ];

        foreach ($constituencies as $constituency) {
            Constituency::create($constituency);
        }
    }
}
