# HRIS
====

Human Resource Information System(HRIS) is a software that enables organisations to collect, validate, analyse and present raw and statistical human resource information
for reporting, analysis and decision making. It is a generic software tool that allows customization to fit organization specific requirements, its built with open meta-data models
and flexible and customizable user interface that allows user to adjust the system to peform, behave, look and feel based on organisation's specific requirements without
the need for software development.

HRIS Software is a loosely coupled modular web based software bundle that is built with free and open source PHP >= 5.3 Web Frameworks.
Among the frameworks Involves
* Symfony2 PHP Web Development Framework
* Doctrine is PHP Object Relational Mapper used for persistence abstraction
* PHPUnit Testing Framework for testing overall system behaviors and functions
* Twig Template Engine for presentation layer
* Composer Package manager

## Why Would you want to choose HRIS
* Faster response and less greedy on system resources
  HRIS was conceived from the start to be fast and to favor performance, its based on Symfony2 Framework that is about
  3 times faster than ZendFramework 1.10 while taking 2 times less memory.
* Unlimited flexiblity through customization
  Whatever your needs are, HRIS will be adaptable. Its customizable capacity makes it entirely configurable, with less required to be done.
  Its also extensible, based on framework dependency injection and event dispatchers, more modules can be pluged in with simplicity.
  HRIS is distributed under GNU General Public Licence which does allow modification and extension of existing features.
* Ease of use through intuitive, user-friendly interfaces
  Simple and Flexible user interfaces allows performing advanced tasks simply and intuitively, which allows begginers to the system to quickly
  feel at ease with HRIS
* Stable and sustainable
  The system is in-built with self system tests, that ensures shipped system behaves and performs what is needed with guaranteed compatibility between versions
* Inteoperable
  Through Web APIs, HRIS can be interoperated by third party applications through the powerful RESTful APIs

## Introduction to HRIS
------------------------
The HRIS software was first developed with basic functionalities in 2009 by University of Dar es salaam to address Human Resource for Health software need,
to track human resource in the health sector, while used by health secretaries at district level for day to day tasks and for reporting, it can also be used
to analyse and help in decision making at higher administrative levels.

HRIS has currently been rolled out in the whole of Tanzania, it's operational and used in all 131 districts in 21 Regions,
17 Regional hospitals, 8 Referral Hospitals of Tanzania, 118 Health Traning institutions and Ministry departments.
A rapidly increasing demand for a flexible, faster and user friendly Human Resource Information tool arose a need for re-implementation of the software
under open source copy-left licence.


## System Administration
------------------------
Users with System administrative priviledge can customize the system to reflect the organisational hierarchy of the company, information to be collected
by an organisation, set of validation constraints that collected dat must adhere to, control of access of data and different system parts, and other system
customizations that affect the system at large.

## Accessing the System
------------------------
System is in-built with secured Authentication and Authorization system that requires users to be authenticated to use any service that requires a user to be loged in
and require user to be authorised as cleared to use some of priviledged system services, such as right to control data to be collected.

## Managing Employee Records
----------------------------
System uses Records management module to control collection, human resource information such as personnel particulars, in service training and employee's history.

## Modules and Features
------------------------
HRIS Version 3 Consist of Several modules designed to collect, validate, report and analyse human resource information, the modules consist
* Forms Management
    Creates and manages forms used as data collection tools along with the information collected with them
* Organisationunits Management
	Creates and manages organisation units, it's properties as in ownership, type, and other attributes, like level in a hierarchy, etc.
* Data Quality Management
	Creates and manages validation constraints that are used to test and ensure quality data is being collected
* Records Management
	Creates and manages collection, storage and retrival of Human resource information in a faster and secure way.

## Properties of HRIS
---------------------
1. Web enabled
2. Platform independent
3. Runs on all major web browsers
4. Runs on most relational databases
5. Licenced under open source licence terms
6. Works Off-line
7. Loosely coupled with Bundle/Modular approach
8. Interoperable
9. Internationalized

## Development
--------------
### Knowledge base
* [Git Source code management](http://git-scm.com/)
* [Composer](http://getcomposer.org/)
* [Symfony2](http://symfony.com/)
* [PHPUnit](http://www.phpunit.de/manual/3.7/en/index.html)
* [Doctrine](http://www.doctrine-project.org/)
* [Twig](http://twig.sensiolabs.org/)
* [Internationalization](http://symfony.com/doc/current/book/translation.html)

## Eclipse Tricks
-----------------
Eclipse is the favoured IDE for HRIS development, the following are development plugins
1. Yedit ( Eclipse plugin for YAML Files - http://code.google.com/p/yedit/)
	update site: http://dadacoalition.org/yedit
2. Symfony Eclipse Plugin ( https://github.com/pulse00/Symfony-2-Eclipse-Plugin )
	update site: http://p2.dubture.com
	
## Database Setup
-----------------
[System source code can be downloaded from github](https://github.com/mukulu/hris)
* Configuration file can be found from hris/app/config/parameters
parameters:
	database_driver: pdo_pgsql
	database_host: %databasehost%
	database_port: %portnumber%
	database_name: %databasename%
	database_user: %databaseuser%
	database_password: %userpassowrd%
	mailer_transport: smtp
	mailer_host: %mailerhost%
	mailer_user: null
	mailer_password: null
	locale: en
	secret: 39057bef4fc167ce3b7103e487939ba41
	database_path: null

## Performance tuning
	File php.ini can be used to tweak performance of the system 