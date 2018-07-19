<?php

/* * ******************************************
 * LiteSpeed Web Cache Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View;

use \LsmcdUserPanel\CPanelWrapper;
use \LsmcdUserPanel\Lsmcd_UserMgr_Util;
use \LsmcdUserPanel\Lsc\UserLSMCDException;

class View
{

    /**
     * @var object
     */
    private $viewModel;

    /**
     * @var \CPANEL  Used to generate header and footer content.
     */
    private $cpanel;

    /**
     *
     * @param object  $viewModel
     */
    public function __construct( $viewModel )
    {
        $this->viewModel = $viewModel;
        $this->cpanel = CPanelWrapper::getCpanelObj();
    }

    public function display()
    {
        $this->loadTpl($this->viewModel->getTpl());
    }

    /**
     *
     * @param string  $tplPath
     * @throws UserLSCMException
     */
    private function loadTpl( $tplPath )
    {

        if ( file_exists($tplPath) ) {
            $do = Lsmcd_UserMgr_Util::get_request_var('do');

            $d = array(
                'do' => $do
            );
            $this->loadTplBlock('PageHeader.tpl', $d);

            include $tplPath;

            $d = array();
            $this->loadTplBlock('PageFooter.tpl', $d);
        }
        else {
            throw new UserLSMCDException("Could not load page template {$tplPath}.");
        }
    }

    /**
     * Used by the page template to load sub-template blocks.
     *
     * @param string  $tplName
     * @param array   $d        Sub-template data.
     * @throws UserLSCMException
     */
    private function loadTplBlock( $tplName, $d )
    {
        $tplPath = __DIR__ . "/Tpl/Blocks/{$tplName}";

        if ( file_exists($tplPath) ) {
            include $tplPath;
        }
        else {
            throw new UserLSMCDException("Could not load block template " . 
                                         $tplPath);
        }
    }

}
