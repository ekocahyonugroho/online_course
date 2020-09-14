<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 20/11/17
 * Time: 8:35
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Backend\Database_communication;
use App\Http\Middleware\appHelper;
use App\Http\Middleware\CourseUserInterface;
use App\Http\Middleware\FormUserInterface;
use App\Http\Controllers\MailController;
use Validator;

class StudentController extends Controller
{
    function __construct(){
        $this->databaseConn = new Database_communication();
        $this->appHelper = new appHelper();
        $this->mail = new MailController();
        $this->FormUI = new FormUserInterface();
    }

    public function showOnlineClassDefaultHome($idCoursesClass){
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

                if($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if($isStudent == 0){
                        return redirect('/dashboard')->with('error','You have not enrolled this Online Class!');
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.EnterOnlineClassOverview', compact('idCoursesClass'));

                    $content = view('dashboardUI.onlineClassControlDashboard.EnterOnlineClass', compact('subcontent','idCoursesClass'));
                    //$content = "";

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                }else{
                    return redirect('/dashboard');
                }
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showSession($idCoursesClass, $idTopic){
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

                if($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if($isStudent == 0){
                        return redirect('/dashboard')->with('error','You have not enrolled this Online Class!');
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.EnterSession', compact('idCoursesClass','idTopic'));

                    $content = view('dashboardUI.onlineClassControlDashboard.EnterTopic', compact('subcontent','idCoursesClass','idTopic'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                }else{
                    return redirect('/dashboard');
                }
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showSubTopic($idCoursesClass, $idTopic, $idSubTopic){
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

                if($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'));

                    if($isStudent->count() == 0){
                        return redirect('/dashboard')->with('error','You have not enrolled this Online Class!');
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $getSubTopicData = $this->databaseConn->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();

                    switch($getSubTopicData->subTopicType) {
                        case "1" :
                            $getSubTopicMaterial = $this->databaseConn->getCoursesClassSubTopicMaterialByIdSubTopic($idSubTopic)->first();

                            if(is_array($getSubTopicMaterial)) {
                                if (count($getSubTopicMaterial) == 0) {
                                    $contentMaterial = "";
                                } else {
                                    $idMaterial = $getSubTopicMaterial->idMaterial;
                                    $contentMaterial = view('dashboardUI.onlineClassControlDashboard.subContent.openMaterial', compact('idCoursesClass', 'idTopic', 'idSubTopic', 'idMaterial'));
                                }
                            }else{
                                $contentMaterial = "";
                            }

                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Reading', compact('idCoursesClass','idTopic', 'idSubTopic', 'contentMaterial'));
                            break;
                        case "2" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Assignment', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                        case "3" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Exam', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.EnterSession', compact('idCoursesClass','idTopic'));
                    }

                    $this->databaseConn->insertStudentAccessSubTopicCount($isStudent->first()->idCoursesClassEnrolled,$idSubTopic);

                    $content = view('dashboardUI.onlineClassControlDashboard.EnterTopic', compact('subcontent','idCoursesClass','idTopic'));
                    

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                }else{
                    return redirect('/dashboard');
                }
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showMaterial($idCoursesClass, $idTopic, $idSubTopic, $idMaterial)
    {
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $contentMaterial = view('dashboardUI.onlineClassControlDashboard.subContent.openMaterial', compact('idCoursesClass', 'idTopic', 'idSubTopic','idMaterial'));

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Reading', compact('idCoursesClass', 'idTopic', 'idSubTopic','contentMaterial'));

                    $content = view('dashboardUI.onlineClassControlDashboard.EnterTopic', compact('subcontent', 'idCoursesClass', 'idTopic'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function enterAssignment($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment)
    {
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $getAssignmentData = $this->databaseConn->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment);

                    if($getAssignmentData->count() == 0){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','Assignment you wish to find is not found!');
                    }

                    $isClosed = '0';

                    $dateTime = date('Y-m-d H:i:s');
                    $deadline = date('Y-m-d H:i:s', strtotime($getAssignmentData->first()->assignmentDeadline));

                    if($dateTime > $deadline){
                        $isClosed = '1';
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    switch(strtoupper($typeAssignment)) {
                        case "WRITTEN" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Assignment_Written', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment', 'isClosed'));
                            break;
                        case "UPLOAD" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Assignment_Upload', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment', 'isClosed'));
                            break;
                        case "CHOICES" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Assignment_Choices', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment', 'isClosed'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Assignment', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.EnterTopic', compact('subcontent', 'idCoursesClass', 'idTopic'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function enterExam($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam)
    {
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $getExamData = $this->databaseConn->getCoursesClassSubTopicExamByIdExam($idExam);

                    if($getExamData->count() == 0){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','Exam you wish to find is not found!');
                    }

                    $isClosed = '0';

                    $dateTime = date('Y-m-d H:i:s');
                    $deadline = date('Y-m-d H:i:s', strtotime($getExamData->first()->examDeadline));

                    if($dateTime > $deadline){
                        $isClosed = '1';
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    switch(strtoupper($typeExam)) {
                        case "WRITTEN" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Exam_Written', compact('idCoursesClass','idTopic', 'idSubTopic','idExam', 'isClosed'));
                            break;
                        case "UPLOAD" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Exam_Upload', compact('idCoursesClass','idTopic', 'idSubTopic','idExam', 'isClosed'));
                            break;
                        case "CHOICES" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Exam_Choices', compact('idCoursesClass','idTopic', 'idSubTopic','idExam', 'isClosed'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.enterSubTopic_Exam', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.EnterTopic', compact('subcontent', 'idCoursesClass', 'idTopic'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function submitAssignmentAnswer($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $getAssignmentData = $this->databaseConn->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment);

                    if($getAssignmentData->count() == 0){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','Assignment you wish to find is not found!');
                    }

                    $dateTime = date('Y-m-d H:i:s');
                    $deadline = date('Y-m-d H:i:s', strtotime($getAssignmentData->first()->assignmentDeadline));

                    if($dateTime > $deadline){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','This Assignment has been closed because has passed the deadline!!');
                    }

                    switch(strtoupper($typeAssignment)) {
                        case "WRITTEN" :
                            $result = $this->insertWrittenAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, $req);
                            break;
                        case "UPLOAD" :
                            $result = $this->uploadAnswerFile($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, $req);
                            break;
                        case "CHOICES" :
                            $result = $this->insertChoicesAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, $req);
                            break;
                        default :
                            $result = redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)
                                ->with('error',strtoupper($typeAssignment).' assignment type is not found on the system.');
                            break;
                    }

                    return $result;
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function submitExamAnswer($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $getExamData = $this->databaseConn->getCoursesClassSubTopicExamByIdExam($idExam);

                    if($getExamData->count() == 0){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','Exam you wish to find is not found!');
                    }

                    $dateTime = date('Y-m-d H:i:s');
                    $deadline = date('Y-m-d H:i:s', strtotime($getExamData->first()->examDeadline));

                    if($dateTime > $deadline){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','This Exam has been closed because has passed the deadline!!');
                    }

                    switch(strtoupper($typeExam)) {
                        case "WRITTEN" :
                            $result = $this->insertExamWrittenAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, $req);
                            break;
                        case "UPLOAD" :
                            $result = $this->uploadExamAnswerFile($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, $req);
                            break;
                        case "CHOICES" :
                            $result = $this->insertExamChoicesAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, $req);
                            break;
                        default :
                            $result = redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)
                                ->with('error',strtoupper($typeExam).' exam type is not found on the system.');
                            break;
                    }

                    return $result;
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    private function uploadAnswerFile($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, $req){
        if( $req->hasFile('fileAnswer') ) {

            $validator = Validator::make($req->all(), [
                'fileAnswer' => [
                    'required'
                ]
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();

                // redirect our user back to the form with the errors from the validator
                return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)
                    ->withErrors($validator);
            }

            $dateTime = date('Y_m_d_H_i_s');

            $imageFolder = '/files/assignment/answer/file/'.$idTopic.'/'.$idSubTopic.'/'.$idAssignment.'/'.$idQuestion.'/'.session('idMember').'/'.$dateTime.'/';

            $destination = base_path().'/public'.$imageFolder;

            $file = $req->file('fileAnswer');
            $fileExtention = $file->clientExtension();
            $fileName = $file->getClientOriginalName();

            $fileName = $fileName.".".$fileExtention;

            $completePathFile = $imageFolder.$fileName;

            if($file->move($destination, $fileName)) {
                $this->databaseConn->submitCreatedCoursesClassAssignmentAnswer($idQuestion, $completePathFile);
                return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)->with('success', 'Your answer has been uploaded and recorded successfully.');
            }else{
                return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)->with('error', 'Failed to be uploaded.');
            }
        }else{
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)->with('error', 'File data is empty.');
        }
    }

    private function uploadExamAnswerFile($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, $req){
        if( $req->hasFile('fileAnswer') ) {

            $validator = Validator::make($req->all(), [
                'fileAnswer' => [
                    'required'
                ]
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();

                // redirect our user back to the form with the errors from the validator
                return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)
                    ->withErrors($validator);
            }

            $dateTime = date('Y_m_d_H_i_s');

            $imageFolder = '/files/exam/answer/file/'.$idTopic.'/'.$idSubTopic.'/'.$idExam.'/'.$idQuestion.'/'.session('idMember').'/'.$dateTime.'/';

            $destination = base_path().'/public'.$imageFolder;

            $file = $req->file('fileAnswer');
            $fileExtention = $file->clientExtension();
            $fileName = $file->getClientOriginalName();

            $fileName = $fileName.".".$fileExtention;

            $completePathFile = $imageFolder.$fileName;

            if($file->move($destination, $fileName)) {
                $this->databaseConn->submitCreatedCoursesClassExamAnswer($idQuestion, $completePathFile);
                return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)->with('success', 'Your answer has been uploaded and recorded successfully.');
            }else{
                return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)->with('error', 'Failed to be uploaded.');
            }
        }else{
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)->with('error', 'File data is empty.');
        }
    }

    private function insertChoicesAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, $req){
        $validator = Validator::make($req->all(), [
            'answer' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)
                ->withErrors($validator);
        }

        $getChoiceValue = $this->databaseConn->getCoursesClassAssignmentChoiceValueByIdChoice($req->answer)->first();

        if(empty($getChoiceValue)){
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)
                ->with('error','Failed to process your answer. Selected choice was not found on the system. ID : '.$req->answer);
        }

        $this->databaseConn->submitCreatedCoursesClassAssignmentChoicesAnswer($idQuestion, $req->answer, $getChoiceValue->choiceScore);

        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)->with('success', 'Your answer has been uploaded and recorded successfully.');
    }

    private function insertExamChoicesAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, $req){
        $validator = Validator::make($req->all(), [
            'answer' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)
                ->withErrors($validator);
        }

        $getChoiceValue = $this->databaseConn->getCoursesClassExamChoiceValueByIdChoice($req->answer)->first();

        if(empty($getChoiceValue)){
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)
                ->with('error','Failed to process your answer. Selected choice was not found on the system. ID : $req->answer');
        }

        $this->databaseConn->submitCreatedCoursesClassExamChoicesAnswer($idQuestion, $req->answer, $getChoiceValue->choiceScore);

        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)->with('success', 'Your answer has been uploaded and recorded successfully.');
    }

    private function insertWrittenAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion, $req){
        $validator = Validator::make($req->all(), [
            'answer'.$idQuestion => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)
                ->with('error','Answer value is empty.');
        }

        $this->databaseConn->submitCreatedCoursesClassAssignmentAnswer($idQuestion, $req->input('answer'.$idQuestion));

        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterAssignment/'.$idAssignment.'/'.$typeAssignment)->with('success', 'Your answer has been uploaded and recorded successfully.');
    }

    private function insertExamWrittenAnswerValue($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idQuestion, $req){
        $validator = Validator::make($req->all(), [
            'answer'.$idQuestion => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)
                ->with('error','Answer value is empty.');
        }

        $this->databaseConn->submitCreatedCoursesClassExamAnswer($idQuestion, $req->input('answer'.$idQuestion));

        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic.'/enterExam/'.$idExam.'/'.$typeExam)->with('success', 'Your answer has been uploaded and recorded successfully.');
    }

    public function completeAssignment($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $getAssignmentData = $this->databaseConn->getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment);

                    if($getAssignmentData->count() == 0){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','Assignment you wish to find is not found!');
                    }

                    $dateTime = date('Y-m-d H:i:s');
                    $deadline = date('Y-m-d H:i:s', strtotime($getAssignmentData->first()->assignmentDeadline));

                    if($dateTime > $deadline){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','This Assignment has been closed because has passed the deadline!!');
                    }

                    $this->databaseConn->completeCreatedCoursesClassAssignmentByIdAssignmentAndIdMember($idAssignment, session('idMember'));

                    $getMentorData = $this->databaseConn->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();

                    foreach ($getMentorData AS $data) {
                        $CourseName = "";

                        if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                            $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                        }else{
                            $CourseName = $getOnlineClassData->first()->CourseName;
                        }

                        $subject = "Student Assignment Completion of ".$CourseName;

                        $content = $this->mail->getStudentAssignmentCompletionEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, session('idMember'), $idAuthority, $data->name);

                        $this->mail->html_email($data->email, $data->name, "SBM ITB TK-Low Online Course - ".$subject, $content);
                    }
                    return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('success','This Assignment has been completed successfully.');
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function completeExam($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $getExamData = $this->databaseConn->getCoursesClassSubTopicExamByIdExam($idExam);

                    if($getExamData->count() == 0){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','Exam you wish to find is not found!');
                    }

                    $dateTime = date('Y-m-d H:i:s');
                    $deadline = date('Y-m-d H:i:s', strtotime($getExamData->first()->examDeadline));

                    if($dateTime > $deadline){
                        return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('error','This Exam has been closed because has passed the deadline!!');
                    }

                    $this->databaseConn->completeCreatedCoursesClassExamByIdExamAndIdMember($idExam, session('idMember'));

                    $getMentorData = $this->databaseConn->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();

                    foreach ($getMentorData AS $data) {
                        $CourseName = "";

                        if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                            $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                        }else{
                            $CourseName = $getOnlineClassData->first()->CourseName;
                        }

                        $subject = "Student Exam Completion of ".$CourseName;

                        $content = $this->mail->getStudentExamCompletionEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, session('idMember'), $idAuthority, $data->name);

                        $this->mail->html_email($data->email, $data->name, "SBM ITB TK-Low Online Course - ".$subject, $content);
                    }
                    return redirect('/myCourse/enterClass/'.$idCoursesClass.'/enterSession/'.$idTopic.'/'.$idSubTopic)->with('success','This Exam has been completed successfully.');
                } else {
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function showCourseProgressReport($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if ($isUserAllowed == FALSE) {
            return redirect('/logout')->with('status', 'You are not allowed to access this system by our administrator.');
        }

        if (session('idMember')) {
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if ($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                if ($getOnlineClassData->count() == 0) {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open was not found!.');
                }

                if ($getOnlineClassData->first()->IsOpened == "0") {
                    return redirect('/dashboard')->with('error', 'Online course you wished to open is closed!');
                }

                $idAuthority = $dataMember->idAuthority;

                if ($idAuthority == "4" OR $idAuthority == "5") {
                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $idUser = session('idMember');

                    $content = view('dashboardUI.dashboardContents.StudentProgressDashboard', compact('idCoursesClass', 'idUser'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                }
            } else {
                return redirect('/logout')->with('status', 'Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        } else {
            return redirect('/')->with('status', 'error');
        }
    }

    public function getCompleteStatusByIdTopicAndIdUserAndIdCoursesClass($idTopic, $idUser, $idCoursesClass){
        $stmtSubTopic = $this->databaseConn->getCoursesClassSubTopicByIdTopic($idTopic);

        if($stmtSubTopic->count() > 0){
            $countSubTopic = $stmtSubTopic->count();
            $dataSubTopic = $stmtSubTopic->get();

            $countAccess = 0;
            foreach($dataSubTopic AS $subTopic){
                $stmtAccessSubTopic = $this->databaseConn->getStudentAccessSubTopicCount($subTopic->idSubTopic, $idUser);

                if($stmtAccessSubTopic->count() > 0){
                    $countAccess++;
                }
            }

            if($countSubTopic === $countAccess){
                return "<span class=\"badge badge-success\">COMPLETED</span>";
            }else{
                return "<span class=\"badge badge-danger\">INCOMPLETE</span>";
            }
        }else{
            return "<span class=\"badge badge-danger\">INCOMPLETE</span>";
        }
    }
}