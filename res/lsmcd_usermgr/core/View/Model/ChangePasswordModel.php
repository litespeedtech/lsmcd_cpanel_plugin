<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View\Model;

use LsmcdUserPanel\CPanelWrapper;
use LsmcdUserPanel\Lsmcd_UserMgr_Util;

class ChangePasswordModel
{

    const FLD_USER = 'user';
    const FLD_SERVER = 'server';
    const FLD_MESSAGE = 'message';
    const FLD_DONE = 'done';

    /**
     * @var mixed[]
     */
    private $tplData = array();

    /**
     *
     * @param string  $subFunction
     */
    public function __construct( $subFunction )
    {
        $this->init($subFunction);
    }

    private function doChange( $user, $password )
    {
        /* Create password file as it only works with a file */
        $result = FALSE;
        $fileName = '/tmp/lsmcd' . $user . '.tmp';
        $file = fopen($fileName, 'wb');
        if ( !$file ) {
            $this->setMessage('Error creating temporary file');
            return FALSE;
        }
        fwrite($file, $password);
        fclose($file);

        $file = "saslpasswd2";

        $cmd = "{$file} -f /etc/sasldb2 -p {$user} < {$fileName}";

        $cpanel = CPanelWrapper::getCpanelObj();

        $result = $cpanel->uapi('lsmcd', 'execIssueCmd', array( 'cmd' => $cmd ));

        $return_var = $result['cpanelresult']['result']['data']['retVar'];
        $resOutput = $result['cpanelresult']['result']['data']['output'];
        if ( $return_var > 0 ) {
            $this->setMessage('saslpasswd2 error: ' . $resOutput);
        }
        else {
            $this->setMessage("Password set successfully");
            $result = TRUE;
        }
        unlink($fileName);
        return $result;
    }

    private function tryChange()
    {
        $password1 = $_POST["pwd1"];
        $password2 = $_POST["pwd2"];
        if ( (strlen($password1) == 0) ||
                (strlen($password2) == 0) ) {
            $this->setMessage('ERROR: Enter the new password in both fields');
        }
        else if ( strcmp($password1, $password2) != 0 ) {
            $this->setMessage('ERROR: Passwords do not match.');
        }
        else {
            if ( $this->doChange(Lsmcd_UserMgr_Util::getCurrentCpanelUser(),
                            $password1) )
                $this->setDone("DONE!");
        }
    }

    private function init( $subFunction )
    {
        $this->setUser();
        $this->setServer();
        $this->setDone('');
        if ( $subFunction != '' )
            $this->tryChange();
        else
            $this->setMessage('');
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

    private function setUser()
    {
        $this->tplData[self::FLD_USER] =
                Lsmcd_UserMgr_Util::getCurrentCpanelUser();
    }

    private function setServer()
    {
        $this->tplData[self::FLD_SERVER] = Lsmcd_UserMgr_Util::getServerAddr();
    }

    private function strArray( $array )
    {
        $str = '';
        foreach ( $array as $key => $value ) {
            if ( strlen($str) )
                $str .= ', ';
            $str .= 'key: ' . $key . ' value: ' . $value;
        }
        return $str;
    }

    public function setMessage( $message )
    {
        $this->tplData[self::FLD_MESSAGE] = $message;
    }

    public function setDone( $flag )
    {
        $this->tplData[self::FLD_DONE] = $flag;
    }

    /**
     *
     * @return string
     */
    public function getTpl()
    {
        return realpath(__DIR__ . '/../Tpl') . '/ChangePassword.tpl';
    }

}
