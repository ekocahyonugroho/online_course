<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataForum = $db->getForumDataByIdForum($idForum)->first();

$buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";
?>
Hi, <?php echo $destinationName; ?>

<p><?php echo $sourceName; ?> has replied your post :</p>
<p>Forum Name : <?php echo $dataForum->forumTitle; ?></p>
<p>Created By : <?php echo $db->getFullNameMemberByIdMember($dataForum->idMemberCreator); ?> at <?php echo date('d F Y H:i:s', strtotime($dataForum->dateTime)); ?></p>
<div style="padding:20px; width: 90%;background: linear-gradient(to bottom, #a7c7dc 0%,#85b2d3 100%);">
    <br />
    <p><?php echo $req->messagePost; ?></p>
</div>
<p>To read more details, please login to your account by click this below button :</p>
<p><a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/login"><button style='<?php echo $buttonStyle; ?>'>Click Here To Login</button></a></p>