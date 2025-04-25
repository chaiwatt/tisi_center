
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
     <!-- ===== Parsley js ===== -->
    <link href="{{asset('plugins/components/parsleyjs/parsley.css?20200630')}}" rel="stylesheet" />
@endpush

{!! Form::open(['url' => 'certificate/tracking-labs/update_report/'.$certi->id,    'method' => 'POST', 'class' => 'form-horizontal  ','id'=>'form_report', 'files' => true]) !!}

<div class="modal fade text-left" id="exampleModalExport" tabindex="-1" role="dialog" aria-labelledby="addBrand">
          <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel1">แนบท้าย
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </h4>
                  </div>
 <div class="modal-body"> 

<div class="row">
    <div class="col-md-12">
 

    </div> 
</div>

</div>
 
<input type="hidden" name="previousUrl" id="previousUrl" value="{{  app('url')->previous()  }}">
<div class="modal-footer">
    <button type="submit" class="btn btn-success" onclick="submit_form();return false">ยืนยัน</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
</div> 
 


        </div>
    </div>
</div>
{!! Form::close() !!}
      
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
     <!-- ===== PARSLEY JS Validation ===== -->
     <script src="{{asset('plugins/components/parsleyjs/parsley.min.js')}}"></script>
     <script src="{{asset('plugins/components/parsleyjs/language/th.js')}}"></script>
     <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
     
    @endpush
    