<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 20/02/18
 * Time: 9:35
 */
?>
<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
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
    <li class="breadcrumb-item"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
    <li class="breadcrumb-item active">Enrolled Students</li>
</ol>
<?php if(session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>
<?php if(session()->has('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if( count( $errors ) > 0 ): ?>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>!</strong> <?php echo e($error); ?>

        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
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
                            <?php if($stmtEnrolledStudents->count() > 0): ?>
                                <?php $count = 1; ?>
                                <?php $__currentLoopData = $stmtEnrolledStudents->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataStudent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo $count++;; ?></td>
                                        <td>
                                            <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/viewEnrolledStudents/sendPrivateMessage/<?php echo $dataStudent->idMember; ?>'" type="button" class="btn btn-info">Send PM</button>
                                            <?php echo $db->getFullNameMemberByIdMember($dataStudent->idMember); ?>

                                        </td>
                                        <td><?php echo $dataStudent->Username; ?></td>
                                        <td><?php echo date('d F Y H:i:s', strtotime($dataStudent->enrollDateTime)); ?></td>
                                        <td><button onclick="viewDetailsStudentProgress(<?php echo $dataStudent->idMember; ?>)" type="button" class="btn btn-success">View</button> </td>
                                        <td><?php echo $db->getSummaryStudentProgress($idCoursesClass,$dataStudent->idMember); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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
        xmlhttpReq.open("GET", "/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/viewEnrolledStudents/showStudentDetailProgress/"+idMember, true);
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
