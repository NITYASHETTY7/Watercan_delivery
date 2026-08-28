<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Admin;

use App\Exports\SupportTicketsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTicketRequest;
use App\Models\SupportTicket;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class SupportTicketController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Support Tickets';
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
        $supportTickets = SupportTicket::query();
        if (checkRequestKey(['from', 'to'])) {
            $supportTickets->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00', \Carbon\Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"]);
        }
        if (checkRequestKey('search')) {
            $supportTickets->where('subject', 'like', '%' . $request->get('search') . '%')
                ->orWhereHas('user', function ($q) use ($request) {

                    $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . trim($request->search) . '%');
                });
        }
        if (checkRequestKey('asc')) {
            $supportTickets->orderBy($request->get('asc'), 'asc');
        }
        if (checkRequestKey('desc')) {
            $supportTickets->orderBy($request->get('desc'), 'desc');
        }

        if (checkRequestKey('customer_type')) {
            $supportTickets->whereHas('user', function ($q) use ($request) {
                $q->where('account_type', $request->customer_type);
            });
        }

        if (checkRequestKey('role')) {
            $supportTickets->whereHas('user.roles', function ($q) use ($request) {
                $q->where('display_name', $request->role);
            });
        }


        if (request()->has('status') && request()->get('status') != null) {
            $supportTickets->where('status', request()->get('status'));
        }


        if (request()->has('subject') && request()->get('subject') != null) {
            $supportTickets->where('subject', request()->get('subject'));
        }
        if ($request->ajax()) {
            $supportTickets = $supportTickets->latest()->paginate($length);
            return view('panel.admin.support_tickets.load', ['supportTickets' => $supportTickets, 'runShimmer' => $runShimmer])->render();
        } else {
            $supportTickets = $supportTickets->whereId(0)->paginate($length);
            $runShimmer = 1;
        }

        $statuses = SupportTicket::STATUSES;

        $accountTypes = User::ACCOUNT_TYPES;

        $roles = Role::whereIn('display_name', ['User', 'Driver'])->get();

        $label = $this->label;
        return view('panel.admin.support_tickets.index', compact('supportTickets', 'statuses', 'label', 'runShimmer','accountTypes','roles'));
    }

    /**
     * media
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = SupportTicket::STATUSES;
        $priorities = SupportTicket::PRIORITIES;
        $users = User::whereRoleIs('user')->get();
        $categories = getCategoriesByCode('SupportTicketCategories');
        $label = \Str::singular($this->label);
        return view('panel.admin.support_tickets.create.index', compact('statuses', 'users', 'categories', 'priorities', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupportTicketRequest $request)
    {

        try {
            $supportTicket = SupportTicket::create([
                'user_id' => $request->get('user_id') ?? null,
                'subject' => $request->get('subject') ?? null,
                'message' => $request->get('message') ?? null,
                'priority' => $request->get('priority') ?? null,
                'ticket_type_id' => $request->get('ticket_type_id') ?? null,
                'status' => SupportTicket::STATUS_UNDER_WORKING,
            ]);
            // $ticket = Conversation::create([
            //     'type_id' => $supportTicket->id,
            //     'user_id' => $supportTicket->user_id,
            //     'type' => SupportTicket::class,
            //     'comment' => $supportTicket->message
            // ]);
            // Image with Give Extension
            if ($request->hasFile('attachment')) {
                $files = $request->file('attachment');
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, $allowedExtensions)) {
                        $responseData = [
                            'status' => 'error',
                            'message' => 'Error',
                            'title' => __('ui.each_file_extension_image'),
                        ];
                        return responseOrRedirect($request, $responseData);
                    }
                }

                $this->uploadFileInMedia($ticket, $files, 'ticket_file');
            }

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => __('ui.ticket_raised_successfully')
                ]);
            }
            return redirect()->route('panel.admin.support-tickets.index')->with('success', __('ui.ticket_raised_successfully'));
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        } elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
        }
        $supportTicket = SupportTicket::whereId($id)->firstOrFail();
        $statuses = SupportTicket::STATUSES;
        $receiver = $supportTicket->user_id;
        $sender = auth()->id();
        $label = \Str::singular($this->label);
        $user = auth()->user();
        $receiver = User::find($supportTicket->user_id);
        return view('panel.admin.support_tickets.show', compact('supportTicket', 'statuses', 'sender', 'receiver', 'label','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        } elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
        }
        $supportTicket = SupportTicket::whereId($id)->firstOrFail();
        $statuses = SupportTicket::STATUSES;
        $priorities = SupportTicket::PRIORITIES;
        $users = User::whereRoleIs('user')->get();
        $admins = User::whereRoleIs('admin')->where('id', '!=', auth()->id())->get();
        $categories = getCategoriesByCode('SupportTicketCategories');
        $label = Str::singular($this->label);
        return view('panel.admin.support_tickets.edit.index', compact('supportTicket', 'statuses', 'users', 'admins',  'categories', 'priorities', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $supportTicket = SupportTicket::whereId($id)->firstOrFail();

            $supportTicket->user_id = $request->user_id;
            $supportTicket->subject = $request->subject;
            $supportTicket->message = $request->message;
            $supportTicket->ticket_type_id = $request->ticket_type_id;
            $supportTicket->priority = $request->priority;
            $supportTicket->assign_to = $request->assign_to;
            $supportTicket->save();
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => __('ui.support_ticket_updated')
                ]);
            }
            return redirect()->route('panel.admin.support-tickets.index')->with('success', __('ui.support_ticket_updated'));
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $supportTicket = SupportTicket::whereId($id)->firstOrFail();
            if ($supportTicket) {
                $supportTicket->delete();
                return back()->with('success', __('ui.support_ticket_deleted'));
            } else {
                return back()->with('error', __('ui.support_ticket_not_found'));
            }
        // } catch (Exception $e) {
        //     $responseData = [
        //         'status' => 'error',
        //         'message' => 'Error',
        //         'title' => __('ui.error_msg') . $e->getMessage(),
        //     ];
        //     return responseOrRedirect(request(), $responseData);
        // }
    }

    /**
     *  Bulk Action .
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkAction(SupportTicket $supportTicket, Request $request)
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
                // Delete
                case ('delete'):
                    SupportTicket::whereIn('id', $request->ids)->get()->each->delete();
                    $msg = 'Bulk delete!';
                    $title = __('ui.deleted') . " " .  count($request->ids) . " " . __('ui.records_successfully');

                    break;
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
                ]);
            }
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }

    /**
     *  Status Update .
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $user = auth()->user();
            $supportTicket = SupportTicket::whereId($id)->firstOrFail();

            if ($supportTicket) {
                $supportTicket->update([
                    'status' => $status,
                    'reply'  => $request->reply
                ]);
                $data['user_id'] =  $supportTicket->user_id;
                $data['title'] = __('ui.ticket_resolved');
                $data['link'] = '#';
                $data['notification'] =  __('template.ticket_resolved_notification', [
                    'ticket_id' => $supportTicket->getPrefix(),
                    'auth_user' => $user->full_name,
                    'auth_user_id' => $user->getPrefix(),
                ]);


                pushOnSiteNotification($data);
                return back()->with('success', __('ui.status_update'));
            }
            return back()->with('error', __('ui.support_ticket_not_found'));
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }

    /**
     *  Attachment .
     *
     * @return \Illuminate\Http\Response
     */
    public function addAttachment(SupportTicketRequest $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        } elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
        }
        $supportTicket = SupportTicket::whereId($id)->firstOrFail();
        if ($request->hasFile('file_name') && $request->file('file_name')->isValid()) {
            if ($supportTicket->getMedia('file_name')->count()) {
                $supportTicket->clearMediaCollection('file_name');
            }
            // Image with Give Extension
            if ($request->hasFile('file_name')) {
                $files = $request->file('file_name');
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, $allowedExtensions)) {
                        $responseData = [
                            'status' => 'error',
                            'message' => 'Error',
                            'title' => __('ui.each_file_extension_image'),
                        ];
                        return responseOrRedirect($request, $responseData);
                    }
                }

                $this->uploadFileInMedia($supportTicket, $files, 'ticket_file');
            }

            // $supportTicket->addMediaFromRequest('file_name')->usingFileName(time().'.'.$request->file_name->extension())->toMediaCollection('file');
            // return back()->with('success', 'Attachment Added successfully');
        }
    }
    /**
     * Reply .
     *
     * @return \Illuminate\Http\Response
     */
    public function reply(SupportTicketRequest $request)
    {
        try {
            $supportTicket = SupportTicket::whereId($request->id)->first();
            if ($supportTicket) {
                $supportTicket->reply = $request->reply;
                $supportTicket->status = 1;
                $supportTicket->save();
                return redirect()->route('panel.admin.support-tickets.index')->with('success', __('ui.replied_successfully'));
            } else {
                return back()->with('error', __('ui.support_ticket_not_found'));
            }
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }

    /**
     * Export .
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        try {
            $userIds = SupportTicket::pluck('id')->toArray();
            return Excel::download(new SupportTicketsExport($userIds), 'support-tickets-' . time() . '.xlsx');
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }


    /**
     * Meeting .
     *
     * @return \Illuminate\Http\Response
     */
    public function startMeet(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $supportTicket = SupportTicket::whereId($id)->firstOrFail();
            if ($supportTicket) {
                $user = auth()->user();
                //TODO: JITSI Meeting Link Creation
                $token = '';
                $token .= substr($user->first_name, 0, 4);
                $token .= substr($user->last_name, 0, 4);
                $token .=  date('dmY');
                $token .= "-";
                $token .= rand(10000, 99999);
                $meeting_link = "https://meet.jit.si/" . $token;

                // Storing
                // Conversation::create([
                //     'type_id' => $supportTicket->id,
                //     'user_id' => $user->id,
                //     'receiver_id' =>  $supportTicket->user_id,
                //     'type' => SupportTicket::class,
                //     'comment' =>  $user->full_name . ' ' . __('ui.click_here_to_join') . ' ' . $meeting_link
                // ]);
                return back()->with('success', __('ui.meeting_room_created'));
            }
        } catch (Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }
}
