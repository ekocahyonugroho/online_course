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
    <link href="{!! asset('css/login-form.css') !!}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Latest compiled and minified JavaScript -->
    <script src="{!! asset('vendor/bootstrap3.3/js/bootstrap.js') !!}"></script>

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" target="_blank" href="http://www.sbm.itb.ac.id/mba/jakarta"><img src="{{URL::asset('/images/tk_low_logo.png')}}" alt="SBM ITB TK Low Logo" height="100" width="auto"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{URL::to('/')}}">Home <span class="sr-only">(current)</span></a>
            </li>
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
                <a class="nav-link" href="#">Help</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Page Content -->
<div class="container" style="padding-top: 5%">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @if ( !empty($loginForm) )
                        <center><h1 class="my-4">Sign In</h1></center>
                        @if( count( $errors ) > 0 )
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif

                        @if (session()->has('status'))
                            @if (session('status') == 'verified')
                                <div class="alert alert-success">
                                    <strong>!</strong> You have successfully verified. Please login using your account to access this Online Course.
                                </div>
                            @elseif (session('status') == 'reset_password_success')
                                <div class="alert alert-success">
                                    <strong>!</strong> Your password has been reset successfully. You may login using your new password.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <strong>!</strong> {!! session('status') !!}.
                                </div>
                            @endif
                        @endif

                        {!! $loginForm !!}
                    @endif

                    @if (!empty($resetForm))
                            <center><h1 class="my-4">Reset Password</h1></center>
                            @if( count( $errors ) > 0 )
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (session()->has('status'))
                                @if (session('status') == 'user_empty')
                                    <div class="alert alert-danger">
                                        <strong>!</strong> Username does not exist.
                                    </div>
                                @elseif (session('status') == 'unmatch')
                                    <div class="alert alert-danger">
                                        <strong>!</strong> Username and Email do not match.
                                    </div>
                                @endif
                            @endif

                            {!! $resetForm !!}
                    @endif
                    <!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; SBM ITB Jakarta Campus 2017</p>
        <p class="m-0 text-center text-white">Programmed By <a href="mailto:eko.cahyo@sbm-itb.ac.id">Eko Cahyo Nugroho</a></p>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="{!! asset('vendor/jquery/jquery.min.js') !!}"></script>
<script src="{!! asset('vendor/popper/popper.min.js') !!}"></script>
<script src="{!! asset('vendor/bootstrap/js/bootstrap.min.js') !!}"></script>

@if (session()->has('status'))
    @if (session('status') == 'wrong_account')
        <script language="JavaScript">
            alert("Wrong login credentials! Please try again!");
        </script>
    @endif
@endif
</body>

</html>
