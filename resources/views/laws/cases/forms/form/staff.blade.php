<div class="form-group">
    <button type="button" class="btn btn-sm btn-success glow mr-1 mb-1 pull-right" id="BtnAddFormStaff"><i class="fa fa-plus"></i> เพิ่ม</button>
</div>

<table class="table table-bordered staff-repeater" id="myTable-staff">
    <thead>
        <tr>
            <th class="text-center" width="2%">#</th>
            <th class="text-center" width="23%">ชื่อ-สกุล</th>
            <th class="text-center" width="25%">ที่อยู่ (สำหรับออกใบสำคัญรับเงินรางวัล)</th>
            <th class="text-center" width="15%">กลุ่ม/กอง</th>
            <th class="text-center" width="15%">ส่วนร่วมในคดี</th>
            <th class="text-center" width="15%">ชื่อบัญชี/เลขที่บัญชี</th>
            <th class="text-center" width="5%">จัดการ</th>
        </tr>
    </thead>
    <tbody id="tbd_staff" data-repeater-list="staff-list">
        @if(!empty($lawcasesform->cases_staff))
            @foreach($lawcasesform->cases_staff as $key => $staff )
                <tr data-repeater-item class="staff_tr">
                    <td class="text-top text-center staff_list_no">{{$key+1}}</td>
                    <td class="text-top text-center">
                        {!! !empty($staff->name)?$staff->name:null !!} 
                        {{-- {!! !empty($staff->taxid)?'<div>('.$staff->taxid.')</div>':null !!}  --}}
                    </td>
                    <td class="text-top">
                        {!! !empty($staff->address)?$staff->address:null !!} 
                        <div><i class="icon-phone"></i>{!! !empty($staff->mobile)?$staff->mobile:null !!} </div>
                        <div><i class="icon-envelope-open"></i>{!! !empty($staff->email)?$staff->email:null !!} </div>
                    </td>
                    <td class="text-top text-center">
                        {!! !empty($staff->DeparmentName)?$staff->DeparmentName:null !!} 
                        {!! !empty($staff->DeparmentTypeName)?'<div>('.$staff->DeparmentTypeName.')</div>':null !!} 
                    </td>
                    <td class="text-top text-center">
                        @if (!empty($lawcasesform->LawRewardGroupArrayID) && !in_array($staff->basic_reward_group_id,$lawcasesform->LawRewardGroupArrayID->toArray()))
                            <span class="reward_group text-danger reward_group_danger">
                                 {!! '(กรุณาตรวจสอบ/แก้ไข)' !!}
                            </span>
                        @else
                            <span class="reward_group ">
                                {!! !empty($staff->reward_group)?$staff->reward_group->title:null !!} 
                            </span>
                        @endif
                      
                    
                    </td>
                    <td class="text-top text-center">
                        {!! !empty($staff->ac_bank)?$staff->ac_bank->title:null !!} 
                        {!! !empty($staff->bank_account_name)?'<div>'.$staff->bank_account_name.'</div>':null !!} 
                        {!! !empty($staff->bank_account_number)?'<div>('.$staff->bank_account_number.')</div>':null !!} 

                        @if(!empty($staff->file_book_bank))
                            <a href="{!!  HP::getFileStorage($staff->file_book_bank->url) !!}" data-id="{!! $staff->file_book_bank->id !!}" class="attach" target="_blank">ไฟล์สมุดบัญชี</a>
                        @endif
                    </td>
                    <td class="text-top text-center">
                        <a href="javascript: void(0)" class="staf_edit m-r-5 show_tag_a"><i class="pointer fa fa-pencil text-primary icon-pencil" style="font-size: 1.5em;"></i></a>
                        <a href="javascript: void(0)" class="staf_delete show_tag_a"><i class="pointer fa fa-remove text-danger icon-remove" style="font-size: 1.5em;"></i></a>

                        <input type="hidden" name="id" value="{!! !empty($staff->id)?$staff->id:null !!}">

                        <!--ส่วนร่วมในคดี-->
                        <input type="hidden" name="basic_reward_group_id" class="basic_reward_group_id" value="{!! !empty($staff->basic_reward_group_id)?$staff->basic_reward_group_id:null !!}">

                        <!--หน่วยงานหน่วยงาน-->
                        <input type="hidden" name="depart_type" value="{!! !empty($staff->depart_type)?$staff->depart_type:null !!}">
                        <!--กอง/กลุ่ม-->
                        <input type="hidden" name="sub_department_id" value="{!! !empty($staff->sub_department_id)?$staff->sub_department_id:null !!}">
                        <!--หน่วยงาน-->
                        <input type="hidden" name="basic_department_id" value="{!! !empty($staff->basic_department_id)?$staff->basic_department_id:null !!}">
                        <!--ชื่อหน่วยงาน-->
                        <input type="hidden" name="department_name" value="{!! !empty($staff->department_name)?$staff->department_name:null !!}">

                        <!--ชื่อ-สกุล-->
                        <input type="hidden" name="name" value="{!! !empty($staff->name)?$staff->name:null !!}">
                        <!--เลขประจำตัวประชาชน-->
                        <input type="hidden" name="taxid" class="staff_taxid" value="{!! !empty($staff->taxid)?$staff->taxid:null !!}">
                        <!--ที่อยู่-->
                        <input type="hidden" name="address" value="{!! !empty($staff->address)?$staff->address:null !!}">
                        <!--เบอร์มือถือ-->
                        <input type="hidden" name="mobile" value="{!! !empty($staff->mobile)?$staff->mobile:null !!}">
                        <!--อีเมล-->
                        <input type="hidden" name="email" value="{!! !empty($staff->email)?$staff->email:null !!}">
                        
                        <!--ชื่อธนาคาร-->
                        <input type="hidden" name="basic_bank_id" value="{!! !empty($staff->basic_bank_id)?$staff->basic_bank_id:null !!}">
                        <!--ชื่อบัญชี-->
                        <input type="hidden" name="bank_account_name" value="{!! !empty($staff->bank_account_name)?$staff->bank_account_name:null !!}">
                        <!--เลขที่บัญชี-->
                        <input type="hidden" name="bank_account_number" value="{!! !empty($staff->bank_account_number)?$staff->bank_account_number:null !!}">

                    </td>

                </tr>
            @endforeach
        @endif
    </tbody>
</table>

 


<p class="font-medium-6 text-orange m-t10"> หมายเหตุ : เป็นข้อมูลที่ใช้สำหรับคำนวณเงินสินบน/เงินรางวัล และออกใบสำคัญรับเงิน กรุณาตรวจสอบข้อมูลให้ครบถ้วนและถูกต้อง </p>


@push('js')
    <script>
        $(document).ready(function() {

            $('.staff-repeater').repeater();

            table_staff = $('#myTable-staff').DataTable({
                serverSide: false,
                processing: false,
                columnDefs: [
                    { className: "text-center text-top", targets:[0, -1] }
                ],
                order: [[0, 'asc']]
            });


        });
    </script>
@endpush

