@extends('layouts.layout')
@section('content')

<div class="container refund">
    <p>{{ trans('researchproject.Title') }}</p>

    <div class="table-refund table-responsive">
        <table id="example1" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="font-weight: bold;">{{ trans('researchproject.Order') }}</th>
                    <th class="col-md-1" style="font-weight: bold;">{{ trans('researchproject.Year') }}</th>
                    <th class="col-md-4" style="font-weight: bold;">{{ trans('researchproject.ProjectName') }}</th>

                    <!-- <th>ระยะเวลาโครงการ</th>
                    <th>ผู้รับผิดชอบโครงการ</th>
                    <th>ประเภททุนวิจัย</th>
                    <th>หน่วยงานที่สนันสนุนทุน</th>
                    <th>งบประมาณที่ได้รับจัดสรร</th> -->
                    <th class="col-md-4" style="font-weight: bold;">{{ trans('researchproject.Detail') }}</th>
                    <th class="col-md-2" style="font-weight: bold;">{{ trans('researchproject.ProjectSuper') }}</th>
                    <!-- <th class="col-md-5">หน่วยงานที่รับผิดชอบ</th> -->
                    <th class="col-md-1" style="font-weight: bold;">{{ trans('researchproject.Status') }}</th>
                </tr>
            </thead>


            <tbody>
                @foreach($resp as $i => $re)
                <tr>
                    <td style="vertical-align: top;text-align: left;">{{$i+1}}</td>
                    <td style="vertical-align: top;text-align: left;">
                        <!-- {{($re->project_year)+543}} -->
                        @if (app()->getLocale() == 'th')
                        <!-- {{ \Carbon\Carbon::parse($re->project_start)->locale('th')->translatedFormat('Y') }}  -->
                        <!-- {{ \Carbon\Carbon::parse($re->project_year) }}  -->
                        {{($re->project_year)+543}}

                        @else
                        {{($re->project_year)}}

                        @endif
                    </td>
                    <td style="vertical-align: top;text-align: left;">
                        {{$re->project_name}}

                    </td>
                    <td>
                        <div style="padding-bottom: 10px">

                            <!-- @if ($re->project_start != null)
                            <span style="font-weight: bold;">
                                ระยะเวลาโครงการ
                            </span>
                            <span style="padding-left: 10px;">
                                {{\Carbon\Carbon::parse($re->project_start)->thaidate('j F Y') }} {{ trans('researchproject.To') }} {{\Carbon\Carbon::parse($re->project_end)->thaidate('j F Y') }}
                                
                            </span>
                            @else
                            <span style="font-weight: bold;">
                                ระยะเวลาโครงการ
                            </span>
                            <span>

                            </span>
                            @endif -->
                            @if ($re->project_start != null)
                            <span style="font-weight: bold;">
                                {{ trans('researchproject.Duration') }}

                            </span>
                            <span style="padding-left: 10px;">
                                @if (app()->getLocale() == 'th')
                                {{ \Carbon\Carbon::parse($re->project_start)->locale('th')->translatedFormat('j F') }}
                                {{ \Carbon\Carbon::parse($re->project_start)->year + 543 }}
                                {{ trans('researchproject.To') }}
                                {{ \Carbon\Carbon::parse($re->project_end)->locale('th')->translatedFormat('j F') }}
                                {{ \Carbon\Carbon::parse($re->project_end)->year + 543 }}
                                @else
                                {{ \Carbon\Carbon::parse($re->project_start)->locale('en')->translatedFormat('j F Y') }}
                                {{ trans('researchproject.To') }}
                                {{ \Carbon\Carbon::parse($re->project_end)->locale('en')->translatedFormat('j F Y') }}
                                @endif
                            </span>
                            @else
                            <span style="font-weight: bold;">
                                {{ trans('researchproject.Duration') }}

                            </span>

                            @endif



                        </div>


                        <!-- @if ($re->project_start != null)
                    <td>{{\Carbon\Carbon::parse($re->project_start)->thaidate('j F Y') }}<br>ถึง {{\Carbon\Carbon::parse($re->project_end)->thaidate('j F Y') }}</td>
                    @else
                    <td></td>
                    @endif -->

                        <!-- <td>@foreach($re->user as $user)
                        {{$user->position }}{{$user->fname_th}} {{$user->lname_th}}<br>
                        @endforeach
                    </td> -->
                        <!-- <td>
                        @if(is_null($re->fund))
                        @else
                        {{$re->fund->fund_type}}
                        @endif
                    </td> -->
                        <!-- <td>@if(is_null($re->fund))
                        @else
                        {{$re->fund->support_resource}}
                        @endif
                    </td> -->
                        <!-- <td>{{$re->budget}}</td> -->
                        <div style="padding-bottom: 10px;">
                            <span style="font-weight: bold;">{{ trans('researchproject.ResearchType') }}</span>
                            <!-- <span style="padding-left: 10px;"> 
                                @if(is_null($re->fund))
                                @else
                                {{$re->fund->fund_type}}
                                @endif
                            </span> -->



                            <span style="padding-left: 10px;">

                                @if (!is_null($re->fund))
                                {{ app()->getLocale() == 'en' ? $re->fund->fund_type_en : $re->fund->fund_type }}
                                @endif




                            </span>
                        </div>
                        <div style="padding-bottom: 10px;">
                            <span style="font-weight: bold;">{{ trans('researchproject.Funding_Agency') }}</span>
                            <span style="padding-left: 10px;">
                                <!-- @if(is_null($re->fund))
                                @else
                                {{$re->fund->support_resource}}
                                @endif</span> -->
                                
                                @if (!is_null($re->fund))
                                {{ app()->getLocale() == 'en' ? $re->fund->support_resource_en : $re->fund->support_resource}}
                                @endif

                        </div>
                        <div style="padding-bottom: 10px;">
                            <span style="font-weight: bold;">{{ trans('researchproject.Responsible_agency') }}</span>
                            <span style="padding-left: 10px;">
                            <!-- {{$re->responsible_department}} -->
                            @if (!is_null($re->responsible_department))
                                {{ app()->getLocale() == 'en' ? $re->responsible_department_en: $re-> responsible_department}}
                                @endif

                            
                                

                                
                            </span>
                        </div>
                        <div style="padding-bottom: 10px;">

                            <span style="font-weight: bold;">{{ trans('researchproject.Budget') }}</span>
                            <span style="padding-left: 10px;"> {{number_format($re->budget)}} {{ trans('researchproject.Baht') }}</span>
                        </div>
                    </td>

                    <td style="vertical-align: top;text-align: left;">
                        <!-- <div style="padding-bottom: 10px;">
                            <span>@foreach($re->user as $user)
                                {{$user->position_th }} {{$user->fname_th}} {{$user->lname_th}}<br>
                                @endforeach</span>
                        </div> -->

                        <div style="padding-bottom: 10px;">
                            <span>



                                @foreach ($re->user as $r)
                                @if($r->hasRole('teacher'))
                                @if(app()->getLocale() == 'en' and $r->academic_ranks_en == 'Lecturer' and $r->doctoral_degree == 'Ph.D.')
                                {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}, Ph.D.
                                <br>
                                @elseif(app()->getLocale() == 'en' and $r->academic_ranks_en == 'Lecturer')
                                {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}
                                <br>
                                @elseif(app()->getLocale() == 'en' and $r->doctoral_degree == 'Ph.D.')
                                {{ str_replace('Dr.', ' ', $r->{'position_'.app()->getLocale()}) }} {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}, Ph.D.
                                <br>
                                @else
                                {{ $r->{'position_'.app()->getLocale()} }} {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}
                                <br>
                                @endif

                                @endif
                                @endforeach
                        </div>







                    </td>
                    @if($re->status == 1)
                    <td style="vertical-align: top;text-align: left;">
                        <h6><label class="badge badge-success">{{ trans('researchproject.Request') }}</label></h6>
                    </td>
                    @elseif($re->status == 2)
                    <td style="vertical-align: top;text-align: left;">
                        <h6><label class="badge bg-warning text-dark">{{ trans('researchproject.On_going') }}</label></h6>
                    </td>
                    @else
                    <td style="vertical-align: top;text-align: left;">
                        <h6><label class="badge bg-dark">{{ trans('researchproject.Completed') }}</label>
                            <h6>
                    </td>
                    @endif
                    <!-- <td></td>
                    <td></td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

<script>
    $(document).ready(function() {

        var table1 = $('#example1').DataTable(
            {
            responsive: true,
            language: {
                search:"{{ trans('researchers.serach') }}",
            }
        });
    });
</script>
@stop