<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SBM ITB TK Low Center - Online Course</title>

    <!-- Bootstrap core CSS -->
    <link href="{!! asset('vendor/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{!! asset('css/4-col-portfolio.css') !!}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Latest compiled and minified JavaScript -->
    <script src="{!! asset('vendor/bootstrap3.3/js/bootstrap.js') !!}"></script>

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" target="_blank" href="http://www.sbm.itb.ac.id/mba/jakarta"><img src="{!!URL::asset('/images/tk_low_logo.png')!!}" alt="SBM ITB TK Low Logo" height="100" width="auto"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{!!URL::to('/')!!}">Home <span class="sr-only">(current)</span></a>
            </li>
            @if(empty(session('username')))
                <li class="nav-item">
                    <a class="nav-link" href="{!!URL::to('/')!!}/login">Sign In</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{!!URL::to('/dashboard')!!}">My Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!!URL::to('/')!!}/logout">Logout</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Terms of Service</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{!!URL::to('/')!!}/help">Help</a>
            </li>
        </ul>
    </div>
</nav>