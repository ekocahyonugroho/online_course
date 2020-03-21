<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 13/02/18
 * Time: 16:09
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
<form method="post" enctype="multipart/form-data" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/submitNewThread" class="form-horizontal">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">Title :</label>
        <div class="col-sm-12">
            <textarea class="form-control" name="titleNewThread"><?php echo e(old('titleNewThread')); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">Message :</label>
        <div class="col-sm-12">
            <textarea class="form-control" id="messageThread" name="messageThread"><?php echo e(old('messageThread')); ?></textarea>
            <script>
                CKEDITOR.replace( 'messageThread');
            </script>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">
            File(s)
        </label>
        <div class="col-sm-9">
            <span class="btn btn-default btn-file">
                <input id="input-2" name="file[]" type="file" class="form-control" multiple data-show-upload="true" data-show-caption="true">
            </span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" class="btn btn-primary">Post</button>
        </div>
    </div>
</form>
