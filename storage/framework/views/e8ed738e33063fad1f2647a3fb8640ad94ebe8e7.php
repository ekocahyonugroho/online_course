<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 14/02/18
 * Time: 21:07
 */
?>
<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

$idAuthority = $db->getAccountDataByIdMember(session('idMember'))->first()->idAuthority;

$dataMember = $db->getFullMemberData(session('idMember'), $idAuthority)->first();

$Fullname = $db->getFullNameMemberByIdMember(session('idMember'));

$dataForum = $getForumData->first();

$getCreatorAccountData = $db->getMemberData($dataForum->idMemberCreator)->first();
$idAuthorityCreator = $getCreatorAccountData->idAuthority;
$FullNameCreator= $db->getFullNameMemberByIdMember($dataForum->idMemberCreator);
$photoCreatorDir = "";

if($db->getUserPhotoByIdMember($dataForum->idMemberCreator)->count() > 0) {
    $photoCreatorDir = $db->getUserPhotoByIdMember($dataForum->idMemberCreator)->first()->PhotoDirectory;
}

$getStartForumMessage = $db->getStartThreadMessageByIdForumAndIdMember($idForum, $dataForum->idMemberCreator);
$getStartForumMessageFile = $db->getStartThreadMessageFileByIdForumMessage($getStartForumMessage->idForumMessage);
$getForumMessages = $db->getAllThreadMessageByIdForum($idForum);
$countPost = 0;
?>
<style type="text/css">
    .profile-header-container{
        margin: 0 auto;
        text-align: center;
    }

    .profile-header-img {
        padding: 54px;
    }

    .profile-header-img > img.img-circle {
        width: 120px;
        height: 120px;
        border: 2px solid #51D2B7;
    }

    .profile-header {
        margin-top: 43px;
    }

    /**
     * Ranking component
     */
    .rank-label-container {
        margin-top: -19px;
        /* z-index: 1000; */
        text-align: center;
    }

    .label.label-default.rank-label {
        background-color: rgb(81, 210, 183);
        padding: 5px 10px 5px 10px;
        border-radius: 27px;
    }
</style>
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
<?php $__currentLoopData = $post; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $countPost++; ?>
    <?php if($getStartForumMessage->idForumMessage == $dataMessage->idForumMessage): ?>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header bg-info">
                        <i class="fa fa-users"></i> <?php echo $dataForum->forumTitle; ?></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="profile-header-container">
                                <div class="profile-header-img">
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label"><?php echo $FullNameCreator; ?></span>
                                    </div>
                                    <img class="img-circle" src="<?php if($photoCreatorDir == ""): ?> <?php echo asset('images/NO-IMAGE.png'); ?> <?php else: ?><?php echo URL::to('/'); ?><?php echo $photoCreatorDir; ?><?php endif; ?>" />
                                    <!-- badge -->
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label"><?php echo $getCreatorAccountData->Remarks; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="profile-header-container">
                                <?php if($dataForum->isClosed == "0"): ?>
                                    <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $idForum; ?>/replyThread'" type="button" class="btn btn-info"><i class="fa fa-mail-reply"></i> Post Reply</button>
                                <?php else: ?>
                                    <button disabled type="button" class="btn btn-danger"><i class="fa fa-window-close"></i> Thread Closed</button>
                                <?php endif; ?>
                                    <?php if(session('idMember') != $dataForum->idMemberCreator): ?>
                                        &nbsp;
                                        <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $idForum; ?>/sendPrivateMessageToCreator'" type="button" class="btn btn-info"><i class="fa fa-inbox"></i> Send Private Message</button>
                                    <?php else: ?>
                                        <?php if($dataForum->isClosed == "0"): ?>
                                            &nbsp;
                                            <button onclick="closeThread()" type="button" class="btn btn-warning"><i class="fa fa-window-close"></i> Close This Thread</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        Forum started at <?php echo date('d F Y H:i:s', strtotime($dataForum->dateTime)); ?>

                        <br />Post(s) : <?php echo $db->getAllThreadMessageByIdForum($dataForum->idForum)->count(); ?>

                        <br />Views : <?php echo $db->getForumViewDataByIdForum($dataForum->idForum)->count(); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-body">
                        <?php echo $dataMessage->messageContent; ?>

                    </div>
                    <div class="card-footer">
                        <?php if($getStartForumMessageFile->count() > 0): ?>
                            <div class="row">
                                <table class="table table-hovered">
                                    <?php $__currentLoopData = $getStartForumMessageFile->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fileMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><a target="_blank" href="<?php echo URL::to('/'); ?><?php echo $fileMessage->fileURL; ?>"><?php echo $fileMessage->fileName; ?></a></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <?php if($dataForum->isClosed == "0"): ?>
                                <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $idForum; ?>/replyThreadWithQuote/<?php echo $dataMessage->idForumMessage; ?>'" type="button" class="btn btn-success"><i class="fa fa-mail-reply"></i>Reply With Quote</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger"><i class="fa fa-window-close"></i> Thread Closed</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php
        $photoMember = "";
        $getForumMessageFile = $db->getStartThreadMessageFileByIdForumMessage($dataMessage->idForumMessage);
        if($db->getUserPhotoByIdMember($dataMessage->idMember)->count() > 0) {
            $photoMember = $db->getUserPhotoByIdMember($dataMessage->idMember)->first()->PhotoDirectory;
        }
        $getMemberAccountData = $db->getMemberData($dataMessage->idMember)->first();
        ?>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header bg-info">
                        <p class="card-text float-left"><?php echo $dataMessage->messageTitle; ?></p>
                        <p class="card-text float-right"><?php echo date('d F Y H:i:s', strtotime($dataMessage->dateTime)); ?></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="profile-header-container">
                                <div class="profile-header-img">
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label"><?php echo $db->getFullNameMemberByIdMember($dataMessage->idMember); ?></span>
                                    </div>
                                    <img class="img-circle" src="<?php if($photoMember == ""): ?> <?php echo asset('images/NO-IMAGE.png'); ?> <?php else: ?><?php echo URL::to('/'); ?><?php echo $photoMember; ?><?php endif; ?>" />
                                    <!-- badge -->
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label"><?php echo $getMemberAccountData->Remarks; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if($dataMessage->idForumMessageQuote != "0"): ?>
                                <table>
                                    <tr>
                                        <td>
                                            <small>Quoted Reply From : <?php echo $db->getFullNameMemberByIdMember($db->getForumMessageByIdForumMessage($dataMessage->idForumMessageQuote)->first()->idMember); ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <blockquote>
                                                <?php echo $db->getForumMessageByIdForumMessage($dataMessage->idForumMessageQuote)->first()->messageContent; ?>

                                            </blockquote>
                                        </td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <?php echo $dataMessage->messageContent; ?>

                        </div>
                    </div>
                    <div class="card-footer">
                        <?php if($getForumMessageFile->count() > 0): ?>
                            <div class="row">
                                <table class="table table-hovered">
                                    <?php $__currentLoopData = $getForumMessageFile->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fileMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><a target="_blank" href="<?php echo URL::to('/'); ?><?php echo $fileMessage->fileURL; ?>"><?php echo $fileMessage->fileName; ?></a></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <?php if($dataForum->isClosed == "0"): ?>
                                <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $idForum; ?>/replyThreadWithQuote/<?php echo $dataMessage->idForumMessage; ?>'" type="button" class="btn btn-success"><i class="fa fa-mail-reply"></i>Reply With Quote</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger"><i class="fa fa-window-close"></i> Thread Closed</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<div class="col-md-6">
    <?php echo $post->links("pagination::bootstrap-4"); ?>

</div>
<script language="JavaScript">
    function closeThread(){
        var isConfirm = confirm("Are you sure to close this thread?");

        if(isConfirm){
            location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum/openForum/<?php echo $idForum; ?>/closeThread'
        }
    }
</script>