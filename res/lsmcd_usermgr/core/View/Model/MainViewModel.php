<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View\Model;

use LsmcdUserPanel\Lsmcd_UserMgr_Controller;
use LsmcdUserPanel\Lsmcd_UserMgr_Util;
use LsmcdUserPanel\Lsc\UserLogger;

class MainViewModel
{

    const FLD_PLUGIN_VER = 'pluginVer';
    const FLD_ERR_MSGS = 'errMsgs';
    const FLD_SUCC_MSGS = 'succMsgs';
    const FLD_ADDR = 'addr';
    const FLD_USER = 'user';
    const FLD_DATA_BY_USER = 'dataByUser';
    const FLD_SASL = 'sasl';

    /**
     * @var mixed[]
     */
    private $tplData = array();

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->setPluginVer();
        $this->setMsgData();
        $this->setAddr();
        $this->setDataByUser();
        $this->setUser();
        $this->setSASL();
    }

    /**
     *
     * @param string  $feild
     * @return null|mixed
     */
    public function getTplData( $feild )
    {
        if ( !isset($this->tplData[$feild]) ) {
            return null;
        }

        return $this->tplData[$feild];
    }

    private function setPluginVer()
    {
        $this->tplData[self::FLD_PLUGIN_VER] =
                Lsmcd_UserMgr_Controller::MODULE_VERSION;
    }

    private function setMsgData()
    {
        $this->tplData[self::FLD_ERR_MSGS] =
                UserLogger::getUiMsgs(UserLogger::UI_ERR);
        $this->tplData[self::FLD_SUCC_MSGS] =
                UserLogger::getUiMsgs(UserLogger::UI_SUCC);
    }

    private function setAddr()
    {
        $this->tplData[self::FLD_ADDR] = Lsmcd_UserMgr_Util::getServerAddr();
    }

    private function setDataByUser()
    {
        $this->tplData[self::FLD_DATA_BY_USER] =
                Lsmcd_UserMgr_Util::getDataByUser();
    }

    private function setUser()
    {
        $this->tplData[self::FLD_USER] =
                Lsmcd_UserMgr_Util::getCurrentCpanelUser();
    }

    private function setSASL()
    {
        $this->tplData[self::FLD_SASL] = Lsmcd_UserMgr_Util::getUseSASL();
    }

    /**
     *
     * @return string
     */
    public function getTpl()
    {
        return realpath(__DIR__ . '/../Tpl') . '/Main.tpl';
    }

}
