<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
  <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMembers" data-parent="#exampleAccordion">
    <i class="fa fa-fw fa-sitemap"></i>
    <span class="nav-link-text">Members</span>
  </a>
  <ul class="sidenav-second-level collapse" id="collapseMembers">
    <li>
      <a href="<?php echo URL::to('/'); ?>/manageMember/admin">Administrators</a>
    </li>
    <li>
      <a href="<?php echo URL::to('/'); ?>/manageMember/lecturer">Lecturers</a>
    </li>
    <li>
      <a href="<?php echo URL::to('/'); ?>/manageMember/student">Students</a>
    </li>
    <li>
      <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMembers2">Public Students</a>
      <ul class="sidenav-third-level collapse" id="collapseMembers2">
        <li>
          <a href="<?php echo URL::to('/'); ?>/manageMember/public/waitingVerification">Waiting Verification</a>
        </li>
        <li>
          <a href="<?php echo URL::to('/'); ?>/manageMember/public">Verified Members</a>
        </li>
      </ul>
    </li>
  </ul>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
  <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseCourseClass" data-parent="#exampleAccordion">
    <i class="fa fa-fw fa-sitemap"></i>
    <span class="nav-link-text">Courses and Classes</span>
  </a>
  <ul class="sidenav-second-level collapse" id="collapseCourseClass">
    <li>
      <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/manageClassProgram">Class Program</a>
    </li>
    <li>
      <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/manageCourse">Courses</a>
    </li>
    <li>
      <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseCourseClass2">Online Course</a>
      <ul class="sidenav-third-level collapse" id="collapseCourseClass2">
        <li>
          <a href="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass">Available Online Class</a>
        </li>
        <li>
          <a href="#">Summary Report</a>
        </li>
      </ul>
    </li>
  </ul>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Link">
  <a class="nav-link" href="#">
    <i class="fa fa-fw fa-link"></i>
    <span class="nav-link-text">Activity Report</span>
  </a>
</li>
<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
  <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSystemSettings" data-parent="#exampleAccordion">
    <i class="fa fa-fw fa-file"></i>
    <span class="nav-link-text">System Settings</span>
  </a>
  <ul class="sidenav-second-level collapse" id="collapseSystemSettings">
    <li>
      <a href="#">Application Setting</a>
    </li>
    <li>
      <a href="#">Server Setting</a>
    </li>
  </ul>
</li>