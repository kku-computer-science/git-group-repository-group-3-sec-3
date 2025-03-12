@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">{{ trans('dashboard.Journal Details') }}</h4>
            <p class="card-description">{{ trans('dashboard.Journal Information') }}</p>
            
            <div class="row mt-3">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Paper Title') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_name }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Abstract') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->abstract }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Keyword') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->keyword }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Journal Type') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_type }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Document Subtype') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_subtype }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Publication') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->publication }}</p>
            </div>
            
            <!-- แสดงผู้จัดทำ (Internal Author) -->
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Author') }}</b></p>
                <p class="card-text col-sm-9">
                    @php
                        $locale = app()->getLocale();
                    @endphp

                    {{-- สำหรับ First Author --}}
                    @foreach($paper->author as $teacher)
                        @if($teacher->pivot->author_type == 1)
                            <b>{{ trans('dashboard.First Author') }}:</b>
                            @if($locale == 'th')
                                {{ $teacher->author_fname }} {{ $teacher->author_lname }}
                            @else
                                @if(!empty($teacher->fname_en) || !empty($teacher->lname_en))
                                    {{ $teacher->fname_en }} {{ $teacher->lname_en }}
                                @else
                                    {{ $teacher->author_fname }} {{ $teacher->author_lname }}
                                @endif
                            @endif
                            <br>
                        @endif
                    @endforeach
                    @foreach($paper->teacher as $teacher)
                        @if($teacher->pivot->author_type == 1)
                            <b>{{ trans('dashboard.First Author') }}:</b>
                            @if($locale == 'th')
                                {{ $teacher->fname_th }} {{ $teacher->lname_th }}
                            @else
                                @if(!empty($teacher->fname_en) || !empty($teacher->lname_en))
                                    {{ $teacher->fname_en }} {{ $teacher->lname_en }}
                                @else
                                    {{ $teacher->fname_th }} {{ $teacher->lname_th }}
                                @endif
                            @endif
                            <br>
                        @endif 
                    @endforeach

                    {{-- สำหรับ Co-Author --}}
                    @foreach($paper->author as $teacher)
                        @if($teacher->pivot->author_type == 2)
                            <b>{{ trans('dashboard.Co-Author') }}:</b>
                            @if($locale == 'th')
                                {{ $teacher->author_fname }} {{ $teacher->author_lname }}
                            @else
                                @if(!empty($teacher->fname_en) || !empty($teacher->lname_en))
                                    {{ $teacher->fname_en }} {{ $teacher->lname_en }}
                                @else
                                    {{ $teacher->author_fname }} {{ $teacher->author_lname }}
                                @endif
                            @endif
                            <br>
                        @endif
                    @endforeach
                    @foreach($paper->teacher as $teacher)
                        @if($teacher->pivot->author_type == 2)
                            <b>{{ trans('dashboard.Co-Author') }}:</b>
                            @if($locale == 'th')
                                {{ $teacher->fname_th }} {{ $teacher->lname_th }}
                            @else
                                @if(!empty($teacher->fname_en) || !empty($teacher->lname_en))
                                    {{ $teacher->fname_en }} {{ $teacher->lname_en }}
                                @else
                                    {{ $teacher->fname_th }} {{ $teacher->lname_th }}
                                @endif
                            @endif
                            <br>
                        @endif 
                    @endforeach

                    {{-- สำหรับ Corresponding Author --}}
                    @foreach($paper->author as $teacher)
                        @if($teacher->pivot->author_type == 3)
                            <b>{{ trans('dashboard.Corresponding Author') }}:</b>
                            @if($locale == 'th')
                                {{ $teacher->author_fname }} {{ $teacher->author_lname }}
                            @else
                                @if(!empty($teacher->fname_en) || !empty($teacher->lname_en))
                                    {{ $teacher->fname_en }} {{ $teacher->lname_en }}
                                @else
                                    {{ $teacher->author_fname }} {{ $teacher->author_lname }}
                                @endif
                            @endif
                            <br>
                        @endif
                    @endforeach
                    @foreach($paper->teacher as $teacher)
                        @if($teacher->pivot->author_type == 3)
                            <b>{{ trans('dashboard.Corresponding Author') }}:</b>
                            @if($locale == 'th')
                                {{ $teacher->fname_th }} {{ $teacher->lname_th }}
                            @else
                                @if(!empty($teacher->fname_en) || !empty($teacher->lname_en))
                                    {{ $teacher->fname_en }} {{ $teacher->lname_en }}
                                @else
                                    {{ $teacher->fname_th }} {{ $teacher->lname_th }}
                                @endif
                            @endif
                            <br>
                        @endif 
                    @endforeach
                </p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Journal Source Title') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_sourcetitle }}</p>
            </div>
            
            <!-- ปีที่ตีพิมพ์: ถ้าเป็นภาษาไทยให้ +543 -->
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Year of Publication') }}</b></p>
                <p class="card-text col-sm-9">
                    @php
                        $year = $paper->paper_yearpub;
                    @endphp
                    @if($locale == 'th')
                        {{ $year + 543 }}
                    @else
                        {{ $year }}
                    @endif
                </p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Volume') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_volume }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Issue Number') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_issue }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.Page') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_page }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.DOI') }}</b></p>
                <p class="card-text col-sm-9">{{ $paper->paper_doi }}</p>
            </div>
            
            <div class="row mt-2">
                <p class="card-text col-sm-3"><b>{{ trans('dashboard.URL') }}</b></p>
                <a href="{{ $paper->paper_url }}" target="_blank" class="card-text col-sm-9">{{ $paper->paper_url }}</a>
            </div>
            
            <a class="btn btn-primary mt-5" href="{{ route('papers.index') }}">{{ trans('dashboard.Back') }}</a>
        </div>
    </div>
</div>
@endsection
