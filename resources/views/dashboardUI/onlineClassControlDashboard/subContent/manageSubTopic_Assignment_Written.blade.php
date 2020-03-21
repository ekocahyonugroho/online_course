@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataAssignment = $db->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment)->first();
$dataQuestion = $db->getCoursesClassAssignmentQuestionByIdAssignment($idAssignment)->get();

$no = 1;
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
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <i class="fa fa-user-circle-o"></i> Control Panel</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            This process is used to create an assignment with direct written method. Means that students should write and type the answer directly.
                        </div>
                    </div>
                </div>
                <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}'" class="btn btn-warning">Back</button>&nbsp;
                <button onclick="showModals('myAddQuestionModals', 'Add New Question')" class="btn btn-primary">Add Question</button>&nbsp;
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Questions List</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>No.</th>
                            <th><center>Action</center></th>
                            <th><center>Question</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($dataQuestion) == 0)
                            <tr class="table-danger">
                                <td colspan="3"><center>NO QUESTION</center></td>
                            </tr>
                        @else
                            @foreach($dataQuestion AS $question)
                                <tr>
                                    <td>{!! $no++ !!}</td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td><button onclick="deleteQuestion({!! $question->idQuestion !!})" type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                                <td><button onclick="editQuestion({!! $question->idQuestion !!})" type="button" class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>{!! $question->Question !!}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myAddQuestionModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myAddQuestionModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myAddQuestionModalsBody">
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageAssignment/{!! $idAssignment !!}/written/editAssignment/submitNewQuestion" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
                    <input type="hidden" name="idAssignment" value="{!! $idAssignment !!}">
                    <input type="hidden" name="typeAssignment" value="written">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Question :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="question" name="question"></textarea>
                            <script>
                                CKEDITOR.replace( 'question');
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
            <div class="modal-footer" id="myAddQuestionModalsExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myEditQuestionModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myEditQuestionModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myEditQuestionModalsBody">
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageAssignment/{!! $idAssignment !!}/upload/editAssignment/submitEditQuestion" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
                    <input type="hidden" name="idAssignment" value="{!! $idAssignment !!}">
                    <input type="hidden" name="typeAssignment" value="written">
                    <input type="hidden" id="editIdQuestion" name="idQuestion" value="">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Question :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="editQuestion" name="editQuestion"></textarea>
                            <script>
                                CKEDITOR.replace( 'editQuestion');
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
            <div class="modal-footer" id="myEditQuestionModalsExtraButton">
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
    function editQuestion(idQuestion){
        var formData = new FormData();
        formData.append('idCoursesClass', {!! $idCoursesClass !!});
        formData.append('idQuestion', idQuestion);
        formData.append('_token', '{{ csrf_token() }}');
        xmlhttpReq.open("POST", "{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageAssignment/{!! $idAssignment !!}/written/editAssignment/previewEditQuestion", true);
        xmlhttpReq.send(formData);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $('#editIdQuestion').val(str[1]);
                    CKEDITOR.instances['editQuestion'].setData(str[2]);
                    showModals('myEditQuestionModals', 'Edit Question');
                }else {
                    $("#myWarningModalsBody").html("Error has occured! "+str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    }
</script>