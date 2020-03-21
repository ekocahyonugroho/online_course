<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
?>
<form method="post" action="<?php echo URL::to('/'); ?>/ServerSide/ManageOnlineClass/editOnlineClassSchedule/submit" class="form-horizontal">
  <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
  <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
  <div class="form-group">
    <label class="control-label col-sm-4" for="email">Started From :</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" value="<?php echo $dataOnlineClass->OpenedStart; ?>" name="startedFrom" id="startedFrom" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4" for="email">Ended At :</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" value="<?php echo $dataOnlineClass->OpenedEnd; ?>" name="endedAt" id="endedAt" />
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-10">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
<script language="JavaScript">
    $(document).ready(function() {
        loadDateTimePicker('startedFrom','yyyy-mm-dd hh:ii');
        loadDateTimePicker('endedAt','yyyy-mm-dd hh:ii');
    });
</script>