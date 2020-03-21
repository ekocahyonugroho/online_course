<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdCoursesClass($idCoursesClass)->get();

$noTopic = 1;
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage Online Class</li>
    <li class="breadcrumb-item active"><?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></li>
</ol>
<div class="row">
    <div class="col-lg-4">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Add New Topic</div>
            <div class="card-body">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/addNewTopicForm/submit">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Topic Name :</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="newTopicName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
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
