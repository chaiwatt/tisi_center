<!--form Modal -->
<div class="modal fade text-left" tabindex="10" id="AddStdForm" role="dialog" aria-labelledby="AddStdFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="AddStdFormLabel">รายชื่อสาขาผลิตภัณฑ์</h4>
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
                                                        {!! Form::label('filter_search_branch', 'คำค้น:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                                        <div class="col-md-10 form-group">
                                                            {!! Form::text('filter_search_branch', null, ['class' => 'form-control', 'id' => 'filter_search_branch', 'placeholder'=>'ค้นหาจาก ชื่อสาขาผลิตภัณฑ์']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group  pull-left">
                                                            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search_branch">ค้นหา</button>
                                                        </div>
                                                        <div class="form-group  pull-left m-l-15">
                                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean_branch">
                                                                ล้าง
                                                            </button>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped" id="myTable_add_branch">
                                        <thead>
                                            <tr>
                                                <th width="2%"><input type="checkbox" id="checkall_branch"></th>
                                                <th width="2%" class="text-center">No.</th>
                                                <th width="48%" class="text-center">สาขาผลิตภัณฑ์</th>
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
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" id="btn_add_branch"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">เลือก</span></button>
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

var table = $('#myTable_add_branch').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    autoWidth: false,
    lengthChange: false,
    ajax: {
        url: '{!! url('/bsection5/workgroup-ib/data_branch') !!}',
        data: function (d) {
            d.filter_search_branch = $('#filter_search_branch').val();
            d.filter_type = $('#filter_type').val();
        }
    },
    columns: [
        { data: 'checkbox', searchable: false, orderable: false},
        { data: 'DT_Row_Index', searchable: false, orderable: false},
        { data: 'title', name: 'title' }
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


$('#checkall_branch').on('click', function(e) {
    if($(this).is(':checked', true)){
        $(".item_branch_checkbox").prop('checked', true);
    } else {
        $(".item_branch_checkbox").prop('checked',false);
    }
});


$('#btn_search_branch').click(function () {
    table.draw();
});

$('#btn_clean_branch').click(function () {
    $('#filter_search_branch').val('');
    table.draw();
});


$("#btn_add_branch").on("click", function () {
    var rows = $('#tbody-add-branch').children();//แถวทั้งหมด
    var item_branch_checkbox =  $('.item_branch_checkbox:checked').length;

        if(item_branch_checkbox > 0  ){
            var  _tr = '';
           // $('#tbody-add-branch').html('');
            $('.item_branch_checkbox:checked').each(function(index, element){
                _tr += '<tr>';
                _tr += '<td class="text-center">'+(index+1)+'</td>'; //ลำดับ
                _tr += '<td>';
                _tr +=  '<p>'+$(element).data('title')+'</p>' ;
                _tr += '<input type="hidden" name="branch_group_id[]" value="'+ $(element).val() +'">';
                _tr += '</td>';
                _tr += '<td class="text-center font-medium-1">';
                _tr += '<button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn_remove_branch "><i class="fa fa-trash"></i></i></button>';
                _tr += '</td>';
                _tr += '</tr>';
            });

        $('#tbody-add-branch').append(_tr);

    }else{
        alert('กรุณาเลือกมอก. ที่ดูแล !');
    }

    ResetTableScopeBranch();

});

    ResetTableScopeBranch();

$("body").on("click", ".btn_remove_branch", function() {
    $(this).parent().parent().remove();
        ResetTableScopeBranch();
});


});

function ResetTableScopeBranch(){
            var rows = $('#tbody-add-branch').children(); //แถวทั้งหมด
            // (rows.length >= 1)?$('#div_table_scope').show():$('#div_table_scope').hide();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
        }

</script>
@endpush
