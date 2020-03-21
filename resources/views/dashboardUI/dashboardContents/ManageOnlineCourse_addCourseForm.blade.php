<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                Add New Course Form</div>
            <div class="card-body">
                <form method="post" action="{!! URL::to('/') !!}/manageOnlineCourse/manageCourse/addNewCourse/submit" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Type :</label>
                        <div class="col-sm-5">
                            <select onchange="if($(this).val() == '1'){$('#courseName').attr('disabled','disabled');$('#newCourseCode').attr('disabled','disabled');$('#courseCode').removeAttr('disabled');}else{$('#courseName').removeAttr('disabled');$('#newCourseCode').removeAttr('disabled');$('#courseCode').attr('disabled','disabled');}" class="form-control" name="courseType" id="courseType">
                                <option value="1">Registered Course</option>
                                <option value="0">New Course</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Course :</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="courseCode" id="courseCode">
                                @foreach($getAvailableCourseCode AS $dataCourseCode)
                                    <option value="{!! $dataCourseCode->kode_mata_kuliah !!}">{!! $dataCourseCode->kode_mata_kuliah !!} - {!! $dataCourseCode->nama_mata_kuliah_eng !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">New Course Name :</label>
                        <div class="col-sm-7">
                            <input disabled="disabled" type="text" class="form-control" name="courseName" id="courseName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">New Course Code :</label>
                        <div class="col-sm-7">
                            <input disabled="disabled" type="text" class="form-control" name="newCourseCode" id="newCourseCode" />
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