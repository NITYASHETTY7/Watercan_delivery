<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;

class ThemeController extends Controller
{
    public $label;

    function __construct()
    {

        $this->label = request()->get('role') ?? 'Choose Theme';
    }
    public function index(Request $request)
    {
        // // Checking Plan = Allowed for Pro Plan Users Only
        // if(!isCompanyHasPro(getCompanyId())){
        //     return back()->with('error','This feature is only avilable for PRO Users. Please Upgrade your membership to unlock this feature today!');
        // }
        // // End Checking Plan

        $label = $this->label;
        $themes = [
            [
                'id'   => '1',
                'title' => 'Plain Theme',
                'description' => 'This is the description for Card 1.',
                'color' => 'success',
                'image' => 'company/images/card/plain.png',
            ],
            [
                'id'   => '2',
                'title' => 'Dark Theme',
                'description' => 'This is the description for Card 2.',
                'color' => 'dark',
                'image' => 'company/images/card/dark.png',
            ],
            [
                'id'   => '3',
                'title' => ' Primary Theme',
                'description' => 'This is the description for Card 3.',
                'color' => 'primary',
                'image' => 'company/images/card/primary.png',
            ],
        ];
        return view('panel.admin.profile.include.index', compact('label','themes'));

    }

    public function store(Request $request)
    {
        try{
            $user = User::find(auth()->id());
            if($user){
                $user->theme_id = $request->theme_id;
                $user->save();
            }

               if($request->ajax())
                return response()->json([
                    'id'=> $request->theme_id,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Theme applied successfully!'
                ]);
            else
            return redirect()->route('panel.admin.personalization.index')->with('success','Tax Created Successfully!');
        }catch(Exception $e){
            $bug = $e->getMessage();
            if(request()->ajax())
            return  response()->json([$bug]);
            else
            return redirect()->back()->with('error', $bug)->withInput($request->all());
        }
    }
}
