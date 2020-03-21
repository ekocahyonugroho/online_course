<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataExam = $db->getCoursesClassSubTopicExamByIdSubTopic($idSubTopic)->get();
$completedStudents = $db->getAllExamCompletionByIdExam($idExam)->get();

$no = 1;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h2>Topic : <?php echo $dataTopic->TopicName; ?></h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h4>Sub Topic : <?php echo $dataSubTopic->subTopicName; ?></h4></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <i class="fa fa-user-circle-o"></i> Control Panel</div>
            <div class="card-body">
                <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>'" class="btn btn-warning">Back</button>&nbsp;
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Completed Students</div>
            <div class="card-body">
                <table class="table table-hovered" id="shortDataTable">
                    <thead>
                    <tr class="table-info">
                        <th>No.</th>
                        <th>Actions</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Enrolled Course At</th>
                        <th>Completed Date</th>
                        <th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $completedStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $idAuthority = $db->getAccountDataByIdMember($data->idMember)->first()->idAuthority;
                        $studentData = $db->getFullMemberData($data->idMember, $idAuthority)->first();
                        $enrollData = $db->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, $data->idMember)->first();

                        $studentName = "";
                        $studentEmail = "";
                        $score = "0";

                        switch($idAuthority){
                            case "4":
                                $studentName = $studentData->nama;
                                $studentEmail = $studentData->email;
                                break;
                            case "5":
                                $studentName = $studentData->nameFirst." ".$studentData->nameLast;
                                $studentEmail = $studentData->emailAddress;
                                break;
                        }

                        $getScore = $db->getTotalExamScoreByIdExamAndIdMember($idExam, $data->idMember);

                        if($getScore){
                            $score = $getScore;
                        }

                        $studentCompletion = $db->getExamCompletionByIdExamAndIdMember($idExam, $data->idMember)->first();
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <table class="table table-hovered">
                                    <td><button onclick="evaluateStudent(<?php echo $data->idMember; ?>)" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> Preview</button></td>
                                </table>
                            </td>
                            <td><?php echo $studentName; ?></td>
                            <td><?php echo $studentData->Username; ?></td>
                            <td><?php echo $studentEmail; ?></td>
                            <td><?php echo $enrollData->enrollDateTime; ?></td>
                            <td><?php echo $data->dateTime; ?></td>
                            <?php if($studentCompletion->isEvaluated == "0"): ?>
                                <td><center><font color="red"><b>UNEVALUATED</b></font></center></td>
                            <?php else: ?>
                                <td><?php echo $score; ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function evaluateStudent(idMember){
        location.href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/<?php echo $typeExam; ?>/evaluateExam/"+idMember;
    }
</script>
