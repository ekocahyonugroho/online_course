<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 24/08/17
 * Time: 9:25
 */

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use App\Http\Backend\Database_communication;
use App\Http\Middleware\appHelper;
use App\Http\Controllers\UserController;

class CourseUserInterface {
    function __construct(){

        $this->databaseConn = new Database_communication();
        $this->userController = new UserController();
    }

    public function enrollButtonToCourseClass($idCoursesClass){
        try {

            $sqlCoursesClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);
            $dataCoursesClass = $sqlCoursesClass->get();

            foreach($dataCoursesClass as $data) {
                if($data->IsOpened == '0'){
                    return "<button class='btn btn-danger'>COURSE IS CLOSED BY ADMINISTRATOR</button>";
                }else{
                    $dateTimeNow = date('Y-m-d H:i:s');
                    $courseStartedDate = date('Y-m-d H:i:s', strtotime($data->OpenedStart));
                    $courseEndedDate = date('Y-m-d H:i:s', strtotime($data->OpenedEnd));
                    if($dateTimeNow >= $courseStartedDate && $dateTimeNow <= $courseEndedDate){
                        if(empty(session('username'))){
                            return "<button id='signInEnrollBtn' onclick=\"location.href = 'https://".$_SERVER['SERVER_NAME']."/login'\" class='btn btn-warning'>SIGN IN TO ENROLL</button><input type='hidden' id='idCourseClassEnroll' value='$idCoursesClass' /> ";
                        }else {

                            $isEnrolled = $this->userController->isUserAlreadyEnroll($idCoursesClass);

                            if($isEnrolled == TRUE){
                                return "<button id='goToCourseBtn' class='btn btn-success'>GO TO COURSE</button><input type='hidden' id='idCourseClass' value='$idCoursesClass' /> ";
                            }else if ($isEnrolled == FALSE) {
                                $isUserListedWaitingList = $this->userController->isUserListedWaitingList($idCoursesClass);

                                if ($isUserListedWaitingList == TRUE) {

                                    $dataWaitingList = $this->databaseConn->getWaitingListClassByIdClassCourseAndIdMember($idCoursesClass, session('idMember'))->first();

                                    if($dataWaitingList->isConfirmed == "1"){
                                        return "<button id='goToCourseBtn' class='btn btn-success'>GO TO COURSE</button><input type='hidden' id='idCourseClass' value='$idCoursesClass' /> ";
                                    }else if($dataWaitingList->isConfirmed == "0"){
                                        return "<button id='goToCourseBtn' class='btn btn-warning'>WAITING CONFIRMATION</button><input type='hidden' id='idCourseClass' value='$idCoursesClass' /> ";
                                    }

                                    return "<button id='goToCourseBtn' class='btn btn-success'>GO TO COURSE</button><input type='hidden' id='idCourseClass' value='$idCoursesClass' /> ";
                                } else if ($isUserListedWaitingList == FALSE) {
                                    return "<button id='enrollBtn' class='btn btn-primary'>ENROLL</button><input type='hidden' id='idCourseClassEnroll' value='$idCoursesClass' /> ";
                                }
                            }
                        }
                    }else {
                        if($dateTimeNow < $courseStartedDate){
                            return "<button class='btn btn-danger'>COURSE IS CLOSED</button><br /><br ><i class=\"fa fa-info-circle\" aria-hidden=\"true\"></i> This class has not been started yet.";
                        }else if($dateTimeNow > $courseEndedDate){
                            return "<button class='btn btn-danger'>COURSE IS CLOSED</button><br /><br ><i class=\"fa fa-info-circle\" aria-hidden=\"true\"></i> This class has been ended.";
                        }
                    }
                }
            }
        }catch(Exception $e){
            return "<button class='btn btn-danger'>COURSE IS CLOSED</button>";
        }
    }

    public function showVideoThumbnailOnCourseClassDescription($idCoursesClass, $width, $height){
        try {

            $sqlCoursesClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);
            $dataCoursesClass = $sqlCoursesClass->get();

            foreach ($dataCoursesClass as $data) {
                if(empty($data->VideoURLDescription) OR $data->VideoURLDescription == ""){
                    if(empty($data->ThumbnailURLAddress) OR $data->ThumbnailURLAddress == ""){
                        return "<img class=\"card-img-top\" src=\"http://placehold.it/".$width."x".$height."\" alt=\"image\">";
                    }else {
                        return "<img class=\"card-img-top\" width='$width' height='$height' src=\"$data->ThumbnailURLAddress\" alt=\"image\">";
                    }
                }else{
                    return "<iframe width=\"$width\" height=\"$height\" src=\"$data->VideoURLDescription\" frameborder=\"0\" allowfullscreen></iframe>";
                }
            }
        }catch(Exception $e){
            return "<img class=\"card-img-top\" src=\"http://placehold.it/".$width."x".$height."\" alt=\"image\">";
        }
    }

    public function showCourseClassPrice($idCoursesClass){
        try {

            $sqlCoursesClass = $this->databaseConn->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass);
            $dataCoursesClass = $sqlCoursesClass->get();

            foreach ($dataCoursesClass as $data) {
                if($data->IsPublic == '1'){
                    if($data->IsFree == '1'){
                        return "Free";
                    }else if($data->IsFree == '0'){
                        return "IDR. ".number_format( $data->CoursePrice , 2 , ',' , '.' );
                    }else{
                        return "NULL";
                    }
                }else if($data->IsPublic == '0'){
                    if($data->IsFree == '1'){
                        return "Free - Students Who Has ID Student Only";
                    }else if($data->IsFree == '0'){
                        return "IDR. ".number_format( $data->CoursePrice , 2 , ',' , '.' )." - Students Who Has ID Student Only";
                    }else{
                        return "NULL";
                    }
                }else{
                    return "NULL";
                }
            }
        }catch(Exception $e){
            return "NULL";
        }
    }

    public function getHighestEducationDropdownList(){
        $stmt = $this->databaseConn->getHighestEducationData()->get();

        $option = "";

        $option .= "<option value=''>Choose</option>";
        foreach($stmt as $data){
            $option .= "<option value='$data->idHighestEducation'>$data->highestEducation</option>";
        }

        return $option;
    }

    public function getWorkingFieldDropdownList(){
        $stmt = $this->databaseConn->getWorkingFieldData()->get();

        $option = "";

        $option .= "<option value=''>Choose</option>";
        foreach($stmt as $data){
            $option .= "<option value='$data->idWorkingField'>$data->workingField</option>";
        }

        return $option;
    }
}
?>