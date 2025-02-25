@extends('dashboards.users.layouts.user-dash-layout')

<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">

@section('content')
<div class="container">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Fund') }}</h4>
            <a class="btn btn-primary btn-menu btn-icon-text btn-sm mb-3" href="{{ route('funds.create') }}">
                <i class="mdi mdi-plus btn-icon-prepend"></i> {{ trans('dashboard.Add') }}
            </a>
            <div class="table-responsive">
                <table id="example1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('dashboard.No.') }}</th>
                            <th>{{ trans('dashboard.Fund Name') }}</th>
                            <th>{{ trans('dashboard.Fund type') }}</th>
                            <th>{{ trans('dashboard.Fund level') }}</th>
                            <th>{{ trans('dashboard.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($funds as $i => $fund)
                            @php
                                $locale = app()->getLocale();
                                // เริ่มต้นให้ fund_type และ fund_level จากฐานข้อมูล (ภาษาไทย)
                                $fundType = $fund->fund_type;
                                $fundLevel = $fund->fund_level;

                                // ถ้าเป็นภาษาอังกฤษ
                                if($locale == 'en') {
                                    // แปลง fund_type
                                    if($fund->fund_type == 'ทุนภายใน') {
                                        $fundType = 'Internal Capital';
                                    } elseif($fund->fund_type == 'ทุนภายนอก') {
                                        $fundType = 'External Capital';
                                    }

                                    // แปลง fund_level
                                    if($fund->fund_level == 'สูง') {
                                        $fundLevel = 'High';
                                    } elseif($fund->fund_level == 'กลาง' || $fund->fund_level == 'ปานกลาง') {
                                        $fundLevel = 'Medium';
                                    } elseif($fund->fund_level == 'ล่าง' || $fund->fund_level == 'ต่ำ') {
                                        $fundLevel = 'Low';
                                    } elseif($fund->fund_level == 'ไม่ได้ระบุ' || $fund->fund_level === null) {
                                        $fundLevel = 'Not specified';
                                    }
                                }
                                // ถ้าเป็นภาษาจีน
                                elseif($locale == 'cn') {
                                    // แปลง fund_type
                                    if($fund->fund_type == 'ทุนภายใน') {
                                        $fundType = '内部资金';
                                    } elseif($fund->fund_type == 'ทุนภายนอก') {
                                        $fundType = '外部资金';
                                    }

                                    // แปลง fund_level
                                    if($fund->fund_level == 'สูง') {
                                        $fundLevel = '高';
                                    } elseif($fund->fund_level == 'กลาง' || $fund->fund_level == 'ปานกลาง') {
                                        $fundLevel = '中';
                                    } elseif($fund->fund_level == 'ล่าง' || $fund->fund_level == 'ต่ำ') {
                                        $fundLevel = '低';
                                    } elseif($fund->fund_level == 'ไม่ได้ระบุ' || $fund->fund_level === null) {
                                        $fundLevel = '未指定';
                                    }
                                }
                                // ถ้าเป็นภาษาไทย (th) ให้ใช้ค่าจากฐานข้อมูลเดิม
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ Str::limit($fund->fund_name, 80) }}</td>
                                <td>{{ $fundType }}</td>
                                <td>{{ $fundLevel }}</td>
                                <td>
                                    <form action="{{ route('funds.destroy', $fund->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <li class="list-inline-item">
                                            <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.View') }}" href="{{ route('funds.show', $fund->id) }}">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </li>
                                        @if(Auth::user()->can('update', $fund))
                                            <li class="list-inline-item">
                                                <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.Edit') }}" href="{{ route('funds.edit', Crypt::encrypt($fund->id)) }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if(Auth::user()->can('delete', $fund))
                                            <li class="list-inline-item">
                                                <button class="btn btn-outline-danger btn-sm show_confirm" type="submit" data-toggle="tooltip" title="{{ trans('dashboard.Delete') }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </li>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer></script>

<script>
    $(document).ready(function() {
        // ดึง locale จาก Blade
        let locale = "{{ app()->getLocale() }}";

        // สร้าง object languageSettings สำหรับ DataTables
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
                infoFiltered: "(从 _MAX_ 条记录中过滤)",
                search: "搜索:",
                paginate: {
                    first: "首页",
                    last: "末页",
                    next: "下页",
                    previous: "上页"
                }
            };
        } else {
            // สมมติว่าถ้าเป็น 'th' หรือภาษาอื่น ใช้ภาษาไทย
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

        var table = $('#example1').DataTable({
            fixedHeader: true,
            language: languageSettings
        });
    });
</script>

<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal("Delete Successfully", {
                    icon: "success",
                }).then(function() {
                    location.reload();
                    form.submit();
                });
            }
        });
    });
</script>
@endsection
