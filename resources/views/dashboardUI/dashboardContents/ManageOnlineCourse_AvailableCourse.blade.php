@inject('Database_communication', 'App\Http\Backend\Database_communication')
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Courses for Online Course</div>
            <div class="card-body">
                <div style="padding: 10px;">
                    <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/manageCourse/addNewCourse'" class="btn btn-primary">Add New Course</button>
                </div>
                <table class="table table-bordered" id="shortDataTable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Opened Course Number</th>
                        <th>Joined Students Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($dataAvailableCourse))
                        <?php $no = 1; ?>
                        @foreach($dataAvailableCourse AS $data)
                            @if($data->isRegisteredInCurriculum == "1")
                                <?php
                                    $detailedData = $Database_communication->getAvailableCoursesClassGeneralDataByCourseCode($data->CourseCode)->first();
                                    $CourseName = $detailedData->nama_mata_kuliah_eng;
                                ?>
                            @elseif($data->isRegisteredInCurriculum == "0")
                                <?php
                                $CourseName = $data->CourseName;
                                ?>
                            @endif
                            <tr>
                                <td>{!! $no++ !!}</td>
                                <td><button type="button" onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/manageCourse/deleteCourse/{!! $data->idAvailableCourse !!}'" class="btn btn-danger">Delete</button></td>
                                <td>{!! $data->CourseCode !!}</td>
                                <td>{!! $CourseName !!}</td>
                                <td>{!! $Database_communication->getCoursesClassGeneralDataByIdAvailableCourse($data->idAvailableCourse)->count() !!}</td>
                                <td>0</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>