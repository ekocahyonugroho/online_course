<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo e(URL::to('/')); ?>/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Administrators</li>
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
    <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Staff</div>
            <div class="card-body">
                <table class="table table-bordered" id="availableStaffTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Unit</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;?>
                        <?php $__currentLoopData = $availableStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataStaff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $no++ ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td>
                                    <a href="<?php echo URL::to('/'); ?>/manageMember/admin/addAdmin/1/<?php echo $dataStaff->username; ?>">
                                        <button type="button" class="btn btn-success">Superadmin</button>
                                    </a>
                                    <br /><br />
                                    <a href="<?php echo URL::to('/'); ?>/manageMember/admin/addAdmin/2/<?php echo $dataStaff->username; ?>">
                                        <button type="button" class="btn btn-info">Admin</button>
                                    </a>
                                </td>
                                <td><?php echo $dataStaff->name; ?></td>
                                <td><?php echo $dataStaff->username; ?></td>
                                <td><?php echo $dataStaff->unit; ?></td>
                                <td><?php echo $dataStaff->position; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Data Source from EcoSystem</div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-user-circle-o"></i> Registered Staff</div>
            <div class="card-body">
                <table class="table table-bordered" id="registeredStaffTable">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Action</th>
                        <th>Authority</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Unit</th>
                        <th>Position</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;?>
                        <?php $__currentLoopData = $registeredStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataStaff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $no++ ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td>
                                    <?php if($dataStaff->IsActive == '1'): ?>
                                        <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/admin/suspendUser/<?php echo $dataStaff->idMember; ?>/admin'" class="btn btn-warning">Suspend</button>
                                    <?php else: ?>
                                        <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/admin/activateUser/<?php echo $dataStaff->idMember; ?>/admin'" class="btn btn-success">Activate</button>
                                    <?php endif; ?>
                                    <br /><br />
                                    <a href="<?php echo URL::to('/'); ?>/manageMember/admin/removeAdmin/<?php echo $dataStaff->idMember; ?>">
                                        <button type="button" class="btn btn-danger">Remove</button>
                                    </a>
                                </td>
                                <td><?php echo strtoupper($dataStaff->Authority); ?></td>
                                <td><?php echo $dataStaff->name; ?></td>
                                <td><?php echo $dataStaff->Username; ?></td>
                                <td><?php echo $dataStaff->unit; ?></td>
                                <td><?php echo $dataStaff->position; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Staff enrolled as administrators</div>
        </div>
    </div>
</div>