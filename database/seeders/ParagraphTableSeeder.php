<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParagraphContent;

class ParagraphTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chk = ParagraphContent::whereCode('home_title')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_title",
                "value" => "Ultimate Laravel Project Starter  **zStarter!**",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_description')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                    "code" => "home_description",
                    "value" => "<p>Start building your Laravel project in no time. <strong>Our powerful toolkit includes all the essential components you need to get started,</strong> including pre-configured authentication, database migrations, and a modular structure that's easy to customize and extend. So why wait? <i>Get started with zStarter today and supercharge your Laravel development workflow!</i></p>",
                    "type" => "2",
                    "remark" => null,
                    "is_permanent" => 1,
                    "views" => null,
                    "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_our_vision')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_our_vision",
               "value" => "Our Vision^^To become a global leader in delivering innovative solutions that redefine excellence in our industry, driven by our commitment to integrity.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_our_solution_title')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_our_solution_title",
                "value" => "99.7 ^^ %^^  Customer Satisfaction",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_our_solution_title_two')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_our_solution_title_two",
                "value" => "4x^^New Visitors",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_our_solutions')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_our_solutions",
                "value" => "Discover our comprehensive range of solutions designed to [solve specific customer challenges or fulfill needs]. From [mention specific solution or service] to [another key offering], we are committed to delivering [quality/differentiated/etc.] solutions that [benefit customers in a unique way, e.g., increase efficiency, enhance productivity, drive growth]. Explore how our tailored solutions can [deliver specific outcomes or benefits, e.g., streamline operations, optimize costs, improve customer satisfaction].",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_who_we_are')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_who_we_are",
                "value" => "At Book My Water, we are more than just a [industry/sector] company. We are innovators, collaborators, and problem-solvers dedicated to [briefly describe your core mission or purpose]. With a passion for [mention key aspects of your industry or sector], we strive to [specific goals or objectives, e.g., 'push the boundaries of technological advancement' or 'set new standards for customer service']. Our commitment to [core values or principles, e.g., 'integrity', 'excellence', 'sustainability'] guides everything we do, ensuring that [how these values benefit your stakeholders, e.g., 'our customers receive the highest quality solutions' or 'our employees thrive in a supportive environment']. Learn more about our journey and discover how we can [impact your audience, e.g., 'transform your business' or 'make a difference in your community'].",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_who_we_are_list')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_who_we_are_list",
                "value" => "Who We Are^^We are a team of dedicated professionals committed to excellence and innovation.^^Our expertise spans various industries, allowing us to deliver comprehensive solutions.^^We prioritize customer satisfaction and strive to exceed expectations.^^Our values are rooted in integrity, collaboration, and continuous improvement.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_our_mission')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_our_mission",
                "value" => "Our Mission^^Our mission is to empower individuals and businesses by providing innovative solutions and unparalleled services. We strive to make a positive impact through our commitment to excellence and sustainability.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('home_our_values')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "home_our_values",
                "value" => "Our Values^^At our core, we uphold integrity, excellence, and innovation. We are committed to delivering exceptional services and fostering a culture of continuous improvement.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "Home"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('about_who_are_we')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "about_who_are_we",
                "value" => "Welcome to Book My Water, where innovation meets [industry/sector]. We are passionate about [describe your core mission or purpose, e.g., 'revolutionizing [industry/sector] with cutting-edge technology' or 'empowering [specific audience] through transformative solutions']. At our core, we believe in [core values or principles, e.g., 'integrity', 'excellence', 'customer-centricity'], guiding us to deliver [mention key benefits or outcomes, e.g., 'exceptional service' or 'game-changing products']. Our journey is fueled by [mention key aspects of your company culture or approach, e.g., 'collaboration', 'continuous learning'], ensuring that every interaction with us reflects our commitment to [customer satisfaction, innovation, etc.]. Explore how [Your Company Name] is shaping the future of [industry/sector] and discover why we are a trusted partner in [benefit or outcome].",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "About"
                ]
            );
        }
        $chk = ParagraphContent::whereCode('about_who_are_we_list')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "about_who_are_we_list",
                "value" => "At Book My Water, we stand for excellence in everything we do.^^Our core values include:
                    1. **Innovation**: We continuously strive to innovate and push boundaries in [your industry/sector].
                    2. **Customer-Centricity**: Our customers are at the heart of our business, and we prioritize their needs.
                    3. **Integrity**: We uphold the highest standards of integrity in all our interactions and operations.
                    4. **Collaboration**: We believe in the power of collaboration, both internally and with our partners.
                    5. **Sustainability**: We are committed to environmental sustainability and minimizing our impact.

                    These values guide us in delivering exceptional [products/services/solutions] that make a positive impact.^^Explore how [Your Company Name] can help you achieve your goals and exceed expectations.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "About"
                ]
            );
        }

        $chk = ParagraphContent::whereCode('about_how_it_works')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "about_how_it_works",
                "value" => "Understanding Book My Water's process is key to grasping our commitment to [industry/sector]. We start by [briefly describe the initial step in your process, e.g., 'listening carefully to our clients' needs' or 'conducting thorough research']. Next, we [describe the subsequent steps, e.g., 'apply our expertise to tailor solutions' or 'implement cutting-edge technology']. Throughout this journey, transparency and collaboration are at the forefront, ensuring [specific benefits or outcomes, e.g., 'efficient project delivery' or 'high client satisfaction']. Our approach is not just about completing tasks; it's about [highlight what makes your process unique, e.g., 'fostering long-term partnerships' or 'driving innovation in every project']. Discover how [Your Company Name] turns concepts into reality and why our method is trusted by [mention your target audience, e.g., 'clients across industries' or 'global partners'].",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "About"
                ]
            );
        }
        $chk = ParagraphContent::whereCode('about_how_it_works_list')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "about_how_it_works_list",
                "value" => "Our approach is structured around several key principles:
                1. [Briefly describe the first step or principle, e.g., 'Understanding client needs' or 'Research and analysis'].
                2. [Describe the second step or principle, e.g., 'Customization and adaptation' or 'Implementation of solutions'].
                3. [Explain the third step or principle, e.g., 'Quality assurance and testing' or 'Continuous improvement and innovation'].
                4. [Conclude with a statement that ties all steps together and emphasizes your commitment to excellence or customer satisfaction, e.g., 'By adhering to these principles, we ensure that every project exceeds expectations and delivers measurable results.']",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "About"
                ]
            );
        }
        $chk = ParagraphContent::whereCode('about_the_full_service')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "about_the_full_service",
                "value" => "The full service we are offering is specifically designed to meet your business needs and projects.^^Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna, vel scelerisque nisl consectetur duis mollis commodo.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "About"
                ]
            );
        }
        $chk = ParagraphContent::whereCode('about_the_full_service')->first();
        if(!$chk) {
            ParagraphContent::firstOrCreate(
                [
                "code" => "about_the_full_service",
                "value" => "The full service we are offering is specifically designed to meet your business needs and projects.^^Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna, vel scelerisque nisl consectetur duis mollis commodo.",
                "type" => "1",
                "remark" => null,
                "is_permanent" => 1,
                "views" => null,
                "group" => "About"
                ]
            );
        }

    }
}
