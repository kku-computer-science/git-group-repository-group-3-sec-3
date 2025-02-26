@extends('dashboards.users.layouts.user-dash-layout')

<!-- Datatables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">

@section('title', trans('dashboard.Dashboard'))

@section('content')
<div class="container">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>  
    @endif
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Published research') }}</h4>
            <a class="btn btn-primary btn-menu btn-icon-text btn-sm mb-3" href="{{ route('papers.create') }}">
                <i class="mdi mdi-plus btn-icon-prepend"></i> {{ trans('dashboard.Add') }}
            </a>
            @if(Auth::user()->hasRole('teacher'))
                <!-- ปุ่มสำหรับเรียก API -->
                <a id="call-all-btn" class="btn btn-primary btn-icon-text btn-sm mb-3" href="#">
                    <i class="mdi mdi-refresh btn-icon-prepend icon-sm"></i> {{ trans('dashboard.Call All') }}
                </a>
                <div id="api-status" style="margin-bottom: 15px;"></div>
            @endif

            <table id="example1" class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('dashboard.No.') }}</th>
                        <th>{{ trans('dashboard.Title') }}</th>
                        <th>{{ trans('dashboard.Type') }}</th>
                        <th>{{ trans('dashboard.Year') }}</th>
                        <th width="280px">{{ trans('dashboard.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $locale = app()->getLocale();
                        // Define mappings for Document Type
                        $typeMap = [
                            'th' => [
                                'Journal' => 'วารสาร',
                                'Conference Proceeding' => 'บทความจากการประชุม',
                                'Book Series' => 'ชุดหนังสือ',
                                'Book' => 'หนังสือ',
                                'Article' => 'บทความ',
                            ],
                            'en' => [
                                'Journal' => 'Journal',
                                'Conference Proceeding' => 'Conference Proceeding',
                                'Book Series' => 'Book Series',
                                'Book' => 'Book',
                                'Article' => 'Article',
                                
                            ],
                            'cn' => [
                                'Journal' => '期刊',
                                'Conference Proceeding' => '会议论文集',
                                'Book Series' => '丛书',
                                'Book' => '书籍',
                                'Article' => '文章',
                            ],
                        ];
                    @endphp
                    @foreach ($papers->sortByDesc('paper_yearpub') as $i => $paper)
                        @php
                            // แปลงค่า Type ตาม mapping หากมีใน mapping มิฉะนั้นใช้ค่าจากฐานข้อมูล
                            $paperType = $paper->paper_type;
                            if(isset($typeMap[$locale]) && isset($typeMap[$locale][$paper->paper_type])) {
                                $paperType = $typeMap[$locale][$paper->paper_type];
                            }
                            
                            // สำหรับ Year: ถ้าเป็นภาษาไทย ให้เพิ่ม 543
                            $paperYear = $paper->paper_yearpub;
                            if($locale == 'th') {
                                $paperYear = $paper->paper_yearpub + 543;
                            }
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ Str::limit($paper->paper_name,50) }}</td>
                            <td>{{ Str::limit($paperType,50) }}</td>
                            <td>{{ $paperYear }}</td>
                            <td>
                                <form action="{{ route('papers.destroy', $paper->id) }}" method="POST">
                                    <li class="list-inline-item">
                                        <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.View') }}" href="{{ route('papers.show', $paper->id) }}">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    </li>
                                    @if(Auth::user()->can('update', $paper))
                                        <li class="list-inline-item">
                                            <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.Edit') }}" href="{{ route('papers.edit', Crypt::encrypt($paper->id)) }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </li>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        </div>
    </div>
</div>

<!-- jQuery และ DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer></script>

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
            title: "{{ __('dashboard.Are you sure?') }}",
            text: "{{ __('dashboard.If you delete this, it will be gone forever.') }}",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal("{{ __('dashboard.Delete Successfully') }}", {
                    icon: "success",
                }).then(function() {
                    form.submit();
                });
            }
        });
    });
</script>
@stop