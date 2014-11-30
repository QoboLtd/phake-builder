phake-builder
=============

[![Build Status](https://travis-ci.org/QoboLtd/phake-builder.svg?branch=master)](https://travis-ci.org/QoboLtd/phake-builder)

A set of build and deploy files, based on [jaz303/phake](https://github.com/jaz303/phake).

If you find this useful, have a look at other project templates, based on this one:

* [project-template-wordpress](https://github.com/QoboLtd/project-template-wordpress) - an automated setup for WordPress CMS
* [project-template-magento](https://github.com/QoboLtd/project-template-magento) - an automated setup for Magento Ecommerce Platform

Install
-------

Install with composer as so:

```
{
    "require": {
      "qobo/phake-builder": "~1.0"
    }
}
```

Usage
-----

In the root of your project, create a ```Phakefile``` with the following:

```
<?php
require_once 'vendor/qobo/phake-builder/Phakefile';
?>
```

*NOTE* : the vendor Phakefile is not autoloaded, as it would be useless 
and annoying in every part of your project, except for the build 
configuration.  So, include it manually only in this one place.

Now you can see the liset of available build targets by running:

```
$ ./vendor/bin/phake -T
```

The output should look something like this:

```
(in /path/to/your/project)
archive:compress           Create ZIP or TAR archive
archive:extract            Extract ZIP or TAR archive
builder:hello              Print welcome message
builder:init               Initialize builder configuration
composer:install           Install composer dependencies
composer:update            Update composer dependencies
default                    Default target
dotenv:create              Create .env file
dotenv:delete              Delete .env file
dotenv:reload              Reload settings from .env
file:chgrp                 Change group ownership on path
file:chmod                 Change permissions on path
file:chown                 Change user ownership on path
file:download              Download file from URL
file:link                  Create symbolic link
file:mkdir                 Create folder
file:mv                    Rename file or folder
file:process               Process template file
file:rm                    Recursively remove file or folder
file:touch                 Create empty file or update timestamp of existing
git:changelog              Git changelog
git:checkout               Git checkout
git:pull                   Git pull
git:push                   Git push
mysql:access-file-allow    Allow file operation
mysql:access-file-deny     Deny file operation
mysql:access-grant         Grant access
mysql:access-revoke        Revoke access
mysql:connect              Test MySQL database connection
mysql:database-create      Create database
mysql:database-drop        Drop database
mysql:database-import      Import database
mysql:find-replace         Find and replace across the database
system:service-restart     Restart system service
system:service-start       Start system service
system:service-stop        Stop system service
```

You can run any of these targets like so:

```
$ ./vendor/bin/phake mysql:connect
```

Most of the included build targets require some parameters.  You can provide
those parameters via the ```.env``` file.  For starters, you can just copy
the provided example file.  You can even do so with phake-builder:

```
$ ./vendor/bin/phake dotenv:create
```

Alternatively, you can pass parameters from the command line. For example:

```
$ ./vendor/bin/phake mysql:connect DB_HOST=localhost DB_USER=root
```

Now you are ready to create your own build targets.  To keep these visually
separate in the list of all, it is recommended that you do so in the 'app'
group.  Here is an example of such target for your own Phakefile:

```
<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

  desc("This is a test");
  task('install', 'db:connect', function() {
    printSuccess('Awesome!');
  });

});
?>
```

Now you can install your app with:

```
$ ./vendor/bin/phake app:install
```

For more information read the documentation for [Phake](https://github.com/jaz303/phake) 
and [phpdotenv](https://github.com/vlucas/phpdotenv).
