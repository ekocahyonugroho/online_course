@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();
$getAvailableMentor = $db->getAvailableMentorForOnlineClassByIdCoursesClass($idCoursesClass)->get();
?>
<div class="row">
    <div class="col-lg-12">
    <form method="post" action="{!! URL::to('/') !!}/ServerSide/ManageOnlineClass/addOnlineClassMentor/submit" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="idCoursesClass" value="{!! $idCoursesClass !!}">
        <div class="form-group">
            <label class="control-label col-sm-4" for="email">Available lecturers :</label>
            <div class="col-sm-6">
                <select class="form-control" name="idMentor">
                    @foreach($getAvailableMentor AS $option)
                        <option value="{!! $option->idMember !!}">{!! $option->nama_dosen !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-info">
                <i class="fa fa-user-circle-o"></i> Mentors List - {!! $dataOnlineClass->nama_mata_kuliah_eng !!}</div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if(count($getOnlineClassMentor) > 0)
                        @foreach($getOnlineClassMentor AS $dataMentor)
                            <tr>
                                <td><a href="{!! URL::to('/') !!}/ServerSide/ManageOnlineClass/addOnlineClassMentor/deleteMentor/{!! $idCoursesClass !!}/{!! $dataMentor->idMember !!}"><i class="fa fa-trash" aria-hidden="true"></i></a> {!! $dataMentor->nama_dosen !!}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr><center>NOT FOUND</center></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>