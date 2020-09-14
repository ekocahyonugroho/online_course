<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 21/08/17
 * Time: 9:41
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Backend\Database_communication;
use App\Http\Middleware\appHelper;
use App\Http\Middleware\CourseUserInterface;
use App\Http\Middleware\FormUserInterface;

class CoursesController extends Controller
{
    function __construct(){
        $this->formUI = new FormUserInterface();
        $this->databaseConn = new Database_communication();
        $this->appHelper = new appHelper();
        $this->mail = new MailController();
    }

    public function index()
    {
        $databaseComm = new Database_communication();
        $userInterface = new CourseUserInterface();

        $available_course_data = $databaseComm->getAllOpenedCourses()->get();

        $header = view('header');
        $footer = view('footer');

        return view('home',compact('available_course_data', 'userInterface', 'header', 'footer'));
    }

    public function help_page(){
        $db = $this->databaseConn;
        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;
                $formUI = new FormUserInterface();

                $header = "";
                $footer = "";

                $leftMenuBar = $formUI->getLeftMenuBarByIdAuthority($idAuthority);

                $content = view('helps.help',compact( 'db', 'header', 'footer'));

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
            }else{
                return redirect('/logout')->with("error","Something went wrong. Please try to re-login");
            }
        }else{
            $header = view('header');
            $footer = view('footer');

            return view('helps.help',compact( 'db', 'header', 'footer'));
        }
    }

    public function showClassCourseAbout($CourseCode,$idCoursesClass)
    {
        $aboutClassCourseContent = "";
        try
        {
            $databaseComm = new Database_communication();
            $userInterface = new CourseUserInterface();

            $sqlCoursesClass = $databaseComm->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);
            $subcontent = view('dashboardUI.onlineClassControlDashboard.subContent.EnterOnlineClassOverview', compact('idCoursesClass'));

            if($sqlCoursesClass->get()->count() == 0){
                $aboutClassCourseContent .= "<div class=\"row\">
                                            <div class=\"col-lg-12\">
                                                <div class=\"panel panel-danger\">
                                                    <!-- /.panel-heading -->
                                                    <div class=\"panel-body\">
                                                        <center><h1>SORRY. THIS COURSE NOT FOUND OR NOT AVAILABLE.</h1></center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
            }else {
                $dataCoursesClass = $sqlCoursesClass->get();
                //Start to load header information of selected Created Class Courses
                foreach($dataCoursesClass as $data) {
                    $aboutClassCourseContent .= "<div class=\"row\">
                                                    <div class=\"col-lg-6\">
                                                        <div class=\"panel panel-info\">
                                                            <!-- /.panel-heading -->
                                                            <div class=\"panel-body\">
                                                                <br />
                                                                
                                                                <!-- /.panel-body -->
                                                                <table width=\"100%\">
                                                                    <tr>
                                                                        <td>
                                                                            <h1>$data->nama_mata_kuliah_id</h1>
                                                                            
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <br /><br />
                                                                        </td>
                                                                    <tr>
                                                                        <td>".$userInterface->enrollButtonToCourseClass($idCoursesClass, $CourseCode)."</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=\"col-lg-6\">
                                                        <div class=\"panel panel-info\">
                                                            <!-- /.panel-heading -->
                                                            <div class=\"panel-body\">
                                                                <br />
                                                                
                                                                <!-- /.panel-body -->
                                                                <table width=\"100%\">
                                                                    <tr>
                                                                        <td rowspan='2'>
                                                                            ".$userInterface->showVideoThumbnailOnCourseClassDescription($idCoursesClass, "500", "250")."
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                <div class=\"col-lg-12\">
                                                        <div class=\"panel panel-info\">
                                                            <!-- /.panel-heading -->
                                                            <div class=\"panel-body\">
                                                                <table class='table table-bordered'>
                                                                    <tr>
                                                                        <td width='1%'><i class=\"fa fa-book\" aria-hidden=\"true\"></i></td>
                                                                        <td width='49%'>Course Number</td>
                                                                        <td width='50%'>$data->CourseCode</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width='1%'><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i></td>
                                                                        <td width='49%'>Classes Start</td>
                                                                        <td width='50%'>".date('d F Y H:i:s', strtotime($data->OpenedStart))." GMT +7</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width='1%'><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i></td>
                                                                        <td width='49%'>Classes End</td>
                                                                        <td width='50%'>".date('d F Y H:i:s', strtotime($data->OpenedEnd))." GMT +7</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width='1%'><i class=\"fa fa-money\" aria-hidden=\"true\"></i></i></td>
                                                                        <td width='49%'>Tuition Fee</td>
                                                                        <td width='50%'><b>".$userInterface->showCourseClassPrice($idCoursesClass)."</b></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
</div>";

                    $aboutClassCourseContent .= "".$subcontent;
                    /*$aboutClassCourseContent .= "<div class=\"row\">
                                                    <div class=\"col-lg-8\">
                                                        <div class=\"panel panel-info\">
                                                            <!-- /.panel-heading -->
                                                            <div class=\"panel-body\">
                                                                <center><h3>OVERVIEW</h3></center>
                                                                <div class='row' >
                                                                    <div class='row-overview'>
                                                                        $subcontent
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>";*/
                }
            }
        }
        catch(Exception $e)
        {
            $aboutClassCourseContent = var_dump($e->getMessage());
        }

        return view('about_class_course',compact('aboutClassCourseContent'));
    }

    public function showAvailableCourses(){
        $isUserAllowed = $this->appHelper->isUserAllowedToAccess();

        if($isUserAllowed == FALSE){
            return redirect('/logout')->with('status','You are not allowed to access this system by our administrator.');
        }

        if(session('idMember')){
            $dataMember = $this->databaseConn->getAccountDataByIdMember(session('idMember'))->first();

            if($dataMember) {
                $idAuthority = $dataMember->idAuthority;

                $leftMenuBar = $this->formUI->getLeftMenuBarByIdAuthority($idAuthority);

                $content = view('dashboardUI.dashboardContents.availableCourses');

                return view('dashboardUI.dashboard', compact('leftMenuBar', 'content'));
            }else{
                return redirect('/logout')->with('status','error');
            }
        }else{
            return redirect('/')->with('status','error');
        }
    }
}
?>