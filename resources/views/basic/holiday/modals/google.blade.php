<div class="modal fade" id="GoogleModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="GoogleModalLabel1">ปฏิทินวันหยุด Google</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('filter_modal_year', 'ปีปฏิทินวันหยุด', ['class' => 'col-md-12 label-filter']) !!}
                            <div class="col-md-12">
                                {!! Form::select('filter_modal_year',  HP::YearRange(date('Y')-2,5), date('Y'),['class' => 'form-control', 'id' => 'filter_modal_year', 'disabled' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="myTableCalendars">
                            <thead>
                                <tr>
                                    <th class="text-center" width="2%">#</th>
                                    <th class="text-center" width="50%">ชื่อวันหยุด</th>
                                    <th class="text-center" width="40%">วันที่</th>
                                    <th class="text-center" width="8%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
