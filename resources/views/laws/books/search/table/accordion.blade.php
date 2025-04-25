{{-- <div class="row">
    <div class="col-md-12 col-sm-12">
        {!! $item->description !!}
    </div>
</div> --}}

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 text-top text-right">คำอธิบาย :</div>
        <div class="col-md-10 text-top">
            {!! !empty( $item->description )?$item->description:null !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row p-10">
    <div class="col-md-12">
        <div class="col-md-2 text-top text-right">ดาวน์โหลไฟล์ :</div>
        <div class="col-md-10 text-top">

            @isset( $item->AttachFileBookManage  )
                @foreach ( $item->AttachFileBookManage as $File )
                    <a href="{!! url('law/update/download-file?id='.$File->id.'&law_book_manage_id='.$item->id) !!}" target="_blank">
                        {!! HP::FileExtension($File->filename)  ?? '' !!}
                    </a>
                @endforeach
            @endisset

        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row p-10">
    <div class="col-md-12">
        <div class="col-md-2 text-top text-right">URL ที่เกี่ยวข้อง :</div>
        <div class="col-md-4 text-top">

            @if( !empty( $item->url )  )

                @php
                    $list_url = json_decode( $item->url, true );
                @endphp

                @foreach ( $list_url as $Url )
                    <a href="{!! url( $Url['url'] ) !!}" class="btn-link" target="_blank">
                        {!! $Url['url_description'] !!}
                    </a>
                @endforeach
            @else
                -
            @endif
            
        </div>
        <div class="col-md-6 text-top text-right">
            วันที่เผยแพร่ : {!! !empty( $item->date_publish)?HP::revertDate($item->date_publish,true):'-' !!} |
            ดาวน์โหลด : {!! $item->BookManageVisitDownload->count() !!} |
            เข้าชม : {!! $item->BookManageVisitView->count() !!} 
        </div>

    </div>
</div>
<div class="clearfix"></div>


<div class="row p-10">
    <div class="col-md-12">
        <div class="col-md-offset-2 col-md-6">
            <span class="label label-primary m-l-5 m-b-10">หมวดหมู่ : {!! !empty($item->BookGroupName)?$item->BookGroupName:null !!} </span>
            <span class="label label-primary m-l-5 m-b-10">ประเภท : {!! !empty($item->BookTypeName)?$item->BookTypeName:null !!} </span>
        </div>
        <div class="col-md-4">
            <div class="pull-right">
                <a href="{!! url('/law/book/search/'.$item->id) !!}" class="btn btn-primary btn-outline btn-rounded btn-sm">เพิ่มเติม</a>
            </div>
        </div>
    </div>
</div>
