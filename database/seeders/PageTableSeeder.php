<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebsitePage;
use Laratrust\Models\LaratrustRole;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Terms & Condition
        $meta = [
            "title" => "Terms",
            "description" => "",
            "keywords" => ""
            ];
        WebsitePage::firstOrCreate(
            [
                "title" => "Terms & Condition",
                "slug" => "terms",
                "content" => "...",
                "status" => true,
                "meta" => $meta,
                "is_permanent" => true
            ]
        );

        // Policy
        $meta = [
            "title" => "Policy",
            "description" => "",
            "keywords" => ""
        ];
        WebsitePage::firstOrCreate(
            [
                "title" => "Policy",
                "slug" => "policy",
                "content" => "...",
                "status" => true,
                "meta" => $meta,
                "is_permanent" => true
            ]
        );


        // Disclaimer
        $meta = [
            "title" => "Disclaimer",
            "description" => "",
            "keywords" => ""
        ];
        WebsitePage::firstOrCreate(
            [
                "title" => "Disclaimer",
                "slug" => "disclaimer",
                "content" => "...",
                "status" => true,
                "meta" => $meta,
                "is_permanent" => true
            ]
        );


        // Return Policy
        $meta = [
            "title" => "Return Policy",
            "description" => "",
            "keywords" => ""
        ];
        WebsitePage::firstOrCreate(
            [
                "title" => "Return Policy",
                "slug" => "return_policy",
                "content" => "...",
                "status" => true,
                "meta" => $meta,
                "is_permanent" => true
            ]
        );


    }
}
