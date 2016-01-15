# cursor.education / environment
**version 0.1**

-----------------------------------------------

## Setup

> ### on local
>
> ```bash
> $ boot2docker up
> $ make set-dev all
> $ open http://{site-address}:8080/
> ```
> 
> ### on stage:
> ```bash
> $ curl -sSL https://get.docker.com/ | sh
> $ docker -v
> $ sudo service docker restart
> $ git clone https://github.com/itspoma/cursor-education-site site/
> $ cd site/ && make
> $ open http://{site-address}/
> ```

[]()

## Configure forwarding multiple docker containers on same port

```bash
$ cat /etc/httpd/conf.d/docker.conf
NameVirtualHost *:80

<VirtualHost *:80>
    ServerName site.cursor.education
    ProxyPass / http://localhost:8080/
    ProxyPassReverse / http://localhost:8080/
</VirtualHost>

<VirtualHost *:80>
    ServerName mantisbt.cursor.education
    ProxyPass / http://localhost:1234/
    ProxyPassReverse / http://localhost:1234/
</VirtualHost>
```

[]()
