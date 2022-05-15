<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
</head>
<body>
    {{-- HTML element to mount the Vue application --}}
    <div id="app">
        {{-- the Vue component --}}
        <router-view></router-view>
    </div>
    {{-- including the app.js file --}}
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
</body>
</html>