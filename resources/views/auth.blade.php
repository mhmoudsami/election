<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>تسجيل الدخول</title>

        <style>
            body {
                
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="auth-app"></div>
    </body>

        <script>
            var t = "{{ app()->getLocale() }}";
        </script>
    <script src="/js/auth-app.js"></script>
</html>
