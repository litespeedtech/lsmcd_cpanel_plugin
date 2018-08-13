<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel;

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
     * @param string  $tag
     * @return null|string
     */
    public static function get_request_var( $tag )
    {
        if ( !isset($_REQUEST[$tag]) )
            return NULL;

        return $_REQUEST[$tag];
    }

    static function setUseSASL($lines)
    {
        foreach ( $lines as $line ) {
            $elements = explode('=', $line, 2);
            if ( count($elements) != 2 )
                continue;
            $title = ltrim(rtrim($elements[0]));
            if ( !strcasecmp($title, self::USE_SASL_TITLE) ) {
                $value = ltrim(rtrim($elements[1]));
                if ( !strcasecmp($value, "true") ) {
                    self::$useSASL = TRUE;
                    break;
                }
            }
        }
        return self::$useSASL;
    }

    static function setDataByUser( $lines )
    {
        foreach ( $lines as $line ) {
            $elements = explode('=', $line, 2);
            if ( count($elements) != 2 )
                continue;
            $title = ltrim(rtrim($elements[0]));
            if ( !strcasecmp($title, self::DATA_BY_USER_TITLE) ) {
                $value = ltrim(rtrim($elements[1]));
                if ( !strcasecmp($value, "true") ) {
                    self::$dataByUser = TRUE;
                    break;
                }
            }
        }
        return self::$dataByUser;
    }

    public static function getServerAddr()
    {
        if ( strlen(self::$addr) )
            return self::$addr;

        if ( file_exists(self::NODE_CONF_FILE) == false ) {
            throw new UserLSMCDException('node.conf not found in expected' .
            ' location: ' . self::NODE_CONF_FILE);
        }
        $lines = file(self::NODE_CONF_FILE);

        foreach ( $lines as $line ) {
            $elements = explode('=', $line, 2);
            if ( count($elements) != 2 )
                continue;
            $title = ltrim(rtrim($elements[0]));
            if ( !strcasecmp($title, self::ADDR_TITLE) ) {
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
                self::setUseSASL($lines);
                self::setDataByUser($lines);
                return self::$addr;
            }
        }

        throw new UserLSMCDException(self::ADDR_TITLE . " not found in: " .
        self::NODE_CONF_FILE);
    }

    public static function getDataByUser()
    {
        self::getServerAddr();
        return(self::$useSASL && self::$dataByUser);
    }

    public static function getUseSASL()
    {
        self::getServerAddr();
        return(self::$useSASL);
    }

}
