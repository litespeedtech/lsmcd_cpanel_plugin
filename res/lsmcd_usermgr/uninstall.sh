#!/bin/bash
# SCRIPT: uninstall.sh
# PURPOSE: Uninstall LSMCD User Manager cPanel Plugin
# AUTHOR: LiteSpeed Technologies
#

PLUGIN_DIR_BASE='/usr/local/cpanel/base/frontend'
PLUGIN_DIR_THEME="${PLUGIN_DIR_BASE}/paper_lantern"
PLUGIN_DIR_THEME2="${PLUGIN_DIR_BASE}/jupiter"
PLUGIN_DIR="${PLUGIN_DIR_THEME}/lsmcd_usermgr"
PLUGIN_DIR2="${PLUGIN_DIR_THEME2}/lsmcd_usermgr"
PERL_MODULE_DIR='/usr/local/cpanel/Cpanel/API'
API_DIR='/usr/local/cpanel/bin/admin/Lsmcd'
if [ -d "${PLUGIN_DIR_THEME2}" ];
then
    INSTALL2="Y"
    if [ -d "${PLUGIN_DIR_THEME}" ];
    then
        INSTALL_PARAM="Y"
        INSTALL="Y"
    else
        INSTALL="N"
    fi
else
    INSTALL2="N"
    INSTALL_PARAM="N"
fi
if [ "$INSTALL" == "N" -a "$INSTALL2" == "N" ]; then
    echo "Install missing required directories.  Is the software installed here?"
    exit 1
fi
if [ ! -e /usr/local/cpanel/scripts/install_plugin ]; then
    echo "Install missing required script.  Are you in a cPanel system?"
    exit 1
fi

pushd `dirname "$0"`

echo 'Uninstalling LSMCD User Manager...'
echo "";

if [ -e $PERL_MODULE ] ; then
    echo 'Removing custom LiteSpeed Perl module...'
    /bin/rm -f $PERL_MODULE
    echo "";
fi

if [ -d $API_DIR ] ; then
    echo 'Removing custom LiteSpeed API calls...'
    /bin/rm -rf $API_DIR
    echo "";
fi

#Remove cPanel plugin and files
echo 'Removing LSMCD User Manager Manager cPanel Plugin...'
if [ "$INSTALL2" == "Y" ] ; then
    /usr/local/cpanel/scripts/uninstall_plugin ${PLUGIN_DIR2}/lsmcd_cpanel_plugin.tar.gz --theme=jupiter
    /bin/rm -rf $PLUGIN_DIR2
    echo ""
fi
if [ "$INSTALL" == "Y" ] ; then
    if [ "$INSTALL_PARAM" == "Y" ]; then
        /usr/local/cpanel/scripts/uninstall_plugin ${PLUGIN_DIR}/lsmcd_cpanel_plugin.tar.gz --theme=paper_lantern
    else
        /usr/local/cpanel/scripts/uninstall_plugin ${PLUGIN_DIR}/lsmcd_cpanel_plugin.tar.gz
    fi
    /bin/rm -rf $PLUGIN_DIR
    echo ""
fi

echo "Uninstallation finished."
echo ""

popd
