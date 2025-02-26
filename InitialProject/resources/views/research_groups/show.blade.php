@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card col-md-10" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Research Group Details') }}</h4>
            <p class="card-description">{{ trans('dashboard.Research Group Information') }}</p>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Name (Thai)') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchGroup->group_name_th }}</p>
            </div>
            <div class="row mt-1">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Name (English)') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchGroup->group_name_en }}</p>
            </div>
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Description (Thai)') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchGroup->group_desc_th }}</p>
            </div>
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Description (English)') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchGroup->group_desc_en }}</p>
            </div>
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Details (Thai)') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchGroup->group_detail_th }}</p>
            </div>
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Details (English)') }}</b></p>
                <p class="card-text col-sm-9">{{ $researchGroup->group_detail_en }}</p>
            </div>
            @php
                $locale = app()->getLocale();
            @endphp
            <div class="row mt-3">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Leader') }}</b></p>
                <p class="card-text col-sm-9">
                    @foreach($researchGroup->user as $user)
                        @if ($user->pivot->role == 1)
                            @if($locale == 'th')
                                {{ $user->position_th }} {{ $user->fname_th }} {{ $user->lname_th }}
                            @else
                                {{ $user->position_en }} {{ $user->fname_en }} {{ $user->lname_en }}
                            @endif
                        @endif
                    @endforeach
                </p>
            </div>
            <div class="row mt-1">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Research Group Members') }}</b></p>
                <p class="card-text col-sm-9">
                    @foreach($researchGroup->user as $user)
                        @if ($user->pivot->role == 2)
                            @if($locale == 'th')
                                {{ $user->position_th }} {{ $user->fname_th }} {{ $user->lname_th }}
                            @else
                                {{ $user->position_en }} {{ $user->fname_en }} {{ $user->lname_en }}
                            @endif
                            @if (!$loop->last), @endif
                        @endif
                    @endforeach
                </p>
            </div>
            <div class="pull-right mt-5">
                <a class="btn btn-primary" href="{{ route('researchGroups.index') }}">{{ trans('dashboard.Back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
$(document).ready(function() {
    /* Your additional JavaScript if needed */
});
</script>
@endsection
