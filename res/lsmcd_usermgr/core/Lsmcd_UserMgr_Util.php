<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel;

use \LsmcdUserPanel\CPanelWrapper;
use LsmcdUserPanel\Lsc\UserLSMCDException;

class Lsmcd_UserMgr_Util
{

    /**
     * @var null|string
     */
    const NODE_CONF_FILE = '/usr/local/lsmcd/conf/node.conf';
    const ADDR_TITLE = "cached.addr";
    const USE_SASL_TITLE = 'cached.usesasl';
    const DATA_BY_USER_TITLE = 'cached.databyuser';
    
    private static $homeDir;
    static $addr;
    static $useSASL;
    static $dataByUser;
    static $addrOnly;
    static $port;

    private function __construct() 
    {
        self::$addr = "";
        self::$useSASL = FALSE;
        self::$dataByUser = FALSE;
    }

    /**
     *
     * @return string
     * @throws \Lsc\Wp\LSCMException
     */
    public static function getHomeDir()
    {
        if ( !self::$homeDir ) {

            if ( isset($_SERVER['HOME']) ) {
                self::$homeDir = $_SERVER['HOME'];
            }
            elseif ( isset($_SERVER['DOCUMENT_ROOT']) ) {
                self::$homeDir = $_SERVER['DOCUMENT_ROOT'];
            }
            else {
                throw new LSMCDException('Could not get home directory');
            }
        }

        return self::$homeDir;
    }

    /**
     * Returns the length of chars that make up the users the home dir.
     *
     * @return int
     */
    public static function getHomeDirLen()
    {
        $homeDirLen = strlen(self::getHomeDir());

        return $homeDirLen;
    }

    /**
     * Gets currently executing user through cPanel set $_ENV variable.
     *
     * @return string
     */
    public static function getCurrentCpanelUser()
    {
        return $_ENV['USER'];
    }

    /**
     *
     * @return string
     */
    public static function getLsmcdHome()
    {
        $confFile = realpath(__DIR__ . '/../lsmcd.conf');

        $cpanel = CPanelWrapper::getCpanelObj();

        $result = $cpanel->uapi('lsmcd', 'getLsmcdHomeDir',
                array( 'confFile' => $confFile ));

        $lsmcdHomeDir = $result['cpanelresult']['result']['data']['lsmcdHomeDir'];

        return $lsmcdHomeDir;
    }

    /**
     *
     * @param string  $tag
     * @return null|string
     */
    public static function get_request_var( $tag )
    {
        if ( !isset($_REQUEST[$tag]) )
            return NULL;

        return $_REQUEST[$tag];
    }

    /**
     *
     * @param string  $tag
     * @return null|string[]
     */
    public static function get_request_list( $tag )
    {
        if ( !isset($_REQUEST[$tag]) )
            return NULL;

        $result = $_REQUEST[$tag];
        return (is_array($result)) ? $result : NULL;
    }

    /**
     * Recursively deletes a directory and its contents.
     *
     * @param string  $dir  Directory path
     */
    public static function rrmdir( $dir, $keepParent = false )
    {
        if ( $dir != '' && is_dir($dir) ) {

            foreach ( glob($dir . '/*') as $file ) {

                if ( is_dir($file) ) {
                    Lsmcd_UserMgr_Util::rrmdir($file);
                }
                else {
                    unlink($file);
                }
            }

            if ( !$keepParent )
                rmdir($dir);

            return true;
        }

        return false;
    }

    static function setUseSASL($lines)
    {
        
        foreach ($lines as $line)
        {
            $elements = explode('=', $line, 2);
            if (count($elements) == 1)
                continue;
            $title = ltrim(rtrim($elements[0]));
            if (!strcasecmp($title,self::USE_SASL_TITLE))
            {
                $value = ltrim(rtrim($elements[1]));
                if (!strcasecmp($value,"true"))
                {
                    self::$useSASL = TRUE;
                    break;
                }
            }
        }
        return self::$useSASL;
    }
    
    static function setDataByUser($lines)
    {
        foreach ($lines as $line)
        {
            $elements = explode('=', $line, 2);
            if (count($elements) == 1)
                continue;
            $title = ltrim(rtrim($elements[0]));
            if (!strcasecmp($title,self::DATA_BY_USER_TITLE))
            {
                $value = ltrim(rtrim($elements[1]));
                if (!strcasecmp($value,"true"))
                {
                    self::$dataByUser = TRUE;
                    break;
                }
            }
        }
        return self::$dataByUser;
    }
    
    public static function getServerAddr()
    {
        if (strlen(self::$addr))
            return self::$addr;
        
        if (file_exists(self::NODE_CONF_FILE) == false)
        {
            throw new UserLSMCDException('node.conf not found in expected' . 
                                         ' location: ' . self::NODE_CONF_FILE);
        }
        $lines = file(self::NODE_CONF_FILE);
        
        foreach ($lines as $line)
        {
            $elements = explode('=', $line, 2);
            if (count($elements) == 1)
                continue;
            $title = ltrim(rtrim($elements[0]));
            if (!strcasecmp($title,self::ADDR_TITLE))
            {
                self::$addr = ltrim(rtrim($elements[1]));
                if (!strlen(self::$addr))
                    throw new UserLSMCDException(self::ADDR_TITLE . 
                                                 " not given a value in: " .
                                                 $line . ' in file: ' .
                                                 self::NODE_CONF_FILE);
                if ((strlen(self::$addr > 7)) && 
                    (!strcasecmp(substr(self::$addr,0,6), 'uds://')))
                {
                    self::$addrOnly = substr(self::$addr, 6);
                    self::$port = 0;
                }
                else if ($pos = strpos(self::$addr, ":"))
                {
                    self::$addrOnly = substr(self::$addr, 0, $pos);
                    if ($pos + 1 == strlen(self::$addr))
                        self::$port = 11211;
                    else
                        self::$port = (int)substr(self::$addr, $pos + 1);
                }
                else
                {
                    self::$addrOnly = self::$addr;
                    self::$port = 11211;
                }
                    
                return self::$addr;
            }
        }

        throw new UserLSMCDException(self::ADDR_TITLE . " not found in: " . 
                                     self::NODE_CONF_FILE);
        self::setUseSASL($lines);
        self::setDataByUser($lines);
    }
    
    public static function getServerAddrOnly()
    {
        getServerAddr();
        return self::$addrOnly;
    }
    
    public static function getServerPort()
    {
        getServerAddr();
        return self::$port;
    }

    public static function getDataByUser()
    {
        return(self::$useSASL && self::$dataByUser);
    }
    
    public static function getUseSASL()
    {
        return(self::$useSASL);
    }
    
}
