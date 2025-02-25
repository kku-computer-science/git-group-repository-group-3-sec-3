@extends('dashboards.users.layouts.user-dash-layout')
@section('title', 'Dashboard')

@section('content')
@php
    // ตรวจสอบ locale ปัจจุบันของระบบ
    $locale = app()->getLocale();
    // เลือกคอลัมน์ที่เหมาะสมสำหรับตำแหน่งและชื่อ
    $position = $locale == 'en' ? Auth::user()->position_en : Auth::user()->position_th;
    $fname    = $locale == 'en' ? Auth::user()->fname_en : Auth::user()->fname_th;
    $lname    = $locale == 'en' ? Auth::user()->lname_en : Auth::user()->lname_th;
@endphp

<h3 style="padding-top: 10px;">{{ trans('dashboard.Welcome') }}</h3>
<br>
<h4>{{ trans('dashboard.Hello') }} {{ $position }} {{ $fname }} {{ $lname }}</h4>
@endsection
    