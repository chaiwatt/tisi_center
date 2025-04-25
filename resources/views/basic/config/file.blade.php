@php
    $list_file = App\AttachFile::where( 'ref_table' , (new App\Models\Basic\Config )->getTable())->orderby('created_at','desc')->get();
@endphp

<div class="table-responsive">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th width="20%">ไฟล์</th>
                <th width="65%">url</th>
                <th width="15%">ลบ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $list_file  as $flie )
                @php
                    $info =   pathinfo( $flie->new_filename , PATHINFO_EXTENSION );
                @endphp

                <tr>
                    <td>
                        @if( $info == 'png' ||  $info == 'jpg'  ||  $info == 'jpeg'  ||  $info == 'gif'   )
                            <img src="{!! HP::getFileStorage($flie->url) !!}" width="30">
                        @else
                            {!! HP::FileExtension($flie->new_filename)  ?? '' !!}
                        @endif
                    </td>
                    <td><button class="btn btn-link btn_copy" type="button">{!! HP::getFileStorage($flie->url) !!}</button></td>
                    <td>
                        <button class="btn btn-danger btn-xs btn_delete_file" value="{!! $flie->id !!}" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    </td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
</div>