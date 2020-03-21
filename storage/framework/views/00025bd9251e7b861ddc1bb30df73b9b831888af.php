<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataMaterial = $db->getCoursesClassSubTopicMaterialByIdSubTopic($idSubTopic)->get();

$noMaterial = 1;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h2>Topic : <?php echo $dataTopic->TopicName; ?></h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h4>Sub Topic : <?php echo $dataSubTopic->subTopicName; ?></h4></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <i class="fa fa-user-circle-o"></i> Control Panel</div>
            <div class="card-body">
                <button onclick="location.href='<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>'" class="btn btn-warning">Back</button>&nbsp;
                <button onclick="showModals('myAddArticleModals', 'Write Article')" class="btn btn-primary">Write Article</button>&nbsp;
                <button onclick="showModals('myUploadPDFModals', 'Upload PDF')" class="btn btn-primary">Upload PDF</button>&nbsp;
                <button onclick="showModals('myUploadPPTXModals', 'Upload PPTX')" class="btn btn-primary">Upload PPTX</button>&nbsp;
                <button onclick="showModals('myUploadVideoModals', 'Upload Video')" class="btn btn-primary">Upload Video File</button>&nbsp;
                <button onclick="showModals('myUploadFileModals', 'Upload File')" class="btn btn-primary">Upload File</button>&nbsp;
                <button onclick="showModals('myUploadExternalModals', 'External Reference')" class="btn btn-primary">External Reference</button>&nbsp;
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Prepared Materials</div>
            <div class="card-body">
                <table class="table table-hovered">
                    <thead>
                        <tr class="table-info">
                            <th>No.</th>
                            <th>Actions</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($dataMaterial) == 0): ?>
                            <tr class="table-danger"><td colspan="7"><center>NO MATERIAL</center></td></tr>
                        <?php else: ?>
                            <?php $__currentLoopData = $dataMaterial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                        // Code to find the material uploader / creator
                                $dataCreator = $db->getMemberData($data->idUser)->first();
                                $idAuthorityCreator = $dataCreator->idAuthority;

                                $dataCreator = $db->getFullMemberData($data->idUser, $idAuthorityCreator)->first();

                                if($idAuthorityCreator == "3"){
                                    $creatorName = $dataCreator->nama_dosen;
                                }else{
                                    $creatorName = $dataCreator->name;
                                }
                                ?>
                            <tr>
                                <td><?php echo $noMaterial++; ?></td>
                                <td>
                                    <table border="0">
                                        <tr>
                                            <td><button onclick="deleteMaterial(<?php echo $data->idMaterial; ?>)" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                            <td><button onclick="previewMaterial(<?php echo $data->idMaterial; ?>)" class="btn btn-info"><i class="fa fa-search-plus" aria-hidden="true"></i></button></td>
                                        </tr>
                                    </table>
                                </td>
                                <td><?php echo $creatorName; ?></td>
                                <td><?php echo date('Y M d H:i:s',strtotime($data->dateTime)); ?> GMT +7</td>
                                <td><?php echo strtoupper($data->typeMaterial); ?></td>
                                <td><?php echo $data->titleMaterial; ?></td>
                                <td><?php echo $data->descriptionMaterial; ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myAddArticleModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myAddArticleModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myAddArticleModalsBody">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            If you are going to add any images or multimedia content, please make sure those files are uploaded into Internet with accessable URL link.
                        </div>
                    </div>
                </div>
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/submitArticle" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Title :</label>
                        <div class="col-sm-12">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Article :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="article"></textarea>
                            <script>
                                CKEDITOR.replace( 'article');
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
            <div class="modal-footer" id="myAddArticleExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myUploadPDFModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myUploadPDFModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myUploadPDFModalsBody">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            Please make sure your PDF Version is 1.5 or above. Better created from Ms. Office on Windows OS.
                        </div>
                    </div>
                </div>
                <form enctype="multipart/form-data" method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/submitPDF" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Title :</label>
                        <div class="col-sm-12">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">PDF File (Max  <?php echo ini_get('post_max_size'); ?> bytes):</label>
                        <div class="col-sm-12">
                            <input type="file" name="pdfFile" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myUploadPDFExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myUploadPPTXModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myUploadPPTXModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myUploadPPTXModalsBody">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            Please upload your power point into Google Drive and open it using Google Slides. Then, please click File and publish it with choose Embeded option. After that copy iFrame codes into provided field below.
                        </div>
                    </div>
                </div>
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/submitPPT" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Title :</label>
                        <div class="col-sm-12">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">PPT Embedded URL:</label>
                        <div class="col-sm-12">
                            <input type="text" name="pptURL" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myUploadPPTXExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myUploadVideoModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myUploadVideoModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myUploadVideoModalsBody">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            Please upload video to Google Drive and get the embed URL. You also may use Youtube Embed URL.
                        </div>
                    </div>
                </div>
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/submitVideo" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Title :</label>
                        <div class="col-sm-12">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Video Embed URL :</label>
                        <div class="col-sm-12">
                            <input type="text" name="videoURL" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myUploadVideoxtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myUploadFileModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myUploadFileModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myUploadFileModalsBody">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            Please upload a downloadable file. If you have multiple files, better you compress it into RAR/ZIP version instead of uploading a file multiple times.
                        </div>
                    </div>
                </div>
                <form enctype="multipart/form-data" method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/submitFile" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Title :</label>
                        <div class="col-sm-12">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">File (Max  <?php echo ini_get('post_max_size'); ?> bytes):</label>
                        <div class="col-sm-12">
                            <input type="file" name="File" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myUploadPDFExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myUploadExternalModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myUploadExternalModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myUploadExternalModalsBody">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            Please add the URL Address as external reference. <b>Ex : https://wikipedia.com</b>
                        </div>
                    </div>
                </div>
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/submitExternal" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Title :</label>
                        <div class="col-sm-12">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">URL Address:</label>
                        <div class="col-sm-12">
                            <input type="text" name="externalURL" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myUploadExternalExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="multiPurposeModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="multiPurposeModalsLabel"></h4>
            </div>
            <div class="modal-body" id="multiPurposeModalsBody">
            </div>
            <div class="modal-footer" id="multiPurposeExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myWarningModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myWarningModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myWarningModalsBody"></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script language="JavaScript">
    function previewMaterial(idMaterial){
        location.href = "/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/"+idMaterial+"/previewMaterial";
    }

    function deleteMaterial(idMaterial){
        var Confirm = confirm("Are you sure to delete this material?");

        if(Confirm){
            location.href = "/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/"+idMaterial+"/deleteMaterial";
        }
    }
</script>
