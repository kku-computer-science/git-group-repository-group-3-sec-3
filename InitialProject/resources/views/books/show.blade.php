@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card col-md-8" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Book Detail') }}</h4>
            <p class="card-description">{{ trans('dashboard.Book Information') }}</p>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Book Name') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->ac_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Year') }}</b></p>
                <p class="card-text col-sm-9">
                    @if (app()->getLocale() == 'th')
                        {{ \Carbon\Carbon::parse($paper->ac_year)->translatedFormat('Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($paper->ac_year)->year - 543 }}
                    @endif
                </p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Source') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->ac_sourcetitle }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Page') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->ac_page }}</p>
            </div>
            <div class="pull-right mt-5">
                <a class="btn btn-primary btn-sm" href="{{ route('books.index') }}">{{ trans('dashboard.Back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
