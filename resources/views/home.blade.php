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
                    <h1 class="my-4">Learn from Online Course <small>SBM ITB TK Low Center</small></h1>
                    @if (session()->has('status'))
                        @if (session('status') == 'register_success')
                            <div class="alert alert-success">
                                <strong>!</strong> You have successfully registered. Please check your email to activate your account before access this Online Course.
                            </div>
                        @elseif (session('status') == 'already_verified')
                            <div class="alert alert-warning">
                                <strong>!</strong> You already been verified. No action was taken.
                            </div>
                        @elseif (session('status') == 'reset_instruction')
                            <div class="alert alert-warning">
                                <strong>!</strong> The instruction has been sent to your registered email. Please follow the instruction to reset your password.
                            </div>
                        @elseif (session('status') == 'link_expired')
                            <div class="alert alert-danger">
                                <strong>!</strong> Reset password link has been expired or not right.
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <strong>!</strong> Something went wrong. No action was taken.
                            </div>
                        @endif
                    @endif
                <!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    @foreach ($available_course_data as $index =>$available_courses)
            <div class="col-lg-4 col-md-5 col-sm-6 portfolio-item">
                <div class="card h-100">
                    <a href="{!!URL::to('/').'/course/'.$available_courses->CourseCode.'-'.$available_courses->idCoursesClass.'/about'!!}"><img class="card-img-top" src="<?php if ($available_courses->ThumbnailURLAddress == "") { echo 'http://placehold.it/700x400'; } else { echo $available_courses->ThumbnailURLAddress; } ?>" alt=""></a>
                    <div class="card-body">
                        <h4 class="card-title"><a href="{!!URL::to('/').'/course/'.$available_courses->CourseCode.'-'.$available_courses->idCoursesClass.'/about'!!}">{!!$available_courses->nama_mata_kuliah_id!!}</a></h4>

                        <p class="card-text">
                            <?php
                            // strip tags to avoid breaking any html
                            echo strlen($available_courses->CourseDescription) >= 100 ?
                                substr($available_courses->CourseDescription, 0, 90) . ' <a href="'.URL::to('/').'/course/'.$available_courses->CourseCode.'-'.$available_courses->idCoursesClass.'/about">[Read more]</a>' :
                                $available_courses->CourseDescription;
                            ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <center>
                            <p class="card-text">
                                Started On {!!date('F Y', strtotime($available_courses->OpenedStart))!!}
                                <br />
                                {!!strtoupper($available_courses->OnlineProgramName)!!}
                                <br />
                                <b>{!!$userInterface->showCourseClassPrice($available_courses->idCoursesClass)!!}</b>
                            </p>
                        </center>
                    </div>
                </div>
            </div>
    @endforeach
    </div>
    <!-- /.row -->

    <!-- Pagination -->
    <ul class="pagination justify-content-center">
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">1</a></li>
        <li class="page-item">
            <a class="page-link" href="#">2</a></li>
        <li class="page-item">
            <a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>

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

</body>

</html>
