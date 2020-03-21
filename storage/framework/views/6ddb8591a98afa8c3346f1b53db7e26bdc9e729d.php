<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                Edit Online Program Form</div>
            <div class="card-body">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/manageClassProgram/editOnlineProgram/<?php echo $getAvailableOnlineProgramName->idAvailableClass; ?>/submit" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Type :</label>
                        <div class="col-sm-5">
                            <select onchange="if($(this).val() == '0'){$('#onlineCourseProgram').attr('disabled','disabled')}else{$('#onlineCourseProgram').removeAttr('disabled')}" class="form-control" name="onlineCourseType" id="onlineCourseType">
                                <option <?php if($getAvailableOnlineProgramName->isDegreeProgram == "0"): ?> selected="selected" <?php endif; ?> value="0">Non Degree</option>
                                <option <?php if($getAvailableOnlineProgramName->isDegreeProgram == "1"): ?> selected="selected" <?php endif; ?> value="1">Degree</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Program :</label>
                        <div class="col-sm-5">
                            <select <?php if($getAvailableOnlineProgramName->isDegreeProgram == "0"): ?> disabled="disabled" <?php endif; ?> class="form-control" id="onlineCourseProgram" name="onlineCourseProgram">
                                <?php $__currentLoopData = $getAvailableEducationProgram; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataProgram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($getAvailableOnlineProgramName->idProgram == $dataProgram->id_program): ?> selected="selected" <?php endif; ?> value="<?php echo $dataProgram->id_program; ?>"><?php echo $dataProgram->nama_program; ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Program Name:</label>
                        <div class="col-sm-6">
                            <input type="text" value="<?php echo $getAvailableOnlineProgramName->OnlineProgramName; ?>" class="form-control" name="onlineCourseProgramName" id="onlineCourseProgramName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>