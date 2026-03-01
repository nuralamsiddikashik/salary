<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Salary System') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    @include('layouts.partials.header')

    <div style="padding:20px;">
        @yield('content')
    </div>

</body>
</html>