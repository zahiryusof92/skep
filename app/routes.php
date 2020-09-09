<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

//change COB
Route::get('/changeCOB/{id}', 'UserController@changeCOB');

//change language
Route::get('/changeLanguage/{lang}', 'UserController@changeLanguage');

//company name
Route::post('/getCompanyName', 'HomeController@getCompanyName');

//login
Route::get('/', 'UserController@login')->before('guest');
Route::get('/login', 'UserController@login')->before('guest');
Route::post('/loginAction', 'UserController@loginAction')->before('guest');
Route::get('/logout', 'UserController@logout')->before('authMember');

//register
Route::get('/register', 'UserController@register')->before('guest');
Route::post('/submitRegister', 'UserController@submitRegister')->before('guest');

//change password
Route::get('/changePassword', 'UserController@changePassword')->before('authMember');
Route::post('/checkPasswordProfile', 'UserController@checkPasswordProfile')->before('authMember');
Route::post('/submitChangePassword', 'UserController@submitChangePassword')->before('authMember');

//edit profile
Route::get('/editProfile', 'UserController@editProfile')->before('authMember');
Route::post('/submitEditProfile', 'UserController@submitEditProfile')->before('authMember');

//home
Route::get('/home', 'AdminController@home')->before('authMember');
Route::get('/getAGMRemainder', 'AdminController@getAGMRemainder')->before('authMember');
Route::get('/getNeverAGM', 'AdminController@getNeverAGM')->before('authMember');
Route::get('/getAGM12Months', 'AdminController@getAGM12Months')->before('authMember');
Route::get('/getAGM15Months', 'AdminController@getAGM15Months')->before('authMember');
Route::get('/getMemoHome', 'AdminController@getMemoHome')->before('authMember');
Route::post('/getMemoDetails', 'AdminController@getMemoDetails')->before('authMember');

Route::get('/getDesignationRemainder', 'AgmController@getDesignationRemainder')->before('authMember');

// --- COB Maintenance --- //
//file prefix
Route::get('/filePrefix', 'AdminController@filePrefix')->before('authMember');
Route::get('/addFilePrefix', 'AdminController@addFilePrefix')->before('authMember');
Route::post('/submitFilePrefix', 'AdminController@submitFilePrefix')->before('authMember');
Route::get('/getFilePrefix', 'AdminController@getFilePrefix')->before('authMember');
Route::post('/activeFilePrefix', 'AdminController@activeFilePrefix')->before('authMember');
Route::post('/inactiveFilePrefix', 'AdminController@inactiveFilePrefix')->before('authMember');
Route::get('/updateFilePrefix/{id}', 'AdminController@updateFilePrefix')->before('authMember');
Route::post('/submitUpdateFilePrefix', 'AdminController@submitUpdateFilePrefix')->before('authMember');
Route::post('/deleteFilePrefix/{id}', 'AdminController@deleteFilePrefix')->before('authMember');

//add file
Route::get('/addFile', 'AdminController@addFile')->before('authMember');
Route::post('/submitFile', 'AdminController@submitFile')->before('authMember');

// import Files
Route::post('/importCOBFile', 'ImportController@importCOBFile')->before('authMember');

// file list
Route::get('/fileList', 'AdminController@fileList')->before('authMember');
Route::get('/getFileList', 'AdminController@getFileList')->before('authMember');

// file list before VP
Route::get('/fileListBeforeVP', 'AdminController@fileListBeforeVP')->before('authMember');
Route::get('/getFileListBeforeVP', 'AdminController@getFileListBeforeVP')->before('authMember');

Route::post('/activeFileList', 'AdminController@activeFileList')->before('authMember');
Route::post('/inactiveFileList', 'AdminController@inactiveFileList')->before('authMember');
Route::post('/deleteFileList', 'AdminController@deleteFileList')->before('authMember');

Route::post('/updateFileNo', 'AdminController@updateFileNo')->before('authMember');

//house scheme
Route::get('/view/house/{id}', 'AdminController@viewHouse')->before('authMember');
Route::get('/update/house/{id}', 'AdminController@house')->before('authMember');
Route::post('/submitUpdateHouseScheme', 'AdminController@submitUpdateHouseScheme')->before('authMember');

//strata
Route::get('/view/strata/{id}', 'AdminController@viewStrata')->before('authMember');
Route::get('/update/strata/{id}', 'AdminController@strata')->before('authMember');
Route::post('/submitUpdateStrata', 'AdminController@submitUpdateStrata')->before('authMember');
Route::post('uploadStrataFile', 'FileController@uploadStrataFile');
Route::post('/findDUN', 'AdminController@findDUN')->before('authMember');
Route::post('/findPark', 'AdminController@findPark')->before('authMember');
Route::post('/deleteStrataFile/{id}', 'AdminController@deleteStrataFile')->before('authMember');

//management
Route::get('/view/management/{id}', 'AdminController@viewManagement')->before('authMember');
Route::get('/update/management/{id}', 'AdminController@management')->before('authMember');
Route::post('/submitUpdateManagement', 'AdminController@submitUpdateManagement')->before('authMember');
Route::post('/deleteAuditReport/{id}', 'AdminController@deleteAuditReport')->before('authMember');
Route::post('/deleteLetterIntegrity/{id}', 'AdminController@deleteLetterIntegrity')->before('authMember');
Route::post('/deleteLetterBankruptcy/{id}', 'AdminController@deleteLetterBankruptcy')->before('authMember');

Route::post('/deleteAGMFile/{id}', 'AdminController@deleteAGMFile')->before('authMember');
Route::post('/deleteEGMFile/{id}', 'AdminController@deleteEGMFile')->before('authMember');
Route::post('/deleteMinutesMeetingFile/{id}', 'AdminController@deleteMinutesMeetingFile')->before('authMember');
Route::post('/deleteJMCFile/{id}', 'AdminController@deleteJMCFile')->before('authMember');
Route::post('/deleteICFile/{id}', 'AdminController@deleteICFile')->before('authMember');
Route::post('/deleteAttendanceFile/{id}', 'AdminController@deleteAttendanceFile')->before('authMember');
Route::post('/deleteAuditedFinancialFile/{id}', 'AdminController@deleteAuditedFinancialFile')->before('authMember');

//monitoring
Route::get('/view/monitoring/{id}', 'AdminController@viewMonitoring')->before('authMember');
Route::get('/update/monitoring/{id}', 'AdminController@monitoring')->before('authMember');
Route::post('/submitUpdateMonitoring', 'AdminController@submitUpdateMonitoring')->before('authMember');
Route::post('/addAGMDetails', 'AdminController@addAGMDetails')->before('authMember');
Route::post('/editAGMDetails', 'AdminController@editAGMDetails')->before('authMember');
Route::get('/getAGM/{file_id}', 'AdminController@getAGM')->before('authMember');
Route::get('/getAGMByMC/{file_id}', 'AdminController@getAGMByMC')->before('authMember');
Route::post('/getAGMDetails', 'AdminController@getAGMDetails')->before('authMember');
Route::post('/deleteAGMDetails/{id}', 'AdminController@deleteAGMDetails')->before('authMember');
Route::post('/addAJKDetails', 'AdminController@addAJKDetails')->before('authMember');
Route::post('/editAJKDetails', 'AdminController@editAJKDetails')->before('authMember');
Route::get('/getAJK/{file_id}', 'AdminController@getAJK')->before('authMember');
Route::post('/deleteAJKDetails/{id}', 'AdminController@deleteAJKDetails')->before('authMember');
Route::get('/editAGM/{id}', 'AdminController@editAGM')->before('authMember');
Route::post('/uploadAuditReportFile', 'FileController@uploadAuditReportFile');
Route::post('/uploadAuditReportFileEdit', 'FileController@uploadAuditReportFileEdit');
Route::post('/uploadLetterIntegrity', 'FileController@uploadLetterIntegrity');
Route::post('/uploadLetterIntegrityEdit', 'FileController@uploadLetterIntegrityEdit');
Route::post('/uploadLetterBankruptcy', 'FileController@uploadLetterBankruptcy');
Route::post('/uploadLetterBankruptcyEdit', 'FileController@uploadLetterBankruptcyEdit');

Route::post('/uploadAGMFile', 'FileController@uploadAGMFile');
Route::post('/uploadEGMFile', 'FileController@uploadEGMFile');
Route::post('/uploadMinutesMeetingFile', 'FileController@uploadMinutesMeetingFile');
Route::post('/uploadJMCFile', 'FileController@uploadJMCFile');
Route::post('/uploadICFile', 'FileController@uploadICFile');
Route::post('/uploadAttendanceFile', 'FileController@uploadAttendanceFile');
Route::post('/uploadAuditedFinancialFile', 'FileController@uploadAuditedFinancialFile');

// Sept 2020
Route::post('/uploadNoticeAgmEgm', 'FileController@uploadNoticeAgmEgm');
Route::post('/deleteNoticeAgmEgm/{id}', 'AdminController@deleteNoticeAgmEgm')->before('authMember');

Route::post('/uploadMinutesAgmEgm', 'FileController@uploadMinutesAgmEgm');
Route::post('/deleteMinutesAgmEgm/{id}', 'AdminController@deleteMinutesAgmEgm')->before('authMember');

Route::post('/uploadMinutesAjk', 'FileController@uploadMinutesAjk');
Route::post('/deleteMinutesAjk/{id}', 'AdminController@deleteMinutesAjk')->before('authMember');

Route::post('/uploadEligibleVote', 'FileController@uploadEligibleVote');
Route::post('/deleteEligibleVote/{id}', 'AdminController@deleteEligibleVote')->before('authMember');

Route::post('/uploadAttendMeeting', 'FileController@uploadAttendMeeting');
Route::post('/deleteAttendMeeting/{id}', 'AdminController@deleteAttendMeeting')->before('authMember');

Route::post('/uploadProksi', 'FileController@uploadProksi');
Route::post('/deleteProksi/{id}', 'AdminController@deleteProksi')->before('authMember');

Route::post('/uploadAjkInfo', 'FileController@uploadAjkInfo');
Route::post('/deleteAjkInfo/{id}', 'AdminController@deleteAjkInfo')->before('authMember');

Route::post('/uploadIc', 'FileController@uploadIc');
Route::post('/deleteIc/{id}', 'AdminController@deleteIc')->before('authMember');

Route::post('/uploadPurchaseAggrement', 'FileController@uploadPurchaseAggrement');
Route::post('/deletePurchaseAggrement/{id}', 'AdminController@deletePurchaseAggrement')->before('authMember');

Route::post('/uploadStrataTitle', 'FileController@uploadStrataTitle');
Route::post('/deleteStrataTitle/{id}', 'AdminController@deleteStrataTitle')->before('authMember');

Route::post('/uploadMaintenanceStatement', 'FileController@uploadMaintenanceStatement');
Route::post('/deleteMaintenanceStatement/{id}', 'AdminController@deleteMaintenanceStatement')->before('authMember');

Route::post('/uploadIntegrityPledge', 'FileController@uploadIntegrityPledge');
Route::post('/deleteIntegrityPledge/{id}', 'AdminController@deleteIntegrityPledge')->before('authMember');

Route::post('/uploadReportAuditedFinancial', 'FileController@uploadReportAuditedFinancial');
Route::post('/deleteReportAuditedFinancial/{id}', 'AdminController@deleteReportAuditedFinancial')->before('authMember');

Route::post('/uploadHouseRules', 'FileController@uploadHouseRules');
Route::post('/deleteHouseRules/{id}', 'AdminController@deleteHouseRules')->before('authMember');

//others
Route::get('/view/others/{id}', 'AdminController@viewOthers')->before('authMember');
Route::get('/updateFile/others/{id}', 'AdminController@others')->before('authMember');
Route::post('/submitUpdateOtherDetails', 'AdminController@submitUpdateOtherDetails')->before('authMember');
Route::post('/uploadOthersImage', 'ImageController@uploadOthersImage');
Route::post('/deleteImageOthers/{id}', 'AdminController@deleteImageOthers')->before('authMember');
Route::get('/getHousingScheme/{file_id}', 'AdminController@getHousingScheme')->before('authMember');
Route::post('/submitAddHousingScheme', 'AdminController@submitAddHousingScheme')->before('authMember');
Route::post('/deleteHousingScheme', 'AdminController@deleteHousingScheme')->before('authMember');

//scoring
Route::get('/view/scoring/{id}', 'AdminController@viewScoring')->before('authMember');
Route::get('/update/scoring/{id}', 'AdminController@scoring')->before('authMember');
Route::post('/addScoring', 'AdminController@addScoring')->before('authMember');
Route::post('/editScoring', 'AdminController@editScoring')->before('authMember');
Route::get('/getScoring/{id}', 'AdminController@getScoring')->before('authMember');
Route::post('/deleteScoring/{id}', 'AdminController@deleteScoring')->before('authMember');

//buyer
Route::get('/view/buyer/{id}', 'AdminController@viewBuyer')->before('authMember');
Route::get('/update/buyer/{id}', 'AdminController@buyer')->before('authMember');
Route::get('/update/addBuyer/{id}', 'AdminController@addBuyer')->before('authMember');
Route::get('/update/editBuyer/{id}', 'AdminController@editBuyer')->before('authMember');
Route::post('/submitBuyer', 'AdminController@submitBuyer')->before('authMember');
Route::post('/submitEditBuyer', 'AdminController@submitEditBuyer')->before('authMember');
Route::get('/getBuyerList/{id}', 'AdminController@getBuyerList')->before('authMember');
Route::post('/deleteBuyer', 'AdminController@deleteBuyer')->before('authMember');

// import buyer
Route::post('/importBuyer', 'ImportController@importBuyer')->before('authMember');

// import tenant
Route::post('/importTenant', 'ImportController@importTenant')->before('authMember');

//document
Route::get('/update/document/{id}', 'AdminController@document')->before('authMember');
Route::get('/getDocument/{id}', 'AdminController@getDocument')->before('authMember');
Route::get('/update/addDocument/{id}', 'AdminController@addDocument')->before('authMember');
Route::post('/submitAddDocument', 'AdminController@submitAddDocument')->before('authMember');
Route::get('/update/editDocument/{id}', 'AdminController@editDocument')->before('authMember');
Route::post('/submitEditDocument', 'AdminController@submitEditDocument')->before('authMember');
Route::post('/deleteDocument/{id}', 'AdminController@deleteDocument')->before('authMember');
Route::post('/deleteDocumentFile', 'AdminController@deleteDocumentFile')->before('authMember');
Route::post('/uploadDocumentFile', 'FileController@uploadDocumentFile')->before('authMember');

//upload csv
Route::get('/update/importBuyer/{id}', 'AdminController@importBuyer')->before('authMember');
Route::post('/uploadBuyerCSVAction/{id}', 'FileController@uploadBuyerCSVAction')->before('authMember');
Route::post('/submitUploadBuyer/{id}', 'AdminController@submitUploadBuyer')->before('authMember');

//file approval
Route::get('/approval/{id}', 'AdminController@fileApproval')->before('authMember');
Route::post('/submitApproval', 'AdminController@submitFileApproval')->before('authMember');

// --- Administration --- //
//company

Route::get('/company', 'AdminController@company')->before('authMember');
Route::get('/getCompany', 'AdminController@getCompany')->before('authMember');
Route::get('/addCompany', 'AdminController@addCompany')->before('authMember');
Route::post('/submitAddCompany', 'AdminController@submitAddCompany')->before('authMember');
Route::get('/editCompany/{id}', 'AdminController@editCompany')->before('authMember');
Route::post('/submitEditCompany', 'AdminController@submitEditCompany')->before('authMember');
Route::post('/activeCompany', 'AdminController@activeCompany')->before('authMember');
Route::post('/inactiveCompany', 'AdminController@inactiveCompany')->before('authMember');
Route::post('/deleteCompany', 'AdminController@deleteCompany')->before('authMember');

//upload logo
Route::post('/logoImage', 'ImageController@logoImage');

//upload nav image
Route::post('/navbarImage', 'ImageController@navbarImage');

//access group
Route::get('/accessGroups', 'AdminController@accessGroups')->before('authMember');
Route::get('/addAccessGroup', 'AdminController@addAccessGroup')->before('authMember');
Route::post('/submitAccessGroup', 'AdminController@submitAccessGroup')->before('authMember');
Route::get('/getAccessGroups', 'AdminController@getAccessGroups')->before('authMember');
Route::post('/activeAccessGroup', 'AdminController@activeAccessGroup')->before('authMember');
Route::post('/inactiveAccessGroup', 'AdminController@inactiveAccessGroup')->before('authMember');
Route::get('/updateAccessGroup/{id}', 'AdminController@updateAccessGroup')->before('authMember');
Route::post('/submitUpdateAccessGroup', 'AdminController@submitUpdateAccessGroup')->before('authMember');
Route::post('/deleteAccessGroup/{id}', 'AdminController@deleteAccessGroup')->before('authMember');

//user
Route::get('/user', 'AdminController@user')->before('authMember');
Route::get('/addUser', 'AdminController@addUser')->before('authMember');
Route::post('/submitUser', 'AdminController@submitUser')->before('authMember');
Route::get('/getUser', 'AdminController@getUser')->before('authMember');
Route::get('/getUserDetails/{id}', 'AdminController@getUserDetails')->before('authMember');
Route::post('/submitApprovedUser', 'AdminController@submitApprovedUser')->before('authMember');
Route::post('/activeUser', 'AdminController@activeUser')->before('authMember');
Route::post('/inactiveUser', 'AdminController@inactiveUser')->before('authMember');
Route::get('/updateUser/{id}', 'AdminController@updateUser')->before('authMember');
Route::post('/submitUpdateUser', 'AdminController@submitUpdateUser')->before('authMember');
Route::post('/deleteUser/{id}', 'AdminController@deleteUser')->before('authMember');
Route::post('/findFile', 'AdminController@findFile')->before('authMember');

//memo
Route::get('/memo', 'AdminController@memo')->before('authMember');
Route::get('/addMemo', 'AdminController@addMemo')->before('authMember');
Route::post('/submitMemo', 'AdminController@submitMemo')->before('authMember');
Route::get('/getMemo', 'AdminController@getMemo')->before('authMember');
Route::post('/activeMemo', 'AdminController@activeMemo')->before('authMember');
Route::post('/inactiveMemo', 'AdminController@inactiveMemo')->before('authMember');
Route::get('/updateMemo/{id}', 'AdminController@updateMemo')->before('authMember');
Route::post('/submitUpdateMemo', 'AdminController@submitUpdateMemo')->before('authMember');
Route::post('/deleteMemo/{id}', 'AdminController@deleteMemo')->before('authMember');

//rating
Route::get('/rating', 'AdminController@rating')->before('authMember');
Route::get('/getRating', 'AdminController@getRating')->before('authMember');
Route::post('/activeRating', 'AdminController@activeRating')->before('authMember');
Route::post('/inactiveRating', 'AdminController@inactiveRating')->before('authMember');
Route::get('/addRating', 'AdminController@addRating')->before('authMember');
Route::post('/submitAddRating', 'AdminController@submitAddRating')->before('authMember');
Route::get('/updateRating/{id}', 'AdminController@updateRating')->before('authMember');
Route::post('/submitUpdateRating', 'AdminController@submitUpdateRating')->before('authMember');
Route::post('/deleteRating/{id}', 'AdminController@deleteRating')->before('authMember');

//form
Route::get('/form', 'AdminController@form')->before('authMember');
Route::get('/getForm', 'AdminController@getForm')->before('authMember');
Route::post('/activeForm', 'AdminController@activeForm')->before('authMember');
Route::post('/inactiveForm', 'AdminController@inactiveForm')->before('authMember');
Route::get('/addForm', 'AdminController@addForm')->before('authMember');
Route::post('/submitAddForm', 'AdminController@submitAddForm')->before('authMember');
Route::get('/updateForm/{id}', 'AdminController@updateForm')->before('authMember');
Route::post('/submitUpdateForm', 'AdminController@submitUpdateForm')->before('authMember');
Route::post('/deleteForm/{id}', 'AdminController@deleteForm')->before('authMember');
Route::post('/deleteFormFile', 'AdminController@deleteFormFile');
Route::post('/uploadFormFile', 'FileController@uploadFormFile');

########################## AGM Submission ##########################

Route::post('/getFileListByCOB', 'AgmController@getFileListByCOB');

//AGM Design Submission
Route::get('/AJK', 'AgmController@AJK')->before('authMember');
Route::get('/getAJK', 'AgmController@getAJK')->before('authMember');
Route::get('/addAJK', 'AgmController@addAJK')->before('authMember');
Route::post('/submitAddAJK', 'AgmController@submitAddAJK')->before('authMember');
Route::get('/editAJK/{id}', 'AgmController@editAJK')->before('authMember');
Route::post('/submitEditAJK', 'AgmController@submitEditAJK')->before('authMember');
Route::post('/deleteAJK', 'AgmController@deleteAJK')->before('authMember');

//Purchaser Submission
Route::get('/purchaser', 'AgmController@purchaser')->before('authMember');
Route::post('/getPurchaser', 'AgmController@getPurchaser')->before('authMember');
Route::get('/addPurchaser', 'AgmController@addPurchaser')->before('authMember');
Route::post('/submitPurchaser', 'AgmController@submitPurchaser')->before('authMember');
Route::get('/editPurchaser/{id}', 'AgmController@editPurchaser')->before('authMember');
Route::post('/submitEditPurchaser', 'AgmController@submitEditPurchaser')->before('authMember');
Route::post('/deletePurchaser', 'AgmController@deletePurchaser')->before('authMember');
Route::get('/importPurchaser', 'AgmController@importPurchaser')->before('authMember');
Route::post('/uploadPurchaserCSVAction', 'FileController@uploadPurchaserCSVAction')->before('authMember');
Route::post('/submitUploadPurchaser', 'AgmController@submitUploadPurchaser')->before('authMember');

//Tenant Submission
Route::get('/tenant', 'AgmController@tenant')->before('authMember');
Route::post('/getTenant', 'AgmController@getTenant')->before('authMember');
Route::get('/addTenant', 'AgmController@addTenant')->before('authMember');
Route::post('/submitTenant', 'AgmController@submitTenant')->before('authMember');
Route::get('/editTenant/{id}', 'AgmController@editTenant')->before('authMember');
Route::post('/submitEditTenant', 'AgmController@submitEditTenant')->before('authMember');
Route::post('/deleteTenant', 'AgmController@deleteTenant')->before('authMember');
Route::get('/importTenant', 'AgmController@importTenant')->before('authMember');
Route::post('/uploadTenantCSVAction', 'FileController@uploadTenantCSVAction')->before('authMember');
Route::post('/submitUploadTenant', 'AgmController@submitUploadTenant')->before('authMember');

// upload minutes
Route::get('/minutes', 'AgmController@minutes')->before('authMember');
Route::get('/getMinutes', 'AgmController@getMinutes')->before('authMember');
Route::get('/addMinutes', 'AgmController@addMinutes')->before('authMember');
Route::post('/submitAddMinutes', 'AgmController@submitAddMinutes')->before('authMember');
Route::get('/editMinutes/{id}', 'AgmController@editMinutes')->before('authMember');
Route::post('/submitEditMinutes', 'AgmController@submitEditMinutes')->before('authMember');
Route::post('/getMinuteDetails', 'AgmController@getMinuteDetails')->before('authMember');
Route::post('/deleteMinutes', 'AgmController@deleteMinutes')->before('authMember');

//document
Route::get('/document', 'AgmController@document')->before('authMember');
Route::get('/getDocument', 'AgmController@getDocument')->before('authMember');
Route::get('/addDocument', 'AgmController@addDocument')->before('authMember');
Route::post('/submitAddDocument', 'AgmController@submitAddDocument')->before('authMember');
Route::get('/updateDocument/{id}', 'AgmController@updateDocument')->before('authMember');
Route::post('/submitUpdateDocument', 'AgmController@submitUpdateDocument')->before('authMember');
Route::post('/deleteDocument/{id}', 'AgmController@deleteDocument')->before('authMember');
Route::post('/deleteDocumentFile', 'AgmController@deleteDocumentFile')->before('authMember');
Route::post('/uploadDocumentFile', 'FileController@uploadDocumentFile')->before('authMember');

########################## Master Setup ##########################
//area
Route::get('/area', 'SettingController@area')->before('authMember');
Route::get('/addArea', 'SettingController@addArea')->before('authMember');
Route::post('/submitArea', 'SettingController@submitArea')->before('authMember');
Route::get('/getArea', 'SettingController@getArea')->before('authMember');
Route::post('/activeArea', 'SettingController@activeArea')->before('authMember');
Route::post('/inactiveArea', 'SettingController@inactiveArea')->before('authMember');
Route::get('/updateArea/{id}', 'SettingController@updateArea')->before('authMember');
Route::post('/submitUpdateArea', 'SettingController@submitUpdateArea')->before('authMember');
Route::post('/deleteArea/{id}', 'SettingController@deleteArea')->before('authMember');

//city
Route::get('/city', 'SettingController@city')->before('authMember');
Route::get('/addCity', 'SettingController@addCity')->before('authMember');
Route::post('/submitCity', 'SettingController@submitCity')->before('authMember');
Route::get('/getCity', 'SettingController@getCity')->before('authMember');
Route::post('/activeCity', 'SettingController@activeCity')->before('authMember');
Route::post('/inactiveCity', 'SettingController@inactiveCity')->before('authMember');
Route::get('/updateCity/{id}', 'SettingController@updateCity')->before('authMember');
Route::post('/submitUpdateCity', 'SettingController@submitUpdateCity')->before('authMember');
Route::post('/deleteCity/{id}', 'SettingController@deleteCity')->before('authMember');

//country
Route::get('/country', 'SettingController@country')->before('authMember');
Route::get('/addCountry', 'SettingController@addCountry')->before('authMember');
Route::post('/submitCountry', 'SettingController@submitCountry')->before('authMember');
Route::get('/getCountry', 'SettingController@getCountry')->before('authMember');
Route::post('/activeCountry', 'SettingController@activeCountry')->before('authMember');
Route::post('/inactiveCountry', 'SettingController@inactiveCountry')->before('authMember');
Route::get('/updateCountry/{id}', 'SettingController@updateCountry')->before('authMember');
Route::post('/submitUpdateCountry', 'SettingController@submitUpdateCountry')->before('authMember');
Route::post('/deleteCountry/{id}', 'SettingController@deleteCountry')->before('authMember');

//state
Route::get('/state', 'SettingController@state')->before('authMember');
Route::get('/addState', 'SettingController@addState')->before('authMember');
Route::post('/submitState', 'SettingController@submitState')->before('authMember');
Route::get('/getState', 'SettingController@getState')->before('authMember');
Route::post('/activeState', 'SettingController@activeState')->before('authMember');
Route::post('/inactiveState', 'SettingController@inactiveState')->before('authMember');
Route::get('/updateState/{id}', 'SettingController@updateState')->before('authMember');
Route::post('/submitUpdateState', 'SettingController@submitUpdateState')->before('authMember');
Route::post('/deleteState/{id}', 'SettingController@deleteState')->before('authMember');

//Document Type
Route::get('/documenttype', 'SettingController@documenttype')->before('authMember');
Route::get('/addDocumenttype', 'SettingController@addDocumenttype')->before('authMember');
Route::post('/submitDocumenttype', 'SettingController@submitDocumenttype')->before('authMember');
Route::get('/getDocumenttype', 'SettingController@getDocumenttype')->before('authMember');
Route::post('/activeDocumenttype', 'SettingController@activeDocumenttype')->before('authMember');
Route::post('/inactiveDocumenttype', 'SettingController@inactiveDocumenttype')->before('authMember');
Route::get('/updateDocumenttype/{id}', 'SettingController@updateDocumenttype')->before('authMember');
Route::post('/submitUpdateDocumenttype', 'SettingController@submitUpdateDocumenttype')->before('authMember');
Route::post('/deleteDocumenttype/{id}', 'SettingController@deleteDocumenttype')->before('authMember');

//Form Type
Route::get('/formtype', 'SettingController@formtype')->before('authMember');
Route::get('/addFormtype', 'SettingController@addFormtype')->before('authMember');
Route::post('/submitFormtype', 'SettingController@submitFormtype')->before('authMember');
Route::get('/getFormtype', 'SettingController@getFormtype')->before('authMember');
Route::post('/activeFormtype', 'SettingController@activeFormtype')->before('authMember');
Route::post('/inactiveFormtype', 'SettingController@inactiveFormtype')->before('authMember');
Route::get('/updateFormtype/{id}', 'SettingController@updateFormtype')->before('authMember');
Route::post('/submitUpdateFormtype', 'SettingController@submitUpdateFormtype')->before('authMember');
Route::post('/deleteFormtype/{id}', 'SettingController@deleteFormtype')->before('authMember');

//category
Route::get('/category', 'SettingController@category')->before('authMember');
Route::get('/addCategory', 'SettingController@addCategory')->before('authMember');
Route::post('/submitCategory', 'SettingController@submitCategory')->before('authMember');
Route::get('/getCategory', 'SettingController@getCategory')->before('authMember');
Route::post('/activeCategory', 'SettingController@activeCategory')->before('authMember');
Route::post('/inactiveCategory', 'SettingController@inactiveCategory')->before('authMember');
Route::get('/updateCategory/{id}', 'SettingController@updateCategory')->before('authMember');
Route::post('/submitUpdateCategory', 'SettingController@submitUpdateCategory')->before('authMember');
Route::post('/deleteCategory/{id}', 'SettingController@deleteCategory')->before('authMember');

//land
Route::get('/landTitle', 'SettingController@landTitle')->before('authMember');
Route::get('/addLandTitle', 'SettingController@addLandTitle')->before('authMember');
Route::post('/submitLandTitle', 'SettingController@submitLandTitle')->before('authMember');
Route::get('/getLandTitle', 'SettingController@getLandTitle')->before('authMember');
Route::post('/activeLandTitle', 'SettingController@activeLandTitle')->before('authMember');
Route::post('/inactiveLandTitle', 'SettingController@inactiveLandTitle')->before('authMember');
Route::get('/updateLandTitle/{id}', 'SettingController@updateLandTitle')->before('authMember');
Route::post('/submitUpdateLandTitle', 'SettingController@submitUpdateLandTitle')->before('authMember');
Route::post('/deleteLandTitle/{id}', 'SettingController@deleteLandTitle')->before('authMember');

//developer
Route::get('/developer', 'SettingController@developer')->before('authMember');
Route::get('/addDeveloper', 'SettingController@addDeveloper')->before('authMember');
Route::post('/submitDeveloper', 'SettingController@submitDeveloper')->before('authMember');
Route::get('/getDeveloper', 'SettingController@getDeveloper')->before('authMember');
Route::post('/activeDeveloper', 'SettingController@activeDeveloper')->before('authMember');
Route::post('/inactiveDeveloper', 'SettingController@inactiveDeveloper')->before('authMember');
Route::get('/updateDeveloper/{id}', 'SettingController@updateDeveloper')->before('authMember');
Route::post('/submitUpdateDeveloper', 'SettingController@submitUpdateDeveloper')->before('authMember');
Route::post('/deleteDeveloper/{id}', 'SettingController@deleteDeveloper')->before('authMember');

//developer
Route::get('/agent', 'SettingController@agent')->before('authMember');
Route::get('/addAgent', 'SettingController@addAgent')->before('authMember');
Route::post('/submitAgent', 'SettingController@submitAgent')->before('authMember');
Route::get('/getAgent', 'SettingController@getAgent')->before('authMember');
Route::post('/activeAgent', 'SettingController@activeAgent')->before('authMember');
Route::post('/inactiveAgent', 'SettingController@inactiveAgent')->before('authMember');
Route::get('/updateAgent/{id}', 'SettingController@updateAgent')->before('authMember');
Route::post('/submitUpdateAgent', 'SettingController@submitUpdateAgent')->before('authMember');
Route::post('/deleteAgent/{id}', 'SettingController@deleteAgent')->before('authMember');

//parliment
Route::get('/parliment', 'SettingController@parliment')->before('authMember');
Route::get('/addParliment', 'SettingController@addParliment')->before('authMember');
Route::post('/submitParliment', 'SettingController@submitParliment')->before('authMember');
Route::get('/getParliment', 'SettingController@getParliment')->before('authMember');
Route::post('/activeParliment', 'SettingController@activeParliment')->before('authMember');
Route::post('/inactiveParliment', 'SettingController@inactiveParliment')->before('authMember');
Route::get('/updateParliment/{id}', 'SettingController@updateParliment')->before('authMember');
Route::post('/submitUpdateParliment', 'SettingController@submitUpdateParliment')->before('authMember');
Route::post('/deleteParliment/{id}', 'SettingController@deleteParliment')->before('authMember');

//DUN
Route::get('/DUN', 'SettingController@dun')->before('authMember');
Route::get('/addDUN', 'SettingController@addDun')->before('authMember');
Route::post('/submitDUN', 'SettingController@submitDun')->before('authMember');
Route::get('/getDUN', 'SettingController@getDun')->before('authMember');
Route::post('/activeDUN', 'SettingController@activeDun')->before('authMember');
Route::post('/inactiveDUN', 'SettingController@inactiveDun')->before('authMember');
Route::get('/updateDUN/{id}', 'SettingController@updateDun')->before('authMember');
Route::post('/submitUpdateDUN', 'SettingController@submitUpdateDun')->before('authMember');
Route::post('/deleteDUN/{id}', 'SettingController@deleteDun')->before('authMember');

//Park
Route::get('/park', 'SettingController@park')->before('authMember');
Route::get('/addPark', 'SettingController@addPark')->before('authMember');
Route::post('/submitPark', 'SettingController@submitPark')->before('authMember');
Route::get('/getPark', 'SettingController@getPark')->before('authMember');
Route::post('/activePark', 'SettingController@activePark')->before('authMember');
Route::post('/inactivePark', 'SettingController@inactivePark')->before('authMember');
Route::get('/updatePark/{id}', 'SettingController@updatePark')->before('authMember');
Route::post('/submitUpdatePark', 'SettingController@submitUpdatePark')->before('authMember');
Route::post('/deletePark/{id}', 'SettingController@deletePark')->before('authMember');

//memo type
Route::get('/memoType', 'SettingController@memoType')->before('authMember');
Route::get('/addMemoType', 'SettingController@addMemoType')->before('authMember');
Route::post('/submitMemoType', 'SettingController@submitMemoType')->before('authMember');
Route::get('/getMemoType', 'SettingController@getMemoType')->before('authMember');
Route::post('/activeMemoType', 'SettingController@activeMemoType')->before('authMember');
Route::post('/inactiveMemoType', 'SettingController@inactiveMemoType')->before('authMember');
Route::get('/updateMemoType/{id}', 'SettingController@updateMemoType')->before('authMember');
Route::post('/submitUpdateMemoType', 'SettingController@submitUpdateMemoType')->before('authMember');
Route::post('/deleteMemoType/{id}', 'SettingController@deleteMemoType')->before('authMember');

//designation
Route::get('/designation', 'SettingController@designation')->before('authMember');
Route::get('/addDesignation', 'SettingController@addDesignation')->before('authMember');
Route::post('/submitDesignation', 'SettingController@submitDesignation')->before('authMember');
Route::get('/getDesignation', 'SettingController@getDesignation')->before('authMember');
Route::post('/activeDesignation', 'SettingController@activeDesignation')->before('authMember');
Route::post('/inactiveDesignation', 'SettingController@inactiveDesignation')->before('authMember');
Route::get('/updateDesignation/{id}', 'SettingController@updateDesignation')->before('authMember');
Route::post('/submitUpdateDesignation', 'SettingController@submitUpdateDesignation')->before('authMember');
Route::post('/deleteDesignation/{id}', 'SettingController@deleteDesignation')->before('authMember');

//unit measure
Route::get('/unitMeasure', 'SettingController@unitMeasure')->before('authMember');
Route::get('/addUnitMeasure', 'SettingController@addUnitMeasure')->before('authMember');
Route::post('/submitUnitMeasure', 'SettingController@submitUnitMeasure')->before('authMember');
Route::get('/getUnitMeasure', 'SettingController@getUnitMeasure')->before('authMember');
Route::post('/activeUnitMeasure', 'SettingController@activeUnitMeasure')->before('authMember');
Route::post('/inactiveUnitMeasure', 'SettingController@inactiveUnitMeasure')->before('authMember');
Route::get('/updateUnitMeasure/{id}', 'SettingController@updateUnitMeasure')->before('authMember');
Route::post('/submitUpdateUnitMeasure', 'SettingController@submitUpdateUnitMeasure')->before('authMember');
Route::post('/deleteUnitMeasure/{id}', 'SettingController@deleteUnitMeasure')->before('authMember');

//race
Route::get('/race', 'SettingController@race')->before('authMember');
Route::get('/addRace', 'SettingController@addRace')->before('authMember');
Route::post('/submitRace', 'SettingController@submitRace')->before('authMember');
Route::get('/getRace', 'SettingController@getRace')->before('authMember');
Route::post('/activeRace', 'SettingController@activeRace')->before('authMember');
Route::post('/inactiveRace', 'SettingController@inactiveRace')->before('authMember');
Route::get('/updateRace/{id}', 'SettingController@updateRace')->before('authMember');
Route::post('/submitUpdateRace', 'SettingController@submitUpdateRace')->before('authMember');
Route::post('/deleteRace/{id}', 'SettingController@deleteRace')->before('authMember');

//nationality
Route::get('/nationality', 'SettingController@nationality')->before('authMember');
Route::get('/addNationality', 'SettingController@addNationality')->before('authMember');
Route::post('/submitNationality', 'SettingController@submitNationality')->before('authMember');
Route::get('/getNationality', 'SettingController@getNationality')->before('authMember');
Route::post('/activeNationality', 'SettingController@activeNationality')->before('authMember');
Route::post('/inactiveNationality', 'SettingController@inactiveNationality')->before('authMember');
Route::get('/updateNationality/{id}', 'SettingController@updateNationality')->before('authMember');
Route::post('/submitUpdateNationality', 'SettingController@submitUpdateNationality')->before('authMember');
Route::post('/deleteNationality/{id}', 'SettingController@deleteNationality')->before('authMember');

// --- Reporting --- //
//audit trail
Route::get('/reporting/auditTrail', 'AdminController@auditTrail')->before('authMember');
Route::post('/getAuditTrail', 'AdminController@getAuditTrail')->before('authMember');

//file by location
Route::get('/reporting/fileByLocation', 'AdminController@fileByLocation')->before('authMember');
Route::get('/getFileByLocation', 'AdminController@getFileByLocation')->before('authMember');

//rating summary
Route::get('/reporting/ratingSummary', 'AdminController@ratingSummary')->before('authMember');

//management summary
Route::get('/reporting/managementSummary', 'AdminController@managementSummary')->before('authMember');

//cob file / management
Route::get('/reporting/cobFileManagement', 'AdminController@cobFileManagement')->before('authMember');


// strata profile
Route::get('/reporting/strataProfile', 'ReportController@strataProfile')->before('authMember');
Route::get('/reporting/getStrataProfile', 'ReportController@getStrataProfile')->before('authMember');
Route::get('/reporting/viewStrataProfile/{id}', 'ReportController@viewStrataProfile')->before('authMember');
Route::get('/print/strataProfile/{id}', 'PrintController@printStrataProfile')->before('authMember');

// owner tenant
Route::get('/reporting/ownerTenant', 'ReportController@ownerTenant')->before('authMember');
Route::get('/print/ownerTenant/file_id={id}', 'PrintController@printOwnerTenant')->before('authMember');

// -- COB -- //
Route::get('cob/get/{id}', 'CobController@get');
Route::get('cob/{id}/get-data', 'CobController@getData');
Route::get('cob/add/{id}', 'CobController@add');
Route::post('cob/store', 'CobController@store');
Route::get('cob/edit/{id}', 'CobController@edit');
Route::post('cob/update', 'CobController@update');

// --- Printing --- //
//audit trail
Route::post('/print/AuditTrail', 'PrintController@printAuditTrail')->before('authMember');

//file by location
Route::get('/print/FileByLocation', 'PrintController@printFileByLocation')->before('authMember');

//rating summary
Route::get('/print/RatingSummary', 'PrintController@printRatingSummary')->before('authMember');

//management summary
Route::get('/print/ManagementSummary', 'PrintController@printManagementSummary')->before('authMember');

//cob file / management
Route::get('/print/CobFileManagement', 'PrintController@printCobFileManagement')->before('authMember');


// FINANCE FILE LIST
Route::get('/financeList', 'FinanceController@financeList')->before('authMember');
Route::get('/getFinanceList', 'FinanceController@getFinanceList')->before('authMember');
Route::post('/inactiveFinanceList', 'FinanceController@inactiveFinanceList')->before('authMember');
Route::post('/activeFinanceList', 'FinanceController@activeFinanceList')->before('authMember');
Route::post('/deleteFinanceList', 'FinanceController@deleteFinanceList')->before('authMember');

Route::get('/addFinanceFileList', 'FinanceController@addFinanceFileList')->before('authMember');
Route::post('/submitAddFinanceFile', 'FinanceController@submitAddFinanceFile')->before('authMember');
Route::get('/editFinanceFileList/{id}', 'FinanceController@editFinanceFileList')->before('authMember');
Route::post('/updateFinanceFileList', 'FinanceController@updateFinanceFileList')->before('authMember');
Route::post('/updateFinanceFile', 'FinanceController@updateFinanceFile')->before('authMember');

Route::post('/updateFinanceCheck', 'FinanceController@updateFinanceCheck')->before('authMember');
Route::post('/updateFinanceFileAdmin', 'FinanceController@updateFinanceFileAdmin')->before('authMember');
Route::post('/updateFinanceFileStaff', 'FinanceController@updateFinanceFileStaff')->before('authMember');
Route::post('/updateFinanceFileContract', 'FinanceController@updateFinanceFileContract')->before('authMember');
Route::post('/updateFinanceFileVandal', 'FinanceController@updateFinanceFileVandal')->before('authMember');
Route::post('/updateFinanceFileRepair', 'FinanceController@updateFinanceFileRepair')->before('authMember');
Route::post('/updateFinanceFileIncome', 'FinanceController@updateFinanceFileIncome')->before('authMember');
Route::post('/updateFinanceFileUtility', 'FinanceController@updateFinanceFileUtility')->before('authMember');
Route::post('/updateFinanceFileReportSf', 'FinanceController@updateFinanceFileReportSf')->before('authMember');
Route::post('/updateFinanceFileReportMf', 'FinanceController@updateFinanceFileReportMf')->before('authMember');

// FINANCE SUPPORT
Route::get('/financeSupport', 'FinanceController@financeSupport')->before('authMember');
Route::get('/getFinanceSupportList', 'FinanceController@getFinanceSupportList')->before('authMember');
Route::get('/addFinanceSupport', 'FinanceController@addFinanceSupport')->before('authMember');
Route::post('/submitFinanceSupport', 'FinanceController@submitFinanceSupport')->before('authMember');
Route::get('/editFinanceSupport/{id}', 'FinanceController@editFinanceSupport')->before('authMember');
Route::post('/updateFinanceSupport', 'FinanceController@updateFinanceSupport')->before('authMember');
Route::post('/deleteFinanceSupport', 'FinanceController@deleteFinanceSupport')->before('authMember');

//form download
Route::get('/formDownload', 'AdminController@formDownload')->before('authMember');
Route::get('/getForm', 'AdminController@getForm')->before('authMember');

/*
 * RONALDO
 */
Route::get('/agmDesignSub', 'AgmController@agmDesignSub')->before('authMember');
Route::get('/addAgmDesignSub', 'AgmController@addAgmDesignSub')->before('authMember');
Route::post('/submitAgmDesignSub', 'AgmController@submitAgmDesignSub')->before('authMember');
Route::get('/getAgmDesignSub', 'AgmController@getAgmDesignSub')->before('authMember');
Route::post('/activeAgmDesignSub', 'AgmController@activeAgmDesignSub')->before('authMember');
Route::post('/inactiveAgmDesignSub', 'AgmController@inactiveAgmDesignSub')->before('authMember');
Route::get('/updateAgmDesignSub/{id}', 'AgmController@updateAgmDesignSub')->before('authMember');
Route::post('/submitUpdateAgmDesignSub', 'AgmController@submitUpdateAgmDesignSub')->before('authMember');
Route::post('/deleteAgmDesignSub/{id}', 'AgmController@deleteAgmDesignSub')->before('authMember');

//AGM Purchase Sub
Route::get('/agmPurchaseSub', 'AgmController@agmPurchaseSub')->before('authMember');
Route::get('/addAgmPurchaseSub', 'AgmController@addAgmPurchaseSub')->before('authMember');
Route::post('/submitAgmPurchaseSub', 'AgmController@submitAgmPurchaseSub')->before('authMember');
Route::get('/getAgmPurchaseSub', 'AgmController@getAgmPurchaseSub')->before('authMember');
Route::post('/activeAgmPurchaseSub', 'AgmController@activeAgmPurchaseSub')->before('authMember');
Route::post('/inactiveAgmPurchaseSub', 'AgmController@inactiveAgmPurchaseSub')->before('authMember');
Route::get('/updateAgmPurchaseSub/{id}', 'AgmController@updateAgmPurchaseSub')->before('authMember');
Route::post('/submitUpdateAgmPurchaseSub', 'AgmController@submitUpdateAgmPurchaseSub')->before('authMember');
Route::post('/deleteAgmPurchaseSub/{id}', 'AgmController@deleteAgmPurchaseSub')->before('authMember');


Route::get('/{cob}', 'UserController@login')->before('guest');
Route::get('/{cob}/login', 'UserController@login')->before('guest');
Route::get('/{cob}/logout', 'UserController@logout')->before('authMember');

//invalid route
Route::get('/{name?}', 'AdminController@showView')->before('authMember');
