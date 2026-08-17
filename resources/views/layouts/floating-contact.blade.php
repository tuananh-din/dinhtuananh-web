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
                <svg class="floating-contact__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M12.49 10.2722v-.4496h1.3467v6.3218h-.7704a.576.576 0 01-.5763-.5729l-.0006.0005a3.273 3.273 0 01-1.9372.6321c-1.8138 0-3.2844-1.4697-3.2844-3.2823 0-1.8125 1.4706-3.2822 3.2844-3.2822a3.273 3.273 0 011.9372.6321l.0006.0005zM6.9188 7.7896v.205c0 .3823-.051.6944-.2995 1.0605l-.03.0343c-.0542.0615-.1815.206-.2421.2843L2.024 14.8h4.8948v.7682a.5764.5764 0 01-.5767.5761H0v-.3622c0-.4436.1102-.6414.2495-.8476L4.8582 9.23H.1922V7.7896h6.7266zm8.5513 8.3548a.4805.4805 0 01-.4803-.4798v-7.875h1.4416v8.3548H15.47zM20.6934 9.6C22.52 9.6 24 11.0807 24 12.9044c0 1.8252-1.4801 3.306-3.3066 3.306-1.8264 0-3.3066-1.4808-3.3066-3.306 0-1.8237 1.4802-3.3044 3.3066-3.3044zm-10.1412 5.253c1.0675 0 1.9324-.8645 1.9324-1.9312 0-1.065-.865-1.9295-1.9324-1.9295s-1.9324.8644-1.9324 1.9295c0 1.0667.865 1.9312 1.9324 1.9312zm10.1412-.0033c1.0737 0 1.945-.8707 1.945-1.9453 0-1.073-.8713-1.9436-1.945-1.9436-1.0753 0-1.945.8706-1.945 1.9436 0 1.0746.8697 1.9453 1.945 1.9453z"/>
                </svg>
                <span class="visually-hidden">Nhắn Zalo</span>
            </a>
        @endif

        @if($messengerHref)
            <a class="floating-contact__button floating-contact__button--messenger" href="{{ $messengerHref }}" target="_blank" rel="noopener noreferrer" aria-label="Nhắn Messenger">
                <svg class="floating-contact__icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M12 0C5.24 0 0 4.952 0 11.64c0 3.499 1.434 6.521 3.769 8.61a.96.96 0 01.323.683l.065 2.135a.96.96 0 0 0 1.347.85l2.381-1.053a.96.96 0 0 1 .641-.046A13 13 0 0 0 12 23.28c6.76 0 12-4.952 12-11.64S18.76 0 12 0m6.806 7.44c.522-.03.971.567.63 1.094l-4.178 6.457a.707.707 0 0 1-.977.208l-3.87-2.504a.44.44 0 0 0-.49.007l-4.363 3.01c-.637.438-1.415-.317-.995-.966l4.179-6.457a.706.706 0 0 1 .977-.21l3.87 2.505c.15.097.344.094.491-.007l4.362-3.008a.7.7 0 0 1 .364-.13"/>
                </svg>
                <span class="visually-hidden">Nhắn Messenger</span>
            </a>
        @endif
    </aside>
@endif
