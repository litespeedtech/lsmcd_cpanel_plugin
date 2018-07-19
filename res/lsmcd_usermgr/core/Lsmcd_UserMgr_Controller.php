<?php

/* * ******************************************
 * LiteSpeed Web Cache Management Plugin for cPanel
 * @Author:   LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel;

use \LsmcdUserPanel\Lsmcd_UserMgr_Util;
use \LsmcdUserPanel\Lsc\Context\UserContext;
use \LsmcdUserPanel\Lsc\Context\UserPanelContextOption;
//use \LsmcdUserPanel\Lsc\UserLogger;
use \LsmcdUserPanel\Lsc\UserLSMCDException;
use \LsmcdUserPanel\View\Model as ViewModel;
use \LsmcdUserPanel\View\View;

class Lsmcd_UserMgr_Controller
{

    const MODULE_VERSION = '1.0.0';
    const TPL_DIR = 'core/View/Tpl';
    
    static $userMgrRuns = 0;

    public function __construct()
    {
        $this->init();
    }

    /**
     *
     * @throws UserLSMCDException
     */
    private function init()
    {
        UserContext::initialize(new UserPanelContextOption('cpanel_user'));
    }

    public function run()
    {
        $supportedActions = array(
            'ChangePassword', 'DisplayStats', '', 'main'
        );

        $do = Lsmcd_UserMgr_Util::get_request_var('do');
  
        switch ($do) {
            case 'ChangePassword':
                $this->changePassword();
                break;
            case 'DisplayStats':
                $this->displayStats();
                break;
            default:
                if ($userMgrRuns)
                    throw new UserLSMCDException("No good do value");
                $this->main();
                $userMgrRuns ++;
                break;
        }
    }

    private function main()
    {
        $viewModel = new ViewModel\MainViewModel();
        $this->display($viewModel);
    }

    private function changePassword()
    {
        
        throw new UserLSMCDException('Change Password request!');
        //$changePasswordModel = new ViewModel\ChangePasswordModel();
        //$this->display($changePasswordModel);
        
    }

    private function displayStats()
    {
        //throw new UserLSMCDException('Display Stats request');
        $viewModel = new ViewModel\StatsModel();
        $this->display($viewModel);
    }

    /**
     * Creates a View object with the passes ViewModel and displays to screen.
     *
     * @param object  $viewModel  ViewModel object.
     */
    private function display( $viewModel )
    {
        $view = new View($viewModel);

        //try {
            $view->display();
        //}
        //catch ( UserLSMCDException $e ) {
        //    $viewModel = new ViewModel\MissingTplViewModel($e->getMessage());
        //    $view = new View($viewModel);
        //    $view->display();
        //}
    }

}
