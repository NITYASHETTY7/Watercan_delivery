<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Products';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $length = 10;
            $runShimmer = 0;
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
    
            $products = Product::query();
    
            if (checkRequestKey('search')) {
                $products->where(
                    function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->get('search') . '%')
                            ->orWhere('price', 'like', '%' . $request->get('search') . '%')
                            ->orWhere('weight', 'like', '%' . $request->get('search') . '%');
                    }
                );
            }
    
            if (checkRequestKey(['from', 'to'])) {
                $products->whereBetween(
                    'created_at',
                    [
                        Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                        Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                    ]
                );
            }
    
            if (checkRequestKey('is_published')) {
                $products->where('is_published', $request->get('is_published'));
            }
    
            if (checkRequestKey('asc')) {
                $products->orderBy($request->get('asc'), 'asc');
            }
    
            if (checkRequestKey('desc')) {
                $products->orderBy($request->get('desc'), 'desc');
            }
    
            $label = $this->label;
            $is_published = Product::IS_PUBLISHED;
    
            if ($request->ajax()) {
                $products = $products->latest()->paginate($length);
                return view('panel.admin.products.load', ['products' => $products, 'runShimmer' => $runShimmer])->render();
            } else {
                $products = $products->whereId(0)->paginate($length);
                $runShimmer = 1;
            }
            return view('panel.admin.products.index', compact('products', 'label', 'runShimmer', 'is_published'));
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            if ($request->wantsJson()) {
                return response()->json(['error' => $errorMessage], 500);
            } else {
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $is_published = Product::IS_PUBLISHED;
            $label = Str::singular($this->label);
            return view('panel.admin.products.create.index', compact('is_published', 'label'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            if(!$request->is_published) {
                $request['is_published'] =  0; 
            }
            Product::create($request->all());
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Product Created Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Product Created Successfully')->withInput();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $label = Str::singular($this->label);
            $is_published = Product::IS_PUBLISHED;
            $product = Product::where('id', $id)->firstOrFail();
            return view('panel.admin.products.edit.index', compact('product', 'is_published', 'label'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            if(!$request->is_published) {
                $request['is_published'] =  0; 
            }
            $product = Product::where('id', $id)->firstOrFail();
            $product->update($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Product Updated Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Product Updated Successfully')->withInput();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'error',
                    'title' => $errorMessage
                ]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
           
            $product = Product::where('id', $id)->firstOrFail();
            $product->delete();
            return redirect()->back()->with('success', 'Product Deleted Successfully')->withInput();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
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
                    Product::whereIn('id', $request->ids)->get()->each->delete();

                    $msg = 'Bulk delete!';
                    $title =  __('ui.deleted') . " " . count($request->ids) . " " . __('ui.records_successfully');
                    break;
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
