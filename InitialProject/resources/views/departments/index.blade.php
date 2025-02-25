<!-- @php
   if(Auth::user()->hasRole('admin')) {
      $layoutDirectory = 'dashboards.admins.layouts.admin-dash-layout';
   } else {
      $layoutDirectory = 'dashboards.users.layouts.user-dash-layout';
   }
@endphp -->

@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
<div class="container">
    <div class="justify-content-center">
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div>
        @endif
        <div class="card">
            <div class="card-header">{{ __('dashboard.Department') }}
                @can('departments-create')

                <a class="btn btn-primary" href="{{ route('departments.create') }}">{{ __('dashboard.New Department') }}</a>

                @endcan
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard.name') }}</th>
                            <th width="280px">{{ __('dashboard.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>{{ $department->department_name_th }}</td>
                            <td>
                                <form action="{{ route('departments.destroy',$department->id) }}" method="POST">
                                    

                                    <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('dashboard.view') }}" href="{{ route('departments.show',$department->id) }}"><i class="mdi mdi-eye"></i></a>

                                    @can('departments-edit')

                                    <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('dashboard.edit') }}" href="{{ route('departments.edit',$department->id) }}"><i class="mdi mdi-pencil"></i></a>

                                    @endcan


                                    @can('departments-delete')
                                    <!-- {!! Form::open(['method' => 'DELETE','route' => ['departments.destroy', $department->id],'style'=>'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit','class' => 'btn btn-danger btn-sm rounded-0','type'=>'button','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title'=>'Delete']) !!}
                                    {!! Form::close() !!} -->
                                    @csrf
                                    @method('DELETE')

                                    <li class="list-inline-item">
                                        <button class="btn btn-outline-danger btn-sm show_confirm" type="submit" data-toggle="tooltip" data-placement="top" title="{{ __('dashboard.delete') }}"><i class="mdi mdi-delete"></i></button>
                                    </li>


                                    @endcan
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->appends($_GET)->links() }}
            </div>
        </div>
    </div>
</div>
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
        var table = $('#example1').DataTable({
            fixedHeader: true,
            language: languageSettings,
            responsive: true
        });
    });
</script>
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
                title: "{{ __('dashboard.are_you_sure') }}",
                text: "{{ __('dashboard.not_recover_file') }}",
                type: "{{ __('dashboard.warning') }}",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("{{ __('dashboard.edit_program') }", {
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