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
            <form action="{{ route('researchGroups.update', $researchGroup->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Research Group Name (Thai) -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Name (Thai)') }}</b></p>
                    <div class="col-sm-8">
                        <input name="group_name_th" value="{{ $researchGroup->group_name_th }}" class="form-control"
                               placeholder="{{ trans('dashboard.Research Group Name (Thai)') }}">
                    </div>
                </div>
                <!-- Research Group Name (English) -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Name (English)') }}</b></p>
                    <div class="col-sm-8">
                        <input name="group_name_en" value="{{ $researchGroup->group_name_en }}" class="form-control"
                               placeholder="{{ trans('dashboard.Research Group Name (English)') }}">
                    </div>
                </div>
                <!-- Research Group Description (Thai) -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Description (Thai)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_desc_th" class="form-control" style="height:90px">{{ $researchGroup->group_desc_th }}</textarea>
                    </div>
                </div>
                <!-- Research Group Description (English) -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Description (English)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_desc_en" class="form-control" style="height:90px">{{ $researchGroup->group_desc_en }}</textarea>
                    </div>
                </div>
                <!-- Research Group Details (Thai) -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Details (Thai)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_detail_th" class="form-control" style="height:90px">{{ $researchGroup->group_detail_th }}</textarea>
                    </div>
                </div>
                <!-- Research Group Details (English) -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Details (English)') }}</b></p>
                    <div class="col-sm-8">
                        <textarea name="group_detail_en" class="form-control" style="height:90px">{{ $researchGroup->group_detail_en }}</textarea>
                    </div>
                </div>
                <!-- Image -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Image') }}</b></p>
                    <div class="col-sm-8">
                        <input type="file" name="group_image" class="form-control">
                    </div>
                </div>
                @php
                    $locale = app()->getLocale();
                @endphp
                <!-- Research Group Leader -->
                <div class="form-group row">
                    <p class="col-sm-3"><b>{{ trans('dashboard.Research Group Leader') }}</b></p>
                    <div class="col-sm-8">
                        <select id="head0" name="head" class="form-control select2">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if($researchGroup->leader_id == $user->id) selected @endif>
                                    {{ $locale == 'th' ? $user->fname_th . ' ' . $user->lname_th : $user->fname_en . ' ' . $user->lname_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Research Group Members -->
                <div class="form-group row">
                    <p class="col-sm-3 pt-4"><b>{{ trans('dashboard.Research Group Members') }}</b></p>
                    <div class="col-sm-8">
                        <table class="table" id="dynamicAddRemove">
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
                <button type="submit" class="btn btn-primary mt-5">{{ trans('dashboard.Submit') }}</button>
                <a class="btn btn-light mt-5" href="{{ route('researchGroups.index') }}">{{ trans('dashboard.Back') }}</a>
            </form>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
$(document).ready(function() {
    $("#head0").select2();
    $("#fund").select2();
    
    var researchGroup = {!! json_encode($researchGroup->user) !!};
    var i = 0;
    // Loop through existing group members (role = 2) and append them dynamically
    for (i = 0; i < researchGroup.length; i++) {
        var obj = researchGroup[i];
        if (obj.pivot.role === 2) {
            $("#dynamicAddRemove").append(
                '<tr>' +
                    '<td><select id="selUser' + i + '" name="moreFields[' + i + '][userid]" style="width: 200px;">' +
                        '@foreach($users as $user)' +
                            '<option value="{{ $user->id }}">' +
                                '{{ app()->getLocale() == "th" ? $user->fname_th . " " . $user->lname_th : $user->fname_en . " " . $user->lname_en }}' +
                            '</option>' +
                        '@endforeach' +
                    '</select></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td>' +
                '</tr>'
            );
            document.getElementById("selUser" + i).value = obj.id;
            $("#selUser" + i).select2();
        }
    }
    
    $("#add-btn2").click(function() {
        ++i;
        $("#dynamicAddRemove").append(
            '<tr>' +
                '<td><select id="selUser' + i + '" name="moreFields[' + i + '][userid]" style="width: 200px;">' +
                    '<option value="">{{ trans('dashboard.Select User') }}</option>' +
                    '@foreach($users as $user)' +
                        '<option value="{{ $user->id }}">' +
                            '{{ app()->getLocale() == "th" ? $user->fname_th . " " . $user->lname_th : $user->fname_en . " " . $user->lname_en }}' +
                        '</option>' +
                    '@endforeach' +
                '</select></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td>' +
            '</tr>'
        );
        $("#selUser" + i).select2();
    });
    
    $(document).on('click', '.remove-tr', function() {
        $(this).closest('tr').remove();
    });
});
</script>
<script>
$(document).ready(function() {
    let locale = "{{ app()->getLocale() }}";
    let languageSettings = {};
    if (locale === 'en') {
        languageSettings = {
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No matching records found",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No records available",
            infoFiltered: "(filtered from _MAX_ total records)",
            search: "Search:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        };
    } else if (locale === 'cn') {
        languageSettings = {
            lengthMenu: "显示 _MENU_ 条目",
            zeroRecords: "未找到匹配的记录",
            info: "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            infoEmpty: "没有可用记录",
            infoFiltered: "(filtered from _MAX_ total records)",
            search: "搜索:",
            paginate: {
                first: "首页",
                last: "末页",
                next: "下页",
                previous: "上页"
            }
        };
    } else {
        languageSettings = {
            lengthMenu: "แสดง _MENU_ รายการ",
            zeroRecords: "ไม่พบข้อมูลที่ตรงกัน",
            info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            infoEmpty: "ไม่มีข้อมูล",
            infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
            search: "ค้นหา:",
            paginate: {
                first: "หน้าแรก",
                last: "หน้าสุดท้าย",
                next: "ถัดไป",
                previous: "ก่อนหน้า"
            }
        };
    }
    $('#example1').DataTable({
        responsive: true,
        language: languageSettings
    });
});
</script>
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        event.preventDefault();
        swal({
            title: "{{ trans('dashboard.Are you sure?') }}",
            text: "{{ trans('dashboard.If you delete this, it will be gone forever.') }}",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal("{{ trans('dashboard.Delete Successfully') }}", {
                    icon: "success",
                }).then(function() {
                    form.submit();
                });
            }
        });
    });
</script>
@stop
