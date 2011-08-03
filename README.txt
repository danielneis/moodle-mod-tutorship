----------------------------------------------------------
TUTORSHIP: A schedule tutoring sessions module for Moodle,
where teachers and students can make appointments for
tutoring interviews within a timetable view. 
----------------------------------------------------------

Please read this Tutorship Activity Module file README.txt, 
which normally takes only a few minutes.

===============================
1. *** QUICK INSTALL (1/12) ***
===============================

Here is a basic outline of the installation process:

1) DO NOT PANIC!

2) Unzip the archive and read this file.

3) Move the files into your Moodle mod Web directory in moodle/mod.

4) Visit Settings > Site Administration > Notifications, you should find
   the module's tables successfully created.

5) Go to Site Administration > Modules > Activities > Manage activities,
   and you should find that the tutorship has been added to the list of
   installed modules.

6) Make your global settings adjustments before adding any instance.

7) You may now proceed to add a new instance of tutorship to a course.

=================================
2. *** QUICK UNINSTALL (2/12) ***
=================================

Here is a basic outline of the uninstallation process:

1) DO YOU REALLY WANT TO UNINSTALL?

2) Go to Site Administration > Modules > Activities > Manage activities,
   and delete your Activity module.

3) You may now proceed to remove the module files from moodle/mod.

==============================
3. *** INTRODUCTION (3/12) ***
==============================

This is a contributed activity module you can download, through Moodle's 
modules and plugins distribution section on the official Web page, without 
any warranty, see chapter 10 and 12 for further details.

Once the module is succesfully installed, following the installation 
instrucctions on chapter 1, the database will have six more tables (see 
chapter 7 for further details) at your Moodle Web site, but don't panic, 
no effect will have on the other Moodle tables.

As well as if you decide to uninstall the module, the uninstall process 
will have no effect on the other Moodle tables.

The module allows the teacher to schedule a tutoring timetable, allows the 
student to see and request an available tutoring time slot through the 
timetable, allows the site administrator to do module global configuration. 

For deeper information see the 4, 5 and 6 chapters.

==========================
4. *** SYNOPSIS (4/12) *** 
==========================

1) If you are a Student:
    
  * Go to a course and click on the instance name, could be some name like
    "Tutoring schedule timetable", but if you are not sure, you can see the 
    module logo at tutorship/pix/ path, see chapter 7 for further details.

  * Select a teacher from the list and current week timetable will be shown. 

  * You can choose to see current or next week available slots.

  * If teacher has enabled student tutorship session request, then you can
    request any available slot, up to a maximum number of reservations,
    stablished by the teacher.

  * You can also cancell your request.

  * You will if your request was confirmed by teacher or not.

2) If you are a Teacher:
    
  * Click on "Turn editing on" and select "Tutorship" from the "Add an 
    activity" list. Type the name for your instance that will be shown to 
    everybody or leave the "Tutoring schedule timetable" default name. Now 
    it is ready to use.

  * Go to any course you are enrolled and click on the module instance name, 
    you will see your tutoring timetable as students see it, or empty if no 
    timetable has been created yet.

  * To create a timetable go to edit and select the period you want your
    timetable be related to.

  * You can have three different timetables, one per period.

  * You can enable or disable any time slot you want to offer as a tutoring
    session, it will be shown on your tutoring timetable.

  * You can adjust your settings common to all timetables.

3) If you are an Administrator:

  * Click on Administration > Modules > Activities > Tutorship and edit the 
    module global configuration, taking in mind not to have any instance added
    to any course. Global configuration should be edited before any module
    instance is added to any course, otherwise negative consecuences will take
    place.

=============================
5. *** DESCRIPTION (5/12) ***
=============================

Tutorship is a Moodle module to administrate and schedule tutoring hours, it
allows students to make appointments with teachers for tutoring interviews, 
from a configurable schedule time slots timetable. Teachers can design their 
timetable in order to offer the students a tutorship timetable, so that they 
can see and make appointments by requesting any available timetable slot.

There are three different views depending on who is viewing the module: 
teacher, student and administrator.

Teachers can manage up to three different timetables, adjust the settings that
will be applied for all timetables, and thay can do it within any course the
teacher is enrolled in.

Students can request or cancell time slots from the teacher's timetable,
where current week is show by default, but next week is also available for
viewing.

Administrators can adjust initial module settings before any teacher adds any
instance to any course.

It was first developed under Moodle version 2.0 in November 2010. 

==========================
6. *** FEATURES (6/12) ***
==========================

1) General features:

  * Email notifications.

  * Supported English and Spanish languages.

  * Help icons on the Tutorship's elements like header or name field.

  * Display error notifications to inform user, in case an error 
    took place.

  * Records user activity to enable audit.

  * Compatibility from 2.0 upwards.

2) Student features:

  * Students can choose the teacher they want to see timetable from.

  * Students can click on the time slot they want to reserve, and they
    can cancell their requests. 

  * Current week and next week time slots view from Tutorship timetable.

  * Can reserve slots from tomorrow to next week's friday, up to a maximum
    of 4 reserves (depends on teacher's timetable configuration).

  * Students will receive in their email box a confirmation or cancellation
    of their request, and they can also see confirmation from the timetable
    view.

3) Teacher features:

  * Current week and next week time slots view from Tutorship timetable.

  * Can confirm or cancell reservations from the timetable view.

  * Can edit the timetable and can save up to three timetables.

  * Can adjust settings to all timetables, like: enable/disable sending mail 
    to teacher, send automatic confirmations to students requests, set the 
    number of reservation request per student (up to 4 as maximum), enable or
    disable student requests (students will not have the reserve link).

4) Administrator features:

  * Global settings can be adjusted from the module settings page at
    Administration > Modules > Activities > Tutorship.

===================================
7. *** PACKAGE STRUCTURE (7/12) ***
===================================

This is the package directory structure, where you can see all the files
included in this package:

* tutorship/
   |_ view.php           - Prints a general view of tutorship.
   |_ teacherview.php    - Prints a particular teacher view of tutorship.
   |_ studentview.php    - Prints a particular student view of tutorship.
   |_ locallib.php       - Internal library of functions for module tutorship.
   |_ lib.php            - Library of interface functions for tutorship.
   |_ index.php          - Prints all instances provided in a course.
   |_ version.php        - Defines the version of tutorship.
   |_ mod_form.php       - The instance configuration form.
   |_ settings.php       - The module configuration variables.
   |_ COPYING.txt        - A copy of the GNU General Public License.
   |_ README.txt         - This info text file.
   |_ lang/en/
   |	|_ tutorship.php - English strings for tutorship.
   |_ lang/es/
   |	|_ tutorship.php - Spanish strings for tutorship.
   |_ pix/
   |	|_ icon.gif      - An instance icon.
   |_ db/
        |_ upgrade.php   - The upgrade tacking file.
        |_ install.xml   - The data base schema definition file.
        |_ access.php    - The tutorship capabilities definition file.
        |_ log.php       - Defines log events.
        |_ uninstall.php - Executed after the uninstall process.

This are the module tables, where all logic data is kept:

  +--------------------------+-----------------------------------------------+
  | mdl_tutorship            | The main instance table.                      |
  +--------------------------+-----------------------------------------------+
  | mdl_tutorship_timetables | The teacher's timetable with their timeslots. |
  +--------------------------+-----------------------------------------------+
  | mdl_tutorship_reserves   | The student's reserved timeslots.             |
  +--------------------------+-----------------------------------------------+
  | mdl_tutorship_configs    | The teacher's timetable configurations.       |
  +--------------------------+-----------------------------------------------+
  | mdl_tutorship_periods    | The timetable's periods.                      |
  +--------------------------+-----------------------------------------------+
  | mdl_tutorship_timeslots  | All the possible timeslots within a week.     |
  +--------------------------+-----------------------------------------------+

===========================
8. *** TODO LIST (8/12) ***
===========================

* Redesign the view structure creating classes to render objects.

* There are too many repeated select elements in settings.php.
  Change day, month and year period select fields to a new date selector.
  If the MDL-24413 new feature is implemented, it will help for this new
  implementation of select elements in the settings.php file.

* Improve languages strings.

* Implement the capacity of adding more periods dinamically in settings.php.

* Implement a form at teacherview.php, where the teacher can send a 
  confirmation adding text written on that form.

* Allow global configuration changing at any time, even if there are module
  instances on courses. It means that updates to all course instance will take
  place.

* Implement sesskey in forms, may be using the function is_post_with_sesskey().

========================
9. *** AUTHOR (9/12) ***
========================

Tutorship module and this manual was written and packaged by

    Alejandro Michavila

=============================
10. *** COPYRIGHT (10/12) ***
=============================

    Tutoring schedule Moodle module, Tutorship
    Copyright © 2010 ownwards. Alejandro Michavila, Tutorship Developer

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

    You are free to change and redistribute it with NO WARRANTY, to the 
    extent permitted by law. Permission to use, copy, modify, and distribute 
    this software and its documentation for any  purpose  and  without fee is 
    hereby granted, provided that the above copyright notice appear in all 
    copies and that both the copyright notice and this permission notice 
    appear in supporting documentation.

    Please read your local GNU General Public License copy that comes 
    along with tutorship in the COPYING.txt file or visit:

   <http://www.gnu.org/licenses/gpl.html>

================================
11. *** AUTHOR NOTES (11/12) ***
================================

* If you want to contribute by expanding, modifying, adapting, improving or 
  correcting this work, please GO AHEAD, but follow the Moodle standard and
  coding style. 

* I'm not a PHP developer, in fact I'm a really rotten developer, so I 
  earnestly recommend to redesign this module, please take a look at the todo 
  list.

* It's ok to end the php files whithout end tag, taken from Moodle Docs:
  PHP works much better when there is no ?> tag because there can not be 
  any trailing whitespace problems.

* There're may be plenty of bugs and language mistakes.
  Sorry for my English and my coding, I wish I've had more time to develop 
  this module.

============================
12. *** SEE ALSO (12/12) ***
============================

For further information:

* See the INSTALL DOCUMENTATION:

   <http://docs.moodle.org/en/Installing_contributed_modules_or_plugins>

* See the MODULE DATABASE ENTRY:

   <http://moodle.org/mod/data/view.php?d=13&rid=4347>

* See the OFICIAL DOCUMENTATION:

   <http://docs.moodle.org/en/Tutorship_module> 

* Browse through the CODE:

   <http://cvs.moodle.org/contrib/plugins/mod/tutorship>

* Download the LATEST VERSION:

   <http://download.moodle.org/download.php/plugins/mod/tutorship.zip>

* Report a BUG:
    
   <http://tracker.moodle.org/browse/CONTRIB/component/10763>

* For any question CONTACT ME:

   <http://moodle.org/user/view.php?id=1116685>

* For Spanish speakers MY BLOG:

   <http://almipa.blogspot.com>
