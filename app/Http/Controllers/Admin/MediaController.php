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

use App\Http\Requests\MediaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Exception;

class MediaController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaRequest $request)
    {
        try {
            $model = "\App\Models\\" . $request->model_type;
            $record = $model::whereId($request->model_id)->first();
            if ($record) {
                if ($record->getMedia($request->media)->count()) {
                    $record->clearMediaCollection($request->media);
                }
                if ($request->ajax()) {
                    return response()->json(['message' => __('ui.media_deleted_successfully')], 200);
                } else {
                    return back()->with('success', __('ui.media_deleted_successfully'));
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['error' => __('ui.media_not_found')], 401);
                } else {
                    return back()->with('error', __('ui.media_not_found'));
                }
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('error', __('ui.error_msg') . $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyById($id, MediaRequest $request)
    {
        try {
            $media = Media::find($id);
            if ($media) {
                $model_type = $media->model_type;
                $model = $model_type::find($media->model_id);
                $model->deleteMedia($media->id);
                if ($request->ajax()) {
                    return response()->json(['status' => 'success', 'message' => __('ui.media_deleted_successfully')], 200);
                } else {
                    return back()->with('success', __('ui.media_deleted_successfully'));
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'error' => __('ui.media_not_found')], 401);
                } else {
                    return back()->with('error', __('ui.media_not_found'));
                }
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('error', __('ui.error_msg') . $e->getMessage());
            }
        }
    }

    public function uploadEditorImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(str_replace('/core/public', "", $_SERVER['DOCUMENT_ROOT']."/media"), $fileName);
            $url = asset('media/' . $fileName);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }
}
