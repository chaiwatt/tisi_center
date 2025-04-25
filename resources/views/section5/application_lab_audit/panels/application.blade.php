<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ข้อมูลคำขอ # {!! $applicationlabaudit->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    @php
                        $applicationlab =  $applicationlabaudit;
                    @endphp
                    @include ('section5/application-request-form.application-lab')
                </div>
            </div>
        </div>
    </div>
</div>