Introduction
============

About DimeTimetrackerBundle
---------------------------

The DimeTimetrackerBundle provides entities and a JSON REST API for time-tracking activities. Those activities can be services (such as development, testing, support or consulting) provided for a customer within a project.

Installation
------------

There is no separate repository for the bundle yet. It will be available as of release 1.0. Installation instructions will follow.

By now, please refer to the Dime_ project repository.

Accessing the API
-----------------

The API is available via HTTP as a JSON REST API.

To make the API available, install the DimeTimetrackerBundle and point your webserver to the ``web`` directory of your project.

For short reference on available URIs type for the production environment call

.. code-block:: bash

    ./app console router:debug --env=prod

Further details can be found in this documentation.

Data structure
--------------

services, bla, foo

Writing bundles based on DimeTimetrackerBundle
----------------------------------------------

Extending enities
~~~~~~~~~~~~~~~~~

* https://groups.google.com/group/symfony-users/msg/b85f00ead1bab22e
* https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/doctrine.md
* https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/config/doctrine/User.orm.xml

(work in progress)

Related Bundles
---------------

* DimeInvoiceBundle
* DimeImportBundle

.. _Dime:                           https://github.com/phpugl/CWE2011
