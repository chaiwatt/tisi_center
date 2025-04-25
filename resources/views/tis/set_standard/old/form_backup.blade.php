@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
@endpush

<div class="row">

      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#mor" aria-controls="mor" role="tab" data-toggle="tab">มอก.</a></li>
        <li role="presentation"><a href="#plan" aria-controls="plan" role="tab" style="cursor: default;">แผน</a></li>
        <li role="presentation"><a href="#result" aria-controls="result" role="tab" style="cursor: default;">ผล</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="mor">
            @include('tis.set_standard.mor', ['set_standard' => $set_standard ?? null])
        </div>
        <div role="tabpanel" class="tab-pane" id="plan">
{{--            @include('tis.set_standard.plan')--}}
        </div>
        <div role="tabpanel" class="tab-pane" id="result">
{{--            @include('tis.set_standard.result')--}}
        </div>

        </div>
      </div>



</div>


@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<!-- tag input -->
<script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

<!-- input file -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {

      const INITIAL_FORM = {
          title: '',
          title_en: '',
          start_year: '',
          // made_by, sdo_name not use
          product_group_id: '',
          appoint_id: '',
          standard_type_id: '',
          standard_format_id: '',
          remark: '',
          set_format_id: '',
          method_id: '',
          industry_target_id: '',
          cluster_id: '',
          refer: [{value: ''}],
          attaches: []
      };

      new Vue({
          el: '#app_mor_container',
          data: {
              form: _.clone(INITIAL_FORM),
              review_status: '1',
              made_by: null,
              sdo_name: "",
              tis_no: "",
              tis_no_text: "",
              tis_book: '',
              tis_nos: []
          },
          async mounted() {
              this.initEditForm('{{ $set_standard ? $set_standard->id : '' }}');

              const vm = this;
              $(this.$refs.start_year).select2().on('change', function () {
                  vm.form.start_year = this.value;
                  $(vm.$refs.start_year).val(this.value)
              });
          },
          computed: {
              selectSDO: function () {
                  return this.made_by === "SDO";
              },
              selectReview: function () {
                  return this.review_status === "2";
              },
              selectNo: function () {
                  return this.tis_no !== "";
              },
              getBooks: function () {
                  let book = [];
                  if (this.tis_no !== "") {
                      this.tis_nos.map(tis => {
                          if (tis.id === this.tis_no) {
                              book.push({value: tis.tis_book});
                          }
                      });
                  }
                  return book;
              }
          },
          methods: {
              onChangeTisNo: async function () {
                  if (this.review_status === "2") {
                      const url = "{{ url('api/tis/standards') }}";
                      try {
                          const res = await axios(url);
                          const data = res.data;
                          this.tis_nos = data.standards.filter(standard => { // เฉพาะที่เป็น ทบทวน
                              return standard.review_status === 2;
                          });
                          console.log(data.standards);
                      } catch (e) {
                          console.log(e);
                      }
                  } else {
                      this.tis_no = "";
                      this.tis_no_text = "";
                      this.form = _.clone(INITIAL_FORM);
                  }
              },
              onChangeNo: function() {
                  const tis_no = this.tis_nos.find(tis => {
                      return tis.id === this.tis_no;
                  });
                  if (tis_no) {
                      this.form = {
                          title: tis_no.title,
                          title_en: tis_no.title_en,
                          start_year: tis_no.tis_year,
                          product_group_id: tis_no.product_group_id,
                          appoint_id: tis_no.appoint_id,
                          standard_type_id: tis_no.standard_type_id,
                          standard_format_id: tis_no.standard_format_id,
                          remark: tis_no.remark,
                          set_format_id: tis_no.set_format_id,
                          method_id: tis_no.method_id,
                          industry_target_id: tis_no.industry_target_id,
                          refer: tis_no.refers.map(refer => {
                              return {value: refer};
                          }),
                          attaches: tis_no.attaches,
                      };
                  } else {
                      this.form = _.clone(INITIAL_FORM);
                  }
              },
              onClickAttachAdd: function () {
                  this.form.attaches.push({
                      file_name: '',
                      file_note: ''
                  })
              },
              onClickReferAdd: function () {
                  this.form.refer.push({value: ''});
              },
              onClickReferRemove: function (index) {
                  this.form.refer.splice(index, 1);
              },
              initEditForm: async function (id) {
                  if (id === '') {
                      return;
                  }

                  try {
                      const url = "{{ url('api/tis/set_standard') }}/" + id;
                      const res = await axios(url);
                      console.log(res);
                      const tis_no = res.data.set_standard;

                      this.review_status = tis_no.review_status.toString();
                      this.made_by = tis_no.made_by;
                      this.sdo_name = tis_no.sdo_name;
                      this.tis_no = tis_no.standard_id ? tis_no.standard_id : '';
                      this.tis_no_text = tis_no.tis_no;
                      this.tis_book = tis_no.tis_book;

                      this.form = {
                          title: tis_no.title,
                          title_en: tis_no.title_en,
                          start_year: tis_no.start_year,
                          product_group_id: tis_no.product_group_id,
                          appoint_id: tis_no.appoint_id,
                          standard_type_id: tis_no.standard_type_id,
                          standard_format_id: tis_no.standard_format_id,
                          remark: tis_no.remark,
                          set_format_id: tis_no.set_format_id,
                          method_id: tis_no.method_id,
                          industry_target_id: tis_no.industry_target_id,
                          cluster_id: tis_no.cluster_id,
                          refer: tis_no.refers.map(refer => {
                              return {value: refer};
                          }),
                          attaches: tis_no.attaches
                      };

                      this.tis_nos = res.data.standards.filter(standard => { // เฉพาะที่เป็น ทบทวน
                          return standard.review_status === 2;
                      });

                  } catch (e) {
                      console.log(e);
                  }

              }
          }
      });

    //เมื่อเลือกจัดทำโดย
    $('#made_by').change(function(event) {
      if($(this).val()=='SDO'){
        $('.sdo_name').show();
      }else{
        $('.sdo_name').hide();
        $('#sdo_name').val('');
      }
    });

    //เมื่อเพิ่มข้อมูลอ้างอิง
    $('#add-refer').click(function(){

      $('#refer-box').children(':first').clone().appendTo('#refer-box'); //Clone Element

      //edit button
      var last_new = $('#refer-box').children(':last');
      $(last_new).find('input').val('');
      $(last_new).find('button').removeClass('btn-success');
      $(last_new).find('button').addClass('btn-danger remove-refer');
      $(last_new).find('button').html('<i class="icon-close"></i>');

    });

    //เมื่อลบข้อมูลอ้างอิง
    $('body').on('click', '.remove-refer', function(event) {
      $(this).parent().parent().remove();
    });

    //เพิ่มไฟล์แนบ
    $('#attach-add').click(function(event) {
      $('.other_attach_item:first').clone().appendTo('#other_attach-box');

      $('.other_attach_item:last').find('input').val('');
      $('.other_attach_item:last').find('a.fileinput-exists').click();
      $('.other_attach_item:last').find('a.view-attach').remove();

      ShowHideRemoveBtn();

    });

    //ลบไฟล์แนบ
    $('body').on('click', '.attach-remove', function(event) {
      $(this).parent().parent().remove();
      ShowHideRemoveBtn();
    });

    ShowHideRemoveBtn();

    $('#made_by').change();

    $('#secretary').tagsinput({
      onTagExists: function(item, $tag) {
        $tag.hide().fadeIn();
      },
      maxTags: 3,
    });

    $('div.bootstrap-tagsinput').addClass('col-md-12');
    // $('div.bootstrap-tagsinput input').prop('disabled', true);

    function delayInputDisable() {
      $("div.bootstrap-tagsinput input").attr('readonly', true);
    }
    setTimeout(delayInputDisable, 1000);  // use setTimeout() to execute.

    $('#appoint_id').change(function(){
        $("#secretary").tagsinput('removeAll');
            var data_val = $(this).val();
            if(data_val!=""){
              $.ajax({
                type: "GET",
                url: "{{url('tis/set_standard/get_secretary')}}",
                datatype: "html",
                data: {
                    appoint_id: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    $("#secretary").tagsinput('add',list);
                    // $('div.bootstrap-tagsinput input').prop('disabled', true);
                }
              });
            }
    });

  });

  function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

    if ($('.other_attach_item').length > 1) {
      $('.attach-remove').show();
    } else {
      $('.attach-remove').hide();
    }

  }

  function Cal(){
    //คำนวนจำนวนคน//
    var numpeople_g = $("#numpeople_g").val();
    var numpeople_subg = $("#numpeople_subg").val();
    var numpeople_attendees = $("#numpeople_attendees").val();
    var Cal_people = parseFloat(numpeople_subg)+parseFloat(numpeople_g);
    Cal_people = (isNaN(Cal_people))?0:Cal_people;
    var Cal_total = parseFloat(numpeople_attendees) - Cal_people;
    $("#total").val(Cal_total);

    //คำนวนเงินกว.//
    var allowances_referee_g = $("#allowances_referee_g").val();
    var allowances_persident_g = $("#allowances_persident_g").val();
    var cal_g_allowances1 = parseFloat(numpeople_g) * parseFloat(allowances_referee_g);
    var cal_g_allowances2 = parseFloat(allowances_persident_g)-parseFloat(allowances_referee_g);
    var total_allowances = cal_g_allowances1+cal_g_allowances2;
    $("#sum_g").val(total_allowances);

    //คำนวณเงิน อนุกว.//
    var allowances_referee_subg = $("#allowances_referee_subg").val();
    var allowances_persident_subg = $("#allowances_persident_subg").val();
    var cal_sumg_allowances1 = parseFloat(numpeople_subg) * parseFloat(allowances_referee_subg);
    var cal_sumg_allowances2 = parseFloat(allowances_persident_subg)-parseFloat(allowances_referee_subg);
    var total_allowances_sumg = cal_sumg_allowances1+cal_sumg_allowances2;
    $("#sum_subg").val(total_allowances_sumg);

    //คำนวณเงินผู้เข้าร่วมประชุม//
    var food_morning_attendees = $("#food_morning_attendees").val();
    var food_noon_attendees = $("#food_noon_attendees").val();
    var food_afternoon_attendees = $("#food_afternoon_attendees").val();

     console.log(food_morning_attendees);
                console.log(food_noon_attendees);
                console.log(food_afternoon_attendees);
    var cal_food = parseFloat(food_morning_attendees)+parseFloat(food_noon_attendees)+parseFloat(food_afternoon_attendees);
    var total_attendees = parseFloat(numpeople_attendees) * cal_food;
    $("#sum_attendees").val(total_attendees);

    //คำนวนรวม(บาท)//
    var sum_g = $("#sum_g").val();
    var sum_subg = $("#sum_subg").val();
    var sum_attendees = $("#sum_attendees").val();
    var sum_total = parseFloat(sum_g) + parseFloat(sum_subg) + parseFloat(sum_attendees);
    $("#sum").val(sum_total);
  }

  function sumOfColumns(){

    var totalQuantity = 0;
    var totalPrice = 0;
    $(".sumtotal").each(function(){
        totalQuantity += parseFloat($(this).html());
        $(".someTotalClass").html(totalQuantity);
    });

    $(".sumtotal_att").each(function(){
        totalPrice += parseFloat($(this).html());
        $(".someTotalPrice").html(totalPrice);
    });

    var sumCalTotal = parseFloat(totalQuantity) + parseFloat(totalPrice);
    $(".sumTotal").html(sumCalTotal);
  }


  function remove(id){
    $("#row" + id).remove();
    sumOfColumns();
  }

  $(document).ready(function () {
    $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
  });

  //JqueryUpdatePlan//
  $( document ).ready(function() {

    var url = "/bigdata-itisi-center/public/tis/set_standard_plan";

    $('.table').on("click", '.btn-edit', function() {
        //$("#planedit-form").show();

        var id = $(this).data("id");
        var editUrl = url + '/' + id + '/edit';
        $.get( editUrl, function (data) {
            //  get field values
            $('#record_id').val(data.id);
            $("input[name='statusOperation_ed']").val(data.statusOperation_id);
            $("input[name='year_ed']").val(data.year);
            $("input[name='quarter_ed']").val(data.quarter);
            $("input[name='startdate_ed']").val(data.start_day);
            $("input[name='enddate_ed']").val(data.end_day);
            //Row1//
            $("input[name='numpeople_g_ed']").val(data.numpeople_g);
            $("input[name='allowances_referee_g_ed']").val(data.allowances_referee_g)
            $("input[name='allowances_persident_g_ed']").val(data.allowances_persident_g);
            $("input[name='sum_g_ed']").val(data.sum_g)
            //Row2//
            $("input[name='numpeople_subg_ed']").val(data.numpeople_subg);
            $("input[name='allowances_referee_subg_ed']").val(data.allowances_referee_subg);
            $("input[name='allowances_persident_subg_ed']").val(data.allowances_persident_subg);
            $("input[name='sum_subg_ed']").val(data.sum_subg);
            //Row3//
            $("input[name='numpeople_attendees_ed']").val(data.numpeople_attendees);
            $("input[name='food_morning_attendees_ed']").val(data.food_morning_attendees);
            $("input[name='food_noon_attendees_ed']").val(data.food_noon_attendees);
            $("input[name='food_afternoon_attendees_ed']").val(data.food_afternoon_attendees);
            $("input[name='sum_attendees_ed']").val(data.sum_attendees);
            //RowSum//
            $("input[name='total_ed']").val(data.total);
            $("input[name='sum_ed']").val(data.sum);


            $('.btn_updatePlan').val("update");
        })
    });

  $( ".btn_updatePlan" ).click(function() {
      var operation_id = $("input[name='statusOperation_ed']").select2().find(":selected").attr("id")
      var $select = $("input[name='statusOperation_ed']").parent().find("select"); // it's <select> element
      var value = $select.val();

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });

      var formData = {
          id : $('#record_id').val(),
          statusOperation_id : $(".select2 option:selected input[name='statusOperation_ed']").select2().val(),
          year : $("input[name='year_ed'] :selected").select2().val(),
          quarter :  $("input[name='quarter_ed'] :selected").select2().val(),
          start_day :  $("input[name='startdate_ed']").val(),
          end_day :  $("input[name='enddate_ed']").val(),
            //Row1//
          numpeople_g:  $("input[name='numpeople_g_ed']").val(),
          allowances_referee_g:  $("input[name='allowances_referee_g_ed']").val(),
          allowances_persident_g:  $("input[name='allowances_persident_g_ed']").val(),
          sum_g:  $("input[name='sum_g_ed']").val(),
            //Row2//
          numpeople_sub:  $("input[name='numpeople_subg_ed']").val(),
          allowances_referee_subg:  $("input[name='allowances_referee_subg_ed']").val(),
          allowances_persident_subg:  $("input[name='allowances_persident_subg_ed']").val(),
          sum_subg:  $("input[name='sum_subg_ed']").val(),
            //Row3//
          numpeople_attendees:  $("input[name='numpeople_attendees_ed']").val(),
          food_morning_attendees:  $("input[name='food_morning_attendees_ed']").val(),
          food_noon_attendees:  $("input[name='food_noon_attendees_ed']").val(),
          food_afternoon_attendees:  $("input[name='food_afternoon_attendees_ed']").val(),
          sum_attendees:  $("input[name='sum_attendees_ed']").val(),
            //RowSum//
          total:  $("input[name='total_ed']").val(),
      };

      //used to determine the http verb to use [add=POST], [update=PUT]
      var state = $('.btn_updatePlan').val();
      var type = "GET"; //for creating new resource
      var id = $('#'+'record_id').val(); // btn-save ID
      var my_url = url + '/' + id + '/update';

      $.ajax({
          type: type,
          url: my_url,
          data: formData,
          dataType: 'json',
          success: function (data) {
            alert('Record updated successfully');
            window.location.reload();
          },
          error: function (data) {
              console.log('Error:', data);
              var obj = {
              };
          }
      });
  });
  });

</script>
@endpush
