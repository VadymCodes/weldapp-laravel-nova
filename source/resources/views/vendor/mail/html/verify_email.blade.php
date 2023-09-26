@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ config('app.url') }}/images/LogoWithTextAlpha.svg" class="logo" alt="Weld Logo">
@endcomponent
@endslot

{{-- Body --}}
<p>Please verify your email</p>
<p>Here is a link you can click</p>
<p><a href="{{$url}}">{{ $url }}</a></p>
@component('mail::button', ['url' => $url])
 Verify Email
@endcomponent

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} 'Weld technologies LTD '. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent