@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Journal Details') }}</h4>
            <p class="card-description">{{ trans('dashboard.Journal Information') }}</p>
            
            <div class="row mt-3">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Paper Title') }}</b></p>
                <p class="card-text col-sm-9">{{ $patent->ac_name }}</p>
            </div>
            
            @php
                $locale = app()->getLocale();
                // แปลงประเภทโดยใช้ switch ถ้า locale ไม่ใช่ th ให้ใช้ข้อความภาษาอังกฤษ
                $value = $patent->ac_type;
                if($locale != 'th'){
                    switch($value) {
                        case 'สิทธิบัตร':
                            $typeDisplay = 'Patent';
                            break;
                        case 'สิทธิบัตร (การประดิษฐ์)':
                            $typeDisplay = 'Patent (Invention)';
                            break;
                        case 'สิทธิบัตร (การออกแบบผลิตภัณฑ์)':
                            $typeDisplay = 'Patent (Product Design)';
                            break;
                        case 'อนุสิทธิบัตร':
                            $typeDisplay = 'Utility Model';
                            break;
                        case 'ลิขสิทธิ์':
                            $typeDisplay = 'Copyright';
                            break;
                        case 'ลิขสิทธิ์ (วรรณกรรม)':
                            $typeDisplay = 'Copyright (Literature)';
                            break;
                        case 'ลิขสิทธิ์ (ดนตรี)':
                            $typeDisplay = 'Copyright (Musical)';
                            break;
                        case 'ลิขสิทธิ์ (ภาพยนตร์)':
                            $typeDisplay = 'Copyright (Film)';
                            break;
                        case 'ลิขสิทธิ์ (ศิลปะ)':
                            $typeDisplay = 'Copyright (Fine Arts)';
                            break;
                        case 'ลิขสิทธิ์ (งานแพร่ภาพ)':
                            $typeDisplay = 'Copyright (Broadcasting)';
                            break;
                        case 'ลิขสิทธิ์ (สื่อโสตทัศนวัสดุ)':
                            $typeDisplay = 'Copyright (Audiovisual)';
                            break;
                        case 'ลิขสิทธิ์ (งานอื่นในด้านวรรณคดี/วิทยาศาสตร์/ศิลปะ)':
                            $typeDisplay = 'Copyright (Other in Literature/Science/Art)';
                            break;
                        case 'ลิขสิทธิ์ (บันทึกเสียง)':
                            $typeDisplay = 'Copyright (Sound Recording)';
                            break;
                        case 'อื่น ๆ':
                            $typeDisplay = 'Others';
                            break;
                        case 'ความลับทางการค้า':
                            $typeDisplay = 'Trade Secret';
                            break;
                        case 'เครื่องหมายการค้า':
                            $typeDisplay = 'Trademark';
                            break;
                        default:
                            $typeDisplay = $value;
                            break;
                    }
                } else {
                    $typeDisplay = $value;
                }
            @endphp

            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Type') }}</b></p>
                <div class="row mt-2">
                    <p class="card-text col-sm-3"><b>{{ trans('dashboard.Type') }}</b></p>
                    <p class="card-test col-sm-9">
                        @if($locale == 'th')
                        {{ $typeDisplay->ac_type }}
                    @elseif($locale == 'cn')
                        {{ $typeDisplay->ac_type_cn }}
                    @else
                        {{ $typeDisplay->ac_type_en }}
                    @endif
    
                    </p>
            </div>
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Registration Date') }}</b></p>
                <p class="card-text col-sm-9">{{ $patent->ac_year }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Registration Number') }}</b></p>
                <p class="card-text col-sm-9">{{ trans('dashboard.Number') }} : {{ $patent->ac_refnumber }}</p>
            </div>
            
            <!-- Internal Faculty -->
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Internal Faculty') }}</b></p>
                <p class="card-text col-sm-9">
                    @foreach($patent->user as $a)
                        @if($locale == 'th')
                            {{ $a->fname_th }} {{ $a->lname_th }}
                        @else
                            @if(!empty($a->fname_en) || !empty($a->lname_en))
                                {{ $a->fname_en }} {{ $a->lname_en }}
                            @else
                                {{ $a->fname_th }} {{ $a->lname_th }}
                            @endif
                        @endif
                        @if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
            
            <!-- Co-Author -->
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Co-Author') }}</b></p>
                <p class="card-text col-sm-9">
                    @foreach($patent->author as $a)
                        @if($locale == 'th')
                            {{ $a->author_fname }} {{ $a->author_lname }}
                        @else
                            @if(!empty($a->fname_en) || !empty($a->lname_en))
                                {{ $a->fname_en }} {{ $a->lname_en }}
                            @else
                                {{ $a->author_fname }} {{ $a->author_lname }}
                            @endif
                        @endif
                        @if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
            
            <div class="pull-right mt-5">
                <a class="btn btn-primary btn-sm" href="{{ route('patents.index') }}">{{ trans('dashboard.Back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
