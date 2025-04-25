<div class="media m-t-0">
    <h2 class="text-dark m-t-0 m-b-0">
        {!! $book_manage->title !!}

        @can('view-'.str_slug('law-book-search'))
            <a class="btn btn-default pull-right" href="{{ url('/law/book/search') }}">
                <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
            </a>
        @endcan
    </h2>
    <hr class="m-t-10 m-b-10">
    <div class="media-body text-muted">
        <span class="media-meta pull-left">
            วันที่เผยแพร่: {!! !empty($book_manage->date_publish) ? HP::formatDateThaiFull($book_manage->date_publish) : '-' !!}&nbsp;
        </span>
        
        <span class="media-meta pull-left">
            &nbsp;|&nbsp;เข้าชม : {!! $book_manage->BookManageVisitView->count() !!}&nbsp;
        </span>
        
        <span class="media-meta pull-left">
            &nbsp;|&nbsp;ดาวน์โหลด : {!! $book_manage->BookManageVisitDownload->count() !!}&nbsp;
        </span>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-1 col-md-10">

        <div class="form-group m-0">
            <label class="control-label col-md-2 text-dark">คำอธิบาย : </label>
            <div class="col-md-7">
                <p class="form-control-static"> {!! !empty( $book_manage->description )?$book_manage->description:'-' !!} </p>
            </div>
        </div>

        <div class="form-group m-0">
            <label class="control-label col-md-2 text-dark">ดาวน์โหลไฟล์ : </label>
            <div class="col-md-7">
                @isset( $book_manage->AttachFileBookManage  )
                    @foreach ( $book_manage->AttachFileBookManage as $File )
                        <p class="form-control-static"><a href="{!! url('law/update/download-file?id='.$File->id.'&law_book_manage_id='.$book_manage->id) !!}" target="_blank"> {!! $File->filename  ?? '' !!} </a></p>
                    @endforeach
                @endisset
            </div>
        </div>

        <div class="form-group m-0">
            <label class="control-label col-md-2 text-dark">URL ที่เกี่ยวข้อง : </label>
            <div class="col-md-7">
                @if( !empty($book_manage->url) )

                    @php
                        $list_url = json_decode( $book_manage->url, true );
                    @endphp
                    
                    @if( !empty($list_url) && count($list_url) > 0 )
                        @foreach ( $list_url as $Url )
                            <p class="form-control-static"> 
                                <a href="{!! url( (!empty($Url['url'] )?$Url['url']:'') ) !!}" target="_blank">
                                    {!! (!empty($Url['url_description'] )?$Url['url_description']:'') !!}
                                </a>
                            </p>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>

        <div class="form-group m-0">
            <label class="control-label col-md-2 text-dark">Tag : </label>
            <div class="col-md-7">
                <p class="form-control-static">
                    @if( !empty($book_manage->tag) )

                        @php
                            $list_tag = json_decode( $book_manage->tag );
                        @endphp

                        @foreach ( $list_tag as $Tag )
                            <span class="label label-info m-l-5">{!! $Tag !!}</span>
                        @endforeach

                    @endif
                </p>
            </div>
        </div>

    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-offset-1 col-md-5">
        <div class="form-group col-md-12">
            <p><span class="h4 text-dark">หมวดหมู่ : </span> <span class="h4"> {!! !empty($book_manage->BookGroupName)?$book_manage->BookGroupName:null !!}</span> </p>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-5">
        <div class="form-group col-md-12">
            <p><span class="h4 text-dark">ประเภท : </span> <span class="h4"> {!! !empty($book_manage->BookTypeName)?$book_manage->BookTypeName:null !!}</span> </p>
        </div>
    </div>
    <!--/span-->
</div>

