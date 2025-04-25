 
<div class="modal fade " id="actioncheck"   data-bs-backdrop="static" data-bs-keyboard="false"   aria-labelledby="actionStatusLabel1" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="actionStatusLabel1">
                    ท่านมีรายการที่รอพิจารณาคดี จำนวน {!! count($approves) !!} รายการ ดังนี้   
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
            </div>

        <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center text-top" width="5%">#</th>
                            <th class="text-center text-top" width="50%">ผู้ประกอนการ</th>
                            <th class="text-center text-top" width="45%">เลขที่อ้างอิง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approves as $key => $item )
                        
                            <tr>
                                <td class="text-center text-top">
                                    {!! ($key+1) !!}
                                </td>
                                <td class="text-top">
                                    {!!  !empty($item->offend_name) ? $item->offend_name : '' !!}
                                </td>
                                <td class="text-top">
                                    {!!  !empty($item->ref_no)?  $item->ref_no:''; !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


            </div>
            <div class="modal-footer ">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">รับทราบ</span></button>
            </div>
        </div>
    </div>
</div>
 
@push('js')

    <script>
    
        $(document).ready(function () {
              $('#actioncheck').modal('show');
        });

    </script>

@endpush


