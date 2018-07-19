<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\Lsc;

use \LsmcdUserPanel\Lsc\UserLogEntry;
use \LsmcdUserPanel\Lsc\UserLSMCDException;
use LsmcdUserPanel\Lsc\Context\UserContextOption;

/**
 * UserLogger is a singleton
 */
class UserLogger
{

    const L_NONE = 0;
    const L_ERROR = 1;
    const L_WARN = 2;
    const L_NOTICE = 3;
    const L_INFO = 4;
    const L_VERBOSE = 5;
    const L_DEBUG = 9;
    const UI_INFO = 0;
    const UI_SUCC = 1;
    const UI_ERR = 2;
    const UI_WARN = 3;

    /**
     * @var null|UserLogger
     */
    private static $instance;

    /**
     * @var int  Highest log message level allowed to be logged. Set to the
     *            higher value between $this->logFileLvl and $this->logEchoLvl.
     */
    private $logLvl;

    /**
     * @var string  File that log messages will be written to (if writable).
     */
    private $logFile;

    /**
     * @var int  Highest log message level allowed to be written to the log
     *           file.
     */
    private $logFileLvl;

    /**
     * @var boolean  When set to true, log messages will not be written to the
     *               log file until this Logger object is destroyed.
     */
    private $bufferedWrite;

    /**
     * @var null|UserLogEntry[]  Stores created LogEntry objects when
     *                           $this->bufferedWrite or $this->bufferedEcho
     *                           are set to true.
     */
    private $msgQueue = null;

    /**
     * @var int  Highest log message level allowed to echoed.
     */
    private $logEchoLvl;

    /**
     * @var boolean  When set to true, echoing of log messages is suppressed.
     */
    private $bufferedEcho;

    /**
     * @var null|string[][]  Leveraged by Control Panel GUI to store and
     *                       retrieve display messages.
     */
    private $uiMsgs = null;

    /**
     *
     * @param UserContextOption  $ctxOption
     */
    private function __construct( UserContextOption $ctxOption )
    {
        $this->logFile = $ctxOption->getLogFile();
        $this->logFileLvl = $ctxOption->getLogFileLvl();
        $this->bufferedWrite = $ctxOption->isBufferedWrite();
        $this->logEchoLvl = $ctxOption->getLogEchoLvl();
        $this->bufferedEcho = $ctxOption->isBufferedEcho();

        if ( $this->bufferedEcho || $this->bufferedWrite ) {
            $this->msgQueue = array();
        }

        if ( $ctxOption instanceof UserPanelContextOption ) {
            $this->uiMsgs = array(
                self::UI_INFO => array(),
                self::UI_SUCC => array(),
                self::UI_ERR => array(),
                self::UI_WARN => array()
            );
        }

        if ( $this->logEchoLvl >= $this->logFileLvl ) {
            $logLvl = $this->logEchoLvl;
        }
        else {
            $logLvl = $this->logFileLvl;
        }

        $this->logLvl = $logLvl;
    }

    public function __destruct()
    {
        if ( $this->bufferedWrite ) {
            $this->writeToFile($this->msgQueue);
        }
    }

    /**
     *
     * @param UserContextOption  $contextOption
     * @throws UserLSMCDException
     */
    public static function Initialize( UserContextOption $contextOption )
    {
        if ( self::$instance != null ) {
            throw new UserLSMCDException('Logger cannot be initialized twice.',
                    UserLSMCDException::E_PROGRAM);
        }

        self::$instance = new self($contextOption);
    }

    /**
     *
     * @param int  $type
     * @return string[]
     */
    public static function getUiMsgs( $type )
    {
        $m = self::me();

        $ret = array();

        if ( $m->uiMsgs != null ) {

            switch ($type) {
                case self::UI_INFO:
                case self::UI_SUCC:
                case self::UI_ERR:
                case self::UI_WARN:
                    $ret = $m->uiMsgs[$type];
                //no default
            }
        }

        return $ret;
    }

    /**
     * Processes any buffered output, writing it to the log file, echoing it
     * out, or both.
     */
    public static function processBuffer()
    {
        $clear = false;

        $m = self::me();

        if ( $m->bufferedWrite ) {
            $m->writeToFile($m->msgQueue);
            $clear = true;
        }

        if ( $m->bufferedEcho ) {
            $m->echoEntries($m->msgQueue);
            $clear = true;
        }

        if ( $clear ) {
            $m->msgQueue = array();
        }
    }

    /**
     *
     * @param string  $msg
     * @param int     $type
     */
    public static function addUiMsg( $msg, $type )
    {
        $m = self::me();

        $uiTypes = array(
            self::UI_INFO,
            self::UI_ERR,
            self::UI_SUCC,
            self::UI_WARN
        );

        if ( $m->uiMsgs != null && in_array($type, $uiTypes) ) {
            $m->uiMsgs[$type][] = $msg;
        }
    }

    /**
     *
     * @param string  $msg
     * @param int     $lvl
     */
    public static function logMsg( $msg, $lvl )
    {
        $m = self::me();

        if ( $m->logLvl >= $lvl ) {
            $m->log($msg, $lvl);
        }
    }

    /**
     *
     * @param string  $msg
     */
    public static function debug( $msg )
    {
        self::logMsg($msg, self::L_DEBUG);
    }

    /**
     *
     * @param string  $msg
     */
    public static function info( $msg )
    {
        self::logMsg($msg, self::L_INFO);
    }

    /**
     *
     * @param string  $msg
     */
    public static function notice( $msg )
    {
        self::logMsg($msg, self::L_NOTICE);
    }

    /**
     *
     * @param string  $msg
     */
    public static function verbose( $msg )
    {
        self::logMsg($msg, self::L_VERBOSE);
    }

    /**
     *
     * @return UserLogger
     * @throws UserLSMCDException
     */
    private static function me()
    {
        if ( self::$instance == null ) {
            throw new UserLSMCDException('Logger Uninitialized.',
                    UserLSMCDException::E_PROGRAM);
        }

        return self::$instance;
    }

    /**
     *
     * @param string  $msg
     * @param int     $lvl
     */
    protected function log( $msg, $lvl )
    {
        $entry = new UserLogEntry($msg, $lvl);

        if ( $this->msgQueue !== null ) {
            $this->msgQueue[] = $entry;
        }

        if ( !$this->bufferedWrite ) {
            $this->writeToFile(array( $entry ));
        }

        if ( !$this->bufferedEcho ) {
            $this->echoEntries(array( $entry ));
        }
    }

    /**
     *
     * @param UserLogEntry[]  $entries
     */
    protected function writeToFile( $entries )
    {
        $content = '';

        foreach ( $entries as $e ) {
            $content .= $e->getOutput($this->logFileLvl);
        }

        if ( $content != '' ) {

            if ( $this->logFile ) {
                file_put_contents($this->logFile, $content,
                        FILE_APPEND | LOCK_EX);
            }
            else {
                error_log($content);
            }
        }
    }

    /**
     *
     * @param UserLogEntry[]  $entries
     */
    protected function echoEntries( $entries )
    {
        foreach ( $entries as $entry ) {

            if ( !$this->bufferedEcho &&
                    ($msg = $entry->getOutput($this->logEchoLvl)) !== '' ) {

                echo $msg;
            }
        }
    }

    /**
     *
     * @param int  $lvl
     * @return string
     */
    public static function getLvlDescr( $lvl )
    {
        switch ($lvl) {
            case self::L_ERROR:
                return 'ERROR';
            case self::L_WARN:
                return 'WARN';
            case self::L_NOTICE:
                return 'NOTICE';
            case self::L_INFO:
                return 'INFO';
            case self::L_VERBOSE:
                return 'DETAIL';
            case self::L_DEBUG:
                return 'DEBUG';
            default:
                /**
                 * Do silently.
                 */
                return '';
        }
    }

}
