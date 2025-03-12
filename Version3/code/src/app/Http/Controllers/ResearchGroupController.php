<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ResearchGroup;
use Illuminate\Http\Request;
use App\Models\Fund;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ResearchGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:groups-list|groups-create|groups-edit|groups-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:groups-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:groups-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:groups-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $researchGroups = ResearchGroup::with('User')->get();
        return view('research_groups.index', compact('researchGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['teacher', 'student'])->get();
        $funds = Fund::get();
        return view('research_groups.create', compact('users', 'funds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name_th' => 'required',
            'group_name_en' => 'required',
            'head'          => 'required',
            //'group_image'  => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        $input = $request->all();

        if ($request->group_image) {
            $input['group_image'] = time() . '.' . $request->group_image->extension();
            $request->group_image->move(public_path('img'), $input['group_image']);
        }

        $researchGroup = ResearchGroup::create($input);
        $head = $request->head;
        $fund = $request->fund;
        $researchGroup->user()->attach($head, ['role' => 1]);

        if ($request->moreFields) {
            foreach ($request->moreFields as $key => $value) {
                if ($value['userid'] != null) {
                    $researchGroup->user()->attach($value, ['role' => 2]);
                }
            }
        }
        return redirect()->route('researchGroups.index')
            ->with('success', trans('dashboard.research_group_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fund  $researchGroup
     * @return \Illuminate\Http\Response
     */
    public function show(ResearchGroup $researchGroup)
    {
        return view('research_groups.show', compact('researchGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fund  $researchGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchGroup $researchGroup)
    {
        $researchGroup = ResearchGroup::find($researchGroup->id);
        $this->authorize('update', $researchGroup);
        $researchGroup = ResearchGroup::with(['user'])->where('id', $researchGroup->id)->first();
        $users = User::get();
        return view('research_groups.edit', compact('researchGroup', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ResearchGroup  $researchGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchGroup $researchGroup)
    {
        $request->validate([
            'group_name_th' => 'required',
            'group_name_en' => 'required',
        ]);

        $input = $request->all();

        if ($request->group_image) {
            $input['group_image'] = time() . '.' . $request->group_image->extension();
            $request->group_image->move(public_path('img'), $input['group_image']);
        }

        $researchGroup->update($input);
        $head = $request->head;
        $researchGroup->user()->detach();
        $researchGroup->user()->attach([
            $head => ['role' => 1],
        ]);

        if ($request->moreFields) {
            foreach ($request->moreFields as $key => $value) {
                if ($value['userid'] != null) {
                    $researchGroup->user()->attach($value, ['role' => 2]);
                }
            }
        }
        return redirect()->route('researchGroups.index')
            ->with('success', trans('dashboard.research_group_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fund  $researchGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchGroup $researchGroup)
    {
        $this->authorize('delete', $researchGroup);
        $researchGroup->delete();
        return redirect()->route('researchGroups.index')
            ->with('success', trans('dashboard.research_group_deleted_successfully'));
    }
}
