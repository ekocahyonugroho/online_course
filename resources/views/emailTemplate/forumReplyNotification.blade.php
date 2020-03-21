@inject('Database_communication', 'App\Http\Backend\Database_communication')
@inject('userInterface', 'App\Http\Middleware\CourseUserInterface')
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
Hi, {!! $destinationName !!}
<p>{!! $sourceName !!} has replied your post :</p>
<p>Forum Name : {!! $dataForum->forumTitle !!}</p>
<p>Created By : {!! $db->getFullNameMemberByIdMember($dataForum->idMemberCreator) !!} at {!! date('d F Y H:i:s', strtotime($dataForum->dateTime)) !!}</p>
<div style="padding:20px; width: 90%;background: linear-gradient(to bottom, #a7c7dc 0%,#85b2d3 100%);">
    <br />
    <p>{!! $req->messagePost !!}</p>
</div>
<p>To read more details, please login to your account by click this below button :</p>
<p><a href="https://{!! $_SERVER['SERVER_NAME'] !!}/login"><button style='{!! $buttonStyle !!}'>Click Here To Login</button></a></p>