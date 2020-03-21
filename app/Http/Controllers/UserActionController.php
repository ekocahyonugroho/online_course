<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 27/09/17
 * Time: 15:08
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

class UserActionController extends Controller
{
    function __construct(){
        $this->databaseConn = new Database_communication();
        $this->appHelper = new appHelper();
        $this->mail = new MailController();
        $this->FormUI = new FormUserInterface();
    }

    public function viewUserAccount(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                $formUI = new FormUserInterface();

                $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                $dataMember = $this->databaseConn->getFullMemberData(session('idMember'), $idAuthority)->first();

                $photoMember = $this->databaseConn->getUserPhotoByIdMember(session('idMember'))->first();

                $content = view('dashboardUI.dashboardContents.MyAccount',compact('dataMember', 'photoMember'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function updateUserInformation(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        $validator = Validator::make($req->all(), [
            'firstName' => [
                'required'
            ],
            'lastName' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            return redirect('/member/MyAccount')
                ->withErrors($validator);
        }

        $this->databaseConn->updateUserPersonalInformation($req, session('idMember'));

        return redirect('/member/MyAccount');
    }

    public function updateUserPhoto(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if( $req->hasFile('photo') ) {
            $validator = Validator::make($req->all(), [
                'photo' => [
                    'required',
                    'mimes:jpeg,jpg,png',
                    'max:1000'
                ]
            ]);

            if ($validator->fails()) {
                return redirect('/member/MyAccount')
                    ->withErrors($validator);
            }

            $imageFolder = '/images/userProfile/';
            $destination = base_path().'/public'.$imageFolder;

            $file = $req->file('photo');
            $fileExtention = $file->clientExtension();

            $fileName = session('idMember').".".$fileExtention;

            $completePathFile = $imageFolder.$fileName;

            $isPhotoExist = $this->databaseConn->getUserPhotoByIdMember(session('idMember'))->count();

            if($isPhotoExist == 0) {
                $this->databaseConn->insertFileDirectory(session('idMember'), $completePathFile);
            }else{
                $this->databaseConn->updateFileDirectory(session('idMember'), $completePathFile);
            }

            if($file->move($destination, $fileName)) {
                return redirect('/member/MyAccount');
            }
        }else{
            return redirect('/member/MyAccount')->with('error', 'Photo file is empty or failed to be uploaded.');
        }
    }

    public function showAdministratorMember(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $availableStaff = $this->databaseConn->getAvailableStaff()->get();

                    $registeredStaff = $this->databaseConn->getAdministrators()->get();

                    $content = view('dashboardUI.dashboardContents.ManageAdmin', compact('availableStaff', 'registeredStaff'));

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

    public function addAdminMember($idAuthority, $Username){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                if($dataMember->idAuthority == "1"){
                    $isExist = $this->databaseConn->getVerifiedUserByUsername($Username)->count();

                    if($isExist == 0){
                        $getSourceUserData = $this->databaseConn->getStaffSourceData($Username)->first();

                        $idMember = $this->databaseConn->registerAdministrator($idAuthority, $getSourceUserData);

                        $content = $this->mail->getWelcomingAdministratorEmailTemplate($idMember);

                        $this->mail->html_email($getSourceUserData->email, $getSourceUserData->name, "Welcome to SBM ITB TK-Low Online Course", $content);

                        return redirect('/manageMember/admin')->with('success',$Username.' has been registered successfully.');

                    }else{
                        return redirect('/manageMember/admin')->with('error','This user already exists and registered as administrator.');
                    }
                }else{
                    return redirect('/')->with('status','error');
                }
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function removeAdminMember($idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){

            if($idMember == session('idMember')){
                return redirect('/manageMember/admin')->with('error','You are not allowed to remove your own account.');
            }

            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                if($dataMember->idAuthority == "1"){
                    $dataMember = $this->databaseConn->getAccountDataByIdMember($idMember)->first();

                    if($dataMember->idAuthority == "1" OR $dataMember->idAuthority == "2"){
                        $this->databaseConn->removeMemberData($idMember);

                        return redirect('/manageMember/admin')->with('success','Administrator deleted successfully.');
                    }else{
                        return redirect('/')->with('status','error');
                    }
                }else{
                    return redirect('/')->with('status','error');
                }
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showLecturerMember(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $availableLecturer = $this->databaseConn->getRegisteredLecturers()->get();

                    $content = view('dashboardUI.dashboardContents.ManageLecturer', compact('availableLecturer'));

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

    public function showAvailableLecturer(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $getLecturerOnServer = $this->databaseConn->getUnregisteredLecturerOnServer()->get();

                    $html = "";

                    $html .= "success&nbsp;";

                    $html .= "<table id='unregisteredLecturer' class='table table-bordered'>";
                    $html .= "<thead>";
                    $html .= "<tr><th>No.</th><th>Name</th><th>Username</th><th>Action</th></tr>";
                    $html .= "</thead>";
                    $html .= "<tbody>";
                    $no = 0;
                    foreach($getLecturerOnServer AS $data){
                        $no++;
                        $html .= "<tr>";
                        $html .= "<td>".$no."</td>";
                        $html .= "<td>".$data->nama_dosen."</td>";
                        $html .= "<td>".$data->username."</td>";
                        $html .= "<td><a href='/manageMember/lecturer/addLecturer/addMember/".$data->id_user."'><button class='btn btn-success'>Add</button></a></td>";
                        $html .= "</tr>";
                    }

                    $html .= "</tbody>";
                    $html .= "</table>&nbsp;";

                    return $html;
                }else{
                    return "You do not have any authority to access this page.";
                }
            }else{
                return "You do not have any authority to access this page.";
            }
        }else{
            return "You do not have any authority to access this page.";
        }
    }

    public function addLecturer($idUserLogin){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $getSourceUserData = $this->databaseConn->getLecturerSourceData($idUserLogin)->first();

                    if(!empty($getSourceUserData)) {
                        if ($getSourceUserData->username != "-" AND $getSourceUserData->username != "") {

                            $isExist = $this->databaseConn->getVerifiedUserByUsername($getSourceUserData->username)->count();

                            if($isExist == 0) {
                                $idMember = $this->databaseConn->registerLecturer($getSourceUserData);

                                $content = $this->mail->getWelcomingLecturerEmailTemplate($idMember);

                                $this->mail->html_email($getSourceUserData->email, $getSourceUserData->nama_dosen, "Welcome to SBM ITB TK-Low Online Course", $content);

                                //$this->mail->html_email("eko.cahyo@sbm-itb.ac.id", $getSourceUserData->nama_dosen, "Welcome to SBM ITB TK-Low Online Course", $content);

                                return redirect('/manageMember/lecturer')->with('success','Lecturer added successfully.');
                            }else{
                                return redirect('/manageMember/lecturer')->with('error','Selected lecturer already exists.');
                            }
                        } else {
                            return redirect('/manageMember/lecturer')->with('error','Selected lecturer was not found on EcoSystem Server.');
                        }
                    }else{
                        return redirect('/manageMember/lecturer')->with('error','Selected lecturer was not found on EcoSystem Server.');
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

    public function showStudentMember(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $registeredStudent = $this->databaseConn->getRegisteredStudentMember()->get();

                    $content = view('dashboardUI.dashboardContents.ManageStudent', compact('registeredStudent'));

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

    public function findAvailableStudent(Request $request){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $value = $request->input('query');

                    $findStudentMemberByNIM = $this->databaseConn->getStudentMemberDataByNim($value);

                    $html = "success&nbsp;";

                    if($findStudentMemberByNIM->count() > 0) {
                        $data = $findStudentMemberByNIM->first();

                        $html .= $data->nama_program."&nbsp;";
                        $html .= $data->nama_angkatan."&nbsp;";
                        $html .= $data->nama."&nbsp;";
                        $html .= $data->Username."&nbsp;";
                        $html .= $data->email."&nbsp;";
                        $html .= "Already Registered&nbsp;";
                    }else{
                        $findStudentByNIM = $this->databaseConn->getStudentDataByNim($value);

                        if($findStudentByNIM->count() > 0){
                            $data = $findStudentByNIM->first();

                            $html .= $data->nama_program."&nbsp;";
                            $html .= $data->nama_angkatan."&nbsp;";
                            $html .= $data->nama."&nbsp;";
                            $html .= $data->username."&nbsp;";
                            $html .= $data->email."&nbsp;";
                            $html .= "Unregistered&nbsp;";
                        }else{
                            $html .= "Not Found&nbsp;";
                            $html .= "Not Found&nbsp;";
                            $html .= "Not Found&nbsp;";
                            $html .= "Not Found&nbsp;";
                            $html .= "Not Found&nbsp;";
                            $html .= "Not Found&nbsp;";
                        }
                    }

                    return $html;
                }else{
                    return "You do not have any authority to access this page.";
                }
            }else{
                return "You do not have any authority to access this page.";
            }
        }else{
            return "You do not have any authority to access this page.";
        }
    }

    public function addStudent(Request $request){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $isExist = $this->databaseConn->getStudentMemberDataByNim($request->addStudentNim)->count();
                    if($isExist == 0){
                        $dataStudent = $this->databaseConn->getStudentDataByNim($request->addStudentNim)->first();

                        if(!empty($dataStudent)) {
                            $idMember = $this->databaseConn->registerStudent($dataStudent);

                            $content = $this->mail->getWelcomingStudentEmailTemplate($idMember);

                            $this->mail->html_email($dataStudent->email, $dataStudent->nama, "Welcome to SBM ITB TK-Low Online Course", $content);

                            //$this->mail->html_email("eko.cahyo@sbm-itb.ac.id", $dataStudent->nama, "Welcome to SBM ITB TK-Low Online Course", $content);

                            return redirect('/manageMember/student')->with('success', 'Student (' . $request->addStudentNim . ') has been added successfully.');
                        }else{
                            return redirect('/manageMember/student')->with('error',$request->addStudentNim.' was not found in EcoSystem Server.');
                        }
                    }else{
                        return redirect('/manageMember/student')->with('error',$request->addStudentNim.' has been registered as member.');
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

    public function showPublicWaitingVerification(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $waitingConfirmation = $this->databaseConn->getWaitingConfirmationNewPublicMember()->get();

                    $content = view('dashboardUI.dashboardContents.ManagePublic',compact('waitingConfirmation'));

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

    public function showVerifiedPublicMember(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $confirmationReport = $this->databaseConn->getVerifiedPublicMember()->get();

                    $content = view('dashboardUI.dashboardContents.ManagePublic',compact('confirmationReport'));

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

    public function resendVerificationEmail($idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $dataPersonalMember = $this->databaseConn->getPersonalDataOfUnverifiedUserByIdMember($idMember)->first();

                    if($dataPersonalMember) {

                        $content = $this->mail->getNewPublicMemberEmailVerificationTemplate($idMember);

                        $this->mail->html_email($dataPersonalMember->emailAddress, $dataPersonalMember->nameFirst . " " . $dataPersonalMember->nameLast, "SBM ITB TK-Low Online Course - Account Verification", $content);

                        return redirect('/manageMember/public/waitingVerification')->with('success', 'Verification email has been sent to ' . $dataPersonalMember->emailAddress);
                    }else{
                        return redirect('/manageMember/public/waitingVerification')->with('error', 'User not found');
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

    public function removeUnverifiedUser($idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $dataPersonalMember = $this->databaseConn->getPersonalDataOfUnverifiedUserByIdMember($idMember)->first();

                    if($dataPersonalMember) {
                        $content = $this->mail->getDeletedPublicMemberEmailTemplate($idMember);

                        $this->databaseConn->removeUnverifiedPublicMember($idMember);

                        $this->mail->html_email($dataPersonalMember->emailAddress, $dataPersonalMember->nameFirst . " " . $dataPersonalMember->nameLast, "SBM ITB TK-Low Online Course - Account Verification", $content);

                        return redirect('/manageMember/public/waitingVerification')->with('success', $dataPersonalMember->Username.' has been removed successfully.');
                    }else{
                        return redirect('/manageMember/public/waitingVerification')->with('error', 'User not found');
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

    public function suspendMember($idMember, $backTarget){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    if($idMember == session('idMember')){
                        return redirect('/manageMember/'.$backTarget)->with('error', 'You are not allowed to suspend your own account.');
                    }

                    $dataPersonalMember = $this->databaseConn->getAccountDataByIdMember($idMember)->first();

                    if($dataPersonalMember) {
                        $this->databaseConn->suspendAccountMemberByIdMember($idMember);

                        return redirect('/manageMember/'.$backTarget)->with('success', $dataPersonalMember->Username.' has been suspended successfully.');
                    }else{
                        return redirect('/manageMember/'.$backTarget)->with('error', 'User not found');
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

    public function activateMember($idMember, $backTarget){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    if($idMember == session('idMember')){
                        return redirect('/manageMember/'.$backTarget)->with('error', 'You are not allowed to suspend your own account.');
                    }

                    $dataPersonalMember = $this->databaseConn->getAccountDataByIdMember($idMember)->first();

                    if($dataPersonalMember) {
                        $this->databaseConn->activateAccountMemberByIdMember($idMember);

                        return redirect('/manageMember/'.$backTarget)->with('success', $dataPersonalMember->Username.' has been activated successfully.');
                    }else{
                        return redirect('/manageMember/'.$backTarget)->with('error', 'User not found');
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

    public function showEditPublicUserDataForm($idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $getMemberPersonalData = $this->databaseConn->getFullMemberData($idMember, '5')->first();

                    if(isset($getMemberPersonalData)) {
                        $highestEducationData = $this->databaseConn->getHighestEducationData()->get();
                        $highestEducationDropdown = [];
                        foreach($highestEducationData AS $data) {
                            $highestEducationDropdown[$data->idHighestEducation] = $data->highestEducation;
                        }

                        $workingFieldData = $this->databaseConn->getWorkingFieldData()->get();
                        $workingFieldDropdown = [];

                        foreach($workingFieldData AS $data){
                            $workingFieldDropdown[$data->idWorkingField] = $data->workingField;
                        }

                        $content = view('dashboardUI.dashboardContents.EditPublicMemberForm', compact('getMemberPersonalData', 'highestEducationDropdown','workingFieldDropdown'));

                        return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                    }else{
                        return redirect('/dashboard')->with('error','Something went wrong. Please contact your IT Support.');
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

    public function doUpdatePublicMember(Request $req, $idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'email' => [
                            'required',
                            'email'

                        ],
                        'firstName' => [
                            'required'
                        ],
                        'lastName' => [
                            'required'
                        ],
                        'gender' => [
                            'required'
                        ],
                        'birthPlace' => [
                            'required'
                        ],
                        'birthDate' => [
                            'required'
                        ],
                        'nationality' => [
                            'required'
                        ],
                        'phoneNumber' => [
                            'required'
                        ],
                        'highestEducation' => [
                            'required'
                        ],
                        'highestEducationInstitution' => [
                            'required'
                        ],
                        'workingField' => [
                            'required'
                        ],
                        'workingPosition' => [
                            'required'
                        ],
                        'workingInstitution' => [
                            'required'
                        ],
                        'workingExperience' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageMember/public/editPublicUser/'.$idMember)
                            ->withErrors($validator);
                    }

                    $Username = $this->databaseConn->updatePublicMemberPersonalData($req, $idMember)->first();

                    $content = $this->mail->getUpdatedPublicMemberPersonalInformationEmailTemplate($idMember);

                    $this->mail->html_email($Username->emailAddress, $Username->nameFirst.' '.$Username->nameLast, "SBM ITB TK-Low Online Course - Updated Personal Information", $content);

                    return redirect('/manageMember/public')->with('success',$Username->Username.' has been updated successfully.');
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

    public function doDeletePublicMember($idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {

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

    public function loadClassProgram(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $dataAvailableProgram = $this->databaseConn->getAvailableProgram()->get();

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_ClassProgram',compact('dataAvailableProgram'));

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function addOnlineProgramForm(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $getAvailableEducationProgram = $this->databaseConn->getAvailableEducationProgram()->get();

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_addOnlineProgramForm', compact('getAvailableEducationProgram'));

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function submitNewOnlineProgramForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'onlineCourseType' => [
                            'required'

                        ],
                        'onlineCourseProgramName' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/manageClassProgram/addOnlineProgram')
                            ->withErrors($validator);
                    }

                    if($req->onlineCourseType == "1") {
                        $validator2 = Validator::make($req->all(), [
                            'onlineCourseProgram' => [
                                'required'
                            ]
                        ]);


                        if ($validator2->fails()) {
                            $messages = $validator2->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/manageClassProgram/addOnlineProgram')
                                ->withErrors($validator2);
                        }
                    }

                    $this->databaseConn->insertNewOnlineProgramName($req);

                    return redirect('/manageOnlineCourse/manageClassProgram/addOnlineProgram')->with('success',$req->onlineCourseProgramName.' has been inserted successfully.');
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

    public function editOnlineProgramForm($idAvailableClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $getAvailableOnlineProgramName = $this->databaseConn->getAvailableEducationProgramByIdAvailableClass($idAvailableClass)->first();

                    $getAvailableEducationProgram = $this->databaseConn->getAvailableEducationProgram()->get();

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_editOnlineProgramForm', compact('getAvailableEducationProgram','getAvailableOnlineProgramName'));

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function submitEditOnlineProgramForm($idAvailableClass, Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'onlineCourseType' => [
                            'required'

                        ],
                        'onlineCourseProgramName' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/manageClassProgram/editOnlineProgram/'.$idAvailableClass)
                            ->withErrors($validator);
                    }

                    if($req->onlineCourseType == "1") {
                        $validator2 = Validator::make($req->all(), [
                            'onlineCourseProgram' => [
                                'required'
                            ]
                        ]);


                        if ($validator2->fails()) {
                            $messages = $validator2->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/manageClassProgram/editOnlineProgram/'.$idAvailableClass)
                                ->withErrors($validator2);
                        }
                    }

                    $this->databaseConn->editOnlineProgramName($idAvailableClass, $req);

                    return redirect('/manageOnlineCourse/manageClassProgram')->with('success',$req->onlineCourseProgramName.' has been edited successfully.');
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

    public function loadCourse(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $dataAvailableCourse = $this->databaseConn->getAvailableCourse()->get();

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_AvailableCourse',compact('dataAvailableCourse'));

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function addCourseForm(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $getAvailableCourseCode = $this->databaseConn->getUnregisteredCourseCodeOnServer()->get();

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_addCourseForm', compact('getAvailableCourseCode'));

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function submitNewCourseForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'courseType' => [
                            'required'

                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/manageCourse/addNewCourse')
                            ->withErrors($validator);
                    }

                    if($req->courseType == "1") {
                        $validator2 = Validator::make($req->all(), [
                            'courseCode' => [
                                'required'
                            ]
                        ]);


                        if ($validator2->fails()) {
                            $messages = $validator2->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/manageCourse/addNewCourse')
                                ->withErrors($validator2);
                        }
                    }else{
                        $validator2 = Validator::make($req->all(), [
                            'courseName' => [
                                'required'
                            ],
                            'newCourseCode' => [
                                'required'
                            ]
                        ]);


                        if ($validator2->fails()) {
                            $messages = $validator2->messages();

                            // redirect our user back to the form with the errors from the validator
                            return redirect('/manageOnlineCourse/manageCourse/addNewCourse')
                                ->withErrors($validator2);
                        }
                    }

                    $execute = $this->databaseConn->insertNewOnlineCourse($req);

                    return $execute;

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

    public function loadAvailableClass(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $dataAvailableClass = $this->databaseConn->getAllAvailableCourses()->get();

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_AvailableClass',compact('dataAvailableClass'));

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function openNewClassForm(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $formUI = new FormUserInterface();

                    $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                    $subcontent = view('dashboardUI.dashboardContents.ManageOnlineCourse_addNewOnlineClassForm');

                    $content = view('dashboardUI.dashboardContents.ManageOnlineCourse', compact('subcontent'));

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

    public function submitOpenNewClassForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'startedFrom' => [
                            'required'

                        ],
                        'endedAt' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/addNewOnlineClassForm')
                            ->withErrors($validator);
                    }

                    $idCoursesClass = $this->databaseConn->createNewOnlineClass($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass);
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

    public function showOnlineClassAdministratorDashboard($idCoursesClass){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $getOnlineClassData = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->count();

                if($getOnlineClassData == 0){
                    return redirect('/dashboard')->with('error','Online course you wished to open was not found!.');
                }

                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.OnlineClassOverview', compact('idCoursesClass'));

                    $content = view('dashboardUI.onlineClassControlDashboard.ManageOnlineClass', compact('subcontent','idCoursesClass'));
                    //$content = "";

                    return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                }else{
                    if($idAuthority == "3"){
                        $getMentor = $this->databaseConn->getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass,session('idMember'))->count();

                        if($getMentor > 0){
                            $leftMenuBar = $this->FormUI->getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass);

                            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.OnlineClassOverview', compact('idCoursesClass'));

                            $content = view('dashboardUI.onlineClassControlDashboard.ManageOnlineClass', compact('subcontent','idCoursesClass'));
                            //$content = "";

                            return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
                        }else{
                            return redirect('/dashboard')->with('error','You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.');
                        }
                    }else {
                        return redirect('/dashboard');
                    }
                }
            }else{
                return redirect('/logout')->with('status','Error. Your login credentials was not found in member database. Please contact our IT Support.');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function showEditOnlineClassSchedule(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $str = "success&nbsp;";

                    $str .= view('dashboardUI.onlineClassControlDashboard.Forms.EditOnlineClassScheduleForm', compact('idCoursesClass'))->render()."&nbsp;";

                    return $str;
                }else{
                    $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                    return $str;
                }
            }else{
                $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                $req->session()->regenerate();
                $req->session()->flush();

                return $str;
            }
        }else{
            $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

            return $str;
        }
    }

    public function submitEditOnlineClassSchedule(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'startedFrom' => [
                            'required'

                        ],
                        'endedAt' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)
                            ->withErrors($validator);
                    }

                    if(date('Y-m-d H:i:s',strtotime($req->startedFrom)) >= date('Y-m-d H:i:s',strtotime($req->endedAt))){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('error','Error. You are not allowed to select start date more advance that end date.');
                    }

                    $this->databaseConn->updateOnlineClassSchedule($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('success','Online Class schedule has been updated successfully.');
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

    public function showAddMentorOnlineClass(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $str = "success&nbsp;";

                    $str .= view('dashboardUI.onlineClassControlDashboard.Forms.AddOnlineClassMentorForm', compact('idCoursesClass'))->render()."&nbsp;";

                    return $str;
                }else{
                    $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                    return $str;
                }
            }else{
                $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                $req->session()->regenerate();
                $req->session()->flush();

                return $str;
            }
        }else{
            $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

            return $str;
        }
    }

    public function submitAddMentorOnlineClass(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $dataOnlineClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $validator = Validator::make($req->all(), [
                        'idMentor' => [
                            'required'

                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)
                            ->withErrors($validator);
                    }

                    $this->databaseConn->addMentorForOnlineClass($req);

                    $content = $this->mail->getWelcomingMentorEmailTemplate($req->idMentor, $idCoursesClass);

                    $getMentorData = $this->databaseConn->getFullMemberData($req->idMentor,"3")->first();

                    $this->mail->html_email($getMentorData->email, $getMentorData->nama_dosen, "SBM ITB TK-Low Online Course - ".$dataOnlineClass->nama_mata_kuliah_eng." Online Class", $content);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('success',$getMentorData->nama_dosen.' has been added as Mentor successfully.');
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

    public function deleteMentorFromOnlineClass($idCoursesClass,$idMember){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                if($idAuthority == "1" OR $idAuthority == "2") {
                    $this->databaseConn->deleteMentorFromOnlineClassByIdMemberAndIdCoursesClass($idCoursesClass,$idMember);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('success','Selected mentor has been deleted successfully.');
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

    public function showAddOnlineClassDescriptionForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

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
                            $str = "error&nbsp;";
                            $str .= "You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.&nbsp;";

                            return $str;
                        }
                    }

                    $str = "success&nbsp;";

                    $str .= view('dashboardUI.onlineClassControlDashboard.Forms.AddClassDescriptionForm', compact('idCoursesClass'))->render()."&nbsp;";

                    return $str;
                }else{
                    $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                    return $str;
                }
            }else{
                $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                $req->session()->regenerate();
                $req->session()->flush();

                return $str;
            }
        }else{
            $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

            return $str;
        }
    }

    public function submitAddOnlineClassDescriptionForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

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
                        'courseDescription' => [
                            'required',
                            'min:100'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)
                            ->withErrors($validator);
                    }

                    $this->databaseConn->updateOnlineClassDecription($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('success','Online Class description has been updated successfully.');
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

    public function showAddOnlineClassOverviewForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

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
                            $str = "You have not assigned to mentor this Online Class. Please contact IT Support if this was a mistake.&nbsp;";

                            return $str;
                        }
                    }

                    $str = "success&nbsp;";

                    $str .= view('dashboardUI.onlineClassControlDashboard.Forms.AddClassOverviewForm', compact('idCoursesClass'))->render()."&nbsp;";

                    return $str;
                }else{
                    $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                    return $str;
                }
            }else{
                $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

                $req->session()->regenerate();
                $req->session()->flush();

                return $str;
            }
        }else{
            $str = "Your login credentials was not found in member database. Please contact our IT Support.&nbsp;";

            return $str;
        }
    }

    public function submitAddOnlineClassOverviewForm(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

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
                        'courseOverview' => [
                            'required',
                            'min:100'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)
                            ->withErrors($validator);
                    }

                    $this->databaseConn->updateOnlineClassOverview($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('success','Online Class overview has been updated successfully.');
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

    public function activateOnlineClass(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

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

                    $this->databaseConn->updateOnlineClassOpenStatus($req);

                    if($req->isOpen == "1"){
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('success','Online Class overview has been OPENED successfully.');
                    }else{
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass)->with('error','Online Class overview has been CLOSED successfully.');
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

    public function addNewOnlineClassTopic($idCoursesClass){
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

                    $content = view('dashboardUI.onlineClassControlDashboard.Forms.AddNewTopic', compact('idCoursesClass'));

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

    public function submitNewOnlineClassTopic(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;

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
                        'newTopicName' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/addNewTopicForm')
                            ->withErrors($validator);
                    }

                    $idTopic = $this->databaseConn->addNewTopic($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic)->with('success','Online Class new topic has been added successfully.');
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

    public function manageTopic($idCoursesClass, $idTopic){
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

                    $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.manageTopic', compact('idCoursesClass','idTopic'));

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

    public function submitNewSubTopic(Request $req){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();
        $idCoursesClass = $req->idCoursesClass;
        $idTopic = $req->idTopic;

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
                        'subTopicName' => [
                            'required'
                        ],
                        'subTopicIssue' => [
                            'required'
                        ],
                        'subTopicDescription' => [
                            'required'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->messages();

                        // redirect our user back to the form with the errors from the validator
                        return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic)
                            ->withErrors($validator);
                    }

                    $idSubTopic = $this->databaseConn->addNewSubTopic($req);

                    return redirect('/manageOnlineCourse/availableClass/manageOnlineClass/'.$idCoursesClass.'/manageSession/'.$idTopic)->with('success','Online Class new sub topic has been added successfully.');
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

}

?>