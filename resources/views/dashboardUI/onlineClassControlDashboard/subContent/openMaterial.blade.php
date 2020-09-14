@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
            <td><b>{!! $dataMaterial->titleMaterial !!}</b></td>
        </tr>
        <tr>
            <td>Description</td>
            <td>:</td>
            <td><b>{!! $dataMaterial->descriptionMaterial !!}</b></td>
        </tr>
        <tr>
            <td>Last Updated</td>
            <td>:</td>
            <td><b>{!! date('d M Y H:i:s',strtotime($dataMaterial->dateTime)) !!} GMT +7</b></td>
        </tr>
        <tr>
            <td>Updated By</td>
            <td>:</td>
            <td><b>{!! $creatorName !!}</b></td>
        </tr>
    </table>

<br />&nbsp;
<br />&nbsp;

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
                    {!! $dataMaterial->contentMaterial !!}
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

