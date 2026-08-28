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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Order;
use Exception;

class SupportTicketsController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'SupportTickets';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // file: app/Http/Controllers/.../SupportTicketController.php

    public function index(Request $request)
    {
        try {
            $perPage = 10;
            $userId = auth()->id();

            $defaultStatus = array_key_first(SupportTicket::STATUSES);
            $activeStatus = $request->input('status', $defaultStatus);

            if (!array_key_exists($activeStatus, SupportTicket::STATUSES)) {
                $activeStatus = $defaultStatus;
            }

            $query = SupportTicket::where('user_id', $userId)
                ->latest();
            $query->where('status', $activeStatus);

            $tickets = $query->paginate($perPage);

            $statuses = SupportTicket::STATUSES;

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('panel.user.support-tickets.partials.ticket-list', compact('tickets', 'statuses'))->render(),
                    'hasMore' => $tickets->hasMorePages(),
                ]);
            }
            return view('panel.user.support-tickets.index', compact('tickets', 'statuses', 'activeStatus'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            if ($request->ticket_type_id == "new") {
                $order = null;
            } else {

                $ticketTypeId = $request->ticket_type_id;
                if (!is_numeric($ticketTypeId)) {
                    $ticketTypeId = secureToken($ticketTypeId, 'decrypt');
                }
                $order = Order::find($ticketTypeId);
            }

            return view('panel.user.support-tickets.create', compact('order'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }


    public function store(Request $request)
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
            if (checkMobileViewActivated()) {
                return redirect()->route('panel.user.support-tickets.index', ['app_back' => true])->with('success', __('ui.ticket_raised_successfully'));
            }

            return redirect()->route('panel.user.support-tickets.index')->with('success', __('ui.ticket_raised_successfully'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }
}
