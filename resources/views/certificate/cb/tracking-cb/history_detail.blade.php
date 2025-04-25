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
             
            @if(!is_null($history))
                @if(in_array($history->system,[2]))
                    @include ('certificate/cb/tracking-cb/history.system02')
                @elseif(in_array($history->system,[3,4]))
                    @include ('certificate/cb/tracking-cb/history.system03')
                @elseif(in_array($history->system,[5]))
                    @include ('certificate/cb/tracking-cb/history.system05')
                 @elseif(in_array($history->system,[6]))
                    @include ('certificate/cb/tracking-cb/history.system06')
                @elseif(in_array($history->system,[7]))
                    @include ('certificate/cb/tracking-cb/history.system07')
                @elseif(in_array($history->system,[8]))
                    @include ('certificate/cb/tracking-cb/history.system08')
                @elseif(in_array($history->system,[9]))
                    @include ('certificate/cb/tracking-cb/history.system09')
                @elseif(in_array($history->system,[10]))
                    @include ('certificate/cb/tracking-cb/history.system10')
                @elseif(in_array($history->system,[11]))
                    @include ('certificate/cb/tracking-cb/history.system11')
                    @elseif(in_array($history->system,[12]))
                    @include ('certificate/cb/tracking-cb/history.system12')
                @else  
                {{ $history->system }}
                @endif
            @endif
           
        </div>
      </div>
    </div>
</div>
