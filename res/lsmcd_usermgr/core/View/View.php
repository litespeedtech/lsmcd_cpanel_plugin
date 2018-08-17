<?php

/* * ******************************************
 * LiteSpeed Web Cache Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View;

use \LsmcdUserPanel\Lsc\UserLSMCDException;

class View
{

    /**
     * @var object
     */
    private $viewModel;

    /**
     *
     * @param object  $viewModel
     */
    public function __construct( $viewModel )
    {
        $this->viewModel = $viewModel;
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
            $d = array();

            $this->loadTplBlock('PageHeader.tpl', $d);

            include $tplPath;

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
