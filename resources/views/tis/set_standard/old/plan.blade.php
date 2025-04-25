@php
    $labelSize = 'col-md-4';
    $formSize = 'col-md-8';
@endphp


<div class="clearfix"></div>
<div id="app_plan_container">

    {!! Form::model($set_standard, [
        'method' => 'PATCH',
        'url' => ['/tis/set_standard/update-plan', $set_standard->id],
        'class' => 'form-horizontal',
        'files' => true
    ]) !!}

        <plan-form
                prop-url="{{ url('/tis/set_standard/update-plan') . '/' . $set_standard->id }}"
                :years="years"
                :status-operations="status_operations"
                :appoint-names="appoint_names"
                :prop-values="values"
                :prop-plan-id="plan_id"
                :prop-status-id="status_id"
                :prop-appoint-name-id="appointNameId"
                :prop-year="year"
                :prop-quarter="quarter"
                :prop-start-date="startDate"
                :prop-end-date="endDate"
                :prop-add-new-plan="addNewPlan"
                :prop-reset-plat-form-data="resetPlatformData">
        </plan-form>

    {!! Form::close() !!}

    <div class="row p-r-15">
        <div class="col-md-12">
            <!--แสดงตาราง -->
            <table id="tb_plan" class="table table-bordered">
                <tr class="info">
                    <th>No.</th>
                    <th>กิจกรรม</th>
                    <th>ปี</th>
                    <th>ไตรมาส</th>
                    <th>วันที่ทำกิจกรรม</th>
                    <th>เบี้ยประชุม</th>
                    <th>ค่าอาหารว่าง</th>
                    <th>ลบ</th>
                    <th>แก้ไข</th>
                    {{-- <th>คำนวนวันที่แล้วเสร็จ</th> --}}
                </tr>
                <tbody v-for="(plan, index) in plans">
                        <tr @click="onClickPlan(plan.id)" style="cursor: pointer;">
                            <td>@{{ index+1 }}</td>
                            <td>@{{ plan.status_operation.title }}</td>
                            <td>@{{ plan.year }}</td>
                            <td>@{{ plan.strQuarter }}</td>
                            <td>@{{ plan.strDate }}</td>
                            <td>@{{ plan.totalAllowances }}</td>
                            <td>@{{ plan.totalFoods }}</td>
                            <td>
                                <button title="ลบ" type="button" @click="deletePlan(plan)"
                                        class="btn btn-light" :disabled="hasDeleting">
                                    <i class="fa fa-trash-o text-danger"></i>
                                </button>
                            </td>
                            <td>
                                <button title="แก้ไข" type="button" class="btn btn-warning button-for-show">
                                    <i class="fa fa-pencil-square-o"></i>
                                </button>
                            </td>
                            {{-- <td>
                                <label><input type="radio" name="status"> สถานะสุดท้าย</label>
                            </td> --}}
                        </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" align="right">รวม</td>
                    <td class="someTotalClass">@{{ set_standard ? set_standard.totalAllowances : 0 }}</td>
                    <td class="someTotalPrice">@{{ set_standard ? set_standard.totalFoods : 0 }}</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">รวมทั้งสิ้น</td>
                    <td colspan="4" class="sumTotal">@{{ set_standard ? set_standard.total : 0 }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

@push('js')

@endpush
