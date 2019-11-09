<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Wayne Lifter - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/app.css">

</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="#">Wayne Lifter</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('welcome')}}">Welcome</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('config.index')}}">Config</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('report.full')}}">Report</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-5">
    <div class="row">
        @yield('content')
    </div>
</div>

<script src="/js/app.js"></script>
</body>
</html>
