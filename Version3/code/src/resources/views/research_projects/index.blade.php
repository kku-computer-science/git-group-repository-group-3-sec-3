@extends('dashboards.users.layouts.user-dash-layout')

<!-- Datatables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">

@section('title', trans('dashboard.Research Project'))

@section('content')
<div class="container">
    @if ($message = Session::get('success'))
      <div class="alert alert-success">
         <p>{{ $message }}</p>
      </div>
    @endif
    <div class="card" style="padding: 16px;">
       <div class="card-body">
           <h4 class="card-title">{{ trans('dashboard.Research Project') }}</h4>
           <a class="btn btn-primary btn-menu btn-icon-text btn-sm mb-3" href="{{ route('researchProjects.create') }}">
               <i class="mdi mdi-plus btn-icon-prepend"></i> {{ trans('dashboard.Add') }}
           </a>
           <table id="example1" class="table table-striped">
              <thead>
                  <tr>
                      <th>{{ trans('dashboard.Research Project') }}</th>
                      <th>{{ trans('dashboard.Year') }}</th>
                      <th>{{ trans('dashboard.Project Name') }}</th>
                      <th>{{ trans('dashboard.Head') }}</th>
                      <th>{{ trans('dashboard.Member') }}</th>
                      <th width="auto">{{ trans('dashboard.Action') }}</th>
                  </tr>
              </thead>
              <tbody>
                  @php
                      $locale = app()->getLocale();
                  @endphp
                  @foreach ($researchProjects as $i => $researchProject)
                      <tr>
                          <td>{{ $i + 1 }}</td>
                          <!-- ถ้าเป็นภาษาไทย ให้เพิ่ม 543 เข้าไปในปี (พ.ศ.) -->
                          @php
                              $year = $researchProject->project_year;
                              $yearDisplay = ($locale == 'th') ? $year + 543 : $year;
                          @endphp
                          <td>{{ $yearDisplay }}</td>
                          <td>{{ Str::limit($researchProject->project_name, 70) }}</td>
                          <!-- หัวหน้า (role = 1) แสดงเฉพาะ fname -->
                          <td>
                              @foreach($researchProject->user as $user)
                                  @if ($user->pivot->role == 1)
                                      @if($locale == 'th')
                                          {{ $user->fname_th }}
                                      @else
                                          {{ $user->fname_en }}
                                      @endif
                                  @endif
                              @endforeach
                          </td>
                          <!-- สมาชิก (role = 2) แสดงเฉพาะ fname -->
                          <td>
                              @foreach($researchProject->user as $user)
                                  @if ($user->pivot->role == 2)
                                      @if($locale == 'th')
                                          {{ $user->fname_th }}
                                      @else
                                          {{ $user->fname_en }}
                                      @endif
                                      @if(!$loop->last),@endif
                                  @endif
                              @endforeach
                          </td>
                          <td>
                              <form action="{{ route('researchProjects.destroy', $researchProject->id) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <li class="list-inline-item">
                                      <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.View') }}" href="{{ route('researchProjects.show', $researchProject->id) }}">
                                          <i class="mdi mdi-eye"></i>
                                      </a>
                                  </li>
                                  @if(Auth::user()->can('update', $researchProject))
                                      <li class="list-inline-item">
                                          <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.Edit') }}" href="{{ route('researchProjects.edit', $researchProject->id) }}">
                                              <i class="mdi mdi-pencil"></i>
                                          </a>
                                      </li>
                                  @endif
                                  @if(Auth::user()->can('delete', $researchProject))
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
           <br>
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
@endsection
