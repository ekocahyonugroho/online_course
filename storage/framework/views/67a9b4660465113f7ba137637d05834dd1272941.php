<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo e(URL::to('/')); ?>/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">My Account</li>
</ol>

<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-info"></i> My Personal Information</div>
            <div class="card-body">
                <?php if(session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>!</strong> <?php echo e(session('error')); ?>

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
                    <div class="col-sm-3">
                        <form enctype="multipart/form-data" method="post" class="form-horizontal" action="<?php echo e(action('UserActionController@updateUserPhoto')); ?>">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <img height="auto" width="80%" src="<?php if(empty($photoMember)): ?> <?php echo asset('images/NO-IMAGE.png'); ?> <?php else: ?> <?php echo asset($photoMember->PhotoDirectory); ?> <?php endif; ?>" /></center>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <input type="file" class="form-control" name="photo" id="photo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-9">
                        <form method="post" class="form-horizontal" action="<?php echo e(action('UserActionController@updateUserInformation')); ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Username :</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <input readonly value="<?php if(!empty($dataMember->Username)): ?><?php echo $dataMember->Username; ?><?php endif; ?>" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email :</label>
                                <div class="col-sm-10">
                                    <input readonly  value="<?php if(!empty($dataMember->email)): ?><?php echo $dataMember->email; ?><?php else: ?> <?php echo $dataMember->emailAddress; ?> <?php endif; ?>"type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">First Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" <?php if(!empty($dataMember->nameFirst)): ?> value="<?php echo $dataMember->nameFirst; ?>" <?php else: ?> value="null" readonly <?php endif; ?> class="form-control" name="firstName"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="pwd">Last Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" <?php if(!empty($dataMember->nameLast)): ?> value="<?php echo $dataMember->nameLast; ?>" <?php else: ?> value="null" readonly <?php endif; ?> class="form-control" name="lastName"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php if($dataMember->idAuthority == "5"): ?> <button type="submit" class="btn btn-success">Update</button> <?php endif; ?> <button type="button" onclick="location.href='<?php echo e(URL::to('/')); ?>/forgot_password'" class="btn btn-info">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>