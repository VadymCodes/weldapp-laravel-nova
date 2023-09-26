<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="application-name" content="{{ config('app.name', 'Laravel') }}">

    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <link rel="shortcut icon" href="{{ url('favicon.ico') }}">

    <link rel="stylesheet" href="{{ mix('css/main.css') }}">

    <base href="{{ url('/') }}">
</head>
<body>
    <div id="app">
        <!-- <div id="vue-loading">{{ __('strings.loading') }}...</div> -->
    </div>

    <script>
      var baseUrl = '{{ url('/api') }}';
    </script>
    <script src="{{ mix('js/main.js') }}"></script>
    <!-- <script
        type="text/javascript"
        src="https://app.termly.io/embed.min.js"
        data-auto-block="on"
        data-website-uuid="738d2d83-4c35-4f20-aacf-6c3255d3b4c1"
    ></script> -->
</body>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', "{{config('analytics.facebook_tracking_id')}}");
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{config('analytics.facebook_tracking_id')}}&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</html>