@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card col-md-8" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Research Projects Detail') }}</h4>
            <p class="card-description">{{ trans('dashboard.Project Information') }}</p>
            
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project Name') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->project_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project Start Date') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->project_start }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project End Date') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->project_end }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Fund Source') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->fund->fund_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Amount') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->budget }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project Details') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->note }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project Status') }}</b></p>
                @if($researchProject->status == 1)
                    <p class="card-text col-sm-9">{{ trans('dashboard.Pending') }}</p>
                @elseif($researchProject->status == 2)
                    <p class="card-text col-sm-9">{{ trans('dashboard.In Progress') }}</p>
                @else
                    <p class="card-text col-sm-9">{{ trans('dashboard.Closed') }}</p>
                @endif
            </div>
            @php
                $locale = app()->getLocale();
            @endphp
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project Lead') }}</b></p>
                @foreach($researchProject->user as $user)
                    @if ($user->pivot->role == 1)
                        <p class="card-text col-sm-9">
                            @if($locale == 'th')
                                {{ $user->position_th }} {{ $user->fname_th }} {{ $user->lname_th }}
                            @else
                                {{ $user->position_en }} {{ $user->fname_en }} {{ $user->lname_en }}
                            @endif
                        </p>
                    @endif
                @endforeach
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Project Members') }}</b></p>
                @foreach($researchProject->user as $user)
                    @if ($user->pivot->role == 2)
                        <p class="card-text col-sm-9">
                            @if($locale == 'th')
                                {{ $user->position_th }} {{ $user->fname_th }} {{ $user->lname_th }}
                            @else
                                {{ $user->position_en }} {{ $user->fname_en }} {{ $user->lname_en }}
                            @endif
                            @if (!$loop->last),@endif
                        </p>
                    @endif
                @endforeach

                @foreach($researchProject->outsider as $user)
                    @if ($user->pivot->role == 2)
                        <p class="card-text col-sm-9">
                            @if($locale == 'th')
                                {{ $user->title_name }} {{ $user->fname }} {{ $user->lname }}
                            @else
                                {{ $user->title_name }} {{ $user->fname_en }} {{ $user->lname_en }}
                            @endif
                            @if (!$loop->last),@endif
                        </p>
                    @endif
                @endforeach
            </div>
            <div class="pull-right mt-5">
                <a class="btn btn-primary" href="{{ route('researchProjects.index') }}">{{ trans('dashboard.Back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
