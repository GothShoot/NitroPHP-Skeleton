# NitroPHP-Skeleton

![GitHub release](https://img.shields.io/github/release/PlumeSolution/nitrophp-skeleton.svg?style=popout)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Maintainability](https://api.codeclimate.com/v1/badges/283cc106bd1051620804/maintainability)](https://codeclimate.com/github/PlumeSolution/NitroPHP-Skeleton/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/283cc106bd1051620804/test_coverage)](https://codeclimate.com/github/PlumeSolution/NitroPHP-Skeleton/test_coverage)
![Packagist](https://img.shields.io/packagist/dt/plume-solution/nitrophp-skeleton.svg?style=popout)

NitroPHP skeleton is a skeleton for Symfony enpowered by ReactPHP for ultimate performance.

It uses an great asynchronous server for managing request->response cycle and implementing an asynchronous periodic timer based command launching for recurrent task.

Installation
=
you can install this skeleton by using composer by using :
```CMD
composer create-project plume-solution/nitrophp-skeleton
```
Usage
=
server
-
Server is simply to use, it is a script in bin directory.

For launching, just use : 
```CMD
php bin/react
``` 
It use the kernel in a separate process for handle request ike the original index.php of symfony.

For now it is unable to stream assets, please use for api server only. Don't worry, this is future planned

Please refer to original documentation of symfony for create your first controller :
https://symfony.com/doc/current/page_creation.html

If you need a specific configuration on the server, you can edit /config/package/server.yaml like this :
```yaml
parameters:
  loop:
    server:
      url: 'http://127.0.0.1'
      port: 9000
```

Async periodic service
-
NitroPHP skeleton implementing async symfony command call, this is called from unique instantied kernel of the server
and is more speed than using Cron (for linux) or planified task (for windows)

For configuring an periodic call, edit /config/package/periodic_timer.yaml like this :
```yaml
parameters:
  loop:
    timer:
      mySuperService: #just using for clean config
        input: 'app:my:super:command' #command to call from console
        time: 60 #time in second before call another time the command
```
See Official documentation for creating your first command : https://symfony.com/doc/current/console.html#creating-a-command

Future planned
=
* Unit test
* Assets stream on the server
* Performance improvement
* On update reboot
* Many cool project using NitroPHP :sunglasses:
* and your suggest :grin:
