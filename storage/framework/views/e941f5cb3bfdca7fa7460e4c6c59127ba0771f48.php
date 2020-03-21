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
                            <th>Name</th>
                            <th>Username</th>
                            <th>Unit</th>
                            <th>Position</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;?>
                        <?php $__currentLoopData = $availableStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataStaff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $no++ ?>
                            <tr>
                                <th><?php echo $no; ?></th>
                                <th><?php echo $dataStaff->name; ?></th>
                                <th><?php echo $dataStaff->username; ?></th>
                                <th><?php echo $dataStaff->unit; ?></th>
                                <th><?php echo $dataStaff->position; ?></th>
                                <th>
                                    <a href="<?php echo URL::to('/'); ?>/manageMember/admin/addAdmin/1/<?php echo $dataStaff->username; ?>">
                                        <button type="button" class="btn btn-success">Superadmin</button>
                                    </a>
                                    <br /><br />
                                    <a href="<?php echo URL::to('/'); ?>/manageMember/admin/addAdmin/2/<?php echo $dataStaff->username; ?>">
                                        <button type="button" class="btn btn-info">Admin</button>
                                    </a>
                                </th>
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
                        <th>Authority</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Unit</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;?>
                        <?php $__currentLoopData = $registeredStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataStaff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $no++ ?>
                            <tr>
                                <th><?php echo $no; ?></th>
                                <th><?php echo strtoupper($dataStaff->Authority); ?></th>
                                <th><?php echo $dataStaff->name; ?></th>
                                <th><?php echo $dataStaff->Username; ?></th>
                                <th><?php echo $dataStaff->unit; ?></th>
                                <th><?php echo $dataStaff->position; ?></th>
                                <th><a href="<?php echo URL::to('/'); ?>/manageMember/admin/removeAdmin/<?php echo $dataStaff->idMember; ?>"><button type="button" class="btn btn-danger">Remove</button></a></th>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Staff enrolled as administrators</div>
        </div>
    </div>
</div>