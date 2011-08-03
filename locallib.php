<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Internal library of functions for module tutorship.
 *
 * All the tutorship specific functions, needed to implement the module
 * logic, are placed here. Never include this file from your lib.php!
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// One important issue is about the connection between lib.php and locallib.php 
// Who has to call the other, how and why? 
// It has to be locallib.php to call lib.php through:

require_once(dirname(__FILE__) . '/lib.php');

// As first line. 
// In this way, if moodle core calls function inside mod/tutorship/lib.php no module 
// specific function will be loaded in memory. 
// If it is tutorship to call a function, it has to require_once('locallib.php'); 
// that, in turn, will require_once('lib.php') so that the module will "see" all its 
// available functions. 

/////////////////////////////////////////////////////////////////////////////////////////////
// +------------+                                                                          //
// |            |                                                                          //
// + Constants: +                                                                          //
// |            |                                                                          //
// +------------+                                                                          //
//                                                                                         //
/////////////////////////////////////////////////////////////////////////////////////////////

define('TUTORSHIP_TIMESLOT_MINUTES', 30);           // Default timeslot length: 30 minutes
define('TUTORSHIP_STARTTIME', 8);                   // Default day start time: 8:00h
define('TUTORSHIP_ENDTIME', 21);                    // Default day end time: 21:00h 
define('TUTORSHIP_MONDAY', 0);                      // Monday
define('TUTORSHIP_TUESDAY', 1);                     // Tuesday
define('TUTORSHIP_WEDNESDAY', 2);                   // Wednesday
define('TUTORSHIP_THURSDAY', 3);                    // Thursday
define('TUTORSHIP_FRIDAY', 4);                      // Friday
define('TUTORSHIP_FIRSTPERIOD_STARTDAY', 20);       // Default first period start day: 20
define('TUTORSHIP_FIRSTPERIOD_STARTMONTH', 9);      // September
define('TUTORSHIP_FIRSTPERIOD_ENDDAY', 14);         // Default frist period end day: 14
define('TUTORSHIP_FIRSTPERIOD_ENDMONTH', 1);        // January
define('TUTORSHIP_FIRSTPERIOD_STARTYEAR', 0);       // Current year
define('TUTORSHIP_FIRSTPERIOD_ENDYEAR', 1);         // Next year
define('TUTORSHIP_SECONDPERIOD_STARTDAY', 1);       // Default second period start day: 1
define('TUTORSHIP_SECONDPERIOD_STARTMONTH', 2);     // February
define('TUTORSHIP_SECONDPERIOD_ENDDAY', 20);        // Default second period end day: 20
define('TUTORSHIP_SECONDPERIOD_ENDMONTH', 5);       // May
define('TUTORSHIP_SECONDPERIOD_STARTYEAR', 1);      // Next year
define('TUTORSHIP_SECONDPERIOD_ENDYEAR', 1);        // Next year
define('TUTORSHIP_THIRDPERIOD_STARTDAY', 15);       // Default third period start day: 15
define('TUTORSHIP_THIRDPERIOD_STARTMONTH', 6);      // June
define('TUTORSHIP_THIRDPERIOD_ENDDAY', 15);         // Default third period end day: 15
define('TUTORSHIP_THIRDPERIOD_ENDMONTH', 7);        // July
define('TUTORSHIP_THIRDPERIOD_STARTYEAR', 1);       // Next year
define('TUTORSHIP_THIRDPERIOD_ENDYEAR', 1);         // Next year

/////////////////////////////////////////////////////////////////////////////////////////////
// +-------------------------------+                                                       //
// |                               |                                                       //
// + Inserting Database functions: +                                                       //
// |                               |                                                       //
// +-------------------------------+                                                       //
//                                                                                         //
// tutorship_insert_timetable(): called from view.php when teacher enablesa timetable slot.//
// tutorship_insert_reserve(): called from view.php when student makes a reservation.      //
// tutorship_insert_teacher_config(): called from view.php to initialize teacher's config. //
// tutorship_insert_default_config(): called from settings.php to initialize module config.// 
// tutorship_insert_periods(): called from lib.php when module is added to a course.       //
// tutorship_insert_timeslots(): called from lib.php when module is added to a course.     //
/////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Inserts a timetable record.
 * Given all record fields containing all the necessary data, this function 
 * will create a new instance and return the id number of the new instance.
 *
 * @param  int $teacherid   The id of the teacher, owner of timetable.
 * @param  int $periodid    The id of the timetable's period.
 * @param  int $timeslotid  The id of the timetable's timeslots.
 * @return int $id          The id of the newly inserted timetable record.
 */
function tutorship_insert_timetable($teacherid, $periodid, $timeslotid) {
    global $DB;

    $timetable               = new stdClass();
    $timetable->teacherid    = $teacherid;
    $timetable->periodid     = $periodid;
    $timetable->timeslotid   = $timeslotid;
    $timetable->timemodified = time();

    $id = $DB->insert_record('tutorship_timetables', $timetable);
    unset($timetable);
    return $id;
}

/**
 * Inserts a reserve record.
 * Given all record fields containing all the necessary data, this function
 * will create a new instance and return the id number of the new instance.
 *
 * @param  int $courseid    The id of the course, owner of timetable.
 * @param  int $studentid   The id of the student's reserve.
 * @param  int $timetableid The id of the timetable's timeslots.
 * @param  int $week        The reserve week number.
 * @return int $id          The id of the newly inserted timetable record.
 */
function tutorship_insert_reserve($courseid, $studentid, $timetableid, $week) {
    global $DB;

    $reserve                = new stdClass();
    $reserve->courseid      = $courseid;
    $reserve->studentid     = $studentid;
    $reserve->timetableid   = $timetableid;
    $reserve->week          = $week;
    $reserve->confirmed     = 0;
    $reserve->timecreated   = time();

    $id = $DB->insert_record('tutorship_reserves', $reserve);
    unset($reserve);
    return $id;
}

/**
 * Inserts a teacher timetable configuration record into tutorship_configs.
 * Given all record fields containing all the necessary data, this function
 * will create a new instance and return the id number of the new instance.
 *
 * @param  int $teacherid       The id of the teacher, owner of configuration.
 * @param  int $autoconfirm     The automatic confirmation, 0-Manual, 1-Automatic.
 * @param  int $notifications   The email notifications, 0-Disable, 1-Enabled.
 * @param  int $maxreserves     The maximum number of reserves per student.
 * @return int $id              The id of the newly inserted configuration record.
 */
function tutorship_insert_teacher_config($teacherid, $autoconfirm, $notifications, $maxreserves, $noreserves) {
    global $DB;

    $configuration                  = new stdClass();
    $configuration->teacherid       = $teacherid;
    $configuration->autoconfirm     = $autoconfirm;
    $configuration->notifications   = $notifications;
    $configuration->maxreserves     = $maxreserves;
    $configuration->noreserves      = $noreserves;
    $configuration->timemodified    = time();

    $id = $DB->insert_record('tutorship_configs', $configuration);
    unset($configuration);
    return $id;
}

/**
 * Sets initial default configuration.
 * Inserts default configuration in config_plugins table,
 * returning true if success. 
 *
 * Todo: Too many repeated elements, try to apply new feature MDL-24413, 
 * in settings.php when implemented, to reduce number of tutorship 
 * configuration fields in config_plugins table and change this function 
 * as consecuence.
 *
 * @see    tutorship_get_config().
 * @param  null.
 * @return boolean Success/Failure.
 */
function tutorship_insert_default_config() {
    global $DB;

    if ($DB->count_records('config_plugins', array('plugin' => 'tutorship')) > 0) {
        return false;
    } else {
        // Time slots configs
        set_config('starttime', TUTORSHIP_STARTTIME, 'tutorship');
        set_config('endtime', TUTORSHIP_ENDTIME, 'tutorship');
        set_config('timeslotlength', TUTORSHIP_TIMESLOT_MINUTES, 'tutorship');

        // First period configs
        set_config('firstperioddesc', get_string('firstperiod', 'tutorship'), 'tutorship');
        set_config('firstperiodstartday', TUTORSHIP_FIRSTPERIOD_STARTDAY, 'tutorship');
        set_config('firstperiodstartmonth', TUTORSHIP_FIRSTPERIOD_STARTMONTH, 'tutorship');
        set_config('firstperiodstartyear', TUTORSHIP_FIRSTPERIOD_STARTYEAR, 'tutorship');
        set_config('firstperiodendday', TUTORSHIP_FIRSTPERIOD_ENDDAY, 'tutorship');
        set_config('firstperiodendmonth', TUTORSHIP_FIRSTPERIOD_ENDMONTH, 'tutorship');
        set_config('firstperiodendyear', TUTORSHIP_FIRSTPERIOD_ENDYEAR, 'tutorship');

        // Second period configs
        set_config('secondperioddesc', get_string('secondperiod', 'tutorship'), 'tutorship');
        set_config('secondperiodstartday', TUTORSHIP_SECONDPERIOD_STARTDAY, 'tutorship');
        set_config('secondperiodstartmonth', TUTORSHIP_SECONDPERIOD_STARTMONTH, 'tutorship');
        set_config('secondperiodstartyear', TUTORSHIP_SECONDPERIOD_STARTYEAR, 'tutorship');
        set_config('secondperiodendday', TUTORSHIP_SECONDPERIOD_ENDDAY, 'tutorship');
        set_config('secondperiodendmonth', TUTORSHIP_SECONDPERIOD_ENDMONTH, 'tutorship');
        set_config('secondperiodendyear', TUTORSHIP_SECONDPERIOD_ENDYEAR, 'tutorship');

        // Third period configs
        set_config('thirdperioddesc', get_string('thirdperiod', 'tutorship'), 'tutorship');
        set_config('thirdperiodstartday', TUTORSHIP_THIRDPERIOD_STARTDAY, 'tutorship');
        set_config('thirdperiodstartmonth', TUTORSHIP_THIRDPERIOD_STARTMONTH, 'tutorship');
        set_config('thirdperiodstartyear', TUTORSHIP_THIRDPERIOD_STARTYEAR, 'tutorship');
        set_config('thirdperiodendday', TUTORSHIP_THIRDPERIOD_ENDDAY, 'tutorship');
        set_config('thirdperiodendmonth', TUTORSHIP_THIRDPERIOD_ENDMONTH, 'tutorship');
        set_config('thirdperiodendyear', TUTORSHIP_THIRDPERIOD_ENDYEAR, 'tutorship');

        // Checks all records
        if ($DB->count_records('config_plugins', array('plugin' => 'tutorship')) == 24) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Inserts the three tutorship periods.
 * Given an object containing all the necessary data,this function
 * will create three new instance and return if the records were
 * inserted or not.
 *
 * @see    tutorship_get_config().
 * @global object.
 * @param  object  $tutorship The object containing all the necessary data.
 * @return boolean Success/Failure.
 */ 
function tutorship_insert_periods($tutorship) {
    global $DB;
   
/// First period 
    // Sets first period start date
    $day        = (int) get_config('tutorship', 'firstperiodstartday');
    $month      = (int) get_config('tutorship', 'firstperiodstartmonth');
    $yearselect = (int) get_config('tutorship', 'firstperiodstartyear');
    $year       = tutorship_to_year($yearselect);
    $firstperiodstart = mktime(0, 0, 0, $month, $day, $year);//, 1); // Summer time

    // Sets first period end date
    $day        = (int) get_config('tutorship', 'firstperiodendday');
    $month      = (int) get_config('tutorship', 'firstperiodendmonth');
    $yearselect = (int) get_config('tutorship', 'firstperiodendyear');
    $year       = tutorship_to_year($yearselect);
    $firstperiodend = mktime(0, 0, 0, $month, $day, $year);

    // Sets first period description
    $firstperioddesc = get_config('tutorship', 'firstperioddesc');
    
/// Second period
    // Sets second period start date    
    $day        = (int) get_config('tutorship', 'secondperiodstartday');
    $month      = (int) get_config('tutorship', 'secondperiodstartmonth');
    $yearselect = (int) get_config('tutorship', 'secondperiodstartyear');
    $year       = tutorship_to_year($yearselect);
    $secondperiodstart = mktime(0, 0, 0, $month, $day, $year);
    
    // Sets second perios end date    
    $day        = (int) get_config('tutorship', 'secondperiodendday');
    $month      = (int) get_config('tutorship', 'secondperiodendmonth');
    $yearselect = (int) get_config('tutorship', 'secondperiodendyear');
    $year       = tutorship_to_year($yearselect);
    $secondperiodend = mktime(0, 0, 0, $month, $day, $year);//, 1); // Summer time
    
    // Sets second period description
    $secondperioddesc = get_config('tutorship', 'secondperioddesc');

/// Third period
    // Sets third period start date
    $day        = (int) get_config('tutorship', 'thirdperiodstartday');
    $month      = (int) get_config('tutorship', 'thirdperiodstartmonth');
    $yearselect = (int) get_config('tutorship', 'thirdperiodstartyear');
    $year       = tutorship_to_year($yearselect);
    $thirdperiodstart = mktime(0, 0, 0, $month, $day, $year);//, 1); // Summer time
        
    // Sets third period end date
    $day        = (int) get_config('tutorship', 'thirdperiodendday');
    $month      = (int) get_config('tutorship', 'thirdperiodendmonth');
    $yearselect = (int) get_config('tutorship', 'thirdperiodendyear');
    $year       = tutorship_to_year($yearselect);
    $thirdperiodend = mktime(0, 0, 0, $month, $day, $year);//, 1); // Summer time
        
    // Sets third period description
    $thirdperioddesc = get_config('tutorship', 'thirdperioddesc');

    // Creates and sets the period objects
    $firstobject                = new stdClass();
    $secondobject               = new stdClass();
    $thirdobject                = new stdClass();
    $firstobject->startdate     = $firstperiodstart;
    $firstobject->enddate       = $firstperiodend;
    $firstobject->description   = $firstperioddesc;
    $secondobject->startdate    = $secondperiodstart;
    $secondobject->enddate      = $secondperiodend;
    $secondobject->description  = $secondperioddesc;
    $thirdobject->startdate     = $thirdperiodstart;
    $thirdobject->enddate       = $thirdperiodend;
    $thirdobject->description   = $thirdperioddesc;

    // Inserts the objects checking if dates are ok
    if (tutorship_validate_period_date($firstobject)) {
        $firstid  = $DB->insert_record('tutorship_periods', $firstobject);
    } else {
        print_error('errperiodvalidation', 'tutorship');
    }
    if (tutorship_validate_period_date($secondobject)) {
        $secondid = $DB->insert_record('tutorship_periods', $secondobject);
    } else {
        print_error('errperiodvalidation', 'tutorship');
    }
    if (tutorship_validate_period_date($thirdobject)) {
        $thirdid  = $DB->insert_record('tutorship_periods', $thirdobject);
    } else {
        print_error('errperiodvalidation', 'tutorship');
    }

    // Don't need the objects any more
    unset($firstobject);
    unset($secondobject);
    unset($thirdobject);
    
    if ($firstid and $secondid and $thirdid) {
        return true;
    } else {
        return false;
    }
}

/**
 * Inserts all possible slots within a week.
 * Given a timeslot length in minutes, this function will create
 * all the possible slots within a week and return if the records 
 * were inserted or not.
 *
 * @param  string $length Time slot length in minutes.
 * @return boolean        Success/Failure.
 */
function tutorship_insert_timeslots($length) {
    global $DB;
    $slotseconds = (int) $length * 60;
    $starttime   = TUTORSHIP_STARTTIME * 60 * 60;
    $endtime     = TUTORSHIP_ENDTIME * 60 * 60;

    if ($slotseconds > 0) {
        // Inserts slots
        for ($i = $starttime; $i <= $endtime; $i += $slotseconds) {
            for ($day = TUTORSHIP_MONDAY; $day <= TUTORSHIP_FRIDAY; $day++) {
                $slot            = new stdClass();
                $slot->day       = $day;
                $slot->starttime = $i;
                $DB->insert_record('tutorship_timeslots', $slot);
                unset($slot);
            }
        }
    }

    // Checks if there are slots
    if ($DB->count_records_select('tutorship_timeslots') > 0) {
        return true;
    } else {
        return false;
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////
// +--------------------------------+                                                      //
// |                                |                                                      //
// + Retrieving Database functions: |                                                      //
// |                                |                                                      //
// +--------------------------------+                                                      //
//                                                                                         //
// tutorship_get_teachers(): called from studentview.php to get course enrolled teachers.  //
// tutorship_get_slot_length(): called from studentview.php and teacherview.php.           //
/////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Gets teachers enrolled within a course.
 *
 * @param  int $courseid Course id.
 * @return object        Teachers enrolled in $courseid.
 */
function tutorship_get_teachers($courseid) {
    global $DB;
    $role     = $DB->get_record('role', array('shortname' => 'editingteacher'));
    $roleid   = (int) $role->id;
    $context  = get_context_instance(CONTEXT_COURSE, $courseid);

    return get_role_users($roleid, $context);
}

/**
 * Returns the time slot length field from the config_plugin table.
 *
 * @param  null.
 * @return int Time slot length in seconds.
 */
function tutorship_get_slot_length() {
    global $DB;
    $timeslotlength = (int) get_config('tutorship', 'timeslotlength');

    if ($timeslotlength) {
        $timeslotlength *= 60;
    } else {
        $timeslotlength = TUTORSHIP_TIMESLOT_MINUTES * 60;
    }

    return $timeslotlength;
}

/////////////////////////////////////////////////////////////////////////////////////////////
// +------------------------------------------------------------+                          //
// |                                                            |                          //
// + Other functions: for converting, checking, creating links. +                          //
// |                                                            |                          //
// +------------------------------------------------------------+                          //
//                                                                                         //
// tutorship_get_slot_link(): called from teacherview.php to print Enable/Disable slots.   //
// tutorship_get_reserve_link(): called from studentview.php to print Reserve/Unreserve.   //
// tutorship_get_week_link(): called from studentview.php and teacherview.php to view week.//
// tutorship_get_reservation_info_link(): called from teacherview.php to view who reserved.//
// tutorship_get_email_link(): called from view.php for teacher's confirmation mails.      //
// tutorship_get_date(): called from locallib.php to get reservation date.                 //
// tutorship_get_reserve_date(): called from view.php to email reservation date string.    //
// tutorship_to_year(): called from locallib.php to get current or next year.              //
// tutorship_has_timetable(): called from studentview.php and teacherview.php to check.    //
// tutorship_get_current_period(): called from studentview and teacherview.php.            //
// tutorship_can_reserve(): called from view.php to check if a student can reserve.        //
// tutorship_validate_period_date(): called from locallib.php to validate the period dates.//
/////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Returns html link with url params and string checking if a slot
 * has already been enable. Enabling a slot means a new record in
 * tutorship_timetables table.
 *
 * @param  int   $timeslotid The id of slot referenced.
 * @param  int   $teacherid  The teacher id making changes.
 * @param  int   $periodid   The id of timetable period.
 * @param  array $urlparams  The url parameters included in the link.
 * @return link  $link       The url link with the slot id as parameter.
 */
function tutorship_get_slot_link($timeslotid, $teacherid, $periodid, $urlparams) {
    global $DB;
    $urlstr                   = '/mod/tutorship/view.php';
    $params                   = $urlparams;
    $params['slotid']         = $timeslotid;
    $params['selectedperiod'] = $periodid;
    $params['action']         = 2; // For enable/disable slot links we want to stay in edit view

    // Gets and checks if timeslotid is enabled

    $timetableconditions = array('teacherid' => $teacherid, 'periodid' => $periodid, 'timeslotid' => $timeslotid);
    $enabled             = $DB->record_exists('tutorship_timetables', $timetableconditions);

    if ($enabled) {
        return html_writer::link(new moodle_url($urlstr, $params), get_string('disable', 'tutorship'));
    } else {
        return html_writer::link(new moodle_url($urlstr, $params), get_string('enable', 'tutorship'));
    }
}

/**
 * Returns html link with url params and string checking if a timetable has
 * already been reserved. Allows reserve and unreserve link and reserved string.
 * Making reservations means a new record in tutorship_reserves table.
 *
 * @param  int   $timetableid   The timetable reserved.
 * @param  int   $studentid     The student id making reservations.
 * @param  int   $courseid      The course id where the student is making the reservations.
 * @param  int   $today         Today's timestamp in function of the selected week.
 * @param  array $urlparams     The url parameters included in the link.   
 * @return mixed $link          The url link with the timetable id as parameter or reserved string.
 */
function tutorship_get_reserve_link($timetableid, $studentid, $courseid, $today, $urlparams) {
    global $DB;
    $urlstr                = '/mod/tutorship/view.php';
    $params                = $urlparams;
    $weeknumber            = date('W', $today);
    $params['timetableid'] = $timetableid;
    //$params['sesskey']     = sesskey();

    // First checks if it is possible to reserve
    // Then checks if timetable week has been reserved on course 

    $reserveconditions = array('timetableid' => $timetableid, 'week' => $weeknumber);
    $reserved          = $DB->record_exists('tutorship_reserves', $reserveconditions);
    $teacherid         = $DB->get_field('tutorship_timetables', 'teacherid', array('id' => $timetableid));
    $cannotreserve     = $DB->get_field('tutorship_configs', 'noreserves', array('teacherid' => $teacherid));

    if ($cannotreserve) {
        return get_string('singletutorship', 'tutorship');
    } else {
        if ($reserved) {

            // Then checks if reservation was made by studentid in a specific week 

            $reserveconditions['studentid'] = $studentid;
            $reserveconditions['week']      = $weeknumber;
            $unreserve = $DB->record_exists('tutorship_reserves', $reserveconditions);

            if ($unreserve) {
                $confirmed    = $DB->get_field('tutorship_reserves', 'confirmed', $reserveconditions);
                $unreservestr = get_string('unreserve', 'tutorship');

                if ($confirmed) {
                    $confirmedstr = get_string('confirmed', 'tutorship');
                    return html_writer::link(new moodle_url($urlstr, $params), $unreservestr).'<br>'.$confirmedstr;
                } else {
                    $notconfirmedstr = get_string('notconfirmed', 'tutorship');
                    return html_writer::link(new moodle_url($urlstr, $params), $unreservestr).'<br>'.$notconfirmedstr;
                }
            } else {
                return get_string('reserved', 'tutorship'); 
            }

        } else {
            return html_writer::link(new moodle_url($urlstr, $params), get_string('reserve', 'tutorship'));
        }
    }
}

/**
 * Returns html link with url params to view current or next week.
 *
 * @param  int   $week      Current-1 or next-2 week.
 * @param  array $urlparams The url parameters included in the link.
 * @return link  $link      The url link with the week as parameter.
 */
function tutorship_get_week_link($week, $urlparams) {
    global $DB;
    $urlstr                = '/mod/tutorship/view.php';
    $params                = $urlparams;
    $params['timetableid'] = 0; // We don't want next week same day to be reserved
    $params['action']      = 1; // For current/next week links we want to stay in view

    if ($week == 1) {           // Current week, so print next week link
        $params['week'] = 2;
        return html_writer::link(new moodle_url($urlstr, $params), get_string('nextweek', 'tutorship'));
    } else {                    // Next week, so print current week link
        $params['week'] = 1;
        return html_writer::link(new moodle_url($urlstr, $params), get_string('currentweek', 'tutorship'));
    }
}

/**
 * Returns html link with url params to view who made the reservation,
 * also returns the cancell or confirm reservation link.
 *
 * @param  int   $courseid    Id of course where a reserve has been requested.
 * @param  int   $timetableid Id of timetable requested.
 * @param  int   $week        Week number.
 * @param  array $urlparams   The url parameters included in the link.
 * @return link  $links       The url link with the reserve id as parameter.
 */
function tutorship_get_reservation_info_link($courseid, $timetableid, $week, $urlparams) {
    global $DB;
    $urlstr               = '/mod/tutorship/view.php';
    $reservationcondition = array('courseid' => $courseid, 'timetableid' => $timetableid, 'week' => $week);

    if ($DB->record_exists('tutorship_reserves', $reservationcondition)) {

        $studentid           = $DB->get_field('tutorship_reserves', 'studentid', $reservationcondition);
        $student             = $DB->get_record('user', array('id' => $studentid));
        $reserveid           = $DB->get_field('tutorship_reserves', 'id', $reservationcondition);
        $linkconditions      = array('id' => $studentid, 'course' => $courseid);
        $linkstr             = $student->username.'<br>'.$student->email;
        $params              = $urlparams;
        $params['action']    = 1;
        $params['cancell']   = 1;
        $params['reserveid'] = $reserveid;

        $conditions = array('courseid' => $courseid, 'timetableid' => $timetableid, 'week' => $week, 'confirmed' => '1');
        $confirmed  = $DB->get_field('tutorship_reserves', 'confirmed', $conditions);

        $output  = html_writer::link(new moodle_url('/user/view.php', $linkconditions), $linkstr);
        $output .= '<br>';

        if (! $confirmed) {
            $confirmparams              = $urlparams;
            $confirmparams['action']    = 1;
            $confirmparams['cancell']   = 0;
            $confirmparams['reserveid'] = $reserveid;
            $output .= html_writer::link(new moodle_url($urlstr, $confirmparams), get_string('confirm', 'tutorship'));
            $output .= '<br>';
        }

        $output .= html_writer::link(new moodle_url($urlstr, $params), get_string('cancel', 'tutorship'));
        
    } else {
        $output = get_string('empty', 'tutorship');
    }

    return $output;
}

/**
 * Returns html link with url params to teacher's timetable view.
 *
 * @param  int  $coursemoduleid The course module id, where action is taking place.
 * @param  int  $week           The requested week number.
 * @return link $link           The html with url params.
 */
function tutorship_get_email_link($coursemoduleid, $week) {
    $params['id']     = $coursemoduleid;
    $params['week']   = $week;
    $params['action'] = 1; // We want to show view when clicking on the link  
    $link             = $CFG->wwwroot.'/mod/tutorship/view.php';
    return html_writer::link(new moodle_url($link, $params), get_string('gotoreservation', 'tutorship'));
}

/**
 * Returns date timestamp from day, week number and year.
 *
 * @see    tutorship_get_reserve_date().
 *
 * @param  int $day         The week day, 0-Monday, 4-Friday.
 * @param  int $weeknumber  The week number, from 1 to 52.
 * @param  int $year        The year.
 * @return int timestamp    The date in Unix timestamp format.
 */
function tutorship_get_date($day, $weeknumber, $year) {
    // Count from '0104' because January 4th is always in week 1
    // (according to ISO 8601).
    $time = strtotime($year.'0104 +'.($weeknumber - 1).' weeks');

    // Get the time of the first day of the week
    $mondaytime = strtotime('-'.(date('w', $time) - 1).' days', $time);

    // Return timestamp
    return strtotime('+'.$day.' days', $mondaytime);
}

/**
 * Returns reservation date string for mails.
 *
 * @param  int    $timetableid
 * @param  int    $studentid
 * @return string $datestr
 */
function tutorship_get_reserve_date($timetableid, $studentid) {
    global $DB;

    $reserveconditions = array('timetableid' => $timetableid, 'studentid' => $studentid);
    $timeslotid = (int) $DB->get_field('tutorship_timetables', 'timeslotid', array('id' => $timetableid));
    $timeslot   = $DB->get_record('tutorship_timeslots', array('id' => $timeslotid));
    $week       = $DB->get_field('tutorship_reserves', 'week', $reserveconditions);

    $year       = date('Y', time());
    $time       = gmdate('H:i', $timeslot->starttime);
    $daynumber  = date('d', tutorship_get_date($timeslot->day, $week, $year));
    $month      = date('m', tutorship_get_date($timeslot->day, $week, $year));

    switch ($timeslot->day) {
        case 0:
            $day = get_string('monday', 'tutorship');
            break;
        case 1:
            $day = get_string('tuesday', 'tutorship');
            break;
        case 2:
            $day = get_string('wednesday', 'tutorship');
            break;
        case 3:
            $day = get_string('thursday', 'tutorship');
            break;
        case 4:
            $day = get_string('friday', 'tutorship');
            break;
    }

    if ($week == date('W', time())) {
        $weekstr = get_string('current', 'tutorship');
    } else {
        $weekstr = get_string('next', 'tutorship');
    }
    
    $datestr  = $weekstr.': <b>'.$day.'</b>, '.get_string('at', 'tutorship').' <b>'.$time.'</b>';
    $datestr .= get_string('hours', 'tutorship').' (';
    $datestr .= get_string('day', 'tutorship').': <b>'.$daynumber.'</b>, '.get_string('month', 'tutorship').': <b>';
    $datestr .= $month.'</b>, '.get_string('year', 'tutorship').': <b>'.$year.'</b>)';

    return $datestr;
}

/**
 * Gets 0 and returns actual year in YYYY format, gets 1
 * and returns next year in YYYY format.
 *
 * @see    tutorship_insert_periods().
 *
 * @param  int $select Year number, 0 is this year and 1 next year.
 * @return boolean     Success/Failure.
 */
function tutorship_to_year($select) {
    $today = time();

    if ($select == 0) {
        return date('Y', $today);
    } else {
        return date('Y', $today) + 1;
    }
}

/**
 * Checks if a teacher has a timetable.
 *
 * @param  int $teacherid Id of the teacher.
 * @param  int $periodid  Id of the timetable period.
 * @return boolean        Success/Failure.
 */
function tutorship_has_timetable($teacherid, $periodid) {
    global $DB;

    if ($DB->count_records('tutorship_timetables', array('teacherid' => $teacherid, 'periodid' => $periodid)) == 0) {
        return false;
    } else {
        return true;
    }
}

/**
 * Returns the current period id by comparing the periods dates with today.
 *
 * @param  int $today   Today's timestamp.
 * @return int $perioid Current period id.
 */
function tutorship_get_current_period($today) {
    global $DB;
    $periodid = 0;
    $periods  = $DB->get_records('tutorship_periods');

    foreach ($periods as $period) {
        if (($period->startdate < $today) and ($period->enddate > $today)) {
            $periodid = $period->id;
        }
    }
    return $periodid;
}

/**
 * If student has made three reserves he can not do any more 
 * reserves for the same timetable.
 *
 * @param  int $timetableid Id of teacher's timetable.
 * @param  int $courseid    Id of course where reserves are taking place.
 * @param  int $studentid   Id of student making reserves.
 * @return boolean          Success/Failure.
 */
function tutorship_can_reserve($timetableid, $courseid, $studentid) {
    global $DB;
    $teacherid   = $DB->get_field('tutorship_timetables', 'teacherid', array('id' => $timetableid)); 
    $maxreserves = $DB->get_field('tutorship_configs', 'maxreserves', array('teacherid' => $teacherid));
    $numreserves = $DB->count_records('tutorship_reserves', array('courseid' => $courseid, 'studentid' => $studentid));

    if ($numreserves < $maxreserves) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if period start date is before end date.
 *
 * @param  object  $periodobject
 * @return boolean Success/Failure.
 */
function tutorship_validate_period_date($periodobject) {
    if ($periodobject->startdate < $periodobject->enddate) {
        return true;
    } else {
        return false;
    }
}
