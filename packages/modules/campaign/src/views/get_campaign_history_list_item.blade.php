@forelse($resultCampaignHistories as $history)
    <li>
        <i class="task-icon bg-c-green"></i>
        <h6>
            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $history->user->userDetail->emp_code.' - '.$history->user->email }}">
                @if(Helper::hasPermission('user.show'))
                    <a href="{{ route('user.show', base64_encode($history->user->id)) }}" title="View User Details">{{ $history->user->userDetail->full_name }}</a>
                @else
                    {{ $history->user->userDetail->full_name }}
                @endif
            </span>
            <span class="float-right text-muted">{{ date('d M, Y \a\t h:i A', strtotime($history->created_at)) }}</span>
        </h6>
        <p class="text-muted">{!! $history->action !!}</p>
    </li>
@empty
@endforelse
