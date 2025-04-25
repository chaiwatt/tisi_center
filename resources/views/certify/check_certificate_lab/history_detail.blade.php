

  <!-- Modal -->
  <div class="modal fade" id="HistoryModal{{$history->id}}" tabindex="-1" role="dialog" aria-labelledby="ReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" > 
                {{ $history->DataSystem ?? null}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
           </h4>
        </div>
        <div class="modal-body text-left">
            <div class="container-fluid">
                {{-- @php
                    dd($history->system);
                @endphp --}}
                @if(!is_null($history))
                @if($history->system == 1)
                    @include ('certify/check_certificate_lab.history.system01')
                @elseif($history->system == 2)
                    @include ('certify/check_certificate_lab.history.system02')
                @elseif($history->system == 3)
                    @include ('certify/check_certificate_lab.history.system03')
                @elseif($history->system == 4)
                    @include ('certify/check_certificate_lab.history.system04')
                @elseif($history->system == 5)
                    @include ('certify/check_certificate_lab.history.system05')
                @elseif($history->system == 6)
                    @include ('certify/check_certificate_lab.history.system06')
                @elseif($history->system == 8 || $history->system == 9 || $history->system == 10)
                    @include ('certify/check_certificate_lab.history.system08')
                @elseif($history->system == 11)
                {{-- @php
                    dd($history);
                @endphp --}}
                    @include ('certify/check_certificate_lab.history.system11')
                @elseif($history->system == 12)
                    @include ('certify/check_certificate_lab.history.system12')
                @endif
            @else 
            @endif
            </div>
        </div>
      </div>
    </div>
</div>

