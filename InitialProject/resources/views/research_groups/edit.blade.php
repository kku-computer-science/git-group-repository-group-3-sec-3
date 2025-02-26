@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
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
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Edit Research Group') }}</h4>
            <p class="card-description">{{ trans('dashboard.Fill in the edited research group details') }}</p>
            <form action="{{ route('researchGroups.update',$researchGroup->id) }}" method="POST" enctype="multipart/form-data" >
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <p class="col-sm-3 "><b>{{ trans('dashboard.Research Group Name (Thai)') }}</b></p>
                    <div class="col-sm-8">
                        <input name="group_name_th" value="{{ $researchGroup->group_name_th }}" class="form-control"
                            placeholder="{{ trans('dashboard.Research Group Name (Thai)') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3 "><b>{{ trans('dashboard.Research Group Name (English)') }}</b></p>
                    <div class="col-sm-8">
                        <input name="group_name_en" value="{{ $researchGroup->group_name_en }}" class="form-control"
                            placeholder="{{ trans('dashboard.Research Group Name (English)') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Description (Thai)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_desc_th" class="form-control" style="height:90px">{{ $researchGroup->group_desc_th }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Description (English)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_desc_en" class="form-control" style="height:90px">{{ $researchGroup->group_desc_en }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Details (Thai)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_detail_th" class="form-control" style="height:90px">{{ $researchGroup->group_detail_th }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Details (English)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_detail_en" class="form-control" style="height:90px">{{ $researchGroup->group_detail_en }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Image') }}</b></p>
                    <div class="col-sm-8">
                        <input type="file" name="group_image" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Leader') }}</b></p>
                    <div class="col-sm-8">
                        <select id='head0' name="head" class="form-control select2">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($researchGroup->leader_id == $user->id) selected @endif>
                                {{ $user->fname_th }} {{ $user->lname_th }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <p class="col-sm-3 pt-4"><b>{{ trans('dashboard.Research Group Members') }}</b></p>
                    <div class="col-sm-8">
                        <table class="table" id="dynamicAddRemove">
                            <tr>
                                <th><button type="button" name="add" id="add-btn2" class="btn btn-success btn-sm add"><i
                                            class="mdi mdi-plus"></i></button></th>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-5">{{ trans('dashboard.Submit') }}</button>
                <a class="btn btn-light mt-5" href="{{ route('researchGroups.index') }}"> {{ trans('dashboard.Back') }}</a>
            </form>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script>
$(document).ready(function() {
    $("#head0").select2()
    $("#fund").select2()


    var researchGroup = <?php echo $researchGroup['user']; ?>;
    var i = 0;

    for (i = 0; i < researchGroup.length; i++) {
        var obj = researchGroup[i];

        if (obj.pivot.role === 2) {
            $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i +
                '][userid]"  style="width: 200px;">@foreach($users as $user)<option value="{{ $user->id }}" >{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>'
            );
            document.getElementById("selUser" + i).value = obj.id;
            $("#selUser" + i).select2()

        }
        //document.getElementById("#dynamicAddRemove").value = "10";
    }
    $("#add-btn2").click(function() {
        ++i;
        $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' + i +
            '][userid]"  style="width: 200px;"><option value="">Select User</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>'
        );
        $("#selUser" + i).select2()

    });
    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });

});
</script>
@stop