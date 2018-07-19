#!/bin/sh
# SCRIPT: uninstall.sh
# PURPOSE: Uninstall LSMCD User Manager cPanel Plugin
# AUTHOR: LiteSpeed Technologies
#

PLUGIN_DIR='/usr/local/cpanel/base/frontend/paper_lantern/lsmcd_usermgr'
PERL_MODULE='/usr/local/cpanel/Cpanel/API/lsmcd.pm'
API_DIR='/usr/local/cpanel/bin/admin/Lsmcd'

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
if [ -e $CPANEL_PLUGIN_DIR ] ; then
    echo 'Removing LSMCD User Manager Manager cPanel Plugin...'
    /usr/local/cpanel/scripts/uninstall_plugin ${PLUGIN_DIR}/lsmcd_user_mgr.tar.gz
    /bin/rm -rf $PLUGIN_DIR
    echo ""
fi

echo "Uninstallation finished."
echo ""

popd