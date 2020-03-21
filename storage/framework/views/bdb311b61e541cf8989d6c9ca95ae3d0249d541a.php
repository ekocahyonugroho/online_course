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
    <div class="col-lg-4">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Add New Sub Topics</div>
            <div class="card-body">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/submitNewSubTopic">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Type :</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="subTopicType">
                                <option value="1">Materials Delivery</option>
                                <option value="2">Assignment</option>
                                <option value="3">Exam</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Sub Topic Name :</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="subTopicName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Issue Covered :</label>
                        <div class="col-sm-12">
                            <textarea type="text" class="form-control" name="subTopicIssue"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="subTopicDescription"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <script>
                    CKEDITOR.replace( 'subTopicIssue');
                    CKEDITOR.replace( 'subTopicDescription');
                </script>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
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
                                        <td><button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $SubTopic->idSubTopic; ?>'" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i></button></td>
                                        <td><button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $SubTopic->idSubTopic; ?>/delete'" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
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
