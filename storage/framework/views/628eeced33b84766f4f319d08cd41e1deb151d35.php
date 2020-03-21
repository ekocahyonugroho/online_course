<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 15/02/18
 * Time: 10:27
 */
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

$idAuthority = $db->getAccountDataByIdMember(session('idMember'))->first()->idAuthority;

$dataMember = $db->getFullMemberData(session('idMember'), $idAuthority)->first();

$Fullname = $db->getFullNameMemberByIdMember(session('idMember'));

$dataForum = $getForumData->first();
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage Online Course</li>
    <li class="breadcrumb-item"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
    <li class="breadcrumb-item">Forum Discussion</li>
    <li class="breadcrumb-item active"><?php echo $dataForum->forumTitle; ?></li>
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
<?php if($idForumMessageQuote != ""): ?>
    <?php
        $getQuoteForumMessage = $db->getForumMessageByIdForumMessage($idForumMessageQuote)->first();
    ?>
<style type="text/css">
    blockquote {
        background: #e0d7d7;
        border-left: 10px solid #ccc;
        margin: 1.5em 10px;
        padding: 0.5em 10px;
        quotes: "\201C""\201D""\2018""\2019";
    }
    blockquote:before {
        color: #ccc;
        content: open-quote;
        font-size: 4em;
        line-height: 0.1em;
        margin-right: 0.25em;
        vertical-align: -0.4em;
    }
    blockquote p {
        display: inline;
    }
</style>
    <div class="row">
        <div class="col-lg-12">
            <!-- Example Bar Chart Card-->
            <div class="card mb-3">
                <div class="card-header bg-info">
                    Quote Reply From <?php echo $db->getFullNameMemberByIdMember($getQuoteForumMessage->idMember); ?> at <?php echo date('d F Y H:i:s',strtotime($getQuoteForumMessage->dateTime)); ?>

                </div>
                <div class="card-body">
                    <blockquote>
                        <p>
                            <?php echo $getQuoteForumMessage->messageContent; ?>

                        </p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $idForum; ?>/submitReplyThread" class="form-horizontal">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="idForumMessageQuote" value="<?php if($idForumMessageQuote != ""): ?><?php echo $idForumMessageQuote; ?><?php endif; ?>" />
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">Title Message :</label>
        <div class="col-sm-12">
            <textarea class="form-control" name="titleNewPost"><?php echo e(old('titleNewPost')); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">Message :</label>
        <div class="col-sm-12">
            <textarea class="form-control" id="messagePost" name="messagePost"><?php echo e(old('messagePost')); ?></textarea>
            <script>
                CKEDITOR.replace( 'messagePost');
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