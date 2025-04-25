<!-- /.modal-dialog -->
<div id="ModalCalender" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ปฏิทินวันหยุด</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div id="calendar"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@php

    $PublicHoliday =  App\Models\Basic\Holiday::whereNotNull('holiday_date')
                                                ->select( DB::raw('title'), DB::raw('holiday_date AS start') )
                                                ->get();

    $Holiday = json_encode($PublicHoliday);

@endphp

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            
            var Holiday = jQuery.parseJSON('{!! $Holiday !!}');
                console.log( Holiday );

                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'th',
                    timeZone: 'Asia/Bangkok',
                    // themeSystem: 'bootstrap4',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    },
                    firstDay: 0,
                    editable: false,
                    events: Holiday,
                    contentHeight: 600

                });

                calendar.render();
            
            $('#ModalCalender').on('show.bs.modal', function (e) {
                calendar.render();
            });



        });

    </script>
@endpush