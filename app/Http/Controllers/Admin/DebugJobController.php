<?php

/**
 *
 * @category  GRC Tool
 *
 * @ref zStarter
 * @author <zStarter>
 * @license <zStarter>
 * @version <RC: 1.2.0>
 * @link <https://riskcognizance.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DebugJobController extends Controller
{
    public function index(Request $request)
    {

        $tableName = $request->query('table_name') ?: '';
        $perPage = (int) $request->query('per_page', 10) ?: 10;

        if (!in_array($tableName, ['jobs', 'failed_jobs'], true)) {
            $errorMessage = __("ui.invalid_table_error");

            return redirect()->back()->with('error', $errorMessage)->withInput();
        }

        // Fetch column names
        $columns = !empty($tableName) ? Schema::getColumnListing($tableName) : [];

        $orderBy = ($tableName == 'failed_jobs') ? 'failed_at' : 'created_at';
        // Paginate data
        $data = DB::table($tableName)->orderBy($orderBy, 'DESC')->paginate($perPage);

        return view('panel.admin.debug_jobs.index', compact('tableName', 'columns', 'data'));
    }


    public function destroy(Request $request)
    {
        $tableName = $request->query('table_name') ?? '';

        if (!in_array($tableName, ['jobs', 'failed_jobs'], true)) {
            return redirect()->back()->with('error', 'Invalid table name, only jobs & failed_jobs can be cleared.');
        }
        DB::table($tableName)->truncate();
        return back()->with('success', "All records from the table '{$tableName}' have been deleted.");
    }
}
