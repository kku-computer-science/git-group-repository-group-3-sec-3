@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<style>
    .my-select {
        background-color: #fff;
        color: #212529;
        border: #000 0.2 solid;
        border-radius: 10px;
        padding: 6px 20px;
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
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">{{ trans('dashboard.Edit Patent Detail') }}</h4>
                <p class="card-description">{{ trans('dashboard.Enter Patent Information') }}</p>
                <form class="forms-sample" action="{{ route('patents.update', $patent->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Title -->
                    <div class="form-group row">
                        <label for="exampleInputac_name" class="col-sm-3 col-form-label">{{ trans('dashboard.Title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_name" value="{{ $patent->ac_name }}" class="form-control" placeholder="{{ trans('dashboard.Title') }}">
                        </div>
                    </div>
                    <!-- Type -->
                    <div class="form-group row">
                        <label for="exampleInputac_type" class="col-sm-3 col-form-label">{{ trans('dashboard.Type') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_type" value="{{ $patent->ac_type }}" class="form-control" placeholder="{{ trans('dashboard.Type') }}">
                        </div>
                    </div>
                    <!-- Registration Date -->
                    <div class="form-group row">
                        <label for="exampleInputac_year" class="col-sm-3 col-form-label">{{ trans('dashboard.Registration Date') }}</label>
                        <div class="col-sm-9">
                            <input type="date" name="ac_year" value="{{ $patent->ac_year }}" class="form-control" placeholder="{{ trans('dashboard.Registration Date') }}">
                        </div>
                    </div>
                    <!-- Registration Number -->
                    <div class="form-group row">
                        <label for="exampleInputac_refnumber" class="col-sm-3 col-form-label">{{ trans('dashboard.Registration Number') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_refnumber" value="{{ $patent->ac_refnumber }}" class="form-control" placeholder="{{ trans('dashboard.Registration Number') }}">
                        </div>
                    </div>
                    <!-- Internal Faculty -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{ trans('dashboard.Internal Faculty') }}</label>
                        <div class="col-sm-9">
                            <table class="table table-bordered" id="dynamicAddRemove">
                                <tr>
                                    <th>
                                        <button type="button" name="add" id="add-btn2" class="btn btn-success btn-sm add">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- External Faculty -->
                    <div class="form-group row">
                        <label for="exampleInput" class="col-sm-3">{{ trans('dashboard.External Faculty') }}</label>
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
                    <button type="submit" class="btn btn-primary me-2 mt-5">{{ trans('dashboard.Submit') }}</button>
                    <a class="btn btn-light mt-5" href="{{ route('patents.index') }}">{{ trans('dashboard.Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Script for Internal Faculty Dynamic Fields -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#selUser0").select2();
        $("#head0").select2();
        var i = 0;
        $("#add-btn2").click(function() {
            ++i;
            $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i + '][userid]" style="width: 200px;"><option value="">{{ trans("dashboard.Select User") }}</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>');
            $("#selUser" + i).select2();
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    });
</script>
<!-- Script for External Faculty Dynamic Fields -->
<script>
    $(document).ready(function() {
        $('#addMore2').on('click', function() {
            var data = $("#tb tr:eq(1)").clone(true).appendTo("#tb");
            data.find("input").val('');
        });
        $(document).on('click', '.remove', function() {
            var trIndex = $(this).closest("tr").index();
            if (trIndex > 1) {
                $(this).closest("tr").remove();
            } else {
                alert("{{ trans('dashboard.Cannot remove first row') }}");
            }
        });
    });
</script>
<!-- Script for Additional External Faculty Fields -->
<script type="text/javascript">
    $(document).ready(function() {
        var patent = <?php echo $patent->author; ?>;
        var postURL = "<?php echo url('addmore'); ?>";
        var i = 0;
        for (i = 0; i < patent.length; i++) {
            var obj = patent[i];
            $("#dynamic_field").append('<tr id="row' + i + '" class="dynamic-added"><td><input type="text" name="fname[]" value="' + obj.author_fname + '" placeholder="{{ trans("dashboard.Enter your First Name") }}" class="form-control name_list" /></td><td><input type="text" name="lname[]" value="' + obj.author_lname + '" placeholder="{{ trans("dashboard.Enter your Last Name") }}" class="form-control name_list" /></td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
        }
        $('#add').click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i + '" class="dynamic-added"><td><input type="text" name="fname[]" placeholder="{{ trans("dashboard.Enter your First Name") }}" class="form-control name_list" /></td><td><input type="text" name="lname[]" placeholder="{{ trans("dashboard.Enter your Last Name") }}" class="form-control name_list" /></td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
        });
        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id).remove();
        });
    });
</script>
@endsection
