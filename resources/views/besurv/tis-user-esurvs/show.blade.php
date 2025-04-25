@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดการตั้งค่ากลุ่มงานย่อยตามมาตรฐาน</h3>
                    @can('view-'.str_slug('tisuseresurvs'))
                        <a class="btn btn-success pull-right" href="{{ url('/besurv/tis-user-esurvs') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th> กลุ่มงานหลัก: </th>
                                  <td>
                                    <p class="form-control-static"> {{ $tisuseresurv->department->depart_name??'n/a' }} </p>
                                  </td>
                              </tr>
                              <tr>
                                  <th> กลุ่มงานย่อย: </th>
                                  <td>
                                    <p class="form-control-static"> {{ $tisuseresurv->sub_departname??'n/a' }} </p>
                                  </td>
                              </tr>
                              <tr>
                                  <th class="text-top"> มาตรฐาน: </th>
                                  <td>
                                      @if($tisuseresurv->tis_users->count() > 0 && $tisuseresurv->tis_users->first()->tb3_Tisno=='All')
                                        <span class="label label-rounded label-success">
                                          <b>มาตรฐานทั้งหมด</b>
                                        </span>
                                      @elseif($tisuseresurv->tis_users->count() > 0)
                                        <ol>
                                          @foreach ((array)$tisuseresurv->tis_users as $tis_user)
                                            @foreach ($tis_user as $key=>$item)
                                            <li> มอก. {{ $item->tb3_Tisno??'n/a' }} | {{ $item->TisName}} </li>
                                            @endforeach
                                          @endforeach
                                        </ol>
                                      @else
                                        <span class="label label-rounded label-default">
                                          <b>ไม่มี</b>
                                        </span>
                                      @endif
                                  </td>
                              </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
