@extends('dashboards.users.layouts.user-dash-layout')
<!-- Datatables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">

@section('title','Dashboard')

@section('content')
<div class="container">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>  
    @endif
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">Published research</h4>
            <a class="btn btn-primary btn-menu btn-icon-text btn-sm mb-3" href="{{ route('papers.create') }}">
                <i class="mdi mdi-plus btn-icon-prepend"></i> ADD 
            </a>
            @if(Auth::user()->hasRole('teacher'))
                <!-- ปุ่มเดียวสำหรับเรียก API ทั้ง 4 endpoint -->
                <a id="call-all-btn" class="btn btn-primary btn-icon-text btn-sm mb-3" href="#">
                    <i class="mdi mdi-refresh btn-icon-prepend icon-sm"></i> Call All
                </a>
                <!-- Div สำหรับแสดงสถานะของแต่ละ API -->
                <div id="api-status" style="margin-bottom: 15px;"></div>
            @endif

            <table id="example1" class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ชื่อเรื่อง</th>
                        <th>ประเภท</th>
                        <th>ปีที่ตีพิมพ์</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($papers->sortByDesc('paper_yearpub') as $i => $paper)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ Str::limit($paper->paper_name,50) }}</td>
                        <td>{{ Str::limit($paper->paper_type,50) }}</td>
                        <td>{{ $paper->paper_yearpub }}</td>
                        <td>
                            <form action="{{ route('papers.destroy', $paper->id) }}" method="POST">
                                <li class="list-inline-item">
                                    <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('papers.show', $paper->id) }}">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </li>
                                @if(Auth::user()->can('update', $paper))
                                <li class="list-inline-item">
                                    <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('papers.edit', Crypt::encrypt($paper->id)) }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                </li>
                                @endif
                                <!-- Commented delete button 
                                @csrf
                                @method('DELETE')
                                <li class="list-inline-item">
                                    <button class="btn btn-outline-danger btn-sm show_confirm" type="submit" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </li>
                                -->
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

<!-- jQuery และ Datatables Scripts -->
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

<!-- Script สำหรับเรียก API ทั้ง 4 ตัวแบบเรียงลำดับ พร้อมแสดงสถานะของแต่ละขั้นตอน -->
<script>
    $(document).ready(function() {
        $("#call-all-btn").click(function(e) {
            e.preventDefault();
            // Disable ปุ่มชั่วคราว เพื่อป้องกันการคลิกซ้ำ
            $("#call-all-btn").prop("disabled", true);
            
            // กำหนด URL ของ API ตามลำดับที่ต้องการเรียก
            var routes = [
                "{{ route('calltci', Crypt::encrypt(Auth::user()->id)) }}",
                "{{ route('callscholar', Crypt::encrypt(Auth::user()->id)) }}",
                "{{ route('callscopus', Crypt::encrypt(Auth::user()->id)) }}",
                "{{ route('callwos', Crypt::encrypt(Auth::user()->id)) }}"
            ];
            
            // กำหนดชื่อและสถานะเริ่มต้นสำหรับแต่ละ API
            var steps = [
                { name: "TCI", status: "รอ" },
                { name: "Scopus", status: "รอ" },
                { name: "Scholar", status: "รอ" },
                { name: "WOS", status: "รอ" }
            ];
            
            // ฟังก์ชันอัปเดตสถานะใน div
            function updateStatus() {
                var html = "";
                for(var i = 0; i < steps.length; i++){
                    html += steps[i].name + " : " + steps[i].status + "<br>";
                }
                $("#api-status").html(html);
            }
            
            // เริ่มต้นด้วยการกำหนดให้ขั้นตอนแรกกำลังทำ
            steps[0].status = "กำลังทำ";
            updateStatus();
            
            // ฟังก์ชันสำหรับเรียก API ทีละตัวแบบเรียงลำดับ
            function callNext(index) {
                if (index >= routes.length) {
                    alert("All calls completed.");
                    $("#call-all-btn").prop("disabled", false);
                    return;
                }
                $.ajax({
                    url: routes[index],
                    type: 'GET', // เปลี่ยนเป็น POST หาก API ของคุณรองรับ POST
                    success: function(response) {
                        console.log("Call completed: " + routes[index]);
                        // อัปเดตสถานะของ API ปัจจุบันให้เสร็จสิ้น
                        steps[index].status = "เสร็จสิ้น";
                        // ถ้ามีขั้นตอนต่อไปให้เปลี่ยนสถานะเป็นกำลังทำ
                        if(index + 1 < steps.length) {
                            steps[index + 1].status = "กำลังทำ";
                        }
                        updateStatus();
                        callNext(index + 1);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error calling: " + routes[index] + " - " + error);
                        // อัปเดตสถานะของ API ปัจจุบันเป็น error
                        steps[index].status = "เกิดข้อผิดพลาด";
                        // ถ้ามีขั้นตอนต่อไปให้เปลี่ยนสถานะเป็นกำลังทำ
                        if(index + 1 < steps.length) {
                            steps[index + 1].status = "กำลังทำ";
                        }
                        updateStatus();
                        callNext(index + 1);
                    }
                });
            }
            
            // เริ่มเรียก API ตั้งแต่ตัวแรก
            callNext(0);
        });
    });
</script>
@stop
