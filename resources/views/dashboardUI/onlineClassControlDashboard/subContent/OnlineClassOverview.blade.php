@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

$dataMember = $db->getAccountDataByIdMember(session('idMember'))->first();

$idAuthority = $dataMember->idAuthority;
?>
<div class="row">
    <div class="col-lg-2">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Open Online Class</div>
            <div class="card-body">
                <form name="activateOnlineClassForm" method="post" action="{!! URL::to('/') !!}/ServerSide/ManageOnlineClass/ActivateOnlineClass">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    @if($dataOnlineClass->IsOpened == "1")
                        <input type="checkbox" value="0" name="isOpen" checked />
                    @else
                        <input type="checkbox" value="1" name="isOpen" />
                    @endif
                    <script>
                        $("[name='isOpen']").bootstrapSwitch({
                            'onSwitchChange': function(event, state){
                                $("[name='activateOnlineClassForm']").submit();
                            }
                        });
                    </script>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-10">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Control Panel</div>
            <div class="card-body">
                <div class="row">
                    @if($idAuthority == "1" OR $idAuthority == "2")
                        <button class="btn btn-primary" onclick="loadEditScheduleForm()">Edit Schedule</button>&nbsp;
                        <button class="btn btn-primary" onclick="loadAddMentorForm()">Add Mentor</button>&nbsp;
                    @endif
                    <button class="btn btn-primary" onclick="loadAddCourseDescription()">Add Class Description</button>&nbsp;
                    <button class="btn btn-primary" onclick="loadAddClassOverview()">Online Class Overview</button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>

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