<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataExam = $db->getCoursesClassSubTopicExamByIdExam($idExam)->first();
$dataQuestion = $db->getCoursesClassExamQuestionByIdExam($idExam)->get();

$getExamCompletion = $db->getExamCompletionByIdExamAndIdMember($idExam, $idMember)->first();

$no = 1;

$idAuthority = $db->getAccountDataByIdMember($idMember)->first()->idAuthority;
$studentData = $db->getFullMemberData($idMember, $idAuthority)->first();
$enrollData = $db->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, $idMember)->first();

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
            <br/><center><h5><?php echo $studentName; ?></h5></center>
            <br/><center><h6><?php echo $studentEmail; ?></h6></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Questions List</div>
            <div class="card-body">
                <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/written/evaluateExam'" class="btn btn-warning">Back</button>&nbsp;
                <br />
                <br />
                <table class="table table-bordered">
                    <tbody>
                    <?php if(count($dataQuestion) == 0): ?>
                        <tr class="table-danger">
                            <td colspan="2"><center>NO QUESTION</center></td>
                        </tr>
                    <?php else: ?>
                        <?php $__currentLoopData = $dataQuestion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $getStudentAnswer = $db->getCreatedCourseClassExamStudentAnswerByIdQuestionAndIdMember($question->idQuestion, $idMember)->first();
                            ?>
                            <tr>
                                <td rowspan="3"><?php echo $no++; ?></td>
                                <td><?php echo $question->Question; ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php if(count($getStudentAnswer) == 0): ?>
                                        <form>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="email">Your Answer (Max  <?php echo ini_get('post_max_size'); ?> bytes) :</label>
                                                <div class="col-sm-6">
                                                    <medium id="emailHelp" class="form-text text-muted">NO ANSWER</medium>
                                                </div>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <form>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <div style="padding-left: 20px;">
                                                        <?php echo $getStudentAnswer->answerValue; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <br />
                                        Score : <b><?php if($getStudentAnswer->isScored == '0'): ?> Under Review <?php else: ?> <?php echo $getStudentAnswer->answerScore; ?> <?php endif; ?></b>
                                        <br />Mentor Suggestion :
                                        <br />
                                        <?php echo $getStudentAnswer->answerSuggestion; ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php if($getExamCompletion->isEvaluated == "0"): ?>
                                        <?php if(count($getStudentAnswer) == 1): ?>
                                            <?php if($getStudentAnswer->isScored == '0'): ?>
                                                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/written/evaluateExam/<?php echo $idMember; ?>/submitEvaluate" class="form-horizontal">
                                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                    <input type="hidden" name="idQuestion" value="<?php echo $question->idQuestion; ?>">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-8" for="email">Score :</label>
                                                        <div class="col-sm-2">
                                                            <input type="number" name="score" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4" for="email">Suggestion :</label>
                                                        <div class="col-sm-12">
                                                            <textarea class="form-control" id="suggestion<?php echo $question->idQuestion; ?>" name="suggestion"></textarea>
                                                            <script>
                                                                var textarea = document.getElementById('suggestion<?php echo $question->idQuestion; ?>');
                                                                CKEDITOR.replace(textarea);
                                                            </script>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-4 col-sm-10">
                                                            <button type="submit" onclick="return confirm('Are you sure to submit your review? You can not change it anymore.')" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php if($getExamCompletion->isEvaluated == "0"): ?>
                        <tr>
                            <td colspan="2"><center><button onclick="finishEvaluation()" class="btn btn-success">Finish Evaluation</button></center></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function finishEvaluation(){
        var isConfirm = confirm('Are you sure to finish this evaluation? Related students would be notified to his/her email later.');

        if(isConfirm){
            location.href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/written/evaluateExam/<?php echo $idMember; ?>/finishEvaluation";
        }
    }
</script>
