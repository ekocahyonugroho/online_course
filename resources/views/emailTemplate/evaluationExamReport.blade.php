@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataQuestion = $db->getCoursesClassExamQuestionByIdExam($idExam)->get();

$getExamCompletion = $db->getExamCompletionByIdExamAndIdMember($idExam, $studentData->idMember)->first();

$no = 1;

$idAuthority = $studentData->idAuthority;

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

$buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

$getScore = $db->getTotalExamScoreByIdExamAndIdMember($idExam, $studentData->idMember);
?>
Hi, {!! $studentName !!}
<p>Below written your exam report of {!! $CourseName !!} on Topic : {!! $getTopic->TopicName !!} ({!! $getSubTopic->subTopicName !!}),</p>
<table border="1">
    @foreach($dataQuestion AS $question)
        <?php
        $getStudentAnswer = $db->getCreatedCourseClassExamStudentAnswerByIdQuestionAndIdMember($question->idQuestion, $studentData->idMember)->first();

        $studentAnswer = "";
        if(count($getStudentAnswer) == 1){
            switch(strtoupper($getExam->examType)){
                case "UPLOAD" :
                    $studentAnswer = "<td colspan=\"2\"><a href=\"".URL::to('/').$getStudentAnswer->answerValue."\">Download Answer</a></td>";
                    break;
                case "CHOICES" :
                    $idChoice = $getStudentAnswer->answerValue;
                    $choiceValue = $db->getCoursesClassExamChoiceValueByIdChoice($idChoice)->first();
                    $studentAnswer = "<td colspan=\"2\">".$choiceValue->answerText."</td>";
                    break;
                case "WRITTEN" :
                    $studentAnswer = $getStudentAnswer->answerValue;
                    break;
                default :
                    $studentAnswer = "<td colspan=\"2\"><medium>NO ANSWER</medium></td>";
            }
        }
        ?>
        <tr>
            <td rowspan="4">{!! $no++ !!}</td>
            <td colspan="2">{!! $question->Question !!}</td>
        </tr>
        <tr>
            @if(count($getStudentAnswer) == 0)
                <td colspan="2"><medium>NO ANSWER</medium></td>
            @else
                {!! $studentAnswer !!}
            @endif
        </tr>
        <tr>
            @if(count($getStudentAnswer) == 0)
                <td colspan="2">Score : 0</td>
            @else
                <td colspan="2">Score : {!! $getStudentAnswer->answerScore !!}</td>
            @endif
        </tr>
        <tr>
            <td>Mentor Suggestion :</td>
            @if(count($getStudentAnswer) == 0)
                <td colspan="2"></td>
            @else
                <td>{!! $getStudentAnswer->answerSuggestion !!}</td>
            @endif
        </tr>
    @endforeach
    <tr>
        <td>Total Score :</td>
        <td colspan="2">{!! $getScore !!}</td>
    </tr>
</table>
<p>To check your result more details, please login to your account by click this below button :</p>
<p><a href="https://{!! $_SERVER['SERVER_NAME'] !!}/login"><button style='{!! $buttonStyle !!}'>Click Here To Login</button></a></p>
