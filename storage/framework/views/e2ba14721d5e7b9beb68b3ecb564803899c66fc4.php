<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo e(URL::to('/')); ?>/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Lecturer</li>
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
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Registered Lecturer</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px;">
                    <button id="btnAddLecturer" type="button" class="btn btn-success">Add Lecturer</button>
                </div>
                <br />
                <div class="row">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Mentored Courses</th>
                                <th>Mentored Students</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 0; ?>
                        <?php if(!empty($availableLecturer)): ?>
                            <?php $__currentLoopData = $availableLecturer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataLecturer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $no++ ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td>
                                        <?php if($dataLecturer->IsActive == '1'): ?>
                                            <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/lecturer/suspendUser/<?php echo $dataLecturer->idMember; ?>/lecturer'" class="btn btn-warning">Suspend</button>
                                        <?php else: ?>
                                            <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/lecturer/activateUser/<?php echo $dataLecturer->idMember; ?>/lecturer'" class="btn btn-success">Activate</button>
                                        <?php endif; ?>
                                        <br /><br />
                                        <button class="btn btn-danger">Remove</button>
                                    </td>
                                    <td><?php echo $dataLecturer->nama_dosen; ?></td>
                                    <td><?php echo $dataLecturer->Username; ?></td>
                                    <td><?php echo $dataLecturer->email; ?></td>
                                    <td><?php echo $dataLecturer->Registered; ?></td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer small text-muted">Data source from EcoSystem</div>
        </div>
    </div>
</div>
<script language="JavaScript">
    $('#btnAddLecturer').click(function() {
        xmlhttpReq.open("GET", "/manageMember/lecturer/addLecturer/showAvailable", true);
        xmlhttpReq.send(null);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#myLargeMultiPurposeModalsBody").html(str[1]);
                    showModals('myLargeMultiPurposeModals', 'Available Lecturer From EcoSystem Server');
                } else {
                    $("#myWarningModalsBody").html(str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    });
</script>