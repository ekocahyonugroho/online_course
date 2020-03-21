<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Program for Online Course</div>
            <div class="card-body">
                <div style="padding: 10px;">
                    <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/manageClassProgram/addOnlineProgram'" class="btn btn-primary">Add Online Program</button>
                </div>
                <table class="table table-bordered" id="shortDataTable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Online Course Program Name</th>
                        <th>Opened Course Number</th>
                        <th>Joined Students Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($dataAvailableProgram)): ?>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $dataAvailableProgram; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/manageClassProgram/editOnlineProgram/<?php echo $data->idAvailableClass; ?>'" class="btn btn-warning">Edit</button><br/><br/><button class="btn btn-danger">Delete</button></td>
                                <?php if($data->isDegreeProgram == '0'): ?>
                                    <td>Non Degree Program</td>
                                <?php elseif($data->isDegreeProgram == '1'): ?>
                                    <td>Degree Program</td>
                                <?php endif; ?>
                                <td><?php echo $data->OnlineProgramName; ?></td>
                                <td><?php echo $Database_communication->getOpenedCourseClassByIdAvailableClass($data->idAvailableClass)->count(); ?></td>
                                <td>0</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>