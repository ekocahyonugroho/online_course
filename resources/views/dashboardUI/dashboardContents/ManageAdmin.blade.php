<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Administrators</li>
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
    <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-users"></i> Available Staff</div>
            <div class="card-body">
                <table class="table table-bordered" id="availableStaffTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Unit</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;?>
                        @foreach($availableStaff as $dataStaff)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>
                                    <a href="{!! URL::to('/') !!}/manageMember/admin/addAdmin/1/{!! $dataStaff->username !!}">
                                        <button type="button" class="btn btn-success">Superadmin</button>
                                    </a>
                                    <br /><br />
                                    <a href="{!! URL::to('/') !!}/manageMember/admin/addAdmin/2/{!! $dataStaff->username !!}">
                                        <button type="button" class="btn btn-info">Admin</button>
                                    </a>
                                </td>
                                <td>{!! $dataStaff->name !!}</td>
                                <td>{!! $dataStaff->username !!}</td>
                                <td>{!! $dataStaff->unit !!}</td>
                                <td>{!! $dataStaff->position !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Data Source from EcoSystem</div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-user-circle-o"></i> Registered Staff</div>
            <div class="card-body">
                <table class="table table-bordered" id="registeredStaffTable">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Action</th>
                        <th>Authority</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Unit</th>
                        <th>Position</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;?>
                        @foreach($registeredStaff as $dataStaff)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>
                                    @if($dataStaff->IsActive == '1')
                                        <button onclick="location.href='{!! URL::to('/') !!}/manageMember/admin/suspendUser/{!! $dataStaff->idMember !!}/admin'" class="btn btn-warning">Suspend</button>
                                    @else
                                        <button onclick="location.href='{!! URL::to('/') !!}/manageMember/admin/activateUser/{!! $dataStaff->idMember !!}/admin'" class="btn btn-success">Activate</button>
                                    @endif
                                    <br /><br />
                                    <a href="{!! URL::to('/') !!}/manageMember/admin/removeAdmin/{!! $dataStaff->idMember !!}">
                                        <button type="button" class="btn btn-danger">Remove</button>
                                    </a>
                                </td>
                                <td>{!! strtoupper($dataStaff->Authority) !!}</td>
                                <td>{!! $dataStaff->name !!}</td>
                                <td>{!! $dataStaff->Username !!}</td>
                                <td>{!! $dataStaff->unit !!}</td>
                                <td>{!! $dataStaff->position !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Staff enrolled as administrators</div>
        </div>
    </div>
</div>