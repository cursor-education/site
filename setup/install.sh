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
    sudo yum -y install httpd
    sudo rm -rfv /etc/httpd/conf.d/*.conf
    sudo cp -f -v /vagrant/setup/nginx/*.conf /etc/httpd/conf.d/
    sudo service httpd restart

    #
    touch ${TMP_FILE}
    echo "SETUP: yew, finished!"
else
    echo "SETUP: Already provisioned."
fi