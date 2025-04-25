<div class="modal fade" id="RewardMaxModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                 <h4 class="modal-title" id="CloseCaseModalLabel1">กำหนดเพดานเงินคำนวณ</h4>
             </div>
             <div class="modal-body form-horizontal">

                @php
                    $option_arrest    = App\Models\Law\Basic\LawArrest::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
                    $option_condition = ['='=>'เท่ากับ','<='=>'ไม่เกิน','>='=>'เกิน'];
                @endphp
             
                <div class="row">
                    <form id="form_reward_max" enctype="multipart/form-data" onsubmit="return false" >

                        <input type="hidden" id="reward_id" value="" >
                        <input type="hidden" id="reward_keys" value="">  

                        <div class="form-group">
                            {!! HTML::decode(Form::label('arrest', 'การจับกุม'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                            <div class="col-md-4 ">
                                {!! Form::select('arrest', $option_arrest,  null, ['class' => 'form-control', 'placeholder'=>'-เลือกการจับกุม-', 'required' => true,  'id'=>'arrest'])  !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! HTML::decode(Form::label('deducted', 'เงื่อนไขหักได้'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                            <div class="col-md-4">
                                {!! Form::select('deducted',  $option_condition ,  null,  ['class' => 'form-control', 'placeholder'=>'-เลือกเงื่อนไขหักได้-','required' => true,'id'=>'deducted'])  !!}
                            </div>
                            <div class="col-md-2 ">
                                {!! Form::text('number', null, ['class' => 'form-control ','id'=>'number', 'required' => true]) !!}
                            </div>
                            <div class="col-md-2 ">
                                {!! Form::select('percentage', ['%'=>'ร้อยละ (%)'],  '%', ['class' => 'form-control', 'disabled' => true, 'id'=>'percentage'])  !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! HTML::decode(Form::label('select_amount', 'เงื่อนไขจำนวนเงิน'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                            <div class="col-md-4 ">
                                {!! Form::select('select_amount', $option_condition ,  null, ['class' => 'form-control', 'placeholder'=>'-เลือกเงื่อนไขจำนวนเงิน-','required' => true,'id'=>'select_amount'])  !!}
                            </div>
                            <div class="col-md-2 ">
                                {!! Form::text('amount', null, ['class' => 'form-control amount','id'=>'amount', 'required' => true]) !!}
                            </div>
                            <div class="col-md-2 ">
                                {!! Form::select('choose_amount',  ['1'=>'จำนวนเงิน'],  '1', ['class' => 'form-control', 'disabled' => true, 'id'=>'choose_amount'])  !!}
                            </div>
                        </div>

                        <center>
                            <button type="submit" class="btn btn-primary" id="choose_reward_max">
                                เลือก
                            </button>
                        </center>

                    </form>
                </div>

                <div class="row">
                    <form id="save_form_reward_max" enctype="multipart/form-data" onsubmit="return false">
                        <input type="hidden" id="reward_max_id" name="reward_max_id" >
                        
                        <div class="col-md-12">
                            <center>
                                <div class="table-responsive m-t-15 reward-repeater">
                                    <table class="table table-striped"  >
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="2%">ลำดับ</th>
                                                <th class="text-center" width="40%">การจับกุม</th>
                                                <th class="text-center" width="25%">หักได้</th>
                                                <th class="text-center" width="25%">จำนวน</th>
                                                <th class="text-center" width="8%">จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_tbody_reward_max" data-repeater-list="reward-list">

                                        </tbody>
                                    </table>
                                </div>
                            </center>
                        </div>

                        <center>
                            <div class="text-center m-t-15">
                                <button type="submit" class="btn btn-primary" id="save_reward_max">
                                    <i class="icon-check"></i> บันทึก
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                    {!! __('ยกเลิก') !!}
                                </button>
                            </div>
                        </center>
                    </form>
                </div>

             </div>
         </div>
     </div>
</div>

@push('js')
    <script>
        $(document).ready(function () {

            $("body").on("click", ".reward_max", function() {

                let id = $(this).data('id');
                $('#reward_max_id').val(id);
                $('#table_tbody_reward_max').html('');

                var form_reward_max = $('#form_reward_max');
                    form_reward_max.find('input,textarea').val('');
                    form_reward_max.find('#arrest,#deducted,#select_amount').val('').trigger('change.select2');

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });

                $.ajax({
                    url: "{!! url('/law/reward/reward_max/get_data_reward_max') !!}" + "?id=" + id
                }).done(function( msg ) {

                    var percentage          = $('#percentage').val();
                    var percentage_text     = $('#percentage').find('option:selected').text();

                    if(msg.message == true){
                        var object = msg.datas;
                        var tr = '';
                        $.each(object,function(index, item ){
                            // การจับกุม
                            var arrest_id           = item.arrest_id;
                            let arrest_text         = item.arrest_text;

                            // เงื่อนไขหักได้ number
                            var deducted_id         = item.deducted_id;
                            let deducted_text       = item.deducted_text;
                            var number              = item.number;

                            // เงื่อนไขจำนวนเงิน
                            var select_amount_id    = item.select_amount_id;
                            let select_amount_text  = item.select_amount_text;
                            var amount              = item.money;

                            var input               =  '<input type="hidden" name="id" value="'+( item.id )+'" >';
                                input               += '<input type="hidden" name="arrest_id" class="arrest_id" value="'+(arrest_id)+'">';
                                input               += '<input type="hidden" name="deducted_id" class="deducted_id" value="'+(deducted_id)+'">';
                                input               += '<input type="hidden" name="number"  class="number" value="'+(number)+'">';
                                input               += '<input type="hidden" name="select_amount_id" class="select_amount_id" value="'+(deducted_id)+'">';
                                input               += '<input type="hidden" name="amount" class="amount" value="'+(amount)+'">';

                            var btn                 =  '<a href="javascript: void(0)" class="reward_edit m-r-5"><i class="pointer fa fa-pencil text-primary icon-pencil" style="font-size: 1.5em;"></i></a>';
                                btn                 += '<a href="javascript: void(0)" class="reward_delete"><i class="pointer fa fa-remove text-danger icon-remove" style="font-size: 1.5em;"></i></a>';

                            tr += '<tr data-repeater-item class="reward_tr">';
                            tr += '<td class="text-top text-center reward_list_no">'+( ++index )+'</td>';
                            tr += '<td class="text-top">'+( item.arrest_text )+'</td>';
                            tr += '<td class="text-top text-center">'+( deducted_text )+' '+( number )+' '+( percentage )+ '</td>';
                            tr += '<td class="text-top text-center">'+(select_amount_text)+' '+(amount)+ '</td>';
                            tr += '<td class="text-top text-center">'+btn+' '+input+'</td>';
                            tr += '</tr>';

                        });
                        $('#table_tbody_reward_max').append(tr);
                        OrderRewardNo();
                        $('.reward-repeater').repeater();
                        data_list_disabled();
                        $.LoadingOverlay("hide");

                    }else{
                        data_list_disabled();
                        $.LoadingOverlay("hide");

                    }
            
                });  
               
                $('#RewardMaxModals').modal('show');

            });

            //Edit
            $(document).on('click', '.reward_edit', function(e) {

                data_list_disabled();

                $('#table_tbody_reward_max').find('.pointer').show();

                let row              = $(this).closest( "tr" );
                let key              = row.data('row');
                let name_arr         = row.find('input').attr('name').split('][');
                let name_set         = name_arr[0]+"]";

                //Hide Btn
                row.find('.pointer').hide();

                //value
                var id               = row.find('input[name*="'+name_set+'[id]"]').val();
                var arrest_id        = row.find('input[name*="'+name_set+'[arrest_id]"]').val();
                var deducted_id      = row.find('input[name*="'+name_set+'[deducted_id]"]').val();
                var number           = row.find('input[name*="'+name_set+'[number]"]').val();
                var select_amount_id = row.find('input[name*="'+name_set+'[select_amount_id]"]').val();
                var amount           = row.find('input[name*="'+name_set+'[amount]"]').val();

                var form_reward_max  = $('#form_reward_max');
                    //Clear value
                    form_reward_max.find('input,textarea').val('');
                    form_reward_max.find('#arrest,#deducted,#select_amount').val('').trigger('change.select2');
                    form_reward_max.find('#arrest').children('option[value="'+arrest_id+'"]').prop('disabled',false);

                    //Set value
                    form_reward_max.find('#reward_keys').val(key);
                    form_reward_max.find('#reward_id').val(id);
                    form_reward_max.find('#arrest').val(arrest_id).trigger('change.select2');
                    form_reward_max.find('#deducted').val(deducted_id).trigger('change.select2');
                    form_reward_max.find('#select_amount').val(select_amount_id).trigger('change.select2');
                    form_reward_max.find('#number').val(number);
                    form_reward_max.find('#amount').val(amount);

            });

            //Delete
            $(document).on('click', '.reward_delete', function(e) {

                if( confirm("ต้องการลบแถวนี้หรือไม่ ?") ){
                    let row = $(this).closest( "tr" ).remove();
                    OrderRewardNo();
                    $('.reward-repeater').repeater();
                    data_list_disabled();
                }

            });

            //Save ส่วนเพิ่มและแก้ไข
            $('#form_reward_max').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var form = $("#form_reward_max");

                var id                 = form.find('#reward_id').val();
                var keys               = form.find('#reward_keys').val();

                // การจับกุม
                let arrest_id          = form.find('#arrest').val();
                let arrest_text        = form.find('#arrest').find('option:selected').text();

                // เงื่อนไขหักได้ number
                let deducted_id        = form.find('#deducted').val();
                let deducted_text      = form.find('#deducted').find('option:selected').text();
                let number             = form.find('#number').val();

                let percentage         = form.find('#percentage').val();
                let percentage_text    = form.find('#percentage').find('option:selected').text();
                // เงื่อนไขจำนวนเงิน
                var select_amount_id   = form.find('#select_amount').val();
                let select_amount_text = form.find('#select_amount').find('option:selected').text();
                var amount             = form.find('#amount').val();

                var input              =  '<input type="hidden" name="id" value="'+( id )+'" >';
                    input              += '<input type="hidden" name="arrest_id" class="arrest_id" value="'+(arrest_id)+'">';
                    input              += '<input type="hidden" name="deducted_id" class="deducted_id" value="'+(deducted_id)+'">';
                    input              += '<input type="hidden" name="number"  class="number" value="'+(number)+'">';
                    input              += '<input type="hidden" name="select_amount_id" class="select_amount_id" value="'+(deducted_id)+'">';
                    input              += '<input type="hidden" name="amount" class="amount" value="'+(amount)+'">';

                var btn                =  '<a href="javascript: void(0)" class="reward_edit m-r-5"><i class="pointer fa fa-pencil text-primary icon-pencil" style="font-size: 1.5em;"></i></a>';
                    btn                += '<a href="javascript: void(0)" class="reward_delete"><i class="pointer fa fa-remove text-danger icon-remove" style="font-size: 1.5em;"></i></a>';

                var tr = '';   
                    tr += '<tr data-repeater-item class="reward_tr">';
                    tr += '<td class="text-top text-center reward_list_no"></td>';
                    tr += '<td class="text-top">'+( arrest_text )+'</td>';
                    tr += '<td class="text-top text-center">'+( deducted_text )+' '+( number )+' '+( percentage )+ '</td>';
                    tr += '<td class="text-top text-center">'+(select_amount_text)+' '+(amount)+ '</td>';
                    tr += '<td class="text-top text-center">'+btn+' '+input+'</td>';
                    tr += '</tr>';

                if( checkNone(keys) ){
                    $("tr[data-row='" + keys + "']").before(tr); 
                    $("tr[data-row='" + keys + "']").remove();
                }else{
                    $('#table_tbody_reward_max').append(tr);
                }

                OrderRewardNo();
                $('.reward-repeater').repeater();
                data_list_disabled();
                //Clear value
                form.find('input,textarea').val('');
                form.find('#arrest,#deducted,#select_amount').val('').trigger('change.select2');
            });

            $('#save_form_reward_max').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#save_form_reward_max")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                if($('#table_tbody_reward_max').children().length > 0){

                    $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                    });

                    $.ajax({
                        type: "POST",
                        url: "{{ url('/law/reward/reward_max/save') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {
                            if(msg.message == true){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'บันทึกเรียบร้อย',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }else{
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                            table.draw();
                            $('#RewardMaxModals').modal('hide');
                            $.LoadingOverlay("hide");
                        }
                    });
                }else{
    
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกการจับกุม',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                }

            });

        });

        function OrderRewardNo(){
            $('#table_tbody_reward_max').find('.reward_list_no').each(function(index, el) {
                var uniqid = Math.floor(Math.random() * 1000000);
                $(el).closest( "tr" ).attr('data-row', uniqid);
                $(el).text(index+1);
            });
        }

        function data_list_disabled(){
            $('#arrest').children('option').prop('disabled',false);
            var rows = $('#table_tbody_reward_max').children(); //แถวทั้งหมด
            $(rows).each(function(index , item){
                var arrest_id = $(item).find('.arrest_id').val();
                $('#arrest').children('option[value="'+arrest_id+'"]').prop('disabled',true);
            });
        }
    </script>
@endpush