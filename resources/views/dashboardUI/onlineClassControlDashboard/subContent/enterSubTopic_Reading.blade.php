@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
            <center><h2>Topic : {!! $dataTopic->TopicName !!}</h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h4>Sub Topic : {!! $dataSubTopic->subTopicName !!}</h4></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <i class="fa fa-user-circle-o"></i> Materials List</div>
            <div class="card-body">
                <button onclick="location.href='{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}'" class="btn btn-warning">Back</button>&nbsp;
                <br />
                <br />
                <table class="table table-hovered">
                    @if(count($dataMaterial) == 0)
                        <tr class="table-danger"><td colspan="2"><center>NO MATERIAL</center></td></tr>
                    @else
                        @foreach($dataMaterial AS $data)
                            <?php
                                $faIcon = "";
                                switch(strtoupper($data->typeMaterial)){
                                    case "ARTICLE" :
                                        $faIcon = "<i class=\"fa fa-file-text\" aria-hidden=\"true\"></i>";
                                        break;
                                    case "PDF" :
                                        $faIcon = "<i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i>";
                                        break;
                                    case "PPT" :
                                        $faIcon = "<i class=\"fa fa-file-powerpoint-o\" aria-hidden=\"true\"></i>";
                                        break;
                                    case "VIDEO" :
                                        $faIcon = "<i class=\"fa fa-file-video-o\" aria-hidden=\"true\"></i>";
                                        break;
                                    case "FILE" :
                                        $faIcon = "<i class=\"fa fa-file\" aria-hidden=\"true\"></i>";
                                        break;
                                    default :
                                        $faIcon = "<i class=\"fa fa-question-circle\" aria-hidden=\"true\"></i>";
                                        break;
                                }
                            ?>
                            <tr>
                                <td>{!! $faIcon !!} <a href="{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $idSubTopic !!}/showMaterial/{!! $data->idMaterial !!}">{!! $data->titleMaterial !!}</a></td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Show Materials</div>
            <div class="card-body">
                @if(!empty($contentMaterial))
                    {!! $contentMaterial !!}
                @endif
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
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/submitArticle" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
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
                <form enctype="multipart/form-data" method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/submitPDF" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
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
                        <label class="control-label col-sm-4" for="email">PDF File (Max  {!! ini_get('post_max_size') !!} bytes):</label>
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
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/submitPPT" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
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
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/submitVideo" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
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
                <form enctype="multipart/form-data" method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/submitFile" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
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
                        <label class="control-label col-sm-4" for="email">File (Max  {!! ini_get('post_max_size') !!} bytes):</label>
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
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/submitExternal" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
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
        location.href = "/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/"+idMaterial+"/previewMaterial";
    }

    function deleteMaterial(idMaterial){
        var Confirm = confirm("Are you sure to delete this material?");

        if(Confirm){
            location.href = "/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/"+idMaterial+"/deleteMaterial";
        }
    }
</script>
