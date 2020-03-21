@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataAssignment = $db->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment)->first();
$dataQuestion = $db->getCoursesClassAssignmentQuestionByIdAssignment($idAssignment)->get();

$getAssignmentCompletion = $db->getAssignmentCompletionByIdAssignmentAndIdMember($idAssignment, $idMember)->first();

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
            <center><h2>Topic : {!! $dataTopic->TopicName !!}</h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h4>Sub Topic : {!! $dataSubTopic->subTopicName !!}</h4></center>
            <br/><center><h5>{!! $studentName !!}</h5></center>
            <br/><center><h6>{!! $studentEmail !!}</h6></center>
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
                <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageAssignment/{!! $idAssignment !!}/upload/evaluateAssignment'" class="btn btn-warning">Back</button>&nbsp;
                <br />
                <br />
                <table class="table table-bordered">
                    <tbody>
                    @if(count($dataQuestion) == 0)
                        <tr class="table-danger">
                            <td colspan="2"><center>NO QUESTION</center></td>
                        </tr>
                    @else
                        @foreach($dataQuestion AS $question)
                            <?php
                            $getStudentAnswer = $db->getCreatedCourseClassAssignmentStudentAnswerByIdQuestionAndIdMember($question->idQuestion, $idMember)->first();
                            ?>
                            <tr>
                                <td rowspan="3">{!! $no++ !!}</td>
                                <td>{!! $question->Question !!}</td>
                            </tr>
                            <tr>
                                <td>
                                    @if(count($getStudentAnswer) == 0)
                                            <form>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-4" for="email">Your Answer (Max  {!! ini_get('post_max_size') !!} bytes) :</label>
                                                    <div class="col-sm-6">
                                                        <medium id="emailHelp" class="form-text text-muted">NO ANSWER</medium>
                                                    </div>
                                                </div>
                                            </form>
                                    @else
                                        <form>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <button type="button" onclick="location.href='{!! URL::to('/').$getStudentAnswer->answerValue !!}'" class="btn btn-success">Download Answer</button>
                                                </div>
                                            </div>
                                        </form>
                                        <br />
                                        Score : <b>@if($getStudentAnswer->isScored == '0') Under Review @else {!! $getStudentAnswer->answerScore !!} @endif</b>
                                        <br />Mentor Suggestion :
                                        <br />
                                        {!! $getStudentAnswer->answerSuggestion !!}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @if($getAssignmentCompletion->isEvaluated == "0")
                                        @if($getStudentAnswer->isScored == '0')
                                        <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageAssignment/{!! $idAssignment !!}/upload/evaluateAssignment/{!! $idMember !!}/submitEvaluate" class="form-horizontal">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="idQuestion" value="{!! $question->idQuestion !!}">
                                            <div class="form-group">
                                                <label class="control-label col-sm-8" for="email">Score :</label>
                                                <div class="col-sm-2">
                                                    <input type="number" name="score" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="email">Suggestion :</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control" id="suggestion{!! $question->idQuestion !!}" name="suggestion"></textarea>
                                                    <script>
                                                        var textarea = document.getElementById('suggestion{!! $question->idQuestion !!}');
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
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if($getAssignmentCompletion->isEvaluated == "0")
                        <tr>
                            <td colspan="2"><center><button onclick="finishEvaluation()" class="btn btn-success">Finish Evaluation</button></center></td>
                        </tr>
                    @endif
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
            location.href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageAssignment/{!! $idAssignment !!}/upload/evaluateAssignment/{!! $idMember !!}/finishEvaluation";
        }
    }
</script>
