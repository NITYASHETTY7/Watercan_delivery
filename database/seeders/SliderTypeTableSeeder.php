<?php

namespace Database\Seeders;

use App\Models\SliderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryType;
use Laratrust\Models\LaratrustRole;

class SliderTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        SliderType::firstOrCreate(
            [
                "title" => "How It Works?",
                "code" => "about_how_it_works",
                "short_text" => "Here are 3 working steps to organize our business projects.",
                "remark" => null,
                "is_published" => 0,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "HOW IT WORKS?",
                "code" => "home_how_it_works",
                "short_text" => "Here are the 3 working steps on success.",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "Tailored Business Solutions to Meet Your Unique Needs.",
                "code" => "about_the_full_service",
                "short_text" => "Our comprehensive services are designed to meet the specific needs of your business and projects. With a focus on excellence, we deliver strategic solutions to optimize your operations and achieve your goals. Trust our expert team to provide unparalleled support and innovation.",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "Donec id",
                "code" => "home_testimonial",
                "short_text" => "“Donec id elit non mi porta gravida at eget metus. Vivamus mollis est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Duis mollis porta est non commodo luctus.”",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "WHAT WE DO?",
                "code" => "home_what_we_do",
                "short_text" => "The service we offer is specifically designed to meet your needs.",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "HOW IT WORKS?",
                "code" => "about_how_it_work",
                "short_text" => "Everything you need on creating a business process.",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "JOIN OUR TEAM",
                "code" => "career_join_our_team",
                "short_text" => "Join our team to help shape the future of development.",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "ABOUT STATISTICS",
                "code" => "about_statistics",
                "short_text" => null,
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "ABOUT OUR TEAM",
                "code" => "about_our_team",
                "short_text" => "Save your time and money by choosing our professional team.",
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "ABOUT_PARTNERS",
                "code" => "about_partners",
                "short_text" => null,
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );

        SliderType::firstOrCreate(
            [
                "title" => "ABOUT TESTIMONIALS",
                "code" => "about_testimonials",
                "short_text" => null,
                "remark" => null,
                "is_published" => 1,
                "is_permanent" => 1,
            ]
        );
    }
}
