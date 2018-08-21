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
            include $tplPath;
        }
        else {
            throw new UserLSMCDException("Could not load page template {$tplPath}.");
        }
    }

}
