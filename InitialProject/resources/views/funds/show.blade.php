@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card col-md-8" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Fund Detail') }}</h4>
            <p class="card-description">{{ trans('dashboard.Fund Description') }}</p>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Funds Name') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Year') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_year }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Description') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_details }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Fund type') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_type }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Level Funds') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_level }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Agency') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Add details by') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->user->fname_th }} {{ $fund->user->lname_th}}</p>
            </div>
            <div class="pull-right mt-5">
                <a class="btn btn-primary btn-sm" href="{{ route('funds.index') }}"> Back</a>
            </div>
        </div>

    </div>


</div>
@endsection