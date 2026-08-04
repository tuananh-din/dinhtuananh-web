@php
    $phone = trim((string) data_get($contact, 'tel', ''));
    $zaloPhone = preg_replace('/\D+/', '', $phone);
    $facebook = trim((string) data_get($contact, 'facebook', ''));
    $facebookHref = \Illuminate\Support\Str::startsWith($facebook, ['http://', 'https://'])
        ? $facebook
        : ($facebook ? 'https://'.$facebook : '');
    $facebookHost = strtolower((string) parse_url($facebookHref, PHP_URL_HOST));
    $facebookPath = trim((string) parse_url($facebookHref, PHP_URL_PATH), '/');
    $facebookUsername = explode('/', $facebookPath)[0] ?? '';
    $messengerUsername = in_array($facebookHost, ['facebook.com', 'www.facebook.com', 'm.facebook.com', 'fb.com', 'www.fb.com'], true)
        && $facebookUsername
        && !in_array(strtolower($facebookUsername), ['profile.php', 'share', 'sharer', 'dialog'], true)
            ? $facebookUsername
            : '';
    $messengerHref = $messengerUsername ? 'https://m.me/'.$messengerUsername : $facebookHref;
@endphp

@if($phone || $zaloPhone || $messengerHref)
    <aside class="floating-contact" aria-label="Liên hệ nhanh">
        @if($phone)
            <a class="floating-contact__button floating-contact__button--phone" href="tel:{{ $phone }}" aria-label="Gọi điện {{ $phone }}">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <span class="visually-hidden">Gọi điện</span>
            </a>
        @endif

        @if($zaloPhone)
            <a class="floating-contact__button floating-contact__button--zalo" href="https://zalo.me/{{ $zaloPhone }}" target="_blank" rel="noopener noreferrer" aria-label="Nhắn Zalo">
                <span class="floating-contact__zalo" aria-hidden="true">Z</span>
                <span class="visually-hidden">Nhắn Zalo</span>
            </a>
        @endif

        @if($messengerHref)
            <a class="floating-contact__button floating-contact__button--messenger" href="{{ $messengerHref }}" target="_blank" rel="noopener noreferrer" aria-label="Nhắn Messenger">
                <i class="fa-brands fa-facebook-messenger" aria-hidden="true"></i>
                <span class="visually-hidden">Nhắn Messenger</span>
            </a>
        @endif
    </aside>
@endif
