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

$getForumList = $db->getForumListByIdCoursesClass($idCoursesClass);
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage Online Course</li>
    <li class="breadcrumb-item active"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
    <li class="breadcrumb-item active">Forum Discussion</li>
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
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-users"></i> Forum Threads</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px; padding-bottom: 10px;">
                    <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/createNewThread'" class="btn btn-primary">Create New Thread</button>
                </div>
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr class="table-info">
                            <th>Forum Subject</th>
                            <th>Last Post</th>
                            <th>Info</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($getForumList->count() > 0): ?>
                        <?php $__currentLoopData = $getForumList->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataForumList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $lastPost = $db->getLatestThreadMessageByIdForum($dataForumList->idForum);
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $dataForumList->idForum; ?>"><?php echo $dataForumList->forumTitle; ?></a>
                                    <br />Created at <?php echo date('d F Y H:i:s', strtotime($dataForumList->dateTime)); ?>

                                    <br />by <b><?php echo $db->getFullNameMemberByIdMember($dataForumList->idMemberCreator); ?></b>
                                </td>
                                <td>
                                    <?php echo date('d F Y H:i:s', strtotime($lastPost->dateTime)); ?>

                                    <br />by <b><?php echo $db->getFullNameMemberByIdMember($lastPost->idMember); ?></b>
                                </td>
                                <td>
                                    Post(s) : <?php echo $db->getAllThreadMessageByIdForum($dataForumList->idForum)->count(); ?>

                                    <br />Views : <?php echo $db->getForumViewDataByIdForum($dataForumList->idForum)->count(); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>