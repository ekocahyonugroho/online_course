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

                            $dataChoice = $db->getExamAnswerChoicesByIdQuestion($question->idQuestion)->get();
                            ?>
                            <tr>
                                <td rowspan="2">{!! $no++ !!}</td>
                                <td>{!! $question->Question !!}</td>
                            </tr>
                            <tr>
                                <td>
                                    @if($isClosed == '1')
                                        @if(empty($dataChoice))
                                            <center>NO CHOICES</center>
                                        @else
                                            <table class="table table-hovered">
                                                @foreach($dataChoice AS $choice)
                                                    <tr class="table-info">
                                                        <td>{!! $choice->choiceScore !!}</td>
                                                        <td>{!! $choice->answerText !!}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    @else
                                        @if(empty($getStudentAnswer))
                                            @if(empty($dataChoice))
                                                <center>NO CHOICES</center>
                                            @else
                                                <table class="table table-hovered">
                                                    @foreach($dataChoice AS $choice)
                                                        <tr class="table-info">
                                                            <td width="8%">
                                                                <form action="{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $idSubTopic !!}/enterExam/{!! $idExam !!}/choices/{!! $question->idQuestion !!}/submitExamAnswer" method="post">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <input type="hidden" name="answer" value="{!! $choice->idChoice !!}" />
                                                                    <button type="submit" onclick="return confirm('Are you sure to submit? If yes, you are not allowed to revise the answer.')" class="btn btn-success">Choose</button>
                                                                </form>
                                                            </td>
                                                            <td>{!! $choice->answerText !!}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                        @else
                                            @if(empty($dataChoice))
                                                <center>NO CHOICES</center>
                                            @else
                                                <table class="table table-hovered">
                                                    @foreach($dataChoice AS $choice)
                                                        <tr class="table-info">
                                                            <td width="5%">
                                                                <center>
                                                                    @if($choice->idChoice == $getStudentAnswer->answerValue)
                                                                        <button class="btn btn-info"><i class="fa fa-check-square" aria-hidden="true"></i></button>
                                                                    @endif
                                                                </center>
                                                            </td>
                                                            <td width="5%"><center><b>{!! $choice->choiceScore !!}</b></center></td>
                                                            <td>{!! $choice->answerText !!}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="table table-success">
                                                        <td colspan="3">Your Score : <b>{!! $getStudentAnswer->answerScore !!}</b></td>
                                                    </tr>
                                                </table>
                                            @endif
                                        @endif
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
            location.href='/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $idSubTopic !!}/enterExam/{!! $idExam !!}/choices/completeExam';
        }
    }
</script>
