<?php

/* * *********************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * *******************************************
 */

spl_autoload_register(function($class) {
    /**
     * project-specific namespace prefix
     */
    $prefix = 'LsmcdUserPanel\\';

    /**
     * base directory for the namespace prefix
     */
    $base_dir = __DIR__ . '/core/';

    $len = strlen($prefix);

    if ( strncmp($prefix, $class, $len) !== 0 ) {
        /**
         * Class use the namespace prefix,
         * move to the next registered autoloader.
         */
        return;
    }

    $relative_class_name = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class_name) . '.php';

    if ( file_exists($file) ) {
        require $file;
    }
});
