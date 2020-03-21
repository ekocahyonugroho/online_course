<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 25/09/17
 * Time: 14:08
 */
?>
<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

$idAuthority = $db->getAccountDataByIdMember(session('idMember'))->first()->idAuthority;

$dataMember = $db->getFullMemberData(session('idMember'), $idAuthority)->first();

$Fullname = "";

switch ($idAuthority) {
    case "1":
        $Fullname = $dataMember->name;
        break;
    case "2":
        $Fullname = $dataMember->name;
        break;
    case "3":
        $Fullname = $dataMember->nama_dosen;
        break;
    case "4":
        $Fullname = $dataMember->nama;
        break;
    case "5":
        $Fullname = $dataMember->nameFirst." ".$dataMember->nameLast;
        break;
    default:
        $Fullname = "NO NAME";
        break;
}

$userPrivateMessageData = $db->findPrivateMessageByIdMemberAndIdCoursesClass($idCoursesClass, session('idMember'));
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage Online Course</li>
    <li class="breadcrumb-item active"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
    <li class="breadcrumb-item active">Private Message</li>
</ol>
<?php if(session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>
<?php if(session()->has('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if( count( $errors ) > 0 ): ?>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>!</strong> <?php echo e($error); ?>

        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<div class="row">
    <div class="col-lg-3">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-users"></i> Private Message</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px; padding-bottom: 10px;">
                    <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/managePrivateMessage/composeNewPrivateMessage'" class="btn btn-primary">Compose New Message</button>
                </div>
                <?php if($userPrivateMessageData->count() > 0): ?>
                    <?php $__currentLoopData = $userPrivateMessageData->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataPrivateMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $isSelected = 0;
                        $stmtUnreadMessage = $db->getUnreadPrivateMessageByIdPrivateMessageAndIdMember($dataPrivateMessage->idPrivateMessage, session('idMember'));
                        $countUnreadMessage = $stmtUnreadMessage->count();
                        ?>
                        <?php if($dataPrivateMessage->idMember1 == session('idMember')): ?>
                            <?php if(isset($messages)): ?>
                                <?php if($dataPrivateMessage->idPrivateMessage == $idPrivateMessage): ?>
                                    <?php
                                    $isSelected++;
                                    ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="card <?php if($isSelected > 0): ?> text-white bg-success mb-3 <?php endif; ?>">
                                <div class="card-body">
                                    <table width="100%">
                                        <tr>
                                            <td width="90%"><strong style="font-family: 'Century Gothic', Arial; font-size: 30px;"><?php echo $db->getAccountDataByIdMember($dataPrivateMessage->idMember2)->first()->Username; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $db->getPrivateMessageDataByIdPrivateMessage($dataPrivateMessage->idPrivateMessage)->first()->titleMessage; ?></td>
                                        </tr>
                                        <tr>
                                            <td><small>Last message : <?php echo $db->getLatestPrivateMessageContent($dataPrivateMessage->idPrivateMessage)->dateTime; ?></small></td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td><button type="button" onclick="viewPrivateMessageContents(<?php echo $dataPrivateMessage->idPrivateMessage; ?>)" class="btn btn-primary btn-sm"><?php if($countUnreadMessage == 0): ?> View <?php else: ?> <?php echo $countUnreadMessage; ?> New Messages <?php endif; ?></button></td>
                                            <td><button type="button" onclick="deletePrivateMessageContents(<?php echo $dataPrivateMessage->idPrivateMessage; ?>)" class="btn btn-danger btn-sm">Delete</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php elseif($dataPrivateMessage->idMember2 == session('idMember')): ?>
                            <?php if(isset($messages)): ?>
                                <?php if($dataPrivateMessage->idPrivateMessage == $idPrivateMessage): ?>
                                    <?php
                                    $isSelected++;
                                    ?>
                                <?php endif; ?>
                            <?php endif; ?>
                                <div class="card <?php if($isSelected > 0): ?> text-white bg-success mb-3 <?php endif; ?>">
                                    <div class="card-body">
                                        <table width="100%">
                                            <tr>
                                                <td width="90%"><strong style="font-family: 'Century Gothic', Arial; font-size: 30px;"><?php echo $db->getAccountDataByIdMember($dataPrivateMessage->idMember1)->first()->Username; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $db->getPrivateMessageDataByIdPrivateMessage($dataPrivateMessage->idPrivateMessage)->first()->titleMessage; ?></td>
                                            </tr>
                                            <tr>
                                                <td><small>Last message : <?php echo $db->getLatestPrivateMessageContent($dataPrivateMessage->idPrivateMessage)->dateTime; ?></small></td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td><button type="button" onclick="viewPrivateMessageContents(<?php echo $dataPrivateMessage->idPrivateMessage; ?>)" class="btn btn-primary btn-sm"><?php if($countUnreadMessage == 0): ?> View <?php else: ?> <?php echo $countUnreadMessage; ?> New Messages <?php endif; ?></button></td>
                                                <td><button type="button" onclick="deletePrivateMessageContents(<?php echo $dataPrivateMessage->idPrivateMessage; ?>)" class="btn btn-danger btn-sm">Delete</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <center>NO MESSAGE</center>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-users"></i> Message Contents</div>
            <div class="card-body">
                <?php if(isset($messages)): ?>
                    <?php echo $messages; ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function viewPrivateMessageContents(idPrivateMessage){
        location.href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/1/managePrivateMessage/showMessage/"+idPrivateMessage;
    }

    function deletePrivateMessageContents(idPrivateMessage){
        var isConfirm = confirm('Are you sure to end this conversation?');

        if(isConfirm){
            location.href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/1/managePrivateMessage/deleteMessage/"+idPrivateMessage;
        }
    }
</script>
