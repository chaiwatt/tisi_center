<!-- Modal เลข 3 -->
<div class="modal fade text-left" id="TakeAction{{$id}}" tabindex="-1" role="dialog" aria-labelledby="addBrand">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"> อยู่ระหว่างดำเนินการ
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
            </div>
<div class="modal-body">
    <legend><h3>คณะผู้ตรวจประเมิน </h3></legend>
    @if(count($auditors) > 0) 
    <div class="row">
        <div class="col-md-12">
         <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center text-white" width="2%">ลำดับ</th>
                    <th class="text-center text-white" width="20%">วันที่/เวลาบันทึก</th>
                    <th class="text-center text-white" width="40%">คณะผู้ตรวจประเมิน</th>
                    <th class="text-center text-white" width="38%">สถานะ</th>
                </tr>
            </thead>
             <tbody>
                @foreach($auditors as $key => $item)
                <tr>
                    <td class="text-center">{{$key+1}}</td>
                    <td> {{HP::DateTimeThai($item->created_at) ?? '-'}} </td>
                    <td>{{ $item->auditor ?? null }}</td>
                    <td>{{ $item->CertiCBAuditorsStepTo->title ?? null }}</td>
                </tr>
                 @endforeach
            </tbody>
        </table>
    </div>
</div>
    @endif
</div>
 
        </div>
    </div>
</div>

