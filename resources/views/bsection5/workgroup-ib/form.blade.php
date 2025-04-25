@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

    <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'ชื่อกลุ่มงาน', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
        {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ใช้งาน</label>
    <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่ใช้งาน</label>

            {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-12">

        <h4>
            รายชื่อเจ้าหน้าที่ในกลุ่มงาน
            <button type="button" class="btn btn-success glow m-b-10 pull-right" data-toggle="modal" data-target="#AddUserForm" id="ButtonAddUserForm"> <i class="icon-plus" aria-hidden="true"></i>
                <span class="align-middle ml-25"> เพิ่ม</span>
            </button>
        </h4>
        <div class="clearfix"></div>

        <div class="table-responsive">
            <table class="table color-bordered-table primary-bordered-table" width="60%"id="div_table_scope">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th class="text-center" width="18%">รายชื่อเจ้าหน้าที่</th>
                        <th class="text-center" width="17%">ตำเเหน่ง</th>
                        <th class="text-center" width="30">กอง</th>
                        <th class="text-center" width="25%">กลุ่มงาน</th>
                        <th class="text-center" width="5%">ลบ</th>
                    </tr>
                </thead>
                <tbody class="font-medium-1" id="tbody-add-user">
                    @isset($workgroup_staff)
                        @foreach ( $workgroup_staff as $key=> $item_staff )
                            @php
                                $user = App\User::where('runrecno',$item_staff->user_reg_id)->first();
                            @endphp

                            @if( !is_null ($user) )
                                <tr>
                                    <td class="text-center">{{$key +1}}</td>
                                    <td>
                                        {!! !empty($user->FullName)?$user->FullName:null !!}
                                        <input type="hidden" name="user_reg_id[]" value="{!! $item_staff->user_reg_id !!}">
                                    </td>
                                    <td>
                                        {!! !empty($user->position)?$user->position:'-' !!}
                                    </td>
                                    <td>
                                        {!! !is_null($user->subdepart->department)? $user->subdepart->department->depart_name:null !!}
                                    </td>
                                    <td>
                                        {!! !is_null($user->subdepart)? $user->subdepart->sub_departname:null !!}
                                    </td>
                                    <td class="text-center font-medium-1">
                                        <button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn_remove_branch "><i class="fa fa-trash"></i></i></button>
                                    </td>
                                </tr>
                            @endif

                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

    @include ('bsection5.workgroup-ib.modal-add-user')

    <div class="col-12">

        <h4>
            รายชื่อสาขาผลิตภัณฑ์ที่ดูแล
            <button type="button" class="btn btn-success glow m-b-10 pull-right" data-toggle="modal" data-target="#AddStdForm" id="ButtonAddStdForm"> <i class="icon-plus" aria-hidden="true"></i>
                <span class="align-middle ml-25"> เพิ่ม</span>
            </button>
        </h4>
        <div class="clearfix"></div>

        <div class="table-responsive">
            <table class="table color-bordered-table primary-bordered-table" width="60%"id="div_table_scope">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th class="text-center" width="40%">สาขาผลิตภัณฑ์</th>
                        <th class="text-center" width="10%">ลบ</th>
                </thead>
                <tbody class="font-medium-1" id="tbody-add-branch">
                    @isset($workgroup_branch)
                        @foreach($workgroup_branch as $key => $item_branch)
                        @php
                            $branch_group = $item_branch->branch_group;
                        @endphp
                            <tr>
                                <td class="text-center">{{ $key+1}}</td>
                                <td>
                                    <p>{{ $branch_group->title }}</p>
                                    <input type="hidden" name="branch_group_id[]" value="{!! $item_branch->branch_group_id !!}">
                                </td>
                                <td class="text-center font-medium-1">
                                    <button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn_remove_branch "><i class="fa fa-trash"></i></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

    @include ('bsection5.workgroup-ib.modal-add-branch')

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('bsection5-workgroup'))
            <a class="btn btn-default" href="{{url('/bsection5/workgroup-ib')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script>
    $(document).ready(function () {

    //Form Request
    $('#request-form').find('button, a').remove();
    $('#request-form').find('.btn-remove-file').remove();
    $('#request-form').find('input[type="text"], input[type="radio"], input[type="checkbox"], input[type="file"], textarea, select, button').prop('disabled', true);
    $('#request-form').find('.note-editable').prop('contenteditable', false);

    });


</script>
@endpush
