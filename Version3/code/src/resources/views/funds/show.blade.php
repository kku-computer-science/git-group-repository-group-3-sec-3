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

            @php
                $locale = app()->getLocale();

                // แปลงประเภทกองทุน (fund_type) ตามภาษาที่เลือก
                $fundType = $fund->fund_type; 
                if($locale == 'en') {
                    if($fund->fund_type == 'ทุนภายใน') {
                        $fundType = 'Internal Capital';
                    } elseif($fund->fund_type == 'ทุนภายนอก') {
                        $fundType = 'External Capital';
                    }
                } elseif($locale == 'cn') {
                    if($fund->fund_type == 'ทุนภายใน') {
                        $fundType = '内部资金';
                    } elseif($fund->fund_type == 'ทุนภายนอก') {
                        $fundType = '外部资金';
                    }
                }

                // แปลงระดับกองทุน (fund_level) ตามภาษาที่เลือก
                $fundLevel = $fund->fund_level;
                if($locale == 'en') {
                    if($fund->fund_level == 'สูง') {
                        $fundLevel = 'High';
                    } elseif($fund->fund_level == 'ปานกลาง' || $fund->fund_level == 'กลาง') {
                        $fundLevel = 'Medium';
                    } elseif($fund->fund_level == 'ต่ำ' || $fund->fund_level == 'ล่าง') {
                        $fundLevel = 'Low';
                    } elseif($fund->fund_level == 'ไม่ได้ระบุ' || $fund->fund_level === null) {
                        $fundLevel = 'Not specified';
                    }
                } elseif($locale == 'cn') {
                    if($fund->fund_level == 'สูง') {
                        $fundLevel = '高';
                    } elseif($fund->fund_level == 'ปานกลาง' || $fund->fund_level == 'กลาง') {
                        $fundLevel = '中';
                    } elseif($fund->fund_level == 'ต่ำ' || $fund->fund_level == 'ล่าง') {
                        $fundLevel = '低';
                    } elseif($fund->fund_level == 'ไม่ได้ระบุ' || $fund->fund_level === null) {
                        $fundLevel = '未指定';
                    }
                }

                // "เพิ่มรายละเอียดโดย" (Add details by) 
                // ถ้าเป็น en หรือ cn ให้ใช้ fname_en + lname_en
                // ถ้าเป็น th ให้ใช้ fname_th + lname_th
                if($locale == 'en' || $locale == 'cn') {
                    $detailBy = $fund->user->fname_en . ' ' . $fund->user->lname_en;
                } else {
                    $detailBy = $fund->user->fname_th . ' ' . $fund->user->lname_th;
                }
            @endphp

            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Fund type') }}</b></p>
                <p class="card-text col-sm-9">{{ $fundType }}</p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Level Funds') }}</b></p>
                <p class="card-text col-sm-9">{{ $fundLevel }}</p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Agency') }}</b></p>
                <p class="card-text col-sm-9">{{ $fund->fund_agency }}</p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Add details by') }}</b></p>
                <p class="card-text col-sm-9">{{ $detailBy }}</p>
            </div>

            <div class="pull-right mt-5">
                <a class="btn btn-primary btn-sm" href="{{ route('funds.index') }}">
                    {{ trans('dashboard.Back') }}
                </a>
            </div>
        </div>
    </div>  
</div>
@endsection
