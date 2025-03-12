@extends('layouts.layout')
@section('content')
<div class="container card-2">
    <p class="title">{{ trans('researchers.res') }}</p>
    @foreach($request as $res)
    <span>
        <ion-icon name="caret-forward-outline" size="small"> </ion-icon>
        @php
        $locale = app()->getLocale();
        $program_name = match ($locale) {
        'th' => $res->program_name_th,
        'cn' => $res->program_name_cn ?? $res->program_name_en,
        default => $res->program_name_en,
        };
        @endphp
        {{ $program_name }}
    </span>
    <div class="d-flex">
        <div class="ml-auto">
            <form class="row row-cols-lg-auto g-3" method="GET" action="{{ route('searchresearchers',['id'=>$res->id])}}">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="textsearch" placeholder="{{ trans('researchers.ph') }}">
                    </div>
                </div>
                <!-- <div class="col-12">
                        <label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                        <select class="form-select" id="inlineFormSelectPref">
                            <option selected> Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div> -->
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary">{{ trans('researchers.serach') }}</button>
                </div>
            </form>
        </div>
    </div>


    <div class="row row-cols-1 row-cols-md-2 g-0">
        @foreach($users as $r)
        <a href=" {{ route('detail',Crypt::encrypt($r->id))}}">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-sm-4">
                        <img class="card-image" src="{{ $r->picture}}" alt="">
                    </div>
                    <div class="col-sm-8 overflow-hidden"
                        style="text-overflow: clip; max-height: {{ app()->getLocale() == 'en' ? '220px' : '210px' }};">
                        <div class="card-body">
                            @if (app()->getLocale() == 'th')
                            <h5 class="card-title-2">
                                {{ $r->{'position_'.app()->getLocale()} }} {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}
                            </h5>
                            @else
                            @php
                            $position = str_replace('Dr.', '', $r->position_en);
                            $name = "{$r->fname_en} {$r->lname_en}";
                            $degree = ($r->doctoral_degree == 'Ph.D.') ? ', Ph.D.' : '';
                            @endphp

                            <h5 class="card-title-2">
                                {{ trim("$position $name$degree") }}
                            </h5>
                            @endif












                            <p class="card-text-1">{{ trans('message.expertise') }}</p>
                            <div class="card-expertise">
                                @foreach($r->expertise->sortBy('expert_name') as $exper)
                                @php
                                $locale = app()->getLocale();
                                $expertise_name = match ($locale) {
                                'th' => $exper->expert_name_th,
                                'cn' => $exper->expert_name_cn,
                                default => $exper->expert_name,
                                };
                                @endphp
                                <p class="card-text"> {{ $expertise_name }}</p>
                                @endforeach
                            </div>
                        </div>
                    </diV>
                </div>
            </div>
        </a>
        @endforeach
        @endforeach
    </div>
</div>

@stop