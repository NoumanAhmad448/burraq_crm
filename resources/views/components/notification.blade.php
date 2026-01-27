@php
    use App\Classes\Notifications\UnreadNotificationCount;
    use App\Models\Notification;
    $unreadCount = UnreadNotificationCount::get();
    $notifications =   Notification::latest()->take(20)->get();

@endphp

<li class="nav-item dropdown">
    <a class="text-white nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <i class="fa fa-bell"></i>

        @if ($unreadCount > 0)
            <span class="badge badge-danger position-absolute" style="top: 0; right: 0;">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-right p-0" style="width: 350px; max-height: 400px; overflow-y: auto;">

        <div class="dropdown-header font-weight-bold">
            Notifications
        </div>

        @forelse($notifications ?? [] as $n)
            <a href="{{ route($n->route['route'], $n->route['route_keys'] ?? []) }}"
                    class="dropdown-item notification-link @if($n->readForUser(auth()->user()->id)) text-muted @endif)"
                    data-id="{{ $n->id }}">

                <strong>{{ ucfirst(str_replace('_', ' ', $n->type)) }}</strong>
                <strong class="ml-5"> {{ showWebPageDate($n->created_at) }}</strong>
                <br>
                <span class="text-muted">
                    {{ $n->count }} students overdue
                </span>
            </a>
            <hr/>
        @empty
            <div class="dropdown-item text-muted text-center">
                No notifications
            </div>
        @endforelse
    </div>
</li>


<script>
document.addEventListener('click', function (e) {
    const el = e.target.closest('.notification-link');
    if (!el) return;

    const notificationId = el.dataset.id;
    // alert(notificationId)

    if (!notificationId) return;

    // Fire & forget
    navigator.sendBeacon(
        "{{ route('notifications.mark-read') }}",
        new URLSearchParams({
            notification_id: notificationId,
            _token: "{{ csrf_token() }}"
        })
    );
});
</script>
