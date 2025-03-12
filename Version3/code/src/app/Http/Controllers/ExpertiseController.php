<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use App\Models\Fund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        if (auth()->user()->hasRole('admin')) {
            $experts = Expertise::all();
        } else {
            $experts = Expertise::with('user')->whereHas('user', function ($query) use ($id) {
                $query->where('users.id', '=', $id);
            })->paginate(10);
        }

        return view('expertise.index', compact('experts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expertise.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $r = $request->validate([
            'expert_name' => 'required',
        ]);

        $exp = Expertise::find($request->exp_id);
        $exp_id = $request->exp_id;

        if (auth()->user()->hasRole('admin')) {
            $exp->update($request->all());
        } else {
            $user = User::find(Auth::user()->id);
            $user->expertise()->updateOrCreate(
                ['id' => $exp_id],
                ['expert_name' => $request->expert_name]
            );
        }

        if (empty($request->exp_id))
            $msg = trans('dashboard.expertise_created_successfully');
        else
            $msg = trans('dashboard.expertise_updated_successfully');

        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('experts.index')->with('success', $msg);
        } else {
            return back()->withInput(['tab' => 'expertise']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expertise  $expertise
     * @return \Illuminate\Http\Response
     */
    public function show(Expertise $expertise)
    {
        return response()->json($expertise);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $exp = Expertise::where($where)->first();
        return response()->json($exp);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exp = Expertise::where('id', $id)->delete();
        $msg = trans('dashboard.expertise_deleted_successfully');

        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('experts.index')->with('success', $msg);
        } else {
            return back()->withInput(['tab' => 'expertise']);
        }
    }

    public function getNames(Request $request)
    {
        $locale = $request->locale ?? app()->getLocale();
        $teachers = User::role('teacher')->get()->mapWithKeys(function ($user) use ($locale) {
            if ($locale == 'th') {
                $name = $user->fname_th . ' ' . $user->lname_th;
            } elseif ($locale == 'cn') {
                $name = $user->fname_cn . ' ' . $user->lname_cn;
            } else {
                $name = $user->fname_en . ' ' . $user->lname_en;
            }
            return [$user->id => $name];
        });
        
        return response()->json(['teachers' => $teachers]);
    }
}
