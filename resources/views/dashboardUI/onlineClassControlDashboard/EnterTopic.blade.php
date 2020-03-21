@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
    <li class="breadcrumb-item">{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</li>
    <li class="breadcrumb-item active">{!! $dataTopic->TopicName !!}</li>
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
{!! $subcontent !!}