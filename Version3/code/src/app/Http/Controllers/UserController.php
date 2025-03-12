<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ResearchProject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new instance of the class.
     *
     * @return void
     */
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();
        return view('users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $departments = Department::all();
        return view('users.create', compact('roles','departments'));
    }

    public function getCategory(Request $request)
    {
        $cat = $request->cat_id;
        $subcat = Program::with('degree')->where('department_id', 1)->get();
        return response()->json($subcat);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fname_en' => 'required',
            'lname_en' => 'required',
            'fname_th' => 'required',
            'lname_th' => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'roles'    => 'required',
            'sub_cat'  => 'required',
        ]);
    
        $user = User::create([  
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'fname_en'  => $request->fname_en,
            'lname_en'  => $request->lname_en,
            'fname_th'  => $request->fname_th,
            'lname_th'  => $request->lname_th,
        ]);
        
        $user->assignRole($request->roles);
    
        $pro_id = $request->sub_cat;
        $program = Program::find($pro_id);
        $user = $user->program()->associate($program)->save();
    
        return redirect()->route('users.index')
            ->with('success', trans('dashboard.user_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $departments = Department::all();
        $id = $user->program->department_id;
        $programs = Program::whereHas('department', function($q) use ($id){    
            $q->where('id', '=', $id);
        })->get();
        
        $roles = Role::pluck('name', 'name')->all();
        $deps = Department::pluck('department_name_EN','department_name_EN')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $userDep = $user->department()->pluck('department_name_EN','department_name_EN')->all();
        return view('users.edit', compact('user', 'roles','deps', 'userRole','userDep','programs','departments'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'fname_en' => 'required',
            'fname_th' => 'required',
            'lname_en' => 'required',
            'lname_th' => 'required',
            'email'    => 'required|email|unique:users,email,'.$id,
            'password' => 'confirmed',
            'roles'    => 'required'
        ]);
    
        $input = $request->all();
        
        if(!empty($input['password'])) { 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
    
        DB::table('model_has_roles')
            ->where('model_id', $id)
            ->delete();
    
        $user->assignRole($request->input('roles'));
        $pro_id = $request->sub_cat;
        $program = Program::find($pro_id);
        $user = $user->program()->associate($program)->save();
    
        return redirect()->route('users.index')
            ->with('success', trans('dashboard.user_updated_successfully'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', trans('dashboard.user_deleted_successfully'));
    }
    
    function profile(){
        return view('dashboards.users.profile');
    }
    
    function updatePicture(Request $request){
        $path = 'images/imag_user/';
        $file = $request->file('admin_image');
        $new_name = 'UIMG_'.date('Ymd').uniqid().'.jpg';
        
        $upload = $file->move(public_path($path), $new_name);
     
        if(!$upload){
            return response()->json([
                'status' => 0,
                'msg'    => trans('dashboard.picture_upload_failed')
            ]);
        } else {
            $oldPicture = User::find(Auth::user()->id)->getAttributes()['picture'];
            if($oldPicture != ''){
                if(\File::exists(public_path($path.$oldPicture))){
                    \File::delete(public_path($path.$oldPicture));
                }
            }
    
            $update = User::find(Auth::user()->id)->update(['picture'=>$new_name]);
    
            if(!$update){
                return response()->json([
                    'status' => 0,
                    'msg'    => trans('dashboard.picture_update_failed')
                ]);
            } else {
                return response()->json([
                    'status' => 1,
                    'msg'    => trans('dashboard.profile_picture_updated_successfully')
                ]);
            }
        }
    }
}
