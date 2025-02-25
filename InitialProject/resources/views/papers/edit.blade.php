@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
<style type="text/css">
    .dropdown-toggle .filter-option {
        height: 40px;
        width: 400px !important;
        color: #212529;
        background-color: #fff;
        border-width: 0.2;
        border-style: solid;
        border-color: -internal-light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
        border-radius: 5px;
        padding: 4px 10px;
    }
    .my-select {
        background-color: #fff;
        color: #212529;
        border: #000 0.2 solid;
        border-radius: 5px;
        padding: 4px 10px;
        width: 100%;
        font-size: 14px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>{{ trans('dashboard.Whoops!') }}</strong> {{ trans('dashboard.There were some problems with your input.') }}<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">{{ trans('dashboard.Edit Published Work') }}</h4>
                <p class="card-description">{{ trans('dashboard.Fill in Research Details') }}</p>
                <form class="forms-sample" action="{{ route('papers.update', $paper->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <p class="col-sm-3"><b>{{ trans('dashboard.Research Publication Source') }}</b></p>
                        <div class="col-sm-8">
                            {!! Form::select('sources[]', $sources, $paperSource, array('class' => 'selectpicker','multiple data-live-search'=>"true")) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_name" class="col-sm-3 col-form-label">{{ trans('dashboard.Research Title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_name" value="{{ $paper->paper_name }}" class="form-control" placeholder="{{ trans('dashboard.Research Title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputabstract" class="col-sm-3 col-form-label">{{ trans('dashboard.Abstract') }}</label>
                        <div class="col-sm-9">
                            <textarea name="abstract" placeholder="{{ trans('dashboard.Abstract') }}" class="form-control form-control-lg" style="height:150px">{{ $paper->abstract }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputkeyword" class="col-sm-3 col-form-label">{{ trans('dashboard.Keyword') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="keyword" value="{{ $paper->keyword }}" class="form-control" placeholder="{{ trans('dashboard.Keyword') }}">
                            <p class="text-danger">{{ trans('dashboard.Keyword Instruction') }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_sourcetitle" class="col-sm-3 col-form-label">{{ trans('dashboard.Journal Name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_sourcetitle" value="{{ $paper->paper_sourcetitle }}" class="form-control" placeholder="{{ trans('dashboard.Journal Name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_type" class="col-sm-3 col-form-label">{{ trans('dashboard.Document Type') }}</label>
                        <div class="col-sm-9">
                            <select id="paper_type" class="custom-select my-select" style="width: 200px;" name="paper_type">
                                <option value="Journal" {{ "Journal" == $paper->paper_type ? 'selected' : '' }}>{{ trans('dashboard.Journal') }}</option>
                                <option value="Conference Proceeding" {{ "Conference Proceeding" == $paper->paper_type ? 'selected' : '' }}>{{ trans('dashboard.Conference Proceeding') }}</option>
                                <option value="Book Series" {{ "Book Series" == $paper->paper_type ? 'selected' : '' }}>{{ trans('dashboard.Book Series') }}</option>
                                <option value="Book" {{ "Book" == $paper->paper_type ? 'selected' : '' }}>{{ trans('dashboard.Book') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_subtype" class="col-sm-3 col-form-label">{{ trans('dashboard.Document Subtype') }}</label>
                        <div class="col-sm-9">
                            <select id="paper_subtype" class="custom-select my-select" style="width: 200px;" name="paper_subtype">
                                <option value="Article" {{ "Article" == $paper->paper_subtype ? 'selected' : '' }}>{{ trans('dashboard.Article') }}</option>
                                <option value="Conference Paper" {{ "Conference Paper" == $paper->paper_subtype ? 'selected' : '' }}>{{ trans('dashboard.Conference Paper') }}</option>
                                <option value="Editorial" {{ "Editorial" == $paper->paper_subtype ? 'selected' : '' }}>{{ trans('dashboard.Editorial') }}</option>
                                <option value="Book Chapter" {{ "Book Chapter" == $paper->paper_subtype ? 'selected' : '' }}>{{ trans('dashboard.Book Chapter') }}</option>
                                <option value="Erratum" {{ "Erratum" == $paper->paper_subtype ? 'selected' : '' }}>{{ trans('dashboard.Erratum') }}</option>
                                <option value="Review" {{ "Review" == $paper->paper_subtype ? 'selected' : '' }}>{{ trans('dashboard.Review') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpublication" class="col-sm-3 col-form-label">{{ trans('dashboard.Publication') }}</label>
                        <div class="col-sm-9">
                            <select id="publication" class="custom-select my-select" style="width: 200px;" name="publication">
                                <option value="International Journal" {{ "International Journal" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.International Journal') }}</option>
                                <option value="International Book" {{ "International Book" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.International Book') }}</option>
                                <option value="International Conference" {{ "International Conference" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.International Conference') }}</option>
                                <option value="National Conference" {{ "National Conference" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.National Conference') }}</option>
                                <option value="National Journal" {{ "National Journal" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.National Journal') }}</option>
                                <option value="National Book" {{ "National Book" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.National Book') }}</option>
                                <option value="National Magazine" {{ "National Magazine" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.National Magazine') }}</option>
                                <option value="Book Chapter" {{ "Book Chapter" == $paper->publication ? 'selected' : '' }}>{{ trans('dashboard.Book Chapter') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_yearpub" class="col-sm-3 col-form-label">{{ trans('dashboard.Year of Publication') }}</label>
                        <div class="col-sm-9">
                            <input type="number" name="paper_yearpub" value="{{ $paper->paper_yearpub }}" class="form-control" placeholder="{{ trans('dashboard.Year of Publication') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_volume" class="col-sm-3 col-form-label">{{ trans('dashboard.Volume') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_volume" value="{{ $paper->paper_volume }}" class="form-control" placeholder="{{ trans('dashboard.Volume') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_issue" class="col-sm-3 col-form-label">{{ trans('dashboard.Issue Number') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_issue" value="{{ $paper->paper_issue }}" class="form-control" placeholder="{{ trans('dashboard.Issue Number') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_citation" class="col-sm-3 col-form-label">{{ trans('dashboard.Citation') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_citation" value="{{ $paper->paper_citation }}" class="form-control" placeholder="{{ trans('dashboard.Citation') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_page" class="col-sm-3 col-form-label">{{ trans('dashboard.Page') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_page" value="{{ $paper->paper_page }}" class="form-control" placeholder="{{ trans('dashboard.Page') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_doi" class="col-sm-3 col-form-label">{{ trans('dashboard.Doi') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_doi" value="{{ $paper->paper_doi }}" class="form-control" placeholder="{{ trans('dashboard.Doi') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_funder" class="col-sm-3 col-form-label">{{ trans('dashboard.Funding') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_funder" value="{{ $paper->paper_funder }}" class="form-control" placeholder="{{ trans('dashboard.Funding') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_url" class="col-sm-3 col-form-label">{{ trans('dashboard.URL') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_url" value="{{ $paper->paper_url }}" class="form-control" placeholder="{{ trans('dashboard.URL') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">{{ trans('dashboard.Author Name (Internal)') }}</label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table" id="dynamicAddRemove">
                                    <tr>
                                        <td>
                                            <button type="button" name="add" id="add-btn2" class="btn btn-success btn-sm">
                                                <i class="mdi mdi-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">{{ trans('dashboard.Author Name (External)') }}</label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_field">
                                    <tr>
                                        <td>
                                            <button type="button" name="add" id="add" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">{{ trans('dashboard.Submit') }}</button>
                    <a class="btn btn-light" href="{{ route('papers.index') }}">{{ trans('dashboard.Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        $("#head0").select2()
        $("#fund").select2()
        //$("#selUser0").select2()
        var papers = <?php echo $paper['teacher']; ?>;
        var i = 0;
        console.log(papers);
        for (i = 0; i < papers.length; i++) {
            var obj = papers[i];
//console.log(obj.pivot.author_type)

            $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i +
                '][userid]"  style="width: 200px;">@foreach($users as $user)<option value="{{ $user->id }}" >{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><select id="pos' + i + '" class="custom-select my-select" style="width: 200px;" name="pos[]"><option value="1">First Author</option><option value="2" >Co-Author</option><option value="3" >Corresponding Author</option></select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>'
            );
            document.getElementById("selUser" + i).value = obj.id;
            document.getElementById("pos" + i).value = obj.pivot.author_type;
            $("#selUser" + i).select2()


            //document.getElementById("#dynamicAddRemove").value = "10";
        }


        $("#add-btn2").click(function() {

            ++i;
            $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i +
                '][userid]"  style="width: 200px;"><option value="">Select User</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><select id="pos" class="custom-select my-select" style="width: 200px;" name="pos[]"><option value="1">First Author</option><option value="2">Co-Author</option><option value="3">Corresponding Author</option></select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>'
            );
            $("#selUser" + i).select2()
        });


        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

    });
</script>
<script>
    $(document).ready(function() {
        var patent = <?php echo $paper->author; ?>;

        var postURL = "<?php echo url('addmore'); ?>";
        var i = 0;
        //console.log(patent)

        for (i = 0; i < patent.length; i++) {
            //console.log(patent);
            var obj2 = patent[i];
            console.log(obj2.pivot.author_type)
            $("#dynamic_field").append('<tr id="row' + i +
                '" class="dynamic-added"><td><input type="text" name="fname[]" value="' + obj2.author_fname + '" placeholder="Enter your Name" class="form-control name_list" /></td><td><input type="text" name="lname[]" value="' + obj2.author_lname + '" placeholder="Enter your Name" class="form-control name_list" /></td><td><select id="poss' + i + '" class="custom-select my-select" style="width: 200px;" name="pos2[]"><option value="1">First Author</option><option value="2">Co-Author</option><option value="3">Corresponding Author</option></select></td><td><button type="button" name="remove" id="' +
                i + '" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
            //document.getElementById("selUser" + i).value = obj.id;
            //console.log(obj.author_fname)
            // let doc=document.getElementById("row" + i)
            // doc.setAttribute('fname','aaa');
            // doc.setAttribute('lname','bbb');
            //document.getElementById("row" + i).value = obj.author_lname;
            //document.getAttribute("lname").value = obj.author_lname;
            //$("#selUser" + i).select2()
            document.getElementById("poss" + i).value = obj2.pivot.author_type;

            //document.getElementById("#dynamicAddRemove").value = "10";
        }

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i +
                '" class="dynamic-added"><td><input type="text" name="fname[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><input type="text" name="lname[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><select id="poss' + i + '"class="custom-select my-select" style="width: 200px;" name="pos2[]"><option value="1">First Author</option><option value="2">Co-Author</option><option value="3">Corresponding Author</option></select></td><td><button type="button" name="remove" id="' +
                i + '" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>

@endsection
<!-- <form action="{{ route('papers.update',$paper->id) }}" method="POST">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $paper->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Detail:</strong>
                    <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $paper->detail }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form> -->