@php
    $noticeTypes = [
        'success' => ['icon' => 'fa-circle-check', 'title' => 'Hoàn tất'],
        'info' => ['icon' => 'fa-circle-info', 'title' => 'Thông tin'],
        'warning' => ['icon' => 'fa-triangle-exclamation', 'title' => 'Lưu ý'],
        'error' => ['icon' => 'fa-circle-xmark', 'title' => 'Cần kiểm tra lại'],
        'neutral' => ['icon' => 'fa-bell', 'title' => 'Thông báo'],
    ];
    $noticeType = isset($type) && array_key_exists($type, $noticeTypes) ? $type : 'neutral';
    $noticeMessages = isset($messages) ? (array) $messages : (isset($message) ? [$message] : []);
    $noticeMessages = array_values(array_filter($noticeMessages, static fn ($noticeMessage) => is_scalar($noticeMessage) && trim((string) $noticeMessage) !== ''));
    $noticeDismissible = in_array($noticeType, ['success', 'info', 'neutral'], true) && ($dismissible ?? true);
    $noticeTitle = $title ?? $noticeTypes[$noticeType]['title'];
    $noticeId = $id ?? null;
    $noticeHidden = $hidden ?? false;
    $hasAction = isset($actionLabel, $actionUrl) && $actionLabel !== '' && $actionUrl !== '';
    $isUrgent = in_array($noticeType, ['warning', 'error'], true);
@endphp

@if(count($noticeMessages))
    <section
        @if($noticeId) id="{{ $noticeId }}" @endif
        class="site-notice site-notice--{{ $noticeType }}"
        role="{{ $isUrgent ? 'alert' : 'status' }}"
        aria-live="{{ $isUrgent ? 'assertive' : 'polite' }}"
        aria-atomic="true"
        data-notice
        data-notice-dismissible="{{ $noticeDismissible ? 'true' : 'false' }}"
        @if($noticeHidden) hidden @endif
    >
        <span class="site-notice__icon" aria-hidden="true"><i class="fa-solid {{ $noticeTypes[$noticeType]['icon'] }}"></i></span>
        <div class="site-notice__content">
            <p class="site-notice__title">{{ $noticeTitle }}</p>
            @if(count($noticeMessages) === 1)
                <p class="site-notice__message">{{ $noticeMessages[0] }}</p>
            @else
                <ul class="site-notice__messages">
                    @foreach($noticeMessages as $noticeMessage)
                        <li>{{ $noticeMessage }}</li>
                    @endforeach
                </ul>
            @endif
            @if($hasAction)
                <a class="site-notice__action" href="{{ $actionUrl }}">{{ $actionLabel }}</a>
            @endif
        </div>
        @if($noticeDismissible)
            <button class="site-notice__dismiss" type="button" data-notice-dismiss aria-label="Đóng thông báo">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        @endif
    </section>
@endif
