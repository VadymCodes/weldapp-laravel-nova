@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ config('app.url') }}/images/LogoWithTextAlpha.svg" class="logo" alt="Weld Logo">
@endcomponent
@endslot

{{-- Body --}}
<p>Your account is created successfully!</p>
<p>Great News! Your account is now up and running.</p>
<!-- @component('mail::button', ['url' => config('app.url')])
 Find, Book, Buy!
@endcomponent -->

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
