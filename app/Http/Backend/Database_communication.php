<?php
/**
 * Created by PhpStorm.
 * User: itsbmitb
 * Date: 21/08/17
 * Time: 9:48
 */
namespace App\Http\Backend;

use Illuminate\Support\Facades\DB;

class Database_communication {

    public function getBaseUserAccountByUsernameAndPassword($user, $pass){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('Username',$user)
            ->where('Password',$pass);

        return $SQL;
    }

    public function getFullMemberData($idMember, $idAuthority){
        switch($idAuthority){
            case 1 :
                $SQL = DB::table('sis_online_course.account_member')
                ->where('account_member.idMember', $idMember)
                ->join('sis.access','account_member.Username','=','access.username')
                ->join('sis_organization.organization_member','access.id','=','organization_member.id_user_login');
                break;
            case "2" :
                $SQL = DB::table('sis_online_course.account_member')
                    ->where('account_member.idMember', $idMember)
                    ->join('sis.access','account_member.Username','=','access.username')
                    ->join('sis_organization.organization_member','access.id','=','organization_member.id_user_login');
                break;
            case "3" :
                $SQL = DB::table('sis_online_course.account_member')
                    ->where('account_member.idMember', $idMember)
                    ->join('sis.access','account_member.Username','=','access.username')
                    ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user');
                break;
            case "4" :
                $SQL = DB::table('sis_online_course.account_member')
                    ->where('account_member.idMember', $idMember)
                    ->join('sis.access','account_member.Username','=','access.username')
                    ->join('sis.ac_data_umum_mahasiswa','access.id','=','ac_data_umum_mahasiswa.id_user');
                break;
            case "5" :
                $SQL = DB::table('sis_online_course.account_member_public')
                    ->join('sis_online_course.account_member','account_member.idMember', '=', 'account_member_public.idMember')
                    ->where('account_member_public.idMember',$idMember)
                    ->where('account_member_public.isVerified','1');
                break;
            default :
                $SQL = DB::table('sis_online_course.account_member')
                    ->where('account_member.idMember',$idMember);
        }

        return $SQL;
    }

    public function getFullNameMemberByIdMember($idMember){
        $idAuthority = $this->getAccountDataByIdMember($idMember)->first()->idAuthority;

        $dataMember = $this->getFullMemberData($idMember, $idAuthority)->first();

        $Fullname = "";

        switch ($idAuthority) {
            case "1":
                $Fullname = $dataMember->name;
                break;
            case "2":
                $Fullname = $dataMember->name;
                break;
            case "3":
                $Fullname = $dataMember->nama_dosen;
                break;
            case "4":
                $Fullname = $dataMember->nama;
                break;
            case "5":
                $Fullname = $dataMember->nameFirst." ".$dataMember->nameLast;
                break;
            default:
                $Fullname = "NO NAME";
                break;
        }

        return $Fullname;
    }

    public function getUserEmailAndNameByIdMemberAndIdAuthority($idMember, $idAuthority){
        $dataUser = $this->getFullMemberData($idMember, $idAuthority)->first();

        switch ($idAuthority){
            case '5' :
                $Email = $dataUser->emailAddress;
                $Name = $dataUser->nameFirst." ".$dataUser->nameLast;
                break;
            default :
                $Email = $dataUser->email;
                $Name = $dataUser->name;
                break;
        }

        $values = array($Email, $Name);

        return $values;
    }

    public function getVerifiedUserByUsername($username){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.Username',$username);

        return $SQL;
    }

    public function getDataOfPublicUserByUsernameAndEmail($username, $email){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.Username', $username)
            ->join('sis_online_course.account_member_public', 'account_member_public.idMember', '=', 'account_member.idMember')
            ->where('account_member_public.emailAddress', $email)
            ->where('account_member_public.isVerified', '1');

        return $SQL;
    }

    public function getUnverifiedUserByUsername($username){
        $SQL = DB::table('sis_online_course.account_member_public_verification')
            ->where('account_member_public_verification.Username',$username);

        return $SQL;
    }

    public function getEcoSystemUserByUsername($username){
        $SQL = DB::table('sis.access')->where('access.username',$username);

        return $SQL;
    }

    public function getPersonalDataOfUnverifiedUserByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member_public')
            ->join('sis_online_course.account_member_public_verification','account_member_public.idMember','=','account_member_public_verification.idMember')
            ->where('account_member_public_verification.isVerified','0')
            ->where('account_member_public.isVerified','0')
            ->where('account_member_public.idMember',$idMember);

        return $SQL;
    }

    public function getPersonalDataOfVerifiedUserByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member_public')
            ->join('sis_online_course.account_member_public_verification','account_member_public.idMember','=','account_member_public_verification.idMember')
            ->where('account_member_public_verification.isVerified','1')
            ->where('account_member_public.isVerified','1')
            ->where('account_member_public.idMember',$idMember);

        return $SQL;
    }

    public function getAccountDataByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.idMember',$idMember);

        return $SQL;
    }

    public function getUserPhotoByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member_photo')
            ->where('account_member_photo.idMember',$idMember);

        return $SQL;
    }

    public function getDataOfPublicUserByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member_public')
            ->join('sis_online_course.account_member','account_member.idMember', '=', 'account_member_public.idMember')
            ->where('account_member_public.idMember',$idMember)
            ->where('account_member_public.isVerified','1');

        return $SQL;
    }

    public function getUnverifiedNewPublicMemberDataByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member_public_verification')
            ->join('sis_online_course.account_member_public', 'account_member_public_verification.idMember', '=', 'account_member_public.idMember')
            ->where('account_member_public_verification.idMember', $idMember)
            ->where('account_member_public.isVerified','0')
            ->where('account_member_public_verification.isVerified','0');

        return $SQL;
    }

    public function getVerifyNewPublicMemberByTokenAndIdMember($token, $idMember){
        $SQL = DB::table('sis_online_course.account_member_public_verification')
            ->where('account_member_public_verification.randomToken',$token)
            ->where('account_member_public_verification.idMember',$idMember);

        return $SQL;
    }

    public function verifyNewPublicMemberAccount($idMember, $token){
        $dateTimeNow = date('Y-m-d H:i:s');

        $dataNewMemberData = $this->getVerifyNewPublicMemberByTokenAndIdMember($token, $idMember)->first();

        $values = array(
            'idMember' => NULL,
            'idAuthority' => '5',
            'Username' => $dataNewMemberData->Username,
            'Password' => $dataNewMemberData->Password,
            'IsActive' => '1',
            'Registered' => $dateTimeNow,
            'ValidUntil' => ''
        );

        $newIdMember = DB::table('sis_online_course.account_member')
            ->insertGetId($values);

        $values = array(
            'idMember' => $newIdMember,
            'isVerified' => '1'
        );

        DB::table('sis_online_course.account_member_public')
            ->where('account_member_public.idMember', $idMember)
            ->update($values);

        $values = array(
            'isVerified' => '1',
            'idMember' => $newIdMember
        );

        DB::table('sis_online_course.account_member_public_verification')
            ->where('account_member_public_verification.idMember', $idMember)
            ->where('account_member_public_verification.randomToken', $token)
            ->update($values);

        return $newIdMember;
    }

    public function insertResetPasswordRequest($idMember){
        $dateNow = date('Y-m-d H:i:s');
        $nextDate = date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($dateNow)));

        $values = array(
            'id' => NULL,
            'idMember' => $idMember,
            'requestDate' => $dateNow,
            'validUntil' => $nextDate,
            'isReset' => '0',
            'resetDate' => ''
        );

        $SQL = DB::table('sis_online_course.account_member_reset_password_request')->insertGetId($values);

        return $SQL;
    }

    public function insertPublicAccountRegistrationData($data, $randomToken, $clientIP, $clientBrowser, $clientLocation){
        $dateTimeNow = date('Y-m-d H:i:s');
        $isSubscribe = '0';

        if($data->subscription){
            $isSubscribe = '1';
        }

        $values = array(
            'idMember' => NULL,
            'Username' => $data->username,
            'Password' => sha1($data->password),
            'randomToken' => $randomToken,
            'registerDate' => $dateTimeNow,
            'isVerified' => '0',
            'clientBrowser' => $clientBrowser,
            'clientIPaddress' => $clientIP,
            'clientLocation' => $clientLocation
        );

        $idMember = DB::table('sis_online_course.account_member_public_verification')->insertGetId($values);

        $interestedReason = "";

        if(!empty($data->interestedReason) OR $data->interestedReason != ""){
            $interestedReason = $data->interestedReason;
        }

        $values = array(
            'idMemberPublic' => NULL,
            'idMember' => $idMember,
            'nameFirst' => $data->firstName,
            'nameLast' => $data->lastName,
            'gender' => $data->gender,
            'birthPlace' => $data->birthPlace,
            'birthDate' => $data->birthDate,
            'nationality' => $data->nationality,
            'phoneNumber' => $data->phoneNumber,
            'emailAddress' => $data->email,
            'idHighestEducation' => $data->highestEducation,
            'highestEducationInstitution' => $data->highestEducationInstitution,
            'idWorkingField' => $data->workingField,
            'workingPosition' => $data->workingPosition,
            'workingInstitution' => $data->workingInstitution,
            'workingExperience' => $data->workingExperience,
            'isSubscription' => $isSubscribe,
            'interestedReason' => $interestedReason,
            'isVerified' => '0'
        );

        DB::table('sis_online_course.account_member_public')->insertGetId($values);

        return $idMember;
    }

    public function getAllAvailableCourses(){
        $SQL = DB::table('sis_online_course.created_courses_class')
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah');

        return $SQL;
    }

    public function getAllOpenedCourses(){
        $SQL = DB::table('sis_online_course.created_courses_class')
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah')
            ->where('created_courses_class.IsOpened','1');

        return $SQL;
    }

    public function getAvailableCoursesByIdAvailableCourse($idAvailableCourse){
        $SQL = DB::table('sis_online_course.available_courses')
            ->where('available_courses.idAvailableCourse',$idAvailableCourse)
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah');

        return $SQL;
    }

    public function getAvailableCourse(){
        $SQL = DB::table('sis_online_course.available_courses');

        return $SQL;
    }

    public function getOnlineClassByIdMemberAsMentor($idMember){
        $SQL = DB::table('sis_online_course.courses_class_mentored')
            ->where('courses_class_mentored.idMember',$idMember)
            ->join('sis_online_course.created_courses_class','courses_class_mentored.idCoursesClass','=','created_courses_class.idCoursesClass')
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah')
            ->OrderBy('created_courses_class.IsOpened','DESC')
            ->OrderBy('created_courses_class.OpenedStart','ASC')
            ->OrderBy('created_courses_class.OpenedEnd','DESC');

        return $SQL;
    }

    public function getOnlineClassMentorByIdCoursesClass($idCoursesClass){
        $SQL = DB::table('sis_online_course.courses_class_mentored')
            ->where('courses_class_mentored.idCoursesClass',$idCoursesClass)
            ->join('sis_online_course.account_member','account_member.idMember','=','courses_class_mentored.idMember')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user');

        return $SQL;
    }

    public function getMentorOnlineClassByIdCoursesClassAndIdMember($idCoursesClass, $idMember){
        $SQL = DB::table('sis_online_course.courses_class_mentored')
            ->where('courses_class_mentored.idCoursesClass',$idCoursesClass)
            ->where('courses_class_mentored.idMember',$idMember)
            ->join('sis_online_course.account_member','account_member.idMember','=','courses_class_mentored.idMember')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user');

        return $SQL;
    }

    public function deleteMentorFromOnlineClassByIdMemberAndIdCoursesClass($idCoursesClass,$idMember){
        DB::table('sis_online_course.courses_class_mentored')
            ->where('courses_class_mentored.idCoursesClass',$idCoursesClass)
            ->where('courses_class_mentored.idMember',$idMember)
            ->delete();
    }

    public function getAvailableMentorForOnlineClassByIdCoursesClass($idCoursesClass){
        $SQLnotIn = DB::table('sis_online_course.courses_class_mentored')->select('courses_class_mentored.idMember')->where('courses_class_mentored.idCoursesClass',$idCoursesClass);

        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.idAuthority','3')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user')
            ->whereNotIn('account_member.idMember',$SQLnotIn);

        return $SQL;
    }

    public function addMentorForOnlineClass($req){
        $values = array(
            'idCoursesClassMentored' => NULL,
            'idCoursesClass' => $req->idCoursesClass,
            'idMember' => $req->idMentor
        );

        DB::table('sis_online_course.courses_class_mentored')->insert($values);
    }

    public function createNewOnlineClass($Req){
        $isFree = "1";
        $CoursePrice = "0";
        $dateTime = date("Y-m-d H:i:s");

        if(isset($Req->tuitionFee)){
            $isFree = "0";
            $CoursePrice = $Req->tuitionFee;
        }

        $values = array(
            "idCoursesClass" => NULL,
            "CreatedDate" => $dateTime,
            "CreatedByIdUser" => session('idMember'),
            "IdAvailableCourse" => $Req->courseCode,
            "idAvailableClass" => $Req->onlineClassProgram,
            "ThumbnailURLAddress" => "",
            "CourseDescription" => "",
            "CourseOverview" => "",
            "VideoURLDescription" => "",
            "OpenedStart" => $Req->startedFrom,
            "OpenedEnd" => $Req->endedAt,
            "IsOpened" => "0",
            "IsFree" => $isFree,
            "IsPublic" => $Req->targetStudent,
            "CoursePrice" => $CoursePrice

        );

        $SQL = DB::table("sis_online_course.created_courses_class")->insertGetId($values);

        return $SQL;
    }

    public function getOpenedCourseClassByIdAvailableClass($idAvailableClass){
        $SQL = DB::table('sis_online_course.created_courses_class')
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah')
            ->where('created_courses_class.idAvailableClass',$idAvailableClass);

        return $SQL;
    }

    public function getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass){
        $SQL = DB::table('sis_online_course.created_courses_class')
            ->where('created_courses_class.idCoursesClass',$idCoursesClass)
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah');

        return $SQL;
    }

    public function updateOnlineClassSchedule($req){
        $values = array(
            'OpenedStart' => $req->startedFrom,
            'OpenedEnd' => $req->endedAt
        );

        DB::table('sis_online_course.created_courses_class')
            ->where('created_courses_class.idCoursesClass', $req->idCoursesClass)
            ->update($values);
    }

    public function updateOnlineClassDecription($req){
        $thumbnailURL = "";
        $videoURL = "";

        if(!empty($req->thumbnailURL)) {
            $thumbnailURL = $req->thumbnailURL;
        }

        if(!empty($req->videoURL)){
            $videoURL = $req->videoURL;
        }

        $values = array(
            'ThumbnailURLAddress' => $thumbnailURL,
            'VideoURLDescription' => $videoURL,
            'CourseDescription' => $req->courseDescription
        );

        DB::table('sis_online_course.created_courses_class')
            ->where('created_courses_class.idCoursesClass', $req->idCoursesClass)
            ->update($values);
    }

    public function updateOnlineClassOverview($req){
        $values = array(
            'CourseOverview' => $req->courseOverview
        );

        DB::table('sis_online_course.created_courses_class')
            ->where('created_courses_class.idCoursesClass', $req->idCoursesClass)
            ->update($values);
    }

    public function updateOnlineClassOpenStatus($req){
        $isOpen = "0";

        if(!empty($req->isOpen)){
            $isOpen = $req->isOpen;
        }

        $values = array(
            'IsOpened' => $isOpen
        );

        DB::table('sis_online_course.created_courses_class')
            ->where('created_courses_class.idCoursesClass', $req->idCoursesClass)
            ->update($values);
    }

    public function addNewTopic($req){
        $values = array(
            'idTopic' => NULL,
            'idCoursesClass' => $req->idCoursesClass,
            'TopicName' => $req->newTopicName
        );

        $idTopic = DB::table('sis_online_course.created_courses_class_topic')->insertGetId($values);

        return $idTopic;
    }

    public function addNewSubTopic($req){
        $values = array(
            'idSubTopic' => NULL,
            'idTopic' => $req->idTopic,
            'subTopicType' => $req->subTopicType,
            'subTopicName' => $req->subTopicName,
            'subTopicIssue' => $req->subTopicIssue,
            'subTopicDescription' => $req->subTopicDescription
        );

        $idSubTopic = DB::table('sis_online_course.created_courses_class_topic_sub')->insertGetId($values);

        return $idSubTopic;
    }

    public function getCoursesClassGeneralDataByIdAvailableCourse($idAvailableCourse){
        $SQL = DB::table('sis_online_course.created_courses_class')
            ->where('created_courses_class.idAvailableCourse',$idAvailableCourse)
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah');

        return $SQL;
    }

    public function getCoursesClassTopicByIdCoursesClass($idCoursesClass){
        $SQL = DB::table('sis_online_course.created_courses_class_topic')
            ->where('created_courses_class_topic.idCoursesClass',$idCoursesClass)
            ->OrderBy('created_courses_class_topic.idTopic','ASC');

        return $SQL;
    }

    public function getCoursesClassTopicByIdTopic($idTopic){
        $SQL = DB::table('sis_online_course.created_courses_class_topic')
            ->where('created_courses_class_topic.idTopic',$idTopic);

        return $SQL;
    }

    public function getCoursesClassSubTopicByIdTopic($idTopic){
        $SQL = DB::table('sis_online_course.created_courses_class_topic_sub')
            ->where('created_courses_class_topic_sub.idTopic',$idTopic)
            ->OrderBy('created_courses_class_topic_sub.idSubTopic');

        return $SQL;
    }

    public function getCoursesClassSubTopicByIdSubTopic($idSubTopic){
        $SQL = DB::table('sis_online_course.created_courses_class_topic_sub')
            ->where('created_courses_class_topic_sub.idSubTopic',$idSubTopic)
            ->OrderBy('created_courses_class_topic_sub.idSubTopic');

        return $SQL;
    }

    public function getStudentAccessSubTopicCount($idSubTopic, $idMember){
        $SQL = DB::table('sis_online_course.courses_class_enrolled_student_access_subtopic')
            ->leftJoin('sis_online_course.courses_class_enrolled', 'courses_class_enrolled_student_access_subtopic.idEnrolledStudent','courses_class_enrolled.idCoursesClassEnrolled')
            ->where('courses_class_enrolled_student_access_subtopic.idSubTopic','=',$idSubTopic)
            ->where('courses_class_enrolled.idMember','=',$idMember);

        return $SQL;
    }

    public function getSummaryStudentProgress($idCoursesClass, $idMember){

    }

    public function insertStudentAccessSubTopicCount($idEnrolledStudent, $idSubTopic){
        $values = array(
            'idEnrolledStudentAccess' => NULL,
            'dateTimeAccess' => date('Y-m-d H:i:s'),
            'idEnrolledStudent' => $idEnrolledStudent,
            'idSubTopic' => $idSubTopic
        );

        DB::table('sis_online_course.courses_class_enrolled_student_access_subtopic')->insert($values);
    }

    public function getCoursesClassSubTopicMaterialByIdSubTopic($idSubTopic){
        $SQL = DB::table('sis_online_course.created_courses_class_material')
            ->where('created_courses_class_material.idSubTopic',$idSubTopic)
            ->OrderBy('created_courses_class_material.idMaterial');

        return $SQL;
    }

    public function getCoursesClassSubTopicAssignmentByIdSubTopic($idSubTopic){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment')
            ->where('created_courses_class_assignment.idSubTopic',$idSubTopic)
            ->OrderBy('created_courses_class_assignment.idAssignment');

        return $SQL;
    }

    public function getCoursesClassSubTopicExamByIdSubTopic($idSubTopic){
        $SQL = DB::table('sis_online_course.created_courses_class_exam')
            ->where('created_courses_class_exam.idSubTopic',$idSubTopic)
            ->OrderBy('created_courses_class_exam.idExam');

        return $SQL;
    }

    public function getCoursesClassSubTopicExamByIdExam($idExam){
        $SQL = DB::table('sis_online_course.created_courses_class_exam')
            ->where('created_courses_class_exam.idExam',$idExam);

        return $SQL;
    }

    public function getCoursesClassExamQuestionByIdExam($idExam){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_question')
            ->where('created_courses_class_exam_question.idExam',$idExam)
            ->OrderBy('created_courses_class_exam_question.idExam','ASC');

        return $SQL;
    }

    public function getCoursesClassSubTopicAssignmentByIdAssignment($idAssignment){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment')
            ->where('created_courses_class_assignment.idAssignment',$idAssignment);

        return $SQL;
    }

    public function getCoursesClassAssignmentQuestionByIdAssignment($idAssignment){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_question')
            ->where('created_courses_class_assignment_question.idAssignment',$idAssignment)
            ->OrderBy('created_courses_class_assignment_question.idAssignment','ASC');

        return $SQL;
    }

    public function getAssignmentAnswerChoicesByIdQuestion($idQuestion){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_answer_choice')
            ->where('created_courses_class_assignment_answer_choice.idQuestion',$idQuestion)
            ->OrderBy('created_courses_class_assignment_answer_choice.idChoice','ASC');

        return $SQL;
    }

    public function getExamAnswerChoicesByIdQuestion($idQuestion){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_answer_choice')
            ->where('created_courses_class_exam_answer_choice.idQuestion',$idQuestion)
            ->OrderBy('created_courses_class_exam_answer_choice.idChoice','ASC');

        return $SQL;
    }

    public function deleteAssignmentQuestionByIdQuestion($idQuestion){
        DB::table('sis_online_course.created_courses_class_assignment_question')
            ->where('created_courses_class_assignment_question.idQuestion',$idQuestion)
            ->delete();
    }

    public function deleteAssignmentQuestionChoicesByIdQuestion($idQuestion){
        DB::table('sis_online_course.created_courses_class_assignment_answer_choice')
            ->where('created_courses_class_assignment_answer_choice.idQuestion', $idQuestion)
            ->delete();
    }

    public function getCoursesClassAssignmentQuestionByIdQuestion($idQuestion){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_question')
            ->where('created_courses_class_assignment_question.idQuestion',$idQuestion);

        return $SQL;
    }

    public function getCoursesClassExamQuestionByIdQuestion($idQuestion){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_question')
            ->where('created_courses_class_exam_question.idQuestion',$idQuestion);

        return $SQL;
    }

    public function getCoursesClassAssignmentChoiceValueByIdChoice($idChoice){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_answer_choice')
            ->where('created_courses_class_assignment_answer_choice.idChoice',$idChoice);

        return $SQL;
    }

    public function getCoursesClassExamChoiceValueByIdChoice($idChoice){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_answer_choice')
            ->where('created_courses_class_exam_answer_choice.idChoice',$idChoice);

        return $SQL;
    }

    public function deleteSubTopicMaterialByIdMaterial($idMaterial){
        DB::table('sis_online_course.created_courses_class_material')->where('created_courses_class_material.idMaterial',$idMaterial)->delete();
    }

    public function deleteSubTopicByIdSubTopic($idSubTopic){
        DB::table('sis_online_course.created_courses_class_topic_sub')
            ->where('created_courses_class_topic_sub.idSubTopic', $idSubTopic)
            ->delete();
    }

    public function getCoursesClassSubTopicMaterialByIdMaterial($idMaterial){
        $SQL = DB::table('sis_online_course.created_courses_class_material')
            ->where('created_courses_class_material.idMaterial',$idMaterial);

        return $SQL;
    }

    public function submitSubTopicArticle($req){
        $description = "";
        $dateTime = date('Y-m-d H:i:s');

        if(!empty($req->description)){
            $description = $req->description;
        }

        $values = array(
            'idMaterial' => NULL,
            'idSubTopic' => $req->idSubTopic,
            'idUser' => session('idMember'),
            'dateTime' => $dateTime,
            'typeMaterial' => "article",
            'titleMaterial' => $req->title,
            'descriptionMaterial' => $description,
            'contentMaterial' => $req->article
        );

        DB::table('sis_online_course.created_courses_class_material')->insert($values);
    }

    public function submitSubTopicFile($req, $completePathFile, $typeMaterial){
        $description = "";
        $dateTime = date('Y-m-d H:i:s');

        if(!empty($req->description)){
            $description = $req->description;
        }

        $values = array(
            'idMaterial' => NULL,
            'idSubTopic' => $req->idSubTopic,
            'idUser' => session('idMember'),
            'dateTime' => $dateTime,
            'typeMaterial' => $typeMaterial,
            'titleMaterial' => $req->title,
            'descriptionMaterial' => $description,
            'contentMaterial' => $completePathFile
        );

        DB::table('sis_online_course.created_courses_class_material')->insert($values);
    }

    public function getUnregisteredCourseCodeOnServer(){
        $SQLnotIn = DB::table('sis_online_course.available_courses')->select('available_courses.CourseCode');

        $SQL = DB::table('sis.ac_mata_kuliah')
            ->whereNotIn('ac_mata_kuliah.kode_mata_kuliah',$SQLnotIn)
            ->orderBy('ac_mata_kuliah.nama_mata_kuliah_eng');
        return $SQL;
    }


    public function getAvailableCoursesClassGeneralDataByCourseCode($CourseCode){
        $SQL = DB::table('sis_online_course.available_courses')
            ->where('available_courses.CourseCode',$CourseCode)
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah');

        return $SQL;
    }

    public function getEnrolledClassByIdClassCourseAndIdMember($idClassCourse, $idMember){
        $SQL = DB::table('sis_online_course.courses_class_enrolled')
            ->where('courses_class_enrolled.idMember', $idMember)
            ->where('courses_class_enrolled.idCoursesClass', $idClassCourse)
            ->join('sis_online_course.created_courses_class', 'created_courses_class.idCoursesClass','=','courses_class_enrolled.idCoursesClass');

        return $SQL;
    }

    public function getEnrolledStudentsByIdClassCourse($idCoursesClass){
        $SQL = DB::table('sis_online_course.courses_class_enrolled')
            ->where('courses_class_enrolled.idCoursesClass', $idCoursesClass)
            ->join('sis_online_course.account_member', 'account_member.idMember','=','courses_class_enrolled.idMember')
            ->OrderBy('courses_class_enrolled.enrollDateTime','ASC');

        return $SQL;
    }

    public function getEnrolledClassByIdMember($idMember){
        $SQL = DB::table('sis_online_course.courses_class_enrolled')
            ->where('courses_class_enrolled.idMember', $idMember)
            ->join('sis_online_course.created_courses_class', 'created_courses_class.idCoursesClass','=','courses_class_enrolled.idCoursesClass')
            ->join('sis_online_course.available_courses','available_courses.idAvailableCourse','=','created_courses_class.idAvailableCourse')
            ->join('sis_online_course.available_class','available_class.idAvailableClass','=','created_courses_class.idAvailableClass')
            ->join('sis.ac_mata_kuliah','available_courses.CourseCode','=','ac_mata_kuliah.kode_mata_kuliah');

        return $SQL;
    }

    public function getEnrolledClassByIdClassCourse($idClassCourse){
        $SQL = DB::table('sis_online_course.courses_class_enrolled')
            ->where('courses_class_enrolled.idCoursesClass', $idClassCourse)
            ->join('sis_online_course.created_courses_class', 'created_courses_class.idCoursesClass','=','courses_class_enrolled.idCoursesClass');

        return $SQL;
    }

    public function getWaitingListClassByIdClassCourseAndIdMember($idClassCourse, $idMember){
        $SQL = DB::table('sis_online_course.courses_class_waiting_list')
            ->where('courses_class_waiting_list.idMember', $idMember)
            ->where('courses_class_waiting_list.idCoursesClass', $idClassCourse)
            ->join('sis_online_course.created_courses_class', 'created_courses_class.idCoursesClass','=','courses_class_waiting_list.idCoursesClass');

        return $SQL;
    }

    public function insertIntoEnrolledCoursesClass($idUser, $idClassCourse){
        $dateNow = date('Y-m-d H:i:s');
        $values = array('idCoursesClassEnrolled' => NULL, 'idCoursesClass' => $idClassCourse, 'idMember' => $idUser, 'enrollDateTime' => $dateNow);
        $SQL = DB::table('sis_online_course.courses_class_enrolled')->insert($values);

        return $SQL;
    }

    public function insertIntoWaitingListCoursesClass($idUser, $idClassCourse){
        $dateNow = date('Y-m-d H:i:s');
        $values = array('idCoursesClassWaitingList' => NULL, 'idCoursesClass' => $idClassCourse, 'idMember' => $idUser, 'DateTime' => $dateNow, 'isConfirmed' => '0');
        $SQL = DB::table('sis_online_course.courses_class_waiting_list')->insert($values);

        return $SQL;
    }

    public function getMemberData($idUser){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.idMember', $idUser)
            ->join('sis_online_course.account_authority', 'account_authority.idAuthority', '=', 'account_member.idAuthority');

        return $SQL;
    }

    public function getHighestEducationData(){
        $SQL = DB::table('sis_online_course.account_member_highest_education')
            ->orderBy('account_member_highest_education.idHighestEducation', 'desc');

        return $SQL;
    }

    public function getWorkingFieldData(){
        $SQL = DB::table('sis_online_course.account_member_working_field')
            ->orderBy('account_member_working_field.idWorkingField', 'desc');

        return $SQL;
    }

    public function getResetPasswordRequest($idRequestPassword){
        $SQL = DB::table('sis_online_course.account_member_reset_password_request')
            ->where('account_member_reset_password_request.id', $idRequestPassword);

        return $SQL;
    }

    public function getAllPasswordRequestByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member_reset_password_request')
            ->where('account_member_reset_password_request.idMember', $idMember);

        return $SQL;
    }

    public function getUserRequestPasswordByIdMemberAndIdRequest($idMember, $idRequestPassword){
        $SQL = DB::table('sis_online_course.account_member_reset_password_request')
            ->where('account_member_reset_password_request.idMember', $idMember)
            ->where('account_member_reset_password_request.id', $idRequestPassword);

        return $SQL;
    }

    public function changeUserPassword($idMember, $password){
        $values = array(
            'Password' => $password
        );

        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.idMember', $idMember)
            ->update($values);

        return $SQL;
    }

    public function setResetedForPasswordResetRequest($idRequestPassword){
        $values = array(
            'isReset' => '1'
        );

        $SQL = DB::table('sis_online_course.account_member_reset_password_request')
            ->where('account_member_reset_password_request.id', $idRequestPassword)
            ->update($values);

        return $SQL;
    }

    public function insertFileDirectory($idMember,$completePathFile){
        $values = array(
            'idMemberPhoto' => NULL,
            'idMember' => $idMember,
            'PhotoDirectory' => $completePathFile
        );

        DB::table('sis_online_course.account_member_photo')->insertGetId($values);
    }

    public function updateFileDirectory($idMember,$completePathFile){
        $values = array(
            'PhotoDirectory' => $completePathFile
        );

        DB::table('sis_online_course.account_member_photo')
            ->where('account_member_photo.idMember', $idMember)
            ->update($values);
    }

    public function updateUserPersonalInformation($req, $idMember){
        $values = array(
            'nameFirst' => $req->firstName,
            'nameLast' => $req->lastName
        );

        DB::table('sis_online_course.account_member_public')
            ->where('account_member_public.idMember', $idMember)
            ->update($values);
    }

    public function updatePublicMemberPersonalData($req, $idMember){
        $isSubscription = '0';
        $interestedReason = '';

        if(!empty($req->subscription)){
            $isSubscription = '1';
        }

        if(!empty($req->interestedReason)){
            $interestedReason = $req->interestedReason;
        }
        $values = array(
            'nameFirst' => $req->firstName,
            'nameLast' => $req->lastName,
            'gender' => $req->gender,
            'birthPlace' => $req->birthPlace,
            'birthDate' => $req->birthDate,
            'nationality' => $req->nationality,
            'phoneNumber' => $req->phoneNumber,
            'emailAddress' => $req->email,
            'idHighestEducation' => $req->highestEducation,
            'highestEducationInstitution' => $req->highestEducationInstitution,
            'idWorkingField' => $req->workingField,
            'workingPosition' => $req->workingPosition,
            'workingInstitution' => $req->workingInstitution,
            'workingExperience' => $req->workingExperience,
            'isSubscription' => $isSubscription,
            'interestedReason' => $interestedReason
        );

        DB::table('sis_online_course.account_member_public')
            ->where('account_member_public.idMember',$idMember)
            ->where('account_member_public.isVerified','1')
            ->update($values);

        $SQL = DB::table('sis_online_course.account_member_public')
            ->where('account_member_public.idMember',$idMember)
            ->where('account_member_public.isVerified','1')
            ->join('sis_online_course.account_member','account_member_public.idMember','=','account_member.idMember');

        return $SQL;
    }

    public function getStaffSourceData($Username){
        $SQL = DB::table('sis.access')
            ->where('access.username',$Username)
            ->join('sis_organization.organization_member','organization_member.id_user_login','=','access.id')
            ->join('sis_organization.organization_unit_position','organization_member.id_unit_position','=','organization_unit_position.id_position')
            ->join('sis_organization.organization_unit', 'organization_unit_position.id_unit','=','organization_unit.id_unit');

        return $SQL;
    }

    public function getLecturerSourceData($idUserLogin){
        $SQL = DB::table('sis.access')
            ->where('access.id', $idUserLogin)
            ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user');

        return $SQL;
    }

    public function getAvailableStaff(){
        $SQLnotIn = DB::table('sis_online_course.account_member')->select('account_member.Username');

        $SQL = DB::table('sis_organization.organization_member')
            ->join('sis_organization.organization_unit_position','organization_member.id_unit_position','=','organization_unit_position.id_position')
            ->join('sis_organization.organization_unit', 'organization_unit_position.id_unit','=','organization_unit.id_unit')
            ->join('sis.access','organization_member.id_user_login','=','access.id')
            ->whereNotIn('access.username', $SQLnotIn);

        return $SQL;
    }

    public function getAdministrators(){
        $SQL = DB::table('sis_online_course.account_member')
            ->join('sis_online_course.account_authority','account_member.idAuthority','=','account_authority.idAuthority')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis_organization.organization_member','access.id','=','organization_member.id_user_login')
            ->join('sis_organization.organization_unit_position','organization_member.id_unit_position','=','organization_unit_position.id_position')
            ->join('sis_organization.organization_unit', 'organization_unit_position.id_unit','=','organization_unit.id_unit')
            ->where('account_member.idAuthority','1')
            ->orWhere('account_member.idAuthority','2');

        return $SQL;
    }

    public function getRegisteredLecturers(){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.idAuthority','3')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user');

        return $SQL;
    }

    public function getUnregisteredLecturerOnServer(){
        $SQLnotIn = DB::table('sis_online_course.account_member')->select('account_member.Username');

        $SQL = DB::table('sis.access')
            ->join('sis.ac_data_umum_dosen','access.id','=','ac_data_umum_dosen.id_user')
            ->whereNotIn('access.username', $SQLnotIn);

        return $SQL;
    }

    public function registerAdministrator($idAuthority, $dataUser){
        $dateNow = date('Y-m-d H:i:s');

        $values = array(
            'idMember' => NULL,
            'idAuthority' => $idAuthority,
            'Username' => $dataUser->username,
            'Password' => $dataUser->password,
            'IsActive' => '1',
            'Registered' => $dateNow,
            'ValidUntil' => ''
        );

        $id = DB::table('sis_online_course.account_member')->insertGetId($values);

        return $id;
    }

    public function registerLecturer($dataUser){
        $dateNow = date('Y-m-d H:i:s');

        $values = array(
            'idMember' => NULL,
            'idAuthority' => '3',
            'Username' => $dataUser->username,
            'Password' => $dataUser->password,
            'IsActive' => '1',
            'Registered' => $dateNow,
            'ValidUntil' => ''
        );

        $id = DB::table('sis_online_course.account_member')->insertGetId($values);

        return $id;
    }

    public function registerStudent($data){
        $dateNow = date('Y-m-d H:i:s');

        $values = array(
            'idMember' => NULL,
            'idAuthority' => '4',
            'Username' => $data->username,
            'Password' => $data->password,
            'IsActive' => '1',
            'Registered' => $dateNow,
            'ValidUntil' => ''
        );

        $id = DB::table('sis_online_course.account_member')->insertGetId($values);

        return $id;
    }

    public function removeMemberData($idMember){
        DB::table('sis_online_course.account_member')->where('account_member.idMember',$idMember)->delete();
        DB::table('sis_online_course.account_member_photo')->where('account_member_photo.idMember',$idMember)->delete();
        DB::table('sis_online_course.account_member_public')->where('account_member_public.idMember',$idMember)->where('account_member_public.isVerified','1')->delete();
    }

    public function removeUnverifiedPublicMember($idMember){
        DB::table('sis_online_course.account_member_public_verification')->where('account_member_public_verification.idMember',$idMember)->where('account_member_public_verification.isVerified','0')->delete();
        DB::table('sis_online_course.account_member_public')->where('account_member_public.idMember',$idMember)->where('account_member_public.isVerified','0')->delete();
    }


    public function getRegisteredStudentMember(){
        $SQL = DB::table('sis_online_course.account_member')
            ->where('account_member.idAuthority','4')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_mahasiswa','access.id','=','ac_data_umum_mahasiswa.id_user')
            ->join('sis.ac_angkatan_kelas','ac_data_umum_mahasiswa.id_angkatan','=','ac_angkatan_kelas.id_angkatan')
            ->join('sis.ac_program','ac_angkatan_kelas.id_program','=','ac_program.id_program')
            ->orderBy('ac_program.nama_program','ASC')
            ->orderBy('ac_angkatan_kelas.nomor_angkatan','ASC')
            ->orderBy('ac_data_umum_mahasiswa.nama','ASC');

        return $SQL;
    }

    public function getStudentMemberDataByNim($nim){
        $SQL = DB::table('sis_online_course.account_member')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_mahasiswa','access.id','=','ac_data_umum_mahasiswa.id_user')
            ->join('sis.ac_angkatan_kelas','ac_data_umum_mahasiswa.id_angkatan','=','ac_angkatan_kelas.id_angkatan')
            ->join('sis.ac_program','ac_angkatan_kelas.id_program','=','ac_program.id_program')
            ->where('account_member.idAuthority','4')
            ->where('ac_data_umum_mahasiswa.nim',$nim);

        return $SQL;
    }

    public function getStudentMemberDataByIdMember($idMember){
        $SQL = DB::table('sis_online_course.account_member')
            ->join('sis.access','account_member.Username','=','access.username')
            ->join('sis.ac_data_umum_mahasiswa','access.id','=','ac_data_umum_mahasiswa.id_user')
            ->join('sis.ac_angkatan_kelas','ac_data_umum_mahasiswa.id_angkatan','=','ac_angkatan_kelas.id_angkatan')
            ->join('sis.ac_program','ac_angkatan_kelas.id_program','=','ac_program.id_program')
            ->where('account_member.idAuthority','4')
            ->where('account_member.idMember',$idMember);

        return $SQL;
    }

    public function getStudentDataByNim($nim){
        $SQL = DB::table('sis.access')
            ->join('sis.ac_data_umum_mahasiswa','access.id','=','ac_data_umum_mahasiswa.id_user')
            ->join('sis.ac_angkatan_kelas','ac_data_umum_mahasiswa.id_angkatan','=','ac_angkatan_kelas.id_angkatan')
            ->join('sis.ac_program','ac_angkatan_kelas.id_program','=','ac_program.id_program')
            ->where('ac_data_umum_mahasiswa.nim',$nim);

        return $SQL;
    }

    public function getAvailableEducationProgram(){
        $SQL = DB::table('sis.ac_program');

        return $SQL;
    }

    public function getAvailableEducationProgramByIdAvailableClass($idAvailableClass){
        $SQL = DB::table('sis_online_course.available_class')
            ->where('available_class.idAvailableClass',$idAvailableClass);

        return $SQL;
    }

    public function insertNewOnlineCourse($req){
        $courseName = "";
        $CourseCode = "";

        if($req->courseType == "0"){
            $courseName = $req->courseName;
            $CourseCode = $req->newCourseCode;
        }else{
            $CourseCode = $req->courseCode;
        }

        $isExist = DB::table('sis_online_course.available_courses')->where('available_courses.CourseCode',$CourseCode)->count();

        if($isExist == 0) {

            $values = array(
                'idAvailableCourse' => NULL,
                'CourseCode' => $CourseCode,
                'isRegisteredInCurriculum' => $req->courseType,
                'courseName' => $courseName
            );

            DB::table('sis_online_course.available_courses')->insert($values);

            return redirect('/manageOnlineCourse/manageCourse')->with('success', 'New course has been inserted successfully.');

        }else{

            return redirect('/manageOnlineCourse/manageCourse/addNewCourse')->withErrors('Duplicate Course Code : '.$CourseCode);

        }

    }

    public function insertNewOnlineProgramName($req){
        $idProgram = "0";

        if($req->onlineCourseType == "1"){
            $idProgram = $req->onlineCourseProgram;
        }

        $values = array(
            'idAvailableClass' => NULL,
            'idProgram' => $idProgram,
            'isDegreeProgram' => $req->onlineCourseType,
            'OnlineProgramName' => $req->onlineCourseProgramName
        );

        DB::table('sis_online_course.available_class')->insert($values);
    }

    public function editOnlineProgramName($idAvailableClass, $req){
        $idProgram = "0";

        if($req->onlineCourseType == "1"){
            $idProgram = $req->onlineCourseProgram;
        }

        $values = array(
            'idProgram' => $idProgram,
            'isDegreeProgram' => $req->onlineCourseType,
            'OnlineProgramName' => $req->onlineCourseProgramName
        );

        DB::table('sis_online_course.available_class')->where('available_class.idAvailableClass',$idAvailableClass)->update($values);
    }

    public function getWaitingConfirmationNewPublicMember(){
        $SQL = DB::table('sis_online_course.account_member_public_verification')
            ->join('sis_online_course.account_member_public','account_member_public_verification.idMember','=','account_member_public.idMember')
            ->join('sis_online_course.account_member_highest_education','account_member_public.idHighestEducation','=','account_member_highest_education.idHighestEducation')
            ->join('sis_online_course.account_member_working_field','account_member_public.idWorkingField','=','account_member_working_field.idWorkingField')
            ->where('account_member_public_verification.isVerified','0')
            ->where('account_member_public.isVerified','0');

        return $SQL;
    }

    public function getVerifiedPublicMember(){
        $SQL = DB::table('sis_online_course.account_member')
            ->join('sis_online_course.account_member_public','account_member.idMember','=','account_member_public.idMember')
            ->join('sis_online_course.account_member_public_verification','account_member_public_verification.Username','=','account_member.Username')
            ->join('sis_online_course.account_member_highest_education','account_member_public.idHighestEducation','=','account_member_highest_education.idHighestEducation')
            ->join('sis_online_course.account_member_working_field','account_member_public.idWorkingField','=','account_member_working_field.idWorkingField')
            ->where('account_member_public_verification.isVerified','1')
            ->where('account_member_public.isVerified','1');

        return $SQL;
    }

    public function suspendAccountMemberByIdMember($idMember){
        $values = array(
            'IsActive' => '0'
        );

        DB::table('sis_online_course.account_member')->where('account_member.idMember',$idMember)->update($values);
    }

    public function activateAccountMemberByIdMember($idMember){
        $values = array(
            'IsActive' => '1'
        );

        DB::table('sis_online_course.account_member')->where('account_member.idMember',$idMember)->update($values);
    }

    public function getAvailableProgram(){
        $SQL = DB::table('sis_online_course.available_class');

        return $SQL;
    }

    public function insertNewAssignment($idSubTopic,$req){
        $dateTime = date('Y-m-d H:i:s');

        $values = array(
            'idAssignment' => NULL,
            'idSubTopic' => $idSubTopic,
            'idUser' => session('idMember'),
            'dateTime' => $dateTime,
            'assignmentType' => $req->typeAssignment,
            'assignmentDescription' => $req->description,
            'assignmentDeadline' => $req->deadline,
            'isRequired' => $req->isRequired,
            'scoreRangeStart' => $req->minScore,
            'scoreRangeEnd' => $req->maxScore
        );

        $idAssignment = DB::table('sis_online_course.created_courses_class_assignment')->InsertGetId($values);

        return $idAssignment;
    }

    public function insertNewExam($idSubTopic,$req){
        $dateTime = date('Y-m-d H:i:s');

        $values = array(
            'idExam' => NULL,
            'idSubTopic' => $idSubTopic,
            'idUser' => session('idMember'),
            'dateTime' => $dateTime,
            'examType' => $req->typeExam,
            'examDescription' => $req->description,
            'examDeadline' => $req->deadline,
            'scoreRangeStart' => $req->minScore,
            'scoreRangeEnd' => $req->maxScore
        );

        $idExam = DB::table('sis_online_course.created_courses_class_exam')->InsertGetId($values);

        return $idExam;
    }

    public function deleteOnlineCourseAssignmentByIdAssignment($idAssignment, $typeAssignment){
        DB::table('sis_online_course.created_courses_class_assignment')->where('created_courses_class_assignment.idAssignment',$idAssignment)->delete();

        $Question = DB::table('sis_online_course.created_courses_class_assignment_question')->select('created_courses_class_assignment_question.idQuestion')->where('created_courses_class_assignment_question.idAssignment',$idAssignment)->get();

        foreach($Question AS $dataQuestion){
            $idQuestion = $dataQuestion->idQuestion;

            DB::table('sis_online_course.created_courses_class_assignment_question')->where('created_courses_class_assignment_question.idQuestion',$idQuestion)->delete();

            if(strtoupper($typeAssignment) == "CHOICES"){
                DB::table('sis_online_course.created_courses_class_assignment_answer_choice')->where('created_courses_class_assignment_answer_choice.idQuestion',$idQuestion)->delete();
            }
        }
    }

    public function deleteOnlineCourseExamByIdExam($idExam, $typeExam){
        DB::table('sis_online_course.created_courses_class_exam')->where('created_courses_class_exam.idExam',$idExam)->delete();

        $Question = DB::table('sis_online_course.created_courses_class_exam_question')->select('created_courses_class_exam_question.idQuestion')->where('created_courses_class_exam_question.idExam',$idExam)->get();

        foreach($Question AS $dataQuestion){
            $idQuestion = $dataQuestion->idQuestion;

            DB::table('sis_online_course.created_courses_class_exam_question')->where('created_courses_class_exam_question.idQuestion',$idQuestion)->delete();

            if(strtoupper($typeExam) == "CHOICES"){
                DB::table('sis_online_course.created_courses_class_exam_answer_choice')->where('created_courses_class_exam_answer_choice.idQuestion',$idQuestion)->delete();
            }
        }
    }

    public function getCreatedCourseClassAssignmentStudentAnswerByIdQuestionAndIdMember($idQuestion, $idMember){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_student_answer')
            ->where('created_courses_class_assignment_student_answer.idQuestion',$idQuestion)
            ->where('created_courses_class_assignment_student_answer.idMember',$idMember);

        return $SQL;
    }

    public function getCreatedCourseClassExamStudentAnswerByIdQuestionAndIdMember($idQuestion, $idMember){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_student_answer')
            ->where('created_courses_class_exam_student_answer.idQuestion',$idQuestion)
            ->where('created_courses_class_exam_student_answer.idMember',$idMember);

        return $SQL;
    }

    public function submitCreatedCoursesClassAssignmentAnswer($idQuestion, $answer){
        $values = array(
            'idStudentAnswer' => NULL,
            'idQuestion' => $idQuestion,
            'idMember' => session('idMember'),
            'dateTime' => date('Y-m-d H:i:s'),
            'answerValue' => $answer,
            'answerScore' => '0',
            'isScored' => '0',
            'answerSuggestion' => ''
        );

        DB::table('sis_online_course.created_courses_class_assignment_student_answer')->insert($values);
    }

    public function submitCreatedCoursesClassExamAnswer($idQuestion, $answer){
        $values = array(
            'idStudentAnswer' => NULL,
            'idQuestion' => $idQuestion,
            'idMember' => session('idMember'),
            'dateTime' => date('Y-m-d H:i:s'),
            'answerValue' => $answer,
            'answerScore' => '0',
            'isScored' => '0',
            'answerSuggestion' => ''
        );

        DB::table('sis_online_course.created_courses_class_exam_student_answer')->insert($values);
    }

    public function submitCreatedCoursesClassAssignmentChoicesAnswer($idQuestion, $answer, $score){
        $values = array(
            'idStudentAnswer' => NULL,
            'idQuestion' => $idQuestion,
            'idMember' => session('idMember'),
            'dateTime' => date('Y-m-d H:i:s'),
            'answerValue' => $answer,
            'answerScore' => $score,
            'isScored' => '1',
            'answerSuggestion' => ''
        );

        DB::table('sis_online_course.created_courses_class_assignment_student_answer')->insert($values);
    }

    public function submitCreatedCoursesClassExamChoicesAnswer($idQuestion, $answer, $score){
        $values = array(
            'idStudentAnswer' => NULL,
            'idQuestion' => $idQuestion,
            'idMember' => session('idMember'),
            'dateTime' => date('Y-m-d H:i:s'),
            'answerValue' => $answer,
            'answerScore' => $score,
            'isScored' => '1',
            'answerSuggestion' => ''
        );

        DB::table('sis_online_course.created_courses_class_exam_student_answer')->insert($values);
    }

    public function getAssignmentCompletionByIdAssignmentAndIdMember($idAssignment, $idMember){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_student_completion')
            ->where('created_courses_class_assignment_student_completion.idAssignment', $idAssignment)
            ->where('created_courses_class_assignment_student_completion.idMember', $idMember);

        return $SQL;
    }

    public function getExamCompletionByIdExamAndIdMember($idExam, $idMember){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_student_completion')
            ->where('created_courses_class_exam_student_completion.idExam', $idExam)
            ->where('created_courses_class_exam_student_completion.idMember', $idMember);

        return $SQL;
    }

    public function getAssignmentCompletionStatusByIdAssignmentAndIdMember($idAssignment, $idMember, $isEvaluated){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_student_completion')
            ->where('created_courses_class_assignment_student_completion.idAssignment', $idAssignment)
            ->where('created_courses_class_assignment_student_completion.idMember', $idMember)
            ->where('created_courses_class_assignment_student_completion.isEvaluated', $isEvaluated);

        return $SQL;
    }

    public function getAssignmentCompletionStatusByIdSubTopicAndIdMember($idSubTopic, $idMember, $isEvaluated){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_student_completion')
            ->leftJoin('sis_online_course.created_courses_class_assignment','created_courses_class_assignment.idAssignment','=','created_courses_class_assignment_student_completion.idAssignment')
            ->where('created_courses_class_assignment.idSubTopic','=',$idSubTopic)
            ->where('created_courses_class_assignment_student_completion.idMember', $idMember)
            ->where('created_courses_class_assignment_student_completion.isEvaluated', $isEvaluated);

        return $SQL;
    }

    public function getAssignmentCompletionByIdAssignment($idAssignment,$isEvaluated){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_student_completion')
            ->where('created_courses_class_assignment_student_completion.idAssignment', $idAssignment)
            ->where('created_courses_class_assignment_student_completion.isEvaluated', $isEvaluated);

        return $SQL;
    }

    public function getExamCompletionByIdExam($idExam,$isEvaluated){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_student_completion')
            ->where('created_courses_class_exam_student_completion.idExam', $idExam)
            ->where('created_courses_class_exam_student_completion.isEvaluated', $isEvaluated);

        return $SQL;
    }

    public function getAllAssignmentCompletionByIdAssignment($idAssignment){
        $SQL = DB::table('sis_online_course.created_courses_class_assignment_student_completion')
            ->where('created_courses_class_assignment_student_completion.idAssignment', $idAssignment)
            ->OrderBy('created_courses_class_assignment_student_completion.isEvaluated','ASC')
            ->OrderBy('created_courses_class_assignment_student_completion.dateTime','ASC');

        return $SQL;
    }
    
    public function getAllExamCompletionByIdExam($idExam){
        $SQL = DB::table('sis_online_course.created_courses_class_exam_student_completion')
            ->where('created_courses_class_exam_student_completion.idExam', $idExam)
            ->OrderBy('created_courses_class_exam_student_completion.isEvaluated','ASC')
            ->OrderBy('created_courses_class_exam_student_completion.dateTime','ASC');

        return $SQL;
    }
    

    public function getCoursesClassAssignmentScoreByIdAssignment($idAssignment){
        $score = DB::table('sis_online_course.created_courses_class_assignment_student_answer')
            ->join('sis_online_course.created_courses_class_assignment_question','created_courses_class_assignment_student_answer.idQuestion','=','created_courses_class_assignment_question.idQuestion')
            ->where('created_courses_class_assignment_question.idAssignment',$idAssignment)
            ->where('created_courses_class_assignment_student_answer.isScored','1')
            ->sum('created_courses_class_assignment_student_answer.answerScore');

        return $score;
    }

    public function getTotalAssignmentScoreByIdAssignmentAndIdMember($idAssignment, $idMember){
        $score = DB::table('sis_online_course.created_courses_class_assignment_student_answer')
            ->join('sis_online_course.created_courses_class_assignment_question','created_courses_class_assignment_student_answer.idQuestion','=','created_courses_class_assignment_question.idQuestion')
            ->where('created_courses_class_assignment_question.idAssignment',$idAssignment)
            ->where('created_courses_class_assignment_student_answer.isScored','1')
            ->where('created_courses_class_assignment_student_answer.idMember',$idMember)
            ->sum('created_courses_class_assignment_student_answer.answerScore');

        $countQuestion = DB::table('sis_online_course.created_courses_class_assignment_question')
            ->where('created_courses_class_assignment_question.idAssignment', $idAssignment)
            ->count();

        $score = $score / $countQuestion;

        return round($score,2);
    }

    public function getTotalExamScoreByIdExamAndIdMember($idExam, $idMember){
        $score = DB::table('sis_online_course.created_courses_class_exam_student_answer')
            ->join('sis_online_course.created_courses_class_exam_question','created_courses_class_exam_student_answer.idQuestion','=','created_courses_class_exam_question.idQuestion')
            ->where('created_courses_class_exam_question.idExam',$idExam)
            ->where('created_courses_class_exam_student_answer.isScored','1')
            ->where('created_courses_class_exam_student_answer.idMember',$idMember)
            ->sum('created_courses_class_exam_student_answer.answerScore');

        $countQuestion = DB::table('sis_online_course.created_courses_class_exam_question')
            ->where('created_courses_class_exam_question.idExam', $idExam)
            ->count();

        $score = $score / $countQuestion;

        return round($score,2);
    }

    public function evaluateStudentAnswerByIdQuestionAndIdMember($idMember, $req){
        $suggestion = "";

        if($req->suggestion){
            $suggestion = $req->suggestion;
        }

        $values = array(
            'answerScore' => $req->score,
            'answerSuggestion' => $suggestion,
            'isScored' => '1'
        );

        DB::table('sis_online_course.created_courses_class_assignment_student_answer')
            ->where('created_courses_class_assignment_student_answer.idQuestion', $req->idQuestion)
            ->where('created_courses_class_assignment_student_answer.idMember', $idMember)
            ->update($values);
    }

    public function evaluateStudentExamAnswerByIdQuestionAndIdMember($idMember, $req){
        $suggestion = "";

        if($req->suggestion){
            $suggestion = $req->suggestion;
        }

        $values = array(
            'answerScore' => $req->score,
            'answerSuggestion' => $suggestion,
            'isScored' => '1'
        );

        DB::table('sis_online_course.created_courses_class_exam_student_answer')
            ->where('created_courses_class_exam_student_answer.idQuestion', $req->idQuestion)
            ->where('created_courses_class_exam_student_answer.idMember', $idMember)
            ->update($values);
    }

    public function completeCreatedCoursesClassAssignmentByIdAssignmentAndIdMember($idAssignment, $idMember){
        $values = array(
            'idStudentCompletion' => NULL,
            'idAssignment' => $idAssignment,
            'dateTime' => date('Y-m-d H:i:s'),
            'idMember' => $idMember,
            'isEvaluated' => '0'
        );

        DB::table('sis_online_course.created_courses_class_assignment_student_completion')->insert($values);
    }

    public function completeCreatedCoursesClassExamByIdExamAndIdMember($idExam, $idMember){
        $values = array(
            'idStudentCompletion' => NULL,
            'idExam' => $idExam,
            'dateTime' => date('Y-m-d H:i:s'),
            'idMember' => $idMember,
            'isEvaluated' => '0'
        );

        DB::table('sis_online_course.created_courses_class_exam_student_completion')->insert($values);
    }

    public function finishEvaluationByIdAssignmentAndIdMember($idAssignment, $idMember){
        $values = array(
            'isEvaluated' => '1'
        );

        DB::table('sis_online_course.created_courses_class_assignment_student_completion')
            ->where('created_courses_class_assignment_student_completion.idAssignment',$idAssignment)
            ->where('created_courses_class_assignment_student_completion.idMember', $idMember)
            ->update($values);

        $Question = DB::table('sis_online_course.created_courses_class_assignment_question')
            ->where('created_courses_class_assignment_question.idAssignment',$idAssignment)
            ->get();

        $values = array(
            'isScored' => '1'
        );

        foreach($Question as $data){
            $idQuestion = $data->idQuestion;

            DB::table('sis_online_course.created_courses_class_assignment_student_answer')
                ->where('created_courses_class_assignment_student_answer.idQuestion', $idQuestion)
                ->where('created_courses_class_assignment_student_answer.idMember',$idMember)
                ->update($values);
        }
    }

    public function finishEvaluationByIdExamAndIdMember($idExam, $idMember){
        $values = array(
            'isEvaluated' => '1'
        );

        DB::table('sis_online_course.created_courses_class_exam_student_completion')
            ->where('created_courses_class_exam_student_completion.idExam',$idExam)
            ->where('created_courses_class_exam_student_completion.idMember', $idMember)
            ->update($values);

        $Question = DB::table('sis_online_course.created_courses_class_exam_question')
            ->where('created_courses_class_exam_question.idExam',$idExam)
            ->get();

        $values = array(
            'isScored' => '1'
        );

        foreach($Question as $data){
            $idQuestion = $data->idQuestion;

            DB::table('sis_online_course.created_courses_class_exam_student_answer')
                ->where('created_courses_class_exam_student_answer.idQuestion', $idQuestion)
                ->where('created_courses_class_exam_student_answer.idMember',$idMember)
                ->update($values);
        }
    }

    public function insertNewAssignmentQuestion($idAssignment, $req){
        $values = array(
            'idQuestion' => NULL,
            'idAssignment' => $idAssignment,
            'Question' => $req->question
        );

        $idQuestion = DB::table('sis_online_course.created_courses_class_assignment_question')->InsertGetId($values);

        if(strtoupper($req->typeAssignment) == "CHOICES"){
            $numArray = 0;
            foreach($req->choiceText AS $choiceText){
                $values = array(
                    'idChoice' => NULL,
                    'idQuestion' => $idQuestion,
                    'answerText' => $choiceText,
                    'choiceScore' => $req->scoreChoice[$numArray]
                );

                DB::table('sis_online_course.created_courses_class_assignment_answer_choice')->InsertGetId($values);

                $numArray++;
            }
        }

        return $idQuestion;
    }

    public function insertNewExamQuestion($idExam, $req){
        $values = array(
            'idQuestion' => NULL,
            'idExam' => $idExam,
            'Question' => $req->question
        );

        $idQuestion = DB::table('sis_online_course.created_courses_class_exam_question')->InsertGetId($values);

        if(strtoupper($req->typeExam) == "CHOICES"){
            $numArray = 0;
            foreach($req->choiceText AS $choiceText){
                $values = array(
                    'idChoice' => NULL,
                    'idQuestion' => $idQuestion,
                    'answerText' => $choiceText,
                    'choiceScore' => $req->scoreChoice[$numArray]
                );

                DB::table('sis_online_course.created_courses_class_exam_answer_choice')->InsertGetId($values);

                $numArray++;
            }
        }

        return $idQuestion;
    }

    public function updateAssignmentQuestion($req){
        $idQuestion = $req->idQuestion;

        $values = array(
            'Question' => $req->editQuestion
        );

        DB::table('sis_online_course.created_courses_class_assignment_question')
            ->where('created_courses_class_assignment_question.idQuestion',$idQuestion)
            ->update($values);

        if(strtoupper($req->typeAssignment) == "CHOICES"){
            DB::table('sis_online_course.created_courses_class_assignment_answer_choice')
                ->where('created_courses_class_assignment_answer_choice.idQuestion',$idQuestion)
                ->delete();

            $numArray = 0;

            foreach($req->choiceText AS $choiceText){
                $values = array(
                    'idChoice' => NULL,
                    'idQuestion' => $idQuestion,
                    'answerText' => $choiceText,
                    'choiceScore' => $req->scoreChoice[$numArray]
                );

                DB::table('sis_online_course.created_courses_class_assignment_answer_choice')->InsertGetId($values);

                $numArray++;
            }
        }
    }

    public function updateExamQuestion($req){
        $idQuestion = $req->idQuestion;

        $values = array(
            'Question' => $req->editQuestion
        );

        DB::table('sis_online_course.created_courses_class_exam_question')
            ->where('created_courses_class_exam_question.idQuestion',$idQuestion)
            ->update($values);

        if(strtoupper($req->typeExam) == "CHOICES"){
            DB::table('sis_online_course.created_courses_class_exam_answer_choice')
                ->where('created_courses_class_exam_answer_choice.idQuestion',$idQuestion)
                ->delete();

            $numArray = 0;

            foreach($req->choiceText AS $choiceText){
                $values = array(
                    'idChoice' => NULL,
                    'idQuestion' => $idQuestion,
                    'answerText' => $choiceText,
                    'choiceScore' => $req->scoreChoice[$numArray]
                );

                DB::table('sis_online_course.created_courses_class_exam_answer_choice')->InsertGetId($values);

                $numArray++;
            }
        }
    }

    public function findPrivateMessageByIdMemberAndIdCoursesClass($idCoursesClass, $idMember){
        $SQL = DB::table('sis_online_course.created_courses_class_private_message')
            ->where('created_courses_class_private_message.idCoursesClass',$idCoursesClass)
            ->where('created_courses_class_private_message.isEnded','!=','1')
            ->where(function($q) use ($idMember){
                $q->where('created_courses_class_private_message.idMember1',$idMember)
                    ->orWhere('created_courses_class_private_message.idMember2',$idMember);
            });

        return $SQL;
    }

    public function submitNewPrivateMessage($idCoursesClass, $req){
        $idMemberDestination = $this->getVerifiedUserByUsername($req->destination)->first()->idMember;
        $dateTime = date('Y-m-d H:i:s');

        $values = array(
            'idPrivateMessage' => NULL,
            'isEnded' => '0',
            'idCoursesClass' => $idCoursesClass,
            'titleMessage' =>$req->subject,
            'idMember1' => session('idMember'),
            'idMember2' => $idMemberDestination
        );

        $idPrivateMessage = DB::table('sis_online_course.created_courses_class_private_message')
            ->insertGetId($values);

        $values = array(
            'idPrivateMessageContent' => NULL,
            'isEnded' => '0',
            'idPrivateMessage' => $idPrivateMessage,
            'idMemberFrom' => session('idMember'),
            'dateTime' => $dateTime,
            'isReadByRecepient' => '0',
            'privateMessageContents' => $req->message
        );

        $idPrivateMessageContent = DB::table('sis_online_course.created_courses_class_private_message_content')->insertGetId($values);

        return $idPrivateMessageContent;sendReplyPrivateMessage($idPrivateMessage, $req);
    }

    public function getPrivateMessageDataByIdPrivateMessage($idPrivateMessage){
        $SQL = DB::table('sis_online_course.created_courses_class_private_message')
            ->where('created_courses_class_private_message.idPrivateMessage', $idPrivateMessage);

        return $SQL;
    }

    public function getPrivateMessageContentByIdPrivateMessageContent($idPrivateMessageContent){
        $SQL = DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.isEnded','!=','1')
            ->where('created_courses_class_private_message_content.idPrivateMessageContent', $idPrivateMessageContent);


        return $SQL;
    }

    public function getLatestPrivateMessageContentFromAnotherUser($idPrivateMessage, $idMemberConversation){
        $data = DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.idPrivateMessage', $idPrivateMessage)
            ->where('created_courses_class_private_message_content.idMemberFrom', $idMemberConversation)
            ->where('created_courses_class_private_message_content.isEnded','!=','1')
            ->OrderBy('created_courses_class_private_message_content.dateTime','DESC')
            ->first();

        return $data;
    }

    public function getLatestPrivateMessageContent($idPrivateMessage){
        $data = DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.idPrivateMessage', $idPrivateMessage)
            ->where('created_courses_class_private_message_content.isEnded','!=','1')
            ->OrderBy('created_courses_class_private_message_content.dateTime','DESC')
            ->first();

        return $data;
    }

    public function getPrivateMessageContentsByIdPrivateMessage($idPrivateMessage){
        $stmt = DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.idPrivateMessage', $idPrivateMessage)
            ->where('created_courses_class_private_message_content.isEnded','!=','1')
            ->OrderBy('created_courses_class_private_message_content.dateTime','ASC');

        return $stmt;
    }

    public function sendReplyPrivateMessage($idPrivateMessage, $req){
        $values = array(
           'idPrivateMessageContent' => NULL,
           'isEnded' => '0',
           'idPrivateMessage' => $idPrivateMessage,
           'idMemberFrom' => session('idMember'),
           'dateTime' => date('Y-m-d H:i:s'),
            'isReadByRecepient' => '0',
            'privateMessageContents' => $req->message
        );

        $idPrivateMessageContent = DB::table('sis_online_course.created_courses_class_private_message_content')
            ->insertGetId($values);

        return $idPrivateMessageContent;
    }

    public function updateReadByRecepientPrivateMessage($idPrivateMessage){
        $values = array(
            'isReadByRecepient' => '1',
        );

        DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.idPrivateMessage', $idPrivateMessage)
            ->where('created_courses_class_private_message_content.isReadByRecepient', '0')
            ->where('created_courses_class_private_message_content.isEnded','!=','1')
            ->where('created_courses_class_private_message_content.idMemberFrom','!=',session('idMember'))
            ->update($values);
    }

    public function getDestinationIdMemberByIdSender($idPrivateMessage,$idMember){
        $data = DB::table('sis_online_course.created_courses_class_private_message')
            ->where('created_courses_class_private_message.idPrivateMessage', $idPrivateMessage)
            ->where('created_courses_class_private_message.isEnded','!=','1')
            ->first();

        if(isset($data)) {
            switch ($idMember) {
                case $data->idMember1:
                    return $data->idMember2;
                    break;
                case $data->idMember2:
                    return $data->idMember1;
                    break;
                default:
                    return 0;
            }
        }else{
            return 0;
        }
    }

    public function getUnreadPrivateMessageByIdPrivateMessageAndIdMember($idPrivateMessage, $idMember){
        $stmt = DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.isReadByRecepient','=','0')
            ->where('created_courses_class_private_message_content.idMemberFrom','!=',$idMember)
            ->where('created_courses_class_private_message_content.isEnded','!=','1')
            ->where('created_courses_class_private_message_content.idPrivateMessage','=',$idPrivateMessage);

        return $stmt;
    }

    public function endPrivateMessageByUser($idPrivateMessage){
        $stmt = DB::table('sis_online_course.created_courses_class_private_message')
            ->where('created_courses_class_private_message.isEnded','!=','1')
            ->where('created_courses_class_private_message.idPrivateMessage','=',$idPrivateMessage);

        if($stmt->count() == 0){
            return 0;
            exit;
        }

        $values = array(
            'isEnded' => '1'
        );

        DB::table('sis_online_course.created_courses_class_private_message')
            ->where('created_courses_class_private_message.idPrivateMessage','=',$idPrivateMessage)
            ->where('created_courses_class_private_message.isEnded','=','0')
            ->update($values);

        DB::table('sis_online_course.created_courses_class_private_message_content')
            ->where('created_courses_class_private_message_content.idPrivateMessage','=',$idPrivateMessage)
            ->where('created_courses_class_private_message_content.isEnded','=','0')
            ->update($values);

        return 1;
    }

    public function getForumListByIdCoursesClass($idCoursesClass){
        $stmt = DB::table('sis_online_course.created_courses_class_forum')
            ->where('created_courses_class_forum.idCoursesClass','=',$idCoursesClass)
            ->OrderBy('created_courses_class_forum.dateTime','DESC');

        return $stmt;
    }

    public function createNewThread($idCoursesClass, $forumTitle){
        $values = array(
            'idForum' => NULL,
            'idCoursesClass' => $idCoursesClass,
            'dateTime' => date('Y-m-d H:i:s'),
            'isClosed' => '0',
            'idMemberCreator' => session('idMember'),
            'forumTitle' => $forumTitle
        );

        $idForum = DB::table('sis_online_course.created_courses_class_forum')
            ->insertGetId($values);

        return $idForum;
    }

    public function insertForumMessage($idForum, $messageTitle, $messageThread, $idForumMessageQuote){
        $values = array(
            'idForumMessage' => NULL,
            'idForum' => $idForum,
            'dateTime' => date('Y-m-d H:i:s'),
            'idMember' => session('idMember'),
            'idForumMessageQuote' => $idForumMessageQuote,
            'messageTitle' => $messageTitle,
            'messageContent' => $messageThread

        );

        $idForumMessage = DB::table('sis_online_course.created_courses_class_forum_message')
            ->insertGetId($values);

        return $idForumMessage;
    }

    public function insertFileForum($idForumMessage, $fileName, $completePathFile){
        $values = array(
            'idForumMessageFile' => NULL,
            'idForumMessage' => $idForumMessage,
            'fileName' => $fileName,
            'fileUrl' => $completePathFile
        );

        DB::table('sis_online_course.created_courses_class_forum_message_file')
            ->insert($values);
    }

    public function getForumMessageByIdForumMessage($idForumMessage){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_message')
            ->where('created_courses_class_forum_message.idForumMessage','=',$idForumMessage);

        return $stmt;
    }

    public function getStartThreadMessageFileByIdForumMessage($idForumMessage){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_message_file')
            ->where('created_courses_class_forum_message_file.idForumMessage','=',$idForumMessage);

        return $stmt;
    }

    public function getForumDataByIdForum($idForum){
        $stmt = DB::table('sis_online_course.created_courses_class_forum')
            ->where('created_courses_class_forum.idForum','=',$idForum);

        return $stmt;
    }

    public function getLatestThreadMessageByIdForum($idForum){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_message')
            ->where('created_courses_class_forum_message.idForum','=',$idForum)
            ->OrderBy('created_courses_class_forum_message.dateTime','DESC')->first();

        return $stmt;
    }

    public function getAllThreadMessageByIdForum($idForum){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_message')
            ->where('created_courses_class_forum_message.idForum','=',$idForum)
            ->OrderBy('created_courses_class_forum_message.idForumMessage','ASC');

        return $stmt;
    }

    public function getStartThreadMessageByIdForumAndIdMember($idForum, $idMember){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_message')
            ->where('created_courses_class_forum_message.idForum','=',$idForum)
            ->where('created_courses_class_forum_message.idMember','=',$idMember)
            ->OrderBy('created_courses_class_forum_message.dateTime','ASC')
            ->first();

        return $stmt;
    }

    public function countAsNewForumView($idForum, $idMember){
        $values = array(
            'idForumView' => NULL,
            'idForum' => $idForum,
            'dateTimeView' => date('Y-m-d H:i:s'),
            'idMember' => $idMember
        );

        DB::table('sis_online_course.created_courses_class_forum_views')->insert($values);
    }

    public function getForumViewDataByIdForum($idForum){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_views')
            ->where('created_courses_class_forum_views.idForum','=',$idForum)
            ->OrderBy('created_courses_class_forum_views.dateTimeViews','ASC');

        return $stmt;
    }

    public function getForumViewDataByIdForumAndIdMember($idForum, $idMember){
        $stmt = DB::table('sis_online_course.created_courses_class_forum_views')
            ->where('created_courses_class_forum_views.idForum','=',$idForum)
            ->where('created_courses_class_forum_views.idMember','=',$idMember);

        return $stmt;
    }

    public function postForumMessageReply($idForum, $req){
        $idForumMessageQuote = "0";

        if(!empty($req->idForumMessageQuote)){
            $idForumMessageQuote = $req->idForumMessageQuote;
        }

        $values = array(
            'idForumMessage' => NULL,
            'idForum' => $idForum,
            'dateTime' => date('Y-m-d H:i:s'),
            'idMember' => session('idMember'),
            'idForumMessageQuote' => $idForumMessageQuote,
            'messageTitle' => $req->titleNewPost,
            'messageContent' => $req->messagePost
        );

        $idForumMessage = DB::table('sis_online_course.created_courses_class_forum_message')
            ->insertGetId($values);

        return $idForumMessage;
    }

    public function closeForumThread($idForum){
        $values = array(
            'isClosed' => '1'
        );

        DB::table('sis_online_course.created_courses_class_forum')->update($values);
    }
}
?>