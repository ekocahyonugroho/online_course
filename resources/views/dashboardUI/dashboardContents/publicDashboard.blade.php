@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;
$getUserData = $db->getFullMemberData(session('idMember'), '5')->first();

$getCourses = $db->getEnrolledClassByIdMember(session('idMember'))->get();
$countCourses = count($getCourses);
?>
<!-- Breadcrumbs-->
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{URL::to('/')}}/dashboard">{!! $getUserData->nameFirst." ".$getUserData->nameLast !!}</a>
    </li>
    <li class="breadcrumb-item active">My Dashboard</li>
</ol>
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
<div class="col-lg-12">
    <div class="alert alert-info">
        <center><h2>My Courses</h2></center>
    </div>
</div>
<div class="row">
    @foreach ($getCourses AS $dataCourses)
        <?php
        $dataCreator = $db->getMemberData($dataCourses->CreatedByIdUser)->first();
        $idAuthorityCreator = $dataCreator->idAuthority;

        $dataCreator = $db->getFullMemberData($dataCourses->CreatedByIdUser, $idAuthorityCreator)->first();

        if($idAuthorityCreator == "3"){
            $creatorName = $dataCreator->nama_dosen;
        }else{
            $creatorName = $dataCreator->name;
        }
        ?>
        <div class="col-lg-3 col-md-5 col-sm-6 portfolio-item">
            <div class="card h-150">
                <a href="{!!URL::to('/').'/course/'.$dataCourses->CourseCode.'-'.$dataCourses->idCoursesClass.'/about'!!}"><img class="card-img-top" src="<?php if ($dataCourses->ThumbnailURLAddress == "") { echo 'http://placehold.it/700x400'; } else { echo $dataCourses->ThumbnailURLAddress; } ?>" alt=""></a>
                <div class="card-body">
                    <h4 class="card-title"><a href="{!!URL::to('/').'/course/'.$dataCourses->CourseCode.'-'.$dataCourses->idCoursesClass.'/about'!!}">{!!$dataCourses->nama_mata_kuliah_id!!}</a></h4>

                    <p class="card-text">
                        <b>Created By : {!! $creatorName !!}</b>
                        <br />
                        <?php
                        // strip tags to avoid breaking any html
                        echo strlen($dataCourses->CourseDescription) >= 100 ?
                            substr($dataCourses->CourseDescription, 0, 90) . ' <a href="'.URL::to('/').'/myCourse/enterClass/'.$dataCourses->idCoursesClass.'">[Read more]</a>' :
                            $dataCourses->CourseDescription;
                        ?>
                    </p>
                </div>
                <div class="card-footer">
                    <center>
                        <p class="card-text">
                            Started On {!!date('F Y', strtotime($dataCourses->OpenedStart))!!}
                            <br />
                            {!!strtoupper($dataCourses->OnlineProgramName)!!}
                            <br />
                            <b>{!!$userInterface->showCourseClassPrice($dataCourses->idCoursesClass)!!}</b>
                            <br />
                            <button onclick="location.href='{!! URL::to('/') !!}/myCourse/enterClass/{!! $dataCourses->idCoursesClass !!}'" class="btn btn-primary">Resume</button>
                        </p>
                    </center>
                </div>
            </div>
        </div>
    @endforeach
</div>
