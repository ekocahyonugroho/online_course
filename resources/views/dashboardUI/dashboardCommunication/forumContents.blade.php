<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 14/02/18
 * Time: 21:07
 */
?>
@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
    <li class="breadcrumb-item">{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</li>
    <li class="breadcrumb-item">Forum Discussion</li>
    <li class="breadcrumb-item active">{!! $dataForum->forumTitle !!}</li>
</ol>
@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> {{ session('error') }}
    </div>
@endif
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>!</strong> {{ session('success') }}
    </div>
@endif
@if( count( $errors ) > 0 )
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>!</strong> {{ $error }}
        </div>
    @endforeach
@endif
@foreach($post AS $dataMessage)
    <?php $countPost++; ?>
    @if($getStartForumMessage->idForumMessage == $dataMessage->idForumMessage)
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header bg-info">
                        <i class="fa fa-users"></i> {!! $dataForum->forumTitle !!}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="profile-header-container">
                                <div class="profile-header-img">
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label">{!! $FullNameCreator !!}</span>
                                    </div>
                                    <img class="img-circle" src="@if($photoCreatorDir == "") {!! asset('images/NO-IMAGE.png') !!} @else{!! URL::to('/') !!}{!! $photoCreatorDir !!}@endif" />
                                    <!-- badge -->
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label">{!! $getCreatorAccountData->Remarks !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="profile-header-container">
                                @if($dataForum->isClosed == "0")
                                    <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/openForum/{!! $idForum !!}/replyThread'" type="button" class="btn btn-info"><i class="fa fa-mail-reply"></i> Post Reply</button>
                                @else
                                    <button disabled type="button" class="btn btn-danger"><i class="fa fa-window-close"></i> Thread Closed</button>
                                @endif
                                    @if(session('idMember') != $dataForum->idMemberCreator)
                                        &nbsp;
                                        <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/openForum/{!! $idForum !!}/sendPrivateMessageToCreator'" type="button" class="btn btn-info"><i class="fa fa-inbox"></i> Send Private Message</button>
                                    @else
                                        @if($dataForum->isClosed == "0")
                                            &nbsp;
                                            <button onclick="closeThread()" type="button" class="btn btn-warning"><i class="fa fa-window-close"></i> Close This Thread</button>
                                        @endif
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        Forum started at {!! date('d F Y H:i:s', strtotime($dataForum->dateTime)) !!}
                        <br />Post(s) : {!! $db->getAllThreadMessageByIdForum($dataForum->idForum)->count() !!}
                        <br />Views : {!! $db->getForumViewDataByIdForum($dataForum->idForum)->count() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-body">
                        {!! $dataMessage->messageContent !!}
                    </div>
                    <div class="card-footer">
                        @if($getStartForumMessageFile->count() > 0)
                            <div class="row">
                                <table class="table table-hovered">
                                    @foreach($getStartForumMessageFile->get() AS $fileMessage)
                                        <tr>
                                            <td><a target="_blank" href="{!! URL::to('/') !!}{!! $fileMessage->fileURL !!}">{!! $fileMessage->fileName !!}</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                        <div class="row">
                            @if($dataForum->isClosed == "0")
                                <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/openForum/{!! $idForum !!}/replyThreadWithQuote/{!! $dataMessage->idForumMessage !!}'" type="button" class="btn btn-success"><i class="fa fa-mail-reply"></i>Reply With Quote</button>
                            @else
                                <button type="button" class="btn btn-danger"><i class="fa fa-window-close"></i> Thread Closed</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
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
                        <p class="card-text float-left">{!! $dataMessage->messageTitle !!}</p>
                        <p class="card-text float-right">{!! date('d F Y H:i:s', strtotime($dataMessage->dateTime)) !!}</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="profile-header-container">
                                <div class="profile-header-img">
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label">{!! $db->getFullNameMemberByIdMember($dataMessage->idMember) !!}</span>
                                    </div>
                                    <img class="img-circle" src="@if($photoMember == "") {!! asset('images/NO-IMAGE.png') !!} @else{!! URL::to('/') !!}{!! $photoMember !!}@endif" />
                                    <!-- badge -->
                                    <div class="rank-label-container">
                                        <span class="label label-default rank-label">{!! $getMemberAccountData->Remarks !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($dataMessage->idForumMessageQuote != "0")
                                <table>
                                    <tr>
                                        <td>
                                            <small>Quoted Reply From : {!! $db->getFullNameMemberByIdMember($db->getForumMessageByIdForumMessage($dataMessage->idForumMessageQuote)->first()->idMember) !!}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <blockquote>
                                                {!! $db->getForumMessageByIdForumMessage($dataMessage->idForumMessageQuote)->first()->messageContent !!}
                                            </blockquote>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                        <div class="row">
                            {!! $dataMessage->messageContent !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($getForumMessageFile->count() > 0)
                            <div class="row">
                                <table class="table table-hovered">
                                    @foreach($getForumMessageFile->get() AS $fileMessage)
                                        <tr>
                                            <td><a target="_blank" href="{!! URL::to('/') !!}{!! $fileMessage->fileURL !!}">{!! $fileMessage->fileName !!}</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                        <div class="row">
                            @if($dataForum->isClosed == "0")
                                <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/openForum/{!! $idForum !!}/replyThreadWithQuote/{!! $dataMessage->idForumMessage !!}'" type="button" class="btn btn-success"><i class="fa fa-mail-reply"></i>Reply With Quote</button>
                            @else
                                <button type="button" class="btn btn-danger"><i class="fa fa-window-close"></i> Thread Closed</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
<div class="col-md-6">
    {!! $post->links("pagination::bootstrap-4") !!}
</div>
<script language="JavaScript">
    function closeThread(){
        var isConfirm = confirm("Are you sure to close this thread?");

        if(isConfirm){
            location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/openForum/{!! $idForum !!}/closeThread'
        }
    }
</script>