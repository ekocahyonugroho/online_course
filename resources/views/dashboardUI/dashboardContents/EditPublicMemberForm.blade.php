<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Members</li>
    <li class="breadcrumb-item">Public</li>
    <li class="breadcrumb-item active">Edit Member</li>
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
                <i class="fa fa-users"></i> Edit Data</div>
            <div class="card-body">
                @if(!empty($getMemberPersonalData))
                    <form method='post' action="{!! action('UserActionController@doUpdatePublicMember', $getMemberPersonalData->idMember) !!}">
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="email" placeholder='username@gmail.com' value='{!! $getMemberPersonalData->emailAddress !!}' class="form-control" name='email' id="email">
                        </div>
                        <div class="form-group">
                            <label>Username :</label>
                            <input type="text" disabled="disabled" readonly value='{!! $getMemberPersonalData->Username !!}' class="form-control" name='username' id="username">
                        </div>
                        <div class="form-group">
                            <label>First Name :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->nameFirst !!}' name='firstName' id="firstName">
                        </div>
                        <div class="form-group">
                            <label>Last Name :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->nameLast !!}' name='lastName' id="lastName">
                        </div>
                        <div class="form-group">
                            <label>Gender :</label>
                            {!! Form::select('gender', array('L' => 'L', 'P' => 'P'), $getMemberPersonalData->gender, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label>Birth Place :</label>
                            <input type="text" placeholder='City Name' class="form-control" value='{!! $getMemberPersonalData->birthPlace !!}' id="birthPlace" name='birthPlace'>
                        </div>
                        <div class="form-group">
                            <label>Birth Date :</label>
                            <input type="date" class="form-control" value='{!! $getMemberPersonalData->birthDate !!}' id="birthDate" name='birthDate'>
                        </div>
                        <div class="form-group">
                            <label>Nationality :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->nationality !!}' id="nationality" name='nationality'>
                        </div>
                        <div class="form-group">
                            <label>Phone Number :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->phoneNumber !!}' id="phoneNumber" name='phoneNumber'>
                        </div>
                        <div class="form-group">
                            <label>Highest Education :</label>
                            {!! Form::select('highestEducation', $highestEducationDropdown, $getMemberPersonalData->idHighestEducation, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label>Highest Education Institution :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->highestEducationInstitution !!}' id="highestEducationInstitution" name='highestEducationInstitution'>
                        </div>
                        <div class="form-group">
                            <label>Your Working Field :</label>
                            {!! Form::select('workingField', $workingFieldDropdown, $getMemberPersonalData->idWorkingField, array('class' => 'form-control')) !!}

                        </div>
                        <div class="form-group">
                            <label>Your Working Position :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->workingPosition !!}' id="workingPosition" name='workingPosition'>
                        </div>
                        <div class="form-group">
                            <label>Your Working Institution :</label>
                            <input type="text" class="form-control" value='{!! $getMemberPersonalData->workingInstitution !!}' id="workingInstitution" name='workingInstitution'>
                        </div>
                        <div class="form-group">
                            <label>Your Working Experience :</label>
                            <input type="number" placeholder='in Years' class="form-control" value='{!! $getMemberPersonalData->workingExperience !!}' id="workingExperience" name='workingExperience'>
                        </div>
                        <div class="form-group">
                            <label>Tell us why you are interested in SBM ITB Online Course :</label>
                            <textarea class='form-control' id='interestedReason' name='interestedReason'>{!! $getMemberPersonalData->interestedReason !!}</textarea>
                        </div>
                        <div class="checkbox">
                            <label><input id='subscription' value='1' @if($getMemberPersonalData->isSubscription == '1') checked='checked' @endif name='subscription' type="checkbox"> Email Subscription</label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>