@inject('Database_communication', 'App\Http\Backend\Database_communication')
<?php
$db = $Database_communication;

$dataMember = $db->getAccountDataByIdMember(session('idMember'))->first();
$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getSession = $db->getCoursesClassTopicByIdCoursesClass($idCoursesClass)->get();

$idAuthority = $dataMember->idAuthority;
?>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
    <a class="nav-link" href="{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}">
        <i class="fa fa-fw fa-link"></i>
        <span class="nav-link-text">Course Overview</span>
    </a>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSessions" data-parent="#exampleAccordion">
        <i class="fa fa-fw fa-sitemap"></i>
        <span class="nav-link-text">Sessions</span>
    </a>
    <ul class="sidenav-second-level collapse" id="collapseSessions">
        @foreach($getSession AS $session)
            <?php
            $dataSubTopic = $db->getCoursesClassSubTopicByIdTopic($session->idTopic)->get();
            ?>
            @if(!empty($dataSubTopic))
                <li>
                    <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapse{!! $session->idTopic !!}">{!! $session->TopicName !!}</a>
                    <ul class="sidenav-third-level collapse" id="collapse{!! $session->idTopic !!}">
                        @foreach($dataSubTopic AS $subTopic)
                            <li>
                                <a href="{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/enterSession/{!! $session->idTopic !!}/{!! $subTopic->idSubTopic !!}">{!! $subTopic->subTopicName !!}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li>
                    <a href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageSession/{!! $session->idTopic !!}">{!! $session->TopicName !!}</a>
                </li>
            @endif
        @endforeach
    </ul>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSystemSettings" data-parent="#exampleAccordion">
        <i class="fa fa-fw fa-file"></i>
        <span class="nav-link-text">Discussion</span>
    </a>
    <ul class="sidenav-second-level collapse" id="collapseSystemSettings">
        <li>
            <a href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/managePrivateMessage">Private Messages</a>
        </li>
        <li>
            <a href="{!! URL::to('/') !!}/manageOnlineCourse/availableClass/manageOnlineClass/{!! $idCoursesClass !!}/manageForum">Forum</a>
        </li>
    </ul>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
    <a class="nav-link" href="{!! URL::to('/') !!}/myCourse/enterClass/{!! $idCoursesClass !!}/viewMyCourseReport">
        <i class="fa fa-fw fa-link"></i>
        <span class="nav-link-text">My Summary Result</span>
    </a>
</li>