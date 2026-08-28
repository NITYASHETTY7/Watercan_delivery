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
use App\Http\Requests\WebsitePageRequest;
use App\Models\Country;
use App\Models\WebsitePage;
use Exception;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsitePageController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Website Pages';
    }

    /**
     *  Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function index(Request $request)
    {
        $length = 10;
        $runShimmer = 0;
        if (checkRequestKey('length')) {
            $length = $request->get('length');
        }
        $websitePages = WebsitePage::query();
         if (checkRequestKey('search')) {
            $websitePages->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%');
        };
        if (checkRequestKey(['from', 'to'])) {
            $websitePages->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"]);
        }
        if (checkRequestKey('asc')) {
            $websitePages->orderBy($request->get('asc'), 'asc');
        }
        if (checkRequestKey('desc')) {
            $websitePages->orderBy($request->get('desc'), 'desc');
        }
        //filter
        if (checkRequestKey('status')) {
            $websitePages->where('status', $request->get('status'));
        }
        if ($request->ajax()) {
            $websitePages = $websitePages->latest()->paginate($length);
            return view('panel.admin.website_setup.load', ['websitePages' => $websitePages,'runShimmer' => $runShimmer])->render();
        } else {
            $websitePages = $websitePages->whereId(0)->paginate($length);
            $runShimmer = 1;
        }
            $label = $this->label;
        return view('panel.admin.website_setup.index', compact('websitePages', 'label','runShimmer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        $label = Str::singular($this->label);
        return view('panel.admin.website_setup.create.index', compact('label', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebsitePageRequest $request)
    { 
        try {
            $meta = [
                'title' => $request->page_meta_title ?? "",
                'description' => $request->page_meta_description ?? "",
                'keywords' => $request->page_keywords ?? "",
            ];
            $websitePage = new WebsitePage();
            $websitePage->title = $request->title;
            $websitePage->slug = $request->slug;
            $websitePage->is_permanent = $request->is_permanent ?? 0;
            $websitePage->content = $request->content;
            $websitePage->status = $request->status ?? 0;
            $websitePage->meta = $meta;
            if ($request->hasFile('page_meta_image')) {
                $files = $request->file('page_meta_image');
                $allowedExtensions = ['jpg','jpeg' ,'png'];
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

                $this->uploadFileInMedia($websitePage,$files,'website_meta_image');
            }


            $websitePage->save();
            if (request()->ajax()) {
                return response()->json([
                       'status' => 'success',
                       'message' => 'Success',
                       'title' => __('ui.page_added')
                    ]);
            }
            return redirect()->route('panel.admin.website-pages.index')->with('success',  __('ui.page_added'));
        } catch (\Exception $e) {
            return back()->with('error',  __('ui.error_msg') . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebsitePage $websitePage
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);

        }
         $websitePage = WebsitePage::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('panel.admin.website_setup.edit.index', compact('websitePage', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\WebsitePage  $websitePage
     * @return \Illuminate\Http\Response
     */
    public function update(WebsitePageRequest $request, $id)
    {
        try {
            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);

            }

            $meta = [
                'title' => $request->page_meta_title ?? "",
                'description' => $request->page_meta_description ?? "",
                'keywords' => $request->page_keywords ?? "",
            ];
            $websitePage = WebsitePage::whereId($id)->firstOrFail();
            if(!$websitePage){
                return back()->with('error', __('ui.website_page_not_found'));
            }
            $websitePage->title = $request->title;
            $websitePage->slug = $request->slug;
            $websitePage->content = $request->content;
            $websitePage->status = $request->status ?? 0;
            $websitePage->is_permanent = $request->is_permanent;
            $websitePage->meta = $meta;
            $websitePage->save();

            // Image with Give Extension
            if ($request->hasFile('page_meta_image')) {
                $files = $request->file('page_meta_image');
                $allowedExtensions = ['jpg','jpeg' ,'png'];
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

                $this->uploadFileInMedia($websitePage,$files,'website_meta_image');
            }

            $websitePage->save();
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => __('ui.page_updated')
                    ]);
            }
            return redirect()->route('panel.admin.website-pages.index')->with('success', __('ui.page_updated'));
        } catch (\Exception $e) {
            return back()->with('error',__('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebsitePage $websitePage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,$id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);

            }
            $websitePage = WebsitePage::whereId($id)->firstOrFail();
            if ($websitePage) {
                $websitePage->delete();
                return back()->with('success', __('ui.page_deleted'));
            } else {
                return back()->with('error', __('ui.page_not_found'));
            }
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Remove Media resource from storage.
     *
     * @param  \App\Models\WebsitePage $websitePage
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia(Request $request,$id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);

            }
            $websitePage = WebsitePage::whereId($id)->firstOrFail();
            if ($websitePage) {
                if ($websitePage->getMedia($request->media)->count()) {
                    $websitePage->clearMediaCollection($request->media);
                }
                return back()->with('success', __('ui.media_deleted_successfully'));
            } else {
                return back()->with('error', __('ui.media_not_found'));
            }
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Bulk Action.
     *
     * @param  \App\Models\WebsitePage $websitePage
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
                return back()->with('error',  __('ui.hands_up'));
            }

            switch ($request->action) {
// Delete
                case ('delete'):
                WebsitePage::whereIn('id', $request->ids)->get()->each->delete();
                    $msg = 'Bulk delete!';
                    $title =  __('ui.deleted') . " " . count($request->ids). " " . __('ui.records_successfully');

                    break;
// Column Update
                case ('columnUpdate'):
                WebsitePage::whereIn('id', $request->ids)->update([
                        $request->column => $request->value
                ]);
                    switch ($request->column) {
                    // Column Status Output Generation
                        case ('is_published'):
                $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Publish" : "Unpublish";
                            $title =  __('ui.update') . " " .count($request->ids). " " . __('ui.records_successfully');

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
}
