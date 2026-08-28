<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        $this->call(RolesAndPermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        // $this->call(CategoryTypeTableSeeder::class);
        $this->call(MailTemplateSeeder::class);
        // $this->call(PageTableSeeder::class);
        // $this->call(SliderTypeTableSeeder::class);
        // $this->call(SliderTableSeeder::class);
        $this->call(SeoTagTableSeeder::class);
        // $this->call(ParagraphTableSeeder::class);
        // $this->call(LocationTableSeeder::class);
        // $this->call(CategoryTableSeeder::class);
    }
}
