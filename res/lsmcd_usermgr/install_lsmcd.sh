#!/bin/sh
pushd `dirname "$0"`
echo 'Installs lsmcd if not already installed'
if [ $USER != 'root' ]
then
   echo 'Must be root to install'
   exit 1
fi
if [ -d /usr/local/lsmcd ]
then
   echo 'lsmcd is already installed'
   exit 0
fi
yum -y groupinstall "Development Tools"
if [ $? -ne 0 ]
then
   echo 'groupinstall failed'
   exit 1
fi
yum -y install autoconf automake zlib-devel openssl-devel expat-devel pcre-devel libmemcached-devel cyrus-sasl
if [ $? -ne 0 ]
then
   echo 'package install failed'
   exit 1
fi
cd /root
if [ -d lsmcd ]
then
   echo 'Removing prior /root/lsmcd directory' 
   rm -rf lsmcd
fi
git clone https://github.com/litespeedtech/lsmcd.git
if [ $? -ne 0 ]
then
   echo 'git clone failed'
   exit 1
fi
cd lsmcd
git checkout SASL_Hash_User
echo "Running configure"
./fixtimestamp.sh
./configure CFLAGS=" -O3" CXXFLAGS=" -O3"
if [ $? -ne 0 ]
then
   echo 'configure failed'
   exit 1
fi
echo 'Running compile'
make
if [ $? -ne 0 ]
then
   echo 'compile failed'
   exit 1
fi
echo 'Running install'
make install
if [ $? -ne 0 ]
then
   echo 'install failed'
   exit 1
fi
echo 'Starting service'
service lsmcd start
if [ $? -ne 0 ]
then
   echo 'service start failed'
   exit 1
fi

echo "Install complete and successful.  Service now running"

popd
exit 0

