@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataExam = $db->getCoursesClassSubTopicExamByIdSubTopic($idSubTopic)->get();

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
                <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}'" class="btn btn-warning">Back</button>&nbsp;
                <button onclick="showModals('myCreateExamModals', 'Create Exam')" class="btn btn-primary">Create Exam</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Exam List</div>
            <div class="card-body">
                <table class="table table-hovered">
                    <thead>
                    <tr class="table-info">
                        <th>No.</th>
                        <th>Actions</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Score Range</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($dataExam) == 0)
                        <tr class="table-danger"><td colspan="9"><center>NO EXAM</center></td></tr>
                    @else
                        @foreach($dataExam AS $data)
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

                            $getUnevaluatedExam = $db->getExamCompletionByIdExam($data->idExam,'0');
                            ?>
                            <tr>
                                <td>{!! $no++ !!}</td>
                                <td>
                                    <table border="0">
                                        <tr>
                                            <td><button onclick="deleteExam({!! $data->idExam !!},'{!! $data->examType !!}')" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                            <td><button onclick="editExam({!! $data->idExam !!},'{!! $data->examType !!}')" class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
                                            <td><button onclick="evaluateExam({!! $data->idExam !!},'{!! $data->examType !!}')" class="btn btn-info">{!! $getUnevaluatedExam->count() !!} Students Completed</button></td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{!! $creatorName !!}</td>
                                <td>{!! date('Y M d H:i:s',strtotime($data->dateTime)) !!} GMT +7</td>
                                <td>{!! strtoupper($data->examType) !!}</td>
                                <td>{!! $data->examDescription !!}</td>
                                <td>{!! date('d M Y H:i:s', strtotime($data->examDeadline)) !!} GMT +7</td>
                                <td>{!! $data->scoreRangeStart !!} - {!! $data->scoreRangeEnd !!}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myCreateExamModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myCreateExamModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myCreateExamModalsBody">
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageExam/submitCreateExam" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
                    <input type="hidden" name="idTopic" value="{!! $idTopic !!}">
                    <input type="hidden" name="idSubTopic" value="{!! $idSubTopic !!}">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Exam Type :</label>
                        <div class="col-sm-12">
                            <select name="typeExam" class="form-control">
                                <option value="written">Written</option>
                                <option value="upload">Upload File</option>
                                <option value="choices">Multiple Choices</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="description" name="description"></textarea>
                            <script>
                                CKEDITOR.replace( 'description');
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Deadline :</label>
                        <div class="col-sm-6">
                            <input type="text" name="deadline" id="deadline" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Minimum Score :</label>
                        <div class="col-sm-4">
                            <input type="number" name="minScore" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Maximum Score :</label>
                        <div class="col-sm-4">
                            <input type="number" name="maxScore" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <script language="JavaScript">
                    $(document).ready(function() {
                        loadDateTimePicker('deadline','yyyy-mm-dd hh:ii');
                    });
                </script>
            </div>
            <div class="modal-footer" id="myCreateExamModalsExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script language="JavaScript">
    function editExam(idExam,typeExam){
        location.href = '/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageExam/'+idExam+'/'+typeExam+'/editExam';
    }

    function deleteExam(idExam,typeExam){
        var isConfirm = confirm('Are you sure to delete this Exam?');
        if(isConfirm) {
            location.href = '/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageExam/' + idExam + '/' + typeExam + '/deleteExam';
        }
    }

    function evaluateExam(idExam,typeExam){
        location.href = '/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $idTopic !!}/{!! $idSubTopic !!}/manageExam/'+idExam+'/'+typeExam+'/evaluateExam';
    }
</script>
