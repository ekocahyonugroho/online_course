<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item active">Public</li>
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
            @if(isset($waitingConfirmation))
                <div class="card-header">
                    <i class="fa fa-user-circle-o"></i> Waiting Confirmation</div>
                <div class="card-body">
                    <table class="table table-bordered" id="longDataTable">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Register Date</th>
                            <th>Client Browser</th>
                            <th>Client IP Address</th>
                            <th>Client Location</th>
                            <th>Gender</th>
                            <th>Birth Place</th>
                            <th>Birth Date</th>
                            <th>Nationality</th>
                            <th>Phone Number</th>
                            <th>Highest Education</th>
                            <th>Highest Education Institution</th>
                            <th>Working Field</th>
                            <th>Working Position</th>
                            <th>Working Institution</th>
                            <th>Working Experience (Years)</th>
                            <th>Is Subscription</th>
                            <th>Interested Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; ?>
                        @foreach($waitingConfirmation AS $data)
                            <tr>
                                <td>{!! $no++ !!}</td>
                                <td><button onclick="location.href='{!! URL::to('/') !!}/manageMember/public/resendVerificationEmail/{!! $data->idMember !!}'" class="btn btn-warning">Resend Verification Email</button><br /><br /><button onclick="location.href='{!! URL::to('/') !!}/manageMember/public/removeUnverifiedUser/{!! $data->idMember !!}'" class="btn btn-danger">Remove</button></td>
                                <td>{!! $data->nameFirst." ".$data->nameLast !!}</td>
                                <td>{!! $data->Username !!}</td>
                                <td>{!! $data->emailAddress !!}</td>
                                <td>{!! $data->registerDate !!} GMT +7</td>
                                <td>{!! $data->clientBrowser !!}</td>
                                <td>{!! $data->clientIPaddress !!}</td>
                                <td>{!! $data->clientLocation !!}</td>
                                <td>{!! $data->gender !!}</td>
                                <td>{!! $data->birthPlace !!}</td>
                                <td>{!! $data->birthDate !!}</td>
                                <td>{!! $data->nationality !!}</td>
                                <td>{!! $data->phoneNumber !!}</td>
                                <td>{!! $data->highestEducation !!}</td>
                                <td>{!! $data->highestEducationInstitution !!}</td>
                                <td>{!! $data->workingField !!}</td>
                                <td>{!! $data->workingPosition !!}</td>
                                <td>{!! $data->workingInstitution !!}</td>
                                <td>{!! $data->workingExperience !!}</td>
                                <td>{!! $data->isSubscription !!}</td>
                                <td>{!! $data->interestedReason !!}</td>
                            </tr>
                        @endforeach
                    @elseif(isset($confirmationReport))
                        <div class="card-header">
                            <i class="fa fa-user-circle-o"></i> Verified Member</div>
                        <div class="card-body">
                            <table class="table table-bordered" id="longDataTable">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Taken Courses</th>
                                    <th>Completed Courses</th>
                                    <th>Gender</th>
                                    <th>Birth Place</th>
                                    <th>Birth Date</th>
                                    <th>Nationality</th>
                                    <th>Phone Number</th>
                                    <th>Highest Education</th>
                                    <th>Highest Education Institution</th>
                                    <th>Working Field</th>
                                    <th>Working Position</th>
                                    <th>Working Institution</th>
                                    <th>Working Experience (Years)</th>
                                    <th>Is Subscription</th>
                                    <th>Interested Reason</th>
                                </tr>
                                </thead>
                                <tbody>
                        <?php $no = 1; ?>
                        @foreach($confirmationReport AS $data)
                            <tr>
                                <td>{!! $no++ !!}</td>
                                <td>
                                    <button onclick="location.href='{!! URL::to('/') !!}/manageMember/public/editPublicUser/{!! $data->idMember !!}'" class="btn btn-info">Edit</button>
                                    <br /><br />
                                    @if($data->IsActive == '1')
                                        <button onclick="location.href='{!! URL::to('/') !!}/manageMember/public/suspendUser/{!! $data->idMember !!}/public'" class="btn btn-warning">Suspend</button>
                                    @else
                                        <button onclick="location.href='{!! URL::to('/') !!}/manageMember/public/activateUser/{!! $data->idMember !!}/public'" class="btn btn-success">Activate</button>
                                    @endif
                                    <br /><br />
                                    <form method="post" action="{!! URL::to('/') !!}/manageMember/public/deleteUser/{!! $data->idMember !!}/public">
                                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                        <button type="submit" id="btnRemovePublicUser" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                                <td>{!! $data->nameFirst." ".$data->nameLast !!}</td>
                                <td>{!! $data->Username !!}</td>
                                <td>{!! $data->emailAddress !!}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>{!! $data->gender !!}</td>
                                <td>{!! $data->birthPlace !!}</td>
                                <td>{!! $data->birthDate !!}</td>
                                <td>{!! $data->nationality !!}</td>
                                <td>{!! $data->phoneNumber !!}</td>
                                <td>{!! $data->highestEducation !!}</td>
                                <td>{!! $data->highestEducationInstitution !!}</td>
                                <td>{!! $data->workingField !!}</td>
                                <td>{!! $data->workingPosition !!}</td>
                                <td>{!! $data->workingInstitution !!}</td>
                                <td>{!! $data->workingExperience !!}</td>
                                <td>{!! $data->isSubscription !!}</td>
                                <td>{!! $data->interestedReason !!}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Public member from Open Registration</div>
        </div>
    </div>
</div>