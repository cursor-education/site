# cursor.education / environment
**version 0.1**

-----------------------------------------------

## Setup

> ### on local
>
> ```bash
> $ boot2docker up
> $ make APP_PORT=8080 APP_ENV=dev
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