<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 28/08/17
 * Time: 14:21
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

class UserController extends Controller
{
    function __construct(){
        $this->databaseConn = new Database_communication();
        $this->appHelper = new appHelper();
        $this->mail = new MailController();
    }

    private function doLogin(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'username' => [
                    'required',

                ],
                'password' => [
                    'required'
                ],
            ]);

            if ($validator->fails()) {
                return redirect('login')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('status', 'wrong_account');
            }

            $user = $req->username;
            $pass = sha1($req->password);


            $check = $this->databaseConn->getBaseUserAccountByUsernameAndPassword($user, $pass)->count();

            if ($check == 0) {
                return redirect('login')->with('status', 'wrong_account');
            }

            $take = $this->databaseConn->getBaseUserAccountByUsernameAndPassword($user, $pass)->first();

            session(['idMember' => $take->idMember]);
            session(['username' => $take->Username]);
            session(['authority' => $take->idAuthority]);
            session(['password' => true]);

            $isActive = $this->isMemberActive();

            if($isActive === FALSE){
                return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
            }

            $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

            if($isUserAllowed == FALSE){
                return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
            }

            return redirect('dashboard');
        }catch (Exception $e){
            echo var_dump($e->getMessage());
        }
    }

    public function userLogin(){
        $formUI = new FormUserInterface();

        $loginForm = $formUI->loginUserForm();

        return view('login', compact('loginForm'));
    }

    public function userRegister(){
        if(!empty(session('idMember'))){
            return redirect('dashboard');
        }else{
            $formUI = new FormUserInterface();

            $registerForm = $formUI->registerPublicUserForm();

            return view('register', compact('registerForm'));
        }
    }

    public function doRegisterUserAccount(Request $req){
        $validator = Validator::make($req->all(), [
            'email' => [
                'required',
                'email'

            ],
            'username' => [
                'required',
            ],
            'password' => [
                'required',
                'min:8'
            ],
            'repeatPassword' => [
                'required',
                'same:password'
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
            ],
            'agree' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        }

        $isExist = $this->doCheckUsernameAvailability($req->username);

        if($isExist == "warning"){
            return redirect('register')
                ->withErrors('You can not use '.$req->username.' as your username. Please choose another one.')
                ->withInput();
        }

        $randomToken = sha1($this->appHelper->getToken());
        $clientIP = $this->appHelper->getClientIPaddress();
        $clientBrowser = $_SERVER['HTTP_USER_AGENT'];
        $clientLocation = $this->appHelper->getClientLocationBasedOnIPaddress($clientIP);

        //Insert new registration then get new ID Member used to send an email verification
        $idMember = $this->databaseConn->insertPublicAccountRegistrationData($req, $randomToken, $clientIP, $clientBrowser, $clientLocation);

        $dataPersonalMember = $this->databaseConn->getPersonalDataOfUnverifiedUserByIdMember($idMember)->first();

        $content = $this->mail->getNewPublicMemberEmailVerificationTemplate($idMember);

        $this->mail->html_email($dataPersonalMember->emailAddress, $dataPersonalMember->nameFirst." ".$dataPersonalMember->nameLast, "SBM ITB TK-Low Online Course - Account Verification", $content);

        return redirect('/')->with('status', 'register_success');
    }

    public function doVerifyNewPublicAccount($token, $idMember){
        $SQLverifyMember = $this->databaseConn->getVerifyNewPublicMemberByTokenAndIdMember($token, $idMember);

        $isExist = $SQLverifyMember->count();

        if($isExist > 0){
            $data = $SQLverifyMember->first();

            if($data->isVerified == '0'){
                $newIdMember = $this->databaseConn->verifyNewPublicMemberAccount($idMember, $token);

                $content = $this->mail->getNewPublicMemberEmailVerifiedTemplate($newIdMember);

                $dataPersonalMember = $this->databaseConn->getDataOfPublicUserByIdMember($newIdMember)->first();

                $this->mail->html_email($dataPersonalMember->emailAddress, $dataPersonalMember->nameFirst." ".$dataPersonalMember->nameLast, "SBM ITB TK-Low Online Course - Account Verification Success", $content);

                return redirect('/login')->with('status', 'verified');
            }else{
                return redirect('/')->with('status', 'already_verified');
            }
        }else{
            return redirect('/')->with('status', 'verify_failed');
        }
    }

    public function doCheckUsernameAvailability($username){
        $isExist = 0;

        $isExist = $this->databaseConn->getVerifiedUserByUsername($username)->count();

        if($isExist > 0){
            return "warning";
        }else{
            $isExist = $this->databaseConn->getUnverifiedUserByUsername($username)->count();

            if($isExist > 0){
                return "warning";
            }else{
                $isExist = $this->databaseConn->getEcoSystemUserByUsername($username)->count();

                if($isExist > 0){
                    return "warning";
                }
            }
        }
    }

    public function doForgotPassword(){
        $isLogin = $this->isSessionLogin();
        $formUI = new FormUserInterface();

        $idMember = "";

        if($isLogin == "1"){
            $idMember = session('idMember');
        }

        $resetForm = $formUI->userResetPassword($isLogin, $idMember);

        return view('login', compact('resetForm'));
    }

    public function resetUserPassword(Request $req){
        $isUserExist = $this->databaseConn->getVerifiedUserByUsername($req->username);

        if($isUserExist->count() > 0){
            $dataAccountUser = $isUserExist->first();

            $dataAccountUser = $this->databaseConn->getFullMemberData($dataAccountUser->idMember, $dataAccountUser->idAuthority)->first();

            if(!empty($dataAccountUser->nameFirst)){
                $userFullName = $dataAccountUser->nameFirst." ".$dataAccountUser->nameLast;
                $userEmail = $dataAccountUser->emailAddress;
            }else{
                $userFullName = $dataAccountUser->name;
                $userEmail = $dataAccountUser->email;
            }

            if($dataAccountUser->idAuthority == "5") {
                $isMatch = $this->databaseConn->getDataOfPublicUserByUsernameAndEmail($req->username, $req->email);

                if($isMatch->count() > 0){
                    $idRequestPassword = $this->databaseConn->insertResetPasswordRequest($dataAccountUser->idMember);

                    $content = $this->mail->getResetPasswordInstructionEmail($dataAccountUser->idMember, $idRequestPassword);

                    $this->mail->html_email($userEmail, $userFullName, "SBM ITB TK-Low Online Course - Reset Password", $content);

                    return redirect('/')->with('status', 'reset_instruction');
                }else{
                    return redirect('/forgot_password')->with('status','unmatch')->withInput();
                }
            }else{
                $idRequestPassword = $this->databaseConn->insertResetPasswordRequest($dataAccountUser->idMember);

                $content = $this->mail->getResetPasswordInstructionEmail($dataAccountUser->idMember, $idRequestPassword);

                $this->mail->html_email($userEmail, $userFullName, "SBM ITB TK-Low Online Course - Reset Password", $content);

                return redirect('/')->with('status', 'reset_instruction');
            }
        }else{
            return redirect('/forgot_password')->with('status','user_empty')->withInput();
        }
    }

    public function resetMyPassword($idMember, $idRequestPassword){
        $stmtAllPasswordRequest = $this->databaseConn->getAllPasswordRequestByIdMember($idMember);

        $countAllPasswordRequest = $stmtAllPasswordRequest->count();

        if($countAllPasswordRequest > 0) {

            $stmtGetUserRequestPassword = $this->databaseConn->getUserRequestPasswordByIdMemberAndIdRequest($idMember, $idRequestPassword);

            if($stmtGetUserRequestPassword->count() > 0) {

                $dataUserPasswordRequest = $stmtGetUserRequestPassword->first();

                if($dataUserPasswordRequest->isReset == "0") {
                    $dataAllPasswordRequest = $stmtAllPasswordRequest->get();

                    $loopCount = 0;
                    $latestIdPasswordRequest = "";

                    foreach ($dataAllPasswordRequest as $data) {
                        $loopCount++;

                        if ($loopCount === $countAllPasswordRequest) {
                            $latestIdPasswordRequest = $data->id;
                        }

                    }

                    if ($latestIdPasswordRequest === $dataUserPasswordRequest->id) {
                        // Compare between time now and valid date whether is still in 1 hour time range or not
                        $dateNow = date('Y-m-d H:i:s');
                        $validTime = date('Y-m-d H:i:s', strtotime($dataUserPasswordRequest->validUntil));

                        if ($dateNow <= $validTime) {
                            //Start to redirect user to new password form

                            $formUI = new FormUserInterface();

                            $newPasswordForm = $formUI->userNewPassword($idMember, $idRequestPassword);

                            return view('myNewPassword', compact('newPasswordForm'));
                        } else {
                            return redirect('/')->with('status', 'link_expired');
                        }

                    } else {
                        return redirect('/')->with('status', 'link_expired');
                    }

                }else{
                    return redirect('/')->with('status', 'link_expired');
                }

            }else{

                return redirect('/')->with('status','error');

            }

        }else{

            return redirect('/')->with('status','error');

        }
    }

    public function submitNewPassword(Request $req){
        $validator = Validator::make($req->all(), [
            'password' => [
                'required',
                'min:8'
            ],
            'repeatPassword' => [
                'required',
                'same:password'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('resetMyPassword/'.$req->idMember.'/'.$req->idRequestPassword)
                ->withErrors($validator)
                ->withInput();
        }

        $validator = Validator::make($req->all(), [
            'idMember' => [
                'required'
            ],
            'idRequestPassword' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('/')->with('status','error');
        }

        $stmtGetUserRequestPassword = $this->databaseConn->getUserRequestPasswordByIdMemberAndIdRequest($req->idMember, $req->idRequestPassword);

        if($stmtGetUserRequestPassword->count() > 0) {

            $dataUserPasswordRequest = $stmtGetUserRequestPassword->first();

            $dateNow = date('Y-m-d H:i:s');
            $validTime = date('Y-m-d H:i:s', strtotime($dataUserPasswordRequest->validUntil));

            if ($dateNow <= $validTime) {
                //Start to change user password

                $dataAccount = $this->databaseConn->getAccountDataByIdMember($req->idMember)->first();

                $dataPersonalMember = $this->databaseConn->getFullMemberData($req->idMember, $dataAccount->idAuthority)->first();

                if(!empty($dataPersonalMember->nameFirst)){
                    $userFullName = $dataPersonalMember->nameFirst." ".$dataPersonalMember->nameLast;
                    $userEmail = $dataPersonalMember->emailAddress;
                }else{
                    $userFullName = $dataPersonalMember->name;
                    $userEmail = $dataPersonalMember->email;
                }

                $changePassword = $this->databaseConn->changeUserPassword($req->idMember, sha1($req->password));

                $updatePasswordResetRequest = $this->databaseConn->setResetedForPasswordResetRequest($req->idRequestPassword);

                $content = $this->mail->getPasswordHasResetInformationEmail($req->idMember, $req->password);

                $this->mail->html_email($userEmail, $userFullName, "SBM ITB TK-Low Online Course - Password Has Been Changed", $content);

                return redirect('/login')->with('status', 'reset_password_success');
            } else {
                return redirect('/')->with('status', 'link_expired');
            }

        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function doAuth(Request $req){
        return $this->doLogin($req);
    }

    public function doLogout(Request $req){
        if($req->session()->has('status')){
            $message = session('status');
            $req->session()->regenerate();
            $req->session()->flush();

            return redirect('login')->with('status',$message);
        }else{
            $req->session()->regenerate();
            $req->session()->flush();

            return redirect('login');
        }
    }

    public function isLogin(){
        if(!empty(session('username'))){
            return redirect('dashboard');
        }else{
            return $this->userLogin();
        }
    }

    public function isSessionLogin(){
        if(!empty(session('username'))){
            return "1";
        }else{
            return "0";
        }
    }

    public function getMemberAuthority($idUser){
        $dataMember = $this->databaseConn->getMemberData($idUser)->first();
        $Authority = strtoupper($dataMember->Authority);

        return $Authority;
    }

    public function userEnrollClass($idClassCourse){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        $isEnrolled = $this->isUserAlreadyEnroll($idClassCourse);
        $idUser = session('idMember');

        $Authority = $this->getMemberAuthority($idUser);

        if($Authority == "STUDENT" OR $Authority == "PUBLIC") {

            if ($isEnrolled == TRUE) {
                return "Error : You have taken this Class previously. Please contact our IT support if this was a mistake.";
            } else if ($isEnrolled == FALSE) {
                $isUserListedWaitingList = $this->isUserListedWaitingList($idClassCourse);

                if ($isUserListedWaitingList == TRUE) {
                    return "Error : You have taken this Class previously with status waiting confirmation process. Please contact our IT support if this was a mistake.";
                } else if ($isUserListedWaitingList == FALSE) {
                    // Insert into enrolled class or waiting list if the class has special condition like need payment or else

                    $stmtGetSelectedClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idClassCourse);
                    $dataSelectedClass = $stmtGetSelectedClass->first();

                    $dateTimeNow = date('Y-m-d H:i:s');
                    $courseStartedDate = date('Y-m-d H:i:s', strtotime($dataSelectedClass->OpenedStart));
                    $courseEndedDate = date('Y-m-d H:i:s', strtotime($dataSelectedClass->OpenedEnd));
                    if ($dateTimeNow >= $courseStartedDate && $dateTimeNow <= $courseEndedDate) {
                        $isOpened = $dataSelectedClass->IsOpened;
                        $isFree = $dataSelectedClass->IsFree;
                        $isPublic = $dataSelectedClass->IsPublic;

                        if ($isOpened == "0") {
                            return "Error : You can not enroll this Class because this class has been closed by Administrator for some reason.";
                            exit;
                        }

                        if ($isPublic == "1") {
                            if ($isFree == "1") {
                                // If free, directly insert course class into enrolled table for user and send email to user as the confirmation

                                $this->databaseConn->insertIntoEnrolledCoursesClass($idUser, $idClassCourse);

                                return "success";
                            } else if ($isFree == "0") {
                                // If Not free, directly insert course class into waiting list table for user to have a confirmation required depends of administration requirements and send email to user as the confirmation

                                $this->databaseConn->insertIntoWaitingListCoursesClass($idUser, $idClassCourse);

                                return "success";
                            }
                        } else if ($isPublic == "0") { // If only regular students could enroll, do check whether user is regular student or not
                            if ($Authority == "STUDENT") {
                                if ($isFree == "1") {
                                    // If free, directly insert course class into enrolled table for user and send email to user as the confirmation

                                    $this->databaseConn->insertIntoEnrolledCoursesClass($idUser, $idClassCourse);

                                    return "success";
                                } else if ($isFree == "0") {
                                    // If Not free, directly insert course class into waiting list table for user to have a confirmation required depends of administration requirements and send email to user as the confirmation

                                    $this->databaseConn->insertIntoWaitingListCoursesClass($idUser, $idClassCourse);

                                    return "success";
                                }
                            } else {
                                return "Error : You can not enroll this class because you are not registered as student in ITB. Please contact our IT support if this was a mistake.";
                            }
                        }
                    } else {
                        return "Error : You can not enroll this Class because you are not in right time to access this class based on the schedule.";
                    }
                }
            }
        }else{
            return "Error : You are not allowed to enroll this Class because you are ".$Authority." in this System. Please contact our IT Support if this was a mistake.";
        }
    }

    public function isUserAlreadyEnroll($idClassCourse){
        $idMember = session('idMember');

        $stmtSQL = $this->databaseConn->getEnrolledClassByIdClassCourseAndIdMember($idClassCourse, $idMember);

        if($stmtSQL->count() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function isUserListedWaitingList($idClassCourse){
        $idMember = session('idMember');

        $stmtSQL = $this->databaseConn->getWaitingListClassByIdClassCourseAndIdMember($idClassCourse, $idMember);

        if($stmtSQL->count() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function loadDefaultUserDashboard(){
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

                $content = $formUI->getDefaultDashboardByIdAuthority($idAuthority);

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
            }else{
                return redirect('/logout')->with('status','error');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }

    public function isMemberActive()
    {
        $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

        if ($dataMember) {
            $IsActive = $dataMember->IsActive;

            if ($IsActive == "1") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>