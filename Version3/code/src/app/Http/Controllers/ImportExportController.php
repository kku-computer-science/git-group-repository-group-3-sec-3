<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ImportExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
       $roles = Role::pluck('name', 'name')->all();
       return view('users.import', compact('roles'));
    }
   
    /**
     * Import users from file.
     *
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request) 
    {
        $validatedData = $request->validate([
           'file' => 'required',
        ]);

        Excel::import(new UsersImport, $request->file('file'));
        return redirect('importfiles')
            ->with('status', trans('dashboard.file_imported_successfully'));
    }

    /**
     * Export users.
     *
     * @return \Illuminate\Support\Collection
     */
    public function export($slug) 
    {
        return Excel::download(new UsersExport, 'users.' . $slug);
    }
}
