@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdTopic($idTopic)->get();
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
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Sub Topics</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Sub Topic Name</th>
                        <th>Issue Covered</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    @if(count($dataSubTopic) == 0)
                        <tr class="table-danger"><td colspan="6"><center>NO SUB TOPICS</center></td></tr>
                    @else
                        @foreach($dataSubTopic AS $SubTopic)
                            <tr>
                                <td>
                                    <table border="0">
                                        <tr>
                                            <td><button onclick="location.href='{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $idTopic !!}/{!! $SubTopic->idSubTopic !!}'" class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i></button></td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{!! $SubTopic->subTopicName !!}</td>
                                <td>{!! $SubTopic->subTopicIssue !!}</td>
                                <td>{!! $SubTopic->subTopicDescription !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
