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

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Admin\UserBankController;
use App\Http\Requests\UserRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Country;
use App\Models\ModelSession;
use App\Models\UserKyc;
use App\Models\UserVibe;
use App\Models\VibeTimeline;
use Exception;
use App\Models\MailSmsTemplate;
use App\Models\Order;
use App\Models\Role;
use App\Models\UserAddress;
use App\Models\UserNote;
use App\Models\ZonePincode;
use App\Models\ZonePincodeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class UserController extends Controller
{
    public $label;
    function __construct()
    {
        if (checkRequestKey('role')) {
            $role = request()->get('role');

            // If role is 'user', override with 'Customer'
            $this->label = ($role === 'User') ? 'Customers' : ucfirst($role) . 's';
        } else {
            $this->label = 'Customers';
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = 10;
        $runShimmer = 0;
        if (checkRequestKey('length')) {
            $length = $request->get('length');
        }
        $roles = Role::whereIn('id', [3, 2])->get()->pluck('name', 'id');

        $users = User::query();
        $users->whereRoleIsNot(['super_admin'])->where('id', '!=', auth()->id());
        if ($request->get('role')) {
            $users->whereRoleIs([request()->get('role')]);
        }

        if (checkRequestKey('is_active')) {
            $users->where('status', $request->get('is_active'));
        }
        if (checkRequestKey('search')) {
            $users->where(
                function ($q) use ($request) {
                    $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . trim($request->search) . '%')
                        ->orWhere('email', 'like', '%' . $request->get('search') . '%')
                        ->orWhere('phone', 'like', '%' . $request->get('search') . '%')
                        ->orWhere('id', 'like', '%' . $request->get('search') . '%');
                }
            );
        }

        if (checkRequestKey(['from', 'to'])) {
            $users->whereBetween(
                'created_at',
                [
                    Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                    Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                ]
            );
        }

        if (checkRequestKey('asc')) {
            $users->orderBy($request->get('asc'), 'asc');
        }

        if (checkRequestKey('desc')) {
            $users->orderBy($request->get('desc'), 'desc');
        }

        $statuses = User::USER_STATUSES;
        $bulk_activation = User::BULK_ACTIVATION;
        $label = $this->label;
        if ($request->ajax()) {
            $users = $users->latest()->paginate($length);
            return view('panel.admin.users.load', ['users' => $users, 'bulk_activation' => $bulk_activation, 'runShimmer' => $runShimmer])->render();
        } else {
            $users = $users->whereId(0)->paginate($length);
            $runShimmer = 1;
        }
        return view('panel.admin.users.index', compact('roles', 'users', 'label', 'statuses', 'bulk_activation', 'runShimmer'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $statuses = User::STATUSES;
            $roles = Role::get();
            $label = Str::singular($this->label);
            $role = Role::where('display_name', request()->role)->first();
            $pincodes = ZonePincode::get();
            return view('panel.admin.users.create.index', compact('roles', 'statuses', 'label', 'role', 'pincodes'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        // try {
            $permissions = [];
            if (isset($request->userPermission) && $request->userPermission != null) {
                $permissions = User::ADMIN_MEMBER_PERMISSION[$request->userPermission] ?? [];
                if (!empty($permissions)) {
                    $permissions = [
                        'key' => $request->userPermission,
                        'permissions' => $permissions['permissions'],
                    ];
                    $designation = $request->userPermission;
                }
            }
            $setting_payload = [];
            if (isset($request->email_notification_alert)) {
                $setting_payload['email_notification_alert'] = $request->email_notification_alert;
            }

            if (isset($request->onsite_notification_alert)) {
                $setting_payload['onsite_notification_alert'] = $request->onsite_notification_alert;
            }
            if (isset($request->sms_notification_alert)) {
                $setting_payload['sms_notification_alert'] = $request->sms_notification_alert;
            }

            $vehicle_details = [
                'vehicle_name' => $request->vehicle_name,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_number' => $request->vehicle_number,
            ];

            $business_payload = [
                'company_name' => $request->company_name,
                'gst_number'   => $request->gst_number,
            ];
            
            
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'dob' => $request->dob,
                'status' => $request->status ?? 1,
                'gender' => $request->gender,
                'setting_payload' => $setting_payload ?? [],
                'phone' => str_replace(' ', '', $request['phone']),
                'country_code' => $request->country_code,
                'permissions' => $permissions ?? [],
                'vehicle_details' => $vehicle_details ?? [],
                'business_payload' => $business_payload ?? [],
                'account_type'     => $request->account_type,
                'is_verified' => $request->is_verified ?? 0,
                'delegate_access' => rand(100000, 999999),
                'wallet' => 0, // Opening with zero balance
                'password' => Hash::make($request->password),
            ]);
            


            // Assign role to the user
            $user->syncRoles([$request->role]);
            $role = optional($user->roles->first())->display_name ?? '';

            // Start Assign Pincode to Driver
            if(UserRole($user->id)['name'] == 'driver') {
                if(!empty($request->pincodes)) {
                    foreach ($request->pincodes as $key => $pincode_id) {
                        if($pincode_id) {
                            $zonePincode = ZonePincode::where('id', $pincode_id)->first();
                            if($zonePincode) {
                                ZonePincodeUser::create([
                                    'zone_pincode_id' => $zonePincode->id,
                                    'branch_id' => $zonePincode->branch_id,
                                    'zone_id' => $zonePincode->zone_id,
                                    'user_id' => $user->id,
                                ]);
                            }
                        }
                    }
                } 
            }
            // End Assign Pincode to Driver

            if ($request->wantsJson()) {
                return response()->json([
                    'role' => $role,
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'User Created Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'User Created Successfully')->withInput();
            }
        // } catch (\Exception $e) {
        //     $errorMessage = $e->getMessage();
        //     if ($request->wantsJson()) {
        //         return response()->json(['error' => $errorMessage], 500);
        //     } else {
        //         return redirect()->back()->with('error', $errorMessage)->withInput();
        //     }
        // }
    }
    /**
     * edit page resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $statuses = User::STATUSES;
            $userPermissions = User::ADMIN_MEMBER_PERMISSION;
            $pincodes = ZonePincode::get();

            $user = User::whereId($id)->with('roles', 'permissions')->first();
            if ($user) {
                $user_role = $user->roles->first();
                $roles = Role::get();
                $label = Str::singular($this->label);

                return view('panel.admin.users.edit.index', compact('user', 'user_role', 'roles', 'statuses', 'label', 'userPermissions', 'pincodes'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Update Specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $user = User::whereId($id)->first();

            if (!$user) {
                return redirect()->back()->with('error', __('ui.user_not_found'));
            }
            $vehicle_details = [
                'vehicle_name' => $request->vehicle_name,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_number' => $request->vehicle_number,
            ];

            if ($request->account_type == User::ACCOUNT_TYPE_BUSINESS) {
                $business_payload = [
                    'company_name' => $request->company_name,
                    'gst_number'   => $request->gst_number,
                ];
            } else {
                // Individual → clear business details
                $business_payload = null;
            }

            if ($request->permissions != null) {
                $permissions = [
                    'key' => $request->userPermission,
                    'permissions' => array_keys($request->permissions),
                ];
            } else {
                $permissions = [];
            }
            $user = User::whereId($user->id)->first();

            $setting_payload = [];

            $setting_payload['email_notification_alert'] = 0;
            if (isset($request->email_notification_alert)) {
                $setting_payload['email_notification_alert'] = 1;
            }

            $setting_payload['onsite_notification_alert'] = 0;
            if (isset($request->onsite_notification_alert)) {
                $setting_payload['onsite_notification_alert'] = 1;
            }
            $setting_payload['sms_notification_alert'] = 0;
            if (isset($request->sms_notification_alert)) {
                $setting_payload['sms_notification_alert'] = 1;
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->dob = $request->dob;
            $user->setting_payload = $setting_payload;
            $user->gender = $request->gender;
            $user->permissions = $permissions;
            $user->phone = $request->phone;
            $user->country_code = $request->country_code;
            $user->status = $request->status;
            $user->vehicle_details = $vehicle_details;
            $user->business_payload = $business_payload;
            $user->account_type = $request->account_type;

            if ($user->email_verified_at == null && $request->email_verify == 1) {
                $user->email_verified_at = now();
            } elseif ($user->email_verified_at != null && !$request->has('email_verify')) {
                $user->email_verified_at = null;
            }
            $user->save();

            $user->syncRoles([$request->role]);
            $role = $user->roles[0]->display_name ?? '';

            // Start Update Pincode Assignment to Driver
                if (UserRole($user->id)['name'] == 'driver') {
                    $newPincodes = $request->pincodes ?? []; // new selected pincodes (array)
                    $existingPincodes = ZonePincodeUser::where('user_id', $user->id)->pluck('zone_pincode_id')->toArray();

                    // Find pincodes to remove (no longer selected)
                    $pincodesToDelete = array_diff($existingPincodes, $newPincodes);

                    // Find new pincodes to add
                    $pincodesToAdd = array_diff($newPincodes, $existingPincodes);

                    // Remove unselected pincodes
                    if (!empty($pincodesToDelete)) {
                        ZonePincodeUser::where('user_id', $user->id)
                            ->whereIn('zone_pincode_id', $pincodesToDelete)
                            ->delete();
                    }

                    // Add newly selected pincodes
                    foreach ($pincodesToAdd as $pincode_id) {
                        $zonePincode = ZonePincode::find($pincode_id);
                        if ($zonePincode) {
                            ZonePincodeUser::create([
                                'zone_pincode_id' => $zonePincode->id,
                                'branch_id' => $zonePincode->branch_id,
                                'zone_id' => $zonePincode->zone_id,
                                'user_id' => $user->id,
                            ]);
                        }
                    }
                }
                // End Update Pincode Assignment to Driver


            if (request()->ajax()) {
                return response()->json(
                    [
                        'role' => $role,
                        'status' => 'success',
                        'message' => 'Success',
                        'title' => __('ui.user_information_updated')
                    ]
                );
            }
            return redirect()->route('panel.admin.users.index', '?role=' . $role)->with('success', __('ui.user_information_updated'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['error' => $bug], 500);
            } else {
                return redirect()->back()->with('error', $bug);
            }
        }
    }

    /**
     * remove Specified User .
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        try {
            // Handle encrypted ID if it's not numeric
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                // Prevent direct numeric access if SECURE_ENDPOINT is enabled
                abort(403, 'Unauthorized access.');
            }

            // Find the user or fail
            $user = User::findOrFail($id);

            if ($user->hasRole('driver')) {
                $order = Order::where('assign_to', $user->id)->first();
                if ($order) {
                    return back()->with('error', 'This driver cannot be deleted because one or more orders are assigned to them.');
                }

                $zonePincodeUser = ZonePincodeUser::where('user_id', $user->id)->first();
                if ($zonePincodeUser) {
                    return back()->with('error', 'This driver cannot be deleted because one or more zone pincodes are assigned to them.');
                }
            }

            if ($user->hasRole('user')) {
                $order = Order::where('user_id', $user->id)->first();
                if ($order) {
                    return back()->with('error', 'This customer cannot be deleted because one or more orders are linked to them.');
                }
            }
            // Delete the user
            $user->forceDelete();

            return back()->with('success', __('ui.record_deleted'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', __('ui.record_not_found'));
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Login.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginAs(Request $request, $id)
    {
        try {

            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            if ($id == auth()->id()) {
                return back()->with('error', __('ui.do_not_login_as_yourself'));
            } else {
                $user = User::find($id);
                session(['admin_user_id' => auth()->id()]);
                session(['admin_user_name' => auth()->user()->full_name]);
                session(['temp_user_id' => $user->id]);
                auth()->logout();

                auth()->loginUsingId($user->id);

                $activity['user_id'] = $user->id;
                $activity['ip_address'] = $request->ip();
                $activity['model'] = User::class;
                $activity['model_id'] = $user->id;
                $activity['incident'] = "Login - User {$user->full_name} ({$user->getPrefix()}) logged in by Admin";
                $activity['version'] = getRequestVersion($request);
                $activity['platform'] = getRequestPlatform($request);

                logUserActivity($activity);


                if (AuthRole() == 'Admin') {
                    return redirect(route('panel.admin.dashboard.index'));
                } elseif (AuthRole() == 'User') {
                    return redirect(route('panel.secure.activity.index'));
                } else {
                    return redirect('/');
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Update Status
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, $s)
    {
        try {
            $user = User::find($id);
            $user->update(['status' => $s]);
            $role = $user->roles[0]->display_name ?? '';
            if (request()->ajax()) {
                $message = array('status' => "success", 'message' => 'Success', 'title' => __('ui.user_status_updated'));
                return response()->json($message);
            } else {
                return redirect()->route('panel.admin.users.index', '?role=' . $role)->with('success', __('ui.user_status_updated'));
            }
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * User Verified Status
     *
     * @return \Illuminate\Http\Response
     */
    public function verifiedStatus(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $user = User::find($id);
            $user->email_verified_at = $request->email_verified_at;
            $user->phone_verified_at = $request->phone_verified_at;
            $user->save();

            return redirect()->back()->with('success', 'status updated!');
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Bulk Action
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json([
                    'status' => 'error',
                ]);
                return back()->with('error', __('ui.hands_up'));
            }

            switch ($request->action) {
                case ('delete'):
                    User::whereIn('id', $request->ids)->get()->each->delete();

                    $msg = 'Bulk delete!';
                    $title =  __('ui.deleted') . " " . count($request->ids) . " " . __('ui.records_successfully');
                    break;

                case ('columnUpdate'):
                    User::whereIn('id', $request->ids)->update([
                        $request->column => $request->value
                    ]);

                    switch ($request->column) {
                        case ('status'):
                            $html['badge_color'] = User::STATUSES[$request->value]['color'];
                            $html['badge_label'] = User::STATUSES[$request->value]['label'];

                            $title = __('ui.updated') . " "  . count($request->ids) . " " . __('ui.records_successfully');
                            break;
                        case ('is_verified'):
                            $html['badge_color'] = User::USER_STATUSES[$request->value]['color'];
                            $html['badge_label'] = User::USER_STATUSES[$request->value]['label'];

                            $title = __('ui.updated') . " "  . count($request->ids) . " " . __('ui.records_successfully');
                            break;
                        default:
                            $type = "error";
                            $title = __('ui.no_action_selected');
                    }
                    break;

                default:
                    $type = "error";
                    $title = __('ui.no_action_selected');
            }

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'column' => $request->column,
                    'action' => $request->action,
                    'data' => $request->ids,
                    'title' => $title,
                    'html' => $html,
                ]);
            }

            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', __('ui.error_msg') . $th->getMessage());
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        } elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
        }
        $user = User::whereId($id)->firstOrFail();
        $userKyc = $user->kyc;
        $label = Str::singular($this->label);

        return view('panel.admin.users.show', compact('label', 'user', 'userKyc'));
    }



    /**
     * Session Bulk Action.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sessionBulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status' => 'error',
                    ]
                );
                return back()->with('error', __('ui.hands_up'));
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    ModelSession::whereIn('id', $request->ids)->get()->each->delete();
                    $msg = 'Bulk delete!';
                    $title = __('ui.session') . " "  . count($request->ids) . __('ui.logout_successfully');
                    break;
                default:
                    $type = "error";
                    $title = __('ui.no_action_selected');
            }

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'column' => $request->column,
                        'action' => $request->action,
                        'data' => $request->ids,
                        'title' => $title,
                        'html' => $html,
                    ]
                );
            }

            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', __('ui.error_msg') . $th->getMessage());
        }
    }

    /**
     * Update KYC.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateKycStatus(Request $request)
    {
        $userKyc = UserKyc::whereUserId($request->user_id)->firstOrFail();
        $kyc_info = json_decode($userKyc->details, true);

        if (is_null($kyc_info)) {
            abort(404);
        }
        $new_kyc_info = [
            'document_type' => $kyc_info['document_type'],
            'document_number' => $kyc_info['document_number'],
            'document_front' => $kyc_info['document_front'],
            'document_back' => $kyc_info['document_back'],
            'admin_remark' => $request['remark'],
        ];

        $new_kyc_info = json_encode($new_kyc_info);

        if ($request->status == UserKyc::STATUS_VERIFIED) {
            $mailcontent_data = MailSmsTemplate::where('code', '=', "Verified-KYC")->first();
            if ($mailcontent_data) {
                $arr = [
                    '{id}' => $user->id,
                    '{name}' => NameById($user->id),
                ];
                $action_button = null;
                TemplateMail($user->name, $mailcontent_data, $user->email, $mailcontent_data->type, $arr, $mailcontent_data, $chk_data = null, $mail_footer = null, $action_button);
            }
            $onsite_notification = [
                'title' => __('ui.kyc_accepted'),
                'notification' => __('template.your_kyc_verify'),
                'link' => route('panel.user.verify.index'),
                'user_id' => $request->user_id,
            ];
            pushOnSiteNotification($onsite_notification);
        }

        if ($request->status == UserKyc::STATUS_REJECTED) {
            $mailcontent_data = MailSmsTemplate::where('code', '=', "Rejected-KYC")->first();
            if ($mailcontent_data) {
                $arr = [
                    '{id}' => $user->id,
                    '{name}' => NameById($user->id),
                ];
                $action_button = null;
                TemplateMail($user->name, $mailcontent_data, $user->email, $mailcontent_data->type, $arr, $mailcontent_data, $chk_data = null, $mail_footer = null, $action_button);
            }
            $onsite_notification['user_id'] = $request->user_id;
            $onsite_notification['title'] = __('ui.account_verification_request_rejected');
            $onsite_notification['link'] = route('panel.user.profile.index') . "?active=account";
            $onsite_notification['notification'] = __('template.account_verification_rejected');
            pushOnSiteNotification($onsite_notification);
            $user_kyc = UserKyc::whereUserId($request->user_id);
            $user_kyc->delete();
        }
        $request->status;
        if ($request->status == UserKyc::STATUS_UNDER_APPROVAL) {
            $userKyc->update(
                [
                    'status' => $request->status,
                ]
            );
        }

        $userKyc->update(
            [
                'details' => $new_kyc_info,
                'status' => $request->status,
            ]
        );

        return redirect()->back()->with('success', __('ui.ekyc_updated'));
    }

    /**
     * get user .
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $input = $request->all();
        $users = User::query();
        $users->select(['id', 'first_name', 'last_name', 'email', 'phone']);
        if ($request->has('query') && !empty($input['query'])) {
            $users->whereRoleIs('Driver')
                ->where("first_name", "like", '%' . $input['query'] . '%')
                ->orWhere("last_name", "like", '%' . $input['query'] . '%')
                ->orWhere("email", "like", '%' . $input['query'] . '%')
                ->orWhere("phone", "like", '%' . $input['query'] . '%');
        } else {
            $users->whereRoleIs(['Driver']);
        }
        $users = $users->orderBy('first_name', 'ASC')->get();
        return response()->json($users);
    }

    /**
     * sessionDelete.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sessionDelete(Request $request, $id)
    {
        // Find session by ID
        $session = ModelSession::whereId($id)->firstOrFail();
        if ($session) {
            $session->delete();
            return back()->with('success', __('ui.session_removed'));
        } else {
            return back()->with('error', __('ui.session_not_found'));
        }
    }

    /**
     * remove specified Storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $sessions = ModelSession::find($id);
        try {
            if ($sessions) {
                $sessions->delete();
                return back()->with('success', __('ui.sessions_logout'));
            } else {
                return back()->with('error', __('ui.sessions_log_not_found'));
            }
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Export
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        try {
            $userIds = User::whereHas(
                'roles',
                function ($query) use ($request) {
                    $query->where('name', $request->role);
                }
            )->pluck('id')->toArray();

            return Excel::download(new UsersExport($userIds), $request->role . 's-' . time() .
                '.xlsx');
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Get Permission
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getPermission(Request $request)
    {
        $roleId = $request->input('roleId');
        if (array_key_exists($roleId, User::ADMIN_MEMBER_PERMISSION)) {
            $permissions = User::ADMIN_MEMBER_PERMISSION[$roleId]['permissions'];
            return response()->json([
                'status' => 'success',
                'permissions' => $permissions,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'permissions' => [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function session(Request $request, $id)
    {
        $length = 10;
        if (checkRequestKey('length')) {
            $length = $request->get('length');
        }
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        } elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
        }

        $user = User::find($id);
        $sessions = ModelSession::whereUserId($id);

        if (checkRequestKey('search')) {
            $sessions->whereHas(
                'user',
                function ($sessions) use ($request) {
                    $sessions->where(User::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $request->get('search') . '%');
                }
            )->orWhere('id', 'like', '%' . $request->search . '%');
        }

        if (checkRequestKey(['from', 'to'])) {
            $sessions->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                    \Carbon\Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                ]
            );
        }

        if ($request->get('user')) {
            $sessions->where('user_id', $request->get('user'));
        }

        if (checkRequestKey('asc')) {
            $sessions->orderBy($request->get('asc'), 'asc');
        }

        if (checkRequestKey('desc')) {
            $sessions->orderBy($request->get('desc'), 'desc');
        }
        $sessions = $sessions->latest()->paginate($length);
        //return $sessions;
        if ($request->ajax()) {
            return view('panel.admin.session.load', compact('sessions', 'user'))->render();
        }
        return view('panel.admin.session.index', compact('sessions', 'user'));
    }
}
