<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SBM ITB TK Low Center - Online Course</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo asset('vendor/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo asset('css/4-col-portfolio.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('css/login-form.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo asset('vendor/bootstrap3.3/js/bootstrap.js'); ?>"></script>

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" target="_blank" href="http://www.sbm.itb.ac.id/mba/jakarta"><img src="<?php echo e(URL::asset('/images/tk_low_logo.png')); ?>" alt="SBM ITB TK Low Logo" height="100" width="auto"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo e(URL::to('/')); ?>">Home <span class="sr-only">(current)</span></a>
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
                    <center><h1 class="my-4">Registration Form</h1></center>
                    <div id="warningField">
                    <?php if( count( $errors ) > 0 ): ?>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="alert alert-danger alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>!</strong> <?php echo e($error); ?>

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </div>
                <?php echo $registerForm; ?>

                <!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Warning Popup -->
    <div class="modal fade" data-keyboard="false" data-backdrop="static" id="myWarningModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content panel-warning">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myWarningModalsLabel"></h4>
                </div>
                <div class="modal-body" id="myWarningModalsBody"></div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary">OK</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
<script src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo asset('vendor/popper/popper.min.js'); ?>"></script>
<script src="<?php echo asset('vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo asset('vendor/app/userActions/userActions.js'); ?>"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script language="JavaScript">
    $( function() {
        $( "#birthDate" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });

        $("#username").focusout(function(){
            checkUsername($(this).val());
        });
    } );

    function checkUsername(value){
        if(value.length !== 0){
            xmlhttpReq.open("GET", "/checkUsernameAvailability/"+value, true);
            xmlhttpReq.send(null);
            xmlhttpReq.onreadystatechange = function () {
                if (xmlhttpReq.readyState == 4) {
                    var str = xmlhttpReq.responseText.split("&nbsp;");
                    if (str[0] == "warning"){
                        var info = '<div class="alert alert-danger alert-dismissable">';
                        info += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        info += '<strong>!</strong> You can not use '+value+' as username. Please choose another one.';
                        info += '</div>'
                        $("#warningField").html(info);
                    }else{
                        $("#warningField").html(str[0]);
                    }
                }
            }
        }
    }
</script>
</body>

</html>
