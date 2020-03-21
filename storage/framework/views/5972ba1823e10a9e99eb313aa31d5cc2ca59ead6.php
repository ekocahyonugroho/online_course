<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataAssignment = $db->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment)->first();
$dataQuestion = $db->getCoursesClassAssignmentQuestionByIdAssignment($idAssignment)->get();

$getAssignmentCompletion = $db->getAssignmentCompletionByIdAssignmentAndIdMember($idAssignment, session('idMember'));

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
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Questions List</div>
            <div class="card-body">
                <button onclick="location.href='<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>'" class="btn btn-warning">Back</button>&nbsp;
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
                            $getStudentAnswer = $db->getCreatedCourseClassAssignmentStudentAnswerByIdQuestionAndIdMember($question->idQuestion, session('idMember'))->first();

                            $dataChoice = $db->getAssignmentAnswerChoicesByIdQuestion($question->idQuestion)->get();
                            ?>
                            <tr>
                                <td rowspan="2"><?php echo $no++; ?></td>
                                <td><?php echo $question->Question; ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php if($isClosed == '1'): ?>
                                        <?php if(count($dataChoice) == 0): ?>
                                            <center>NO CHOICES</center>
                                        <?php else: ?>
                                            <table class="table table-hovered">
                                                <?php $__currentLoopData = $dataChoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="table-info">
                                                        <td><?php echo $choice->choiceScore; ?></td>
                                                        <td><?php echo $choice->answerText; ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </table>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if(count($getStudentAnswer) == 0): ?>
                                            <?php if(count($dataChoice) == 0): ?>
                                                <center>NO CHOICES</center>
                                            <?php else: ?>
                                                <table class="table table-hovered">
                                                    <?php $__currentLoopData = $dataChoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="table-info">
                                                            <td width="8%">
                                                                <form action="<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/enterAssignment/<?php echo $idAssignment; ?>/choices/<?php echo $question->idQuestion; ?>/submitAssignmentAnswer" method="post">
                                                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                                    <input type="hidden" name="answer" value="<?php echo $choice->idChoice; ?>" />
                                                                    <button type="submit" onclick="return confirm('Are you sure to submit? If yes, you are not allowed to revise the answer.')" class="btn btn-success">Choose</button>
                                                                </form>
                                                            </td>
                                                            <td><?php echo $choice->answerText; ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </table>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if(count($dataChoice) == 0): ?>
                                                <center>NO CHOICES</center>
                                            <?php else: ?>
                                                <table class="table table-hovered">
                                                    <?php $__currentLoopData = $dataChoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="table-info">
                                                            <td width="5%">
                                                                <center>
                                                                    <?php if($choice->idChoice == $getStudentAnswer->answerValue): ?>
                                                                        <button class="btn btn-info"><i class="fa fa-check-square" aria-hidden="true"></i></button>
                                                                    <?php endif; ?>
                                                                </center>
                                                            </td>
                                                            <td width="5%"><center><b><?php echo $choice->choiceScore; ?></b></center></td>
                                                            <td><?php echo $choice->answerText; ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="table table-success">
                                                        <td colspan="3">Your Score : <b><?php echo $getStudentAnswer->answerScore; ?></b></td>
                                                    </tr>
                                                </table>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="table-info" colspan="2">
                                <?php if($getAssignmentCompletion->count() == 0): ?>
                                    <?php if($isClosed == '1'): ?>
                                        <center><button type="button" disabled class="btn btn-danger">Passed Deadline</button></center>
                                    <?php else: ?>
                                        <center><button type="button" onclick="completeAssignment()" class="btn btn-success">Complete My Assignment</button></center>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <center><large class="form-text text-muted">COMPLETED</large></center>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function completeAssignment(){
        var isConfirm = confirm('Are you sure to complete this Assignment? If Yes, this assignment would be marked as submitted to be scored by your Mentor.');

        if(isConfirm){
            location.href='/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/enterAssignment/<?php echo $idAssignment; ?>/choices/completeAssignment';
        }
    }
</script>
