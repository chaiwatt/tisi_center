<!--form Modal -->
<div class="modal fade text-left" tabindex="10" id="AddStdForm" role="dialog" aria-labelledby="AddStdFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="AddStdFormLabel">รายชื่อ มอก.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box" >
                                <div class="row">
                                    <div class="col-md-12">
                                            <div class="row">
                                                    <div class="col-md-9">
                                                        {!! Form::label('filter_search_std', 'คำค้น:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                                        <div class="col-md-10 form-group">
                                                            {!! Form::text('filter_search_std', null, ['class' => 'form-control', 'id' => 'filter_search_std', 'placeholder'=>'ค้นหาจาก ชื่อ มอก. th/en']); !!}
                                                        </div>  
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group  pull-left">
                                                            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search_std">ค้นหา</button>
                                                        </div>
                                                        <div class="form-group  pull-left m-l-15">
                                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean_std">
                                                                ล้าง
                                                            </button>
                                                        </div>
                                                    </div>                                            
                                            </div> 
                                        </div>
                                    </div>
    
                            <div class="row">
                                <div class="col-md-12">    
                                    <table class="table table-striped" id="myTable_add_std">
                                        <thead>
                                            <tr>
                                                <th width="2%"><input type="checkbox" id="checkall_std"></th>
                                                <th width="2%" class="text-center">No.</th>
                                                <th width="96%" class="text-center">มอก.</th>   
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">ยกเลิก</span></button>
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" id="btn_add_std"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">เลือก</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@push('js')
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script>
     
     $(function () {

var table = $('#myTable_add_std').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    autoWidth: false,
    lengthChange: true,
    ajax: {
        url: '{!! url('/bsection5/workgroup/data_tis_standards') !!}',
        data: function (d) {

            d.filter_search_std = $('#filter_search_std').val();
            d.filter_status = $('#filter_status').val();
            d.filter_tis_id = $('#filter_tis_id').val();
            d.filter_type = $('#filter_type').val();

            
        }
    },
    columns: [
        { data: 'checkbox', searchable: false, orderable: false},
        { data: 'DT_Row_Index', searchable: false, orderable: false},
        { data: 'tis_name', name: 'tis_name' },
    ],
    columnDefs: [
        { className: "text-center", targets:[1] },
    ],
    fnDrawCallback: function() {

        $(".js-switch").each(function() {
            new Switchery($(this)[0], { size: 'small' });
        });
    }
});


$('#checkall_std').on('click', function(e) {
    if($(this).is(':checked',true)){
    $(".item_std_checkbox").prop('checked', true);
    } else {
    $(".item_std_checkbox").prop('checked',false);
    }
});


$('#btn_search_std').click(function () {
    table.draw();
});

$('#btn_clean_std').click(function () {
    $('#filter_search_std').val('');
    table.draw();
});


$("#btn_add_std").on("click" , function () {
    var rows = $('#tbody-add-std').children();//แถวทั้งหมด
    var item_std_checkbox =  $('.item_std_checkbox:checked').length;

        if(item_std_checkbox > 0  ){
            var  _tr = '';
               // $('#tbody-add-std').html('');
                $('.item_std_checkbox:checked').each(function(index, element){
                        _tr+= '<tr>';
                        _tr += '<td class="text-center">'+(index+1)+'</td>'; //ลำดับ
                        _tr+= '<td>';
                        _tr+=  '<p>'+$(element).data('tis_name')+'</p>' ;
                        _tr+= '<input type="hidden" name="tis_id[]" value="'+ $(element).val() +'">';
                        _tr+= '</td>';
                        _tr+= '<td class="text-center font-medium-1">';
                        _tr+= '<button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn_remove_branch "><i class="fa fa-trash"></i></i></button>';
                        _tr+= '</td>';
                        _tr+= '</tr>';
        });

        $('#tbody-add-std').append(_tr);

    }else{
        alert('กรุณาเลือกมอก. ที่ดูแล !');
    }
            
    ResetTableScopeStd();

});

    ResetTableScopeStd();

$("body").on("click", ".btn_remove_branch", function() {
    $(this).parent().parent().remove();
        ResetTableScopeStd();
});


});

function ResetTableScopeStd(){
            var rows = $('#tbody-add-std').children(); //แถวทั้งหมด
            // (rows.length >= 1)?$('#div_table_scope').show():$('#div_table_scope').hide();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
        }
   
</script>
@endpush