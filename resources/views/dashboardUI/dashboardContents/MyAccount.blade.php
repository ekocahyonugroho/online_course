<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">My Account</li>
</ol>

<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-info"></i> My Personal Information</div>
            <div class="card-body">
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>!</strong> {{ session('error') }}
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
                    <div class="col-sm-3">
                        <form enctype="multipart/form-data" method="post" class="form-horizontal" action="{{ action('UserActionController@updateUserPhoto')  }}">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <img height="auto" width="80%" src="@if(empty($photoMember)) {!! asset('images/NO-IMAGE.png') !!} @else {!! asset($photoMember->PhotoDirectory) !!} @endif" /></center>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="file" class="form-control" name="photo" id="photo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-9">
                        <form method="post" class="form-horizontal" action="{{ action('UserActionController@updateUserInformation')  }}">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Username :</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input readonly value="@if (!empty($dataMember->Username)){!! $dataMember->Username !!}@endif" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email :</label>
                                <div class="col-sm-10">
                                    <input readonly  value="@if (!empty($dataMember->email)){!! $dataMember->email !!}@else {!! $dataMember->emailAddress !!} @endif"type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">First Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" @if(!empty($dataMember->nameFirst)) value="{!! $dataMember->nameFirst !!}" @else value="null" readonly @endif class="form-control" name="firstName"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="pwd">Last Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" @if(!empty($dataMember->nameLast)) value="{!! $dataMember->nameLast !!}" @else value="null" readonly @endif class="form-control" name="lastName"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    @if ($dataMember->idAuthority == "5") <button type="submit" class="btn btn-success">Update</button> @endif <button type="button" onclick="location.href='{{ URL::to('/') }}/forgot_password'" class="btn btn-info">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>