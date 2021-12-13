#!/bin/bash
# SCRIPT: install.sh
# PURPOSE: Install LSMCD User Manager cPanel Plugin
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
    echo "Install missing required directories.  Are you in a cPanel system?"
    exit 1
fi
if [ ! -e /usr/local/cpanel/scripts/install_plugin ]; then
    echo "Install missing required script.  Are you in a cPanel system?"
    exit 1
fi

pushd `dirname "$0"`

echo 'Installing LSMCD (LiteSpeed MemcacheD) User Manager...'

echo 'Verifying requirements...'

if [ ${USER} != 'root' ] 
then
    echo 'Must be run as root'
    exit 1
fi

./install_lsmcd.sh
if [ $? -ne 0 ]
then
    echo 'lsmcd install failed'
    exit 1
fi

USE_PYTHON3=0
whereis pip3|grep -q '/'
if [ $? -eq 0 ] ; then
    echo 'Use python3'
    USE_PYTHON3=1
else
    whereis pip|grep -q '/'
    if [ $? -gt 0 ]
    then
        echo 'pip missing - installing'
        wget https://bootstrap.pypa.io/pip/2.6/get-pip.py -O get-pip.py
        if [ $? -gt 0 ]
        then
            echo 'pip download failed - validate your network connection'
            exit 1
        fi
        python get-pip.py
        if [ $? -gt 0 ]
        then
            echo 'pip install failed'
            exit 1
        fi
    fi
fi

echo ${PWD}/lsmcdsasl.py.raw
if [ ! -f ${PWD}/lsmcdsasl.py.raw ]
then
    echo 'Must be run from directory where the full installation was unpacked'
    exit 1
fi

if [ $USE_PYTHON3 -eq 1 ] ; then
    sed 's~/usr/bin/python~/usr/bin/python3~' lsmcdsasl.py.raw > lsmcdsasl.py 
    chmod 755 lsmcdsasl.py 
else
    cp lsmcdsasl.py.raw lsmcdsasl.py
fi

./lsmcdsasl.py -

if [ $? -gt 0 ] 
then
    echo 'Installing python-binary-memcached'
    if [ $USE_PYTHON3 -eq 1 ] ; then
        pip3 install python-binary-memcached
    else
        pip install python-binary-memcached
    fi
    if [ $? -gt 0 ]
    then
        echo 'Error installing required package - validate your network connection'
        exit 1
    fi

    ./lsmcdsasl.py -
    if [ $? -gt 0 ] 
    then
        echo 'After install, package still missing, check prior messages'
        exit 1
    fi
fi


echo "Copying files..."
if [ "$INSTALL" == "Y" ]; then
    # checks for existing plugin folder and	deletes	if exists
    if [ -d $PLUGIN_DIR ]; then
        rm -rf $PLUGIN_DIR
    fi
    # Create the directory for the plugin
    mkdir -p $PLUGIN_DIR
    # Move all files to plugin directory
    cp -r * ${PLUGIN_DIR}/
fi

if [ "$INSTALL2" == "Y" ]; then
    # checks for existing plugin folder and	deletes	if exists
    if [ -d $PLUGIN_DIR2 ]; then
        rm -rf $PLUGIN_DIR2
    fi
    # Create the directory for the plugin
    mkdir -p $PLUGIN_DIR2
    # Move all files to plugin directory
    cp -r * ${PLUGIN_DIR2}/
fi

# Install the plugin (which also places the png image in the proper location)
if [ "$INSTALL" == "Y" ]; then
    if [ "$INSTALL_PARAM" == "Y" ]; then
        /usr/local/cpanel/scripts/install_plugin ${PLUGIN_DIR}/lsmcd_cpanel_plugin.tar.gz --theme=paper_lantern
    else
        /usr/local/cpanel/scripts/install_plugin ${PLUGIN_DIR}/lsmcd_cpanel_plugin.tar.gz
    fi
fi

if [ "$INSTALL2" == "Y" ]; then
    /usr/local/cpanel/scripts/install_plugin ${PLUGIN_DIR2}/lsmcd_cpanel_plugin.tar.gz --theme=jupiter
fi

echo 'Installing needed Perl module and custom API calls...'
echo ""

cp -f ../lsmcd.pm ${PERL_MODULE_DIR}/

if [ ! -d $API_DIR ] ; then
    mkdir $API_DIR
fi

cp -f ../lsmcdAdminBin* ${API_DIR}/

if [ "$INSTALL" == "Y" ]; then
    chmod -R 644 $PLUGIN_DIR
    find ${PLUGIN_DIR}/ -type d -execdir chmod 755 {} +
    chmod 700 ${PLUGIN_DIR}/*.sh
    chmod 755 ${PLUGIN_DIR}/*.py
fi

if [ "$INSTALL2" == "Y" ]; then
    chmod -R 644 $PLUGIN_DIR2
    find ${PLUGIN_DIR2}/ -type d -execdir chmod 755 {} +
    chmod 700 ${PLUGIN_DIR2}/*.sh
    chmod 755 ${PLUGIN_DIR2}/*.py
fi

chmod 700 ${API_DIR}/lsmcdAdminBin
chmod 644 ${API_DIR}/lsmcdAdminBin.conf
chmod 644 ${PERL_MODULE_DIR}/lsmcd.pm

echo 'Installation for LSMCD (LiteSpeed MemcacheD) User Manager Completed!'
echo ""

popd
