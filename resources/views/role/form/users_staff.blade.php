<div class="row">
    
    <div class="col-md-5 col-xs-5">
            {!! Form::select('from[]', $from, null, ['class' => 'not_select2 col-md-12', 'multiple'=>'multiple', 'size'=>13, 'id'=>'lstview']) !!}
            {{count($from)}}
    </div>

    <div class="col-xs-2">
        <button type="button" id="lstview_undo" class="btn btn-danger btn-block waves-effect waves-light">ย้อนกลับ</button>
        <button type="button" id="lstview_rightAll" class="btn btn-default btn-block waves-effect waves-light"><i class="glyphicon glyphicon-forward"></i></button>
        <button type="button" id="lstview_rightSelected" class="btn btn-default btn-block waves-effect waves-light"><i class="glyphicon glyphicon-chevron-right"></i></button>
        <button type="button" id="lstview_leftSelected" class="btn btn-default btn-block waves-effect waves-light"><i class="glyphicon glyphicon-chevron-left"></i></button>
        <button type="button" id="lstview_leftAll" class="btn btn-default btn-block waves-effect waves-light"><i class="glyphicon glyphicon-backward"></i></button>
        <button type="button" id="lstview_redo" class="btn btn-warning btn-block waves-effect waves-light">เลิกย้อน</button>
    </div>

    <div class="col-md-5 col-xs-5">

        
        {!! Form::select('to[]', $to, null, ['class' => 'not_select2 col-md-12', 'multiple'=>'multiple', 'size'=>13, 'id'=>'lstview_to']) !!}
        {{count($to)}}
    </div>
</div>
