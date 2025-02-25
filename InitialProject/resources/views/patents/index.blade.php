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
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ Str::limit($paper->ac_name,50) }}</td>
                        <td>{{ $paper->ac_type }}</td>
                        <td>{{ $paper->ac_year }}</td>
                        <td>{{ Str::limit($paper->ac_refnumber, 50) }}</td>
                        <td>
                            @foreach($paper->user as $a)
                                {{ $a->fname_th }} {{ $a->lname_th }}@if(!$loop->last),@endif
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ route('patents.destroy',$paper->id) }}" method="POST">
                                <!-- View -->
                                <li class="list-inline-item">
                                    <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.View') }}" href="{{ route('patents.show',$paper->id) }}">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </li>
                                <!-- Edit -->
                                @if(Auth::user()->can('update',$paper))
                                <li class="list-inline-item">
                                    <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.Edit') }}" href="{{ route('patents.edit',$paper->id) }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                </li>
                                @endif
                                <!-- Delete -->
                                @if(Auth::user()->can('delete',$paper))
                                @csrf
                                @method('DELETE')
                                <li class="list-inline-item">
                                    <button class="btn btn-outline-danger btn-sm show_confirm" type="submit" data-toggle="tooltip" data-placement="top" title="{{ trans('dashboard.Delete') }}">
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
        var table1 = $('#example1').DataTable({
            responsive: true,
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
