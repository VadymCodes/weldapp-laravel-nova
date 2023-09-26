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
</head>
<body>
    <div name="termly-embed" data-id="d267420e-06d2-425c-ab67-02ad2d30d650" data-type="iframe"></div>
    <script type="text/javascript">(function(d, s, id) {
      var js, tjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "https://app.termly.io/embed-policy.min.js";
      tjs.parentNode.insertBefore(js, tjs);
    }(document, 'script', 'termly-jssdk'));</script>
</body>
</html>