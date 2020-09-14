<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CoursesController@index');
Route::get('/help', 'CoursesController@help_page');

Route::get('/course/{course_code}-{class_course_id}/about', 'CoursesController@showClassCourseAbout');

Route::get('/login', 'UserController@isLogin');

Route::get('/logout', 'UserController@doLogout');

Route::get('/register', 'UserController@userRegister');

Route::post('/registerUserAccount', 'UserController@doRegisterUserAccount');

Route::get('/forgot_password', 'UserController@doForgotPassword');

Route::get('/reset_password', 'UserController@resetUserPassword');

Route::get('/resetMyPassword/{idMember}/{idRequestPassword}', 'UserController@resetMyPassword');

Route::get('/submitNewPassword', 'UserController@submitNewPassword');

Route::get('/checkUsernameAvailability/{username}', 'UserController@doCheckUsernameAvailability');

Route::get('/newPublicMemberVerification/{token}/{idMember}', 'UserController@doVerifyNewPublicAccount');

Route::post('/authentification', 'UserController@doAuth');

Route::get('/authentification/sessionCheck/isLogin', 'UserController@isSessionLogin');

Route::get('/userAction/enrollClass/{class_course_id}', 'UserController@userEnrollClass');

Route::get('/dashboard', 'UserController@loadDefaultUserDashboard');

Route::group(['prefix'=> 'ServerSide'], function () {
    Route::group(['prefix'=> 'ManageOnlineClass'], function () {
        Route::group(['prefix'=> 'editOnlineClassSchedule'], function () {
            Route::post("/",'UserActionController@showEditOnlineClassSchedule');
            Route::post("/submit",'UserActionController@submitEditOnlineClassSchedule');
        });

        Route::group(['prefix'=> 'addOnlineClassMentor'], function () {
            Route::post("/",'UserActionController@showAddMentorOnlineClass');
            Route::post("/submit",'UserActionController@submitAddMentorOnlineClass');
            Route::get("/deleteMentor/{idCoursesClass}/{idMember}",'UserActionController@deleteMentorFromOnlineClass');
        });

        Route::group(['prefix'=> 'addOnlineClassDescription'], function () {
            Route::post("/",'UserActionController@showAddOnlineClassDescriptionForm');
            Route::post("/submit",'UserActionController@submitAddOnlineClassDescriptionForm');
        });

        Route::group(['prefix'=> 'addOnlineClassOverview'], function () {
            Route::post("/",'UserActionController@showAddOnlineClassOverviewForm');
            Route::post("/submit",'UserActionController@submitAddOnlineClassOverviewForm');
        });

        Route::group(['prefix'=> 'ActivateOnlineClass'], function () {
            Route::post("/",'UserActionController@activateOnlineClass');
        });
    });
});


Route::group(['prefix'=> 'member'], function () {
    Route::get("/MyAccount",'UserActionController@viewUserAccount');

    Route::post("/updateUserInformation",'UserActionController@updateUserInformation');

    Route::post("/updateUserPhoto",'UserActionController@updateUserPhoto');
});

Route::group(['prefix'=> 'manageMember'], function () {

    Route::get("/",'UserController@loadDefaultUserDashboard');

    Route::group(['prefix'=> 'admin'], function () {

        Route::get("/",'UserActionController@showAdministratorMember');

        Route::get("/addAdmin/{idAuthority}/{Username}",'UserActionController@addAdminMember');

        Route::get("/removeAdmin/{idMember}",'UserActionController@removeAdminMember');

        Route::get("/suspendUser/{idMember}/{backTarget}",'UserActionController@suspendMember');

        Route::get("/activateUser/{idMember}/{backTarget}",'UserActionController@activateMember');

    });

    Route::group(['prefix'=> 'lecturer'], function () {

        Route::get("/",'UserActionController@showLecturerMember');

        Route::get("/suspendUser/{idMember}/{backTarget}",'UserActionController@suspendMember');

        Route::get("/activateUser/{idMember}/{backTarget}",'UserActionController@activateMember');

        Route::group(['prefix'=> 'addLecturer'], function () {

            Route::get("/showAvailable",'UserActionController@showAvailableLecturer');

            Route::get("/addMember/{idUserLogin}",'UserActionController@addLecturer');

        });
    });

    Route::group(['prefix'=> 'student'], function () {

        Route::get("/",'UserActionController@showStudentMember');

        Route::post("/findAvailableStudent",'UserActionController@findAvailableStudent');

        Route::post("/addStudent",'UserActionController@addStudent');

        Route::get("/suspendUser/{idMember}/{backTarget}",'UserActionController@suspendMember');

        Route::get("/activateUser/{idMember}/{backTarget}",'UserActionController@activateMember');

    });

    Route::group(['prefix'=> 'public'], function () {

        Route::get("/",'UserActionController@showVerifiedPublicMember');

        Route::get("/waitingVerification",'UserActionController@showPublicWaitingVerification');

        Route::get("/resendVerificationEmail/{idMember}",'UserActionController@resendVerificationEmail');

        Route::get("/removeUnverifiedUser/{idMember}",'UserActionController@removeUnverifiedUser');

        Route::group(['prefix'=> 'editPublicUser'], function () {

            Route::get("/",'UserController@loadDefaultUserDashboard');

            Route::get("/{idMember}",'UserActionController@showEditPublicUserDataForm');

            Route::post("/updateMember/{idMember}",'UserActionController@doUpdatePublicMember');
        });

        Route::get("/suspendUser/{idMember}/{backTarget}",'UserActionController@suspendMember');

        Route::get("/activateUser/{idMember}/{backTarget}",'UserActionController@activateMember');

        Route::post("/deleteUser/{idMember}/public",'UserActionController@doDeletePublicMember');

    });

});

Route::group(['prefix'=> 'manageOnlineCourse'], function () {

    Route::get("/",'UserController@loadDefaultUserDashboard');

    Route::group(['prefix'=> 'manageClassProgram'], function () {

        Route::get("/",'UserActionController@loadClassProgram');

        Route::group(['prefix'=> 'addOnlineProgram'], function () {

            Route::get("/",'UserActionController@addOnlineProgramForm');

            Route::post("/submit",'UserActionController@submitNewOnlineProgramForm');

        });

        Route::group(['prefix'=> 'editOnlineProgram'], function () {

            Route::get("/{idAvailableClass}",'UserActionController@editOnlineProgramForm');

            Route::post("/{idAvailableClass}/submit",'UserActionController@submitEditOnlineProgramForm');

        });

    });

    Route::group(['prefix'=> 'manageCourse'], function () {

        Route::get("/", 'UserActionController@loadCourse');

        Route::group(['prefix'=> 'addNewCourse'], function () {

            Route::get("/",'UserActionController@addCourseForm');

            Route::post("/submit",'UserActionController@submitNewCourseForm');

        });

    });

    Route::group(['prefix'=> 'availableClass'], function () {

        Route::get("/", 'UserActionController@loadAvailableClass');

        Route::group(['prefix'=> 'addNewOnlineClassForm'], function () {

            Route::get("/",'UserActionController@openNewClassForm');

            Route::post("/submit",'UserActionController@submitOpenNewClassForm');

        });

        Route::group(['prefix'=> 'manageOnlineClass'], function () {

            Route::get("/", 'UserActionController@loadAvailableClass');

            Route::group(['prefix'=> '{idCoursesClass}'], function () {

                Route::get("/",'UserActionController@showOnlineClassAdministratorDashboard');

                Route::group(['prefix'=> 'manageSession'], function () {

                    Route::get("/",'UserActionController@showOnlineClassAdministratorDashboard');

                    Route::group(['prefix'=> 'addNewTopicForm'], function () {

                        Route::get("/",'UserActionController@addNewOnlineClassTopic');

                        Route::post("/submit",'UserActionController@submitNewOnlineClassTopic');

                    });

                    Route::group(['prefix'=> '{idTopic}'], function () {

                        Route::get("/",'UserActionController@manageTopic');

                        Route::post("/submitNewSubTopic",'UserActionController@submitNewSubTopic');

                        Route::group(['prefix'=> '{idSubTopic}'], function () {

                            Route::get("/",'OnlineClassController@manageSubTopic');

                            Route::get("/{idMaterial}/previewMaterial",'OnlineClassController@previewMaterial');

                            Route::get("/{idMaterial}/deleteMaterial",'OnlineClassController@deleteMaterial');

                            Route::post("/submitMaterials",'OnlineClassController@submitMaterials');

                            Route::post("/submitArticle",'OnlineClassController@submitArticle');

                            Route::post("/submitPDF",'OnlineClassController@submitPDF');

                            Route::post("/submitPPT",'OnlineClassController@submitPPT');

                            Route::post("/submitVideo",'OnlineClassController@submitVideo');

                            Route::post("/submitFile",'OnlineClassController@submitFile');

                            Route::post("/submitExternal",'OnlineClassController@submitExternal');

                            Route::get("/delete",'OnlineClassController@deleteSubTopic');

                            Route::group(['prefix'=> 'manageAssignment'], function () {

                                Route::get("/",'OnlineClassController@manageSubTopic');

                                Route::post("/submitCreateAssignment",'OnlineClassController@submitCreateAssignment');

                                Route::group(['prefix'=> '{idAssignment}'], function () {

                                    Route::get("/",'OnlineClassController@manageSubTopic');

                                    Route::group(['prefix'=> '{typeAssignment}'], function () {

                                        Route::get("/",'OnlineClassController@manageSubTopic');

                                        Route::group(['prefix'=> 'editAssignment'], function () {

                                            Route::get("/",'OnlineClassController@editAssignment');

                                            Route::get("/deleteQuestion/{idQuestion}",'OnlineClassController@deleteQuestion');

                                            Route::post("/submitNewQuestion",'OnlineClassController@submitNewQuestion');

                                            Route::post("/submitEditQuestion",'OnlineClassController@submitEditQuestion');

                                            Route::post("/previewEditQuestion",'OnlineClassController@previewEditQuestion');
                                        });

                                        Route::get("/deleteAssignment",'OnlineClassController@deleteAssignment');

                                        Route::group(['prefix'=> 'evaluateAssignment'], function () {

                                            Route::get("/",'OnlineClassController@showCompletedStudents');

                                            Route::group(['prefix'=> '{idMember}'], function () {

                                                Route::get("/",'OnlineClassController@showCompletedStudentsAnswer');

                                                Route::post("/submitEvaluate",'OnlineClassController@submitEvaluation');

                                                Route::get("/finishEvaluation",'OnlineClassController@finishEvaluation');
                                            });
                                        });
                                    });
                                });
                            });

                            Route::group(['prefix'=> 'manageExam'], function () {

                                Route::get("/",'OnlineClassController@manageSubTopic');

                                Route::post("/submitCreateExam",'OnlineClassController@submitCreateExam');

                                Route::group(['prefix'=> '{idExam}'], function () {

                                    Route::get("/",'OnlineClassController@manageSubTopic');

                                    Route::group(['prefix'=> '{typeExam}'], function () {

                                        Route::get("/",'OnlineClassController@manageSubTopic');

                                        Route::group(['prefix'=> 'editExam'], function () {

                                            Route::get("/",'OnlineClassController@editExam');

                                            Route::get("/deleteQuestion/{idQuestion}",'OnlineClassController@deleteExamQuestion');

                                            Route::post("/submitNewQuestion",'OnlineClassController@submitNewExamQuestion');

                                            Route::post("/submitEditQuestion",'OnlineClassController@submitEditExamQuestion');

                                            Route::post("/previewEditQuestion",'OnlineClassController@previewEditExamQuestion');
                                        });

                                        Route::get("/deleteExam",'OnlineClassController@deleteExam');

                                        Route::group(['prefix'=> 'evaluateExam'], function () {

                                            Route::get("/",'OnlineClassController@showCompletedExamStudents');

                                            Route::group(['prefix'=> '{idMember}'], function () {

                                                Route::get("/",'OnlineClassController@showCompletedStudentsExamAnswer');

                                                Route::post("/submitEvaluate",'OnlineClassController@submitExamEvaluation');

                                                Route::get("/finishEvaluation",'OnlineClassController@finishExamEvaluation');
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });

                Route::group(['prefix'=> 'managePrivateMessage'], function () {
                    Route::get("/",'OnlineClassCommunicationController@showUserPrivateMessage');

                    Route::get("/composeNewPrivateMessage",'OnlineClassCommunicationController@composeNewPrivateMessage');

                    Route::group(['prefix'=> 'showMessage/{idPrivateMessage}'], function () {

                        Route::get("/",'OnlineClassCommunicationController@showPrivateMessage');

                        Route::post("/sendReplyPrivateMessage",'OnlineClassCommunicationController@sendReplyPrivateMessage');
                    });

                    Route::get("/deleteMessage/{idPrivateMessage}",'OnlineClassCommunicationController@deleteMessage');

                    Route::post("/submitNewPrivateMessage",'OnlineClassCommunicationController@submitNewPrivateMessage');

                });

                Route::group(['prefix'=> 'manageForum'], function () {
                    Route::get("/",'OnlineClassCommunicationController@showCourseForumList');

                    Route::get("/createNewThread",'OnlineClassCommunicationController@createNewThread');

                    Route::post("/submitNewThread",'OnlineClassCommunicationController@submitNewThread');

                    Route::group(['prefix'=> 'openForum'], function () {
                        Route::get("/",'OnlineClassCommunicationController@showCourseForumList');

                        Route::group(['prefix'=> '{idForum}'], function () {
                            Route::get("/",'OnlineClassCommunicationController@openForum');

                            Route::get("/replyThread",'OnlineClassCommunicationController@replyThreadDiscussion');

                            Route::get("/replyThreadWithQuote/{idForumMessage}",'OnlineClassCommunicationController@replyThreadDiscussionWithQuote');

                            Route::post("/submitReplyThread", 'OnlineClassCommunicationController@submitReplyThread');

                            Route::get("/closeThread",'OnlineClassCommunicationController@ownerForumCloseThread');

                            Route::get("/sendPrivateMessageToCreator",'OnlineClassCommunicationController@sendPrivateMessageToCreator');
                        });
                    });
                });
                Route::group(['prefix'=> 'viewEnrolledStudents'], function () {
                    Route::get("/",'OnlineClassController@showEnrolledStudents');
                    Route::get("/sendPrivateMessage/{idMember}",'OnlineClassCommunicationController@sendPrivateMessageToStudent');
                    Route::get("/showStudentDetailProgress/{idMember}",'OnlineClassController@showStudentDetailProgress');
                });
            });
        });
    });
});

Route::group(['prefix'=> 'myCourse'], function () {
    Route::get("/",'UserController@loadDefaultUserDashboard');

    Route::group(['prefix'=> 'enterClass'], function () {
        Route::get("/",'UserController@loadDefaultUserDashboard');

        Route::group(['prefix'=> '{idCoursesClass}'], function () {

            Route::get("/",'StudentController@showOnlineClassDefaultHome');

            Route::group(['prefix'=> 'enterSession'], function () {

                Route::get("/",'StudentController@showOnlineClassDefaultHome');

                Route::group(['prefix'=> '{idTopic}'], function () {

                    Route::get("/",'StudentController@showSession');

                    Route::group(['prefix'=> '{idSubTopic}'], function () {

                        Route::get("/",'StudentController@showSubTopic');

                        Route::get("/showMaterial/{idMaterial}",'StudentController@showMaterial');

                        Route::group(['prefix'=> 'enterAssignment'], function () {

                            Route::get("/{idAssignment}/{typeAssignment}",'StudentController@enterAssignment');

                            Route::post("/{idAssignment}/{typeAssignment}/{idQuestion}/submitAssignmentAnswer",'StudentController@submitAssignmentAnswer');

                            Route::get("/{idAssignment}/{typeAssignment}/completeAssignment",'StudentController@completeAssignment');
                        });

                        Route::group(['prefix'=> 'enterExam'], function () {

                            Route::get("/{idExam}/{typeExam}",'StudentController@enterExam');

                            Route::post("/{idExam}/{typeExam}/{idQuestion}/submitExamAnswer",'StudentController@submitExamAnswer');

                            Route::get("/{idExam}/{typeExam}/completeExam",'StudentController@completeExam');
                        });

                    });

                });

            });

            Route::get("/viewMyCourseReport",'StudentController@showCourseProgressReport');
        });

    });
});

Route::group(['prefix'=> 'courses'], function () {
    Route::get("/", 'CoursesController@showAvailableCourses');
});



