@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$stmtTopic = $db->getCoursesClassTopicByIdCoursesClass($idCoursesClass)->get();

$dataMember = $db->getAccountDataByIdMember(session('idMember'))->first();

if(is_array($dataMember)){
    $idAuthority = $dataMember->idAuthority;
}else{
    $idAuthority = "0";
}
?>

<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Online Class Overview</div>
            <div class="card-body">
                <center><h3>OVERVIEW</h3></center>
                @if(empty($dataOnlineClass->CourseOverview))
                    <div class="alert alert-danger">
                        <strong>!</strong> NO OVERVIEW YET
                    </div>
                @else
                    {!! $dataOnlineClass->CourseOverview !!}
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Sessions</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Topic</th>
                        <th>Sub Topic</th>
                        <th>Reading Description</th>
                        <th>Materials</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stmtTopic AS $dataTopic)
                        <?php
                        $stmtSubTopic = $db->getCoursesClassSubTopicByIdTopic($dataTopic->idTopic);
                        $dataSubTopic = $stmtSubTopic->get();
                        $countSubTopic = $stmtSubTopic->count();

                        $i = 0;
                        ?>
                        <tr>
                            <td rowspan="{!! $countSubTopic !!}">{!! $dataTopic->TopicName !!}</td>
                        @foreach($dataSubTopic AS $subTopic)
                            <?php
                                    $stmtMaterials = $db->getCoursesClassSubTopicMaterialByIdSubTopic($subTopic->idSubTopic);
                                    $dataMaterials = $stmtMaterials->get();
                                    $countMaterials = $stmtMaterials->count();
                                    $x = 1;
                            if($i === 0){
                                ?>
                                <td>{!! $subTopic->subTopicName !!}</td>
                                <td>{!! $subTopic->subTopicDescription !!}</td>
                                <td>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Type</th>
                                                <th>Title</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($countMaterials === 0)
                                            <tr><td colspan="3">NO DATA YET</td></tr>
                                        @else
                                            @foreach($dataMaterials AS $materials)
                                                <tr>
                                                <td>{!! $x !!}</td>
                                                <td>{!! $materials->typeMaterial !!}</td>
                                                <td>{!! $materials->titleMaterial !!}</td>
                                                <?php $x++; ?>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </td>
                                <?php
                            }else{
                                ?>
                                <tr>
                                    <td>{!! $subTopic->subTopicName !!}</td>
                                    <td>{!! $subTopic->subTopicDescription !!}</td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                                $i++;
                            ?>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" data-keyboard="false" data-backdrop="static" id="mediumModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="mediumModalsLabel"></h4>
            </div>
            <div class="modal-body" id="mediumModalsBody"></div>
            <div class="modal-footer">
                <div id="modalExtraButton"></div>
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
    function loadEditScheduleForm(){
        var formData = new FormData();
        formData.append('idCoursesClass', '{!! $idCoursesClass !!}');
        formData.append('_token', '{{ csrf_token() }}');
        xmlhttpReq.open("POST", "/ServerSide/ManageOnlineClass/editOnlineClassSchedule", true);
        xmlhttpReq.send(formData);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#mediumModalsBody").html(str[1]);
                    showModals('mediumModals', 'Edit Schedule');
                }else {
                    $("#myWarningModalsBody").html("Error has occured! "+str[1]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }

    function loadAddMentorForm(){
        var formData = new FormData();
        formData.append('idCoursesClass', '{!! $idCoursesClass !!}');
        formData.append('_token', '{{ csrf_token() }}');
        xmlhttpReq.open("POST", "/ServerSide/ManageOnlineClass/addOnlineClassMentor", true);
        xmlhttpReq.send(formData);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#mediumModalsBody").html(str[1]);
                    showModals('mediumModals', 'Add Mentor');
                }else {
                    $("#myWarningModalsBody").html("Error has occured! "+str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }

    function loadAddCourseDescription(){
        var formData = new FormData();
        formData.append('idCoursesClass', '{!! $idCoursesClass !!}');
        formData.append('_token', '{{ csrf_token() }}');
        xmlhttpReq.open("POST", "/ServerSide/ManageOnlineClass/addOnlineClassDescription", true);
        xmlhttpReq.send(formData);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#mediumModalsBody").html(str[1]);
                    showModals('mediumModals', 'Add Description');
                }else {
                    $("#myWarningModalsBody").html("Error has occured! "+str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }

    function loadAddClassOverview(){
        var formData = new FormData();
        formData.append('idCoursesClass', '{!! $idCoursesClass !!}');
        formData.append('_token', '{{ csrf_token() }}');
        xmlhttpReq.open("POST", "/ServerSide/ManageOnlineClass/addOnlineClassOverview", true);
        xmlhttpReq.send(formData);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#mediumModalsBody").html(str[1]);
                    showModals('mediumModals', 'Online Class Overview');
                }else {
                    $("#myWarningModalsBody").html("Error has occured! "+str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }
</script>