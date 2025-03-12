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
                            <th>{{ __('dashboard.Name') }}</th>
                            <th width="280px">{{ __('dashboard.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>
                                @if(app()->getLocale() == 'th')
                                    {{ $department->department_name_th ?? $department->department_name_en }}
                                @elseif(app()->getLocale() == 'cn')
                                    {{ $department->department_name_cn ?? $department->department_name_en }}
                                @else
                                    {{ $department->department_name_en }}
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('departments.destroy',$department->id) }}" method="POST">
                                    

                                    <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('dashboard. view') }}" href="{{ route('departments.show',$department->id) }}"><i class="mdi mdi-eye"></i></a>

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
                                        <button class="btn btn-outline-danger btn-sm show_confirm" type="submit" data-toggle="tooltip" data-placement="top" title="__('dashboard.delete')"><i class="mdi mdi-delete"></i></button>
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
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
                title: `Are you sure?`,
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