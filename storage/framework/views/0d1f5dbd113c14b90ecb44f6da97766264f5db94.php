<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();
$getAvailableMentor = $db->getAvailableMentorForOnlineClassByIdCoursesClass($idCoursesClass)->get();
?>
<div class="row">
    <div class="col-lg-12">
    <form method="post" action="<?php echo URL::to('/'); ?>/ServerSide/ManageOnlineClass/addOnlineClassMentor/submit" class="form-horizontal">
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
        <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
        <div class="form-group">
            <label class="control-label col-sm-4" for="email">Available lecturers :</label>
            <div class="col-sm-6">
                <select class="form-control" name="idMentor">
                    <?php $__currentLoopData = $getAvailableMentor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo $option->idMember; ?>"><?php echo $option->nama_dosen; ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
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
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Mentors List - <?php echo $dataOnlineClass->nama_mata_kuliah_eng; ?></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <?php if(count($getOnlineClassMentor) > 0): ?>
                        <?php $__currentLoopData = $getOnlineClassMentor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataMentor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo URL::to('/'); ?>/ServerSide/ManageOnlineClass/addOnlineClassMentor/deleteMentor/<?php echo $idCoursesClass; ?>/<?php echo $dataMentor->idMember; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a> <?php echo $dataMentor->nama_dosen; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr><center>NOT FOUND</center></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>