<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();
$getAvailableMentor = $db->getAvailableMentorForOnlineClassByIdCoursesClass($idCoursesClass)->get();
?>
<div class="row">
    <div class="col-lg-12">
        <form method="post" action="<?php echo URL::to('/'); ?>/ServerSide/ManageOnlineClass/addOnlineClassDescription/submit" class="form-horizontal">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
            <div class="form-group">
                <label class="control-label col-sm-8" for="email">Online Class Thumbnail Image URL Address :</label>
                <div class="col-sm-12">
                    <input type="text" value="<?php echo $dataOnlineClass->ThumbnailURLAddress; ?>" name="thumbnailURL" placeholder="http://" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-8" for="email">Opening Video URL Address :</label>
                <div class="col-sm-12">
                    <input type="text" value="<?php echo $dataOnlineClass->VideoURLDescription; ?>" name="videoURL" placeholder="http://" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="email">Course Description :</label>
                <div class="col-sm-12">
                    <textarea class="form-control" name="courseDescription"></textarea>
                    <script>
                        CKEDITOR.replace( 'courseDescription');
                        <?php if(!empty($dataOnlineClass->CourseDescription)): ?>
                            CKEDITOR.instances['courseDescription'].setData('<?php echo str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br />",$dataOnlineClass->CourseDescription); ?>', {
                            callback: function() {
                                this.checkDirty(); // true
                            }
                        });
                        <?php endif; ?>
                    </script>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>


