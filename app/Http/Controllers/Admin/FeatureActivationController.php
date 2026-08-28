<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class FeatureActivationController extends Controller
{
     public function index()
     {
          $groups = [
               'Sales & Payments' => [
                    'options' => [
                         // ['name' => "Sales & Payments", 'key' => 'toggling_sales_&_payments_activation', 'tooltip' => "Activate Sales & Payments", 'sub_options' => []],
                         ['name' => "Order Management", 'key' => 'order_activation', 'tooltip' => "Activate Order Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_manage_orders_bulk_delete'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_manage_orders_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_manage_orders_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_manage_orders_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_manage_orders_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_manage_orders_table_record_limit'],
                         ]],
                         ['name' => "Subscribers", 'key' => 'subscribers_activation', 'tooltip' => "Activate Subscribers Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_subscribers_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_subscribers_bulk_upload'],
                              ['name' => "Excel Export", 'key' => 'toggling_subscribers_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_subscribers_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_subscribers_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_subscribers_table_record_limit'],
                         ]],
                         ['name' => "Control Payout", 'key' => 'payout_activation', 'tooltip' => "Activate Payout Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_control_payout_bulk_delete'],
                              ['name' => "Excel Export", 'key' => 'toggling_control_payout_table_excel_export'],
                              ['name' => "Print", 'key' => 'toggling_control_payout_table_print'],
                              ['name' => "Filter", 'key' => 'toggling_control_payout_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_control_payout_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_control_payout_table_record_limit'],
                         ]]
                    ],
               ],
               'Control Products' => [
                    'options' => [
                         //  ['name' => "Control Products", 'key' => 'toggling_control_products_activation', 'tooltip' => "Activate Control Products", 'sub_options' => []],
                         ['name' => "Manage Items", 'key' => 'item_activation', 'tooltip' => "Activate Item Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_item_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_item_bulk_upload'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_manage_item_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_manage_item_table_excel_export'],
                              ['name' => "Print", 'key' => 'toggling_manage_item_table_print'],
                              ['name' => "Filter", 'key' => 'toggling_manage_item_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_manage_item_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_manage_item_table_record_limit'],
                         ]],
                         ['name' => "Subscription Plans", 'key' => 'subscription_plans_activation', 'tooltip' => "Activate Subscription Plans Management", 'sub_options' => [

                              ['name' => "Excel Export", 'key' => 'toggling_subscription_plans_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_subscription_plans_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_subscription_plans_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_subscription_plans_table_record_limit'],
                         ]],

                    ],
               ],
               'Reports Module' => [
                    'options' => [
                         // ['name' => "Manage Reports", 'key' => 'toggling_reports_activation', 'tooltip' => "Activate Report Management", 'sub_options' => []],
                         ['name' => "Manage Purchase Reports", 'key' => 'toggling_purchase_activation', 'tooltip' => "Activate Purchase Management", 'sub_options' => []],
                         ['name' => "Manage Registration Reports", 'key' => 'toggling_registration_activation', 'tooltip' => "Activate Registration Management", 'sub_options' => []],
                    ]
               ],



               'Administrator Module' => [
                    'options' => [
                         //                    ['name' => "Administrator", 'key' => 'toggling_administrator_activation', 'tooltip' => "Activate Administrator", 'sub_options' => []],
                         ['name' => "User Management", 'key' => 'user_management_activation', 'tooltip' => "Activate User Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_user_management_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_user_management_bulk_upload'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_user_management_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_user_management_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_user_management_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_user_management_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_user_management_table_record_limit'],
                         ]],
                         ['name' => "Role & Permission", 'key' => 'roles_and_permission_activation', 'tooltip' => "Activate Role Management", 'sub_options' => [
                              ['name' => "Search", 'key' => 'toggling_permission_table_search'],
                         ]],

                    ],
               ],

               'Contact Enquiry Module' => [
                    'options' => [

                         ['name' => "Website Enquiry", 'key' => 'website_enquiry_activation', 'tooltip' => "Activate Website Enquiry Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_website_enquiry_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_website_enquiry_bulk_upload'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_website_enquiry_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_website_enquiry_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_website_enquiry_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_website_enquiry_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_website_enquiry_table_record_limit'],
                         ]],
                         ['name' => "Support Tickets", 'key' => 'ticket_activation', 'tooltip' => "Activate Support Tickets Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_support_ticket_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_support_ticket_bulk_upload'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_support_ticket_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_support_ticket_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_support_ticket_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_support_ticket_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_support_ticket_table_record_limit'],
                         ]],
                         ['name' => "Newsletters", 'key' => 'newsletter_activation', 'tooltip' => "Activate Newsletters Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_newsletter_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_newsletter_bulk_upload'],

                              ['name' => "Excel Export", 'key' => 'toggling_newsletter_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_newsletter_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_newsletter_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_newsletter_table_record_limit'],
                         ]],
                         ['name' => "Leads", 'key' => 'lead_activation', 'tooltip' => "Activate Leads Management", 'sub_options' => [

                              ['name' => "Bulk Upload", 'key' => 'toggling_lead_bulk_upload'],
                              ['name' => "Excel Export", 'key' => 'toggling_lead_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_lead_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_lead_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_lead_table_record_limit'],
                         ]],
                    ],
               ],

               'Content Management Module' => [
                    'options' => [
                         // ['name' => "Content Management", 'key' => 'toggling_content_management_activation', 'tooltip' => "Activate Content Management", 'sub_options' => []],
                         ['name' => "Blogs", 'key' => 'article_activation', 'tooltip' => "Activate Blogs Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_blogs_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_blogs_bulk_upload'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_blogs_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_blogs_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_blogs_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_blogs_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_blogs_table_record_limit'],
                         ]],
                         ['name' => "Templates", 'key' => 'mail_sms_activation', 'tooltip' => "Activate Mail/Text Templates Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_templates_bulk_delete'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_templates_bulk_status_update'],
                              ['name' => "Filter", 'key' => 'toggling_templates_table_filter'],
                              ['name' => "Check box", 'key' => 'toggling_templates_table_checkbox'],
                              ['name' => "Search", 'key' => 'toggling_templates_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_templates_table_record_limit'],
                              ['name' => "Excel", 'key' => 'toggling_templates_table_excel_export'],
                         ]],
                         ['name' => "Category Group", 'key' => 'category_management_activation', 'tooltip' => "Activate Category Group Management", 'sub_options' => [

                              ['name' => "Sync Category", 'key' => 'toggling_category_management_bulk_sync'],
                              ['name' => "Filter", 'key' => 'toggling_category_management_table_filter'],

                              ['name' => "Search", 'key' => 'toggling_category_management_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_category_management_table_record_limit'],
                         ]],
                         ['name' => "Slider Group ", 'key' => 'slider_activation', 'tooltip' => "Activate Slider Group Management", 'sub_options' => [

                              ['name' => "Sync Slider", 'key' => 'toggling_slider_bulk_sync'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_slider_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_slider_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_slider_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_slider_table_search'],
                              ['name' => "Check box", 'key' => 'toggling_slider_table_checkbox'],
                              ['name' => "Record Limit", 'key' => 'toggling_slider_table_record_limit'],
                         ]],

                         ['name' => "Paragraph Content", 'key' => 'paragraph_content_activation', 'tooltip' => "Activate Paragraph Content Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_paragraph_content_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_paragraph_content_bulk_upload'],
                              ['name' => "Check box", 'key' => 'toggling_paragraph_content_checkbox'],
                              ['name' => "Filter", 'key' => 'toggling_paragraph_content_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_paragraph_content_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_paragraph_content_table_record_limit'],
                              ['name' => "Excel", 'key' => 'toggling_paragraph_content_table_excel_export'],
                         ]],

                         ['name' => "Manage FAQs", 'key' => 'faq_activation', 'tooltip' => "Activate Manage FAQs Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_faq_activation_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_faq_activation_bulk_upload'],
                              ['name' => "Check box", 'key' => 'toggling_faq_activation_checkbox'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_faq_activation_bulk_status_update'],
                              ['name' => "Filter", 'key' => 'toggling_faq_activation_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_faq_activation_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_faq_activation_table_record_limit'],
                         ]],
                         ['name' => "Location", 'key' => 'location_activation', 'tooltip' => "Activate Location Management", 'sub_options' => [
                              ['name' => "Check box", 'key' => 'toggling_location_activation_checkbox'],
                              ['name' => "Search", 'key' => 'toggling_location_activation_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_location_activation_table_record_limit'],
                            //   ['name' => "Excel", 'key' => 'toggling_location_activation_table_excel_export'],
                         ]],
                         ['name' => "Control SEO", 'key' => 'seo_tags_activation', 'tooltip' => "Activate SEO Tags Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_seo_tags_bulk_delete'],
                              ['name' => "Bulk Upload", 'key' => 'toggling_seo_tags_bulk_upload'],
                              ['name' => "Check box", 'key' => 'toggling_seo_tags_checkbox'],
                              ['name' => "Bulk Status Update", 'key' => 'toggling_seo_tags_bulk_status_update'],
                              ['name' => "Excel Export", 'key' => 'toggling_seo_tags_table_excel_export'],
                              ['name' => "Filter", 'key' => 'toggling_seo_tags_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_seo_tags_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_seo_tags_table_record_limit'],
                         ]],

                         ['name' => "Pages", 'key' => 'pages_activation', 'tooltip' => "Activate Pages Management", 'sub_options' => [
                              ['name' => "Bulk Delete", 'key' => 'toggling_pages_activation_bulk_delete'],
                              ['name' => "Check box", 'key' => 'toggling_pages_activation_checkbox'],
                              ['name' => "Filter", 'key' => 'toggling_pages_activation_table_filter'],
                              ['name' => "Search", 'key' => 'toggling_pages_activation_table_search'],
                              ['name' => "Record Limit", 'key' => 'toggling_pages_activation_table_record_limit'],
                              ['name' => "Excel", 'key' => 'toggling_pages_activation_table_excel_export'],
                         ]],
                         ['name' => "Promo Code", 'key' => 'toggling_promo_code_activation', 'tooltip' => "Activate Promo Code Management", 'sub_options' => []],
                         ['name' => "File Manager", 'key' => 'toggling_file_manager_activation', 'tooltip' => "Activate File Manager Management", 'sub_options' => []],
                         //      ['name' => "Wallet", 'key' => 'wallet_activation', 'tooltip' => "Activate Wallet Management", 'sub_options' => []],
                    ],
               ],

               'Setup & Configurations Module' => [
                    'options' => [
                         // ['name' => "Setup & Configurations", 'key' => 'toggling_setup_&_configurations_activation', 'tooltip' => "Activate Setup & Configurations", 'sub_options' => []],
                         ['name' => "Basic Details", 'key' => 'basic_details_activation', 'tooltip' => "Activate Basic Details Management", 'sub_options' => [
                              ['name' => "Control Details", 'key' => 'toggling_control_details_activation'],
                              ['name' => "Custom Style", 'key' => 'toggling_custom_style_activation'],
                              ['name' => "Custom Script", 'key' => 'toggling_custom_script_activation'],

                         ]],
                         // ['name'=>"Appearance",'key'=>'appearance_activation','tooltip'=>"Activate Appearance Management", 'sub_options' => []],
                         ['name' => "General Configuration", 'key' => 'manage_general_configuration_activation', 'tooltip' => "Activate General Configuration Management", 'sub_options' => [
                              ['name' => "General", 'key' => 'toggling_general_activation'],
                              ['name' => "Currency", 'key' => 'toggling_currency_activation'],
                              ['name' => "Date Mode", 'key' => 'toggling_date_mode_activation'],
                              ['name' => "Notification/Verification", 'key' => 'toggling_notification_verification_activation'],
                              ['name' => "Invoice Config", 'key' => 'toggling_invoice_activation'],
                              ['name' => "Troubleshoot", 'key' => 'toggling_troubleshoot_activation'],
                         ]],
                         ['name' => "Mail/SMS Configuration", 'key' => 'mail_sms_configuration_activation', 'tooltip' => "Activate Mail/SMS Configuration Management", 'sub_options' => [
                              ['name' => "Mail Config", 'key' => 'toggling_mail_activation'],
                              ['name' => "SMS Config", 'key' => 'toggling_sms_activation'],
                              ['name' => "FCM Config", 'key' => 'toggling_fcm_activation'],
                         ]],
                         // ['name'=>"Payment Gateway",'key'=>'payment_gateway_activation','tooltip'=>"Activate Payment Gateway Management", 'sub_options' => []],
                         ['name' => "Theme", 'key' => 'toggling_theme_activation', 'tooltip' => "Activate Theme Management", 'sub_options' => [

                              // ['name' => "Control Details", 'key' => 'toggling_control_details_activation'],
                              // ['name' => "Custom Style", 'key' => 'toggling_custom_style_activation'],
                              // ['name' => "Custom Script", 'key' => 'toggling_custom_script_activation'],

                         ]],

                    ],
               ],

               'Resources Module' => [
                    'options' => [
                         ['name' => "Manage Resources", 'key' => 'toggling_resources_activation', 'tooltip' => "Activate Resources Management", 'sub_options' => []],
                    ]
               ],

               'MFA Manage' => [
                    'options' => [
                         ['name' => "Admin MFA Manage", 'key' => 'toggling_admin_mfa_activation', 'tooltip' => "Activate Admin MFA", 'sub_options' => []],
                         ['name' => "User MFA Manage", 'key' => 'toggling_user_mfa_activation', 'tooltip' => "Activate User MFA", 'sub_options' => []],
                    ]
               ],

               'DAC Manage' => [
                    'options' => [
                         ['name' => "DAC Manage", 'key' => 'toggling_dac_activation', 'tooltip' => "Activate DAC", 'sub_options' => []],
                    ]
               ],

               'Broadcast' => [
                    'options' => [
                         ['name' => "Broadcast", 'key' => 'toggling_broadcast_activation', 'tooltip' => "Activate Broadcast", 'sub_options' => []],
                    ]
               ],
               'Marketing' => [
                    'options' => [
                         ['name' => "Marketing", 'key' => 'toggling_marketing_activation', 'tooltip' => "Activate Marketing", 'sub_options' => []],
                    ]
               ],

            //    //Start CrudGen Compiler

            //    'Projects Module' => [
            //         'options' => [
            //              ['name' => "Projects Manage", 'key' => 'toggling_projects_activation', 'tooltip' => "Activate Projects", 'sub_options' => []],
            //         ]
            //    ],

            //    'Projects Module' => [
            //         'options' => [
            //              ['name' => "Projects Manage", 'key' => 'toggling_projects_activation', 'tooltip' => "Activate Projects", 'sub_options' => []],
            //         ]
            //    ],


            //    'Projects Module' => [
            //         'options' => [
            //              ['name' => "Projects Manage", 'key' => 'toggling_projects_activation', 'tooltip' => "Activate Projects", 'sub_options' => []],
            //         ]
            //    ],


            //    'Tests Module' => [
            //         'options' => [
            //              ['name' => "Tests Manage", 'key' => 'toggling_tests_activation', 'tooltip' => "Activate Tests", 'sub_options' => []],
            //         ]
            //    ],


            //    'Tests Module' => [
            //         'options' => [
            //              ['name' => "Tests Manage", 'key' => 'toggling_tests_activation', 'tooltip' => "Activate Tests", 'sub_options' => []],
            //         ]
            //    ],


            //    'Test12s Module' => [
            //         'options' => [
            //              ['name' => "Test12s Manage", 'key' => 'test12s_activation', 'tooltip' => "Activate Test12s", 'sub_options' => []],
            //         ]
            //    ],

            //    'Qa-t-e-s-ts Module' => [
            //         'options' => [
            //              ['name' => "Qa-t-e-s-ts Manage", 'key' => 'qa-t-e-s-ts_activation', 'tooltip' => "Activate Qa-t-e-s-ts", 'sub_options' => []],
            //         ]
            //    ],

            //    //End CrudGen Compiler

          ];
          if (env('DEV_MODE') == 1) {
               return view('panel.admin.features_activation.index', compact('groups'));
          }
          return abort(404);
     }

     public function store(Request $request)
     {
          try {
               $setting = Setting::where('key', '=', $request->key)->first();
               if ($setting) {
                    $setting->value = $request->val;
                    $setting->group = "activation";
                    $setting->save();
               } else {
                    $data = new setting();
                    $data->key = $request->key;
                    $data->value = $request->val;
                    $data->group = "activation";
                    $data->save();
               }

               return response(__('ui.record_updated'), 200);
          } catch (\Exception $e) {
               return response($e->getMessage(), 200);
          }
     }


}
