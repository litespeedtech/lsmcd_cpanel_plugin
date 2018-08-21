<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View\Model;

use LsmcdUserPanel\CPanelWrapper;
use LsmcdUserPanel\Lsmcd_UserMgr_Util;
use LsmcdUserPanel\Lsc\UserLSMCDException;

class StatsModel
{

    const FLD_STATS = 'stats';
    const FLD_USER = 'user';
    const FLD_SERVER = 'server';
    const FLD_ERR_MSG = 'errMsg';

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
        $this->setUser();
        $this->setServer();
        $this->setStatsAndErrMsg();
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

    private function doStats()
    {
        $lsmcdHome = getcwd();

        $cpanel = CPanelWrapper::getCpanelObj();

        $result = $cpanel->uapi('lsmcd', 'doStats',
                array( 'server' => $this->tplData[self::FLD_SERVER],
                        'directory' => $lsmcdHome ));

        $return_var = $result['cpanelresult']['result']['data']['retVar'];
        $resOutput = $result['cpanelresult']['result']['data']['output'];

        if ( $return_var > 0 ) {

            switch ($return_var) {
                case UserLSMCDException::E_USER_NOT_DEFINED:
                    $errMsg = ' User not found in SASL database. Please visit the '
                            . '<button type="submit" name="do" value="ChangePassword"'
                            . ' class="uk-button-link">Change Password</button> screen to '
                            . 'set a password and create an entry.';
                    break;
                default:
                    $msg =
                        "Error getting stats info: RC: {$return_var} Output: {$resOutput}";
                    throw new UserLSMCDException($msg);
            }

            $this->tplData[self::FLD_ERR_MSG] = $errMsg;
            return array();
        }

        $output = (!empty($resOutput)) ? explode("\n", $resOutput) : array();

        return $output;
    }

    private function setStatsAndErrMsg()
    {
        $this->tplData[self::FLD_ERR_MSG] = '';
        $this->tplData[self::FLD_STATS] = $this->doStats();
    }

    private function setUser()
    {
        $this->tplData[self::FLD_USER] = Lsmcd_UserMgr_Util::getDataByUser() ?
                Lsmcd_UserMgr_Util::getCurrentCpanelUser() :
                'All Users';
    }

    private function setServer()
    {
        $this->tplData[self::FLD_SERVER] = Lsmcd_UserMgr_Util::getServerAddr();
    }

    /**
     *
     * @return string
     */
    public function getTpl()
    {
        return realpath(__DIR__ . '/../Tpl') . '/Stats.tpl';
    }

}
