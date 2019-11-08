<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>App Name - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/app.css">

</head>
<body>
@section('sidebar')

@show

<div class="container">
    @yield('content')
</div>
<script src="/js/app.js"/>
</body>
</html>
