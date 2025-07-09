Developer Documentation
=======================

Working on the project
----------------------

| The ``develop`` branch contains the most current working code of the
  Project and should be considered beta.
| The ``master`` branch is the most current stable release of
  LibreBooking.
| You can automatically keep your fork up to date with the `pull GitHub
  App <https://github.com/apps/pull>`__. which will sync the ``master``
  (hardreset) and ``develop`` (rebase) branches for you. Please commit
  bugfixes / features to a new branch prefixed ``bugfix-``, ``feature-``
  so they can be looked over, and pull requested to the ``develop``
  branch and will eventually end up in a release on ``master``.

Design philosophy
-----------------

| The Model-View-Presenter (MVP) pattern is used to keep a clear
  separation between application logic and presentation logic.
| Page objects act as thin abstraction to the template engine and
  typically have no other logic.
| Presenter objects orchestrate interactions between underlying
  application logic objects and the page.
| This typically includes fetching and transforming data and minimal
  application logic.

.. code:: text

       [page].php should
           - define ROOT_DIR
           - include /Pages/[page]Page.php
           - construct [page]Page and call PageLoad()

-  Each page should have at least one corresponding template in ``/tpl``
-  Each page should have a corresponding class in ``/Pages``
-  Each Page class should have a corresponding presenter class in
   ``/Presenters``

| Logically related code should be grouped in a directory with a
  “namespace.php” file, which requires all files in that directory.
| This simply makes it easier to include necessary file dependencies.

API
---

LibreBooking has a REST-API `API-Documentation <./API.md>`__

User interface
--------------

| `Smarty template
  engine <https://www.smarty.net/docsv2/en/language.basic.syntax.tpl>`__
  is used for all UI presentation.
| Page templates are located in ``/tpl`` and, by default, are cached to
  ``/tpl_c``
| `Fugue Icons <https://p.yusukekamiyamane.com/>`__ are used as the
  default iconset and when needed saved to ``/Web/img/<icon-name>.png``

Tools
-----

| you can easily install these useful php tools needed for development
  with `phive <https://github.com/phar-io/phive#getting-phive>`__ and
  `composer <https://getcomposer.org/download/>`__
| For phive to work properly you also need to install
  `gnupgp <https://www.gnupg.org/download/index.html#binary>`__

Simply run ``composer install-tools`` in the root of the project and all
tools will be available inside the ``/tools`` directory.

`Phing <https://www.phing.info/#docs>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

``composer build`` Builds / Packages a distributable relase inside
``/build`` configured via ``/build.xml``.

`PHPUnit <https://phpunit.readthedocs.io/en/latest/writing-tests-for-phpunit.html>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

All classes should have good unit test coverage. The level of coverage
is up to the developer and should be done when the code is sufficiently
complex. Tests must all succeed for a final release.

`PHPDocumentor <https://docs.phpdoc.org/latest/guide/guides/running-phpdocumentor.html>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Generates automatic documentation based on code comments. You can
customize the output by copying ``/phpdoc.dist.xml`` to ``/phpdoc.xml``
which now takes precedence and isn’t tracked with git. The documentation
will be generated in ``/.phpdoc/build``.

`PHP-CS-Fixer <https://github.com/FriendsOfPhp/PHP-CS-Fixer#usage>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

| ``composer lint`` and ``composer fix``
| lints (just warnings) and fixes (changes files) code formating to
  [PSR-12]

Application Structure
---------------------

.. code:: text

       /config                     Application configuration
       /Controls                   All reusable page control objects
       /database_schema            Base and upgrade sql scripts

       /Domain                     Domain specific entities, repositories, services, etc
           /Access                    Domain repository objects (db abstraction layer)
           /Events                    Domain events
           /Values                    Value objects

       /Jobs                       All tasks that run on a schedule (cron tasks, for example)
       /lang                       Translations
       /lib                        All application supporting objects

           /Application               Most of the subdirectories are self-explanatory
               /Admin                  Application administration logic
               /Attributes             Custom attributes
               /Authentication         Authentication (login)
               /Authorization          Authorization (access)
               /Reporting              Logic for generating and running reports
               /Reservation
                   /Notification           Post-reservation logic
                   /Persistence            Creating, updating, deleting reservation information
                   /Validation             Pre-reservation logic
               /Schedule                   Schedule population
               /User

           /Common                     Non-specific shared objects (localization, dates, smarty, etc)
           /Config                     Configuration read/write
           /Database                   Database access and abstractions
           /Email                      Email abstractions and services
           /external                   Third party libraries
           /FileSystem                 Filesystem, I/O object
           /Graphics                   Creating and manipulating images
           /Server                     Web server abstractions (sessions, cookies, etc)
           /WebService                 Objects for creating and invoking web services (the API)

       /Pages                      Page binding and work flow logic
       /phing-tasks                Custom build tasks that hook into phing
       /plugins                    Contains subdirectories for each type of plugin
       /Presenters                 Application logic, page population
       /tests                      All unit tests
       /tpl                        All Smarty page and control templates
       /tpl_c                      Default Smarty template cache directory
       /uploads                    Default file upload directory
       /Web                        All user facing pages
           /scripts                    All application related javascript files
       /WebServices                The LibreBooking API

Database
--------

|Entity Relationship Diagram| you can open ``/doc/LibreBooking.mbw``
with `MySQL Workbench <https://www.mysql.com/products/workbench/>`__ to
edit/update this ERD

.. |Entity Relationship Diagram| image:: ./ERD.svg
