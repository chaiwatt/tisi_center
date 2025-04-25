
<div class="clearfix"></div>

<table class="table table-bordered" id="myTableSummary">
    <thead>
        <tr>
            <th class="text-center"  rowspan="2" width="2%"> #</th>
            <th class="text-center"  rowspan="2" width="8%">เลขที่อ้างอิง/วันที่ประกาศ</th>
            <th class="text-center"  rowspan="2" width="20%">ชื่อเรื่องประกาศ</th>
            <th class="text-center"  rowspan="2" width="12%">สถานะ</th>     
            <th class="text-center"  colspan="4" width="30%">ความเห็น</th>
            <th class="text-center"  rowspan="2" width="7%">รวม</th>
        </tr>
        <tr>
            <th class="text-center" width="10%">เห็นชอบให้บังคับ<br>ตามร่างกฎกระกระทรวงฯ ทุกประการ</th>
            <th class="text-center" width="10%">ไม่เห็นชอบให้บังคับ<br>ตามร่างกฎกระกระทรวงฯ</th>
            <th class="text-center" width="10%">เห็นชอบ<br>กับการขยายระยะเวลา</th>
            <th class="text-center" width="10%">ไม่เห็นชอบ<br>กับการขยายระยะเวลา</th>
        </tr>                                        
    </thead>
    <tbody>

    </tbody>
</table>

@push('js')

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#myTableSummary').DataTable({
                processing: true,
                serverSide: false,
                searching: false,
                autoWidth: false,
                lengthChange: false,
                info: false,
                paging: false,
                ajax: {
                    url: '{!! url('/law/listen/ministry-track/data_list_ministry_summary') !!}',
                    data: function (d) {
                        d.ministry_id = '{{$lawlistministry->id}}';
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status' },
                    { data: 'comment1', name: 'comment1' },
                    { data: 'comment2', name: 'comment2' },
                    { data: 'comment3', name: 'comment3' },
                    { data: 'comment4', name: 'comment4' },
                    { data: 'comment_amonut', name: 'comment_amonut' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1,-2,-3,-4,-5] }

                ],
                fnDrawCallback: function() {
                }
            });
            
    });
          
</script>


@endpush