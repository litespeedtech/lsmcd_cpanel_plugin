<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\Lsc\Context;

use \LsmcdUserPanel\Lsc\UserUtil;
use LsmcdUserPanel\Lsc\UserLogger;
use LsmcdUserPanel\Lsc\UserLSMCDException;

/**
 * UserContext is a singleton
 */
class UserContext
{

    /**
     * @var UserContextOption
     */
    protected $options;

    /**
     * @var null|string
     */
    protected $flagContent;

    /**
     * @var null|string
     */
    protected $readmeContent;

    /**
     *
     * @var null|UserContext
     */
    protected static $instance;

    /**
     *
     * @param UserContextOption  $contextOption
     */
    protected function __construct( UserContextOption $contextOption )
    {
        $this->options = $contextOption;
    }

    /**
     *
     * @return UserContextOption
     */
    public static function getOption()
    {
        return self::me(true)->options;
    }

    /**
     *
     * @param UserContextOption  $contextOption
     * @throws UserLSMCDException
     */
    public static function initialize( UserContextOption $contextOption )
    {
        if ( self::$instance != null ) {
            /**
             * Do not allow, UserContext already initialized.
             */
            throw new UserLSMCDException('Context cannot be initialized twice.',
                    UserLSMCDException::E_PROGRAM);
        }

        self::$instance = new self($contextOption);
        UserLogger::Initialize($contextOption);
    }

    /**
     *
     * @return UserContext
     * @throws UserLSMCDException
     */
    protected static function me()
    {
        if ( self::$instance == null ) {
            /**
             * Do not allow, must initialize first.
             */
            throw new UserLSMCDException('Uninitialized context.',
                    UserLSMCDException::E_NON_FATAL);
        }

        return self::$instance;
    }

    /**
     *
     * @return int
     */
    public static function getScanDepth()
    {
        return self::me()->options->getScanDepth();
    }

    /**
     *
     * @return string
     */
    public static function getFlagFileContent()
    {
        $m = self::me();

        if ( $m->flagContent == null ) {
            $m->flagContent = <<<CONTENT
This file was created by LiteSpeed Web Cache Manager

When this file exists, your LiteSpeed Cache plugin for WordPress will NOT be affected
by Mass Enable/Disable operations performed through LiteSpeed Web Cache Manager.

Please DO NOT ATTEMPT to remove this file unless you understand the above.

CONTENT;
        }

        return $m->flagContent;
    }

}
