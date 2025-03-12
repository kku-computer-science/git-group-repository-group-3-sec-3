<?php

namespace App\Http\Controllers;

use App\Models\Academicwork;
use App\Models\Author;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;

class PatentController extends Controller
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
            $patents = Academicwork::where('ac_type', '!=', 'book')->get();
        } else {
            $patents = Academicwork::with('user')
                ->where('ac_type', '!=', 'book')
                ->whereHas('user', function ($query) use ($id) {
                    $query->where('users.id', '=', $id);
                })->paginate(10);
        }
        return view('patents.index', compact('patents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['teacher', 'student'])->get();
        return view('patents.create', compact('users'));
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
            'ac_name'       => 'required',
            'ac_type'       => 'required',
            'ac_year'       => 'required',
            'ac_refnumber'  => 'required',
        ]);

        $input = $request->except(['_token']);
        $acw = Academicwork::create($input);

        $id = auth()->user()->id;
        $user = User::find($id);

        $x = 1;
        $length = count($request->moreFields);
        foreach ($request->moreFields as $key => $value) {
            if ($value['userid'] != null) {
                if ($x === 1) {
                    $acw->user()->attach($value, ['author_type' => 1]);
                } else if ($x === $length) {
                    $acw->user()->attach($value, ['author_type' => 3]);
                } else {
                    $acw->user()->attach($value, ['author_type' => 2]);
                }
            }
            $x++;
        }

        $x = 1;
        if (isset($input['fname'][0]) and (!empty($input['fname'][0]))) {
            $length = count($request->input('fname'));
            foreach ($request->input('fname') as $key => $value) {
                $data['fname'] = $input['fname'][$key];
                $data['lname'] = $input['lname'][$key];
                if (Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first() == null) {
                    $author = new Author;
                    $author->author_fname = $data['fname'];
                    $author->author_lname = $data['lname'];
                    $author->save();
                    if ($x === 1) {
                        $acw->author()->attach($author, ['author_type' => 1]);
                    } else if ($x === $length) {
                        $acw->author()->attach($author, ['author_type' => 3]);
                    } else {
                        $acw->author()->attach($author, ['author_type' => 2]);
                    }
                } else {
                    $author = Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first();
                    $authorid = $author->id;
                    if ($x === 1) {
                        $acw->author()->attach($authorid, ['author_type' => 1]);
                    } else if ($x === $length) {
                        $acw->author()->attach($authorid, ['author_type' => 3]);
                    } else {
                        $acw->author()->attach($authorid, ['author_type' => 2]);
                    }
                }
                $x++;
            }
        }

        return redirect()->route('patents.index')
            ->with('success', trans('dashboard.patent_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patent = Academicwork::find($id);
        return view('patents.show', compact('patent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patent = Academicwork::find($id);
        $this->authorize('update', $patent);
        $users = User::role(['teacher', 'student'])->get();
        return view('patents.edit', compact('patent', 'users'));
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
        $patent = Academicwork::find($id);
        $input = $request->except(['_token']);
        $patent->update([
            'ac_name'      => $request->ac_name,
            'ac_type'      => $request->ac_type,
            'ac_year'      => $request->ac_year,
            'ac_refnumber' => $request->ac_refnumber,
        ]);

        $acw = $patent;
        $patent->user()->detach();
        $x = 1;
        $length = count($request->moreFields);
        foreach ($request->moreFields as $key => $value) {
            if ($value['userid'] != null) {
                if ($x === 1) {
                    $acw->user()->attach($value, ['author_type' => 1]);
                } else if ($x === $length) {
                    $acw->user()->attach($value, ['author_type' => 3]);
                } else {
                    $acw->user()->attach($value, ['author_type' => 2]);
                }
            }
            $x++;
        }

        $patent->author()->detach();
        $x = 1;
        if (isset($input['fname'][0]) and (!empty($input['fname'][0]))) {
            $length = count($request->input('fname'));
            foreach ($request->input('fname') as $key => $value) {
                $data['fname'] = $input['fname'][$key];
                $data['lname'] = $input['lname'][$key];
                if (Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first() == null) {
                    $author = new Author;
                    $author->author_fname = $data['fname'];
                    $author->author_lname = $data['lname'];
                    $author->save();
                    if ($x === 1) {
                        $acw->author()->attach($author, ['author_type' => 1]);
                    } else if ($x === $length) {
                        $acw->author()->attach($author, ['author_type' => 3]);
                    } else {
                        $acw->author()->attach($author, ['author_type' => 2]);
                    }
                } else {
                    $author = Author::where([['author_fname', '=', $data['fname']], ['author_lname', '=', $data['lname']]])->first();
                    $authorid = $author->id;
                    if ($x === 1) {
                        $acw->author()->attach($authorid, ['author_type' => 1]);
                    } else if ($x === $length) {
                        $acw->author()->attach($authorid, ['author_type' => 3]);
                    } else {
                        $acw->author()->attach($authorid, ['author_type' => 2]);
                    }
                }
                $x++;
            }
        }

        return redirect()->route('patents.index')
            ->with('success', trans('dashboard.patent_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patent = Academicwork::find($id);
        $this->authorize('delete', $patent);
        $patent->delete();

        return redirect()->route('patents.index')
            ->with('success', trans('dashboard.patent_deleted_successfully'));
    }
}
