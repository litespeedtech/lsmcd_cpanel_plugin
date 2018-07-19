<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View\Model;

class MissingTplViewModel
{

    const FLD_MSG = 'msg';

    /**
     * @var string[]
     */
    private $tplData = array();

    /**
     *
     * @param string  $msg
     */
    public function __construct( $msg )
    {
        $this->init($msg);
    }

    /**
     *
     * @param string  $msg
     */
    private function init( $msg )
    {
        $this->tplData[self::FLD_MSG] = $msg;
    }

    /**
     *
     * @param string  $feild
     * @return null|string
     */
    public function getTplData( $feild )
    {
        if ( !isset($this->tplData[$feild]) ) {
            return null;
        }

        return $this->tplData[$feild];
    }

    /**
     *
     * @return string
     */
    public function getTpl()
    {
        return realpath(__DIR__ . '/../Tpl') . '/MissingTpl.tpl';
    }

}
