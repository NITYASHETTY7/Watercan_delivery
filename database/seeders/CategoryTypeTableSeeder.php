<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryType;

class CategoryTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryType::firstOrCreate(['name' => 'Item Categories','allowed_level' => 1,'code' => 'ItemCategories','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate(['name' => 'Blog Categories','allowed_level' => 1,'code' => 'BlogCategories','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate(['name' => 'Faq Categories','allowed_level' => 1,'code' => 'FaqCategories','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate(['name' => 'Support Ticket Categories','allowed_level' => 1,'code' => 'SupportTicketCategories','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate(['name' => 'Lead Categories','allowed_level' => 1,'code' => 'LeadCategories','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate(['name' => 'Lead Source','allowed_level' => 1,'code' => 'LeadSources','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate(['name' => 'Lead Contact Job Title Categories','allowed_level' => 1,'code' => 'LeadContactJobTitleCategories','is_permanent' => 1,'remark' => null]);
        CategoryType::firstOrCreate([ 'name' => 'Lead Status', 'allowed_level' => '1', 'code' => 'LeadStatus', 'is_permanent' => '1', 'remark' => '']);
        CategoryType::firstOrCreate([ 'name' => 'Lead Source', 'allowed_level' => '1', 'code' => 'LeadSource', 'is_permanent' => '1', 'remark' => '']);
        CategoryType::firstOrCreate([ 'name' => 'Job Title Category', 'allowed_level' => '1', 'code' => 'JobTitleCategories', 'is_permanent' => '1', 'remark' => '']);
        CategoryType::firstOrCreate([ 'name' => 'Contact Category', 'allowed_level' => '2', 'code' => 'ContactCategory', 'is_permanent' => '1', 'remark' => '']);
        CategoryType::firstOrCreate([ 'name' => 'Paragraph Content Group', 'allowed_level' => '2', 'code' => 'ParagraphContentGroup', 'is_permanent' => '1', 'remark' => '']);
        CategoryType::firstOrCreate([ 'name' => 'Product Category', 'allowed_level' => '1', 'code' => 'ProductCategory', 'is_permanent' => '1', 'remark' => 'product category names']);
        CategoryType::firstOrCreate([ 'name' => 'Notes Title Category', 'allowed_level' => '1', 'code' => 'NotesTitleCategory', 'is_permanent' => '1', 'remark' => 'Notes Title']);
        CategoryType::firstOrCreate([ 'name' => 'Case Study Categories', 'allowed_level' => '1', 'code' => 'CaseStudyCategories', 'is_permanent' => '1', 'remark' => '']);
        CategoryType::firstOrCreate([ 'name' => 'Career Categories', 'allowed_level' => '1', 'code' => 'CareerCategories', 'is_permanent' => '1', 'remark' => '']);
    }
}
