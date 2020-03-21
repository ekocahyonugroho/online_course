<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php
        $db = $Database_communication;
?>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                Add New Online Class Form</div>
            <div class="card-body">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/addNewOnlineClassForm/submit" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Online Class Program :</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="onlineClassProgram" id="onlineClassProgram">
                                <?php
                                    $getAvailableEducationProgram = $db->getAvailableProgram()->get();
                                ?>

                                    <?php $__currentLoopData = $getAvailableEducationProgram; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo $data->idAvailableClass; ?>"><?php echo $data->OnlineProgramName; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Course Name :</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="courseCode" id="courseCode">
                                <?php
                                $getAvailableCourse = $db->getAvailableCourse()->get();
                                ?>

                                <?php $__currentLoopData = $getAvailableCourse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($data->isRegisteredInCurriculum == "1"): ?>
                                            <?php
                                            $detailedData = $db->getAvailableCoursesClassGeneralDataByCourseCode($data->CourseCode)->first();
                                            $CourseName = $detailedData->nama_mata_kuliah_eng;
                                            ?>
                                        <?php elseif($data->isRegisteredInCurriculum == "0"): ?>
                                            <?php
                                            $CourseName = $data->CourseName;
                                            ?>
                                        <?php endif; ?>

                                        <option value="<?php echo $data->idAvailableCourse; ?>"><?php echo $data->CourseCode; ?> - <?php echo $CourseName; ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Started From :</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="startedFrom" id="startedFrom" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Ended At :</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="endedAt" id="endedAt" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Target Participant :</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="targetStudent" id="targetStudent">
                                <option value="1">Public</option>
                                <option value="0">Students who have ID Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Tuition Fee in IDR :</label>
                        <div class="col-sm-4">
                            <input type="number" placeholder="Keep empty if FREE" class="form-control" name="tuitionFee" id="tuitionFee" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
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
            </div>
        </div>
    </div>
</div>