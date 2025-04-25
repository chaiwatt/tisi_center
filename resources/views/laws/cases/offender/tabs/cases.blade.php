<div class="row box_filter_cases">
    <div class="col-md-4"> 
        <p class="h4 text-bold-300 text-left show_time_tabs">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <div class="col-md-12">
                {!! Form::text('filter_standard_cases', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา มอก.-', 'id' => 'filter_standard_cases']); !!}
            </div>
        </div>
    </div><!-- /.col-lg-5 -->
    <div class="col-lg-4">
        <div class="form-group">
            <div class="col-md-12">
                {!! Form::text('filter_license_number_cases', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา เลขที่ใบอนุญาต-', 'id' => 'filter_license_number_cases']); !!}
            </div>
        </div>
    </div><!-- /.col-lg-5 -->
</div>

<div class="row">
    <div class="pull-right">
        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean_cases">
            ล้างค่า
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped" id="myTable-Cases">
            <thead>
                <tr>
                    <th class="text-center" width="4%">ครั้งที่</th>
                    <th class="text-center" width="13%">เลขคดี</th>
                    <th class="text-center" width="12%">เลขใบอนุญาต</th>
                    <th class="text-center" width="18%">มอก.</th>
                    <th class="text-center" width="10%">ความผิดตามมาตรา</th>
                    <th class="text-center" width="10%">ดำเนินการ</th>
                    <th class="text-center" width="13%">วันที่พบการกระทำผิด</th>
                    <th class="text-center" width="10%">นิติกร</th>
                    <th class="text-center" width="10%">สถานะคดี</th>
                    <th class="text-center" width="10%">จัดการ</th>

                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            table_cases = $('#myTable-Cases').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/cases/offender/data_offender_cases') !!}',
                    data: function (d) {
                        d.law_offender_id       = '{!! $offender->id !!}';
                        d.filter_standard       = $('#filter_standard_cases').val();
                        d.filter_license_number = $('#filter_license_number_cases').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'license_number', name: 'license_number' },
                    { data: 'tis', name: 'tis' },
                    { data: 'law_section', name: 'law_section' },
                    { data: 'cases', name: 'cases' },
                    { data: 'date_offender_case', name: 'date_offender_case' },
                    { data: 'lawyer_by', name: 'lawyer_by' },                    
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0, 1, 2, -1, -2, -3] },
                    { className: "text-top", targets: "_all" }
                ],
                fnDrawCallback: function() {
                    ShowTime();
                }
            });

            $('#filter_standard_cases,#filter_license_number_cases').change(function (e) { 
                table_cases.draw();                
            });

            $("#filter_standard_cases").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-standards-td3') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $("#filter_license_number_cases").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-license-tb4') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $('#btn_clean_cases').click(function () {
                $('.box_filter_cases').find('input').select2('val', '');
                table_cases.draw();
            });


            $(document).on('click', '.btn_cases_edit', function () {

                if( checkNone( $(this).val() )  ){

                    $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                    });

                    $.ajax({
                        url: "{!! url('/law/cases/offender/html?id_case=') !!}" + $(this).val() + '&law_offender_id=' + '{!! $offender->id !!}'
                    }).done(function( object ) {
                        if( checkNone(object) ){

                            $('.form_cases_edit').html(object);
                            $('#OffenderCaseModal').modal('show');

                            //ปฎิทิน
                            $('.mydatepicker').datepicker({
                                autoclose: true,
                                toggleActive: true,
                                language:'th-th',
                                format: 'dd/mm/yyyy',
                            });

                            reBuiltSelect2(  $('.form_cases_edit').find('select'));
                                            
                            $('.repeater-product').repeater({
                                show: function () {

                                    $(this).slideDown();

                                    reBuiltSelect2($(this).find('select'));
                                    resetProductNo();
                                },
                                hide: function (deleteElement) {
                                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                                        $(this).slideUp(deleteElement);
                          
                                        setTimeout(function(){
                                            resetProductNo();
                                        }, 500);
                                    }
                                }
                            });
                            resetProductNo();

                            resetStandardNo();
                            
                            $(".check_format_en_and_number").on("keypress",function(e){
                                var eKey = e.which || e.keyCode;
                                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                                    return false;
                                }  
                            });

                            $("#seleted_tis_id").select2({
                                dropdownAutoWidth: true,
                                width: '100%',
                                ajax: {
                                    url: "{{ url('/law/funtion/search-standards-td3') }}",
                                    type: "get",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                            return {
                                                searchTerm: params // search term
                                            };
                                    },
                                    results: function (response) {
                                        return {
                                            results: response
                                        };
                                    },
                                    cache: true,
                                },
                                placeholder: 'คำค้นหา',
                                minimumInputLength: 1,
                            });

                            $('.repeater-licenses').repeater();
                        }
                        $.LoadingOverlay("hide");
                    });


                }

            });

            $(".check_format_en_and_number").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }  
            });

        });

        function reBuiltSelect2(select){
            //Select2 Destroy
            // $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

        function resetProductNo(){

            $('.product_no').each(function(index, el) {
                $(el).text(index+1);
            });

            if($('.product_no').length > 1){
                $('.product_delete').show();
            }else{
                $('.product_delete').hide();
            }

        }

    </script>
@endpush