FROM centos:6
MAINTAINER itspoma <itspoma@gmail.com>

ENV APP_ENV dev

RUN yum clean all \
 && yum install -y git curl mc bc \
 && yum install -y gcc-c++ make \
 && yum install -y tar \
 && yum install -y epel-release


# php 5.4
RUN rpm -Uvh http://repo.webtatic.com/yum/el6/latest.rpm \
 && yum install -y php55w php55w-pdo php55w-mysql php55w-intl php55w-soap

# configure the php.ini
RUN echo "" >> /etc/php.ini \
 && sed 's/;date.timezone.*/date.timezone = Europe\/Kiev/' -i /etc/php.ini \
 && sed 's/^display_errors.*/display_errors = On/' -i /etc/php.ini \
 && sed 's/;error_log.*/error_log = \/shared\/logs\/php_errors.log/' -i /etc/php.ini \
 && sed 's/^display_startup_errors.*/display_startup_errors = On/' -i /etc/php.ini \
 && sed 's/^variables_order.*/variables_order = "EGPCS"/' -i /etc/php.ini


# apache2
RUN yum install -y httpd \
 && rm -rfv /etc/httpd/conf.d/*.conf

# configure the httpd
RUN sed 's/#ServerName.*/ServerName site/' -i /etc/httpd/conf/httpd.conf \
 && sed 's/#EnableSendfile.*/EnableSendfile off/' -i /etc/httpd/conf/httpd.conf

# put vhost config for httpd
ADD ./environment/httpd/*.conf /etc/httpd/conf.d/


# nodejs
RUN curl --silent --location https://rpm.nodesource.com/setup | bash - \
 && yum install -y nodejs \
 && npm -g install npm@latest

# install nodejs & npm & dependencies
RUN npm install -g inherits \
 && npm install -g coffee-script \
 && npm install -g grunt \
 && npm install -g grunt-cli

# composer
RUN curl -sS https://getcomposer.org/installer | php \
 && php composer.phar clearcache


WORKDIR /shared

CMD ["/bin/bash", "/shared/environment/init.sh"]