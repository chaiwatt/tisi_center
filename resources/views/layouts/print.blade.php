<!DOCTYPE html>
<html>

    <head>
        <meta  charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @stack('styles')

    </head>
    <body>
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-body">
                    @yield('content')
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
