<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 12/02/18
 * Time: 15:50
 */
?>
@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
<?php
$db = $Database_communication;

$dataPrivateMessage = $db->getPrivateMessageDataByIdPrivateMessage($idPrivateMessage)->first();

$buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";
?>
Hi, {!! $destinationName !!}
<p>{!! $sourceName !!} has ended your conversation and you are no longer can access.</p>
<div style="padding:20px; width: 90%;background: linear-gradient(to bottom, #a7c7dc 0%,#85b2d3 100%);">
    <p><h2>Subject : {!! $dataPrivateMessage->titleMessage !!}</h2></p>
</div>
<p>To read more details, please login to your account by click this below button :</p>
<p><a href="https://{!! $_SERVER['SERVER_NAME'] !!}/login"><button style='{!! $buttonStyle !!}'>Click Here To Login</button></a></p>
