@extends('layouts.master')

@push('css')

@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานการแก้ไขผู้ประกอบการ (SSO)</h3>

                    <div class="clearfix"></div>
                    <hr>

                    <iframe title="Report SSO - Report.edit.profile" class="col-md-12 col-sm-12" height="612" src="https://app.powerbi.com/view?r=eyJrIjoiODRjMzg2YjktOWMzMi00MTM0LTg0YTAtYzI5OTE1MmI1NGI1IiwidCI6IjYyYzFjOTFkLWQ2MTctNGRhYy05MDk0LTQ3M2YyYjhiOGE5YiIsImMiOjEwfQ%3D%3D" frameborder="0" allowFullScreen="true"></iframe>

                    <div class="clearfix"></div>

                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')

@endpush
