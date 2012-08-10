Backvendor
==========

Backvendor is an Yii extension that helps developers to speed up development of web services and administration sites based on Yii Framework.

Feature list
------------

1.  Just one console command to create basic structure of your multi application system with common core.
2.  Backend engine that allows creating CRUD pages by setting configurations in Yii style. You do not have to generate CRUD controllers and views for every ActiveRecord model - you generate models and configure custom options in array (e.g. image uploading for image fields). It allows you to change CRUD by changing models only.
3.  JSON API Web service engine
4.  Auto documentation of API version generated as a web page
5.  API versioning. You can make successors of your API versions to support older and newer once at one time.
6.  Engine to create functional tests for your API due to TDD concept based on phpunit.
7.  Deployment script for moving your project to production server using Phing

Quick start
-----------
```bash
 cd /path/to/webroot
 git clone git@github.com:mobidevpublisher/backvendor.git  
 cd backvendor
 php bviic.php createmultiapp --path="/path/to/webroot/my-backvendor-project-folder"
```

Resources
---------

Backvendor at Yii extensions - http://www.yiiframework.com/extension/backvendor
Documentation - http://mobidev.biz/backvendor
Demo project - https://github.com/mobidevpublisher/backvendor-demo