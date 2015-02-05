# cursor.education / environment
**version 0.1**

-----------------------------------------------

## Setup

> ### on local environment
>
> install vagrant dependencies
>
>     $ vagrant plugin install vagrant-hostmanager
>
>
> initialize vagrant virtual box
>
>     $ vagrant destroy --force   # to reset vagrant image
>     $ vagrant up                # to setup virtual machine
>     $ vagrant ssh               # to enter inside of vm
>
>
> initialize app dependencies
>
>     vm$ cd /vagrant/shared/site/
>     vm$ curl -sS https://getcomposer.org/installer | php
>     vm$ php composer.phar install
>
>
> and navigate to http://local.cursor.education/
>
>

[]()