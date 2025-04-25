<div class="col-md-12 col-sm-12">
    <div id="accordion">
        @isset($list_scope)
            @foreach ( $list_scope as $scopeSTD )
                @php
                    $tis_standards = $scopeSTD->tis_standards;
                    $tis_standards_id = !is_null($tis_standards) ? $tis_standards->getKey() : null ;
                @endphp

                @if (!is_null($tis_standards_id))
                    
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{!! $tis_standards_id !!}" aria-expanded="true" aria-controls="collapse-{!! $tis_standards_id !!}">
                                    {!! (!empty($tis_standards->tb3_Tisno)?$tis_standards->tb3_Tisno.' : ':null).(!empty($tis_standards->tb3_TisThainame)?$tis_standards->tb3_TisThainame:null) !!}
                                </button>
                                {!! $tis_standards->status=="5" ? '<span class="label label-rounded label-danger font-15" style="margin-bottom:-20px;">มอก. ยกเลิก</span>' : '' !!}
                            </h5>
                        </div>

                        <div id="collapse-{!! $tis_standards_id !!}" class="collapse in" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="col-sm-12 col-md-12">

                                    <input type="hidden" class="scope_input_std" value="{!! $tis_standards_id !!}" data-lab_id="{!! $labs->id !!}" >

                                    <div class="scope_show_std_{!! $tis_standards_id !!}"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                @endif
                
            @endforeach
            
        @endisset
    </div>
</div>