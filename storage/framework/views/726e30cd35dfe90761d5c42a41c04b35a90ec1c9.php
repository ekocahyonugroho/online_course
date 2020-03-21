<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Online Class</div>
            <div class="card-body">
                <div style="padding: 10px;">
                    <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/addNewOnlineClassForm'" class="btn btn-primary">Add New Online Class</button>
                </div>
                <table class="table table-bordered" id="longDataTable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Online Program Name</th>
                        <th>Course Name</th>
                        <th>Course Description</th>
                        <th>Started From</th>
                        <th>Ended At</th>
                        <th>Tuition Fee</th>
                        <th>Target Student</th>
                        <th>Mentored By</th>
                        <th>Registered Students</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($dataAvailableClass)): ?>
                        <?php
                            $no = 1;
                            $db = $Database_communication;
                        ?>
                        <?php $__currentLoopData = $dataAvailableClass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dataClassCreator = $db->getMemberData($data->CreatedByIdUser)->first();
                                $idMember = $dataClassCreator->idMember;
                                $idAuthority = $dataClassCreator->idAuthority;

                                $getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($data->idCoursesClass)->get();

                                $getOnlineClassStudent = $db->getEnrolledClassByIdClassCourse($data->idCoursesClass)->get();
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $data->idCoursesClass; ?>'" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i> Manage</button></td>

                                <?php if($data->IsOpened == "1"): ?>
                                    <td><span class="badge badge-success">Opened</span></td>
                                <?php elseif($data->IsOpened == "0"): ?>
                                    <td><span class="badge badge-danger">Closed</span></td>
                                <?php endif; ?>

                                <td><?php echo $db->getFullMemberData($idMember, $idAuthority)->first()->name; ?></td>
                                <td><?php echo date('d F Y H:i:s', strtotime($data->CreatedDate)); ?> GMT +7</td>
                                <td><?php echo $db->getAvailableEducationProgramByIdAvailableClass($data->idAvailableClass)->first()->OnlineProgramName; ?></td>
                                <td><?php echo $db->getAvailableCoursesByIdAvailableCourse($data->idAvailableCourse)->first()->nama_mata_kuliah_eng; ?></td>
                                <td><?php echo $data->CourseDescription; ?></td>
                                <td><?php echo date('d F Y H:i:s',strtotime($data->OpenedStart)); ?> GMT +7</td>
                                <td><?php echo date('d F Y H:i:s',strtotime($data->OpenedEnd)); ?> GMT +7</td>

                                <?php if($data->IsFree == "1"): ?>
                                    <td><span class="badge badge-success">FREE</span></td>
                                <?php elseif($data->IsFree == "0"): ?>
                                    <td><span class="badge badge-info">IDR <?php echo number_format( $data->CoursePrice , 2 , ',' , '.' ); ?></span></td>
                                <?php endif; ?>


                                <?php if($data->IsPublic == "1"): ?>
                                    <td>Opened For Public</td>
                                <?php elseif($data->IsPublic == "0"): ?>
                                    <td>Students Who Has ID Student Only</td>
                                <?php endif; ?>

                                <td>
                                    <?php if(count($getOnlineClassMentor) > 0): ?>
                                        <table>
                                        <?php $__currentLoopData = $getOnlineClassMentor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataMentor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo $dataMentor->nama_dosen; ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </table>
                                    <?php else: ?>
                                        NOT FOUND
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(count($getOnlineClassStudent) > 0): ?>
                                        <?php echo count($getOnlineClassStudent); ?> Students
                                    <?php else: ?>
                                        NOT FOUND
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>