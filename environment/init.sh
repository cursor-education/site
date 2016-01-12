#!/bin/bash -e

# set application environment
sed "s/SetEnv APP_ENV.*/SetEnv APP_ENV ${APP_ENV}/" -i /etc/httpd/conf.d/site.conf

# restart services
/sbin/service httpd restart

/bin/bash