<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataExam = $db->getCoursesClassSubTopicExamByIdExam($idExam)->first();
$dataQuestion = $db->getCoursesClassExamQuestionByIdExam($idExam)->get();

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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            This process is used to create an exam with uploading file method. Means that students read the question and upload any required files as the answer based on the question asked.
                        </div>
                    </div>
                </div>
                <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>'" class="btn btn-warning">Back</button>&nbsp;
                <button onclick="showModals('myAddQuestionModals', 'Add New Question')" class="btn btn-primary">Add Question</button>&nbsp;
            </div>
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
                <table class="table table-bordered">
                    <thead>
                    <tr class="table-info">
                        <th>No.</th>
                        <th><center>Action</center></th>
                        <th><center>Question</center></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(count($dataQuestion) == 0): ?>
                        <tr class="table-danger">
                            <td colspan="3"><center>NO QUESTION</center></td>
                        </tr>
                    <?php else: ?>
                        <?php $__currentLoopData = $dataQuestion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <table>
                                        <tr>
                                            <td><button onclick="deleteQuestion(<?php echo $question->idQuestion; ?>)" type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                            <td><button onclick="editQuestion(<?php echo $question->idQuestion; ?>)" type="button" class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
                                        </tr>
                                    </table>
                                </td>
                                <td><?php echo $question->Question; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myAddQuestionModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myAddQuestionModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myAddQuestionModalsBody">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/upload/editExam/submitNewQuestion" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <input type="hidden" name="idExam" value="<?php echo $idExam; ?>">
                    <input type="hidden" name="typeExam" value="upload">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Question :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="question" name="question"></textarea>
                            <script>
                                CKEDITOR.replace( 'question');
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myAddQuestionModalsExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myEditQuestionModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myEditQuestionModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myEditQuestionModalsBody">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/upload/editExam/submitEditQuestion" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <input type="hidden" name="idExam" value="<?php echo $idExam; ?>">
                    <input type="hidden" name="typeExam" value="upload">
                    <input type="hidden" id="editIdQuestion" name="idQuestion" value="">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Question :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="editQuestion" name="editQuestion"></textarea>
                            <script>
                                CKEDITOR.replace( 'editQuestion');
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myEditQuestionModalsExtraButton">
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
<script language="JavaScript">
    function editQuestion(idQuestion){
        var formData = new FormData();
        formData.append('idCoursesClass', <?php echo $idCoursesClass; ?>);
        formData.append('idQuestion', idQuestion);
        formData.append('_token', '<?php echo e(csrf_token()); ?>');
        xmlhttpReq.open("POST", "<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageExam/<?php echo $idExam; ?>/upload/editExam/previewEditQuestion", true);
        xmlhttpReq.send(formData);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $('#editIdQuestion').val(str[1]);
                    CKEDITOR.instances['editQuestion'].setData(str[2]);
                    showModals('myEditQuestionModals', 'Edit Question');
                }else {
                    $("#myWarningModalsBody").html("Error has occured! "+str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }
</script>