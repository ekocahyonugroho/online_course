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
  <li class="breadcrumb-item active"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
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
                <h1><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></h1>
                <br /><h4><i><?php echo $dataOnlineClass->nama_mata_kuliah_id; ?></i></h4>
                <br />&nbsp;
                <br><h6><i><b>Created by : <?php echo $creatorName; ?> at <?php echo date('d M Y H:i:s', strtotime($dataOnlineClass->CreatedDate)); ?> GMT +7</b></i></h6>
              </td>
              <td rowspan='2'>
                <?php echo $userInterface->showVideoThumbnailOnCourseClassDescription($idCoursesClass, "500", "250"); ?>

              </td>
            </tr>
            <tr>
              <td>
                <?php if(empty($dataOnlineClass->CourseDescription)): ?>
                  <div class="alert alert-danger">
                    <strong>!</strong> NO DESCRIPTION YET
                  </div>
                <?php else: ?>
                  <?php echo $dataOnlineClass->CourseDescription; ?>

                <?php endif; ?>
              </td>
            </tr>
          </table>
        </div>
        &nbsp;
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
                      <?php if(count($getOnlineClassMentor) > 0): ?>
                        <table width="100%">
                          <?php $__currentLoopData = $getOnlineClassMentor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataMentor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                              <td class="table-info"><?php echo $dataMentor->nama_dosen; ?></td>
                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                      <?php else: ?>
                        <center>NOT FOUND</center>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if(count($getOnlineClassStudent) > 0): ?>
                        <?php echo count($getOnlineClassStudent); ?> Students
                      <?php else: ?>
                        <center>NOT FOUND</center>
                      <?php endif; ?>
                    </td>
                  </tr>
                </tbody>
            </table>
        </div>
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
            <td width='50%'><?php echo $dataOnlineClass->CourseCode; ?></td>
          </tr>
          <tr>
            <td width='1%'><i class="fa fa-calendar" aria-hidden="true"></i></td>
            <td width='49%'>Classes Start</td>
            <td width='50%'><?php echo date('d F Y H:i:s', strtotime($dataOnlineClass->OpenedStart)); ?> GMT +7</td>
          </tr>
          <tr>
            <td width='1%'><i class="fa fa-calendar" aria-hidden="true"></i></td>
            <td width='49%'>Classes End</td>
            <td width='50%'><?php echo date('d F Y H:i:s', strtotime($dataOnlineClass->OpenedEnd)); ?> GMT +7</td>
          </tr>
          <tr>
            <td width='1%'><i class="fa fa-money" aria-hidden="true"></i></td>
            <td width='49%'>Tuition Fee</td>
            <td width='50%'><b><?php echo $userInterface->showCourseClassPrice($idCoursesClass); ?></b></td>
          </tr>
        </table>
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
          <?php if(count($dataTopic) == 0): ?>
            <tr class="table-danger"><td colspan="2"><center>NO TOPICS</center></td></tr>
          <?php else: ?>
            <?php $__currentLoopData = $dataTopic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo $noTopic++; ?></td>
                <td><a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $topic->idTopic; ?>"><?php echo $topic->TopicName; ?></a></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php echo $subcontent; ?>