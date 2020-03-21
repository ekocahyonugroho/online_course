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

$getForumList = $db->getForumListByIdCoursesClass($idCoursesClass);
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage Online Course</li>
    <li class="breadcrumb-item active">{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</li>
    <li class="breadcrumb-item active">Forum Discussion</li>
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
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-users"></i> Forum Threads</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px; padding-bottom: 10px;">
                    <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/createNewThread'" class="btn btn-primary">Create New Thread</button>
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
                    @if($getForumList->count() > 0)
                        @foreach($getForumList->get() AS $dataForumList)
                            <?php
                                $lastPost = $db->getLatestThreadMessageByIdForum($dataForumList->idForum);
                            ?>
                            <tr>
                                <td>
                                    <a href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum/openForum/{!! $dataForumList->idForum !!}">{!! $dataForumList->forumTitle !!}</a>
                                    <br />Created at {!! date('d F Y H:i:s', strtotime($dataForumList->dateTime)) !!}
                                    <br />by <b>{!! $db->getFullNameMemberByIdMember($dataForumList->idMemberCreator) !!}</b>
                                </td>
                                <td>
                                    {!! date('d F Y H:i:s', strtotime($lastPost->dateTime)) !!}
                                    <br />by <b>{!! $db->getFullNameMemberByIdMember($lastPost->idMember) !!}</b>
                                </td>
                                <td>
                                    Post(s) : {!! $db->getAllThreadMessageByIdForum($dataForumList->idForum)->count() !!}
                                    <br />Views : {!! $db->getForumViewDataByIdForum($dataForumList->idForum)->count() !!}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>