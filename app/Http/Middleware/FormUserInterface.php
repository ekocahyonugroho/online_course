<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 22/09/17
 * Time: 9:05
 */
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Backend\Database_communication;
use App\Http\Middleware\appHelper;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CourseUserInterface;

class FormUserInterface
{
    function __construct()
    {

        $this->databaseConn = new Database_communication();
        $this->userController = new UserController();
    }

    public function loginUserForm(){
        $loginForm = "<form method='post' action=\"".action('UserController@doAuth')."\">
                          <div class=\"imgcontainer-login\">
                            <img src=\"https://cdn3.iconfinder.com/data/icons/business-analytics/512/customer_group-512.png\" alt=\"Avatar\" class=\"avatar-login\">
                          </div>
                        
                          <div class=\"container-login\">
                            <label><b>Username</b></label>
                            <input type=\"hidden\" name=\"_token\" value=\"".csrf_token()."\">
                            <input type=\"username\" placeholder=\"Enter Username\" value='".Input::old('username')."' id='username' name=\"username\" required>
                        
                            <label><b>Password</b></label>
                            <input type=\"password\" placeholder=\"Enter Password\" value='".Input::old('password')."' id='password' name=\"password\" required>
                        
                            <button type=\"submit\">Login</button>
                            <!-- <input type=\"checkbox\" checked=\"checked\"> Remember me -->
                          </div>
                        
                          <div class=\"container-login\" style=\"background-color:#f1f1f1\">
                            <button type=\"reset\" class=\"cancelbtn-login\">Cancel</button>
                            <button type=\"button\" onclick=\"location.href='https://".$_SERVER['SERVER_NAME']."/register'\"  class=\"registerbtn-login\">Register</button>
                            <span class=\"psw\">Forgot <a href=\"https://".$_SERVER['SERVER_NAME']."/forgot_password\">password?</a></span>
                          </div>
                      </form> ";

        return $loginForm;
    }

    public function registerPublicUserForm(){
        $courseUI = new CourseUserInterface();

        $registerForm = "<form method='post' action=\"".action('UserController@doRegisterUserAccount')."\">
                                <div class=\"form-group\">
                                    <label>Email :</label>
                                    <input type=\"hidden\" name=\"_token\" value=\"".csrf_token()."\">
                                    <input type=\"email\" placeholder='username@gmail.com' value='".Input::old('email')."' class=\"form-control\" name='email' id=\"email\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Username :</label>
                                    <input type=\"text\" placeholder='Choose Username' value='".Input::old('username')."' class=\"form-control\" name='username' id=\"username\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Password :</label>
                                    <input type=\"password\" class=\"form-control\" value='".Input::old('password')."' name='password' id=\"password\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Repeat Password :</label>
                                    <input type=\"password\" class=\"form-control\" name='repeatPassword' id=\"repeatPassword\">
                                </div>
                                <div class=\"form-group\">
                                    <label>First Name :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('firstName')."' name='firstName' id=\"firstName\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Last Name :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('lastName')."' name='lastName' id=\"lastName\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Gender :</label>
                                    <select class=\"form-control\" id=\"gender\" name='gender'>
                                        <option value=''>Choose</option>
                                        <option value='L'>Male</option>
                                        <option value='P'>Female</option>
                                    </select>
                                </div>
                                <div class=\"form-group\">
                                    <label>Birth Place :</label>
                                    <input type=\"text\" placeholder='City Name' class=\"form-control\" value='".Input::old('birthPlace')."' id=\"birthPlace\" name='birthPlace'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Birth Date :</label>
                                    <input type=\"date\" class=\"form-control\" value='".Input::old('birthDate')."' id=\"birthDate\" name='birthDate'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Nationality :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('nationality')."' id=\"nationality\" name='nationality'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Phone Number :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('phoneNumber')."' id=\"phoneNumber\" name='phoneNumber'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Highest Education :</label>
                                    <select class=\"form-control\" id=\"highestEducation\" name='highestEducation'>
                                        ".$courseUI->getHighestEducationDropdownList()."
                                    </select>
                                </div>
                                <div class=\"form-group\">
                                    <label>Highest Education Institution :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('highestEducationInstitution')."' id=\"highestEducationInstitution\" name='highestEducationInstitution'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Your Working Field :</label>
                                    <select class=\"form-control\" id=\"workingField\" name='workingField'>
                                        ".$courseUI->getWorkingFieldDropdownList()."
                                    </select>
                                </div>
                                <div class=\"form-group\">
                                    <label>Your Working Position :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('workingPosition')."' id=\"workingPosition\" name='workingPosition'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Your Working Institution :</label>
                                    <input type=\"text\" class=\"form-control\" value='".Input::old('workingInstitution')."' id=\"workingInstitution\" name='workingInstitution'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Your Working Experience :</label>
                                    <input type=\"number\" placeholder='in Years' class=\"form-control\" value='".Input::old('workingExperience')."' id=\"workingExperience\" name='workingExperience'>
                                </div>
                                <div class=\"form-group\">
                                    <label>Tell us why you are interested in SBM ITB Online Course :</label>
                                    <textarea class='form-control' id='interestedReason' name='interestedReason'>".Input::old('interestedReason')."</textarea>
                                </div>
                                <div class=\"checkbox\">
                                    <label><input id='subscription' value='1' name='subscription' type=\"checkbox\"> Email Subscription</label>
                                </div>
                                <div class=\"checkbox\">
                                    <label><input id='agree' value='1' name='agree' type=\"checkbox\"> I agree with Terms & Condition</label>
                                </div>
                                <button type=\"submit\" class=\"btn btn-default\">Submit</button>
                            </form>";

        return $registerForm;
    }

    public function userResetPassword($isLogin, $idMember){
        if($isLogin == "0") {
            $usernameValue = Input::old('username');
            $emailValue = Input::old('email');

            $inputControl = "";
        }else if($isLogin == "1"){
            $inputControl = "readonly";

            $dataUser = $this->databaseConn->getMemberData($idMember)->first();

            if($dataUser->idAuthority == "5"){
                $personalData = $this->databaseConn->getDataOfPublicUserByIdMember($idMember)->first();
            }else{
                $personalData = $this->databaseConn->getFullMemberData($idMember, $dataUser->idAuthority)->first();
            }
            $usernameValue = $dataUser->Username;
            if(!empty($personalData->email)){
                $emailValue = $personalData->email;
            }else{
                $emailValue = $personalData->emailAddress;
            }

        }

        $html = "<form action=\"" . action('UserController@resetUserPassword') . "\">
                                <div class=\"form-group\">
                                    <label>Email :</label>
                                    <input type=\"email\" placeholder='username@gmail.com' $inputControl value='" . $emailValue . "' class=\"form-control\" name='email' id=\"email\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Username :</label>
                                    <input type=\"text\" placeholder='Choose Username' $inputControl value='" . $usernameValue . "' class=\"form-control\" name='username' id=\"username\">
                                </div>
                                <button type=\"submit\" class=\"btn btn-info\">Reset My Password</button>
                            </form>";

        return $html;
    }

    public function userNewPassword($idMember, $idRequestPassword){
        $html = "          <form action=\"" . action('UserController@submitNewPassword') . "\">
                                <input type='hidden' name='idMember' value='".$idMember."' />
                                <input type='hidden' name='idRequestPassword' value='".$idRequestPassword."' />
                                <div class=\"form-group\">
                                        <label>New Password :</label>
                                    <input type=\"password\" placeholder='Type new password' class=\"form-control\" name='password' id=\"password\">
                                </div>
                                <div class=\"form-group\">
                                    <label>Repeat :</label>
                                    <input type=\"password\" placeholder='Repeat password' class=\"form-control\" name='repeatPassword' id=\"repeatPassword\">
                                </div>
                                <button type=\"submit\" class=\"btn btn-info\">Submit New Password</button>
                            </form>";

        return $html;
    }

    public function getLeftMenuBarByIdAuthority($idAuthority){
        switch ($idAuthority){
            case "1" :
                return view('dashboardUI.dashboardMenuBar.superadmin');
                break;
            case "2" :
                return view('dashboardUI.dashboardMenuBar.admin');
                break;
            case "3" :
                return view('dashboardUI.dashboardMenuBar.lecturer');
                break;
            case "4" :
                return view('dashboardUI.dashboardMenuBar.student');
                break;
            case "5" :
                return view('dashboardUI.dashboardMenuBar.public');
                break;
            default :
                return redirect('/logout');
        }
    }

    public function getDefaultDashboardByIdAuthority($idAuthority){
        switch ($idAuthority){
            case "1" :
                return view('dashboardUI.dashboardContents.MyDashboard');
                break;
            case "2" :
                return view('dashboardUI.dashboardContents.MyDashboard');
                break;
            case "3" :
                return view('dashboardUI.dashboardContents.lecturerDashboard');
                break;
            case "4" :
                return view('dashboardUI.dashboardContents.studentDashboard');
                break;
            case "5" :
                return view('dashboardUI.dashboardContents.publicDashboard');
                break;
            default :
                return redirect('/logout');
        }
    }

    public function getDefaultMenuBarEnterCourse($idAuthority, $idCoursesClass){
        switch ($idAuthority){
            case "1" :
                return view('dashboardUI.dashboardMenuBar.manageOnlineClass',compact('idCoursesClass'));
                break;
            case "2" :
                return view('dashboardUI.dashboardMenuBar.manageOnlineClass',compact('idCoursesClass'));
                break;
            case "3" :
                return view('dashboardUI.dashboardMenuBar.manageOnlineClass',compact('idCoursesClass'));
                break;
            case "4" :
                return view('dashboardUI.dashboardMenuBar.enterOnlineClass',compact('idCoursesClass'));
                break;
            case "5" :
                return view('dashboardUI.dashboardMenuBar.enterOnlineClass',compact('idCoursesClass'));
                break;
            default :
                return redirect('/logout');
        }
    }
}
?>