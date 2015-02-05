#!/usr/bin/env bash
TMP_FILE='/tmp/vagrant-setup-state.keep'

if [ ! -f ${TMP_FILE} ]; then
    echo "SETUP: started.."

    # stop firewall
    sudo service iptables stop
    sudo chkconfig iptables off

    # install midnight-commander
    sudo yum -y install mc

    # install & configure apache
    sudo yum -y install httpd php
    sudo rm -rfv /etc/httpd/conf.d/*.conf
    sudo cp -f -v /vagrant/setup/nginx/*.conf /etc/httpd/conf.d/
    sudo service httpd restart

    # install nodejs & npm & dependencies
    sudo yum -y install nodejs
    curl -L http://npmjs.org/install.sh | sudo sh
    sudo npm install -g coffee-script
    sudo npm install -g grunt
    sudo npm install -g grunt-cli
    cd /var/shared/site && sudo npm install && cd /tmp

    #
    touch ${TMP_FILE}
    echo "SETUP: yew, finished!"
else
    echo "SETUP: already provisioned."
fi

chmod -R 0777 /vagrant/logs/
chmod -R 0777 /var/shared/site/web/

sudo service httpd restart