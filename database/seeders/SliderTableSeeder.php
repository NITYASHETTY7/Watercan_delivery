<?php

namespace Database\Seeders;

use App\Models\Slider;
use App\Models\SliderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryType;
use Laratrust\Models\LaratrustRole;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sliderCategory = SliderType::whereCode('about_how_it_work')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Magic Touch",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero pharetra augue dapibus. Praesent commodo cursus.",
                    "description" => "Nulla vitae elit libero pharetra augue dapibus. Praesent commodo cursus.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_how_it_work')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Data Analysis",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Vivamus sagittis lacus vel augue laoreet. Etiam porta sem malesuada magna.",
                ]
            );

        }
        $sliderCategory = SliderType::whereCode('about_how_it_work')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Collect Ideas",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Cras mattis consectetur purus sit amet. Aenean lacinia bibendum nulla sed.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_what_we_do')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                ["title" => "Content Marketing",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                "description" => "Duis mollis gravida commodo id luctus erat porttitor ligula, eget lacinia odio sem aget elit nullam quis risus eget.",]
            );
        }
        $sliderCategory = SliderType::whereCode('home_what_we_do')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Social Engagement",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Duis mollis gravida commodo id luctus erat porttitor ligula, eget lacinia odio sem aget elit nullam quis risus eget.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_what_we_do')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Market Research",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Duis mollis gravida commodo id luctus erat porttitor ligula, eget lacinia odio sem aget elit nullam quis risus eget.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_what_we_do')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Daily Updates",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Duis mollis gravida commodo id luctus erat porttitor ligula, eget lacinia odio sem aget elit nullam quis risus eget.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_what_we_do')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Secure Payments",
                    "image" => "http://localhost/my-projects/zstarter/public_html//storage/130/no-notification-icon.png",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Duis mollis gravida commodo id luctus erat porttitor ligula, eget lacinia odio sem aget elit nullam quis risus eget.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_what_we_do')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "24/7 Support",
                    "image" => "http://localhost/my-projects/zstarter/public_html//storage/129/Access-denied-icon2-01.png",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Duis mollis gravida commodo id luctus erat porttitor ligula, eget lacinia odio sem aget elit nullam quis risus eget.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_testimonial')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Coriss Ambady",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Financial Analyst",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_the_full_service')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Marketing",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus. Cras justo cum sociis natoque magnis.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_the_full_service')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Strategy",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus. Cras justo cum sociis natoque magnis.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_the_full_service')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Development",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus. Cras justo cum sociis natoque magnis.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_the_full_service')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Data Analysis",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus. Cras justo cum sociis natoque magnis.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_how_it_works')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Collect Ideas",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero pharetra augue dapibus.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_how_it_works')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Data Analysis",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Vivamus sagittis lacus augue laoreet vel.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('home_how_it_works')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Magic Touch",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Cras mattis consectetur purus sit amet.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('career_join_our_team')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Career Growth",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus cras justo.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('career_join_our_team')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Work From Anywhere",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus cras justo.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('career_join_our_team')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Smart Salary",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus cras justo.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('career_join_our_team')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Flexible Hours",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida at eget metus cras justo.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_statistics')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Completed Projects",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "7518",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_statistics')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Satisfied Customers",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "3472",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_statistics')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Expert Employees",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "2184",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_statistics')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Awards Won",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "4523",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_our_team')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Tina Geller",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Financial Analyst^^Fermentum massa justo sit amet risus morbi leo.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_our_team')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "sr rajput",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Financial Analyst^^Fermentum massa justo sit amet risus morbi leo.",
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_partners')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "SLOWAVE",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => null,
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_partners')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Skybox",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => null,
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_partners')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Malory",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => null,
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_partners')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Creatink",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => null,
                ]
            );
        }
        $sliderCategory = SliderType::whereCode('about_testimonials')->first();
        if ($sliderCategory) {
            Slider::firstOrCreate(
                [
                    "title" => "Cory Zamora",
                    "image" => "",
                    "status" => 1,
                    "slider_type_id" => $sliderCategory->id,
                    "type" => 1,
                    "description" => "Financial Analyst^^“Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.Vestibulum ligula porta felis euismod semper. Cras justo odio consectetur.”",
                ]
            );

        }
    }
}
