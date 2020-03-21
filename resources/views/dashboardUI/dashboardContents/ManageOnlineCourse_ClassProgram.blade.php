@inject('Database_communication', 'App\Http\Backend\Database_communication')
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Program for Online Course</div>
            <div class="card-body">
                <div style="padding: 10px;">
                    <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/manageClassProgram/addOnlineProgram'" class="btn btn-primary">Add Online Program</button>
                </div>
                <table class="table table-bordered" id="shortDataTable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Online Course Program Name</th>
                        <th>Opened Course Number</th>
                        <th>Joined Students Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($dataAvailableProgram))
                        <?php $no = 1; ?>
                        @foreach($dataAvailableProgram AS $data)
                            <tr>
                                <td>{!! $no++ !!}</td>
                                <td><button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/manageClassProgram/editOnlineProgram/{!! $data->idAvailableClass !!}'" class="btn btn-warning">Edit</button><br/><br/><button class="btn btn-danger">Delete</button></td>
                                @if($data->isDegreeProgram == '0')
                                    <td>Non Degree Program</td>
                                @elseif($data->isDegreeProgram == '1')
                                    <td>Degree Program</td>
                                @endif
                                <td>{!! $data->OnlineProgramName !!}</td>
                                <td>{!! $Database_communication->getOpenedCourseClassByIdAvailableClass($data->idAvailableClass)->count() !!}</td>
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