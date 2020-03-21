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
<form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/managePrivateMessage/submitNewPrivateMessage" class="form-horizontal">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">To :</label>
        <div class="col-sm-4">
            <input value="@if(!empty($destinationUsername)) {!! $destinationUsername !!} @else{{ old('destination') }}@endif" type="text" class="form-control" onkeypress="findUserDestinationPrivateMessage()" id="newPrivateMessageTo" name="destination" placeholder="Destination Username" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">Subject :</label>
        <div class="col-sm-6">
            <input value="{{ old('subject') }}" type="text" class="form-control" id="newPrivateMessageSubject" name="subject" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-4" for="email">Message :</label>
        <div class="col-sm-12">
            <textarea class="form-control" id="newPrivateMessage" name="message">{{ old('message') }}</textarea>
            <script>
                CKEDITOR.replace( 'newPrivateMessage');
            </script>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </div>
</form>