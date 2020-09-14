@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataExam = $db->getCoursesClassSubTopicExamByIdExam($idExam)->first();
$dataQuestion = $db->getCoursesClassExamQuestionByIdExam($idExam)->get();

$getExamCompletion = $db->getExamCompletionByIdExamAndIdMember($idExam, session('idMember'));

$no = 1;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h2>Topic : {!! $dataTopic->TopicName !!}</h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h4>Sub Topic : {!! $dataSubTopic->subTopicName !!}</h4></center>
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
                <button onclick="location.href='{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $idSubTopic !!}'" class="btn btn-warning">Back</button>&nbsp;
                <br />
                <br />
                <table class="table table-bordered">
                    <tbody>
                    @if(empty($dataQuestion))
                    <tr class="table-danger">
                        <td colspan="2"><center>NO QUESTION</center></td>
                    </tr>
                    @else
                    @foreach($dataQuestion AS $question)
                    <?php
                    $getStudentAnswer = $db->getCreatedCourseClassExamStudentAnswerByIdQuestionAndIdMember($question->idQuestion, session('idMember'))->first();
                    ?>
                    <tr>
                        <td rowspan="2">{!! $no++ !!}</td>
                        <td>{!! $question->Question !!}</td>
                    </tr>
                    <tr>
                        <td>
                            @if(empty($getStudentAnswer))
                            @if($getExamCompletion->count() == 0)
                            @if($isClosed == '1')
                            <form>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="email">Your Answer (Max  {!! ini_get('post_max_size') !!} bytes) :</label>
                                    <div class="col-sm-6">
                                        <medium id="emailHelp" class="form-text text-muted"><b>This exam has been closed because has passed the deadline.</b></medium>
                                    </div>
                                </div>
                            </form>
                            @else
                            <form method="post" enctype="multipart/form-data" action="{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $idSubTopic !!}/enterExam/{!! $idExam !!}/upload/{!! $question->idQuestion !!}/submitExamAnswer">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="email">Your Answer (Max  {!! ini_get('post_max_size') !!} bytes) :</label>
                                    <div class="col-sm-6">
                                        <input type="file" class="form-control" name="fileAnswer" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-10">
                                        <medium id="emailHelp" class="form-text text-muted">If you click Submit button, you are not allowed to change your answer anymore.</medium>
                                        <button type="submit" onclick="return confirm('Are you sure to submit? If yes, you are not allowed to revise the answer.')" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                            @endif
                            @else
                            <form>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="email">Your Answer (Max  {!! ini_get('post_max_size') !!} bytes) :</label>
                                    <div class="col-sm-6">
                                        <medium id="emailHelp" class="form-text text-muted">NO ANSWER</medium>
                                    </div>
                                </div>
                            </form>
                            @endif
                            @else
                            <form>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="email">Your Answer (Max  {!! ini_get('post_max_size') !!} bytes) :</label>
                                    <div class="col-sm-6">
                                        <button type="button" onclick="location.href='{!! URL::to('/').$getStudentAnswer->answerValue !!}'" class="btn btn-success">My Answer</button>
                                    </div>
                                </div>
                            </form>
                            <br />
                            Your Score : <b>@if($getStudentAnswer->isScored == '0') Under Review @else {!! $getStudentAnswer->answerScore !!} @endif</b>
                            <br />Mentor Suggestion :
                            <br />
                            {!! $getStudentAnswer->answerSuggestion !!}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="table-info" colspan="2">
                            @if($getExamCompletion->count() == 0)
                            @if($isClosed == '1')
                            <center><button type="button" disabled class="btn btn-danger">Passed Deadline</button></center>
                            @else
                            <center><button type="button" onclick="completeExam()" class="btn btn-success">Complete My Exam</button></center>
                            @endif
                            @else
                            <center><large class="form-text text-muted">COMPLETED</large></center>
                            @endif
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function completeExam(){
        var isConfirm = confirm('Are you sure to complete this Exam? If Yes, this exam would be marked as submitted to be scored by your Mentor.');

        if(isConfirm){
            location.href='/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $idSubTopic !!}/enterExam/{!! $idExam !!}/upload/completeExam';
        }
    }
</script>
