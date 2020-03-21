<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 21/02/18
 * Time: 10:45
 */
?>
<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
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
                <i class="fa fa-users"></i> Detail Progress <?php echo $Fullname; ?></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <?php if($getTopic->count() > 0): ?>
                        <?php $__currentLoopData = $getTopic->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataTopic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $getSubTopic = $db->getCoursesClassSubTopicByIdTopic($dataTopic->idTopic); ?>
                            <tr class="table-info">
                                <td><?php echo $dataTopic->TopicName; ?></td>
                                <td>
                                    <table class="table table-bordered">
                                    <?php if($getSubTopic->count() > 0): ?>
                                        <?php $__currentLoopData = $getSubTopic->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataSubTopic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="table-warning">
                                                <td><?php echo $dataSubTopic->subTopicName; ?></td>
                                                <td>
                                                    <table class="table table-bordered">
                                                        <tr class="table-danger">
                                                            <td>
                                                    <?php if($dataSubTopic->subTopicType == "1"): ?>
                                                        <?php
                                                            $getStudentAccessCount = $db->getStudentAccessSubTopicCount($dataSubTopic->idSubTopic, $idMember);
                                                        ?>
                                                            <?php if($getStudentAccessCount->count() > 0): ?>
                                                                Access <?php echo $getStudentAccessCount->count(); ?> time(s). Last access : <?php echo date('d F Y H:i:s', strtotime($getStudentAccessCount->first()->dateTimeAccess)); ?>

                                                            <?php else: ?>
                                                                Never Access
                                                            <?php endif; ?>
                                                        <?php elseif($dataSubTopic->subTopicType == "2"): ?>
                                                            <?php
                                                                $stmtCompletion = $db->getAssignmentCompletionStatusByIdSubTopicAndIdMember($dataSubTopic->idSubTopic, $idMember, '1');
                                                            ?>
                                                                <?php if($stmtCompletion->count() > 0): ?>
                                                                    <?php $countCompleted = 0; ?>
                                                                    <?php $__currentLoopData = $stmtCompletion->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataCompletion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($dataCompletion->isEvaluated == "1"): ?>
                                                                                <?php $countCompleted++; ?>
                                                                            <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                    <?php echo $countCompleted; ?> of <?php echo $stmtCompletion->count(); ?> has been completed
                                                                <?php else: ?>
                                                                    Have not been completed
                                                                <?php endif; ?>
                                                    <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    </table>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
