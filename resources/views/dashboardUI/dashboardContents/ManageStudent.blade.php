<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Student</li>
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
                <i class="fa fa-users"></i> Registered ITB Student</div>
            <div class="card-body">
                <div class="row" style="padding-left: 5px;">
                    <button id="btnAddStudent" type="button" class="btn btn-success">Add Student</button>
                </div>
                <br />
                <div class="row">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Program</th>
                            <th>Class</th>
                            <th>NIM</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Registered Date</th>
                            <th>Taken Courses</th>
                            <th>Completed Courses</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 0; ?>
                        @if(!empty($registeredStudent))
                            @foreach($registeredStudent as $dataStudent)
                                <?php $no++ ?>
                                <tr>
                                    <td>{!! $no !!}</td>
                                    <td>
                                        @if($dataStudent->IsActive == '1')
                                            <button onclick="location.href='{!! URL::to('/') !!}/manageMember/student/suspendUser/{!! $dataStudent->idMember !!}/student'" class="btn btn-warning">Suspend</button>
                                        @else
                                            <button onclick="location.href='{!! URL::to('/') !!}/manageMember/student/activateUser/{!! $dataStudent->idMember !!}/student'" class="btn btn-success">Activate</button>
                                        @endif
                                        <br /><br />
                                        <button class="btn btn-danger">Remove</button>
                                    </td>
                                    <td>{!! $dataStudent->nama_program !!}</td>
                                    <td>{!! $dataStudent->nama_angkatan !!}</td>
                                    <td>{!! $dataStudent->nim !!}</td>
                                    <td>{!! $dataStudent->nama !!}</td>
                                    <td>{!! $dataStudent->Username !!}</td>
                                    <td>{!! $dataStudent->email !!}</td>
                                    <td>{!! $dataStudent->Registered !!}</td>
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
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myAddStudentModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myAddStudentModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myAddStudentModalsBody">
                <form id="addStudentForm" method="post" action="{!! URL::to('/') !!}/manageMember/student/addStudent" class="form-horizontal">
                    <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">NIM / Student Number:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="addStudentNim" name="addStudentNim" placeholder="find by NIM">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Program :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addStudentProgram" name="addStudentProgram" disabled="disabled" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Class :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addStudentClass" name="addStudentClass" disabled="disabled" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Name :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addStudentName" name="addStudentName" disabled="disabled" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Username :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addStudentUsername" name="addStudentUsername" disabled="disabled" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Email :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addStudentEmail" name="addStudentEmail" disabled="disabled" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Status :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="addStudentStatus" name="addStudentStatus" disabled="disabled" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="myAddStudentModalsExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script language="JavaScript">
    $('#btnAddStudent').click(function() {
        showModals('myAddStudentModals', 'Add Student From EcoSystem Server');
        $('#addStudentForm')[0].reset();
    });

    $('#addStudentNim').keyup(function() {
        if($(this).val() != ""){
            var value = $(this).val();
            var formData = new FormData();
            formData.append('query', value);
            formData.append('_token',$('#_token').val());
            xmlhttpReq.open("POST", "/manageMember/student/findAvailableStudent", true);
            xmlhttpReq.send(formData);
            xmlhttpReq.onreadystatechange = function () {
                if (xmlhttpReq.readyState == 4) {
                    var str = xmlhttpReq.responseText.split("&nbsp;");
                    if (str[0] == 'success') {
                        $('#addStudentProgram').val(str[1]);
                        $('#addStudentClass').val(str[2]);
                        $('#addStudentName').val(str[3]);
                        $('#addStudentUsername').val(str[4]);
                        $('#addStudentEmail').val(str[5]);
                        $('#addStudentStatus').val(str[6]);
                    }else {
                        $("#myWarningModalsBody").html(str[0]);
                        showModals('myWarningModals', 'Warning');
                    }
                }
            }
        }
    });
</script>