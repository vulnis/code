<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Setting;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate an array that will be used to generate the side menu
     */
    private function getSideMenu(){
        $menu = array(
            array(trans('messages.Settings'), 'index.php'),
            array(trans('messages.ConfigureRiskFormula'), 'configure_risk_formula.php'),
            array(trans('messages.ConfigureReviewSettings'), 'review_settings.php'),
            array(trans('messages.AddAndRemoveValues'), 'add_remove_values.php'),
            array(trans('messages.RoleManagement'), 'role_management.php'),
            array(trans('messages.UserManagement'), 'user_management.php'),
            array(trans('messages.RedefineNamingConventions'), 'custom_names.php'),
            array(trans('messages.AssetValuation'), 'assetvaluation.php'),
            array(trans('messages.DeleteRisks'), 'delete_risks.php'),
            array(trans('messages.AuditTrail'), 'audit_trail.php'),
        );
        // If the Import/Export Extra is enabled
        if ($this->getSetting('import_export'))
        {
            $menu[] = array(trans('messages.Import') . "/" . trans('messages.Export'), 'importexport.php');
        }

        // If the Assessments Extra is enabled
        if ($this->getSetting('assessments'))
        {
            $menu[] = array(trans('messages.ActiveAssessments'), 'active_assessments.php');
        }
        $menu[] = array(trans('messages.Extras'), 'extras.php');
        $menu[] = array(trans('messages.Announcements'), 'announcements.php');
        $menu[] = array(trans('messages.Register') . " & " . trans('messages.Upgrade'), 'register.php');
        $menu[] = array(trans('messages.HealthCheck'), 'health_check.php');
        $menu[] = array(trans('messages.About'), 'about.php');
        return $menu;
    }

    /**
     * Retrieve a value from the settings table by name
     */
    private function getSetting($name)
    {
        $result = Setting::where('name', $name)->first();
        if($result){
            return $result->value;
        } else {
            return;
        }
    }

    /**
     * Get a instance token from the database or generate one
     */
    private function getToken(){
        $instance = Setting::where('name', 'instance_id')->first();
        if(!$instance){
            // Generate
            $token = "";
            $values = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        
            for ($i = 0; $i < 50; $i++)
            {
                $token .= $values[array_rand($values)];
            }
            $instance = new Setting;
            $instance->name = 'instance_id';
            $instance->value = $token;
            $instance->save();
            $instance_id = $token;
        } else {
            $instance_id = $instance->value;
        }
        return $instance_id;
    }

    /**
     * update a Setting in the database. Only administrators may do so.
     */
    private function updateSetting($name, $value)
    {

    }

    public function index()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function configureriskformula()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function reviewsettings()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function addremovevalues()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function rolemanagement()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function usermanagement()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function customnames()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function assetvaluation()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function deleterisks()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function audittrail()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function extras()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function announcements()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }

    public function register()
    {
        return view('admin.register',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu(),
            'registration_notice' => true,
            'instance_id' => $this->getToken(),
            'mysqldump' => false,
            'mysqldumppath' => $this->getSetting('mysqldump_path'),
            'registered' => false,
            'update' => isset($_POST['update'])
        ]);
    }

    public function healthcheck()
    {
        return view('admin.index',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu()
        ]);
    }
    
    public function about()
    {
        return view('admin.about',[
            'prefix' => 'admin',
            'menu' => $this->getSideMenu(),
            'versioninfo' => $this->getVersionInfo()
        ]);
    }

    private function getVersionInfo()
    {
        $version_page = file('https://updates.simplerisk.com/Current_Version.xml');
        $regex_pattern = array();
        $regex_pattern['app'] = array('regex' => '/<appversion>(.*)<\/appversion>/', 'latest'=>'');
        $regex_pattern['db'] = array('regex' => '/<dbversion>(.*)<\/dbversion>/', 'latest'=>'');
        $regex_pattern['authentication'] = array('regex' => '/<authentication>(.*)<\/authentication>/', 'latest'=>'');
        $regex_pattern['encryption'] = array('regex' => '/<encryption>(.*)<\/encryption>/', 'latest'=>'');
        $regex_pattern['importexport'] = array('regex' => '/<importexport>(.*)<\/importexport>/', 'latest'=>'');
        $regex_pattern['notification'] = array('regex' => '/<notification>(.*)<\/notification>/', 'latest'=>'');
        $regex_pattern['separation'] = array('regex' => '/<separation>(.*)<\/separation>/', 'latest'=>'');
        $regex_pattern['upgrade'] = array('regex' => '/<upgrade>(.*)<\/upgrade>/', 'latest'=>'');
        $regex_pattern['assessments'] = array('regex' => '/<assessments>(.*)<\/assessments>/', 'vallatestue'=>'');
        $regex_pattern['api'] = array('regex' => '/<api>(.*)<\/api>/', 'latest'=>'');
        $regex_pattern['complianceforge'] = array('regex' => '/<complianceforge>(.*)<\/complianceforge>/', 'latest'=>'');
        $regex_pattern['complianceforgescf'] = array('regex' => '/<complianceforgescf>(.*)<\/complianceforgescf>/', 'latest'=>'');
        $regex_pattern['customization'] = array('regex' => '/<customization>(.*)<\/customization>/', 'latest'=>'');

        $result = array();
        foreach ($version_page as $line)
        {
            foreach($regex_pattern as $k => &$entry)
                if (preg_match($entry['regex'], $line, $matches))
                {
                    if($k == 'app')
                    {
                        $entry['current'] = config('app.version');
                    } elseif ($k == 'db')
                    {
                        $entry['current'] = $this->getSetting('db_version');
                    }

                    $entry['latest'] = $matches[1];
                }
        }
        return $regex_pattern;
    }
}
