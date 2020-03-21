<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Backend\Database_communication;

class MailController extends Controller {
    public function basic_email($to, $toName, $subject, $content){
        $data = array(
            'content' => $content
        );

        Mail::send(['text'=>'mail'], $data, function($message) use ($subject, $toName, $to) {
            $message->to($to, $toName)->subject
            ($subject);
            $message->from('do-not-reply@sbm-itb.ac.id','SBM ITB Jakarta Campus');
        });

        return "sent";
    }

    public function html_email($to, $toName, $subject, $content){
        $data = array(
            'content' => $content
        );

        Mail::send('mail', $data, function($message) use ($subject, $toName, $to) {
            $message->to($to, $toName)->subject
            ($subject);
            $message->from('do-not-reply@sbm-itb.ac.id','SBM ITB Jakarta Campus');
        });

        return "sent";
    }

    public function attachment_email($to, $toName, $subject, $content, $attachements){
        $data = array(
            'content' => $content
        );

        Mail::send('mail', $data, function($message) use ($attachements, $subject, $toName, $to) {
            $message->to($to, $toName)->subject
            ($subject);
            $message->from('do-not-reply@sbm-itb.ac.id','SBM ITB Jakarta Campus');
            foreach ($attachements as $attachement) {
                $message->attach($attachement);
            }
        });

        return "sent";
    }

    public function getNewPublicMemberEmailVerificationTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getUnverifiedNewPublicMemberDataByIdMember($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->nameFirst." ".$dataMember->nameLast.". <br />Welcome to SBM ITB - TKLow Online Course</h1>";
        $content .= "<br /><br />";
        $content .= "Please verify your account by clicking below button before using SBM ITB TKLow Online Course or you can not use your account to login.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/newPublicMemberVerification/".$dataMember->randomToken."/".$idMember."\"><button style='$buttonStyle'>Click Here To Verify</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getDeletedPublicMemberEmailTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getPersonalDataOfUnverifiedUserByIdMember($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->nameFirst." ".$dataMember->nameLast.".";
        $content .= "<br /><br />";
        $content .= "Unfortunately your account has been deleted by our Administrator recently. Please re-register your self by clicking below button.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/register\"><button style='$buttonStyle'>Click Here</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getNewPublicMemberEmailVerifiedTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getDataOfPublicUserByIdMember($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->nameFirst." ".$dataMember->nameLast.". <br />Welcome to SBM ITB - TKLow Online Course</h1>";
        $content .= "<br /><br />";
        $content .= "Your account has been verified successfully and you may use it to access Online Course with typing your username : <b>".$dataMember->Username."</b>";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";
        return $content;
    }

    public function getWelcomingAdministratorEmailTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getAccountDataByIdMember($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->Username.". <br />Welcome to SBM ITB - TKLow Online Course</h1>";
        $content .= "<br /><br />";
        $content .= "You have been registered as Administrator of SBM ITB TK Low Online Course. You may manage it with typing your username : <b>".$dataMember->Username."</b> and password is same with your EcoSystem account.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";
        return $content;
    }

    public function getWelcomingLecturerEmailTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getFullMemberData($idMember, '3')->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->nama_dosen.". <br />Welcome to SBM ITB - TKLow Online Course</h1>";
        $content .= "<br /><br />";
        $content .= "You have been registered as Lecturer of SBM ITB TK Low Online Course. You may manage it with typing your username : <b>".$dataMember->Username."</b> and password is same with your EcoSystem account.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";
        return $content;
    }

    public function getWelcomingMentorEmailTemplate($idMember, $idCoursesClass){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getFullMemberData($idMember, '3')->first();
        $dataCourse = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->nama_dosen.". <br />Welcome to SBM ITB - TKLow Online Course</h1>";
        $content .= "<br /><br />";
        $content .= "You have been registered as Mentor of Online Class as detailed below :";
        $content .= "<table width='70%' border='1'>";
        $content .= "<tr><td>Program Name</td><td>:</td><td>".$dataCourse->OnlineProgramName."</td></tr>";
        $content .= "<tr><td>Course Code</td><td>:</td><td>".$dataCourse->CourseCode."</td></tr>";
        $content .= "<tr><td>Course Name</td><td>:</td><td>".$dataCourse->nama_mata_kuliah_eng."</td></tr>";
        $content .= "<tr><td>Start Date</td><td>:</td><td>".$dataCourse->OpenedStart." GMT +7</td></tr>";
        $content .= "<tr><td>Ended At</td><td>:</td><td>".$dataCourse->OpenedEnd." GMT +7</td></tr>";
        $content .= "</table>";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "Please manage your Online Class and communicate with students with click below button.";
        $content .= "</div>";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";
        return $content;
    }

    public function getWelcomingStudentEmailTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getStudentMemberDataByIdMember($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember->nama.". <br />Welcome to SBM ITB - TKLow Online Course</h1>";
        $content .= "<br /><br />";
        $content .= "You have been registered as Student of SBM ITB TK Low Online Course. You may manage it with typing your username : <b>".$dataMember->Username."</b> and password is same with your EcoSystem account.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";
        return $content;
    }

    public function getResetPasswordInstructionEmail($idMember, $idRequestPassword){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getMemberData($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember-> Username."</h1>";
        $content .= "<br /><br />";
        $content .= "Our system has detected that you have been requesting a password reset. Please click this below button to reset your password. This link only available for 1 hour started from you have received this email.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/resetMyPassword/".$idMember."/".$idRequestPassword."\"><button style='$buttonStyle'>Click Here To Reset</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getPasswordHasResetInformationEmail($idMember, $password){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getMemberData($idMember)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember-> Username."</h1>";
        $content .= "<br /><br />";
        $content .= "Our system has detected that you have reset your password. Your new password is <b>".$password."</b>. You may login using your new password by clicking this below button.";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getUpdatedPublicMemberPersonalInformationEmailTemplate($idMember){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getFullMemberData($idMember, '5')->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$dataMember-> Username."</h1>";
        $content .= "<br /><br />";
        $content .= "You have received this email because your personal data in our Online Course System has been updated recently.<br />Please find this detail below for your personal information :";
        $content .= "<br />";
        $content .= "<br />";
        $content .= "<table border='1' width='100%'>";

        $content .= "<tr><td><b>Email Address</b></td><td>:</td><td>$dataMember->emailAddress</td></tr>";
        $content .= "<tr><td><b>First Name</b></td><td>:</td><td>$dataMember->nameFirst</td></tr>";
        $content .= "<tr><td><b>Last Name</b></td><td>:</td><td>$dataMember->nameLast</td></tr>";
        $content .= "<tr><td><b>Gender</b></td><td>:</td><td>$dataMember->gender</td></tr>";
        $content .= "<tr><td><b>Birth Place</b></td><td>:</td><td>$dataMember->birthPlace</td></tr>";
        $content .= "<tr><td><b>Birth Date</b></td><td>:</td><td>$dataMember->birthDate</td></tr>";
        $content .= "<tr><td><b>Nationality</b></td><td>:</td><td>$dataMember->nationality</td></tr>";
        $content .= "<tr><td><b>Phone Number</b></td><td>:</td><td>$dataMember->phoneNumber</td></tr>";
        $content .= "<tr><td><b>Highest Education</b></td><td>:</td><td>$dataMember->idHighestEducation</td></tr>";
        $content .= "<tr><td><b>Highest Education Institution</b></td><td>:</td><td>$dataMember->highestEducationInstitution</td></tr>";
        $content .= "<tr><td><b>Working Field</b></td><td>:</td><td>$dataMember->idWorkingField</td></tr>";
        $content .= "<tr><td><b>Working Position</b></td><td>:</td><td>$dataMember->workingPosition</td></tr>";
        $content .= "<tr><td><b>Working Institution</b></td><td>:</td><td>$dataMember->workingInstitution</td></tr>";
        $content .= "<tr><td><b>Working Experience in Years</b></td><td>:</td><td>$dataMember->workingExperience</td></tr>";
        $content .= "<tr><td><b>Interested Reason</b></td><td>:</td><td>$dataMember->interestedReason</td></tr>";

        $content .= "</table>";
        $content .= "<br /><br />Please click this below button to access your account :<br />";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getStudentAssignmentCompletionEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idMember, $idAuthority, $MentorName){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getFullMemberData($idMember, $idAuthority)->first();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        $getTopic = $SQLconn->getCoursesClassTopicByIdTopic($idTopic)->first();
        $getSubTopic = $SQLconn->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
        $getAssignment = $SQLconn->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$MentorName."</h1>";
        $content .= "<br /><br />";
        $content .= $dataMember->nameFirst." ".$dataMember->nameLast." has completed your Assignment for your mentored Online Course as detailed below : ";
        $content .= "<br />";
        $content .= "<br />";
        $content .= "<table border='1' width='100%'>";

        $content .= "<tr><td>Course</td><td>:</td><td>".$getOnlineClassData->CourseCode." - ".$CourseName."</td></tr>";
        $content .= "<tr><td>Topic</td><td>:</td><td>".$getTopic->TopicName."</td></tr>";
        $content .= "<tr><td>Sub Topic</td><td>:</td><td>".$getSubTopic->subTopicName."</td></tr>";
        $content .= "<tr><td>Assignment Type</td><td>:</td><td>".strtoupper($typeAssignment)."</td></tr>";
        $content .= "<tr><td>Assignment Description</td><td>:</td><td>".$getAssignment->assignmentDescription."</td></tr>";

        $content .= "</table>";
        $content .= "<br />";
        $content .= "Please review the result on your Online Course dashboard.";
        $content .= "<br />";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getStudentExamCompletionEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idMember, $idAuthority, $MentorName){
        $SQLconn = new Database_communication();

        $content = "";

        $dataMember = $SQLconn->getFullMemberData($idMember, $idAuthority)->first();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        $getTopic = $SQLconn->getCoursesClassTopicByIdTopic($idTopic)->first();
        $getSubTopic = $SQLconn->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
        $getExam = $SQLconn->getCoursesClassSubTopicExamByIdExam($idExam)->first();

        $buttonStyle = "background-color: #008CBA;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;";

        $content .= "<h1>Hi, ".$MentorName."</h1>";
        $content .= "<br /><br />";
        $content .= $SQLconn->getFullNameMemberByIdMember($idMember)." has completed your Exam for your mentored Online Course as detailed below : ";
        $content .= "<br />";
        $content .= "<br />";
        $content .= "<table border='1' width='100%'>";

        $content .= "<tr><td>Course</td><td>:</td><td>".$getOnlineClassData->CourseCode." - ".$CourseName."</td></tr>";
        $content .= "<tr><td>Topic</td><td>:</td><td>".$getTopic->TopicName."</td></tr>";
        $content .= "<tr><td>Sub Topic</td><td>:</td><td>".$getSubTopic->subTopicName."</td></tr>";
        $content .= "<tr><td>Exam Type</td><td>:</td><td>".strtoupper($typeExam)."</td></tr>";
        $content .= "<tr><td>Exam Description</td><td>:</td><td>".$getExam->examDescription."</td></tr>";

        $content .= "</table>";
        $content .= "<br />";
        $content .= "Please review the result on your Online Course dashboard.";
        $content .= "<br />";
        $content .= "<div style='padding-top: 5%; padding-bottom: 5%'>";
        $content .= "<a href=\"https://".$_SERVER['SERVER_NAME']."/login\"><button style='$buttonStyle'>Click Here To Login</button></a>";
        $content .= "</div>";

        return $content;
    }

    public function getFinishEvaluationEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $studentData){
        $SQLconn = new Database_communication();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        $getTopic = $SQLconn->getCoursesClassTopicByIdTopic($idTopic)->first();
        $getSubTopic = $SQLconn->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
        $getAssignment = $SQLconn->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment)->first();

        return view('emailTemplate.evaluationAssignmentReport',compact('idAssignment','CourseName','getTopic','getSubTopic','getAssignment','studentData'))->render();
    }

    public function getFinishExamEvaluationEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $studentData){
        $SQLconn = new Database_communication();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        $getTopic = $SQLconn->getCoursesClassTopicByIdTopic($idTopic)->first();
        $getSubTopic = $SQLconn->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
        $getExam = $SQLconn->getCoursesClassSubTopicExamByIdExam($idExam)->first();

        return view('emailTemplate.evaluationExamReport',compact('idExam','CourseName','getTopic','getSubTopic','getExam','studentData'))->render();
    }

    public function sendPrivateMessageNotification($idCoursesClass, $idPrivateMessageContent, $sourceName, $destinationName, $req){
        $SQLconn = new Database_communication();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        return view('emailTemplate.privateMessageNotification', compact('CourseName','idPrivateMessageContent', 'sourceName','destinationName','req'))->render();
    }

    public function sendEndedPrivateMessageNotification($idCoursesClass, $idPrivateMessage, $sourceName, $destinationName){
        $SQLconn = new Database_communication();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        return view('emailTemplate.endedPrivateMessageNotification', compact('CourseName','idPrivateMessage', 'sourceName','destinationName'))->render();
    }

    public function sendForumReplyNotification($idCoursesClass, $idForum, $req, $sourceName, $destinationName){
        $SQLconn = new Database_communication();

        $getOnlineClassData = $SQLconn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($getOnlineClassData->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->nama_mata_kuliah_eng;
        }else{
            $CourseName = $getOnlineClassData->CourseName;
        }

        return view('emailTemplate.forumReplyNotification', compact('CourseName','idForum','req', 'sourceName','destinationName'))->render();
    }
}
?>