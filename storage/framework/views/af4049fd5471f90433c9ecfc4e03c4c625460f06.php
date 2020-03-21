<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php
$db = $Database_communication;

$dataMember = $db->getAccountDataByIdMember(session('idMember'))->first();
$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getSession = $db->getCoursesClassTopicByIdCoursesClass($idCoursesClass)->get();

$idAuthority = $dataMember->idAuthority;
?>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
    <a class="nav-link" href="<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>">
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
        <?php $__currentLoopData = $getSession; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $dataSubTopic = $db->getCoursesClassSubTopicByIdTopic($session->idTopic)->get();
            ?>
            <?php if(!empty($dataSubTopic)): ?>
                <li>
                    <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapse<?php echo $session->idTopic; ?>"><?php echo $session->TopicName; ?></a>
                    <ul class="sidenav-third-level collapse" id="collapse<?php echo $session->idTopic; ?>">
                        <?php $__currentLoopData = $dataSubTopic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subTopic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $session->idTopic; ?>/<?php echo $subTopic->idSubTopic; ?>"><?php echo $subTopic->subTopicName; ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $session->idTopic; ?>"><?php echo $session->TopicName; ?></a>
                </li>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSystemSettings" data-parent="#exampleAccordion">
        <i class="fa fa-fw fa-file"></i>
        <span class="nav-link-text">Discussion</span>
    </a>
    <ul class="sidenav-second-level collapse" id="collapseSystemSettings">
        <li>
            <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/managePrivateMessage">Private Messages</a>
        </li>
        <li>
            <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageForum">Forum</a>
        </li>
    </ul>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
    <a class="nav-link" href="<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/viewMyCourseReport">
        <i class="fa fa-fw fa-link"></i>
        <span class="nav-link-text">My Summary Result</span>
    </a>
</li>