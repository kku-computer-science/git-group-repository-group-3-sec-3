@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="justify-content-center">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>{{ trans('dashboard.oops') }}</strong>{{ trans('dashboard.something_went_wrong') }}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ trans('dashboard.validation_required') }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card" style="padding: 16px;">
                <div class="card-body">
                    <h4 class="card-title mb-5">{{ trans('dashboard.add_user') }}</h4>
                    <p class="card-description">{{ trans('dashboard.edit_user_info') }}</p>
                    {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <p><b>{{ trans('dashboard.first_name_th') }}</b></p>
                            {!! Form::text('fname_th', null, array('placeholder' => __('dashboard.first_name_th'),'class' =>
                            'form-control')) !!}
                        </div>
                        <div class="col-sm-6">
                            <p><b>{{ trans('dashboard.last_name_th') }}</b></p>
                            {!! Form::text('lname_th', null, array('placeholder' => __('dashboard.last_name_th'),'class' =>
                            'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <p><b>{{ trans('dashboard.first_name_en') }}</b></p>
                            {!! Form::text('fname_en', null, array('placeholder' => __('dashboard.first_name_en'),'class' =>
                            'form-control')) !!}
                        </div>
                        <div class="col-sm-6">
                            <p><b>{{ trans('dashboard.last_name_en') }}</b></p>
                            {!! Form::text('lname_en', null, array('placeholder' => __('dashboard.last_name_en'),'class' =>
                            'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-sm-8">
                            <p><b>{{ trans('dashboard.email') }}</b></p>
                            {!! Form::text('email', null, array('placeholder' => __('dashboard.email'),'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <p><b>{{ trans('dashboard.password') }}</b></p>
                            {!! Form::password('password', array('placeholder' => __('dashboard.password'),'class' => 'form-control'))!!}
                        </div>
                        <div class="col-sm-6">
                            <p><b>{{ trans('dashboard.confirm_password') }}</p></b>
                            {!! Form::password('password_confirmation', array('placeholder' => __('dashboard.confirm_password'),'class' =>'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                    <p><b>{{ trans('dashboard.Role') }}</b></p>
                    <div class="col-sm-8">
                            @php
                            // สร้างอาเรย์ใหม่สำหรับ roles ที่แปลภาษา
                            $localizedRoles = [];
                            foreach ($roles as $k => $v) {
                            // สมมติ $roles = ['admin' => 'admin', 'headproject' => 'headproject', ...]
                            // $k = 'admin', $v = 'admin'
                            // ให้ key = 'admin' เหมือนเดิม, แต่ value = trans('users.role_admin') (ถ้า $v == 'admin')
                            $localizedRoles[$k] = trans('users.role_'.$v);
                            }
                            @endphp

                            {!! Form::select('roles[]', $localizedRoles, [], [
                            'class' => 'selectpicker',
                            'multiple',
                            'data-none-selected-text' => trans('users.nothing_selected')
                            ]) !!}

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 for="category">{{ trans('dashboard.Department') }} <span class="text-danger">*</span></h6>
                                <select class="form-control" name="cat" id="cat" style="width: 100%;" required>
                                    <option>{{ trans('dashboard.select_category') }}</option>
                                    @foreach ($departments as $cat)
                                    <option value="{{$cat->id}}">{{ $cat->department_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <h6 for="subcat">{{ trans('dashboard.Program') }} <span class="text-danger">*</span></h6>
                                <select class="form-control select2" name="sub_cat" id="subcat" required>
                                    <option value="">{{ trans('dashboard.select_subcategory') }}</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ trans('dashboard.Submit') }}</button>
                    <a class="btn btn-secondary" href="{{ route('users.index') }}">{{ trans('dashboard.cancel') }}</a>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<script>
    $('#cat').on('change', function(e) {
        var cat_id = e.target.value;
        $.get('/ajax-get-subcat?cat_id=' + cat_id, function(data) {
            $('#subcat').empty();
            $.each(data, function(index, areaObj) {
                //console.log(areaObj)
                $('#subcat').append('<option value="' + areaObj.id + '">' + areaObj.degree.title_en +' in '+ areaObj.program_name_en + '</option>');
            });
        });
    });
</script>

@endsection