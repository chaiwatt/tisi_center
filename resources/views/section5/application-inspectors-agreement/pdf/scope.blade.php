@extends('layouts.print')
@push('styles')
    <style type="text/css">

        .hr {
            border: 1px solid black;
        }

        .font_pending{
            padding-top:-1px !important;
            /* font-weight:100; */
        }

        .font_span{
            border-bottom: 1px dotted black;
            padding-bottom: 0px;
        }
        .font-14 {
            font-size: 14pt;
            letter-spacing: 0px !important;
        }


        .font-16 {
            font-size: 16pt;
        }



        .font-bold {
            font-weight: bold;
        }

        @page {
            /* size: auto;
            padding-top: 35px;
            padding-bottom: 30px;
            margin-left: 10%;
            margin-right:  10%; */
        }

    </style>

@endpush
@section('content')

    @php
        $inspectors = $application->section5_inspectors;
        $agreement = $application->inspector_agreement;
    @endphp

    <div class="row">
        <table width="100%">
            <tbody>
                <tr>
                    <td align="center" class="font-16 font-bold">รายละเอียดแนบท้ายเงื่อนไขการขึ้นทะเบียนผู้ตรวจแและผู้ประเมิน</td>
                </tr>
                <tr>
                    <td align="center" class="font-16 font-bold">สำหรับผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</td>
                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tbody>
                <tr>
                    <td class="font-14 font_pending">
                        ชื่อผู้ตรวจ/ผู้ประเมิน ที่ได้รับการขึ้นทะเบียน: {!! !empty($application->applicant_full_name)?$application->applicant_full_name:'-' !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        ชื่อหน่วยงานผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม: {!! !empty($application->agency_name)?$application->agency_name:'-' !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        สถานประกอบการตั้งอยู่เลขที่: {!! !empty($application->AgencyDataAdress)?$application->AgencyDataAdress:'-' !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        รายละเอียดรายสาขาที่ขึ้นทะเบียน:
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table width="100%" style="border-collapse: collapse;overflow: wrap;">
            <thead>
                <tr style="border: 1px solid black" valign="top">
                    <td align="center" width="10%" style="border: 1px solid black;" class="font-14">หมวด</td>
                    <td align="center" width="40%" style="border: 1px solid black;" class="font-14">สาขาผลิตภัณฑ์</td>
                    <td align="center" width="50%" style="border: 1px solid black;" class="font-14">รายสาขา</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ( $Bgroup as $branch_group_id => $branchData )
                        @php

                            $BS_group = $scope_group_result->where('branch_group_id', $branch_group_id )->first();
                            $bs_branch_group = $BS_group->bs_branch_group;
                        @endphp

                        <tr>
                            <td class="font-14" align="center" valign="top" style="border: 1px solid black;display: inline">{!! array_key_exists( $branch_group_id, $group_number )?$group_number[ $branch_group_id ]:1 !!}</td>
                            <td class="font-14" valign="top" style="border: 1px solid black;display: inline">
                                {!! !empty( $bs_branch_group->title )? $bs_branch_group->title:null !!}
                            </td>
                            <td class="font-14" valign="top" style="border: 1px solid black;display: inline">
                                <div class="col-md-12" >
                                    @foreach ( $branchData as $branch )
                                        @php
                                            $bs_branch = $branch->bs_branch;
                                        @endphp
                                        <p>
                                            - {!! !empty( $bs_branch->title )? $bs_branch->title:null !!}
                                        </p>
                                    @endforeach
                                </div>
                            </td>
                        </tr>

                    @endforeach
                </tr>
            </tbody>
        </table>
        <br>
        <table width="100%">
            <tbody>
                <tr>
                    <td class="font-14 font_pending">
                       ทั้งนี้มีผลตั้งแต่วันที่ <span class="font_span">&nbsp;&nbsp;&nbsp;&nbsp;{!! !is_null($agreement) && !empty($agreement->start_date) ? HP::formatDateThaiFullPoint($agreement->start_date):null !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>จนถึงวันที่ <span class="font_span">&nbsp;&nbsp;&nbsp;&nbsp;{!! !is_null($agreement) && !empty($agreement->end_date)?HP::formatDateThaiFullPoint($agreement->end_date):null !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                       ขึ้นทะเบียนครั้งแรกเมื่อวันที่ <span class="font_span">&nbsp;&nbsp;&nbsp;&nbsp;{!! !is_null($agreement) && !empty($agreement->first_date) ? HP::formatDateThaiFullPoint($agreement->first_date) : null !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection
