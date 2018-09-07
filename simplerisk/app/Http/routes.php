<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\Risk;
use Illuminate\Http\Request;

/* Account Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'account'],function () {
    Route::get('/', function () {
        return view('default');
    });
    Route::get('change_password.php', function () {
        return view('default');
    });
    Route::get('profile.php', function () {
        return view('default');
    });
});

/* Admin Routes */
Route::group(['prefix' => 'admin'],function () {
    Route::get('/', 'AdminController@index');
    Route::get('about.php', 'AdminController@about');
    Route::get('add_remove_values.php', 'AdminController@addremovevalues');
    Route::get('announcements.php', 'AdminController@announcements');
    Route::get('assetvaluation.php', 'AdminController@assetvaluation');
    Route::get('audit_trail.php', 'AdminController@audittrail');
    Route::get('configure_risk_formula.php', 'AdminController@configureriskformula');
    Route::get('custom_names.php', 'AdminController@customnames');
    Route::get('delete_risks.php', 'AdminController@deleterisks');
    Route::get('extras.php', 'AdminController@extras');
    Route::get('health_check.php', 'AdminController@healthcheck');
    Route::get('index.php', 'AdminController@index');
    Route::get('register.php', 'AdminController@register');
    Route::get('review_settings.php', 'AdminController@reviewsettings');
    Route::get('role_management.php', 'AdminController@rolemanagement');
    Route::get('user_management.php', 'AdminController@usermanagement');
    

    // TODO
    Route::get('active_assessments.php', function () {
        return view('default');
    });
    Route::get('api.php', function () {
        return view('default');
    });
    Route::get('assessments.php', function () {
        return view('default');
    });
    Route::get('authentication.php', function () {
        return view('default');
    });
    Route::get('complianceforge_scf.php', function () {
        return view('default');
    });
    Route::get('complianceforge.php', function () {
        return view('default');
    });
    Route::get('complianceforge_risk_formula.php', function () {
        return view('default');
    });
    Route::get('controls.php', function () {
        return view('default');
    });
    
    Route::get('customization.php', function () {
        return view('default');
    });
    
    Route::get('encryption.php', function () {
        return view('default');
    });
    
    Route::get('importexport.php', function () {
        return view('default');
    });
    
    Route::get('notification.php', function () {
        return view('default');
    });
    Route::get('separation.php', function () {
        return view('default');
    });
    Route::get('upgrade.php', function () {
        return view('default');
    });
    
    Route::get('view_user_details.php', function () {
        return view('default');
    });
});

/* Api Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'api'],function () {
    Route::get('/', function () {
        return view('default');
    });
    Route::get('index.php', function () {
        return view('default');
    });
    Route::get('mock.php', function () {
        return view('default');
    });
});

/* Assessments Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'assessments'],function () {
    Route::get('index.php', 'AssessmentController@index');
    Route::get('risks.php', 'AssessmentController@risks');
    Route::get('assessments.php', function () {
        return view('default');
    });
    Route::get('contacts.php', function () {
        return view('default');
    });
    Route::get('download.php', function () {
        return view('default');
    });
    Route::get('importexport.php', function () {
        return view('default');
    });
    
    Route::get('questionnaire_compare.php', function () {
        return view('default');
    });
    Route::get('questionnaire_questions.php', function () {
        return view('default');
    });
    Route::get('questionnaire_results.php', function () {
        return view('default');
    });
    Route::get('questionnaire_templates.php', function () {
        return view('default');
    });
    Route::get('questionnaire_trail.php', function () {
        return view('default');
    });
    Route::get('questionnaire_index.php', function () {
        return view('default');
    });
    Route::get('questionnaires.php', function () {
        return view('default');
    });
    Route::get('risks.php', function () {
        return view('default');
    });
});

/* Assets Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'assets'],function () {
    Route::get('/', function () {
        return view('default');
    });
    Route::get('adddeleteassets.php', function () {
        return view('default');
    });
    Route::get('edit.php', function () {
        return view('default');
    });
    Route::get('index.php', function () {
        return view('default');
    });
    Route::get('view.php', function () {
        return view('default');
    });
});

/* Assets Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'compliance'],function () {
    Route::get('/', function () {
        return view('default');
    });
    Route::get('active_audits.php', function () {
        return view('default');
    });
    Route::get('audit_initiation.php', function () {
        return view('default');
    });
    Route::get('download.php', function () {
        return view('default');
    });
    Route::get('index.php', function () {
        return view('default');
    });
    Route::get('past_audits.php', function () {
        return view('default');
    });
    Route::get('testing.php', function () {
        return view('default');
    });
    Route::get('view_test.php', function () {
        return view('default');
    });
});

/* Governance Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'governance'],function () {
    Route::get('/', function () {
        return view('default');
    });
    Route::get('documentation.php', function () {
        return view('default');
    });
    Route::get('download.php', function () {
        return view('default');
    });
    Route::get('index.php', function () {
        return view('default');
    });
});

/* Management Routes */
Route::group(['middleware' => 'auth', 'prefix' => 'management'],function () {
    Route::get('/', function () {
        return view('default');
    });
    Route::get('documentation.php', function () {
        return view('default');
    });
    Route::get('download.php', function () {
        return view('default');
    });
    Route::get('index.php', function () {
        return view('default');
    });
});

/* Report Routes */
Route::group(['prefix' => 'reports'],function () {
    Route::get('index.php', 'ReportController@index');
});

/* Main Routes */
Route::auth();

Route::get('/mitigations', 'MitigationController@index');
Route::post('/mitigation', 'MitigationController@store');
Route::get('/mitigation', 'MitigationController@new');
Route::delete('/mitigation/{mitigation}', 'MitigationController@destroy');

Route::get('/assets', 'AssetController@index');
Route::post('/asset', 'AssetController@store');
Route::get('/asset', 'AssetController@new');
Route::delete('/asset/{asset}', 'AssetController@destroy');

Route::get('/assessments', 'AssessmentController@index');
Route::get('/assessment/{id?}/{query?}', 'StandardController@index');
Route::post('/assesment', 'AssessmentController@store');
Route::get('/assesment', 'AssessmentController@new');
Route::delete('/assesment/{assesment}', 'AssessmentController@destroy');

/* Risks */
Route::get('/risks', 'RiskController@index');
Route::post('/risk', 'RiskController@store');
Route::get('/risk', 'RiskController@new');
Route::delete('/risk/{risk}', 'RiskController@destroy');

Route::get('/', 'HomeController@index');
Route::get('/index.php', 'HomeController@index');
