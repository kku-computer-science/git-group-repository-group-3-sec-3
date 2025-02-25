@extends('dashboards.users.layouts.user-dash-layout')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
            <div class="pull-right">
            </div>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- <a class="btn btn-primary" href="{{ route('papers.index') }}"> Back </a> -->

    <div class="col-md-10 grid-margin stretch-card">
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">{{ trans('dashboard.Add Published Work') }}</h4>
                <p class="card-description">{{ trans('dashboard.Fill in Research Details') }}</p>
                <form class="forms-sample" action="{{ route('papers.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="exampleInputpaper_name" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Research Publication Source') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <select class="selectpicker" multiple data-live-search="true" name="cat[]">
                                @foreach( $source as $s)
                                <option value='{{ $s->id }}'>{{ $s->source_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleInputpaper_name" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Research Title') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_name" class="form-control" placeholder="{{ trans('dashboard.Research Title') }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="exampleInputabstract" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Abstract') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <textarea name="abstract" class="form-control form-control-lg" style="height:150px" placeholder="{{ trans('dashboard.Abstract') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleInputkeyword" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Keyword') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="keyword" class="form-control" placeholder="keyword">
                            <p class="text-danger">{{ trans('dashboard.Keyword Instruction') }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_type" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Document Type') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <select id='paper_type' class="custom-select my-select" style='width: 200px;' name="paper_type">
                                <option value="" disabled selected>{{ trans('dashboard.Please Specify Type') }}</option>
                                <option value="Journal">Journal</option>
                                <option value="Conference Proceeding">Conference Proceeding</option>
                                <option value="Book Series">Book Series</option>
                                <option value="Book">Book</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_subtype" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Document Subtype') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <select id='paper_subtype' class="custom-select my-select" style='width: 200px;' name="paper_subtype">
                                <option value="" disabled selected>{{ trans('dashboard.Please Specify Subtype') }}</option>
                                <option value="Article">Article</option>
                                <option value="Conference Paper">Conference Paper</option>
                                <option value="Editorial">Editorial</option>
                                <option value="Book Chapter">Book Chapter</option>
                                <option value="Erratum">Erratum</option>
                                <option value="Review">Review</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpublicatione" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Publication') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <select id='publication' class="custom-select my-select" style='width: 200px;' name="publication">
                                <option value="" disabled selected>{{ trans('dashboard.Please Specify Type') }}</option>
                                <option value="International Journal">International Journal</option>
                                <option value="International Book">International Book</option>
                                <option value="International Conference">International Conference</option>
                                <option value="National Conference">National Conference</option>
                                <option value="National Journal">National Journal</option>
                                <option value="National Book">National Book</option>
                                <option value="National Magazine">National Magazine</option>
                                <option value="Book Chapter">Book Chapter</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_sourcetitle" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Journal Name') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_sourcetitle" class="form-control" placeholder="{{ trans('dashboard.Journal Name') }}">
                        </div>
                    </div>
            
                    <div class="form-group row">
                        <label for="exampleInputpaper_yearpub" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Year of Publication') }}</b>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" name="paper_yearpub" class="form-control" placeholder="{{ trans('dashboard.Year of Publication') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_volume" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Volume') }}</b>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" name="paper_volume" class="form-control" placeholder="Volume">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_issue" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Issue Number') }}</b>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" name="paper_issue" class="form-control" placeholder="Issue">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_citation" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Citation') }}</b>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" name="paper_citation" class="form-control" placeholder="{{ trans('dashboard.Number of Citations') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_page" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Page') }}</b>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" name="paper_page" class="form-control" placeholder="01-99">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_doi" class="col-sm-3 col-form-label">
                            <b>Doi</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_doi" class="form-control" placeholder="doi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_funder" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Funding') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="int" name="paper_funder" class="form-control" placeholder="Funder">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_url" class="col-sm-3 col-form-label">
                            <b>URL</b>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="paper_url" class="form-control" placeholder="url">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_doi" class="col-sm-3 ">
                            <b>{{ trans('dashboard.Author Name (Internal)') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamicAddRemove">
                                    <tr>
                                        <td>
                                            <select id='selUser0' style='width: 200px;' name="moreFields[0][userid]">
                                                <option value=''>{{ trans('dashboard.Select User') }}</option>
                                                @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select id='pos2' class="custom-select my-select" style='width: 200px;' name="pos2[]">
                                                <option value="1">{{ trans('dashboard.First Author') }}</option>
                                                <option value="2">{{ trans('dashboard.Co-Author') }}</option>
                                                <option value="3">{{ trans('dashboard.Corresponding Author') }}</option>
                                            </select>
                                            
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="add-btn2" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputpaper_doi" class="col-sm-3 col-form-label">
                            <b>{{ trans('dashboard.Author Name (External)') }}</b>
                        </label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_field">
                                    <tr>
                                        <td>
                                            <input type="text" name="fname[]" placeholder="First name" class="form-control name_list" />
                                        </td>
                                        <td>
                                            <input type="text" name="lname[]" placeholder="Last name" class="form-control name_list" />
                                        </td>
                                        <td>
                                            <select id='pos2' class="custom-select my-select" style='width: 200px;' name="pos2[]">
                                                <option value="1">{{ trans('dashboard.First Author') }}</option>
                                                <option value="2">{{ trans('dashboard.Co-Author') }}</option>
                                                <option value="3">{{ trans('dashboard.Corresponding Author') }}</option>
                                            </select>
                                            
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="add" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="submit" id="submit" class="btn btn-primary me-2">{{ trans('dashboard.Submit') }}</button>
                    <a class="btn btn-light" href="{{ route('papers.index') }}">{{ trans('dashboard.Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $("#selUser0").select2()
        $("#head0").select2()

        var i = 0;

        $("#add-btn2").click(function() {

            ++i;
            $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i +
                '][userid]"  style="width: 200px;"><option value="">Select User</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><select id="pos" class="custom-select my-select" style="width: 200px;" name="pos[]"><option value="1">First Author</option><option value="2">Co-Author</option><option value="3">Corresponding Author</option></select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr">X</i></button></td></tr>'
            );
            $("#selUser" + i).select2()
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var postURL = "<?php echo url('addmore'); ?>";
        var i = 1;


        $('#add').click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i +
                '" class="dynamic-added"><td><input type="text" name="fname[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><input type="text" name="lname[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><select id="pos2" class="custom-select my-select" style="width: 200px;" name="pos2[]"><option value="1">First Author</option><option value="2">Co-Author</option><option value="3">Corresponding Author</option></select></td><td><button type="button" name="remove" id="' +
                i + '" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
        });


        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#submit').click(function() {
            $.ajax({
                url: postURL,
                method: "POST",
                data: $('#add_name').serialize(),
                type: 'json',
                success: function(data) {
                    if (data.error) {
                        printErrorMsg(data.error);
                    } else {
                        i = 1;
                        $('.dynamic-added').remove();
                        $('#add_name')[0].reset();
                        $(".print-success-msg").find("ul").html('');
                        $(".print-success-msg").css('display', 'block');
                        $(".print-error-msg").css('display', 'none');
                        $(".print-success-msg").find("ul").append(
                            '<li>Record Inserted Successfully.</li>');
                    }
                }
            });
        });


        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $(".print-success-msg").css('display', 'none');
            $.each(msg, function(key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        }
    });
</script>
@endsection
<!-- <form action="{{ route('papers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="paper_name" class="form-control" placeholder="paper_name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Year:</strong>
                    <textarea class="form-control" style="height:150px" name="paper_year" placeholder="paper_year"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>paper_type:</strong>
                    <textarea class="form-control" style="height:150px" name="paper_type" placeholder="paper_type"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>paper_level:</strong>
                    <textarea class="form-control" style="height:150px" name="paper_level" placeholder="paper_level"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>paper_details:</strong>
                    <textarea class="form-control" style="height:150px" name="paper_details" placeholder="paper_details"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
</div> -->