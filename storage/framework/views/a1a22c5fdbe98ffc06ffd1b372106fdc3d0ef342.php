<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataPrivateMessageContent = $db->getPrivateMessageContentByIdPrivateMessageContent($idPrivateMessageContent)->first();
$dataPrivateMessage = $db->getPrivateMessageDataByIdPrivateMessage($dataPrivateMessageContent->idPrivateMessage)->first();

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

<p>You have got a private message from <?php echo $sourceName; ?>,</p>
<div style="padding:20px; width: 90%;background: linear-gradient(to bottom, #a7c7dc 0%,#85b2d3 100%);">
    <p><h2>Subject : <?php echo $dataPrivateMessage->titleMessage; ?></h2></p>
    <p><?php echo $dataPrivateMessageContent->privateMessageContents; ?></p>
</div>
<p>To read more details, please login to your account by click this below button :</p>
<p><a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/login"><button style='<?php echo $buttonStyle; ?>'>Click Here To Login</button></a></p>