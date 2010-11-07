Open Power Classes 2.x-namespaced
=================================

Open Power Classes is a collection of utility classes for PHP and other foundation
libraries. Currently, it consists of four components:

+ `Opc\Paginator` - the general-purpose paginator integrated with Open Power Template
+ `Opc\Translate` - generic translation interface implementation that features INTL
    extensions and `MessageFormatter` Unicode-compatible class.
+ `Opc\Visit` - request information collector. Rebuilds the default `$_SERVER` data to
	the more useful form.
+ `Opc\View\Cache` - default caching system for OPT templates.

Version status
------------------

This is a modification of OPC 2.1 branch which has been rewritten to PHP 5.3 namespaces
in order to simplify the migration to Open Power Libs 3 in the near future.

Help
----

+ User manuals are available [here](http://static.invenzzia.org/docs/).
+ [Tutorials](http://www.invenzzia.org/en/resources/articles)
+ [Project wiki](http://wiki.invenzzia.org)
+ [Discussion board](http://forums.invenzzia.org)
+ [Bugtracker](http://bugs.invenzzia.org)


Authors and license
-------------------

Open Power Template - Copyright (c) Invenzzia Group 2008-2010

The code was written by:

+ Tomasz "Zyx" Jedrzejewski - design, programming, documentation
+ Jacek "eXtreme" Jedrzejewski - testing, minor improvements, debug console
+ Amadeusz "megaweb" Starzykiewicz - additional programming.

Contributors: krecik

The library is available under the terms of [New BSD License](http://www.invenzzia.org/license/new-bsd).