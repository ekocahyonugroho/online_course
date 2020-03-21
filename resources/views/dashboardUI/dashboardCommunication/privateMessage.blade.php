<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 25/09/17
 * Time: 14:08
 */
?>
@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
    <li class="breadcrumb-item active">{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</li>
    <li class="breadcrumb-item active">Private Message</li>
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
<div class="row">
    <div class="col-lg-3">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-users"></i> Private Message</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px; padding-bottom: 10px;">
                    <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/managePrivateMessage/composeNewPrivateMessage'" class="btn btn-primary">Compose New Message</button>
                </div>
                @if($userPrivateMessageData->count() > 0)
                    @foreach($userPrivateMessageData->get() AS $dataPrivateMessage)
                        <?php
                        $isSelected = 0;
                        $stmtUnreadMessage = $db->getUnreadPrivateMessageByIdPrivateMessageAndIdMember($dataPrivateMessage->idPrivateMessage, session('idMember'));
                        $countUnreadMessage = $stmtUnreadMessage->count();
                        ?>
                        @if($dataPrivateMessage->idMember1 == session('idMember'))
                            @if(isset($messages))
                                @if($dataPrivateMessage->idPrivateMessage == $idPrivateMessage)
                                    <?php
                                    $isSelected++;
                                    ?>
                                @endif
                            @endif
                            <div class="card @if($isSelected > 0) text-white bg-success mb-3 @endif">
                                <div class="card-body">
                                    <table width="100%">
                                        <tr>
                                            <td width="90%"><strong style="font-family: 'Century Gothic', Arial; font-size: 30px;">{!! $db->getAccountDataByIdMember($dataPrivateMessage->idMember2)->first()->Username !!}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{!! $db->getPrivateMessageDataByIdPrivateMessage($dataPrivateMessage->idPrivateMessage)->first()->titleMessage !!}</td>
                                        </tr>
                                        <tr>
                                            <td><small>Last message : {!! $db->getLatestPrivateMessageContent($dataPrivateMessage->idPrivateMessage)->dateTime !!}</small></td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td><button type="button" onclick="viewPrivateMessageContents({!! $dataPrivateMessage->idPrivateMessage !!})" class="btn btn-primary btn-sm">@if($countUnreadMessage == 0) View @else {!! $countUnreadMessage !!} New Messages @endif</button></td>
                                            <td><button type="button" onclick="deletePrivateMessageContents({!! $dataPrivateMessage->idPrivateMessage !!})" class="btn btn-danger btn-sm">Delete</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @elseif($dataPrivateMessage->idMember2 == session('idMember'))
                            @if(isset($messages))
                                @if($dataPrivateMessage->idPrivateMessage == $idPrivateMessage)
                                    <?php
                                    $isSelected++;
                                    ?>
                                @endif
                            @endif
                                <div class="card @if($isSelected > 0) text-white bg-success mb-3 @endif">
                                    <div class="card-body">
                                        <table width="100%">
                                            <tr>
                                                <td width="90%"><strong style="font-family: 'Century Gothic', Arial; font-size: 30px;">{!! $db->getAccountDataByIdMember($dataPrivateMessage->idMember1)->first()->Username !!}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>{!! $db->getPrivateMessageDataByIdPrivateMessage($dataPrivateMessage->idPrivateMessage)->first()->titleMessage !!}</td>
                                            </tr>
                                            <tr>
                                                <td><small>Last message : {!! $db->getLatestPrivateMessageContent($dataPrivateMessage->idPrivateMessage)->dateTime !!}</small></td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td><button type="button" onclick="viewPrivateMessageContents({!! $dataPrivateMessage->idPrivateMessage !!})" class="btn btn-primary btn-sm">@if($countUnreadMessage == 0) View @else {!! $countUnreadMessage !!} New Messages @endif</button></td>
                                                <td><button type="button" onclick="deletePrivateMessageContents({!! $dataPrivateMessage->idPrivateMessage !!})" class="btn btn-danger btn-sm">Delete</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                        @endif
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body">
                            <center>NO MESSAGE</center>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-users"></i> Message Contents</div>
            <div class="card-body">
                @if(isset($messages))
                    {!! $messages !!}
                @endif
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    function viewPrivateMessageContents(idPrivateMessage){
        location.href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/1/managePrivateMessage/showMessage/"+idPrivateMessage;
    }

    function deletePrivateMessageContents(idPrivateMessage){
        var isConfirm = confirm('Are you sure to end this conversation?');

        if(isConfirm){
            location.href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/1/managePrivateMessage/deleteMessage/"+idPrivateMessage;
        }
    }
</script>
