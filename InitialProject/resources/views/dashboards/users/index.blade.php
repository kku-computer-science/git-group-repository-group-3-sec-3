@extends('dashboards.users.layouts.user-dash-layout')
@section('title', 'Dashboard')

@section('content')
@php
    $locale = app()->getLocale();

    // ถ้าเป็น 'en' หรือ 'cn' ให้ใช้ position_en / fname_en / lname_en
    // ถ้าเป็น 'th' หรือภาษาอื่น ๆ ให้ใช้ position_th / fname_th / lname_th
    if($locale == 'en' || $locale == 'cn'){
        $position = Auth::user()->position_en;
        $fname    = Auth::user()->fname_en;
        $lname    = Auth::user()->lname_en;
    } else {
        $position = Auth::user()->position_th;
        $fname    = Auth::user()->fname_th;
        $lname    = Auth::user()->lname_th;
    }
@endphp

<h3 style="padding-top: 10px;">{{ trans('dashboard.Welcome') }}</h3>
<br>
<h4>{{ trans('dashboard.Hello') }} {{ $position }} {{ $fname }} {{ $lname }}</h4>
@endsection
