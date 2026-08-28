<?php

namespace Database\Seeders;

use App\Models\CategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Laratrust\Models\LaratrustRole;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ItemCategories
        $itemCategoryType = CategoryType::whereCode('ItemCategories')->first();
        if ($itemCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $itemCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        // BlogCategories
        $blogCategoryType = CategoryType::whereCode('BlogCategories')->first();
        if ($blogCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $blogCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        // CaseStudyCategoryType
        $caseStudyCategoryType = CategoryType::whereCode('CaseStudyCategories')->first();
        if ($caseStudyCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $caseStudyCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        // CareerCategoryType
        $careerCategoryType = CategoryType::whereCode('careerCategoryType')->first();
        if ($careerCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $careerCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        // FaqCategories
        $faqCategoryType = CategoryType::whereCode('FaqCategories')->first();
        if ($faqCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $faqCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        //SupportTicketCategories
        $supportTicketCategoryType = CategoryType::whereCode('SupportTicketCategories')->first();
        if ($supportTicketCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $supportTicketCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }


        // LeadCategories
        $leadCategoryType = CategoryType::whereCode('LeadCategories')->first();
        if ($leadCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $leadCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        // LeadSourceCategories
        $leadSourceCategoryType = CategoryType::whereCode('LeadSources')->first();
        if ($leadSourceCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $leadSourceCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }

        // LeadContactJobTitleCategories
        $leadContactJobTitleCategoryType = CategoryType::whereCode('LeadContactJobTitleCategories')->first();
        if ($leadContactJobTitleCategoryType) {
            Category::firstOrCreate(
                [
                    "name" => "General",
                    "level" => 1,
                    "category_type_id" => $leadContactJobTitleCategoryType->id,
                    "parent_id" => null,
                    "icon" => null,
                ]
            );
        }
    }
}
