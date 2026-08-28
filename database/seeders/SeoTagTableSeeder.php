<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SeoTag;
use Laratrust\Models\LaratrustRole;

class SeoTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeoTag::firstOrCreate(
            [
            "code" => "home",
            "title" => "Home",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );
        SeoTag::firstOrCreate(
            [
            "code" => "about",
            "title" => "About",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );
        SeoTag::firstOrCreate(
            [
            "code" => "contact",
            "title" => "Contact",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );
        SeoTag::firstOrCreate(
            [
            "code" => "blog",
            "title" => "Blogs",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );

        SeoTag::firstOrCreate(
            [
            "code" => "faq",
            "title" => "FAQs",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );
        SeoTag::firstOrCreate(
            [
            "code" => "pricing",
            "title" => "Pricing",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );

        SeoTag::firstOrCreate(
            [
            "code" => "adminLogin",
            "title" => "Admin Login",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );

        SeoTag::firstOrCreate(
            [
            "code" => "userLogin",
            "title" => "User Login",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );

        SeoTag::firstOrCreate(
            [
            "code" => "memberLogin",
            "title" => "Member Login",
            "keyword" => "Developed By Book My Water",
            "description" =>"Keyword Description",
            "remark" => null,
            ]
        );

        SeoTag::firstOrCreate([
            "code" => "description",
            "title" => "Description",
            "keyword" => "Meta Description",
            "description" => "Short page summary for search engines",
            "remark" => "This meta tag provides a concise summary of the page content for SEO.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "keywords",
            "title" => "Keywords",
            "keyword" => "Meta Keywords",
            "description" => "Relevant keywords for the page",
            "remark" => "Specify comma-separated keywords relevant to the page content.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "subject",
            "title" => "Subject",
            "keyword" => "Meta Subject",
            "description" => "General subject or theme of the page",
            "remark" => "Defines the topic or subject of the page.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "copyright",
            "title" => "Copyright",
            "keyword" => "Meta Copyright",
            "description" => "Copyright information",
            "remark" => "Indicates the copyright ownership of the website content.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "language",
            "title" => "Language",
            "keyword" => "Meta Language",
            "description" => "Language code for the content",
            "remark" => "Specifies the language of the content (e.g., EN, IN).",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "robots",
            "title" => "Robots",
            "keyword" => "Meta Robots",
            "description" => "Directs search engine bots to index and follow links",
            "remark" => "Controls how search engines index and follow the page.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "abstract",
            "title" => "Abstract",
            "keyword" => "Meta Abstract",
            "description" => "Additional abstract description",
            "remark" => "Provides a summary for technical or academic documents.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "topic",
            "title" => "Topic",
            "keyword" => "Meta Topic",
            "description" => "Topic or category of the page",
            "remark" => "Defines the primary topic or category of the page.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "summary",
            "title" => "Summary",
            "keyword" => "Meta Summary",
            "description" => "Short summary of the page content",
            "remark" => "Provides an overview of the content for SEO purposes.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "classification",
            "title" => "Classification",
            "keyword" => "Meta Classification",
            "description" => "Classification or type of the page",
            "remark" => "Specifies the type of content (e.g., Business, News).",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "author",
            "title" => "Author",
            "keyword" => "Meta Author",
            "description" => "Author or creator of the content",
            "remark" => "Includes the name or email of the content's author.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "designer",
            "title" => "Designer",
            "keyword" => "Meta Designer",
            "description" => "Name of the designer or organization",
            "remark" => "Credits the website's designer or organization.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "reply_to",
            "title" => "Reply-To",
            "keyword" => "Meta Reply-To",
            "description" => "Email for user queries",
            "remark" => "Provides a contact email address for user inquiries.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "owner",
            "title" => "Owner",
            "keyword" => "Meta Owner",
            "description" => "Owner of the website",
            "remark" => "Specifies the website owner or organization.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "url",
            "title" => "URL",
            "keyword" => "Meta URL",
            "description" => "Current page URL",
            "remark" => "Specifies the canonical URL for the page.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "og_title",
            "title" => "OG Title",
            "keyword" => "Open Graph Title",
            "description" => "Title for Open Graph (used by social media)",
            "remark" => "Defines the title displayed when sharing on social media.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "og_type",
            "title" => "OG Type",
            "keyword" => "Open Graph Type",
            "description" => "Type of content (e.g., website, article, product)",
            "remark" => "Specifies the type of object for Open Graph sharing.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "og_url",
            "title" => "OG URL",
            "keyword" => "Open Graph URL",
            "description" => "Canonical URL for Open Graph",
            "remark" => "The URL that Open Graph tags link to.",
        ]);
        
        
        SeoTag::firstOrCreate([
            "code" => "og_site_name",
            "title" => "OG Site Name",
            "keyword" => "Open Graph Site Name",
            "description" => "Website name for Open Graph",
            "remark" => "Defines the name of the website in Open Graph tags.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "og_description",
            "title" => "OG Description",
            "keyword" => "Open Graph Description",
            "description" => "Open Graph description",
            "remark" => "A brief description for social media previews.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "twitter_card",
            "title" => "Twitter Card",
            "keyword" => "Twitter Card Type",
            "description" => "Type of Twitter card (e.g., summary, summary_large_image)",
            "remark" => "Specifies the layout of the Twitter card.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "twitter_title",
            "title" => "Twitter Title",
            "keyword" => "Twitter Title",
            "description" => "Title for Twitter card",
            "remark" => "Defines the title for Twitter card previews.",
        ]);
        
        SeoTag::firstOrCreate([
            "code" => "twitter_description",
            "title" => "Twitter Description",
            "keyword" => "Twitter Description",
            "description" => "Description for Twitter card",
            "remark" => "Specifies the description for Twitter card previews.",
        ]);
        
    }
}
