@php
    $std_lists  = App\Models\Section5\IbcbsScopeTis::with(['scope_tis_std','scope_detail'])->where('ibcb_code', $ibcb->ibcb_code )->get();
@endphp

<div class="col-md-12 col-sm-12">
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก สาขา, รายสาขา, เลข มอก., ชื่อ มอก.']); !!}
            </div><!-- /form-group -->
        </div><!-- /.col-lg-4 -->

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::select('filter_branch_group', App\Models\Basic\BranchGroup::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'id'=> 'filter_branch_group', 'placeholder'=>'-เลือกสาขา-']); !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::select('filter_branch', App\Models\Basic\Branch::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'id'=> 'filter_branch', 'placeholder'=>'-เลือกรายสาขา-']); !!}
            </div>
        </div>

    </div>
</div>
<div class="col-md-12 col-sm-12">
  
    <table width="100%" class="table table-bordered table-striped" id="MyTable-std">
    
        <thead>
            <tr>
                <th class="text-center" width="1%">ลำดับ</th>
                <th class="text-center" width="45%">ผลิตภัณฑ์อุตสาหกรรม</th>
                <th class="text-center" width="33%">มาตรฐาน เลขที่ มอก.</th>
                <th class="text-center">รายสาขา</th>
                <th class="text-center">สาขา</th>
                <th class="text-center" width="20%">วันที่หมดอายุ</th>
            </tr>
        </thead>

        <tbody>

        </tbody>

    </table>

</div>

@push('js')

    <script>
        jQuery(document).ready(function() {
            var table_std = $('#MyTable-std').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/ibcb/data_scope_std') !!}',
                    data: function (d) {

                        d.id = '{!! $ibcb->id !!}';
                        d.filter_search = $('#filter_search').val();
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch       = $('#filter_branch').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tis_title', name: 'tis_title' },
                    { data: 'tis_tisno', name: 'tis_tisno' },
                    { data: 'bs_branch', name: 'bs_branch' },
                    { data: 'bs_branch_group', name: 'bs_branch_group' },
                    { data: 'end_date', name: 'end_date' },
                    
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1] },
                    { className: "text-center", visible: false, targets: [3,4] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();

                    var bs_branch_group = null;
                    api.column(3, {
                        page: 'current'
                    }).data().each(function(branch_group, i) {
                        if (bs_branch_group !== branch_group) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="11">สาขา' + branch_group + '</td></tr>'
                            );

                            bs_branch_group = branch_group;
                        }
                    });

                    var bs_branch = null;
                    api.column(4, {
                        page: 'current'
                    }).data().each(function(branch, i) {
                        if (bs_branch !== branch) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="11">&nbsp;&nbsp;รายสาขา' + branch + '</td></tr>'
                            );
                            bs_branch = branch;
                        }
                    });
                }
            });

            
            $('#filter_branch_group').change(function (e) {

                $('#filter_branch').html('<option value=""> -เลือกรายสาขา- </option>');
                var value = ( $(this).val() != "" )?$(this).val():'ALL';
                if(value){
                    $.ajax({
                        url: "{!! url('/section5/get-branch-data') !!}" + "/" + value
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_branch').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    });
                }

            });

            $('#filter_search').keyup(function (e) { 
                table_std.draw();
            });

            $('#filter_branch_group,#bs_branch_group').change(function (e) { 
                table_std.draw();
            });

        });
    </script>

@endpush