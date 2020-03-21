<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 20/02/18
 * Time: 9:35
 */
?>
@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

$idAuthority = $db->getAccountDataByIdMember(session('idMember'))->first()->idAuthority;

$dataMember = $db->getFullMemberData(session('idMember'), $idAuthority)->first();

$Fullname = $db->getFullNameMemberByIdMember(session('idMember'));

$stmtEnrolledStudents = $db->getEnrolledStudentsByIdClassCourse($idCoursesClass);
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage Online Course</li>
    <li class="breadcrumb-item">{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</li>
    <li class="breadcrumb-item active">Enrolled Students</li>
</ol>
@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> {{ session('error') }}
    </div>
@endif
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> {{ session('success') }}
    </div>
@endif
@if( count( $errors ) > 0 )
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>!</strong> {{ $error }}
        </div>
    @endforeach
@endif
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Enrolled Students</div>
            <div class="card-body">
                <div class="row">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Enrolled Date</th>
                            <th>Detail Progress</th>
                            <th>Summary Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($stmtEnrolledStudents->count() > 0)
                                <?php $count = 1; ?>
                                @foreach($stmtEnrolledStudents->get() AS $dataStudent)
                                    <tr>
                                        <td>{!! $count++; !!}</td>
                                        <td>
                                            <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/viewEnrolledStudents/sendPrivateMessage/{!! $dataStudent->idMember !!}'" type="button" class="btn btn-info">Send PM</button>
                                            {!! $db->getFullNameMemberByIdMember($dataStudent->idMember) !!}
                                        </td>
                                        <td>{!! $dataStudent->Username !!}</td>
                                        <td>{!! date('d F Y H:i:s', strtotime($dataStudent->enrollDateTime)) !!}</td>
                                        <td><button onclick="viewDetailsStudentProgress({!! $dataStudent->idMember !!})" type="button" class="btn btn-success">View</button> </td>
                                        <td>{!! $db->getSummaryStudentProgress($idCoursesClass,$dataStudent->idMember) !!}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="mediumModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="mediumModalsLabel"></h4>
            </div>
            <div class="modal-body" id="mediumModalsBody"></div>
            <div class="modal-footer">
                <div id="modalExtraButton"></div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script language="JavaScript">
    function viewDetailsStudentProgress(idMember){
        xmlhttpReq.open("GET", "/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/viewEnrolledStudents/showStudentDetailProgress/"+idMember, true);
        xmlhttpReq.send(null);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#mediumModalsBody").html(str[1]);
                    showModals('mediumModals', 'Detail Student Progress');
                } else {
                    $("#myWarningModalsBody").html(str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }
</script>
