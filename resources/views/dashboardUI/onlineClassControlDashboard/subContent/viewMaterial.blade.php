@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
                <button class="btn btn-warning" onclick="location.href='/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}'">Back</button>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="alert alert-info">
        <center><h2>{!! $dataMaterial->titleMaterial !!}</h2></center>
    </div>
</div>
<div class="col-lg-12">
    <div class="alert alert-info">
        {!! $dataMaterial->descriptionMaterial !!}
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Material Content</div>
            <div class="card-body">
                @if($dataMaterial->typeMaterial == "article")
                    {!! html_entity_decode($dataMaterial->contentMaterial) !!}
                @elseif($dataMaterial->typeMaterial == "pdf")
                    <div style="background-color: #404040;height: 150%;padding: 0;margin: 0;" id="myPDF"></div>
                    <script type="text/javascript">

                        $(function() {
                            $("#myPDF").pdf( {
                                source: "{!! asset($dataMaterial->contentMaterial) !!}",
                                pdfScale : 2,
                                title: "{!! $dataMaterial->titleMaterial !!}",
                                disableZoom: true,
                                redrawOnWindowResize : true,
                                loadingHTML: "Still Loading. Please wait..."
                            });
                        });

                    </script>
                @elseif($dataMaterial->typeMaterial == "ppt")
                    <button onclick="location.href='{!! asset($dataMaterial->contentMaterial) !!}'" class="btn btn-success">Download</button>
                @elseif($dataMaterial->typeMaterial == "video")
                    <div class="video-container">
                        {!! $dataMaterial->contentMaterial !!}
                    </div>
                @elseif($dataMaterial->typeMaterial == "file")
                    <button onclick="location.href='{!! asset($dataMaterial->contentMaterial) !!}'" class="btn btn-success">Download</button>
                    <iframe hidden src="{!! asset($dataMaterial->contentMaterial) !!}"></iframe>
                @elseif($dataMaterial->typeMaterial == "external")
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-warning">
                                Please allow pop-up on your browser or click this <a href="{!! $dataMaterial->contentMaterial !!}" target="_blank">link</a> to open.
                            </div>
                        </div>
                    </div>
                    <iframe width="100%" height="768px" src="{!! $dataMaterial->contentMaterial !!}"></iframe>
                    <script language="JavaScript">
                        window.open('{!! $dataMaterial->contentMaterial !!}');
                    </script>
                @endif
            </div>
        </div>
    </div>
</div>
