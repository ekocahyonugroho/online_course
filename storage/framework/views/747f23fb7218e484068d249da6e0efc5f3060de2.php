<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo e(URL::to('/')); ?>/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Public</li>
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
            <?php if(isset($waitingConfirmation)): ?>
                <div class="card-header">
                    <i class="fa fa-user-circle-o"></i> Waiting Confirmation</div>
                <div class="card-body">
                    <table class="table table-bordered" id="longDataTable">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Register Date</th>
                            <th>Client Browser</th>
                            <th>Client IP Address</th>
                            <th>Client Location</th>
                            <th>Gender</th>
                            <th>Birth Place</th>
                            <th>Birth Date</th>
                            <th>Nationality</th>
                            <th>Phone Number</th>
                            <th>Highest Education</th>
                            <th>Highest Education Institution</th>
                            <th>Working Field</th>
                            <th>Working Position</th>
                            <th>Working Institution</th>
                            <th>Working Experience (Years)</th>
                            <th>Is Subscription</th>
                            <th>Interested Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $waitingConfirmation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/public/resendVerificationEmail/<?php echo $data->idMember; ?>'" class="btn btn-warning">Resend Verification Email</button><br /><br /><button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/public/removeUnverifiedUser/<?php echo $data->idMember; ?>'" class="btn btn-danger">Remove</button></td>
                                <td><?php echo $data->nameFirst." ".$data->nameLast; ?></td>
                                <td><?php echo $data->Username; ?></td>
                                <td><?php echo $data->emailAddress; ?></td>
                                <td><?php echo $data->registerDate; ?> GMT +7</td>
                                <td><?php echo $data->clientBrowser; ?></td>
                                <td><?php echo $data->clientIPaddress; ?></td>
                                <td><?php echo $data->clientLocation; ?></td>
                                <td><?php echo $data->gender; ?></td>
                                <td><?php echo $data->birthPlace; ?></td>
                                <td><?php echo $data->birthDate; ?></td>
                                <td><?php echo $data->nationality; ?></td>
                                <td><?php echo $data->phoneNumber; ?></td>
                                <td><?php echo $data->highestEducation; ?></td>
                                <td><?php echo $data->highestEducationInstitution; ?></td>
                                <td><?php echo $data->workingField; ?></td>
                                <td><?php echo $data->workingPosition; ?></td>
                                <td><?php echo $data->workingInstitution; ?></td>
                                <td><?php echo $data->workingExperience; ?></td>
                                <td><?php echo $data->isSubscription; ?></td>
                                <td><?php echo $data->interestedReason; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php elseif(isset($confirmationReport)): ?>
                        <div class="card-header">
                            <i class="fa fa-user-circle-o"></i> Verified Member</div>
                        <div class="card-body">
                            <table class="table table-bordered" id="longDataTable">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Taken Courses</th>
                                    <th>Completed Courses</th>
                                    <th>Gender</th>
                                    <th>Birth Place</th>
                                    <th>Birth Date</th>
                                    <th>Nationality</th>
                                    <th>Phone Number</th>
                                    <th>Highest Education</th>
                                    <th>Highest Education Institution</th>
                                    <th>Working Field</th>
                                    <th>Working Position</th>
                                    <th>Working Institution</th>
                                    <th>Working Experience (Years)</th>
                                    <th>Is Subscription</th>
                                    <th>Interested Reason</th>
                                </tr>
                                </thead>
                                <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $confirmationReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/public/editPublicUser/<?php echo $data->idMember; ?>'" class="btn btn-info">Edit</button>
                                    <br /><br />
                                    <?php if($data->IsActive == '1'): ?>
                                        <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/public/suspendUser/<?php echo $data->idMember; ?>/public'" class="btn btn-warning">Suspend</button>
                                    <?php else: ?>
                                        <button onclick="location.href='<?php echo URL::to('/'); ?>/manageMember/public/activateUser/<?php echo $data->idMember; ?>/public'" class="btn btn-success">Activate</button>
                                    <?php endif; ?>
                                    <br /><br />
                                    <form method="post" action="<?php echo URL::to('/'); ?>/manageMember/public/deleteUser/<?php echo $data->idMember; ?>/public">
                                        <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                                        <button type="submit" id="btnRemovePublicUser" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                                <td><?php echo $data->nameFirst." ".$data->nameLast; ?></td>
                                <td><?php echo $data->Username; ?></td>
                                <td><?php echo $data->emailAddress; ?></td>
                                <td>0</td>
                                <td>0</td>
                                <td><?php echo $data->gender; ?></td>
                                <td><?php echo $data->birthPlace; ?></td>
                                <td><?php echo $data->birthDate; ?></td>
                                <td><?php echo $data->nationality; ?></td>
                                <td><?php echo $data->phoneNumber; ?></td>
                                <td><?php echo $data->highestEducation; ?></td>
                                <td><?php echo $data->highestEducationInstitution; ?></td>
                                <td><?php echo $data->workingField; ?></td>
                                <td><?php echo $data->workingPosition; ?></td>
                                <td><?php echo $data->workingInstitution; ?></td>
                                <td><?php echo $data->workingExperience; ?></td>
                                <td><?php echo $data->isSubscription; ?></td>
                                <td><?php echo $data->interestedReason; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Public member from Open Registration</div>
        </div>
    </div>
</div>