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

use App\Exports\GeneralSettingExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use App\Models\Setting;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class GeneralController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'General Setting';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label = $this->label;
        $noOfDecimal = Setting::NO_OF_DECIMAL;
        return view('panel.admin.general.index', compact('label','noOfDecimal'));
    }

    public function maintenanceIndex()
    {
        return view('panel.admin.maintenance.index')->with('label', $this->label);
    }

    public function contentGroup()
    {
        return view('panel.admin.maintenance.index');
    }

    public function storageLink()
    {
        try {
            shell_exec('pwd');
            shell_exec('cd ' . base_path('public'));
            shell_exec('rm -rf storage');
            \File::link(storage_path('app\public'), public_path('storage'));
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 1000);
            return back()->with('success', __('ui.storage_linked_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . ' ' . $e->getMessage());
        }
    }

    public function OptimizeClear()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            return back()->with('success', __('ui.optimization_cleared'));
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . ' ' . $e->getMessage());
        }
    }

    public function sessionClear()
    {
        try {
            $directory = config('session.files');
            $ignoreFiles = ['.gitignore', '.', '..'];
            $files = scandir($directory);
            foreach ($files as $file) {
                if (!in_array($file, $ignoreFiles)) {
                    unlink($directory . '/' . $file);
                }
            }
            return back()->with('success', __('ui.optimization_cleared'));
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . ' ' . $e->getMessage());
        }
    }

    public function backup()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return redirect()->back()->with('success', __('ui.backup_taken'));
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . ' ' . $e->getMessage());
        }
    }

    public function single_env_key_update($key, $value)
    {
        $file = DotenvEditor::load();
        $file->setKey($key, (string)$value);
        $file->save();
    }

    public function export(Request $request)
    {
        try {
            $itemIds = Setting::all()->pluck('id')->toArray();
            return Excel::download(new GeneralSettingExport($itemIds), 'gen-settings-' . time() . '.csv');
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . ' ' . $e->getMessage());
        }
    }
}
