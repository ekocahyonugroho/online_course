@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
            <div class="card-header bg-primary">
                <i class="fa fa-user-circle-o"></i> Control Panel</div>
            <div class="card-body">
                <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}'" class="btn btn-warning">Back</button>&nbsp;
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
                    @foreach($completedStudents AS $data)
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
                            <td>{!! $no++ !!}</td>
                            <td>
                                <table class="table table-hovered">
                                    <td><button onclick="evaluateStudent({!! $data->idMember !!})" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> Preview</button></td>
                                </table>
                            </td>
                            <td>{!! $studentName !!}</td>
                            <td>{!! $studentData->Username !!}</td>
                            <td>{!! $studentEmail !!}</td>
                            <td>{!! $enrollData->enrollDateTime !!}</td>
                            <td>{!! $data->dateTime !!}</td>
                            @if($studentCompletion->isEvaluated == "0")
                                <td><center><font color="red"><b>UNEVALUATED</b></font></center></td>
                            @else
                                <td>{!! $score !!}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function evaluateStudent(idMember){
        location.href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageExam/{!! $idExam !!}/{!! $typeExam !!}/evaluateExam/"+idMember;
    }
</script>
