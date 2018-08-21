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
        $this->setStats();
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
            throw new UserLSMCDException('Error getting stats info: RC: ' .
            $return_var . ' Output: ' .
            $resOutput);
        }

        $output = (!empty($resOutput)) ? explode("\n", $resOutput) : array();

        return $output;
    }

    private function setStats()
    {
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
