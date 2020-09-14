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

if($idAuthorityCreator == "3"){
  $creatorName = $dataCreator->nama_dosen;
}else{
    $creatorName = $dataCreator->name;
}

$dataTopic = $db->getCoursesClassTopicByIdCoursesClass($idCoursesClass)->get();

$noTopic = 1;
?>
<ol class="breadcrumb">
  <li class="breadcrumb-item">Manage Online Class</li>
  <li class="breadcrumb-item active">{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</li>
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
  <div class="col-lg-8">
    <!-- Example Bar Chart Card-->
    <div class="card mb-3">
      <div class="card-header bg-info">
        <i class="fa fa-camera-o"></i> Opening</div>
      <div class="card-body">
        <td rowspan='2'>
          {!! $userInterface->showVideoThumbnailOnCourseClassDescription($idCoursesClass, "500", "250") !!}
        </td>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <!-- Example Bar Chart Card-->
    <div class="card mb-3">
      <div class="card-header bg-info">
        <i class="fa fa-user-circle-o"></i> Detail Schedule and Fee</div>
      <div class="card-body">
        <table class='table table-bordered'>
          <tr>
            <td width='1%'><i class="fa fa-book" aria-hidden="true"></i></td>
            <td width='49%'>Course Number</td>
            <td width='50%'>{!! $dataOnlineClass->CourseCode !!}</td>
          </tr>
          <tr>
            <td width='1%'><i class="fa fa-calendar" aria-hidden="true"></i></td>
            <td width='49%'>Classes Start</td>
            <td width='50%'>{!! date('d F Y H:i:s', strtotime($dataOnlineClass->OpenedStart)) !!} GMT +7</td>
          </tr>
          <tr>
            <td width='1%'><i class="fa fa-calendar" aria-hidden="true"></i></td>
            <td width='49%'>Classes End</td>
            <td width='50%'>{!! date('d F Y H:i:s', strtotime($dataOnlineClass->OpenedEnd)) !!} GMT +7</td>
          </tr>
          <tr>
            <td width='1%'><i class="fa fa-money" aria-hidden="true"></i></td>
            <td width='49%'>Tuition Fee</td>
            <td width='50%'><b>{!! $userInterface->showCourseClassPrice($idCoursesClass) !!}</b></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-8">
    <!-- Example Bar Chart Card-->
    <div class="card mb-3">
      <div class="card-header bg-info">
        <i class="fa fa-users"></i> General Info</div>
      <div class="card-body">
        <div class="row">
          <table width="100%">
            <tr>
              <td>
                <h1>{!! $dataOnlineClass->nama_mata_kuliah_eng !!}</h1>
                <br /><h4><i>{!! $dataOnlineClass->nama_mata_kuliah_id !!}</i></h4>
                <br />&nbsp;
                <br><h6><i><b>Created by : {!! $creatorName !!} at {!! date('d M Y H:i:s', strtotime($dataOnlineClass->CreatedDate)) !!} GMT +7</b></i></h6>
              </td>
            </tr>
            <tr>
              <td>
                @if(empty($dataOnlineClass->CourseDescription))
                  <div class="alert alert-danger">
                    <strong>!</strong> NO DESCRIPTION YET
                  </div>
                @else
                  {!! $dataOnlineClass->CourseDescription !!}
                @endif
              </td>
            </tr>
          </table>
        </div>
        &nbsp
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <!-- Example Bar Chart Card-->
    <div class="card mb-3">
      <div class="card-header bg-info">
        <i class="fa fa-user-circle-o"></i> Occupation</div>
      <div class="card-body">
        <div class="row">
          <table class="table table-bordered">
            <thead>
            <tr class="table-info">
              <th><center>MENTORS</center></th>
              <th><center>STUDENT NUMBER</center></th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>
                @if(count($getOnlineClassMentor) > 0)
                  <table width="100%">
                    @foreach($getOnlineClassMentor AS $dataMentor)
                      <tr>
                        <td class="table-info">{!! $dataMentor->nama_dosen !!}</td>
                      </tr>
                    @endforeach
                  </table>
                @else
                  <center>NOT FOUND</center>
                @endif
              </td>
              <td>
                @if(count($getOnlineClassStudent) > 0)
                  {!! count($getOnlineClassStudent) !!} Students
                @else
                  <center>NOT FOUND</center>
                @endif
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <!-- Example Bar Chart Card-->
    <div class="card mb-3">
      <div class="card-header bg-success">
        <i class="fa fa-user-circle-o"></i> Topics</div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
          <tr class="table-info">
            <th>No.</th>
            <th><center>Topic Name</center></th>
          </tr>
          </thead>
          <tbody>
          @if(count($dataTopic) == 0)
            <tr class="table-danger"><td colspan="2"><center>NO TOPICS</center></td></tr>
          @else
            @foreach($dataTopic AS $topic)
              <tr>
                <td>{!! $noTopic++ !!}</td>
                <td><a href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $topic->idTopic !!}">{!! $topic->TopicName !!}</a></td>
              </tr>
            @endforeach
          @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
{!! $subcontent !!}