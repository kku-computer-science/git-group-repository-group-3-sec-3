@extends('dashboards.users.layouts.user-dash-layout')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@section('content')
<style type="text/css">
    .dropdown-toggle {
        height: 40px;
        width: 400px !important;
    }
    body label:not(.input-group-text) {
        margin-top: 10px;
    }
    body .my-select {
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
                <h4 class="card-title">{{ trans('dashboard.Add Other Academic Works') }}</h4>
                <p class="card-description">
                    {{ trans('dashboard.Enter details for other academic works (Patents, Utility Models, Copyright)') }}
                </p>
                <form class="forms-sample" action="{{ route('patents.store') }}" method="POST">
                    @csrf
                    <!-- Title Field -->
                    <div class="form-group row">
                        <label for="exampleInputac_name" class="col-sm-3">
                            {{ trans('dashboard.Title (Patent, Utility Model, Copyright)') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_name" class="form-control" placeholder="{{ trans('dashboard.Title (Patent, Utility Model, Copyright)') }}">
                        </div>
                    </div>
                    <!-- Type Field -->
                    <div class="form-group row">
                        <label for="exampleInputac_type" class="col-sm-3">
                            {{ trans('dashboard.Type') }}
                        </label>
                        <div class="col-sm-4">
                            <select id="category" class="custom-select my-select" name="ac_type">
                                <option value="" disabled selected>{{ trans('dashboard.Please specify type') }}</option>
                                <optgroup label="{{ trans('dashboard.Patent') }}">
                                    <option value="สิทธิบัตร">{{ trans('dashboard.Patent') }}</option>
                                    <option value="สิทธิบัตร (การประดิษฐ์)">{{ trans('dashboard.Patent (Invention)') }}</option>
                                    <option value="สิทธิบัตร (การออกแบบผลิตภัณฑ์)">{{ trans('dashboard.Patent (Product Design)') }}</option>
                                </optgroup>
                                <optgroup label="{{ trans('dashboard.Utility Model') }}">
                                    <option value="อนุสิทธิบัตร">{{ trans('dashboard.Utility Model') }}</option>
                                </optgroup>
                                <optgroup label="{{ trans('dashboard.Copyright') }}">
                                    <option value="ลิขสิทธิ์">{{ trans('dashboard.Copyright') }}</option>
                                    <option value="ลิขสิทธิ์ (วรรณกรรม)">{{ trans('dashboard.Copyright (Literature)') }}</option>
                                    <option value="ลิขสิทธิ์ (ตนตรีกรรม)">{{ trans('dashboard.Copyright (Musical)') }}</option>
                                    <option value="ลิขสิทธิ์ (ภาพยนตร์)">{{ trans('dashboard.Copyright (Film)') }}</option>
                                    <option value="ลิขสิทธิ์ (ศิลปกรรม)">{{ trans('dashboard.Copyright (Fine Arts)') }}</option>
                                    <option value="ลิขสิทธิ์ (งานแพร่เสี่ยงแพร่ภาพ)">{{ trans('dashboard.Copyright (Broadcasting)') }}</option>
                                    <option value="ลิขสิทธิ์ (โสตทัศนวัสดุ)">{{ trans('dashboard.Copyright (Audiovisual)') }}</option>
                                    <option value="ลิขสิทธิ์ (งานอื่นใดในแผนกวรรณคดี/วิทยาศาสตร์/ศิลปะ)">{{ trans('dashboard.Copyright (Other in Literature/Science/Art)') }}</option>
                                    <option value="ลิขสิทธิ์ (สิ่งบันทึกเสียง)">{{ trans('dashboard.Copyright (Sound Recording)') }}</option>
                                </optgroup>
                                <optgroup label="{{ trans('dashboard.Others') }}">
                                    <option value="ความลับทางการค้า">{{ trans('dashboard.Trade Secret') }}</option>
                                    <option value="เครื่องหมายการค้า">{{ trans('dashboard.Trademark') }}</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <!-- Date of Registration Field -->
                    <div class="form-group row">
                        <label for="exampleInputac_year" class="col-sm-3">
                            {{ trans('dashboard.Date of Registration') }}
                        </label>
                        <div class="col-sm-4">
                            <input type="date" name="ac_year" class="form-control" placeholder="{{ trans('dashboard.Date of Registration') }}">
                        </div>
                    </div>
                    <!-- Registration Number Field -->
                    <div class="form-group row">
                        <label for="exampleInputac_refnumber" class="col-sm-3">
                            {{ trans('dashboard.Registration Number') }}
                        </label>
                        <div class="col-sm-4">
                            <input type="text" name="ac_refnumber" class="form-control" placeholder="{{ trans('dashboard.Registration Number') }}">
                        </div>
                    </div>
                    <!-- Internal Faculty Field -->
                    <div class="form-group row">
                        <label for="exampleInputac_doi" class="col-sm-3">
                            {{ trans('dashboard.Internal Faculty') }}
                        </label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-hover small-text" id="dynamicAddRemove">
                                    <tr>
                                        <td>
                                            <select id="selUser0" style="width: 200px;" name="moreFields[0][userid]">
                                                <option value="">{{ trans('dashboard.Select User') }}</option>
                                                @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="add-btn2" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- External Faculty Field -->
                    <div class="form-group row">
                        <label for="exampleInputpaper_doi" class="col-sm-3">
                            {{ trans('dashboard.External Faculty') }}
                        </label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-hover small-text" id="tb">
                                    <tr class="tr-header">
                                        <th>{{ trans('dashboard.First Name') }}</th>
                                        <th>{{ trans('dashboard.Last Name') }}</th>
                                        <th>
                                            <a href="javascript:void(0);" style="font-size:18px;" id="addMore2" title="{{ trans('dashboard.Add More Person') }}">
                                                <i class="mdi mdi-plus"></i>
                                            </a>
                                        </th>
                                    <tr>
                                        <td>
                                            <input type="text" name="fname[]" class="form-control" placeholder="{{ trans('dashboard.First Name') }}">
                                        </td>
                                        <td>
                                            <input type="text" name="lname[]" class="form-control" placeholder="{{ trans('dashboard.Last Name') }}">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="remove">
                                                <span><i class="mdi mdi-minus"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="submit" id="submit" class="btn btn-primary me-2">
                        {{ trans('dashboard.Submit') }}
                    </button>
                    <a class="btn btn-light" href="{{ route('patents.index') }}">
                        {{ trans('dashboard.Cancel') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#selUser0").select2();
        $("#head0").select2();
        var i = 0;
        $("#add-btn2").click(function() {
            ++i;
            $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i + '][userid]" style="width: 200px;"><option value="">{{ trans("dashboard.Select User") }}</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr">X</button></td></tr>');
            $("#selUser" + i).select2();
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    });
</script>
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
<script type="text/javascript">
    $(document).ready(function() {
        var postURL = "<?php echo url('addmore'); ?>";
        var i = 1;
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
