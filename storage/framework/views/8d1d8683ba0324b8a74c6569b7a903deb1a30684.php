<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataMaterial = $db->getCoursesClassSubTopicMaterialByIdMaterial($idMaterial)->first();
?>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <i class="fa fa-user-circle-o"></i> Control Panel</div>
            <div class="card-body">
                <button class="btn btn-warning" onclick="location.href='/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>'">Back</button>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="alert alert-info">
        <center><h2><?php echo $dataMaterial->titleMaterial; ?></h2></center>
    </div>
</div>
<div class="col-lg-12">
    <div class="alert alert-info">
        <?php echo $dataMaterial->descriptionMaterial; ?>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Material Content</div>
            <div class="card-body">
                <?php if($dataMaterial->typeMaterial == "article"): ?>
                    <?php echo html_entity_decode($dataMaterial->contentMaterial); ?>

                <?php elseif($dataMaterial->typeMaterial == "pdf"): ?>
                    <div style="background-color: #404040;height: 150%;padding: 0;margin: 0;" id="myPDF"></div>
                    <script type="text/javascript">

                        $(function() {
                            $("#myPDF").pdf( {
                                source: "<?php echo asset($dataMaterial->contentMaterial); ?>",
                                pdfScale : 2,
                                title: "<?php echo $dataMaterial->titleMaterial; ?>",
                                disableZoom: true,
                                redrawOnWindowResize : true,
                                loadingHTML: "Still Loading. Please wait..."
                            });
                        });

                    </script>
                <?php elseif($dataMaterial->typeMaterial == "ppt"): ?>
                    <?php echo $dataMaterial->contentMaterial; ?>

                <?php elseif($dataMaterial->typeMaterial == "video"): ?>
                    <?php echo $dataMaterial->contentMaterial; ?>

                <?php elseif($dataMaterial->typeMaterial == "file"): ?>
                    <button onclick="location.href='<?php echo asset($dataMaterial->contentMaterial); ?>'" class="btn btn-success">Download</button>
                    <iframe hidden src="<?php echo asset($dataMaterial->contentMaterial); ?>"></iframe>
                <?php elseif($dataMaterial->typeMaterial == "external"): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-warning">
                                Please allow pop-up on your browser or click this <a href="<?php echo $dataMaterial->contentMaterial; ?>" target="_blank">link</a> to open.
                            </div>
                        </div>
                    </div>
                    <iframe width="100%" height="768px" src="<?php echo $dataMaterial->contentMaterial; ?>"></iframe>
                    <script language="JavaScript">
                        window.open('<?php echo $dataMaterial->contentMaterial; ?>');
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
