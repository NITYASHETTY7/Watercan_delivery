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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Basic Details';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return 's';
        $label = $this->label;
        return view('panel.admin.setting.index', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $envVariables = ['APP_NAME', 'APP_URL', 'PASSWORD_RESET_EXPIRY'];
        $setEnvVariables = [];

        try {

            // Handle 'recaptcha' toggle if not present in request but group is 'general_setting'
            if (!$request->has('recaptcha') && $request->group === 'general_setting') {
                $recaptchaSetting = Setting::where('key', 'recaptcha')->first();
                if ($recaptchaSetting) {
                    $recaptchaSetting->update([
                        'value' => 0,
                    ]);
                }
            }
            foreach ($request->except('_token') as $key => $input) {
                $setting = Setting::where('key', $key)->first();
                if ($input instanceof UploadedFile) {
                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                    if (!in_array($input->getMimeType(), $allowedMimeTypes)) {
                        if (request()->ajax()) {
                            return response()->json([
                                'active' => '',
                                'status' => 'error',
                                'message' => 'Error',
                                'title' => 'Only JPG, JPEG and PNG images are allowed.'
                            ]);
                        }
                        return back()->withErrors([
                            $key => 'Only JPG, JPEG and PNG images are allowed.'
                        ]);
                    }
                    if ($request->hasFile($key)) {
                        $value = $this->uploadFile($input, 'settings', Str::snake($key) . '_')->getFilePath();
                        if ($setting) {
                            $this->deleteStorageFile($setting->value);
                        }
                    } else {
                        $value = $setting->value;
                    }
                } else {
                    $value = is_array($input) ? json_encode($input) : $input;
                }
                if ($setting) {
                    $setting->update([
                        "key" => $key,
                        "value" => $value,
                    ]);
                } else {
                    Setting::firstOrCreate([
                        "key" => $key,
                        "value" => $value
                    ]);
                }
                if (in_array($key, array_map('strtolower', $envVariables))) {
                    $setEnvVariables[] = [
                        'key' => strtoupper($key),
                        'value' => $value
                    ];
                }

                Cache::forget('getSetting' . Str::studly($key));
            }
            if (!isset($request->recaptcha)) {
                $setting = Setting::where('key', 'recaptcha')->first();
                if ($setting) {
                    $setting->value = 0;
                    $setting->update();
                }
            }

            $this->ArrayofEnvKeyUpdate($setEnvVariables);

            if (request()->ajax()) {
                return response()->json([
                    'active' => $request->active ?? '',
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => __('ui.setting_updated')
                ]);
            }
            return $this->backSuccess(__('ui.setting_updated'));

        } catch (\Exception $e) {
            return $this->backError(__('ui.error_msg') . $e->getMessage());
        }
    }


    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ArrayofEnvKeyUpdate($array)
    {
        $file = DotenvEditor::load();
        $file->setKeys($array);
        $file->save();
    }

    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function singleEnvKeyUpdate($key, $value)
    {
        $file = DotenvEditor::load();
        $file->setKey($key, $value);
        $file->save();
    }

    /**
     * Feature Activation List.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function featuresActivationIndex()
    {
        $groups = [
            'Sales Module' => [
                'options' => [
                    ['name' => "Order Management", 'key' => 'order_activation', 'tooltip' => "Activate Order Management"]
                ],
            ],
            'Item Module' => [
                'options' => [
                    ['name' => "Item Management", 'key' => 'item_activation', 'tooltip' => "Activate Item Management"],
                ]
            ],
            'Vendor Module' => [
                'options' => [
                    ['name' => "Payout Management", 'key' => 'payout_activation', 'tooltip' => "Activate Payout Management"]
                ],
            ],
            'Administrator Module' => [
                'options' => [
                    ['name' => "User Management", 'key' => 'user_management_activation', 'tooltip' => "Activate User Management"],
                    ['name' => "Add User", 'key' => 'user_management_activation', 'tooltip' => "Activate Add User Management"],
                    ['name' => "Role", 'key' => 'roles_and_permission_activation', 'tooltip' => "Activate Role Management"],
                    ['name' => "Permission", 'key' => 'roles_and_permission_activation', 'tooltip' => "Activate Permission Management"],
                    ['name' => "E KYC Verification ", 'key' => 'e_kyc_verification_activation', 'tooltip' => "Activate E KYC Verification Management"],


                ],
            ],
            'Content management Module' => [
                'options' => [
                    ['name' => "Wallet", 'key' => 'wallet_activation', 'tooltip' => "Activate Wallet Management"],
                    ['name' => "Blogs", 'key' => 'article_activation', 'tooltip' => "Activate Blogs Management"],
                    ['name' => "Mail/Text Templates", 'key' => 'manage_mail_sms', 'tooltip' => "Activate Mail/Text Templates Management"],
                    ['name' => "Category Group", 'key' => 'manage_category', 'tooltip' => "Activate Category Group Management"],
                    ['name' => "Slider Group ", 'key' => 'slider_activation', 'tooltip' => "Activate Slider Group Management"],
                    ['name' => "Paragraph Content", 'key' => 'paragraph_content_activation', 'tooltip' => "Activate Paragraph Content Management"],
                    ['name' => "Manage FAQs", 'key' => 'faq_activation', 'tooltip' => "Activate Manage FAQs Management"],
                    ['name' => "Location", 'key' => 'location_activation', 'tooltip' => "Activate Location Management"],
                    ['name' => "Subscription Plans", 'key' => 'subscription_plans_activation', 'tooltip' => "Activate Subscription Plans Management"],
                    ['name' => "Article", 'key' => 'article_activation', 'tooltip' => "Activate Article Management"],
                    ['name' => "Subscribers", 'key' => 'subscribers_activation', 'tooltip' => "Activate Subscribers Management"],

                ],
            ],
            'Contact Enquiry Module' => [
                'options' => [
                    ['name' => "Website Enquiry", 'key' => 'website_enquiry_activation', 'tooltip' => "Activate Website Enquiry Management"],
                    ['name' => "Support Tickets", 'key' => 'ticket_activation', 'tooltip' => "Activate Support Tickets Management"],
                    ['name' => "Newsletters", 'key' => 'newsletter_activation', 'tooltip' => "Activate Newsletters Management"],
                    ['name' => "Leads", 'key' => 'lead_activation', 'tooltip' => "Activate Leads Management"]
                ],
            ],
            'Website setup Module' => [
                'options' => [
                    ['name' => "Basic Details", 'key' => 'basic_details_activation', 'tooltip' => "Activate Basic Details Management"],
                    ['name' => "Pages", 'key' => 'pages_activation', 'tooltip' => "Activate Pages Management"],
                    // ['name'=>"Appearance",'key'=>'appearance_activation','tooltip'=>"Activate Appearance Management"],
                    ['name' => "Social Login", 'key' => 'social_login_activation', 'tooltip' => "Activate Social Login Management"],
                    ['name' => "Seo Tags ", 'key' => 'seo_tags_activation', 'tooltip' => "Activate Seo Tags Management"],
                    ['name' => "Payment Gateway", 'key' => 'payment_gateway_activation', 'tooltip' => "Activate Payment Gateway Management"],
                ],
            ],
            'Setup & Configurations Module' => [
                'options' => [
                    ['name' => "General Configuration", 'key' => 'manage_general_configuration', 'tooltip' => "Activate General Configuration Management"],
                    ['name' => "Mail/SMS Configuration", 'key' => 'mail_sms_configuration', 'tooltip' => "Activate Mail/SMS Configuration Management"]

                ],
            ],


        ];
        if (env('DEV_MODE') == 1) {
            return view('panel.admin.features_activation.index', compact('groups'));
        }
        return abort(404);
    }

    /**
     * Feature Activation Store
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function featuresActivationStore(Request $request)
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
