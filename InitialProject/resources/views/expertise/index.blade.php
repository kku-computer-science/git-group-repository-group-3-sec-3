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
            <h4 class="card-title" style="text-align: center;">{{ trans('dashboard.teacher_expertise') }}</h4>
            <table id="example1" class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('dashboard.id') }}</th>
                        @if(Auth::user()->hasRole('admin'))
                            <th>{{ trans('dashboard.teacher_name') }}</th>
                        @endif
                        <th>{{ trans('dashboard.name') }}</th>
                        <th>{{ trans('dashboard.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($experts as $i => $expert)
                    <tr id="expert_id_{{ $expert->id }}">
                        <td>{{ $i+1 }}</td>
                        @if(Auth::user()->hasRole('admin'))
                            <td>
                                @if (app()->getLocale() == 'th')
                                    {{ $expert->user->fname_th }} {{ $expert->user->lname_th }}
                                @elseif (app()->getLocale() == 'cn')
                                    {{ $expert->user->fname_cn }} {{ $expert->user->lname_cn }}
                                @else
                                    {{ $expert->user->fname_en }} {{ $expert->user->lname_en }}
                                @endif
                            </td>
                        @endif
                        <td>
                            @if (app()->getLocale() == 'th')
                                {{ $expert->expert_name_th }}
                            @elseif (app()->getLocale() == 'cn')
                                {{ $expert->expert_name_cn }}
                            @else
                                {{ $expert->expert_name }}
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('experts.destroy', $expert->id) }}" method="POST">
                                <li class="list-inline-item">
                                    <a class="btn btn-outline-success btn-sm"
                                       id="edit-expertise"
                                       type="button"
                                       data-toggle="modal"
                                       data-id="{{ $expert->id }}"
                                       data-placement="top"
                                       title="{{ trans('dashboard.edit') }}"
                                       href="javascript:void(0)">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                </li>
                                @csrf
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <li class="list-inline-item">
                                    <button class="btn btn-outline-danger btn-sm show_confirm"
                                            id="delete-expertise"
                                            type="submit"
                                            data-id="{{ $expert->id }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="{{ trans('dashboard.delete') }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </li>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add and Edit expertise modal -->
<div class="modal fade" id="crud-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="expertiseCrudModal"></h4>
            </div>
            <div class="modal-body">
                <form name="expForm" action="{{ route('experts.store') }}" method="POST">
                    <input type="hidden" name="exp_id" id="exp_id">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>{{ trans('dashboard.name') }}:</strong>
                                <input type="text" name="expert_name" id="expert_name" class="form-control"
                                       placeholder="{{ trans('dashboard.expert_name_placeholder') }}"
                                       onchange="validate()">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" id="btn-save" name="btnsave" class="btn btn-primary" disabled>
                                {{ trans('dashboard.submit') }}
                            </button>
                            <a href="{{ route('experts.index') }}" class="btn btn-danger">
                                {{ trans('dashboard.cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JS Scripts --}}
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer></script>
<script src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js" defer></script>
{{-- sweetalert --}}
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    //--- SINGLE DataTable Initialization ---
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
                infoFiltered: "(从 _MAX_ 条记录中筛选)",
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

        // Initialize DataTable just once, merging language + rowGroup + order
        $('#example1').DataTable({
            fixedHeader: true,
            language: languageSettings,
            order: [
                [1, 'asc']
            ],
            rowGroup: {
                dataSrc: 1
            }
        });
    });
</script>

<script>
    //--- AJAX + Modal + Delete Script ---
    $(document).ready(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // When click New expertise button
        $('#new-expertise').click(function() {
            $('#btn-save').val("create-expertise");
            $('#expertise').trigger("reset");
            $('#expertiseCrudModal').html("{{ trans('dashboard.add_new_expertise') }}");
            $('#crud-modal').modal('show');
        });

        // Edit expertise
        $('body').on('click', '#edit-expertise', function() {
            var expert_id = $(this).data('id');
            $.get('experts/' + expert_id + '/edit', function(data) {
                $('#expertiseCrudModal').html("{{ trans('dashboard.edit_expertise') }}");
                $('#btn-update').val("Update");
                $('#btn-save').prop('disabled', false);
                $('#crud-modal').modal('show');
                $('#exp_id').val(data.id);

                // Detect current locale and set the expert_name accordingly
                if ( '{{ app()->getLocale() }}' == 'th' ) {
                    $('#expert_name').val(data.expert_name_th); // Set expert_name_th for Thai
                } else if ( '{{ app()->getLocale() }}' == 'cn' ) {
                    $('#expert_name').val(data.expert_name_cn); // Set expert_name_cn for Chinese
                } else {
                    $('#expert_name').val(data.expert_name); // Default to English
                }
            });
        });

        // Delete expertise
        $('body').on('click', '#delete-expertise', function(e) {
            var expert_id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            e.preventDefault();
            swal({
                title: "{{ trans('dashboard.are_you_sure') }}",
                text: "{{ trans('dashboard.not_recover_file') }}",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("{{ trans('dashboard.delete_successfully') }}", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                        $.ajax({
                            type: "DELETE",
                            url: "experts/" + expert_id,
                            data: { id: expert_id, _token: token },
                            success: function(data) {
                                $('#msg').html("{{ trans('dashboard.entry_deleted_successfully') }}");
                                $("#expert_id_" + expert_id).remove();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    });
                }
            });
        });
    });
</script>

<script>
    //--- Enable/Disable submit button when typing expert_name ---
    function validate() {
        if (document.expForm.expert_name.value !== '')
            document.expForm.btnsave.disabled = false;
        else
            document.expForm.btnsave.disabled = true;
    }
</script>


<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
                title: "{{ trans('dashboard.title') }}",
                text: "{{ trans('dashboard.text') }}",
                icon: "warning",
                buttons: {
                    cancel: "{{ trans('dashboard.cancel') }}",
                    confirm: "{{ trans('dashboard.ok') }}"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("{{ trans('dashboard.DeleteSuccessfully') }}", {
                        icon: "success",
                        buttons: {
                            confirm: "{{ trans('dashboard.ok') }}"
                        },
                    }).then(function() {
                        location.reload();
                        form.submit();
                    });
                }
            });
    });
</script>
@stop
