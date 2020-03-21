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
                            ?>
                            <tr>
                                <td rowspan="2"><?php echo $no++; ?></td>
                                <td><?php echo $question->Question; ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php if(count($getStudentAnswer) == 0): ?>
                                        <?php if($getAssignmentCompletion->count() == 0): ?>
                                            <?php if($isClosed == '1'): ?>
                                                <form>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4" for="email">Your Answer (Max  <?php echo ini_get('post_max_size'); ?> bytes) :</label>
                                                        <div class="col-sm-12">
                                                            <medium id="emailHelp" class="form-text text-muted"><b>This assignment has been closed because has passed the deadline.</b></medium>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <form method="post" action="<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/enterAssignment/<?php echo $idAssignment; ?>/written/<?php echo $question->idQuestion; ?>/submitAssignmentAnswer">
                                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4" for="email">Your Answer :</label>
                                                        <div class="col-sm-12">
                                                            <textarea class="form-control" id="answer<?php echo $question->idQuestion; ?>" name="answer<?php echo $question->idQuestion; ?>"></textarea>
                                                            <script>
                                                                CKEDITOR.replace( 'answer<?php echo $question->idQuestion; ?>');
                                                            </script>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-4 col-sm-10">
                                                            <medium id="emailHelp" class="form-text text-muted">If you click Submit button, you are not allowed to change your answer anymore.</medium>
                                                            <button type="submit" onclick="return confirm('Are you sure to submit? If yes, you are not allowed to revise the answer.')" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <form>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-4" for="email">Your Answer :</label>
                                                    <div class="col-sm-12">
                                                        <medium id="emailHelp" class="form-text text-muted">NO ANSWER</medium>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <form>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="email">Your Answer :</label>
                                                <div class="col-sm-12">
                                                    <?php echo $getStudentAnswer->answerValue; ?>

                                                </div>
                                            </div>
                                        </form>
                                        <br />
                                        Your Score : <b><?php if($getStudentAnswer->isScored == '0'): ?> Under Review <?php else: ?> <?php echo $getStudentAnswer->answerScore; ?> <?php endif; ?></b>
                                        <br />Mentor Suggestion :
                                        <br />
                                        <?php echo $getStudentAnswer->answerSuggestion; ?>

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
            location.href='/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/enterAssignment/<?php echo $idAssignment; ?>/written/completeAssignment';
        }
    }
</script>
