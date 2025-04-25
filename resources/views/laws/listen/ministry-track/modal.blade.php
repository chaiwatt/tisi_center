<div class="modal fade" id="ResultModals">
    <div  class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="AssignModalLabel1">ประกาศราชกิจจานุเบกษา</h4>
            </div>
            <div class="modal-body form-horizontal">
            {!! Form::open(['url' => '/law/listen/ministry-track', 'class' => 'form-horizontal', 'files' => true]) !!}
                {{ csrf_field() }}

             @include('laws.listen.ministry-track.form.form-modal-track')

            <input type="hidden" name="listen_id"  id="listen_id" value="">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-3">

                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> บันทึก
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {!! __('ยกเลิก') !!}
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });
    });

    </script>
@endpush