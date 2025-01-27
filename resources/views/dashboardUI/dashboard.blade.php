<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 25/09/17
 * Time: 14:08
 */
?>
@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$idAuthority = $db->getAccountDataByIdMember(session('idMember'))->first()->idAuthority;

$dataMember = $db->getFullMemberData(session('idMember'), $idAuthority)->first();

$Fullname = "";

switch ($idAuthority) {
    case "1":
        $Fullname = $dataMember->name;
        break;
    case "2":
        $Fullname = $dataMember->name;
        break;
    case "3":
        $Fullname = $dataMember->nama_dosen;
        break;
    case "4":
        $Fullname = $dataMember->nama;
        break;
    case "5":
        $Fullname = $dataMember->nameFirst." ".$dataMember->nameLast;
        break;
    default:
        $Fullname = "NO NAME";
        break;
}
?>
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Online Course - SBM ITB TK Low Center</title>
    <!-- Bootstrap core CSS-->
    <link href="{!! asset('dashboard_vendor/vendor/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{!! asset('dashboard_vendor/vendor/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="{!! asset('dashboard_vendor/vendor/datatables/dataTables.bootstrap4.css') !!}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{!! asset('dashboard_vendor/css/sb-admin.css') !!}" rel="stylesheet">

    <link href="{!! asset('plugin/datetimepicker/css/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet" media="screen">

    <link href="{!! asset('plugin/bootsrap_switch/dist/css/bootstrap3/bootstrap-switch.css') !!}" rel="stylesheet" media="screen">

    <link href="{!! asset('plugin/pdfviewer/jquery.touchPDF.css') !!}" rel="stylesheet" media="screen" />

    <link href="{!! asset('css/embed_youtube.css') !!}" rel="stylesheet" />

    <!-- Bootstrap core JavaScript-->
    <script src="{!! asset('dashboard_vendor/vendor/jquery/jquery.min.js') !!}"></script>

    <script src="{!! asset('dashboard_vendor/vendor/popper/popper.min.js') !!}"></script>
    <script src="{!! asset('dashboard_vendor/vendor/bootstrap/js/bootstrap.js') !!}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{!! asset('dashboard_vendor/vendor/jquery-easing/jquery.easing.min.js') !!}"></script>

    <script type="text/javascript" src="{!! asset('plugin/datetimepicker/js/bootstrap-datetimepicker.js') !!}" charset="UTF-8"></script>

    <script type="text/javascript" src="{!! asset('plugin/datetimepicker/js/locales/bootstrap-datetimepicker.id.js') !!}" charset="UTF-8"></script>

    <script src="{!! asset('plugin/ckeditor/ckeditor.js') !!}"></script>

    <script src="{!! asset('plugin/bootsrap_switch/dist/js/bootstrap-switch.js') !!}"></script>

    <!-- PDF Touch. Documentation : https://www.jqueryscript.net/other/Touch-enabled-jQuery-Web-PDF-Viewer-TouchPDF.html -->
    <script type="text/javascript" src="{!! asset('plugin/pdfviewer/pdf.compatibility.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugin/pdfviewer/pdf.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugin/pdfviewer/jquery.touchSwipe.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugin/pdfviewer/jquery.touchPDF.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugin/pdfviewer/jquery.panzoom.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugin/pdfviewer/jquery.mousewheel.js') !!}"></script>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{URL::to('/')}}">Online Course - SBM ITB TK Low Center</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{URL::to('/')}}/dashboard">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="MyAccount">
                <a class="nav-link" href="{{URL::to('/')}}/member/MyAccount">
                    <i class="fa fa-fw fa-link"></i>
                    <span class="nav-link-text">My Account</span>
                </a>
            </li>
            {!! $leftMenuBar !!}
        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <!--
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-envelope"></i>
                    <span class="d-lg-none">Messages
              <span class="badge badge-pill badge-primary">12 New</span>
            </span>
                    <span class="indicator text-primary d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
                </a>
                <div class="dropdown-menu" aria-labelledby="messagesDropdown">
                    <h6 class="dropdown-header">New Messages:</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <strong>David Miller</strong>
                        <span class="small float-right text-muted">11:21 AM</span>
                        <div class="dropdown-message small">Hey there! This new version of SB Admin is pretty awesome! These messages clip off when they reach the end of the box so they don't overflow over to the sides!</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <strong>Jane Smith</strong>
                        <span class="small float-right text-muted">11:21 AM</span>
                        <div class="dropdown-message small">I was wondering if you could meet for an appointment at 3:00 instead of 4:00. Thanks!</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <strong>John Doe</strong>
                        <span class="small float-right text-muted">11:21 AM</span>
                        <div class="dropdown-message small">I've sent the final files over to you for review. When you're able to sign off of them let me know and we can discuss distribution.</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item small" href="#">View all messages</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-bell"></i>
                    <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning">6 New</span>
            </span>
                    <span class="indicator text-warning d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
                </a>
                <div class="dropdown-menu" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">New Alerts:</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
                        <span class="small float-right text-muted">11:21 AM</span>
                        <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
              <span class="text-danger">
                <strong>
                  <i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>
              </span>
                        <span class="small float-right text-muted">11:21 AM</span>
                        <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
                        <span class="small float-right text-muted">11:21 AM</span>
                        <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item small" href="#">View all alerts</a>
                </div>
            </li>-->
            <li class="nav-item">
                <a class="nav-link">
                    Welcome, {!! $Fullname !!}
                </a>
            </li>
            <!--<li class="nav-item">
                <form class="form-inline my-2 my-lg-0 mr-lg-2">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Search for...">
                        <span class="input-group-btn">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
                    </div>
                </form>
            </li>-->
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-fw fa-sign-out"></i>Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="content-wrapper">
    <div class="container-fluid">
        {!! $content !!}
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small>Copyright © SBM ITB Jakarta Campus 2017 || Programmed By Eko Cahyo Nugroho</small>
            </div>
        </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{!! URL::to('/logout') !!}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-keyboard="false" data-backdrop="static" id="myLargeMultiPurposeModals" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myLargeMultiPurposeModalsLabel"></h4>
                </div>
                <div class="modal-body" id="myLargeMultiPurposeModalsBody"></div>
                <div class="modal-footer" id="myLargeMultiPurposeModalsExtraButton">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

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
    <script src="{!! asset('vendor/app/userActions/userActions.js') !!}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{!! asset('dashboard_vendor/vendor/chart.js/Chart.min.js') !!}"></script>
    <script src="{!! asset('dashboard_vendor/vendor/datatables/jquery.dataTables.js') !!}"></script>
    <script src="{!! asset('dashboard_vendor/vendor/datatables/dataTables.bootstrap4.js') !!}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{!! asset('dashboard_vendor/js/sb-admin.min.js') !!}"></script>
    <!-- Custom scripts for this page-->
    <script src="{!! asset('dashboard_vendor/js/sb-admin-datatables.min.js') !!}"></script>
    <script src="{!! asset('dashboard_vendor/js/sb-admin-charts.min.js') !!}"></script>
    <script src="{!! asset('vendor/AngularJS/angular.js') !!}"></script>
</div>
</body>

</html>

