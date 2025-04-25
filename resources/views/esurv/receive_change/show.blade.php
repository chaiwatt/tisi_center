@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div id="box_readonly">
        @include ('esurv.receive_change.form')
       </div>
    
    
    </div>
@endsection
@push('js') 
    <script>
        jQuery(document).ready(function() {
           // จัดการข้อมูลในกล่องคำขอ false
            $('#box_readonly').find('button[type="submit"]').remove();
            $('#box_readonly').find('.icon-close').parent().remove();
            $('#box_readonly').find('.fa-copy').parent().remove();
            $('#box_readonly').find('.button_hide').hide();
            $('#box_readonly').find('input').prop('disabled', true);
            $('#box_readonly').find('input').prop('disabled', true);
            $('#box_readonly').find('textarea').prop('disabled', true); 
             $('#box_readonly').find('select').prop('disabled', true);
             $('#box_readonly').find('.bootstrap-tagsinput').prop('disabled', true);
             $('#box_readonly').find('span.tag').children('span[data-role="remove"]').remove();
             $('#box_readonly').find('button').prop('disabled', true);
             $('#box_readonly').find('button').remove();
             $('#box_readonly').find('button').remove();
            $('body').on('click', '.attach-remove', function() {
                $(this).parent().parent().parent().find('input[type=hidden]').val('');
                $(this).parent().remove();
            });
        });
    </script>
     
@endpush