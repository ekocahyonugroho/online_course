@inject('Database_communication', 'App\Http\Backend\Database_communication')
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Online Class</div>
            <div class="card-body">
                <div style="padding: 10px;">
                    <button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/addNewOnlineClassForm'" class="btn btn-primary">Add New Online Class</button>
                </div>
                <table class="table table-bordered" id="longDataTable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Online Program Name</th>
                        <th>Course Name</th>
                        <th>Course Description</th>
                        <th>Started From</th>
                        <th>Ended At</th>
                        <th>Tuition Fee</th>
                        <th>Target Student</th>
                        <th>Mentored By</th>
                        <th>Registered Students</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($dataAvailableClass))
                        <?php
                            $no = 1;
                            $db = $Database_communication;
                        ?>
                        @foreach($dataAvailableClass AS $data)
                            <?php
                                $dataClassCreator = $db->getMemberData($data->CreatedByIdUser)->first();
                                $idMember = $dataClassCreator->idMember;
                                $idAuthority = $dataClassCreator->idAuthority;

                                $getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($data->idCoursesClass)->get();

                                $getOnlineClassStudent = $db->getEnrolledClassByIdClassCourse($data->idCoursesClass)->get();
                            ?>
                            <tr>
                                <td>{!! $no++ !!}</td>
                                <td><button onclick="location.href='{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $data->idCoursesClass !!}'" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i> Manage</button></td>

                                @if($data->IsOpened == "1")
                                    <td><span class="badge badge-success">Opened</span></td>
                                @elseif($data->IsOpened == "0")
                                    <td><span class="badge badge-danger">Closed</span></td>
                                @endif

                                <td>{!! $db->getFullMemberData($idMember, $idAuthority)->first()->name !!}</td>
                                <td>{!! date('d F Y H:i:s', strtotime($data->CreatedDate)) !!} GMT +7</td>
                                <td>{!! $db->getAvailableEducationProgramByIdAvailableClass($data->idAvailableClass)->first()->OnlineProgramName !!}</td>
                                <td>{!! $db->getAvailableCoursesByIdAvailableCourse($data->idAvailableCourse)->first()->nama_mata_kuliah_eng !!}</td>
                                <td>{!! $data->CourseDescription !!}</td>
                                <td>{!! date('d F Y H:i:s',strtotime($data->OpenedStart)) !!} GMT +7</td>
                                <td>{!! date('d F Y H:i:s',strtotime($data->OpenedEnd)) !!} GMT +7</td>

                                @if($data->IsFree == "1")
                                    <td><span class="badge badge-success">FREE</span></td>
                                @elseif($data->IsFree == "0")
                                    <td><span class="badge badge-info">IDR {!! number_format( $data->CoursePrice , 2 , ',' , '.' ) !!}</span></td>
                                @endif


                                @if($data->IsPublic == "1")
                                    <td>Opened For Public</td>
                                @elseif($data->IsPublic == "0")
                                    <td>Students Who Has ID Student Only</td>
                                @endif

                                <td>
                                    @if(count($getOnlineClassMentor) > 0)
                                        <table>
                                        @foreach($getOnlineClassMentor AS $dataMentor)
                                            <tr>
                                                <td>{!! $dataMentor->nama_dosen !!}</td>
                                            </tr>
                                        @endforeach
                                        </table>
                                    @else
                                        NOT FOUND
                                    @endif
                                </td>
                                <td>
                                    @if(count($getOnlineClassStudent) > 0)
                                        {!! count($getOnlineClassStudent) !!} Students
                                    @else
                                        NOT FOUND
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>