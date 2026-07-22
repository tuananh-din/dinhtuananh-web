@extends('layouts.master')
@section('page_title', 'Liên hệ | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Liên hệ | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
<style>
    .contact-shell {
        padding: 110px 0;
    }

    .contact-card {
        max-width: 760px;
        margin: 0 auto;
        background: #121212;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 28px;
    }

    .contact-card .form-clt {
        margin-bottom: 12px;
    }

    .contact-card input,
    .contact-card textarea {
        width: 100%;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: #0e0e0e;
        color: #fff;
        padding: 11px 12px;
    }

    .contact-card textarea {
        min-height: 120px;
        resize: vertical;
    }

    .contact-note {
        color: #bdbdbd;
        margin-top: 10px;
    }
</style>

<section class="contact-shell fix">
    <div class="container">
        <div class="contact-card">
            <div class="section-title">
                <h6>Li&#234;n h&#7879;</h6>
                <h2>&#272;&#259;ng k&#253; t&#432; v&#7845;n nhanh</h2>
            </div>

            @if(session('success'))
                <p style="color:#7af2a0;">{{ session('success') }}</p>
            @endif
            @if($errors->any())
                <p style="color:#ff8f8f;">{{ $errors->first() }}</p>
            @endif

            <form action="{{ route('lead.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="source_page" value="contact_page">
                <div class="form-clt">
                    <input type="text" name="name" placeholder="H&#7885; v&#224; t&#234;n (*)" value="{{ old('name') }}" required>
                </div>
                <div class="form-clt">
                    <input type="text" name="phone" placeholder="S&#7889; &#273;i&#7879;n tho&#7841;i (*)" value="{{ old('phone') }}" required>
                </div>
                <div class="form-clt">
                    <input type="email" name="email" placeholder="Email (kh&#244;ng b&#7855;t bu&#7897;c)" value="{{ old('email') }}">
                </div>
                <div class="form-clt">
                    <textarea name="message" placeholder="Nhu c&#7847;u c&#7911;a b&#7841;n (kh&#244;ng b&#7855;t bu&#7897;c)">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="theme-btn">&#272;&#259;ng k&#253; t&#432; v&#7845;n <i class="fa-solid fa-arrow-up-right"></i></button>
            </form>

            <p class="contact-note">Ch&#250;ng t&#244;i s&#7869; li&#234;n h&#7879; l&#7841;i trong gi&#7901; l&#224;m vi&#7879;c.</p>
        </div>
    </div>
</section>
@endsection
