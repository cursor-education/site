#!/usr/bin/env bash
TMP_FILE='/tmp/vagrant-setup-state.keep'

if [ ! -f ${TMP_FILE} ]; then
    echo "SETUP: started.."

    # stop firewall
    sudo service iptables stop
    sudo chkconfig iptables off

    # install utils
    sudo yum -y install mc curl git

    # install php
    sudo rpm -Uvh http://mirror.webtatic.com/yum/el6/latest.rpm
    sudo yum -y install php54w
    php -v

    # install & configure apache
    sudo yum -y install httpd
    sudo rm -rfv /etc/httpd/conf.d/*.conf
    sudo cp -f -v /vagrant/setup/nginx/*.conf /etc/httpd/conf.d/
    sudo service httpd restart

    # install nodejs & npm & dependencies
    sudo yum -y install nodejs
    curl -L http://npmjs.org/install.sh | sudo sh
    sudo npm install -g coffee-script
    sudo npm install -g grunt
    sudo npm install -g grunt-cli
    cd /var/shared/site && npm i && cd /tmp

    #
    touch ${TMP_FILE}
    echo "SETUP: yew, finished!"
else
    echo "SETUP: already provisioned."
fi

chmod -R 0777 /vagrant/logs/
chmod -R 0777 /var/shared/site/web/

sudo service httpd restart