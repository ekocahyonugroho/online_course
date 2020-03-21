<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataQuestion = $db->getCoursesClassAssignmentQuestionByIdAssignment($idAssignment)->get();

$getAssignmentCompletion = $db->getAssignmentCompletionByIdAssignmentAndIdMember($idAssignment, $studentData->idMember)->first();

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

$getScore = $db->getTotalAssignmentScoreByIdAssignmentAndIdMember($idAssignment, $studentData->idMember);
?>
Hi, <?php echo $studentName; ?>

<p>Below written your assignment report of <?php echo $CourseName; ?> on Topic : <?php echo $getTopic->TopicName; ?> (<?php echo $getSubTopic->subTopicName; ?>),</p>
<table border="1">
    <?php $__currentLoopData = $dataQuestion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $getStudentAnswer = $db->getCreatedCourseClassAssignmentStudentAnswerByIdQuestionAndIdMember($question->idQuestion, $studentData->idMember)->first();

        $studentAnswer = "";
        if(count($getStudentAnswer) == 1){
            switch(strtoupper($getAssignment->assignmentType)){
                case "UPLOAD" :
                    $studentAnswer = "<td colspan=\"2\"><a href=\"".URL::to('/').$getStudentAnswer->answerValue."\">Download Answer</a></td>";
                    break;
                case "CHOICES" :
                    $idChoice = $getStudentAnswer->answerValue;
                    $choiceValue = $db->getCoursesClassAssignmentChoiceValueByIdChoice($idChoice)->first();
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
            <td rowspan="4"><?php echo $no++; ?></td>
            <td colspan="2"><?php echo $question->Question; ?></td>
        </tr>
        <tr>
            <?php if(count($getStudentAnswer) == 0): ?>
                    <td colspan="2"><medium>NO ANSWER</medium></td>
                <?php else: ?>
                    <?php echo $studentAnswer; ?>

            <?php endif; ?>
        </tr>
        <tr>
            <?php if(count($getStudentAnswer) == 0): ?>
                <td colspan="2">Score : 0</td>
            <?php else: ?>
                <td colspan="2">Score : <?php echo $getStudentAnswer->answerScore; ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <td>Mentor Suggestion :</td>
            <?php if(count($getStudentAnswer) == 0): ?>
                <td colspan="2"></td>
            <?php else: ?>
                <td><?php echo $getStudentAnswer->answerSuggestion; ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td>Total Score :</td>
            <td colspan="2"><?php echo $getScore; ?></td>
        </tr>
</table>
<p>To check your result more details, please login to your account by click this below button :</p>
<p><a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/login"><button style='<?php echo $buttonStyle; ?>'>Click Here To Login</button></a></p>
