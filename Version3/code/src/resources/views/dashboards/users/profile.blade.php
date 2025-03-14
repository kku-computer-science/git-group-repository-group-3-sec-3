@extends('dashboards.users.layouts.user-dash-layout')
<style>
    body label:not(.input-group-text) {
        margin-top: 10px;
    }

    body .my-select {
        background-color: #fff;
        color: #212529;
        border: #000 0.2 solid;
        border-radius: 10px;
        padding: 6px 20px;
        width: 100%;
    }
</style>
@section('title','Profile')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@section('content')
<div class="container profile">
    <div class="bg-white shadow rounded-lg d-block d-sm-flex">
        <div class="profile-tab-nav border-right">
            <div class="p-4">
                <div class="img-circle text-center mb-3">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle admin_picture" src="{{ Auth::user()->picture }}" alt="User profile picture">
                    </div>
                    <h4 class="text-center p-2">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h4>
                    <input type="file" name="admin_image" id="admin_image" style="opacity: 0;height:1px;display:none">
                    <a href="javascript:void(0)" class="btn btn-primary btn-block btn-sm" id="change_picture_btn"><b>{{ trans('dashboard.Change Picture') }}</b></a>
                </div>

            </div>
            <div class="nav flex-column nav-pills-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link " id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
                    <i class="mdi mdi-account-card-details"></i>
                    <span class="menu-title"> {{ trans('dashboard.Account') }} </span>
                </a>
                <a class="nav-link " id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
                    <i class="mdi mdi-key-variant"></i>
                    <span class="menu-title"> {{ trans('dashboard.Password') }} </span>
                </a>
                @if(Auth::user()->hasRole('teacher'))
                <a class="nav-link {{old('tab') == 'expertise' ? ' active' : null}}" id="expertise-tab" data-toggle="pill" href="#expertise" role="tab" aria-controls="expertise" aria-selected="false">
                    <i class="mdi mdi-account-star"></i>
                    <span class="menu-title"> {{ trans('dashboard.Expertise') }} </span>
                </a>
                <a class="nav-link" id="education-tab" data-toggle="pill" href="#education" role="tab" aria-controls="education" aria-selected="false">
                    <i class="mdi mdi-school"></i>
                    <span class="menu-title"> {{ trans('dashboard.Education') }} </span>
                </a>
                @endif
            </div>
        </div>
        <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
            <!-- <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab"> -->
            <div class="tab-pane " id="account" role="tabpanel" aria-labelledby="account-tab">
                <h3 class="mb-4">Profile Settings</h3>
                <form class="form-horizontal" method="POST" action="{{ route('adminUpdateInfo') }}" id="AdminInfoForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-sm-4">
                                <label>{{trans('dashboard.Name title')}}</label>
                                <select class="custom-select my-select " name="title_name_en">
                                    <option value="Mr." {{ Auth::user()->title_name_en == 'Mr.' ? 'selected' : '' }}>{{ trans('dashboard.Mr.') }}</option>
                                    <option value="Miss" {{ Auth::user()->title_name_en == 'Miss' ? 'selected' : '' }}>{{ trans('dashboard.Mrs.') }}</option>
                                    <option value="Mrs." {{ Auth::user()->title_name_en == 'Mrs.' ? 'selected' : '' }}>{{ trans('dashboard.Ms.') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.First name (English)') }}</label>
                                <input type="text" class="form-control" id="inputfNameEN" placeholder="Name" value="{{ Auth::user()->fname_en }}" name="fname_en">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Last name (English)') }}</label>
                                <input type="text" class="form-control" id="inputlNameEN" placeholder="Name" value="{{ Auth::user()->lname_en }}" name="lname_en">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.First name (Thai)') }}</label>
                                <input type="text" class="form-control" id="inputfNameTH" placeholder="Name" value="{{ Auth::user()->fname_th }}" name="fname_th">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Last name (Thai)') }}</label>
                                <input type="text" class="form-control" id="inputlNameTH" placeholder="Name" value="{{ Auth::user()->lname_th }}" name="lname_th">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Email') }}</label>
                                <input type="text" class="form-control" id="inputEmail" placeholder="Email" value="{{ Auth::user()->email }}" name="email">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                        @if(Auth::user()->hasRole('teacher'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Academic Ranks (English)') }}</label>
                                <select id="category" class="custom-select my-select" name="academic_ranks_en">
                                    <option value="Professor" {{ Auth::user()->academic_ranks_en == 'Professor' ? 'selected' : '' }}>Professor</option>
                                    <option value="Associate Professor" {{ Auth::user()->academic_ranks_en == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                    <option value="Assistant Professor" {{ Auth::user()->academic_ranks_en == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                                    <option value="Lecturer" {{ Auth::user()->academic_ranks_en == 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Academic Ranks (Thai)') }}</label>
                                <select name="academic_ranks_th" id="subcategory" class="custom-select my-select">
                                   
                                        <option value="ศาสตราจารย์" {{ Auth::user()->academic_ranks_th == 'ศาสตราจารย์' ? 'selected' : '' }}>ศาสตราจารย์</option>
                                   
                                   
                                        <option value="รองศาสตราจารย์" {{ Auth::user()->academic_ranks_th == 'รองศาสตราจารย์' ? 'selected' : '' }}>รองศาสตราจารย์</option>
                                   
                                    
                                        <option value="ผู้ช่วยศาสตราจารย์" {{ Auth::user()->academic_ranks_th == 'ผู้ช่วยศาสตราจารย์' ? 'selected' : '' }}>ผู้ช่วยศาสตราจารย์</option>
                                    
                                   
                                        <option value="อาจารย์" {{ Auth::user()->academic_ranks_th == 'อาจารย์' ? 'selected' : '' }}>อาจารย์</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input name="pos" type="checkbox" value="check2" />{{ trans('dashboard.For those without a doctorate, please specify.') }}</label>
                                </div>

                            </div>
                        </div>
                        @endif
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>


            <div class="tab-pane fade " id="password" role="tabpanel" aria-labelledby="password-tab">
                <form class="form-horizontal" action="{{ route('adminChangePassword') }}" method="POST" id="changePasswordAdminForm">
                    <h3 class="mb-4">{{ trans('dashboard.Password Settings') }}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Old Password') }}</label>
                                <input type="password" class="form-control" id="inputpassword" placeholder="{{ trans('dashboard.Old Password') }}" name="oldpassword">
                                <span class="text-danger error-text oldpassword_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.New Password') }}</label>
                                <input type="password" class="form-control" id="newpassword" placeholder="{{ trans('dashboard.New Password') }}" name="newpassword">
                                <span class="text-danger error-text newpassword_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Confirm Password') }}</label>
                                <input type="password" class="form-control" id="cnewpassword" placeholder="{{ trans('dashboard.Confirm Password') }}" name="cnewpassword">
                                <span class="text-danger error-text cnewpassword_error"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary">{{ trans('dashboard.Update') }}</button>
                        <!-- <button class="btn btn-light">Cancel</button> -->
                    </div>

                </form>
            </div>
            <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                <form class="form-horizontal" method="POST" action="{{ route('updateEdInfo') }}" id="EdInfoForm">
                    <h3 class="mb-4">{{ trans('dashboard.Education History') }}</h3>
                    <div class="row">
                        <label>{{ trans('dashboard.Bachelor degree') }}</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Name of university') }}</label>
                                @if (empty(Auth::user()->education[0]->uname))
                                <input type="text" class="form-control" id="inputlBUName" placeholder="{{ trans('dashboard.Name of university') }}" value="" name="b_uname">
                                @else
                                <input type="text" class="form-control" id="inputlBUName" placeholder="{{ trans('dashboard.Name of university') }}" value="{{Auth::user()->education[0]->uname }}" name="b_uname">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Degree Name') }}</label>
                                @if (empty(Auth::user()->education[0]->qua_name))
                                <input type="text" class="form-control" id="inputlBQuName" placeholder="{{ trans('dashboard.Degree Name') }}" value="" name="b_qua_name">
                                @else
                                <input type="text" class="form-control" id="inputlBQuName" placeholder="{{ trans('dashboard.Degree Name') }}" value="{{Auth::user()->education[0]->qua_name }}" name="b_qua_name">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Year of graduation') }}</label>
                                @if (empty(Auth::user()->education[0]->year))
                                <input type="text" class="form-control" id="inputlYear" placeholder="{{ trans('dashboard.Year of graduation') }}" value="" name="b_year">
                                @else
                                <input type="text" class="form-control" id="inputlYear" placeholder="{{ trans('dashboard.Year of graduation') }}" value="{{Auth::user()->education[0]->year }}" name="b_year">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label>{{ trans('dashboard.Master degree') }}</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Name of university') }}</label>
                                @if (empty(Auth::user()->education[1]->uname))
                                <input type="text" class="form-control" id="inputlMUName" placeholder="{{ trans('dashboard.Name of university') }}" value="" name="m_uname">
                                @else
                                <input type="text" class="form-control" id="inputlMUName" placeholder="{{ trans('dashboard.Name of university') }}" value="{{Auth::user()->education[1]->uname }}" name="m_uname">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Degree Name') }}</label>
                                @if (empty(Auth::user()->education[1]->qua_name))
                                <input type="text" class="form-control" id="inputlMQuName" placeholder="{{ trans('dashboard.Degree Name') }}" value="" name="m_qua_name">
                                @else
                                <input type="text" class="form-control" id="inputlMQuName" placeholder="{{ trans('dashboard.Degree Name') }}" value="{{Auth::user()->education[1]->qua_name }}" name="m_qua_name">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Year of graduation') }}</label>
                                @if (empty(Auth::user()->education[1]->year))
                                <input type="text" class="form-control" id="inputlYear" placeholder="{{ trans('dashboard.Year of graduation') }}" value="" name="m_year">
                                @else
                                <input type="text" class="form-control" id="inputlYear" placeholder="{{ trans('dashboard.Year of graduation') }}" value="{{Auth::user()->education[1]->year }}" name="m_year">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label>{{ trans('dashboard.PhD') }}</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Name of university') }}</label>
                                @if (empty(Auth::user()->education[2]->uname))
                                <input type="text" class="form-control" id="inputlDUName" placeholder="{{ trans('dashboard.Name of university') }}" value="" name="d_uname">
                                @else
                                <input type="text" class="form-control" id="inputlDUName" placeholder="{{ trans('dashboard.Name of university') }}" value="{{Auth::user()->education[2]->uname}}" name="d_uname">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Degree Name') }}</label>
                                @if (empty(Auth::user()->education[2]->qua_name))
                                <input type="text" class="form-control" id="inputlDQuName" placeholder="{{ trans('dashboard.Degree Name') }}" value="" name="d_qua_name">
                                @else
                                <input type="text" class="form-control" id="inputlDQuName" placeholder="{{ trans('dashboard.Degree Name') }}" value="{{Auth::user()->education[2]->qua_name }}" name="d_qua_name">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('dashboard.Year of graduation') }}</label>
                                @if (empty(Auth::user()->education[2]->year))
                                <input type="text" class="form-control" id="inputlYear" placeholder="{{ trans('dashboard.Year of graduation') }}" value="" name="d_year">
                                @else
                                <input type="text" class="form-control" id="inputlYear" placeholder="{{ trans('dashboard.Year of graduation') }}" value="{{Auth::user()->education[2]->year }}" name="d_year">
                                @endif
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary">{{ trans('dashboard.Update') }}</button>
                        <!-- <button class="btn btn-light">Cancel</button> -->
                    </div>

                </form>

            </div>
            <div class="tab-pane fade show{{old('tab') == 'expertise' ? ' active' : null}}" id="expertise" role="tabpanel" aria-labelledby="expertise-tab">
                <h3 class="mb-4">{{ trans('dashboard.Expertise') }}</h3>
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <!-- <a href="javascript:void(0)" class="btn btn-success mb-2" id="new-expertise" data-toggle="modal">Add Expertise</a> -->
                            <button type="button" class="btn btn-primary btn-menu1 btn-icon-text btn-sm mb-3" data-toggle="modal" data-target="#crud-modal">
                                <i class="mdi mdi-plus btn-icon-prepend"></i>{{ trans('dashboard.Add Expertise') }}
                            </button>
                        </div>
                    </div>
                </div>
                <br />
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p id="msg">{{ $message }}</p>
                </div>
                @endif


                <table class="table table-striped table-hover">
                    <tr>
                        <th colspan="2">{{ trans('dashboard.Expertise') }}</th>

                    </tr>
                    @foreach (Auth::user()->expertise as $expert)
                    <tr id="expert_id_{{ $expert->id }}">
                         <td>
                            @if (app()->getLocale() == 'th')
                                {{ $expert->expert_name_th }}
                            @elseif (app()->getLocale() == 'cn')
                                {{ $expert->expert_name_cn }}
                            @else
                                {{ $expert->expert_name }}
                            @endif
                        </td>
                        <td width="180px">
                            <form action="{{ route('experts.destroy',$expert->id) }}" method="POST">
                                <!-- <a class="btn btn-info" id="show-expertise" data-toggle="modal" data-id="{{ $expert->id }}">Show</a> -->
                                <li class="list-inline-item">
                                    <button class="btn btn-outline-success btn-sm" href="javascript:void(0)" id="edit-expertise" type="button" data-toggle="modal" data-placement="top" data-id="{{ $expert->id }}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                </li>
                                <!-- <a href="javascript:void(0)" class="btn btn-success" id="edit-expertise" data-toggle="modal" data-id="{{ $expert->id }}">Edit </a> -->
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <li class="list-inline-item">
                                    <button id="delete-expertise" data-id="{{ $expert->id }}" class="btn btn-outline-danger btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete"></i></button>
                                </li>
                                <!-- <a id="delete-expertise" data-id="{{ $expert->id }}" class="btn btn-danger delete-user">Delete</a> -->
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </table>
            </div>

            </form>
        </div>
    </div>
</div>
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
                                <strong>Name:</strong>
                                <input type="text" name="expert_name" id="expert_name" class="form-control" placeholder="Expert_name" onchange="validate()">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" id="btn-save" name="btnsave" class="btn btn-primary" disabled>Submit</button>
                            <!-- <a  href="{{ URL::previous() }}"class="btn btn-danger">Cancel</a>-->
                            <button class="btn btn-danger" id="btnCancel" data-dismiss="modal">Cancel</button>
                            <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> -->
<!-- <script src="alert/dist/sweetalert-dev.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->


<script>
    $(document).ready(function() {
        var $optgroups = $('#subcategory > optgroup');

        $("#category").on("change", function() {
            var selectedVal = this.value;

            $('#subcategory').html($optgroups.filter('[id="' + selectedVal + '"]'));
        });
    });
</script>

<script>
    $(function() {
        /* UPDATE ADMIN PERSONAL INFO */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showSwal(type) {
            swal({
                title: "{{ trans('dashboard.update_info_confirmation') }}",
                text: "{{ trans('dashboard.update_info_confirmation') }}",
                icon: "warning",
                buttons: {
                    cancel: "{{ trans('dashboard.cancel_update') }}",
                    confirm: "{{ trans('dashboard.Update Info') }}"
                },
                dangerMode: true,
            }).then((isConfirm) => {
                if (isConfirm) {
                    swal("{{ trans('dashboard.update_success') }}", {
                        icon: "success",
                        buttons: {
                            confirm: "{{ trans('dashboard.ok') }}"
                        },
                    });
                } else {
                    swal("{{ trans('dashboard.update_error') }}", {
                        icon: "error",
                        buttons: {
                            confirm: "{{ trans('dashboard.ok') }}"
                        },
                    });
                }
            });
        }

        $('#AdminInfoForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(document).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $('.admin_name').each(function() {
                            $(this).html($('#AdminInfoForm').find($('input[name="name"]')).val());
                        });
                        swal("{{ trans('dashboard.Update Info') }}", {
                            icon: "success",
                            buttons: {
                                confirm: "{{ trans('dashboard.ok') }}"
                            },
                        });
                    }
                }
            });
        });

        $('#EdInfoForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(document).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $('.admin_name').each(function() {
                            $(this).html($('#EdInfoForm').find($('input[name="name"]')).val());
                        });
                        swal("{{ trans('dashboard.Update Info') }}", {
                            icon: "success",
                            buttons: {
                                confirm: "{{ trans('dashboard.ok') }}"
                            },
                        });
                    }
                }
            });
        });

        $(document).on('click', '#change_picture_btn', function() {
            $('#admin_image').click();
        });

        $('#admin_image').ijaboCropTool({
            preview: '.admin_picture',
            setRatio: 2 / 3,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['CROP', 'QUIT'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: '{{ route("adminPictureUpdate") }}',
            withCSRF: ['_token', '{{ csrf_token() }}'],
            onSuccess: function(message, element, status) {
                swal("{{ trans('dashboard.update_profile_picture') }}", {
                    icon: "success",
                    buttons: {
                        confirm: "{{ trans('dashboard.ok') }}"
                    },
                });
            },
            onError: function(message, element, status) {
                alert(message);
            }
        });

        $('#changePasswordAdminForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(document).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $('#changePasswordAdminForm')[0].reset();
                        swal("{{ trans('dashboard.update_password') }}", {
                            icon: "success",
                            buttons: {
                                confirm: "{{ trans('dashboard.ok') }}"
                            },
                        });
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {

        /* When click New expertise button */
        $('#new-expertise').click(function() {
            $('#btn-save').val("create-expertise");
            $('#expertise').trigger("reset");
            $('#expertiseCrudModal').html("Add New Expertise");
            $('#crud-modal').modal('show');

        });

        /* Edit expertise */
        $('body').on('click', '#edit-expertise', function() {
            var expert_id = $(this).data('id');
            $.get('experts/' + expert_id + '/edit', function(data) {
                $('#expertiseCrudModal').html("Edit Expertise");

                $('#btn-update').val("Update");
                $('#btn-save').prop('disabled', false);
                $('#crud-modal').modal('show');
                $('#exp_id').val(data.id);
                $('#expert_name').val(data.expert_name);

                //$('#v-pills-tabContent.a.active').removeClass("active");

                //$('li.list-group-item.active').removeClass("active");
                //$(this).addClass("active");

                //swal("Update Profile Picture", "Your account is updated!", "success");
            })

        });


        /* Delete expertise */
        $('body').on('click', '#delete-expertise', function() {
            var expert_id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");


            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    swal("Delete Successfully", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                        $.ajax({
                            type: "DELETE",
                            url: "experts/" + expert_id,
                            data: {
                                "id": expert_id,
                                "_token": token,
                            },

                            success: function() {
                                $("#expert_id_" + expert_id).remove();
                                //swal("Done!", "It was succesfully deleted!", "success");

                                // $('#v-pills-tab.a.active').removeClass("active");
                                // $(this).addClass("active");
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error deleting!", "Please try again", "error");
                            }
                        });
                        
                    });

                }
            });
        });
    });
</script>


<script>
    error = false

    function validate() {
        if (document.expForm.expert_name.value != '')
            document.expForm.btnsave.disabled = false
        else
            document.expForm.btnsave.disabled = true
    }
</script>
@endsection