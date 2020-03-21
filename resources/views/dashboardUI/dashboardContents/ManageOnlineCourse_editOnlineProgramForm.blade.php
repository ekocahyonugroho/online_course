<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                Edit Online Program Form</div>
            <div class="card-body">
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/manageClassProgram/editOnlineProgram/{!! $getAvailableOnlineProgramName->idAvailableClass !!}/submit" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Type :</label>
                        <div class="col-sm-5">
                            <select onchange="if($(this).val() == '0'){$('#onlineCourseProgram').attr('disabled','disabled')}else{$('#onlineCourseProgram').removeAttr('disabled')}" class="form-control" name="onlineCourseType" id="onlineCourseType">
                                <option @if($getAvailableOnlineProgramName->isDegreeProgram == "0") selected="selected" @endif value="0">Non Degree</option>
                                <option @if($getAvailableOnlineProgramName->isDegreeProgram == "1") selected="selected" @endif value="1">Degree</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Program :</label>
                        <div class="col-sm-5">
                            <select @if($getAvailableOnlineProgramName->isDegreeProgram == "0") disabled="disabled" @endif class="form-control" id="onlineCourseProgram" name="onlineCourseProgram">
                                @foreach($getAvailableEducationProgram AS $dataProgram)
                                    <option @if($getAvailableOnlineProgramName->idProgram == $dataProgram->id_program) selected="selected" @endif value="{!! $dataProgram->id_program !!}">{!! $dataProgram->nama_program !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Program Name:</label>
                        <div class="col-sm-6">
                            <input type="text" value="{!! $getAvailableOnlineProgramName->OnlineProgramName !!}" class="form-control" name="onlineCourseProgramName" id="onlineCourseProgramName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>