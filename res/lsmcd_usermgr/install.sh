#!/bin/sh
# SCRIPT: install.sh
# PURPOSE: Install LSMCD User Manager cPanel Plugin
# AUTHOR: LiteSpeed Technologies
#

PLUGIN_DIR='/usr/local/cpanel/base/frontend/paper_lantern/lsmcd_usermgr'
PERL_MODULE_DIR='/usr/local/cpanel/Cpanel/API'
API_DIR='/usr/local/cpanel/bin/admin/Lsmcd'

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

whereis pip|grep -q '/'
if [ $? -gt 0 ]
then
    echo 'pip missing - installing'
    wget https://bootstrap.pypa.io/2.6/get-pip.py -O get-pip.py
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

echo ${PWD}/lsmcdsasl.py
if [ ! -x ${PWD}/lsmcdsasl.py ]
then
    echo 'Must be run from directory where the full installation was unpacked'
    exit 1
fi

./lsmcdsasl.py -

if [ $? -gt 0 ] 
then
    echo 'Installing python-binary-memcached'
    pip install python-binary-memcached
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
# checks for existing plugin folder and	deletes	if exists
if [ -d $PLUGIN_DIR ]
then
    rm -rf $PLUGIN_DIR
fi

# Create the directory for the plugin
mkdir -p $PLUGIN_DIR

# Move all files to plugin directory
cp -r * ${PLUGIN_DIR}/

# Install the plugin (which also places the png image in the proper location)
/usr/local/cpanel/scripts/install_plugin ${PLUGIN_DIR}/lsmcd_cpanel_plugin.tar.gz

echo 'Installing needed Perl module and custom API calls...'
echo ""

cp -f ../lsmcd.pm ${PERL_MODULE_DIR}/

if [ ! -d $API_DIR ] ; then
    mkdir $API_DIR
fi

cp -f ../lsmcdAdminBin* ${API_DIR}/

chmod -R 644 $PLUGIN_DIR
find ${PLUGIN_DIR}/ -type d -execdir chmod 755 {} +
chmod 700 ${PLUGIN_DIR}/*.sh
chmod 755 ${PLUGIN_DIR}/*.py


chmod 700 ${API_DIR}/lsmcdAdminBin
chmod 644 ${API_DIR}/lsmcdAdminBin.conf
chmod 644 ${PERL_MODULE_DIR}/lsmcd.pm

echo 'Installation for LSMCD (LiteSpeed MemcacheD) User Manager Completed!'
echo ""

popd
