eztimedobjects extension for eZ Publish
========================================

This extension enables users to manage timed pubblication and expiration of the objects inside eZ Publish.

The extension allows users to choose which classes have to be publish through this workflow and which are the date attributes to use to get publish and expire dates.


Prerequisites
-------------

PHP 5.3+  
eZ Publish 4.4+ / 5.0+ (Legacy stack)  
MySQL 5  
See INSTALL for installation details


Settings
--------

* timed_objects.ini: ExpireSettings and PublishSettings contain which class attribute have to be used to manage publications and expirations. They have to be write in <class_identifier>/<class_attribute_identifier> form.


Php classes
-----------

* timedObjectsFunctionCollection: contains some functions to retrieve informations stored in timed_objects.ini


Events
------

* hide object: has to be triggered "after publish" and hides the object if the publication date is in the future
* set expire: has to be triggered "after publish" and stores informations about expiring date in the db
* timed objects: contains both hide object and set expire events (usefull if you need both of them)


Cronjobs
--------

* cronjob part 'timed_objects': unhide objects when publication date is come. Frequence of the cronjob depends on how often you want to publish new objects. Logs in var/log/publish_objects.log
* cronjob part 'expire_objects': hide objects when expire date is come. Frequence depends on how ofter you want to hide objects, but one a day could be fine. Logs in var/log/expire_objects.log
