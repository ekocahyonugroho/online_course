<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 15/11/17
 * Time: 11:10
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



class OnlineClassController extends Controller
{
    function __construct()
    {
        $this->databaseConn = new Database_communication();
        $this->appHelper = new appHelper();
        $this->mail = new MailController();
        $this->FormUI = new FormUserInterface();
    }

    public function manageSubTopic($idCoursesClass, $idTopic, $idSubTopic){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $getSubTopicData = $this->databaseConn->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();

                    switch($getSubTopicData->subTopicType) {
                        case "1" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Reading', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                        case "2" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                        case "3" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageTopic', compact('idCoursesClass','idTopic'));
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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


    public function submitArticle(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'titleArticle' => [
                            'required'
                        ],
                        'article' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->withErrors($validator);
                    }

                    $this->databaseConn->submitSubTopicArticle($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)->with('success','Your article has been inserted into database successfully.');
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


    public function previewMaterial($idCoursesClass, $idTopic, $idSubTopic, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idMaterial = $req->idMaterial;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $dataMaterial = $this->databaseConn->getCoursesClassSubTopicMaterialByIdMaterial($idMaterial)->first();

                    if(empty($dataMaterial)){
                        return redirect('/dashboard')->with('error','Material you find was not found!');
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.viewMaterial', compact('idCoursesClass', 'idTopic','idSubTopic', 'idMaterial'));

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                    //return view('dashboardUI.dashboardContents.pdfView', compact('dataMaterial'))->render();
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

    public function submitMaterials(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;
        $materialType = $req->selectMaterialType;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    if($materialType === "0" OR empty($materialType)){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('error','Please choose the correct material type! Submitted type was '.$materialType);
                    }

                    if($materialType === "article"){
                        $this->submitArticle($req);
                    }else if($materialType === "youtube"){
                        $this->submitVideo($req);
                    }else if($materialType === "external"){
                        $this->submitExternal($req);
                    }else if($materialType === "file"){
                        if( $req->hasFile('uploadFile') ) {
                            $file = $req->file('uploadFile');
                            $fileExtention = $file->clientExtension();

                            if ($fileExtention === "pdf" OR $fileExtention === "PDF") {
                                $this->submitPDF($req, "pdf");
                            } else if ($fileExtention === "pptx" OR $fileExtention === "PPTX" OR $fileExtention === "ppt" OR $fileExtention === "PPT") {
                                $this->submitPDF($req, "ppt");
                            }else{
                                $this->submitFile($req);
                            }
                        }else{
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with("error","You have to select a file before submit!");
                        }
                    }

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic);

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

    public function submitPDF(Request $req, $type="pdf"){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    if( $req->hasFile('uploadFile') ) {

                        $validator = Validator::make($req->all(), [
                            'titleFile' => [
                                'required'
                            ],
                            'uploadFile' => [
                                'required',
                                'mimes:pdf,PDF,ppt,PPT,pptx,PPTX'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)
                                ->withErrors($validator);
                        }

                        $dateTime = date('Y_m_d_H_i_s');

                        $imageFolder = '/files/material/file/'.$idTopic.'/'.$idSubTopic.'/'.$dateTime.'/';

                        $destination = base_path().'/public'.$imageFolder;

                        $file = $req->file('uploadFile');
                        $fileExtention = $file->clientExtension();

                        $fileName = $req->titleFile.".".$fileExtention;

                        $completePathFile = $imageFolder.$fileName;

                        if($file->move($destination, $fileName)) {
                            $this->databaseConn->submitSubTopicFile($req, $completePathFile,$type);
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('success', 'Your PDF has been inserted into database successfully.');
                        }else{
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('error', 'Failed to be uploaded.');
                        }
                    }else{
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('error', 'PDF data is empty.');
                    }
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

    public function submitPPT(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }



                        $validator = Validator::make($req->all(), [
                            'titleFile' => [
                                'required'
                            ],
                            'pptURL' => [
                                'required'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)
                                ->withErrors($validator);
                        }


                    $this->databaseConn->submitSubTopicFile($req, $req->pptURL,'ppt');
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('success', 'Your PPT file has been inserted into database successfully.');

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

    public function submitVideo(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }



                    $validator = Validator::make($req->all(), [
                        'titleYoutube' => [
                            'required'
                        ],
                        'videoURL' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)
                            ->withErrors($validator);
                    }


                    $this->databaseConn->submitSubTopicFile($req, $req->videoURL,'video');
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('success', 'Your Video file has been inserted into database successfully.');

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

    public function submitFile(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    if( $req->hasFile('uploadFile') ) {

                        $validator = Validator::make($req->all(), [
                            'titleFile' => [
                                'required'
                            ],
                            'uploadFile' => [
                                'required'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)
                                ->withErrors($validator);
                        }

                        $dateTime = date('Y_m_d_H_i_s');

                        $imageFolder = '/files/material/file/'.$idTopic.'/'.$idSubTopic.'/'.$dateTime.'/';

                        $destination = base_path().'/public'.$imageFolder;

                        $file = $req->file('uploadFile');
                        $fileExtention = $file->clientExtension();

                        $fileName = $req->titleFile.".".$fileExtention;

                        $completePathFile = $imageFolder.$fileName;

                        if($file->move($destination, $fileName)) {
                            $this->databaseConn->submitSubTopicFile($req, $completePathFile,'file');
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('success', 'Your File has been inserted into database successfully.');
                        }else{
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('error', 'Failed to be uploaded.');
                        }
                    }else{
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('error', 'File data is empty.');
                    }
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

    public function submitExternal(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;
        $idSubTopic = $req->idSubTopic;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }



                    $validator = Validator::make($req->all(), [
                        'titleExternal' => [
                            'required'
                        ],
                        'externalURL' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)
                            ->withErrors($validator);
                    }


                    $this->databaseConn->submitSubTopicFile($req, $req->externalURL,'external');
                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/' . $idCoursesClass . '/manageSession/' . $idTopic . '/' . $idSubTopic)->with('success', 'Your external reference has been inserted into database successfully.');

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

    public function deleteMaterial($idCoursesClass, $idTopic, $idSubTopic, $idMaterial){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $dataMaterial = $this->databaseConn->getCoursesClassSubTopicMaterialByIdMaterial($idMaterial)->first();

                    if(empty($dataMaterial)){
                        return redirect('/dashboard')->with('error','Material you find was not found!');
                    }

                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . $dataMaterial->contentMaterial)) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . $dataMaterial->contentMaterial);
                    }

                    $this->databaseConn->deleteSubTopicMaterialByIdMaterial($idMaterial);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)->with('success','Selected material has been deleted successfully.');
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

    public function submitCreateExam($idCoursesClass, $idTopic, $idSubTopic, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'description' => [
                            'required'

                        ],
                        'deadline' => [
                            'required'
                        ],
                        'minScore' => [
                            'required'
                        ],
                        'maxScore' => [
                            'required'
                        ],
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->withErrors($validator);
                    }

                    $dataOnlineClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

                    $deadline = date('Y-m-d H:i:s', strtotime($req->deadline));
                    $openedClass = date('Y-m-d H:i:s', strtotime($dataOnlineClass->OpenedStart));
                    $endedClass = date('Y-m-d H:i:s', strtotime($dataOnlineClass->OpenedEnd));

                    if($deadline < $openedClass OR $deadline > $endedClass){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->with('error','You have to choose deadline inside Online Class date range between '.$openedClass.' and '.$endedClass);
                    }

                    if($req->maxScore < $req->minScore){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->with('error','You are not allowed to set Max Score is less than Min Score!');
                    }

                    $idExam = $this->databaseConn->insertNewExam($idSubTopic,$req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                        ->with('success','Exam has been created successfully. Please continue to create question and its requirements.');

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

    public function editExam($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    switch(strtoupper($typeExam)) {
                        case "WRITTEN" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_Written', compact('idCoursesClass','idTopic', 'idSubTopic','idExam'));
                            break;
                        case "UPLOAD" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_Upload', compact('idCoursesClass','idTopic', 'idSubTopic','idExam'));
                            break;
                        case "CHOICES" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_Choices', compact('idCoursesClass','idTopic', 'idSubTopic','idExam'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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

    public function submitCreateAssignment($idCoursesClass, $idTopic, $idSubTopic, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'description' => [
                            'required'

                        ],
                        'deadline' => [
                            'required'
                        ],
                        'minScore' => [
                            'required'
                        ],
                        'maxScore' => [
                            'required'
                        ],
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->withErrors($validator);
                    }

                    $dataOnlineClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

                    $deadline = date('Y-m-d H:i:s', strtotime($req->deadline));
                    $openedClass = date('Y-m-d H:i:s', strtotime($dataOnlineClass->OpenedStart));
                    $endedClass = date('Y-m-d H:i:s', strtotime($dataOnlineClass->OpenedEnd));

                    if($deadline < $openedClass OR $deadline > $endedClass){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->with('error','You have to choose deadline inside Online Class date range between '.$openedClass.' and '.$endedClass);
                    }

                    if($req->maxScore < $req->minScore){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)
                            ->with('error','You are not allowed to set Max Score is less than Min Score!');
                    }

                    $idAssignment = $this->databaseConn->insertNewAssignment($idSubTopic,$req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                        ->with('success','Assignment has been created successfully. Please continue to create question and its requirements.');

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

    public function editAssignment($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    switch(strtoupper($typeAssignment)) {
                        case "WRITTEN" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_Written', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment'));
                            break;
                        case "UPLOAD" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_Upload', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment'));
                            break;
                        case "CHOICES" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_Choices', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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

    public function submitNewQuestion($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'question' => [
                            'required'

                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                            ->withErrors($validator);
                    }

                    if(strtoupper($req->typeAssignment) == "CHOICES"){
                        $validator = Validator::make($req->all(), [
                            'choiceText.*' => [
                                'required'
                            ],
                            'scoreChoice.*' => [
                                'required',
                                'numeric'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                                ->withErrors($validator);
                        }
                    }

                    $this->databaseConn->insertNewAssignmentQuestion($idAssignment, $req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                        ->with('success','The question has been inserted successfully.');

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

    public function submitNewExamQuestion($idCoursesClass, $idTopic, $idSubTopic, $idExam, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'question' => [
                            'required'

                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                            ->withErrors($validator);
                    }

                    if(strtoupper($req->typeExam) == "CHOICES"){
                        $validator = Validator::make($req->all(), [
                            'choiceText.*' => [
                                'required'
                            ],
                            'scoreChoice.*' => [
                                'required',
                                'numeric'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                                ->withErrors($validator);
                        }
                    }

                    $this->databaseConn->insertNewExamQuestion($idExam, $req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                        ->with('success','The question has been inserted successfully.');

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

    public function submitEditQuestion($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'editQuestion' => [
                            'required'

                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                            ->withErrors($validator);
                    }

                    if(strtoupper($req->typeAssignment) == "CHOICES"){
                        $validator = Validator::make($req->all(), [
                            'choiceText.*' => [
                                'required'
                            ],
                            'scoreChoice.*' => [
                                'required',
                                'numeric'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                                ->withErrors($validator);
                        }
                    }

                    $this->databaseConn->updateAssignmentQuestion($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$req->typeAssignment.'/editAssignment')
                        ->with('success','The question has been updated successfully.');

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

    public function submitEditExamQuestion($idCoursesClass, $idTopic, $idSubTopic, $idExam, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'editQuestion' => [
                            'required'

                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                            ->withErrors($validator);
                    }

                    if(strtoupper($req->typeExam) == "CHOICES"){
                        $validator = Validator::make($req->all(), [
                            'choiceText.*' => [
                                'required'
                            ],
                            'scoreChoice.*' => [
                                'required',
                                'numeric'
                            ]
                        ]);

                        if ($validator->fails()) {
                            $messages = $validator->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                                ->withErrors($validator);
                        }
                    }

                    $this->databaseConn->updateExamQuestion($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$req->typeExam.'/editExam')
                        ->with('success','The question has been updated successfully.');

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

    public function previewEditQuestion(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {
                    $idQuestion = $req->idQuestion;
                    $idCoursesClass = $req->idCoursesClass;

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return "You are not assigned as mentor on this course. Please contact your IT Support if this was a mistake.";
                        }
                    }

                    $data = "success&nbsp;";
                    $data .= $idQuestion."&nbsp;";

                    $getQuestionData = $this->databaseConn->getCoursesClassAssignmentQuestionByIdQuestion($idQuestion)->first();

                    $data .= $getQuestionData->Question."&nbsp;";

                    if(strtoupper($req->typeAssignment) == "CHOICES"){
                        $choiceNum = 0;
                        $getQuestionChoices = $this->databaseConn->getAssignmentAnswerChoicesByIdQuestion($idQuestion)->get();

                        foreach($getQuestionChoices AS $dataChoices){
                            if($choiceNum == 0){
                                $data .= "<div  class=\"input-group\">
                                    <span class=\"input-group-addon\">Answer Choice</span>
                                    <input value='$dataChoices->answerText' type=\"text\" class=\"form-control\" name=\"choiceText[]\" />
                                    <span class=\"input-group-addon\" style=\"border-left: 0; border-right: 0;\">Score Value</span>
                                    <input value='$dataChoices->choiceScore' type=\"text\" class=\"form-control\" name=\"scoreChoice[]\" />
                                    <button type='button' onclick='addChoices()' class=\"btn btn-info add_field_button\"><i class=\"fa fa-plus-circle\" aria-hidden=\"true\"></i></button>
                                  </div>";
                            }else {
                                $data .= "<div class=\"input-group\" style=\"padding-top: 10px;\">";
                                $data .= "<span class=\"input-group-addon\">Answer Choice</span>";
                                $data .= "<input value='$dataChoices->answerText' type=\"text\" class=\"form-control\" name=\"choiceText[]\" />";
                                $data .= "<span class=\"input-group-addon\" style=\"border-left: 0; border-right: 0;\">Score Value</span>";
                                $data .= "<input value='$dataChoices->choiceScore' type=\"text\" class=\"form-control\" name=\"scoreChoice[]\" />";
                                $data .= "<button class=\"btn btn-danger remove remove_field\" type=\"button\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></button>";
                                $data .= "</div>";
                            }
                            $choiceNum++;
                        }

                        $data .= "&nbsp;";
                    }

                    return $data;

                }else{
                    return "You do not have any privillage to access this part.";
                }
            }else{
                return "Your login credentials was not found in member database. Please contact our IT Support.";
            }
        }else{
            return "System can not process your request. Please try to re-login.";
        }
    }

    public function previewEditExamQuestion(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {
                    $idQuestion = $req->idQuestion;
                    $idCoursesClass = $req->idCoursesClass;

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return "You are not assigned as mentor on this course. Please contact your IT Support if this was a mistake.";
                        }
                    }

                    $data = "success&nbsp;";
                    $data .= $idQuestion."&nbsp;";

                    $getQuestionData = $this->databaseConn->getCoursesClassExamQuestionByIdQuestion($idQuestion)->first();

                    $data .= $getQuestionData->Question."&nbsp;";

                    if(strtoupper($req->typeExam) == "CHOICES"){
                        $choiceNum = 0;
                        $getQuestionChoices = $this->databaseConn->getExamAnswerChoicesByIdQuestion($idQuestion)->get();

                        foreach($getQuestionChoices AS $dataChoices){
                            if($choiceNum == 0){
                                $data .= "<div  class=\"input-group\">
                                    <span class=\"input-group-addon\">Answer Choice</span>
                                    <input value='$dataChoices->answerText' type=\"text\" class=\"form-control\" name=\"choiceText[]\" />
                                    <span class=\"input-group-addon\" style=\"border-left: 0; border-right: 0;\">Score Value</span>
                                    <input value='$dataChoices->choiceScore' type=\"text\" class=\"form-control\" name=\"scoreChoice[]\" />
                                    <button type='button' onclick='addChoices()' class=\"btn btn-info add_field_button\"><i class=\"fa fa-plus-circle\" aria-hidden=\"true\"></i></button>
                                  </div>";
                            }else {
                                $data .= "<div class=\"input-group\" style=\"padding-top: 10px;\">";
                                $data .= "<span class=\"input-group-addon\">Answer Choice</span>";
                                $data .= "<input value='$dataChoices->answerText' type=\"text\" class=\"form-control\" name=\"choiceText[]\" />";
                                $data .= "<span class=\"input-group-addon\" style=\"border-left: 0; border-right: 0;\">Score Value</span>";
                                $data .= "<input value='$dataChoices->choiceScore' type=\"text\" class=\"form-control\" name=\"scoreChoice[]\" />";
                                $data .= "<button class=\"btn btn-danger remove remove_field\" type=\"button\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></button>";
                                $data .= "</div>";
                            }
                            $choiceNum++;
                        }

                        $data .= "&nbsp;";
                    }

                    return $data;

                }else{
                    return "You do not have any privillage to access this part.";
                }
            }else{
                return "Your login credentials was not found in member database. Please contact our IT Support.";
            }
        }else{
            return "System can not process your request. Please try to re-login.";
        }
    }

    public function deleteSubTopic($idCoursesClass, $idTopic, $idSubTopic){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $this->databaseConn->deleteSubTopicByIdSubTopic($idSubTopic);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic)
                        ->with('success','The Sub Topic has been deleted successfully.');

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

    public function deleteQuestion($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idQuestion){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $this->databaseConn->deleteAssignmentQuestionByIdQuestion($idQuestion);

                    if(strtoupper($typeAssignment) == "CHOICES"){
                        $this->databaseConn->deleteAssignmentQuestionChoicesByIdQuestion($idQuestion);
                    }

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$typeAssignment.'/editAssignment')
                        ->with('success','The question has been deleted successfully.');

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

    public function deleteAssignment($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $this->databaseConn->deleteOnlineCourseAssignmentByIdAssignment($idAssignment, $typeAssignment);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)->with('success','Assignment data has been deleted successfully.');
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

    public function deleteExam($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $this->databaseConn->deleteOnlineCourseExamByIdExam($idExam, $typeExam);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic)->with('success','Exam data has been deleted successfully.');
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

    public function showCompletedStudents($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_CompletedStudents', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment','typeAssignment'));

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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

    public function showCompletedExamStudents($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_CompletedStudents', compact('idCoursesClass','idTopic', 'idSubTopic','idExam','typeExam'));

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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
    
    public function showCompletedStudentsAnswer($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    switch(strtoupper($typeAssignment)) {
                        case "WRITTEN" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_EvaluateWritten', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment', 'idMember'));
                            break;
                        case "UPLOAD" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_EvaluateUpload', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment', 'idMember'));
                            break;
                        case "CHOICES" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment_EvaluateChoices', compact('idCoursesClass','idTopic', 'idSubTopic','idAssignment', 'idMember'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Assignment', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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

    public function showCompletedStudentsExamAnswer($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    switch(strtoupper($typeExam)) {
                        case "WRITTEN" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_EvaluateWritten', compact('idCoursesClass','idTopic', 'idSubTopic','idExam', 'idMember'));
                            break;
                        case "UPLOAD" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_EvaluateUpload', compact('idCoursesClass','idTopic', 'idSubTopic','idExam', 'idMember'));
                            break;
                        case "CHOICES" :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam_EvaluateChoices', compact('idCoursesClass','idTopic', 'idSubTopic','idExam', 'idMember'));
                            break;
                        default :
                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageSubTopic_Exam', compact('idCoursesClass','idTopic', 'idSubTopic'));
                            break;
                    }

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageTopic', compact('subcontent','idCoursesClass','idTopic'));

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

    public function submitEvaluation($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idMember, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'score' => [
                            'required',
                            'numeric'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$typeAssignment.'/evaluateAssignment/'.$idMember)
                            ->withErrors($validator);
                    }

                    $this->databaseConn->evaluateStudentAnswerByIdQuestionAndIdMember($idMember, $req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$typeAssignment.'/evaluateAssignment/'.$idMember)
                        ->with('success','You have scored this answer successfully.');
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

    public function submitExamEvaluation($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idMember, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $validator = Validator::make($req->all(), [
                        'score' => [
                            'required',
                            'numeric'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$typeExam.'/evaluateExam/'.$idMember)
                            ->withErrors($validator);
                    }

                    $this->databaseConn->evaluateStudentExamAnswerByIdQuestionAndIdMember($idMember, $req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$typeExam.'/evaluateExam/'.$idMember)
                        ->with('success','You have scored this answer successfully.');
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

    public function finishEvaluation($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $getAssignmentCompletion = $this->databaseConn->getAssignmentCompletionByIdAssignmentAndIdMember($idAssignment, $idMember)->first();

                    $message = "";
                    $messageStatus = "";

                    if($getAssignmentCompletion->isEvaluated == "0"){
                        $messageStatus = "success";
                        $message = "You have finished selected assignment evaluation successfully.";

                        $this->databaseConn->finishEvaluationByIdAssignmentAndIdMember($idAssignment, $idMember);


                        $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                        $CourseName = "";

                        if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                            $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                        }else{
                            $CourseName = $getOnlineClassData->first()->CourseName;
                        }

                        $subject = "Assignment Evaluation Report of ".$CourseName;

                        $idAuthority = $this->databaseConn->getAccountDataByIdMember($idMember)->first()->idAuthority;
                        $studentData = $this->databaseConn->getFullMemberData($idMember, $idAuthority)->first();

                        $studentName = "";
                        $studentEmail = "";

                        switch($idAuthority){
                            case "4":
                                $studentName = $studentData->nama;
                                $studentEmail = $studentData->email;
                                break;
                            case "5":
                                $studentName = $studentData->nameFirst." ".$studentData->nameLast;
                                $studentEmail = $studentData->emailAddress;
                                break;
                        }

                        $content = $this->mail->getFinishEvaluationEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idAssignment, $typeAssignment, $studentData);
                        $this->mail->html_email($studentEmail, $studentName, "SBM ITB TK-Low Online Course - ".$subject, $content);
                    }

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageAssignment/'.$idAssignment.'/'.$typeAssignment.'/evaluateAssignment')->with($messageStatus,$message);
                    //return $content;
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

    public function finishExamEvaluation($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {

                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }

                    $getExamCompletion = $this->databaseConn->getExamCompletionByIdExamAndIdMember($idExam, $idMember)->first();

                    $message = "";
                    $messageStatus = "";

                    if($getExamCompletion->isEvaluated == "0"){
                        $messageStatus = "success";
                        $message = "You have finished selected exam evaluation successfully.";

                        $this->databaseConn->finishEvaluationByIdExamAndIdMember($idExam, $idMember);


                        $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);

                        $CourseName = "";

                        if($getOnlineClassData->first()->isRegisteredInCurriculum == '1'){
                            $CourseName = $getOnlineClassData->first()->nama_mata_kuliah_eng;
                        }else{
                            $CourseName = $getOnlineClassData->first()->CourseName;
                        }

                        $subject = "Exam Evaluation Report of ".$CourseName;

                        $idAuthority = $this->databaseConn->getAccountDataByIdMember($idMember)->first()->idAuthority;
                        $studentData = $this->databaseConn->getFullMemberData($idMember, $idAuthority)->first();

                        $studentName = "";
                        $studentEmail = "";

                        switch($idAuthority){
                            case "4":
                                $studentName = $studentData->nama;
                                $studentEmail = $studentData->email;
                                break;
                            case "5":
                                $studentName = $studentData->nameFirst." ".$studentData->nameLast;
                                $studentEmail = $studentData->emailAddress;
                                break;
                        }

                        $content = $this->mail->getFinishExamEvaluationEmailTemplate($idCoursesClass, $idTopic, $idSubTopic, $idExam, $typeExam, $studentData);
                        $this->mail->html_email($studentEmail, $studentName, "SBM ITB TK-Low Online Course - ".$subject, $content);
                    }

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic.'/'.$idSubTopic.'/manageExam/'.$idExam.'/'.$typeExam.'/evaluateExam')->with($messageStatus,$message);
                    //return $content;
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

    public function showEnrolledStudents($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {
                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return redirect('/dashboard')->with('error', "You are not assigned as mentor on this course. Please contact your IT Support if this was a mistake.");
                        }
                    }

                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $content = view('dashboardUI.onlineClassControlDashboard.viewCourseStudents', compact('idCoursesClass'));

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                }else{
                    return redirect('/dashboard')->with("error","You do not have any privillage to access this part.");
                }
            }else{
                return redirect('/dashboard')->with('error', "Your login credentials was not found in member database. Please contact our IT Support.");
            }
        }else{
            return redirect('/dashboard')->with('error', "System can not process your request. Please try to re-login.");
        }
    }

    public function showStudentDetailProgress($idCoursesClass, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2" OR $idAuthority == "3") {
                    if($idAuthority == "3") {
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor == 0){
                            return "You are not assigned as mentor on this course. Please contact your IT Support if this was a mistake.";
                        }
                    }

                    $isStudent = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idCoursesClass, $idMember)->count();

                    if ($isStudent == 0) {
                        return redirect('/dashboard')->with('error', 'You have not enrolled this Online Class!');
                    }

                    $str = "success&nbsp;";
                    $str .= view('dashboardUI.onlineClassControlDashboard.subContent.studentDetailProgress', compact('idCoursesClass','idMember'));

                    return $str;
                }else{
                    return "You do not have any privillage to access this part.";
                }
            }else{
                return "Your login credentials was not found in member database. Please contact our IT Support.";
            }
        }else{
            return "System can not process your request. Please try to re-login.";
        }
    }
}