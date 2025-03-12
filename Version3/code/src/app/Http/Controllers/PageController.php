<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Stroage;
use App\Models\Product;
use App\Models\ResearchGroup;

class PageController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function uploadpage(Request $request)
    {
        //$researchGroup = ResearchGroup::with('User')->find($request);
        $product = Product::with('group')->where('id', '=', 1)->get();
        $researchGroup = ResearchGroup::find(1);
        //dd($product);
        $place_id = 3;
        $this->authorize('create', [Product::class, $place_id]);
        //dd($request->group_id);
        //$this->authorize('upload', Product::class);
        //dd($researchGroup);
        return view('research_groups.uploadfiles.product');
    }

    public function store(Request $request)
    {
        $researchGroup = ResearchGroup::find($request->group_id);
        
        $products = Product::with('group')->where('id', '=', $request->group_id);

        $this->authorize('create', [Product::class, $request->group_id]);
        
        $data = new Product();
        $file = $request->file;
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $request->file->move('files', $filename);
        $data->file = $filename;
        $data->name = $request->name;
        $data->description = $request->description;
        
        //$researchGroup = ResearchGroup::find($request->group_id);
        $data->group()->associate($researchGroup);
        $data->save();

        return redirect()->back();
    }

    public function show()
    {
        $data = Product::all();
        return view('research_groups.uploadfiles.showproduct', compact('data'));
    }

    public function download(Request $request, $file)
    {
        return response()->download(public_path('files/' . $file));
    }

    public function delete(Request $request)
    {
        $file = Product::find($request->id);
        //$researchGroup = ResearchGroup::find($request->group_id);
        //$file = Product::with('group')->where('id', '=', $request->group_id);

        $this->authorize('delete', [Product::class, $request->group_id]);
        unlink(public_path('files/' . $file->file)); 
        Product::where("id", $file->id)->delete();

        return back()->with("success", trans('dashboard.file_deleted_successfully'));
    }
    
    public function uploadFile(Request $request, $id)
    {
        dd($request);
        $data = Product::find($id);
        $file = $request->file;
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $request->file->move('files', $filename);
        $data->file = $filename;
        
        $data->save();

        return redirect()->back();
    }
}
