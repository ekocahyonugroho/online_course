<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdTopic($idTopic)->get();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h2>Topic : <?php echo $dataTopic->TopicName; ?></h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Sub Topics</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Sub Topic Name</th>
                        <th>Issue Covered</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <?php if(count($dataSubTopic) == 0): ?>
                        <tr class="table-danger"><td colspan="6"><center>NO SUB TOPICS</center></td></tr>
                    <?php else: ?>
                        <?php $__currentLoopData = $dataSubTopic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $SubTopic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <table border="0">
                                        <tr>
                                            <td><button onclick="location.href='<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $SubTopic->idSubTopic; ?>'" class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i></button></td>
                                        </tr>
                                    </table>
                                </td>
                                <td><?php echo $SubTopic->subTopicName; ?></td>
                                <td><?php echo $SubTopic->subTopicIssue; ?></td>
                                <td><?php echo $SubTopic->subTopicDescription; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
