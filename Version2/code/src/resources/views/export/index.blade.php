@extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@section('content')

<style>
.table-responsive {
    margin: 30px 0;
}

.table-wrapper {
    min-width: 1000px;
    background: #fff;
    padding: 20px 25px;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
}

.search-box {
    position: relative;
    float: right;
    top:0;
}

.search-box .input-group {
    min-width: 300px;
    position: absolute;
    right: 0;
}

.search-box .input-group-addon,
.search-box input {
    border-color: #ddd;
    border-radius: 0;
}

.search-box input {
    height: 34px;
    padding-right: 35px;
    background: #0e393e;
    color: #ffffff;
    border: none;
    border-radius: 15px !important;
}

.search-box input:focus {
    background: #0e393e;
    color: #ffffff;
}

.search-box input::placeholder {
    font-style: italic;
}

.search-box .input-group-addon {
    min-width: 35px;
    border: none;
    background: transparent;
    position: absolute;
    right: 0;
    z-index: 9;
    padding: 6px 0;
}

.search-box i {
    color: #a0a5b1;
    font-size: 19px;
    position: relative;
    top: 2px;
}
</style>
<script>
$(document).ready(function() {
    // Activate tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Filter table rows based on searched term
    $("#search").on("keyup", function() {
        var term = $(this).val().toLowerCase();
        $("table tbody tr").each(function() {
            $row = $(this);
            var name = $row.find("td:nth-child(2)").text().toLowerCase();
            console.log(name);
            if (name.search(term) < 0) {
                $row.hide();
            } else {
                $row.show();
            }
        });
    });
});
</script>
<div class="container">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ __('dashboard.Users') }}</h4>
            <!-- <p class="card-description">สามารถ Export ข้อมูลของอาจารย์แต่ละท่าน</p> -->
            <!-- <div class="search-box">
                <div class="input-group">
                    <input type="text" id="search" class="form-control" placeholder="Search by Name">
                    <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                </div>
            </div> -->

            <div class="table-responsive mt-5">
                <table id="example1" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard.Teacher Name') }}</th>
                            <th>{{ __('dashboard.Name') }}</th>
                            <th>{{ __('dashboard.email') }}</th>
                            <th>{{ __('dashboard.Roles') }}</th>
                            <th width="280px">{{ __('dashboard.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1 ?>
                        @foreach ($data as $key => $user)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $user->fname_en }} {{ $user->lname_en }} </td>
                            <td>
                            @if(app()->getLocale() == 'th')
                                {{ $user->expertises->expert_name_th ?? $user->expertises->expert_name }}
                            @elseif(app()->getLocale() == 'cn')
                                {{ $user->expertises->expert_name_cn ?? $user->expertises->expert_name }}
                            @else
                                {{ $user->expertises->expert_name }}
                            @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $val)
                                <label class="badge badge-dark">{{ $val }}</label>
                                @endforeach
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-danger btn-sm" type="button" data-toggle="tooltip"
                                    data-placement="top" title="PDF" href="{{ route('pdf', ['id' => $user->id]) }}"><i
                                        class="mdi mdi-file-pdf"></i></a>

                                <a class="btn btn-success btn-sm" type="button" data-toggle="tooltip"
                                    data-placement="top" title="EXCEL" href="{{ route('excel', ['id' => $user->id]) }}"><i
                                        class="mdi mdi-file-excel"></i></a>

                                <a class="btn btn-primary btn-sm" type="button" data-toggle="tooltip"
                                    data-placement="top" title="WORD" href="{{ route('docx', ['id' => $user->id]) }}"><i
                                        class="mdi mdi-file-word"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src = "https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer ></script>
<script src = "https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer ></script>
<script>
    $(document).ready(function() {
        // Get current locale from Laravel
        let locale = "{{ app()->getLocale() }}";
        
        // Language settings object
        let languageSettings = {};
        
        // Set language settings based on locale
        if (locale === 'en') {
            languageSettings = {
                lengthMenu: "Show _MENU_ entries",
                zeroRecords: "No matching records found",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
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
                lengthMenu: "显示 _MENU_ 条记录",
                zeroRecords: "没有找到匹配的记录",
                info: "显示第 _START_ 至 _END_ 条记录，共 _TOTAL_ 条",
                infoEmpty: "显示第 0 至 0 条记录，共 0 条",
                infoFiltered: "(由 _MAX_ 条记录过滤)",
                search: "搜索:",
                paginate: {
                    first: "首页",
                    last: "末页",
                    next: "下页",
                    previous: "上页"
                }
            };
        } else {
            // Default to Thai for 'th' locale
            languageSettings = {
                lengthMenu: "แสดง _MENU_ รายการ",
                zeroRecords: "ไม่พบข้อมูล",
                info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
                infoFiltered: "(กรองข้อมูล _MAX_ ทุกรายการ)",
                search: "ค้นหา:",
                paginate: {
                    first: "หน้าแรก",
                    last: "หน้าสุดท้าย",
                    next: "ถัดไป",
                    previous: "ก่อนหน้า"
                }
            };
        }
        
        // Initialize DataTable with language settings
        var table1 = $('#example1').DataTable({
            responsive: true,
            language: languageSettings
        });
    });
</script>
@stop