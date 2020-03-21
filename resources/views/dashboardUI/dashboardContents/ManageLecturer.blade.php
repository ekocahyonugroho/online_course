<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Lecturer</li>
</ol>
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>!</strong> {{ session('error') }}
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>!</strong> {{ session('success') }}
</div>
@endif
@if( count( $errors ) > 0 )
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>!</strong> {{ $error }}
</div>
@endforeach
@endif
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Registered Lecturer</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px;">
                    <button id="btnAddLecturer" type="button" class="btn btn-success">Add Lecturer</button>
                </div>
                <br />
                <div class="row">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Mentored Courses</th>
                                <th>Mentored Students</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 0; ?>
                        @if(!empty($availableLecturer))
                            @foreach($availableLecturer as $dataLecturer)
                                <?php $no++ ?>
                                <tr>
                                    <td>{!! $no !!}</td>
                                    <td>
                                        @if($dataLecturer->IsActive == '1')
                                            <button onclick="location.href='{!! URL::to('/') !!}/manageMember/lecturer/suspendUser/{!! $dataLecturer->idMember !!}/lecturer'" class="btn btn-warning">Suspend</button>
                                        @else
                                            <button onclick="location.href='{!! URL::to('/') !!}/manageMember/lecturer/activateUser/{!! $dataLecturer->idMember !!}/lecturer'" class="btn btn-success">Activate</button>
                                        @endif
                                        <br /><br />
                                        <button class="btn btn-danger">Remove</button>
                                    </td>
                                    <td>{!! $dataLecturer->nama_dosen !!}</td>
                                    <td>{!! $dataLecturer->Username !!}</td>
                                    <td>{!! $dataLecturer->email !!}</td>
                                    <td>{!! $dataLecturer->Registered !!}</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer small text-muted">Data source from EcoSystem</div>
        </div>
    </div>
</div>
<script language="JavaScript">
    $('#btnAddLecturer').click(function() {
        xmlhttpReq.open("GET", "/manageMember/lecturer/addLecturer/showAvailable", true);
        xmlhttpReq.send(null);
        xmlhttpReq.onreadystatechange = function () {
            if (xmlhttpReq.readyState == 4) {
                var str = xmlhttpReq.responseText.split("&nbsp;");
                if (str[0] == 'success') {
                    $("#myLargeMultiPurposeModalsBody").html(str[1]);
                    showModals('myLargeMultiPurposeModals', 'Available Lecturer From EcoSystem Server');
                } else {
                    $("#myWarningModalsBody").html(str[0]);
                    showModals('myWarningModals', 'Warning');
                }
            }
        }
    });
</script>