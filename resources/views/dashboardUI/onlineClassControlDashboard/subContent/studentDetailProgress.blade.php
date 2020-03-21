<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 21/02/18
 * Time: 10:45
 */
?>
@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

$idAuthority = $db->getAccountDataByIdMember($idMember)->first()->idAuthority;

$dataMember = $db->getFullMemberData($idMember, $idAuthority)->first();

$Fullname = $db->getFullNameMemberByIdMember($idMember);

$getTopic = $db->getCoursesClassTopicByIdCoursesClass($idCoursesClass);
?>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Detail Progress {!! $Fullname !!}</div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if($getTopic->count() > 0)
                        @foreach($getTopic->get() AS $dataTopic)
                            <?php $getSubTopic = $db->getCoursesClassSubTopicByIdTopic($dataTopic->idTopic); ?>
                            <tr class="table-info">
                                <td>{!! $dataTopic->TopicName !!}</td>
                                <td>
                                    <table class="table table-bordered">
                                    @if($getSubTopic->count() > 0)
                                        @foreach($getSubTopic->get() AS $dataSubTopic)
                                            <tr class="table-warning">
                                                <td>{!! $dataSubTopic->subTopicName !!}</td>
                                                <td>
                                                    <table class="table table-bordered">
                                                        <tr class="table-danger">
                                                            <td>
                                                    @if($dataSubTopic->subTopicType == "1")
                                                        <?php
                                                            $getStudentAccessCount = $db->getStudentAccessSubTopicCount($dataSubTopic->idSubTopic, $idMember);
                                                        ?>
                                                            @if($getStudentAccessCount->count() > 0)
                                                                Access {!! $getStudentAccessCount->count() !!} time(s). Last access : {!! date('d F Y H:i:s', strtotime($getStudentAccessCount->first()->dateTimeAccess)) !!}
                                                            @else
                                                                Never Access
                                                            @endif
                                                        @elseif($dataSubTopic->subTopicType == "2")
                                                            <?php
                                                                $stmtCompletion = $db->getAssignmentCompletionStatusByIdSubTopicAndIdMember($dataSubTopic->idSubTopic, $idMember, '1');
                                                            ?>
                                                                @if($stmtCompletion->count() > 0)
                                                                    <?php $countCompleted = 0; ?>
                                                                    @foreach($stmtCompletion->get() AS $dataCompletion)
                                                                        @if($dataCompletion->isEvaluated == "1")
                                                                                <?php $countCompleted++; ?>
                                                                            @endif
                                                                    @endforeach

                                                                    {!! $countCompleted !!} of {!! $stmtCompletion->count() !!} has been completed
                                                                @else
                                                                    Have not been completed
                                                                @endif
                                                    @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
