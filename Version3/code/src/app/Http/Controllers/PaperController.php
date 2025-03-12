<?php

namespace App\Http\Controllers;

use App\Exports\ExportPaper;
use App\Exports\ExportUser;
use App\Exports\UsersExport;
use App\Models\Author;
use App\Models\Paper;
use App\Models\Source_data;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class PaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('staff')) {
            $papers = Paper::with('teacher', 'author')->orderBy('paper_yearpub', 'desc')->get();
        } else {
            $papers = Paper::with('teacher', 'author')->whereHas('teacher', function ($query) use ($id) {
                $query->where('users.id', '=', $id);
            })->orderBy('paper_yearpub', 'desc')->get();
        }
        return view('papers.index', compact('papers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $source = Source_data::all();
        $users = User::role(['teacher', 'student'])->get();
        return view('papers.create', compact('source', 'users'));
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
            'paper_name'       => 'required|unique:papers,paper_name',
            'paper_type'       => 'required',
            'paper_sourcetitle'=> 'required',
            // 'paper_url'      => 'required',
            'paper_yearpub'    => 'required',
            'paper_volume'     => 'required',
            //'paper_issue'     => 'required',
            //'paper_citation'  => 'required',
            //'paper_page'      => 'required',
            'paper_doi'        => 'required',
        ]);
        
        $input = $request->except(['_token']);
        $key = $input['keyword'];
        $key = explode(', ', $key);
        $myNewArray = [];
        foreach ($key as $val) {
            $a['$'] = $val;
            array_push($myNewArray, $a);
        }
        $input['keyword'] = $myNewArray;
        $paper = Paper::create($input);
        
        foreach ($request->cat as $key => $value) {
            $paper->source()->attach($value);
        }
        
        foreach ($request->moreFields as $key => $value) {
            if ($value['userid'] != null) {
                $paper->teacher()->attach($value, ['author_type' => $request->pos[$key]]);
            }
        }
        
        $x = 1;
        if (isset($input['fname'][0]) and (!empty($input['fname'][0]))) {
            foreach ($request->input('fname') as $key => $value) {
                $data['fname'] = $input['fname'][$key];
                $data['lname'] = $input['lname'][$key];
                if (Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first() == null) {
                    $author = new Author;
                    $author->author_fname = $data['fname'];
                    $author->author_lname = $data['lname'];
                    $author->save();
                    $paper->author()->attach($author, ['author_type' => $request->pos2[$key]]);
                } else {
                    $author = Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first();
                    $authorid = $author->id;
                    $paper->author()->attach($authorid, ['author_type' => $request->pos2[$key]]);
                }
                $x++;
            }
        }
        return redirect()->route('papers.index')
            ->with('success', trans('dashboard.papers_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function show(Paper $paper)
    {
        $k = collect($paper['keyword']);
        $val = $k->implode('$', ', ');
        $paper['keyword'] = $val;
        return view('papers.show', compact('paper'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $id = decrypt($id);
            $paper = Paper::find($id);
            $k = collect($paper['keyword']);
            $val = $k->implode('$', ', ');
            $paper['keyword'] = $val;
            $this->authorize('update', $paper);
            $sources = Source_data::pluck('source_name', 'source_name')->all();
            $paperSource = $paper->source->pluck('source_name', 'source_name')->all();
            $users = User::role(['teacher', 'student'])->get();
            return view('papers.edit', compact('paper', 'users', 'paperSource', 'sources'));
        } catch (DecryptException $e) {
            return abort(404, trans('dashboard.page_not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paper $paper)
    {
        $this->validate($request, [
            //'paper_name'       => 'required|unique:papers,paper_name',
            'paper_type'        => 'required',
            'paper_sourcetitle' => 'required',
            // 'paper_url'       => 'required',
            //'paper_yearpub'    => 'required',
            'paper_volume'      => 'required',
            'paper_issue'       => 'required',
            'paper_citation'    => 'required',
            'paper_page'        => 'required',
            // 'paper_doi'      => 'required',
        ]);
        $input = $request->except(['_token']);
        $key = $input['keyword'];
        $key = explode(', ', $key);
        $myNewArray = [];
        foreach ($key as $val) {
            $a['$'] = $val;
            array_push($myNewArray, $a);
        }
        $input['keyword'] = $myNewArray;
        
        $paper->update($input);
        $paper->author()->detach();
        $paper->teacher()->detach();
        $paper->source()->detach();
        
        foreach ($request->sources as $key => $value) {
            $v = Source_data::select('id')->where('source_name', '=', $value)->get();
            $paper->source()->attach($v);
        }
        
        $x = 0;
        $length = count($request->moreFields);
        foreach ($request->moreFields as $key => $value) {
            if ($value['userid'] != null) {
                $d = $input['pos'][$x];
                $paper->teacher()->attach($value, ['author_type' => $d]);
            }
            $x++;
        }
        
        $paper->author()->detach();
        $x = 1;
        if (isset($input['fname'][0]) and (!empty($input['fname'][0]))) {
            foreach ($request->input('fname') as $key => $value) {
                $data['fname'] = $input['fname'][$key];
                $data['lname'] = $input['lname'][$key];
                if (Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first() == null) {
                    $author = new Author;
                    $author->author_fname = $data['fname'];
                    $author->author_lname = $data['lname'];
                    $author->save();
                    $paper->author()->attach($author, ['author_type' => $request->pos2[$key]]);
                } else {
                    $author = Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first();
                    $authorid = $author->id;
                    $paper->author()->attach($authorid, ['author_type' => $request->pos2[$key]]);
                }
                $x++;
            }
        }
        return redirect()->route('papers.index')
            ->with('success', trans('dashboard.papers_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        return Excel::download(new ExportUser, 'papers.xlsx');
    }
}
