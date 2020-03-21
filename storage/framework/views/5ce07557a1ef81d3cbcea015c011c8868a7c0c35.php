<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo e(URL::to('/')); ?>/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item">Public</li>
    <li class="breadcrumb-item active">Edit Member</li>
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
                <i class="fa fa-users"></i> Edit Data</div>
            <div class="card-body">
                <?php if(!empty($getMemberPersonalData)): ?>
                    <form method='post' action="<?php echo action('UserActionController@doUpdatePublicMember', $getMemberPersonalData->idMember); ?>">
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <input type="email" placeholder='username@gmail.com' value='<?php echo $getMemberPersonalData->emailAddress; ?>' class="form-control" name='email' id="email">
                        </div>
                        <div class="form-group">
                            <label>Username :</label>
                            <input type="text" disabled="disabled" readonly value='<?php echo $getMemberPersonalData->Username; ?>' class="form-control" name='username' id="username">
                        </div>
                        <div class="form-group">
                            <label>First Name :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->nameFirst; ?>' name='firstName' id="firstName">
                        </div>
                        <div class="form-group">
                            <label>Last Name :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->nameLast; ?>' name='lastName' id="lastName">
                        </div>
                        <div class="form-group">
                            <label>Gender :</label>
                            <?php echo Form::select('gender', array('L' => 'L', 'P' => 'P'), $getMemberPersonalData->gender, array('class' => 'form-control')); ?>

                        </div>
                        <div class="form-group">
                            <label>Birth Place :</label>
                            <input type="text" placeholder='City Name' class="form-control" value='<?php echo $getMemberPersonalData->birthPlace; ?>' id="birthPlace" name='birthPlace'>
                        </div>
                        <div class="form-group">
                            <label>Birth Date :</label>
                            <input type="date" class="form-control" value='<?php echo $getMemberPersonalData->birthDate; ?>' id="birthDate" name='birthDate'>
                        </div>
                        <div class="form-group">
                            <label>Nationality :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->nationality; ?>' id="nationality" name='nationality'>
                        </div>
                        <div class="form-group">
                            <label>Phone Number :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->phoneNumber; ?>' id="phoneNumber" name='phoneNumber'>
                        </div>
                        <div class="form-group">
                            <label>Highest Education :</label>
                            <?php echo Form::select('highestEducation', $highestEducationDropdown, $getMemberPersonalData->idHighestEducation, array('class' => 'form-control')); ?>

                        </div>
                        <div class="form-group">
                            <label>Highest Education Institution :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->highestEducationInstitution; ?>' id="highestEducationInstitution" name='highestEducationInstitution'>
                        </div>
                        <div class="form-group">
                            <label>Your Working Field :</label>
                            <?php echo Form::select('workingField', $workingFieldDropdown, $getMemberPersonalData->idWorkingField, array('class' => 'form-control')); ?>


                        </div>
                        <div class="form-group">
                            <label>Your Working Position :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->workingPosition; ?>' id="workingPosition" name='workingPosition'>
                        </div>
                        <div class="form-group">
                            <label>Your Working Institution :</label>
                            <input type="text" class="form-control" value='<?php echo $getMemberPersonalData->workingInstitution; ?>' id="workingInstitution" name='workingInstitution'>
                        </div>
                        <div class="form-group">
                            <label>Your Working Experience :</label>
                            <input type="number" placeholder='in Years' class="form-control" value='<?php echo $getMemberPersonalData->workingExperience; ?>' id="workingExperience" name='workingExperience'>
                        </div>
                        <div class="form-group">
                            <label>Tell us why you are interested in SBM ITB Online Course :</label>
                            <textarea class='form-control' id='interestedReason' name='interestedReason'><?php echo $getMemberPersonalData->interestedReason; ?></textarea>
                        </div>
                        <div class="checkbox">
                            <label><input id='subscription' value='1' <?php if($getMemberPersonalData->isSubscription == '1'): ?> checked='checked' <?php endif; ?> name='subscription' type="checkbox"> Email Subscription</label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>