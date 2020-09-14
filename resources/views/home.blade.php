{!! $header !!}

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

{!! $footer !!}
