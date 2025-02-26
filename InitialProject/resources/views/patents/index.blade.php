@extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
@section('title', trans('dashboard.Patents & Copyrights'))

@section('content')
<div class="container">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Patents & Copyrights') }}</h4>
            <a class="btn btn-primary btn-menu btn-icon-text btn-sm mb-3" href="{{ route('patents.create') }}">
                <i class="mdi mdi-plus btn-icon-prepend"></i> {{ trans('dashboard.ADD') }}
            </a>

            @php
                // locale ปัจจุบัน
                $locale = app()->getLocale();

                // Mapping ภาษาอังกฤษกลาง -> ภาษาไทย/จีน/อังกฤษ
                $patentTypeMap = [
                    'th' => [
                        'Patent' => 'สิทธิบัตร',
                        'Patent (Invention)' => 'สิทธิบัตร (การประดิษฐ์)',
                        'Patent (Product Design)' => 'สิทธิบัตร (การออกแบบผลิตภัณฑ์)',
                        'Utility Model' => 'อนุสิทธิบัตร',
                        'Copyright' => 'ลิขสิทธิ์',
                        'Copyright (Literature)' => 'ลิขสิทธิ์ (วรรณกรรม)',
                        'Copyright (Musical)' => 'ลิขสิทธิ์ (ดนตรี)',
                        'Copyright (Film)' => 'ลิขสิทธิ์ (ภาพยนตร์)',
                        'Copyright (Fine Arts)' => 'ลิขสิทธิ์ (ศิลปะ)',
                        'Copyright (Broadcasting)' => 'ลิขสิทธิ์ (งานแพร่ภาพ)',
                        'Copyright (Audiovisual)' => 'ลิขสิทธิ์ (สื่อโสตทัศนวัสดุ)',
                        'Copyright (Other in Literature/Science/Art)' => 'ลิขสิทธิ์ (งานอื่นในด้านวรรณคดี/วิทยาศาสตร์/ศิลปะ)',
                        'Copyright (Sound Recording)' => 'ลิขสิทธิ์ (บันทึกเสียง)',
                        'Others' => 'อื่น ๆ',
                        'Trade Secret' => 'ความลับทางการค้า',
                        'Trademark' => 'เครื่องหมายการค้า',
                    ],
                    'en' => [
                        'Patent' => 'Patent',
                        'Patent (Invention)' => 'Patent (Invention)',
                        'Patent (Product Design)' => 'Patent (Product Design)',
                        'Utility Model' => 'Utility Model',
                        'Copyright' => 'Copyright',
                        'Copyright (Literature)' => 'Copyright (Literature)',
                        'Copyright (Musical)' => 'Copyright (Musical)',
                        'Copyright (Film)' => 'Copyright (Film)',
                        'Copyright (Fine Arts)' => 'Copyright (Fine Arts)',
                        'Copyright (Broadcasting)' => 'Copyright (Broadcasting)',
                        'Copyright (Audiovisual)' => 'Copyright (Audiovisual)',
                        'Copyright (Other in Literature/Science/Art)' => 'Copyright (Other in Literature/Science/Art)',
                        'Copyright (Sound Recording)' => 'Copyright (Sound Recording)',
                        'Others' => 'Others',
                        'Trade Secret' => 'Trade Secret',
                        'Trademark' => 'Trademark',
                    ],
                    'cn' => [
                        'Patent' => '专利',
                        'Patent (Invention)' => '专利（发明）',
                        'Patent (Product Design)' => '专利（产品设计）',
                        'Utility Model' => '实用新型',
                        'Copyright' => '版权',
                        'Copyright (Literature)' => '版权（文学）',
                        'Copyright (Musical)' => '版权（音乐）',
                        'Copyright (Film)' => '版权（电影）',
                        'Copyright (Fine Arts)' => '版权（美术）',
                        'Copyright (Broadcasting)' => '版权（广播）',
                        'Copyright (Audiovisual)' => '版权（视听）',
                        'Copyright (Other in Literature/Science/Art)' => '版权（其他文学/科学/艺术）',
                        'Copyright (Sound Recording)' => '版权（录音）',
                        'Others' => '其他',
                        'Trade Secret' => '商业机密',
                        'Trademark' => '商标',
                    ],
                ];

                // ฟังก์ชัน normalize เพื่อแปลงจากค่าไทย -> อังกฤษกลาง
                function normalizePatentType($value) {
                    // กรณีฐานข้อมูลเก็บเป็นภาษาไทย เช่น "สิทธิบัตร", "ลิขสิทธิ์"
                    // ให้คืนเป็นอังกฤษกลาง เช่น "Patent", "Copyright"
                    // คุณต้องใส่เงื่อนไขให้ครบตามที่มีใน DB
                    switch($value) {
                        case 'สิทธิบัตร':
                            return 'Patent';
                        case 'สิทธิบัตร (การประดิษฐ์)':
                            return 'Patent (Invention)';
                        case 'สิทธิบัตร (การออกแบบผลิตภัณฑ์)':
                            return 'Patent (Product Design)';
                        case 'อนุสิทธิบัตร':
                            return 'Utility Model';
                        case 'ลิขสิทธิ์':
                            return 'Copyright';
                        case 'ลิขสิทธิ์ (วรรณกรรม)':
                            return 'Copyright (Literature)';
                        case 'ลิขสิทธิ์ (ดนตรี)':
                            return 'Copyright (Musical)';
                        case 'ลิขสิทธิ์ (ภาพยนตร์)':
                            return 'Copyright (Film)';
                        case 'ลิขสิทธิ์ (ศิลปะ)':
                            return 'Copyright (Fine Arts)';
                        case 'ลิขสิทธิ์ (งานแพร่ภาพ)':
                            return 'Copyright (Broadcasting)';
                        case 'ลิขสิทธิ์ (สื่อโสตทัศนวัสดุ)':
                            return 'Copyright (Audiovisual)';
                        case 'ลิขสิทธิ์ (งานอื่นในด้านวรรณคดี/วิทยาศาสตร์/ศิลปะ)':
                            return 'Copyright (Other in Literature/Science/Art)';
                        case 'ลิขสิทธิ์ (บันทึกเสียง)':
                            return 'Copyright (Sound Recording)';
                        case 'อื่น ๆ':
                            return 'Others';
                        case 'ความลับทางการค้า':
                            return 'Trade Secret';
                        case 'เครื่องหมายการค้า':
                            return 'Trademark';
                        default:
                            // หากไม่เจอใน switch ถือว่า DB อาจเก็บเป็นอังกฤษอยู่แล้ว
                            return $value;
                    }
                }
            @endphp

            <table id="example1" class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('dashboard.No.') }}</th>
                        <th>{{ trans('dashboard.Title') }}</th>
                        <th>{{ trans('dashboard.Type') }}</th>
                        <th>{{ trans('dashboard.Registration Date') }}</th>
                        <th>{{ trans('dashboard.Registration Number') }}</th>
                        <th>{{ trans('dashboard.Author') }}</th>
                        <th width="280px">{{ trans('dashboard.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patents as $i => $paper)
                        @php
                            // 1) Normalize ac_type -> อังกฤษกลาง
                            $normalizedType = normalizePatentType($paper->ac_type);

                            // 2) map ไปยังภาษาที่เลือก
                            $paperType = $normalizedType;
                            if(isset($patentTypeMap[$locale][$normalizedType])) {
                                $paperType = $patentTypeMap[$locale][$normalizedType];
                            }

                            // 3) แปลงวันที่จดทะเบียน
                            // สมมติว่าใน DB เก็บเป็น พ.ศ. => date('Y', ...) ได้ 2562
                            // ถ้า en/cn => ลบ 543
                            // ถ้า th => แสดงตามเดิม
                            $yearData = date('Y', strtotime($paper->ac_year));
                            if($locale == 'en' || $locale == 'cn'){
                                $yearDisplay = $yearData - 543;
                            } else {
                                $yearDisplay = $yearData; // ภาษาไทย แสดงตาม DB
                            }
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ Str::limit($paper->ac_name,50) }}</td>
                            <td>{{ $paperType }}</td>
                            <td>{{ $yearDisplay }}</td>
                            <td>{{ Str::limit($paper->ac_refnumber, 50) }}</td>
                            <td>
                                @foreach($paper->user as $a)
                                    @if($locale == 'th')
                                        {{ $a->fname_th }} {{ $a->lname_th }}
                                    @else
                                        {{ $a->fname_en }} {{ $a->lname_en }}
                                    @endif
                                    @if(!$loop->last),@endif
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('patents.destroy',$paper->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <!-- View -->
                                    <li class="list-inline-item">
                                        <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip"
                                           data-placement="top" title="{{ trans('dashboard.View') }}"
                                           href="{{ route('patents.show',$paper->id) }}">
                                           <i class="mdi mdi-eye"></i>
                                        </a>
                                    </li>
                                    <!-- Edit -->
                                    @if(Auth::user()->can('update',$paper))
                                        <li class="list-inline-item">
                                            <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip"
                                               data-placement="top" title="{{ trans('dashboard.Edit') }}"
                                               href="{{ route('patents.edit',$paper->id) }}">
                                               <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </li>
                                    @endif
                                    <!-- Delete -->
                                    @if(Auth::user()->can('delete',$paper))
                                        <li class="list-inline-item">
                                            <button class="btn btn-outline-danger btn-sm show_confirm" type="submit" data-toggle="tooltip"
                                                    data-placement="top" title="{{ trans('dashboard.Delete') }}">
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

<!-- Scripts -->
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
                title: `{{ trans('dashboard.Are you sure?') }}`,
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
                        location.reload();
                        form.submit();
                    });
                }
            });
    });
</script>
@stop
