@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<style>
    .my-select {
        background-color: #fff;
        color: #212529;
        border: #000 0.2 solid;
        border-radius: 10px;
        padding: 4px 10px;
        width: 100%;
        font-size: 14px;
    }
</style>
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- <a class="btn btn-primary" href="{{ route('funds.index') }}"> Back </a> -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('dashboard.Add Fund') }}</h4>
                <p class="card-description">{{ trans('dashboard.Fill in the research fund details') }}</p>
                <form class="forms-sample" action="{{ route('funds.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="exampleInputfund_type" class="col-sm-2 ">{{ trans('dashboard.Type Funds') }}</label>
                        <div class="col-sm-4">
                            <select name="fund_type" class="custom-select my-select" id="fund_type" onchange='toggleDropdown(this);' required>
                                <option value="" disabled selected >{{ trans('dashboard.Please specify the type of capital') }}</option>
                                <option value="ทุนภายใน">{{ trans('dashboard.Internal Capital') }}</option>
                                <option value="ทุนภายนอก">{{ trans('dashboard.External capital') }}</option>
                            </select>
                        </div>
                    </div>
                    <div id="fund_code">
                        <div class="form-group row">
                            <label for="exampleInputfund_level" class="col-sm-2 ">{{ trans('dashboard.Level Funds') }}</label>
                            <div class="col-sm-4">
                                <select name="fund_level" class="custom-select my-select">
                                <option value="" disabled selected >{{ trans('dashboard.Please specify the level of fund') }}</option>
                                    <option value="">{{ trans('dashboard.Not specified') }}</option>
                                    <option value="สูง">{{ trans('dashboard.Low') }}</option>
                                    <option value="กลาง">{{ trans('dashboard.Medium') }}</option>
                                    <option value="ล่าง">{{ trans('dashboard.High') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputfund_name" class="col-sm-2 ">{{ trans('dashboard.Funds Name') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" class="form-control" placeholder="{{ trans('dashboard.Funds Name') }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="exampleInputsupport_resource" class="col-sm-2 ">{{ trans('dashboard.Supporting Agencies Research Projects') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" class="form-control" placeholder="{{ trans('dashboard.Supporting Agencies Research Projects') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">{{ trans('dashboard.Submit') }}</button>
                    <a class="btn btn-light" href="{{ route('funds.index')}}">{{ trans('dashboard.Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const ac = document.getElementById("fund_code");
    //ac.style.display = "none";

    function toggleDropdown(selObj) {
        ac.style.display = selObj.value === "ทุนภายใน" ? "block" : "none";
    }
</script>
@endsection