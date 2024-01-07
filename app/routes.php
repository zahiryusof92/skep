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

 /**
 * Route for maintenance mode
 */
Route::get('/systemUp', function() {
    Artisan::call('up');

    return Redirect::to('/');
});
Route::get('/systemDown', function() {
    if (Auth::user()->isSuperadmin()) {
        Artisan::call('down');
    }

    return Redirect::to('/');
})->before('authMember');

/**
 * Route for testing
 */
Route::get('/test/testMail', 'TestController@testMail');


/**
 * Route for search
 */
Route::post('search', array('as' => 'search.index', 'uses' => 'SearchController@index'));

/*
 * LPHS REPORT START
 */

Route::get('/lphs/finance/{council}/{year}', 'LPHSController@finance');
Route::get('/lphs/developer/{council}', 'LPHSController@developer');
Route::get('/lphs/strata/{council}', 'LPHSController@strata');
Route::get('/lphs/jmb/{council}', 'LPHSController@jmb');
Route::get('/lphs/mc/{council}', 'LPHSController@mc');
Route::get('/lphs/agent/{council}', 'LPHSController@agent');
Route::get('/lphs/others/{council}', 'LPHSController@others');
Route::get('/lphs/agm/{council}', 'LPHSController@agm');
Route::get('/lphs/owner/{council}', 'LPHSController@owner');
Route::get('/lphs/tenant/{council}', 'LPHSController@tenant');
Route::get('/lphs/management/{council}', 'LPHSController@management');
Route::get('/lphs/createJMB/{council}', 'LPHSController@createJMB');
Route::get('/lphs/removeJMB/{council}', 'LPHSController@removeJMB');
Route::get('/lphs/updateJMBExpiration/{council}/{date}', 'LPHSController@updateJMBExpiration');
Route::get('/lphs/update/rating', 'LPHSController@updateRatingSummary');
Route::get('/lphs/odesiLife/{council}', 'LPHSController@odesiLife');
Route::get('/lphs/JMBMCSignIn/{council}', 'LPHSController@JMBMCSignIn');
Route::get('/lphs/updateByUser/{council}', 'LPHSController@updateByUser');
Route::get('/lphs/neverHasAGM/{council}', 'LPHSController@neverHasAGM');
Route::get('/lphs/due12MonthsAGM/{council}', 'LPHSController@due12MonthsAGM');
Route::get('/lphs/due15MonthsAGM/{council}', 'LPHSController@due15MonthsAGM');
Route::get('/lphs/insurance/{council}', 'LPHSController@insurance');
Route::get('/lphs/financeOutstanding/{council}', 'LPHSController@financeOutstanding');
Route::get('/lphs/strataByCategory/{council}', 'LPHSController@strataByCategory');
Route::get('/lphs/electricity/{council}', 'LPHSController@electricity');
Route::get('/lphs/uploadOCR/{council}', 'LPHSController@uploadOCR');
Route::get('/lphs/commercial/{council}', 'LPHSController@commercial');
Route::get('/lphs/extractData/{council}/{year}', 'LPHSController@extractData');
Route::get('/lphs/agmHasBeenApproved/{council}', 'LPHSController@agmHasBeenApproved');
Route::get('/lphs/exportOwner/{council}/{category}/{page}', 'LPHSController@exportOwner');
Route::get('/lphs/activeStrata/{council}', 'LPHSController@activeStrata');

/*
 * LPHS REPORT END
 */

/**
 * MBS
 */
Route::get('/mbs/resetJMB/', 'MBSController@resetJMB');
/**
 * END MBS
 */

/*
 * Clear cache
 */
Route::get('clear-cache', function () {
    Artisan::call('view:clear');

    return Redirect::to('/');
});

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
Route::get('/home', 'HomeController@home')->before('authMember');
Route::get('/home/getAGMRemainder', 'HomeController@getAGMRemainder')->before('authMember');
Route::get('/home/getNeverAGM', 'HomeController@getNeverAGM')->before('authMember');
Route::get('/home/getAGM12Months', 'HomeController@getAGM12Months')->before('authMember');
Route::get('/home/getAGM15Months', 'HomeController@getAGM15Months')->before('authMember');
Route::get('/home/getMemoHome', 'HomeController@getMemoHome')->before('authMember');
Route::post('/home/getMemoDetails', 'HomeController@getMemoDetails')->before('authMember');
Route::get('/home/getDesignationRemainder', 'HomeController@getDesignationRemainder')->before('authMember');
Route::get('/home/getInsuranceRemainder', 'HomeController@getInsuranceRemainder')->before('authMember');
Route::get('/home/getActiveMemoHome', 'HomeController@getActiveMemoHome')->before('authMember');

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
Route::get('cob/sync', 'CobSyncController@index')->before('authMember');

//draft files
Route::get('/draft/fileList', 'DraftController@fileList')->before('authMember');
Route::get('/draft/getFileList', 'DraftController@getFileList')->before('authMember');
Route::get('/draft/houseScheme/{id}', array('as' => 'cob.file.draft.house.edit', 'uses' => 'DraftController@houseScheme'))->before('authMember');
Route::post('/draft/submitHouseScheme', 'DraftController@submitHouseScheme')->before('authMember');
Route::get('/draft/strata/{id}', array('as' => 'cob.file.draft.strata.edit', 'uses' => 'DraftController@strata'))->before('authMember');
Route::post('/draft/submitStrata', 'DraftController@submitStrata')->before('authMember');
Route::get('/draft/management/{id}', array('as' => 'cob.file.draft.management.edit', 'uses' => 'DraftController@management'))->before('authMember');
Route::post('/draft/submitManagement', 'DraftController@submitManagement')->before('authMember');
Route::get('/draft/others/{id}', array('as' => 'cob.file.draft.others.edit', 'uses' => 'DraftController@others'))->before('authMember');
Route::post('/draft/submitOthers', 'DraftController@submitOthers')->before('authMember');
Route::post('/draft/deleteFile', 'DraftController@deleteFile')->before('authMember');

//add file
Route::get('/getLatestFile', 'AdminController@getLatestFile')->before('authMember');
Route::get('/addFile', 'AdminController@addFile')->before('authMember');
Route::post('/submitFile', 'AdminController@submitFile')->before('authMember');

// import Files
Route::post('/importCOBFile', 'ImportController@importCOBFile')->before('authMember');

// export Files
Route::get('/exportCOBFile', 'ExportController@exportCOBFile')->before('authMember');
Route::post('/submitExportCOBFile', 'ExportController@submitExportCOBFile')->before('authMember');

// file list
Route::get('/fileList', array('as' => 'cob.file.index', 'uses' => 'AdminController@fileList'))->before('authMember');
Route::get('/getFileList', 'AdminController@getFileList')->before('authMember');

// file list before VP
Route::get('/fileListBeforeVP', 'AdminController@fileListBeforeVP')->before('authMember');
Route::get('/getFileListBeforeVP', 'AdminController@getFileListBeforeVP')->before('authMember');

Route::post('/activeFileList', 'AdminController@activeFileList')->before('authMember');
Route::post('/inactiveFileList', 'AdminController@inactiveFileList')->before('authMember');
Route::post('/deleteFileList', 'AdminController@deleteFileList')->before('authMember');

Route::post('/updateFileNo', 'AdminController@updateFileNo')->before('authMember');

//fixed deposit
Route::get('/update/fixedDeposit/{id}', array('as' => 'cob.file.fixedDeposit.edit', 'uses' => 'FixedDepositController@edit'))->before('authMember');

//house scheme
Route::get('/view/house/{id}', 'AdminController@viewHouse')->before('authMember');
Route::get('/update/house/{id}', array('as' => 'cob.file.house.edit', 'uses' => 'AdminController@house'))->before('authMember');
Route::post('/submitUpdateHouseScheme', 'AdminController@submitUpdateHouseScheme')->before('authMember');

//strata
Route::get('/view/strata/{id}', 'AdminController@viewStrata')->before('authMember');
Route::get('/update/strata/{id}', array('as' => 'cob.file.strata.edit', 'uses' => 'AdminController@strata'))->before('authMember');
Route::post('/submitUpdateStrata', 'AdminController@submitUpdateStrata')->before('authMember');
Route::post('uploadStrataFile', 'FileController@uploadStrataFile');
Route::post('/findDUN', 'AdminController@findDUN')->before('authMember');
Route::post('/findPark', 'AdminController@findPark')->before('authMember');
Route::post('/deleteStrataFile/{id}', 'AdminController@deleteStrataFile')->before('authMember');

//management
Route::get('/view/management/{id}', 'AdminController@viewManagement')->before('authMember');
Route::get('/update/management/{id}', array('as' => 'cob.file.management.edit', 'uses' => 'AdminController@management'))->before('authMember');
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
Route::get('/update/monitoring/{id}',  array('as' => 'cob.file.monitoring.edit', 'uses' => 'AdminController@monitoring'))->before('authMember');
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

/**
 * OCR
 */
Route::post('/uploadOcr', 'FileController@uploadOcr');

/**
 * Endorsement Letter
 */
Route::post('/uploadEndorsementLetter', 'FileController@uploadEndorsementLetter');

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
Route::get('/updateFile/others/{id}', array('as' => 'cob.file.others.edit', 'uses' => 'AdminController@others'))->before('authMember');
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
Route::get('/update/buyer/{id}', array('as' => 'cob.file.buyer.edit', 'uses' => 'AdminController@buyer'))->before('authMember');
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
Route::get('/update/document/{id}', array('as' => 'cob.file.document.index', 'uses' => 'AdminController@document'))->before('authMember');
Route::get('/getDocument/{id}', 'AdminController@getDocument')->before('authMember');
Route::get('/update/addDocument/{id}', 'AdminController@addDocument')->before('authMember');
Route::post('/submitAddDocument', 'AdminController@submitAddDocument')->before('authMember');
Route::get('/update/editDocument/{id}', array('as' => 'cob.file.document.edit', 'uses' => 'AdminController@editDocument'))->before('authMember');
Route::post('/submitEditDocument', 'AdminController@submitEditDocument')->before('authMember');
Route::post('/deleteDocument/{id}', 'AdminController@deleteDocument')->before('authMember');
Route::post('/deleteDocumentFile', 'AdminController@deleteDocumentFile')->before('authMember');
Route::post('/uploadDocumentFile', 'FileController@uploadDocumentFile')->before('authMember');

//insurance
Route::get('/insurance/{id}', array('as' => 'cob.file.insurance.index', 'uses' => 'AdminController@insurance'))->before('authMember');
Route::get('/getInsurance/{id}', 'AdminController@getInsurance')->before('authMember');
Route::get('/addInsurance/{id}', 'AdminController@addInsurance')->before('authMember');
Route::post('/submitAddInsurance', 'AdminController@submitAddInsurance')->before('authMember');
Route::get('/updateInsurance/{id}/{file_id}', array('as' => 'cob.file.insurance.edit', 'uses' => 'AdminController@updateInsurance'))->before('authMember');
Route::post('/submitUpdateInsurance', 'AdminController@submitUpdateInsurance')->before('authMember');
Route::post('/deleteInsurance/{id}', 'AdminController@deleteInsurance')->before('authMember');
Route::post('/uploadInsuranceAttachment', 'FileController@uploadInsuranceAttachment')->before('authMember');
Route::post('/deleteInsuranceAttachment/{id}', 'AdminController@deleteInsuranceAttachment')->before('authMember');

//finance_support
Route::get('/financeSupport/{id}', array('as' => 'cob.file.finance_support.index', 'uses' => 'AdminController@financeSupport'))->before('authMember');
Route::get('/getFinanceSupport/{id}', 'AdminController@getFinanceSupport')->before('authMember');
Route::get('/addFinanceSupport/{id}', 'AdminController@addFinanceSupport')->before('authMember');
Route::post('/submitAddFinanceSupport', array('as' => 'cob.file.finance_support.store', 'uses' => 'AdminController@submitAddFinanceSupport'))->before('authMember');
Route::get('/updateFinanceSupport/{id}', array('as' => 'cob.file.finance_support.edit', 'uses' => 'AdminController@updateFinanceSupport'))->before('authMember');
Route::post('/submitUpdateFinanceSupport', 'AdminController@submitUpdateFinanceSupport')->before('authMember');
Route::post('/deleteFinanceSupport/{id}', 'AdminController@deleteFinanceSupport')->before('authMember');

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
Route::get('/AJK', array('as' => 'ajk.index', 'uses' => 'AgmController@AJK'))->before('authMember');
Route::get('/getAJK', 'AgmController@getAJK')->before('authMember');
Route::get('/addAJK', 'AgmController@addAJK')->before('authMember');
Route::post('/submitAddAJK', 'AgmController@submitAddAJK')->before('authMember');
Route::get('/editAJK/{id}', array('as' => 'ajk.edit', 'uses' => 'AgmController@editAJK'))->before('authMember');
Route::post('/submitEditAJK', 'AgmController@submitEditAJK')->before('authMember');
Route::post('/deleteAJK', 'AgmController@deleteAJK')->before('authMember');

//Purchaser Submission
Route::get('/purchaser', array('as' => 'purchaser.index', 'uses' => 'AgmController@purchaser'))->before('authMember');
Route::get('/getPurchaser', 'AgmController@getPurchaser')->before('authMember');
Route::get('/addPurchaser', 'AgmController@addPurchaser')->before('authMember');
Route::post('/submitPurchaser', 'AgmController@submitPurchaser')->before('authMember');
Route::get('/editPurchaser/{id}', array('as' => 'purchaser.edit', 'uses' => 'AgmController@editPurchaser'))->before('authMember');
Route::post('/submitEditPurchaser', 'AgmController@submitEditPurchaser')->before('authMember');
Route::post('/deletePurchaser', 'AgmController@deletePurchaser')->before('authMember');
Route::get('/importPurchaser', 'AgmController@importPurchaser')->before('authMember');
Route::post('/uploadPurchaserCSVAction', 'FileController@uploadPurchaserCSVAction')->before('authMember');
Route::post('/submitUploadPurchaser', 'AgmController@submitUploadPurchaser')->before('authMember');
Route::post('/report/purchaser', 'ReportController@purchaser')->before('authMember');
Route::post('/print/purchaser', 'PrintController@printPurchaser')->before('authMember');

//Tenant Submission
Route::get('/tenant', array('as' => 'tenant.index', 'uses' => 'AgmController@tenant'))->before('authMember');
Route::get('/getTenant', 'AgmController@getTenant')->before('authMember');
Route::get('/addTenant', 'AgmController@addTenant')->before('authMember');
Route::post('/submitTenant', 'AgmController@submitTenant')->before('authMember');
Route::get('/editTenant/{id}', array('as' => 'tenant.edit', 'uses' => 'AgmController@editTenant'))->before('authMember');
Route::post('/submitEditTenant', 'AgmController@submitEditTenant')->before('authMember');
Route::post('/deleteTenant', 'AgmController@deleteTenant')->before('authMember');
Route::get('/importTenant', 'AgmController@importTenant')->before('authMember');
Route::post('/uploadTenantCSVAction', 'FileController@uploadTenantCSVAction')->before('authMember');
Route::post('/submitUploadTenant', 'AgmController@submitUploadTenant')->before('authMember');
Route::post('/report/tenant', 'ReportController@tenant')->before('authMember');
Route::post('/print/tenant', 'PrintController@printTenant')->before('authMember');

// upload minutes
Route::get('/minutes', array('as' => 'minutes.index', 'uses' => 'AgmController@minutes'))->before('authMember');
Route::get('/getMinutes', 'AgmController@getMinutes')->before('authMember');
Route::get('/addMinutes', 'AgmController@addMinutes')->before('authMember');
Route::post('/submitAddMinutes', 'AgmController@submitAddMinutes')->before('authMember');
Route::get('/editMinutes/{id}', array('as' => 'minutes.edit', 'uses' => 'AgmController@editMinutes'))->before('authMember');
Route::post('/submitEditMinutes', 'AgmController@submitEditMinutes')->before('authMember');
Route::post('/getMinuteDetails', 'AgmController@getMinuteDetails')->before('authMember');
Route::post('/deleteMinutes', 'AgmController@deleteMinutes')->before('authMember');

//document
Route::get('/document', array('as' => 'document.index', 'uses' => 'AgmController@document'))->before('authMember');
Route::get('/getDocument', 'AgmController@getDocument')->before('authMember');
Route::get('/addDocument', 'AgmController@addDocument')->before('authMember');
Route::post('/submitAddDocument', 'AgmController@submitAddDocument')->before('authMember');
Route::get('/updateDocument/{id}', array('as' => 'document.edit', 'uses' => 'AgmController@updateDocument'))->before('authMember');
Route::post('/submitUpdateDocument', 'AgmController@submitUpdateDocument')->before('authMember');
Route::post('/deleteDocument/{id}', 'AgmController@deleteDocument')->before('authMember');
Route::post('/deleteDocumentFile', 'AgmController@deleteDocumentFile')->before('authMember');
Route::post('/uploadDocumentFile', 'FileController@uploadDocumentFile')->before('authMember');

//defect
Route::get('/defect', 'AdminController@defect')->before('authMember');
Route::get('/getDefect', 'AdminController@getDefect')->before('authMember');
Route::get('/addDefect', 'AdminController@addDefect')->before('authMember');
Route::post('/submitAddDefect', 'AdminController@submitAddDefect')->before('authMember');
Route::get('/updateDefect/{id}', 'AdminController@updateDefect')->before('authMember');
Route::post('/submitUpdateDefect', 'AdminController@submitUpdateDefect')->before('authMember');
Route::post('/deleteDefect/{id}', 'AdminController@deleteDefect')->before('authMember');
Route::post('/deleteDefectAttachment', 'AdminController@deleteDefectAttachment')->before('authMember');
Route::post('/uploadDefectAttachment', 'FileController@uploadDefectAttachment')->before('authMember');

########################## Master Setup ##########################

//postponeAGMReason
Route::resource('statusAGMReason', 'PostponeAGMReasonController');

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

//liquidator
Route::get('/liquidator', 'SettingController@liquidator')->before('authMember');
Route::get('/addLiquidator', 'SettingController@addLiquidator')->before('authMember');
Route::post('/submitLiquidator', 'SettingController@submitLiquidator')->before('authMember');
Route::get('/getLiquidator', 'SettingController@getLiquidator')->before('authMember');
Route::post('/activeLiquidator', 'SettingController@activeLiquidator')->before('authMember');
Route::post('/inactiveLiquidator', 'SettingController@inactiveLiquidator')->before('authMember');
Route::get('/updateLiquidator/{id}', 'SettingController@updateLiquidator')->before('authMember');
Route::post('/submitUpdateLiquidator', 'SettingController@submitUpdateLiquidator')->before('authMember');
Route::post('/deleteLiquidator/{id}', 'SettingController@deleteLiquidator')->before('authMember');

//agent
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
Route::post('/uploadMemoFile', array('as' => 'memo.fileUpload', 'uses' => 'FileController@uploadMemoFile'))->before('authMember');

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

//defect category
Route::get('/defectCategory', 'SettingController@defectCategory')->before('authMember');
Route::get('/addDefectCategory', 'SettingController@addDefectCategory')->before('authMember');
Route::post('/submitDefectCategory', 'SettingController@submitDefectCategory')->before('authMember');
Route::get('/getDefectCategory', 'SettingController@getDefectCategory')->before('authMember');
Route::post('/activeDefectCategory', 'SettingController@activeDefectCategory')->before('authMember');
Route::post('/inactiveDefectCategory', 'SettingController@inactiveDefectCategory')->before('authMember');
Route::get('/updateDefectCategory/{id}', 'SettingController@updateDefectCategory')->before('authMember');
Route::post('/submitUpdateDefectCategory', 'SettingController@submitUpdateDefectCategory')->before('authMember');
Route::post('/deleteDefectCategory/{id}', 'SettingController@deleteDefectCategory')->before('authMember');

//insurance provider
Route::get('/insuranceProvider', 'SettingController@insuranceProvider')->before('authMember');
Route::get('/addInsuranceProvider', 'SettingController@addInsuranceProvider')->before('authMember');
Route::post('/submitInsuranceProvider', 'SettingController@submitInsuranceProvider')->before('authMember');
Route::get('/getInsuranceProvider', 'SettingController@getInsuranceProvider')->before('authMember');
Route::post('/activeInsuranceProvider', 'SettingController@activeInsuranceProvider')->before('authMember');
Route::post('/inactiveInsuranceProvider', 'SettingController@inactiveInsuranceProvider')->before('authMember');
Route::get('/updateInsuranceProvider/{id}', 'SettingController@updateInsuranceProvider')->before('authMember');
Route::post('/submitUpdateInsuranceProvider', 'SettingController@submitUpdateInsuranceProvider')->before('authMember');
Route::post('/deleteInsuranceProvider/{id}', 'SettingController@deleteInsuranceProvider')->before('authMember');

// --- Reporting --- //
//audit trail
Route::get('/reporting/auditTrail', array('as' => 'reporting.log.index', 'uses' => 'ReportController@auditTrail'))->before('authMember');
Route::post('/export/auditTrail', array('as' => 'export.log', 'uses' => 'ExportController@auditTrail'))->before('authMember');

Route::get('/reporting/auditLogon', array('as' => 'reporting.logon.index', 'uses' => 'ReportController@auditLogon'))->before('authMember');
Route::get('/reporting/auditLogon/old', array('as' => 'reporting.logon.old.index', 'uses' => 'ReportController@auditLogonOld'))->before('authMember');

//file by location
Route::get('/reporting/fileByLocation', 'ReportController@fileByLocation')->before('authMember');
Route::get('/getFileByLocation', 'ReportController@getFileByLocation')->before('authMember');

//rating summary
Route::get('/reporting/ratingSummary', 'ReportController@ratingSummary')->before('authMember');

//management summary
Route::get('/reporting/managementSummary', 'ReportController@managementSummary')->before('authMember');


//cob file / management
Route::get('/reporting/cobFileManagement', 'ReportController@cobFileManagement')->before('authMember');

// strata profile
Route::get('/reporting/strataProfile', 'ReportController@strataProfile')->before('authMember');
Route::get('/reporting/getStrataProfile', 'ReportController@getStrataProfile')->before('authMember');
Route::get('/reporting/getStrataProfileAnalytic', 'ReportController@getStrataProfileAnalytic')->before('authMember');
Route::get('/reporting/viewStrataProfile/{id}', 'ReportController@viewStrataProfile')->before('authMember');
Route::get('/reporting/getStrataProfileFinance/{file_id}', array('as' => 'reporting.strataProfile.finance', 'uses' => 'ReportController@getStrataProfileFinance'))->before('authMember');
Route::get('/print/strataProfile/{id}', 'PrintController@printStrataProfile')->before('authMember');

// owner tenant
Route::get('/reporting/ownerTenant', 'ReportController@ownerTenant')->before('authMember');
Route::get('/print/ownerTenant/file_id={id}', 'PrintController@printOwnerTenant')->before('authMember');

// insurance
Route::get('/reporting/insurance', 'ReportController@insurance')->before('authMember');
Route::get('/print/insurance/file_id={id}', 'PrintController@printInsurance')->before('authMember');

// complaint
Route::get('/reporting/complaint', 'ReportController@complaint')->before('authMember');
Route::get('/print/complaint/file_id={id}', 'PrintController@printComplaint')->before('authMember');

// collection
Route::get('/reporting/collection', 'ReportController@collection')->before('authMember');
Route::get('/print/collection/file_id={id}', 'PrintController@printCollection')->before('authMember');

// council
Route::get('/reporting/council', 'ReportController@council')->before('authMember');
Route::get('/print/council/cob_id={id}', 'PrintController@printCouncil')->before('authMember');

// dun
Route::get('/reporting/dun', 'ReportController@dun')->before('authMember');
Route::get('/print/dun/cob_id={id}', 'PrintController@printDun')->before('authMember');

// parliment
Route::get('/reporting/parliment', 'ReportController@parliment')->before('authMember');
Route::get('/print/parliment/cob_id={id}', 'PrintController@printParliment')->before('authMember');

// vp
Route::get('/reporting/vp', 'ReportController@vp')->before('authMember');
Route::get('/print/vp', 'PrintController@printVp')->before('authMember');

// management list
Route::get('/reporting/management', 'ReportController@management')->before('authMember');
Route::post('/reporting/managementList', 'ReportController@managementList')->before('authMember');
Route::get('/reporting/getManagementList', 'ReportController@getManagementList')->before('authMember');
Route::post('/print/managementList', 'PrintController@printManagementList')->before('authMember');

// land title
Route::get('/reporting/landTitle', 'ReportController@landTitle')->before('authMember');
Route::get('/print/landTitle/{cob_id}/{land_title_id}', 'PrintController@printLandTitle')->before('authMember');

// -- COB -- //
Route::get('cob/get/{id}', 'CobController@get');
Route::get('cob/{id}/get-data', 'CobController@getData');
Route::get('cob/add/{id}', 'CobController@add');
Route::post('cob/store', 'CobController@store');
Route::get('cob/edit/{id}', 'CobController@edit');
Route::post('cob/update', 'CobController@update');

// --- Printing --- //
//audit trail
Route::post('/print/auditTrail', array('as' => 'print.log', 'uses' => 'PrintController@printAuditTrailNew'))->before('authMember');

//file by location
Route::get('/print/FileByLocation', 'PrintController@printFileByLocation')->before('authMember');

//rating summary
Route::get('/print/RatingSummary', 'PrintController@printRatingSummary')->before('authMember');

//management summary
Route::get('/print/ManagementSummary', 'PrintController@printManagementSummary')->before('authMember');

//cob file / management
Route::get('/print/CobFileManagement', 'PrintController@printCobFileManagement')->before('authMember');

// finance support
Route::post('/print/financeSupport', 'PrintController@financeSupport')->before('authMember');

// FINANCE FILE LIST
Route::get('/financeList', array('as' => 'finance_file.index', 'uses' => 'FinanceController@financeList'))->before('authMember');
Route::get('/getFinanceList', 'FinanceController@getFinanceList')->before('authMember');
Route::post('/inactiveFinanceList', 'FinanceController@inactiveFinanceList')->before('authMember');
Route::post('/activeFinanceList', 'FinanceController@activeFinanceList')->before('authMember');
Route::post('/deleteFinanceList', 'FinanceController@deleteFinanceList')->before('authMember');

// import Files
Route::post('/importFinanceFile', 'ImportController@importFinanceFile')->before('authMember');

Route::get('/addFinanceFileList', 'FinanceController@addFinanceFileList')->before('authMember');
Route::post('/submitAddFinanceFile', 'FinanceController@submitAddFinanceFile')->before('authMember');
Route::get('/editFinanceFileList/{id}', array('as' => 'finance_file.edit', 'uses' => 'FinanceController@editFinanceFileList'))->before('authMember');
Route::post('/updateFinanceFileList', 'FinanceController@updateFinanceFileList')->before('authMember');
Route::post('/updateFinanceFile', 'FinanceController@updateFinanceFile')->before('authMember');

Route::post('/updateFinanceFileCheck', 'FinanceController@updateFinanceFileCheck')->before('authMember');
Route::post('/updateFinanceFileSummary', 'FinanceController@updateFinanceFileSummary')->before('authMember');
Route::post('/updateFinanceFileAdmin', 'FinanceController@updateFinanceFileAdmin')->before('authMember');
Route::post('/updateFinanceFileStaff', 'FinanceController@updateFinanceFileStaff')->before('authMember');
Route::post('/updateFinanceFileContract', 'FinanceController@updateFinanceFileContract')->before('authMember');
Route::post('/updateFinanceFileVandal', 'FinanceController@updateFinanceFileVandal')->before('authMember');
Route::post('/updateFinanceFileRepair', 'FinanceController@updateFinanceFileRepair')->before('authMember');
Route::post('/updateFinanceFileIncome', 'FinanceController@updateFinanceFileIncome')->before('authMember');
Route::post('/updateFinanceFileUtility', 'FinanceController@updateFinanceFileUtility')->before('authMember');
Route::post('/updateFinanceFileReportSf', 'FinanceController@updateFinanceFileReportSf')->before('authMember');
Route::post('/updateFinanceFileReportMf', 'FinanceController@updateFinanceFileReportMf')->before('authMember');

//cob file / management
Route::get('/print/financeFile/{id}', 'PrintController@printFinanceFile')->before('authMember');

// FINANCE SUPPORT
Route::get('/financeSupport', array('as' => 'finance_support.index', 'uses' => 'FinanceController@financeSupport'))->before('authMember');
Route::get('/getFinanceSupportList', 'FinanceController@getFinanceSupportList')->before('authMember');
Route::get('/addFinanceSupport', 'FinanceController@addFinanceSupport')->before('authMember');
Route::post('/submitFinanceSupport', 'FinanceController@submitFinanceSupport')->before('authMember');
Route::get('/editFinanceSupport/{id}', array('as' => 'finance_support.edit', 'uses' => 'FinanceController@editFinanceSupport'))->before('authMember');
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


Route::group(array('before' => 'authMember'), function() {
    /*
     * Category Setup Start
     */
    Route::post('category/active', 'CategoryController@active');
    Route::post('category/inactive', 'CategoryController@inactive');
    Route::resource('category', 'CategoryController', ['except' => 'show']);
    /*
     * Category Setup End
     */

    /*
     * Conversion Rate Start
     */
    Route::resource('conversion', 'ConversionController', ['except' => 'create', 'show', 'destroy']);
    /*
     * Conversion Rate End
     */

    /*
     * Point Package Start
     */
    Route::post('pointPackage/active', 'PointPackageController@active');
    Route::post('pointPackage/inactive', 'PointPackageController@inactive');
    Route::resource('pointPackage', 'PointPackageController', ['except' => 'show']);
    /*
     * Point Package Rate End
     */


    /*
     * Directory Route Start
     */
    Route::post('/vendors/review', 'VendorController@review');
    Route::post('/vendors/project', 'VendorController@project');
    Route::post('/vendors/project/update', 'VendorController@updateProject');
    Route::post('/vendors/project/status', 'VendorController@status');
    Route::get('/vendors/project/destroy/{id}', 'VendorController@destroyProject');
    Route::resource('vendors', 'VendorController');
    Route::resource('propertyAgents', 'PropertyAgentController');
    /*
     * Directory Route End
     */

    /*
     * Summon Start
     */
    Route::post('summon/purchaser', 'SummonController@purchaser');
    Route::get('summon/create/{type}', ['as' => 'summon.create', 'uses' => 'SummonController@create']);
    Route::post('summon/orders', 'SummonController@orders');
    Route::get('summon/payment', 'SummonController@payment');
    Route::post('summon/submitPay', 'SummonController@submitPay');
    Route::post('summon/uploadPayment', 'SummonController@uploadPayment');
    Route::get('summon/councilSummonList', 'SummonController@councilSummonList');
    Route::get('summon/paid', 'SummonController@paidListing');
    Route::resource('summon', 'SummonController', ['except' => 'create']);
    /*
     * Summon End
     */

    /*
     * My Point Start
     */
    Route::get('myPoint/reload', 'MyPointController@reload');
    Route::post('myPoint/orders', 'MyPointController@orders');
    Route::get('myPoint/payment', 'MyPointController@payment');
    Route::post('myPoint/submitPay', 'MyPointController@submitPay');
    Route::resource('myPoint', 'MyPointController');
    /*
     * My Point End
     */

    Route::get('epks/approval', ['as' => 'epks.approval', 'uses' => 'EPKSController@index']);
    Route::get('epks/draft', ['as' => 'epks.draft', 'uses' => 'EPKSController@index']);
    Route::post('epks/fileUpload', ['as' => 'epks.fileUpload', 'uses' => 'EPKSController@fileUpload']);
    Route::post('epks/imageUpload', ['as' => 'epks.imageUpload', 'uses' => 'EPKSController@imageUpload']);
    Route::post('epks/submitConfirm/{id}', ['as' => 'epks.submitConfirm', 'uses' => 'EPKSController@submitConfirm']);
    Route::post('epks/submitByCOB/{id}', ['as' => 'epks.submitByCOB', 'uses' => 'EPKSController@submitByCOB']);
    Route::resource('epks', 'EPKSController');

    /**
     * AGM Postpone
     */
    Route::get('statusAGM/acknowldged', ['as' => 'statusAGM.approved', 'uses' => 'PostponeAGMController@approved']);
    Route::get('statusAGM/rejected', ['as' => 'statusAGM.rejected', 'uses' => 'PostponeAGMController@rejected']);
    Route::get('statusAGM/report', ['as' => 'statusAGM.report', 'uses' => 'PostponeAGMController@report']);
    Route::post('statusAGM/fileUpload', ['as' => 'statusAGM.fileUpload', 'uses' => 'PostponeAGMController@fileUpload']);
    Route::post('statusAGM/review', ['as' => 'statusAGM.review', 'uses' => 'PostponeAGMController@review']);
    Route::post('statusAGM/submitByCOB/{id}', ['as' => 'statusAGM.submitByCOB', 'uses' => 'PostponeAGMController@submitByCOB']);
    Route::post('statusAGM/approvalUpload', ['as' => 'statusAGM.approvalUpload', 'uses' => 'PostponeAGMController@approvalUpload']);
    Route::resource('statusAGM', 'PostponeAGMController'); 

    /**
     * DLP
     */
    Route::post('dlp/fileUpload', ['as' => 'dlp.fileUpload', 'uses' => 'DlpController@fileUpload']);
    Route::get('dlp/deposit', ['as' => 'dlp.deposit', 'uses' => 'DlpController@deposit']);
    Route::get('dlp/deposit/create', ['as' => 'dlp.deposit.create', 'uses' => 'DlpController@createDeposit']);    
    Route::post('dlp/deposit/store', ['as' => 'dlp.deposit.store', 'uses' => 'DlpController@storeDeposit']);
    Route::get('dlp/deposit/list', ['as' => 'dlp.deposit.list', 'uses' => 'DlpController@listDeposit']);
    Route::get('dlp/deposit/show/{id}', ['as' => 'dlp.deposit.show', 'uses' => 'DlpController@showDeposit']);
    Route::post('dlp/deposit/return/{id}', ['as' => 'dlp.deposit.return', 'uses' => 'DlpController@returnDeposit']);
    Route::post('dlp/deposit/approval/{id}', ['as' => 'dlp.deposit.approval', 'uses' => 'DlpController@approvalDeposit']);
    Route::post('dlp/deposit/usage/create/{id}', ['as' => 'dlp.deposit.usage.create', 'uses' => 'DlpController@createUsageDeposit']);
    Route::get('dlp/deposit/usage/{id}', ['as' => 'dlp.deposit.usage', 'uses' => 'DlpController@usageDeposit']);
    Route::post('dlp/deposit/usage/fileUpload', ['as' => 'dlp.deposit.usage.fileUpload', 'uses' => 'DlpController@fileUploadUsageDeposit']);
    Route::post('dlp/deposit/usage/delete{id}', ['as' => 'dlp.deposit.usage.delete', 'uses' => 'DlpController@deleteUsageDeposit']);

    // Route::get('dlp/progress', ['as' => 'dlp.progress', 'uses' => 'DlpController@progress']);
    // Route::post('dlp/progress/store', ['as' => 'dlp.progress.store', 'uses' => 'DlpController@storeProgress']);
    // Route::get('dlp/progress/list', ['as' => 'dlp.progress.list', 'uses' => 'DlpController@listProgress']);
    // Route::get('dlp/progress/show/{id}', ['as' => 'dlp.progress.show', 'uses' => 'DlpController@showProgress']);
    // Route::delete('dlp/progress/destroy/{id}', ['as' => 'dlp.progress.destroy', 'uses' => 'DlpController@destroyProgress']);

    // Route::get('dlp/period', ['as' => 'dlp.period', 'uses' => 'DlpController@period']);
    // Route::post('dlp/period/store', ['as' => 'dlp.period.store', 'uses' => 'DlpController@storePeriod']);
    // Route::get('dlp/period/list', ['as' => 'dlp.period.list', 'uses' => 'DlpController@listPeriod']);
    // Route::get('dlp/period/show/{id}', ['as' => 'dlp.period.show', 'uses' => 'DlpController@showPeriod'])

    /**
     * Ledger
     */
    Route::resource('ledger', 'LedgerController');
    
    Route::post('epksStatement/submit/{id}', ['as' => 'epksStatement.submit', 'uses' => 'EpksStatementController@submit']);
    Route::get('epksStatement/print/{id}', ['as' => 'epksStatement.print', 'uses' => 'EpksStatementController@printStatement']);
    Route::resource('epksStatement', 'EpksStatementController');

    /**
     * Reporting
     */
    Route::get('/reporting/epks', array('as' => 'reporting.epks.index', 'uses' => 'ReportController@epks'));
    Route::post('/print/epks',  array('as' => 'reporting.print.epks', 'uses' => 'PrintController@epks'));
    Route::post('print/generate',  array('as' => 'print.generate.index', 'uses' => 'PrintController@generate'));
    Route::get('/reporting/generate',  array('as' => 'report.generate.index', 'uses' => 'ReportController@generate'));
    Route::get('reporting/generate/selected',  array('as' => 'report.generateSelected.index', 'uses' => 'ReportController@generateSelected'));
    Route::post('print/statistic',  array('as' => 'print.statistic.index', 'uses' => 'PrintController@statistic'));
    Route::get('reporting/statistic',  array('as' => 'report.statistic.index', 'uses' => 'ReportController@statistic'));
    Route::get('reporting/fileMovement',  array('as' => 'report.fileMovement.index', 'uses' => 'ReportController@fileMovement'));
    Route::post('export/fileMovement', array('as' => 'export.fileMovement', 'uses' => 'ExportController@fileMovement'))->before('authMember');
    
    /**
     * Data Sync
     */
    Route::get('cob/get-option', 'CobController@getOption');
    Route::post('buyer/sync', 'CobSyncController@submitBuyerSync');
    Route::get('cob/get-property', 'CobSyncController@getProperty');

    Route::resource('file-movement', 'FileMovementController');

    /** COB File Movement */
    Route::get('update/fileMovement/{file_id}', array('as' => 'cob.file-movement.index', 'uses' => 'CobFileMovementController@index'));
    Route::get('update/addFileMovement/{file_id}', array('as' => 'cob.file-movement.create', 'uses' => 'CobFileMovementController@create'));
    Route::post('update/submitAddFileMovement', array('as' => 'cob.file-movement.store', 'uses' => 'CobFileMovementController@store'));
    Route::get('update/updateFileMovement/{id}/{file_id}', array('as' => 'cob.file-movement.edit', 'uses' => 'CobFileMovementController@edit'));
    Route::put('update/submitUpdateFileMovement/{id}', array('as' => 'cob.file-movement.update', 'uses' => 'CobFileMovementController@update'));
    Route::delete('update/deleteFileMovement/{id}', array('as' => 'cob.file-movement.destroy', 'uses' => 'CobFileMovementController@destroy'));
    Route::get('update/printFileMovement/{id}', array('as' => 'cob.file-movement.print', 'uses' => 'PrintController@printFileMovement'));

     /** COB Audit Account */
     Route::get('update/auditAccount/{file_id}', array('as' => 'cob.audit-account.index', 'uses' => 'CobAuditAccountController@index'));
     Route::get('update/addAuditAccount/{file_id}', array('as' => 'cob.audit-account.create', 'uses' => 'CobAuditAccountController@create'));
     Route::post('update/submitAddAuditAccount', array('as' => 'cob.audit-account.store', 'uses' => 'CobAuditAccountController@store'));
     Route::get('update/updateAuditAccount/{id}/{file_id}', array('as' => 'cob.audit-account.edit', 'uses' => 'CobAuditAccountController@edit'));
     Route::put('update/submitUpdateAuditAccount/{id}', array('as' => 'cob.audit-account.update', 'uses' => 'CobAuditAccountController@update'));
     Route::delete('update/deleteAuditAccount/{id}', array('as' => 'cob.audit-account.destroy', 'uses' => 'CobAuditAccountController@destroy'));
     Route::post('auditAccount/fileUpload',  array('as' => 'cob.audit-account.fileUpload', 'uses' => 'CobAuditAccountController@fileUpload'));
     
    /**
     * COB Draft Reject
     */
    Route::get('draft/reject/index', array('as' => 'file.draft.reject.index', 'uses' => 'DraftRejectController@index'));
    Route::get('draft/reject/create', array('as' => 'file.draft.reject.create', 'uses' => 'DraftRejectController@create'));
    Route::post('draft/reject', array('as' => 'file.draft.reject.store', 'uses' => 'DraftRejectController@store'));
    Route::get('draft/reject/{id}', array('as' => 'file.draft.reject.show', 'uses' => 'DraftRejectController@show'));

    /**
     * MPS Sync
     */
    Route::get('mpsSync', 'MPSSyncController@index');
    Route::get('mpsSync/getFileList', 'MPSSyncController@getFileList');
    Route::get('mpsSync/getFinanceList', 'MPSSyncController@getFinanceList');
    Route::post('mpsSync/destroy', 'MPSSyncController@destroy');   
    Route::post('file/sync', 'Api\FileController@submitSync'); 
    
    /**
     * Finance
     */
    Route::get('finance/recalculateSummary', 'FinanceController@recalculateSummary');
    
    /**
     * COB Letter
     */
    Route::get('cob_letter/getForm', array('as' => 'cob_letter.getForm', 'uses' => 'CobLetterController@getForm'));
    Route::resource('cob_letter', 'CobLetterController');

    /**
     * EService
     */
    Route::resource('eservicePrice', 'EServicePriceController');

    Route::get('eservice/draft', ['as' => 'eservice.draft', 'uses' => 'EServiceController@draft']);
    Route::get('eservice/approved', ['as' => 'eservice.approved', 'uses' => 'EServiceController@approved']);
    Route::get('eservice/rejected', ['as' => 'eservice.rejected', 'uses' => 'EServiceController@rejected']);
    Route::post('eservice/verify', ['as' => 'eservice.verify', 'uses' => 'EServiceController@verify']);
    Route::get('eservice/report', ['as' => 'eservice.report', 'uses' => 'EServiceController@report']);
    Route::get('eservice/getForm', array('as' => 'eservice.getForm', 'uses' => 'EServiceController@getForm'));
    Route::post('eservice/fileUpload', ['as' => 'eservice.fileUpload', 'uses' => 'EServiceController@fileUpload']);
    Route::post('eservice/submitPayment', array('as' => 'eservice.submitPayment', 'uses' => 'EServiceController@submitPayment'));
    Route::get('eservice/callbackPayment/{orderID}', array('as' => 'eservice.callbackPayment', 'uses' => 'EServiceController@callbackPayment'));
    Route::post('eservice/review', ['as' => 'eservice.review', 'uses' => 'EServiceController@review']);
    Route::post('eservice/submitApprove', ['as' => 'eservice.submitApprove', 'uses' => 'EServiceController@submitApprove']);
    Route::post('eservice/submitReject', ['as' => 'eservice.submitReject', 'uses' => 'EServiceController@submitReject']);
    Route::get('eservice/paymentHistory', array('as' => 'eservice.paymentHistory', 'uses' => 'EServiceController@paymentHistory'));
    Route::get('eservice/showPaymentHistory/{id}', array('as' => 'eservice.showPaymentHistory', 'uses' => 'EServiceController@showPaymentHistory'));

    Route::resource('eservice', 'EServiceController');
    Route::get('eservice/create/{type}', array('as' => 'eservice.create', 'uses' => 'EServiceController@create'));
    Route::get('eservice/payment/{id}', array('as' => 'eservice.payment', 'uses' => 'EServiceController@payment'));
    Route::post('eservice/submitByCOB/{id}', ['as' => 'eservice.submitByCOB', 'uses' => 'EServiceController@submitByCOB']);
    Route::get('eservice/getLetterPDF/{id}', ['as' => 'eservice.getLetterPDF', 'uses' => 'EServiceController@getLetterPDF']);
    Route::get('eservice/getLetterWord/{id}', ['as' => 'eservice.getLetterWord', 'uses' => 'EServiceController@getLetterWord']);
    /**
     * API Client
     */
    Route::resource('clients', 'APIClientController');
    Route::get('client/buildings', array('as' => 'clients.building.index', 'uses' => 'APIBuildingController@index'));
    Route::get('client/buildings/{id}/edit', array('as' => 'clients.building.edit', 'uses' => 'APIBuildingController@edit'));
    Route::get('client/buildings/{id}/active', array('as' => 'clients.building.status.active', 'uses' => 'APIBuildingController@updateActive'));
    Route::get('client/buildings/{id}/inactive', array('as' => 'clients.building.status.inactive', 'uses' => 'APIBuildingController@updateInactive'));
    Route::post('client/buildings/logs', array('as' => 'clients.building.log', 'uses' => 'APIBuildingController@log'));
    
    Route::resource('email_log', 'EmailLogController');
    Route::get('notification/markAll', array('as' => 'notification.markAll', 'uses' => 'NotificationController@markAll'));
    Route::resource('notification', 'NotificationController');
});

/** Transaction */
Route::get('transaction/success', 'TransactionController@success');

Route::group(array('prefix' => 'revenue'), function() {
    Route::get('transaction/pay', 'TransactionController@processRevenueMonster')->before('authMember');
    Route::post('transaction/success', 'TransactionController@revenueSuccess');
    Route::get('transaction/getOrderStatus', 'TransactionController@getRevenueTransactionStatus');
    // Route::get('get', 'TransactionController@getTransaction')->before('authMember');
    // Route::post('process', 'TransactionController@paymentProcess')->before('authMember');
});

Route::group(array('prefix' => 'transaction'), function() {
    Route::get('/', 'TransactionController@index')->before('authMember');
    Route::get('get', 'TransactionController@getTransaction')->before('authMember');
    Route::post('process', 'TransactionController@paymentProcess')->before('authMember');
});

Route::get('/{cob}', 'UserController@login')->before('guest');
Route::get('/{cob}/login', 'UserController@login')->before('guest');
Route::get('/{cob}/logout', 'UserController@logout')->before('authMember');

// Route group for API
Route::group(array('prefix' => 'api/v1'), function() {
    Route::post('sso/username-checking', 'Api\ApiController@SSOUsernameValidate');
    Route::post('sso/login', 'Api\ApiController@SSOLogin');
    Route::post('profile/update_simple', 'Api\ApiController@updateSimpleProfileInfo');
    Route::post('/login', 'Api\ApiController@login');
    Route::get('getCouncil', 'Api\ApiController@getCouncil');
    
    /** 
     * Finance File API 
     */
    Route::post('oauth/token', 'Api\AuthController@token');
    Route::group(array('prefix' => 'api', 'before' => 'jwt-auth'), function() {
        Route::post('files/get', 'Api\FileController@get');
        Route::post('finance/new', 'FinanceAPIController@addNewFinance');
        // Route::post('addNewFinanceCheck', 'FinanceAPIController@addNewFinanceCheck');
        // Route::post('addNewFinanceSummary', 'FinanceAPIController@addNewFinanceSummary');
        Route::post('finance/update', 'FinanceAPIController@updateFinance');
        // Route::post('updateFinanceCheck', 'FinanceAPIController@updateFinanceCheck');
        // Route::post('updateFinanceSummary', 'FinanceAPIController@updateFinanceSummary');
        // Route::delete('deleteFinanceFile/{id}', 'FinanceAPIController@deleteAllFinanceRecord');
        Route::post('finance/import', 'FinanceAPIController@import');
    });
});
//API route
Route::group(array('prefix' => 'api', 'before' => ['auth.basic', 'authMember']), function() {

    Route::post('addNewFinanceFile', 'FinanceAPIController@addNewFinance');
    Route::post('updateFinanceFile', 'FinanceAPIController@updateFinance');
    Route::post('files/get', 'Api\FileController@get');
    Route::post('importFinanceFile', 'FinanceAPIController@import');
});

Route::group(array('prefix' => 'api/v1/export'), function() {
    Route::get('councilFacility', 'ExportController@exportCouncilFacility');
    Route::get('councilFacilityByStrata', 'ExportController@exportCouncilFacilityByStrata');
    Route::get('strataName', 'ExportController@strataName');
    Route::get('reporting', 'ExportController@reporting');
    Route::get('JMBMCSignByCouncil', 'ExportController@JMBMCSignByCouncil');
    Route::get('tunggakanFinance', 'ExportController@tunggakanFinance');
    Route::get('fileDetails', 'ExportController@fileDetails');
    Route::post('generateReport', array('as' => 'api.v1.export.generateReport', 'uses' => 'ExportController@generateReport'));
});


Route::group(array('prefix' => 'api/v1', 'before' => 'jwt-auth'), function() {
    Route::post('/editProfile', 'Api\ApiController@editProfile');

    Route::post('/files', 'Api\ApiController@files');
    Route::post('/houseScheme', 'Api\ApiController@houseScheme');
    Route::post('/personInCharge', 'Api\ApiController@personInCharge');
    Route::post('/strata', 'Api\ApiController@strata');
    Route::post('/facility', 'Api\ApiController@facility');
    Route::post('/management', 'Api\ApiController@management');
    Route::post('/monitoring', 'Api\ApiController@monitoring');
    Route::post('/meetingJMB', 'Api\ApiController@meetingJMB');
    Route::post('/meetingMC', 'Api\ApiController@meetingMC');
    Route::post('/designation', 'Api\ApiController@designation');
    Route::post('/other', 'Api\ApiController@other');
    Route::post('/buyer', 'Api\ApiController@buyer');
    Route::post('/document', 'Api\ApiController@document');
    Route::post('/insurance', 'Api\ApiController@insurance');

    Route::post('/rating', 'Api\ApiController@rating');
    Route::post('/addRating', 'Api\ApiController@addRating');
    Route::post('/editRating', 'Api\ApiController@editRating');

    Route::post('/search', 'Api\ApiController@search');

    Route::get('getDashboardData', 'Api\ApiController@getDashboardData');
});

Route::group(array('prefix' => 'api/v2'), function() {
    Route::post('/agmEgm', 'Api\ResidentApiController@agmEgm');
    Route::post('/designation', 'Api\ResidentApiController@designation');
    Route::post('/complaint', 'Api\ResidentApiController@complaint');
    Route::get('/complaintCategory', 'Api\ResidentApiController@complaintCategory');
    Route::post('/addComplaint', 'Api\ResidentApiController@addComplaint');
    Route::post('/deleteComplaint', 'Api\ResidentApiController@deleteComplaint');
});

Route::group(array('prefix' => 'api/v3', 'before' => ['auth.basic', 'authMember']), function() {
    Route::get('dashboard/getAnalyticData', 'Api\DashboardAnalyticController@getAnalyticData');
    Route::group(array('prefix' => 'cob'), function() {
        Route::get('company/getOption', array('as' => 'v3.api.company.getOption', 'uses' => 'Api\CompanyController@getOption'));
        Route::get('company/getNameOption', array('as' => 'v3.api.company.getNameOption', 'uses' => 'Api\CompanyController@getNameOption'));
        Route::get('files/getOption',  array('as' => 'v3.api.files.getOption', 'uses' => 'Api\COBFileController@getOption'));
        Route::get('audit_trail/getOption', array('as' => 'v3.api.audit_trail.getModuleOption', 'uses' => 'Api\AuditTrailController@getModuleOption'));
        Route::get('role/getOption', array('as' => 'v3.api.role.getOption', 'uses' => 'Api\RoleController@getOption'));
        Route::get('strata/getOption',  array('as' => 'v3.api.strata.getOption', 'uses' => 'Api\StrataController@getOption'));
        Route::get('city/getOption',  array('as' => 'v3.api.city.getOption', 'uses' => 'Api\CityController@getOption'));
        Route::get('developer/getOption',  array('as' => 'v3.api.developer.getOption', 'uses' => 'Api\DeveloperController@getOption'));
        Route::get('dun/getOption',  array('as' => 'v3.api.dun.getOption', 'uses' => 'Api\DunController@getOption'));
        Route::get('area/getOption',  array('as' => 'v3.api.area.getOption', 'uses' => 'Api\AreaController@getOption'));
        Route::get('category/getOption',  array('as' => 'v3.api.category.getOption', 'uses' => 'Api\CategoryController@getOption'));

        Route::get('insurance/getAnalyticData', 'Api\InsuranceController@getAnalyticData');
        Route::get('insurance/getListing', 'Api\InsuranceController@getListing');
        Route::get('management/getAnalyticData', 'Api\ManagementController@getAnalyticData');
        Route::get('management/getListing', 'Api\ManagementController@getListing');
        Route::get('other/getAnalyticData', 'Api\OtherController@getAnalyticData');
        Route::get('other/getListing', 'Api\OtherController@getListing');
        Route::get('owner/getListing', 'Api\OwnerController@getListing');
        Route::get('owner/getAnalyticData', 'Api\OwnerController@getAnalyticData');
        Route::get('scoring/getAnalyticData', 'Api\ScoringController@getAnalyticData');
        Route::get('scoring/getRatingData', 'Api\ScoringController@getRatingData');
        Route::get('strata/getListing', 'Api\StrataController@getListing');
        Route::get('strata/getAnalyticData', 'Api\StrataController@getAnalyticData');
        Route::get('tenant/getListing', 'Api\TenantController@getListing');
        Route::get('tenant/getAnalyticData', 'Api\TenantController@getAnalyticData');
        Route::get('cob_letter/getTypeOptions', array('as' => 'v3.api.cob_letter.getTypeOptions', 'uses' => 'Api\COBLetterController@getTypeOptions'));
        Route::get('eservice/getTypeOptions', array('as' => 'v3.api.eservice.getTypeOptions', 'uses' => 'Api\EServiceController@getTypeOptions'));
    });

    Route::group(array('prefix' => 'finance'), function() {
        Route::get('file/getAnalyticData', 'Api\FinanceFileController@getAnalyticData');
        Route::get('support/getListing', 'Api\FinanceSupportController@getListing');
        Route::get('support/getAnalyticData', 'Api\FinanceSupportController@getAnalyticData');
    });

    Route::group(array('prefix' => 'other'), function() {
        Route::get('agent/getListing', 'Api\AgentController@getListing');
        Route::get('city/getListing', 'Api\CityController@getListing');
        Route::get('complaint/getListing', 'Api\ComplaintController@getListing');
        Route::get('complaint/getAnalyticData', 'Api\ComplaintController@getAnalyticData');
        Route::get('developer/getListing', 'Api\DeveloperController@getListing');
        Route::get('developer/getAnalyticData', 'Api\DeveloperController@getAnalyticData');
        Route::get('dun/getListing', 'Api\DunController@getListing');
        Route::get('dun/option', 'Api\DunController@option');
        Route::get('insurance_provider/getListing', 'Api\InsuranceProviderController@getListing');
        Route::get('insurance_provider/getAnalyticData', 'Api\InsuranceProviderController@getAnalyticData');
        Route::get('land/getListing', 'Api\LandController@getListing');
        Route::get('land_category/getListing', 'Api\LandCategoryController@getListing');
        Route::get('land_category/getAnalyticData', 'Api\LandCategoryController@getAnalyticData');
        Route::get('park/getListing', 'Api\ParkController@getListing');
        Route::get('parliment/getListing', 'Api\ParlimentController@getListing');
        Route::get('parliment/getAnalyticData', 'Api\ParlimentController@getAnalyticData');
        Route::get('parliment/option', 'Api\ParlimentController@option');
        Route::get('state/getListing', 'Api\StateController@getListing');
    });
});

/*
 * Cronjob
 */
Route::get('cronjob/createFileByCob/{cob}', 'CronjobController@createFileByCob');
Route::get('cronjob/createFile/{id}', 'CronjobController@createFile');
Route::get('cronjob/updateFile/{id}', 'CronjobController@updateFile');
Route::get('cronjob/deleteFile/{id}', 'CronjobController@deleteFile');

Route::group(array('prefix' => 'api/v4'), function() {
    // Files API
    Route::get('files', 'Api\FileController@files');
    Route::get('filesHouseScheme', 'Api\FileController@filesHouseScheme');
    Route::get('filesStrata', 'Api\FileController@filesStrata');
    Route::get('filesFacility', 'Api\FileController@filesFacility');
    Route::get('filesManagement', 'Api\FileController@filesManagement');
    Route::get('filesManagementJMB', 'Api\FileController@filesManagementJMB');
    Route::get('filesManagementMC', 'Api\FileController@filesManagementMC');
    Route::get('filesManagementAgent', 'Api\FileController@filesManagementAgent');
    Route::get('filesManagementOthers', 'Api\FileController@filesManagementOthers');
    Route::get('filesManagementDeveloper', 'Api\FileController@filesManagementDeveloper');
    Route::get('filesMonitoring', 'Api\FileController@filesMonitoring');
    Route::get('filesMonitoringDocument', 'Api\FileController@filesMonitoringDocument');
    Route::get('filesOther', 'Api\FileController@filesOther');
    Route::get('filesRating', 'Api\FileController@filesRating');

    // Finance API
    Route::get('financeFile', 'Api\FinanceController@financeFile');
    Route::get('financeCheck', 'Api\FinanceController@financeCheck');
    Route::get('financeSummary', 'Api\FinanceController@financeSummary');
    Route::get('financeReport', 'Api\FinanceController@financeReport');
    Route::get('financeReportExtra', 'Api\FinanceController@financeReportExtra');
    Route::get('financeReportPerbelanjaan', 'Api\FinanceController@financeReportPerbelanjaan');
    Route::get('financeIncome', 'Api\FinanceController@financeIncome');
    Route::get('financeUtility', 'Api\FinanceController@financeUtility');
    Route::get('financeContract', 'Api\FinanceController@financeContract');
    Route::get('financeRepair', 'Api\FinanceController@financeRepair');
    Route::get('financeVandalisme', 'Api\FinanceController@financeVandalisme');
    Route::get('financeStaff', 'Api\FinanceController@financeStaff');
    Route::get('financeAdmin', 'Api\FinanceController@financeAdmin');

    Route::get('syncFile', 'Api\FileController@syncFile');
    Route::get('syncFinance', 'Api\FinanceController@syncFinance');
});

//invalid route
Route::get('/{name?}', 'AdminController@showView')->before('authMember');


Route::get('test/finance/api', 'TestController@updateFinanceAPI');