<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 20/12/17
 * Time: 10:02
 */

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Backend\Database_communication;
use App\Http\Middleware\appHelper;
use App\Http\Middleware\CourseUserInterface;
use App\Http\Middleware\FormUserInterface;
use App\Http\Controllers\MailController;
use Validator;



class OnlineClassCommunicationController extends Controller
{
    function __construct()
    {
        $this->databaseConn = new Database_communication();
        $this->appHelper = new appHelper();
        $this->mail = new MailController();
        $this->FormUI = new FormUserInterface();
    }

    public function showUserPrivateMessage($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.privateMessage', compact('idCoursesClass'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function composeNewPrivateMessage($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.composeNewPrivateMessage', compact('idCoursesClass'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function submitNewPrivateMessage($idCoursesClass, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $validator = Validator::make($req->all(), [
                    'destination' => [
                        'required'
                    ],
                    'subject' => [
                        'required'
                    ],
                    'message' => [
                        'required'
                    ]
                ]);

                if ($validator->fails()) {
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage/composeNewPrivateMessage')
                        ->withErrors($validator)->withInput();
                }

                if($dataMember->Username === $req->destination){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage/composeNewPrivateMessage')
                        ->with('error','You are not allowed to send Private Message to your self.')->withInput();
                }

                $isDestinationUserExist = $this->databaseConn->getVerifiedUserByUsername($req->destination)->count();

                if($isDestinationUserExist < 1){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage/composeNewPrivateMessage')
                        ->with('error',$req->destination.' was not found or unregistered in system. Please type the correct destination username.')->withInput();
                }

                $destinationIdMember = $this->databaseConn->getVerifiedUserByUsername($req->destination)->first()->idMember;
                $dataDestinationMember = $this->databaseConn->getAccountDataByIdMember($destinationIdMember)->first();
                $idAuthorityDestinationMember = $dataDestinationMember->idAuthority;

                $arrayNameAndEmailDestinationMember = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority($destinationIdMember,$idAuthorityDestinationMember);

                $destinationEmail = $arrayNameAndEmailDestinationMember[0];
                $destinationName = $arrayNameAndEmailDestinationMember[1];

                $arrayUserNameAndEmail = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority(session('idMember'), $idAuthority);

                $idPrivateMessageContent = $this->databaseConn->submitNewPrivateMessage($idCoursesClass, $req);

                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                $CourseName = "";

                if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                    $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                }else {
                    $CourseName = $getOnlineClassData->first()->CourseName;
                }


                $subject = "Private Message From ".$arrayUserNameAndEmail[1]." In ".$CourseName;

                $content = $this->mail->sendPrivateMessageNotification($idCoursesClass, $idPrivateMessageContent, $arrayUserNameAndEmail[1], $destinationName, $req);

                $this->mail->html_email($destinationEmail, $destinationName, "SBM ITB TK-Low Online Course - ".$subject, $content);

                return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage')
                    ->with('success','Your message has been sent to '.$req->destination.' successfully.');
                //return $content;
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showPrivateMessage($idCoursesClass,$idPrivateMessage){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $isUserConversation = $this->databaseConn->getDestinationIdMemberByIdSender($idPrivateMessage,session('idMember'));

                if($isUserConversation == 0){
                    return redirect('/dashboard')->with('error', 'Ooops. Something went wrong with this conversation.');
                }

                $this->databaseConn->updateReadByRecepientPrivateMessage($idPrivateMessage);

                $messages = view('dashboardUI.dashboardCommunication.privateMessageContents',compact('idCoursesClass','idPrivateMessage'))->render();

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.privateMessage', compact('idCoursesClass','idPrivateMessage','messages'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function sendReplyPrivateMessage($idCoursesClass, $idPrivateMessage, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $isUserConversation = $this->databaseConn->getDestinationIdMemberByIdSender($idPrivateMessage,session('idMember'));

                if($isUserConversation == 0){
                    return redirect('/dashboard')->with('error', 'Ooops. Something went wrong with this conversation.');
                }

                $validator = Validator::make($req->all(), [
                    'message' => [
                        'required'
                    ]
                ]);

                if ($validator->fails()) {
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage/showMessage/'.$idPrivateMessage)
                        ->withErrors($validator)->withInput();
                }

                $idPrivateMessageContent = $this->databaseConn->sendReplyPrivateMessage($idPrivateMessage, $req);

                $destinationIdMember = $this->databaseConn->getDestinationIdMemberByIdSender($idPrivateMessage, session('idMember'));

                $dataDestinationMember = $this->databaseConn->getAccountDataByIdMember($destinationIdMember)->first();
                $idAuthorityDestinationMember = $dataDestinationMember->idAuthority;

                $arrayUserNameAndEmail = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority(session('idMember'), $idAuthority);

                $arrayNameAndEmailDestinationMember = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority($destinationIdMember,$idAuthorityDestinationMember);

                $destinationEmail = $arrayNameAndEmailDestinationMember[0];
                $destinationName = $arrayNameAndEmailDestinationMember[1];

                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                $CourseName = "";

                if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                    $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                }else {
                    $CourseName = $getOnlineClassData->first()->CourseName;
                }

                $subject = "Private Message From ".$arrayUserNameAndEmail[1]."  In ".$CourseName;

                $content = $this->mail->sendPrivateMessageNotification($idCoursesClass, $idPrivateMessageContent, $arrayUserNameAndEmail[1], $destinationName, $req);

                $this->mail->html_email($destinationEmail, $destinationName, "SBM ITB TK-Low Online Course - ".$subject, $content);

                return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage/showMessage/'.$idPrivateMessage);

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function deleteMessage($idCoursesClass,$idPrivateMessage){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $isUserConversation = $this->databaseConn->getDestinationIdMemberByIdSender($idPrivateMessage,session('idMember'));

                if($isUserConversation == 0){
                    return redirect('/dashboard')->with('error', 'Ooops. Something went wrong with this conversation.');
                }

                $destinationIdMember = $this->databaseConn->getDestinationIdMemberByIdSender($idPrivateMessage, session('idMember'));

                $endMessage = $this->databaseConn->endPrivateMessageByUser($idPrivateMessage);

                if($endMessage != 1){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idPrivateMessage.'/managePrivateMessage')
                        ->with('error', 'Ooops. Something went wrong when ended this conversation.');
                }

                $dataDestinationMember = $this->databaseConn->getAccountDataByIdMember($destinationIdMember)->first();
                $idAuthorityDestinationMember = $dataDestinationMember->idAuthority;

                $arrayUserNameAndEmail = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority(session('idMember'), $idAuthority);

                $arrayNameAndEmailDestinationMember = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority($destinationIdMember,$idAuthorityDestinationMember);

                $destinationEmail = $arrayNameAndEmailDestinationMember[0];
                $destinationName = $arrayNameAndEmailDestinationMember[1];

                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                $CourseName = "";

                if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                    $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                }else {
                    $CourseName = $getOnlineClassData->first()->CourseName;
                }

                $subject = "Private Message From ".$arrayUserNameAndEmail[1]."  In ".$CourseName;

                $content = $this->mail->sendEndedPrivateMessageNotification($idCoursesClass, $idPrivateMessage, $arrayUserNameAndEmail[1], $destinationName);

                $this->mail->html_email($destinationEmail, $destinationName, "SBM ITB TK-Low Online Course - ".$subject, $content);

                return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/managePrivateMessage');

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showCourseForumList($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.forumDiscussion', compact('idCoursesClass','idPrivateMessage','messages'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function createNewThread($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.composeNewThread', compact('idCoursesClass','idPrivateMessage','messages'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function submitNewThread($idCoursesClass, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $validator = Validator::make($req->all(), [
                    'titleNewThread' => [
                        'required'
                    ],
                    'messageThread' => [
                        'required'
                    ]
                ]);

                if ($validator->fails()) {
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/createNewThread')
                        ->withErrors($validator)->withInput();
                }

                $isWithFile = 0;

                if( $req->hasFile('file') ) {
                    $validator = Validator::make($req->all(), [
                        'file' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/createNewThread')
                            ->withErrors($validator)->withInput();
                    }

                    $isWithFile++;
                }

                $idForum = $this->databaseConn->createNewThread($idCoursesClass, $req->titleNewThread);
                $idForumMessage = $this->databaseConn->insertForumMessage($idForum, "", $req->messageThread, '0');

                if($isWithFile > 0){
                    $fileFolder = '/files/forums/'.$idForum.'/'.$idForumMessage.'/';
                    $destination = base_path().'/public'.$fileFolder;

                    $files = $req->file('file');

                    foreach ($files as $file) {
                        //$fileExtention = $file->clientExtension();
                        $fileName = $file->getClientOriginalName();

                        $completeFileName = $fileName;

                        $completePathFile = $fileFolder . $completeFileName;

                        $this->databaseConn->insertFileForum($idForumMessage, $fileName, $completePathFile);

                        $file->move($destination, $completeFileName);
                    }
                }

                return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/openForum/'.$idForum)
                    ->with('success','Welcome to your new thread.');

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function openForum($idCoursesClass, $idForum){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $getForumData = $this->databaseConn->getForumDataByIdForum($idForum);

                if($getForumData->count() == 0){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Something wrong was happen. Forum data not found.');
                }

                if(session('idMember') != $getForumData->first()->idMemberCreator) {
                    $isAlreadyView = $this->databaseConn->getForumViewDataByIdForumAndIdMember($idForum, session('idMember'));

                    if ($isAlreadyView->count() == 0) {
                        $this->databaseConn->countAsNewForumView($idForum, session('idMember'));
                    }
                }

                $post = $this->databaseConn->getAllThreadMessageByIdForum($idForum)->paginate(5);

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.forumContents', compact('idCoursesClass','idForum','getForumData','post'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function replyThreadDiscussion($idCoursesClass, $idForum){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $getForumData = $this->databaseConn->getForumDataByIdForum($idForum);

                if($getForumData->count() == 0){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Something wrong was happen. Forum data not found.');
                }else{
                    if($getForumData->first()->isClosed == "1"){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Sorry, this thread has been closed by the owner.');
                    }
                }

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $idForumMessageQuote = "";

                $content = view('dashboardUI.dashboardCommunication.threadReplyForm', compact('idCoursesClass','idForum', 'getForumData','idForumMessageQuote'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function replyThreadDiscussionWithQuote($idCoursesClass, $idForum, $idForumMessageQuote){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $getForumData = $this->databaseConn->getForumDataByIdForum($idForum);

                if($getForumData->count() == 0){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Something wrong was happen. Forum data not found.');
                }else{
                    if($getForumData->first()->isClosed == "1"){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Sorry, this thread has been closed by the owner.');
                    }
                }

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.threadReplyForm', compact('idCoursesClass','idForum', 'getForumData','idForumMessageQuote'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function submitReplyThread($idCoursesClass, $idForum, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $getForumData = $this->databaseConn->getForumDataByIdForum($idForum);

                if($getForumData->count() == 0){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Something wrong was happen. Forum data not found.');
                }else{
                    if($getForumData->first()->isClosed == "1"){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Sorry, this thread has been closed by the owner.');
                    }
                }

                $validator = Validator::make($req->all(), [
                    'titleNewPost' => [
                        'required'
                    ],
                    'messagePost' => [
                        'required'
                    ]
                ]);

                if ($validator->fails()) {
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/openForum/'.$idForum.'/replyThread')
                        ->withErrors($validator)->withInput();
                }

                $isWithFile = 0;

                if( $req->hasFile('file') ) {
                    $input_data = $req->all();

                    $validator = Validator::make(
                        $input_data, [
                        'file.*' => 'required|max:2000'
                    ],[
                            'file.*.required' => 'Please upload any file which has size more than 0 Kb',
                            'file.*.max' => 'Sorry! Maximum allowed size for an image is 2MB. Please upload your file into Google Drive and write the link in message field.',
                        ]
                    );

                    if ($validator->fails()) {
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/openForum/'.$idForum.'/replyThread')
                            ->withErrors($validator)->withInput();
                    }

                    $isWithFile++;
                }


                $idForumMessage = $this->databaseConn->postForumMessageReply($idForum, $req);

                if($isWithFile > 0){
                    $fileFolder = '/files/forums/'.$idForum.'/'.$idForumMessage.'/';
                    $destination = base_path().'/public'.$fileFolder;

                    $files = $req->file('file');

                    foreach ($files as $file) {
                        //$fileExtention = $file->clientExtension();
                        $fileName = $file->getClientOriginalName();

                        $completeFileName = $fileName;

                        $completePathFile = $fileFolder . $completeFileName;

                        $this->databaseConn->insertFileForum($idForumMessage, $fileName, $completePathFile);

                        $file->move($destination, $completeFileName);
                    }
                }

                $isSent = $this->sentNotificationToDestination($idCoursesClass, $idForum, $req, $idAuthority);

                if($isSent == FALSE){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/openForum/'.$idForum)
                        ->with('error','Your post has been posted successfully but without notification email.');
                }

                return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum/openForum/'.$idForum)
                    ->with('success','Your post has been posted successfully.');

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function sentNotificationToDestination($idCoursesClass, $idForum, $req, $idAuthority){
        $destinationIdMember = "";
        $idForumMessageQuote = "0";

        if(!empty($req->idForumMessageQuote)){
            $idForumMessageQuote = $req->idForumMessageQuote;
            $dataForumMessage = $this->databaseConn->getForumMessageByIdForumMessage($idForumMessageQuote)->first();
            $destinationIdMember = $dataForumMessage->idMember;
        }else{
            $dataForum = $this->databaseConn->getForumDataByIdForum($idForum)->first();
            $destinationIdMember = $dataForum->idMemberCreator;
        }

        $dataDestinationMember = $this->databaseConn->getAccountDataByIdMember($destinationIdMember)->first();
        $idAuthorityDestinationMember = $dataDestinationMember->idAuthority;

        $arrayUserNameAndEmail = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority(session('idMember'), $idAuthority);

        $arrayNameAndEmailDestinationMember = $this->databaseConn->getUserEmailAndNameByIdMemberAndIdAuthority($destinationIdMember,$idAuthorityDestinationMember);

        $destinationEmail = $arrayNameAndEmailDestinationMember[0];
        $destinationName = $arrayNameAndEmailDestinationMember[1];

        $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

        $CourseName = "";

        if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
            $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
        }else {
            $CourseName = $getOnlineClassData->first()->CourseName;
        }

        $subject = "Forum Reply At ".date('d F Y H:i:s')." From ".$arrayUserNameAndEmail[1]."  In ".$CourseName;

        $content = $this->mail->sendForumReplyNotification($idCoursesClass, $idForum, $req, $arrayUserNameAndEmail[1], $destinationName);

        $this->mail->html_email($destinationEmail, $destinationName, "SBM ITB TK-Low Online Course - ".$subject, $content);

        return TRUE;
    }

    public function ownerForumCloseThread($idCoursesClass, $idForum){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $getForumData = $this->databaseConn->getForumDataByIdForum($idForum);

                if($getForumData->count() == 0){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Something wrong was happen. Forum data not found.');
                }else{
                    if($getForumData->first()->idMemberCreator !== session('idMember')){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Sorry, You are not the owner to close this thread.');
                    }
                    if($getForumData->first()->isClosed == "1"){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Sorry, this thread has been closed by the owner.');
                    }
                }

                $this->databaseConn->closeForumThread($idForum)->with('success','This forum has been closed successfully.');

                return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum');
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function sendPrivateMessageToCreator($idCoursesClass, $idForum){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                    if($getMentor == 0){
                        return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                    }
                }

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }
                }

                $getForumData = $this->databaseConn->getForumDataByIdForum($idForum);

                if($getForumData->count() == 0){
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Something wrong was happen. Forum data not found.');
                }else{
                    if($getForumData->first()->idMemberCreator == session('idMember')){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageForum')->with('error','Sorry, You can not send message to your self.');
                    }
                }

                $destinationUsername = $this->databaseConn->getAccountDataByIdMember($getForumData->first()->idMemberCreator)->first()->Username;

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.composeNewPrivateMessage', compact('idCoursesClass', 'destinationUsername'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));

            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function sendPrivateMessageToStudent($idCoursesClass, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if($getOnlineClassData->count() == 0){
                    return redirect('/dashboard')->with('error','Online course you wished to open was not found!.');
                }

                if($getOnlineClassData->first()->IsOpened == "0"){
                    return redirect('/dashboard')->with('error','Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;


                if ($idAuthority == "3") {
                    $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($getMentor == 0) {
                        return "You are not assigned as mentor on this course. Please contact your IT Support if this was a mistake.";
                    }
                }


                if($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if($isStudent == 0){
                        return redirect('/dashboard')->with('error','You have not enrolled this Online Class!');
                    }
                }

                $destinationUsername = $this->databaseConn->getAccountDataByIdMember($idMember)->first()->Username;

                $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                $content = view('dashboardUI.dashboardCommunication.composeNewPrivateMessage', compact('idCoursesClass', 'destinationUsername'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }
}