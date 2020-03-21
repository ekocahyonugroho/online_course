<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataMaterial = $db->getCoursesClassSubTopicMaterialByIdMaterial($idMaterial)->first();

$dataCreator = $db->getMemberData($dataMaterial->idUser)->first();
$idAuthorityCreator = $dataCreator->idAuthority;

$dataCreator = $db->getFullMemberData($dataMaterial->idUser, $idAuthorityCreator)->first();

if($idAuthorityCreator == "3"){
    $creatorName = $dataCreator->nama_dosen;
}else{
    $creatorName = $dataCreator->name;
}
?>

    <table border="0" style="font-family: 'Times New Roman', arial, sans-serif; font-style: oblique; font-size: 18px;">
        <tr>
            <td>Title</td>
            <td>:</td>
            <td><b><?php echo $dataMaterial->titleMaterial; ?></b></td>
        </tr>
        <tr>
            <td>Description</td>
            <td>:</td>
            <td><b><?php echo $dataMaterial->descriptionMaterial; ?></b></td>
        </tr>
        <tr>
            <td>Last Updated</td>
            <td>:</td>
            <td><b><?php echo date('d M Y H:i:s',strtotime($dataMaterial->dateTime)); ?> GMT +7</b></td>
        </tr>
        <tr>
            <td>Updated By</td>
            <td>:</td>
            <td><b><?php echo $creatorName; ?></b></td>
        </tr>
    </table>

<br />&nbsp;
<br />&nbsp;

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

