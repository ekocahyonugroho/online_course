<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();
$getOnlineClassStudent = $db->getEnrolledClassByIdClassCourse($idCoursesClass)->get();

$dataCreator = $db->getMemberData($dataOnlineClass->CreatedByIdUser)->first();
$idAuthorityCreator = $dataCreator->idAuthority;

$dataCreator = $db->getFullMemberData($dataOnlineClass->CreatedByIdUser, $idAuthorityCreator)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();

if($idAuthorityCreator == "3"){
    $creatorName = $dataCreator->nama_dosen;
}else{
    $creatorName = $dataCreator->name;
}
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Online Class</li>
    <li class="breadcrumb-item"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
    <li class="breadcrumb-item active"><?php echo $dataTopic->TopicName; ?></li>
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
<?php echo $subcontent; ?>