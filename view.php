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
 * Prints a particular instance of tutorship.
 *
 * The tutorship instance view that shows the teacher's tutoring
 * timetable configuration with time slots for student to reserve.
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Includes all the required files
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Common parametres from POST or GET
$id                 = optional_param('id', 0, PARAM_INT);               // course_module ID, or
$t                  = optional_param('t', 0, PARAM_INT);                // tutorship instance ID
$selectedteacher    = optional_param('selectedteacher', 0, PARAM_INT);  // selected teacher from studentview
$action             = optional_param('action', 1, PARAM_INT);           // teacher action (view or edit) from teacherview
$selectedperiod     = optional_param('selectedperiod', 1, PARAM_INT);   // selected period from teacherview
$maxreserves        = optional_param('maxreserves', 0, PARAM_INT);      // max number of reserves from teacherview
$autoconfirm        = optional_param('autoconfirm', 0, PARAM_INT);      // automatic confirmation from teacherview
$notify             = optional_param('notify', 1, PARAM_INT);           // send notifications from teacherview
$noreserves         = optional_param('noreserves', 0, PARAM_INT);       // disable student reserves from teacherview
$slotid             = optional_param('slotid', 0, PARAM_INT);           // teacher slotid from teacherview
$week               = optional_param('week', 1, PARAM_INT);             // current/next week selection from studentview
$timetableid        = optional_param('timetableid', 0, PARAM_INT);      // timetable id reserved from studentview
$reserveid          = optional_param('reserveid', 0, PARAM_INT);        // reserve id confirmed or cancel from teacherview
$cancell            = optional_param('cancell', 0, PARAM_INT);          // cancell reservation from teacherview

$teacherconditions  = array('teacherid' => $USER->id);                  // This will be use a few times

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Retrieves necessary information from database
if ($id) {
    if (! $cm = get_coursemodule_from_id('tutorship', $id, 0, false, MUST_EXIST)) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST)) {
        print_error('coursemisconf');
    }
    if (! $tutorship = $DB->get_record('tutorship', array('id' => $cm->instance), '*', MUST_EXIST)) {
        print_error('invalidcoursemodule');
    }
} else if ($t) {
    if (! $tutorship = $DB->get_record('tutorship', array('id' => $t), '*', MUST_EXIST)) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record('course', array('id' => $tutorship->course), '*', MUST_EXIST)) {
        print_error('coursemisconf');
    }
    if (! $cm = get_coursemodule_from_instance('tutorship', $tutorship->id, $course->id, false, MUST_EXIST)) {
        print_error('invalidcoursemodule');
    }
} else {
    print_error('missingparameter');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Security login priviledges, context and records user activity
require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/tutorship:view', $context);
add_to_log($course->id, 'tutorship', 'view', 'view.php?id='.$cm->id, $tutorship->name, $cm->id);
if (has_capability('mod/tutorship:update', $context)) { // Only teachers can do this
// Todo - implement sesskey in forms, may be using function is_post_with_sesskey().

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Enables/Disables time slot within a timetable
if ($slotid) {
    if ($selectedperiod) {
        $slotstableconditions = array('teacherid' => $USER->id, 'periodid' => $selectedperiod, 'timeslotid' => $slotid);
    } else {
        $slotstableconditions = array('teacherid' => $USER->id, 'timeslotid' => $slotid);
    }

    // Records user activity
    add_to_log($course->id, 'tutorship', 'editslot', 'teacherview.php?id='.$cm->id, $tutorship->name, $cm->id);

    // If there is a time slot in timetable then delete, otherwise create it.
    if ($DB->record_exists('tutorship_timetables', $slotstableconditions)) {

        // Sends out mail to inform students who reserved those time slots and removes reserves
        $teachertimetableid = $DB->get_field('tutorship_timetables', 'id', $slotstableconditions);
        $reserves = $DB->get_records('tutorship_reserves', array('timetableid' => $teachertimetableid));
        if ($reserves) {
            $site     = get_site();
            $subject  = format_string($site->shortname).': '.format_string($course->shortname).': ';
            $subject .= get_string('reservationcancelled', 'tutorship');
            $message  = '<p>'.format_string($site->fullname).': '.format_string($course->fullname).': ';
            $message .= get_string('modulename', 'tutorship').'.</p>';
            $message .= get_string('reservationcancelledtxt', 'tutorship');
            $message .= '<b>'.format_string(fullname($USER)).'</b>.<br>';
            $message .= get_string('reservationdetails', 'tutorship');
            foreach ($reserves as $reserve) {
                $message .= tutorship_get_reserve_date($DB->get_field('tutorship_reserves', 'timetableid',
                                                       array('id' => $reserve->id)), $reserve->studentid);
                $to = $DB->get_record('user', array('id' => $reserve->studentid));    
                // Deletes timetable reserves
                if ($DB->delete_records('tutorship_reserves', array('id' => $reserve->id))) {
                    // Email to student
                    if (! email_to_user($to, $USER, $subject, null, $message)) {
                        print_error('erremail', 'tutorship');
                    }   
                } else {
                    print_error('errcancelconfirm', 'tutorship');
                }
            }
        }

        // Deletes time slot from timetable
        if (! $DB->delete_records('tutorship_timetables', $slotstableconditions)) {
            print_error('errslotdelete', 'tutorship');
        }

    } else {
        if (! tutorship_insert_timetable($USER->id, $selectedperiod, $slotid)) {
            print_error('errtimetable', 'tutorship');
        }
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Updates maximum number of reserves per student
if ($maxreserves) {
    $configuration = $DB->get_record('tutorship_configs', $teacherconditions);

    // In case the teacher changes the maximum number of reserves
    if (! $configuration) { // If there was not any configuration added yet, adds
        if (! tutorship_insert_teacher_config($USER->id, '0', '1', $maxreserves, '0')) {
            print_error('errconfig', 'tutorship');
        }
    } else { // updates
        if ($configuration->maxreserves != $maxreserves) {
            if ($DB->set_field('tutorship_configs', 'maxreserves', $maxreserves, $teacherconditions)) {
                // Records user activity
                add_to_log($course->id, 'tutorship', 'edit maxreserves', 'teacherview.php?id='.$cm->id, 
                           $tutorship->name, $cm->id);
            } else {
                print_error('errmaxreserves', 'tutorship');
            }
        }
    }
} else if ($DB->record_exists('tutorship_configs', $teacherconditions)) {
    $maxreserves = (int) $DB->get_field('tutorship_configs', 'maxreserves', $teacherconditions);
} else {
    $maxreserves = 3;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Enables/Disables automatic confirmation
if (isset($autoconfirm)) {
    $slotid = 0;
    $configuration = $DB->get_record('tutorship_configs', $teacherconditions);

    // In case the teacher changes the automatic confirmation
    if (! $configuration) { // If there was not any configuration added yet, adds
        if (! tutorship_insert_teacher_config($USER->id, $autoconfirm, '1', '3', '0')) {
            print_error('errconfig', 'tutorship');
        }
    } else { // updates
        if ($configuration->autoconfirm != $autoconfirm) {
            if ($DB->set_field('tutorship_configs', 'autoconfirm', $autoconfirm, $teacherconditions)) {
                // Records user activity
                add_to_log($course->id, 'tutorship', 'edit confirmation', 'teacherview.php?id='.$cm->id, 
                           $tutorship->name, $cm->id);        
            } else {
                print_error('errconfirm', 'tutorship');
            }
        }
    }
} else if ($DB->record_exists('tutorship_configs', $teacherconditions)) {
    $autoconfirm = (int) $DB->get_field('tutorship_configs', 'autoconfirm', $teacherconditions);
} else {
    $autoconfirm = 0;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Enables/Disables email notifications
if (isset($notify)) {   
    $configuration = $DB->get_record('tutorship_configs', $teacherconditions);

    // In case the teacher changes the notifications
    if (! $configuration) { // If there was not any configuration added yet, adds
        if (! tutorship_insert_teacher_config($USER->id, '0', $notify, '3', '0')) {
            print_error('errconfig', 'tutorship');
        }
    } else { // updates
        if ($configuration->notifications != $notify) {
            if ($DB->set_field('tutorship_configs', 'notifications', $notify, $teacherconditions)) {
                // Records user activity
                add_to_log($course->id, 'tutorship', 'edit notifications', 'teacherview.php?id='.$cm->id, 
                           $tutorship->name, $cm->id);
            } else {
                print_error('errnotify', 'tutorship');
            }
        }
    }
} else if ($DB->record_exists('tutorship_configs', $teacherconditions)) {
    $notifications = (int) $DB->get_field('tutorship_configs', 'notifications', $teacherconditions);
} else {
    $notifications = 1;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Enables/Disables reservations
if (isset($noreserves)) {
    $configuration = $DB->get_record('tutorship_configs', $teacherconditions);

    // In case the teacher changes the noreserves
    if (! $configuration) { // If there was not any configuration added yet, adds
        if (! tutorship_insert_teacher_config($USER->id, '0', $notify, '3', $noreserves)) {
            print_error('errconfig', 'tutorship');
        }
    } else { // updates
        if ($configuration->noreserves != $noreserves) {
            if ($DB->set_field('tutorship_configs', 'noreserves', $noreserves, $teacherconditions)) {
                // Records user activity
                add_to_log($course->id, 'tutorship', 'edit noreserves', 'teacherview.php?id='.$cm->id,
                           $tutorship->name, $cm->id);
            } else {
                print_error('errnoreserves', 'tutorship');
            }
        }
    }
} else if ($DB->record_exists('tutorship_configs', $teacherconditions)) {
    $noreserves = (int) $DB->get_field('tutorship_configs', 'noreserves', $teacherconditions);
} else {
    $noreserves = 0;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Confirm or cancel reservations
if ($reserveid) {
    $site        = get_site();
    $studentid   = $DB->get_field('tutorship_reserves', 'studentid', array('id' => $reserveid));
    $to          = $DB->get_record('user', array('id' => $studentid));
    $subject     = format_string($site->shortname).': '.format_string($course->shortname).': ';
    $message     = '<p>'.format_string($site->fullname).': '.format_string($course->fullname).': ';
    $message    .= get_string('modulename', 'tutorship').'.</p>';

    if ($cancell) {
        // Cancell and clear reservation
        if ($DB->record_exists('tutorship_reserves', array('id' => $reserveid))) {

            // Records user activity
            add_to_log($course->id, 'tutorship', 'reserve cancell', 'studentview.php?id='.$cm->id, $tutorship->name, 
                       $cm->id);

            // Email settings
            $subject .= get_string('reservationcancelled', 'tutorship');
            $message .= get_string('reservationcancelledtxt', 'tutorship');
            $message .= ' <b>'.format_string(fullname($USER)).'</b>.<br>';
            $message .= get_string('reservationdetails', 'tutorship');
            $message .= tutorship_get_reserve_date($DB->get_field('tutorship_reserves', 'timetableid',
                                                                  array('id' => $reserveid)), $studentid);

            // Teacher has cancelled a reservation request
            if ($DB->delete_records('tutorship_reserves', array('id' => $reserveid))) {

                // Email to student
                if (! email_to_user($to, $USER, $subject, null, $message)) {
                    print_error('erremail', 'tutorship');
                }

            } else {
                print_error('errcancelconfirm', 'tutorship');
            }

        }
    } else {
        // Confirm reservation
        if ($DB->record_exists('tutorship_reserves', array('id' => $reserveid, 'confirmed' => '0'))) {

            // Records user activity
            add_to_log($course->id, 'tutorship', 'reserve confirm', 'studentview.php?id='.$cm->id, $tutorship->name, 
                       $cm->id);

            // Teacher has confirmed a reservation request
            if ($DB->set_field('tutorship_reserves', 'confirmed', 1, array('id' => $reserveid))) {

                // Email to student
                $subject .= get_string('reservationconfirmed', 'tutorship');
                $message .= get_string('reservationconfirmedtxt', 'tutorship');
                $message .= ' <b>'.format_string(fullname($USER)).'</b>.<br>';
                $message .= get_string('reservationdetails', 'tutorship'); 
                $message .= tutorship_get_reserve_date($DB->get_field('tutorship_reserves', 'timetableid',
                                                                      array('id' => $reserveid)), $studentid);
                if (! email_to_user ($to, $USER, $subject, null, $message)) {
                    print_error('erremail', 'tutorship');
                }
    
            } else {
                print_error('errconfirmation', 'tutorship');
            }

        // Cancel and clear reservation
        } else if ($DB->record_exists('tutorship_reserves', array('id' => $reserveid, 'confirmed' => '1'))) {

            // Records user activity
            add_to_log($course->id, 'tutorship', 'reserve cancell', 'studentview.php?id='.$cm->id, $tutorship->name, 
                       $cm->id);

            // Email settings
            $subject .= get_string('reservationcancelled', 'tutorship');
            $message .= get_string('reservationcancelledtxt', 'tutorship');
            $message .= ' <b>'.format_string(fullname($USER)).'</b>.<br>';
            $message .= get_string('reservationdetails', 'tutorship');
            $message .= tutorship_get_reserve_date($DB->get_field('tutorship_reserves', 'timetableid',
                                                                  array('id' => $reserveid)), $studentid);

            // Teacher has cancelled a reservation request
            if ($DB->delete_records('tutorship_reserves', array('id' => $reserveid))) {

                // Email to student
                if (! email_to_user ($to, $USER, $subject, null, $message)) {
                    print_error('erremail', 'tutorship');
                }
        
            } else {
                print_error('errcancelconfirm', 'tutorship');
            }

        }
    }
}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Establishes current week or next week time
if ($week == 1) {        // Current week
    $today = time();
} else if ($week == 2) { // Next week
    $today = time() + (7 * 24 * 60 * 60);
}
if (has_capability('mod/tutorship:reserve', $context)) { // Only students can do this
// Todo - implement sesskey in forms, may be using function is_post_with_sesskey().

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Reserves/Unreserves
if ($timetableid) { 
    $reachedmaxreserves     = false;
    $weeknumber             = date('W', $today);
    $teacherid              = $DB->get_field('tutorship_timetables', 'teacherid', array('id' => $timetableid));
    $reservationconditions  = array('courseid' => $course->id, 'studentid' => $USER->id, 'timetableid' => $timetableid,
                                    'week' => $weeknumber);
    $site                   = get_site();
    $to                     = $DB->get_record('user', array('id' => $teacherid));
    $subject                = format_string($site->shortname).': '.format_string($course->shortname).': ';
    $autoconfirmsubject     = $subject;
    $message                = '<p>'.format_string($site->fullname).': '.format_string($course->fullname).': ';
    $message               .= get_string('modulename', 'tutorship').'.</p>';

    // If reservation was made by student, delete it, otherwise create it
    // Cancell reservation
    if ($DB->record_exists('tutorship_reserves', $reservationconditions)) {
        if ($DB->delete_records('tutorship_reserves', $reservationconditions)) {

            // Records user activity
            add_to_log($course->id, 'tutorship', 'unreserve', 'studentview.php?id='.$cm->id, $tutorship->name, $cm->id);

            // If teacher has notifications enabled, email to teacher
            if ($DB->get_field('tutorship_configs', 'notifications', array('teacherid' => $teacherid))) {

                // Student has cancelled a reservation, email to teacher
                $subject .= get_string('reservationcancelled', 'tutorship');
                $message .= get_string('reservationcancelledtxt', 'tutorship');
                $message .= ' <b>'.format_string(fullname($USER)).'</b>.<br>';
                $message .= get_string('reservationdetails', 'tutorship');
                $message .= tutorship_get_reserve_date($timetableid, $USER->id);
                if (! email_to_user($to, $USER, $subject, null, $message)) {
                    print_error('erremail', 'tutorship');
                }

            }

        } else {
            print_error('errunreserve', 'tutorship');
        }

    // Make reservation
    } else if (tutorship_can_reserve($timetableid, $course->id, $USER->id)) { // Has made all possible reservations?
        if (tutorship_insert_reserve($course->id, $USER->id, $timetableid, $weeknumber)) {

            // Records user activity
            add_to_log($course->id, 'tutorship', 'reserve', 'studentview.php?id='.$cm->id, $tutorship->name, $cm->id);

            // If has notifications configuration enabled, email to teacher
            if ($DB->get_field('tutorship_configs', 'notifications', array('teacherid' => $teacherid))) {

                // Student has made a reservation, email to teacher
                $subject     .= get_string('reservationrequest', 'tutorship');
                $messagehtml .= get_string('reservationrequesttxt', 'tutorship');
                $messagehtml .= ' <b>'.format_string(fullname($USER)).'</b>.<br>';
                $messagehtml .= get_string('reservationdetails', 'tutorship');
                $messagehtml .= tutorship_get_reserve_date($timetableid, $USER->id).'.<br>';
                $messagehtml .= tutorship_get_email_link($cm->id, $week);
                if (! email_to_user ($to, $USER, $subject, null, $messagehtml)) {
                    print_error('erremail', 'tutorship');
                }

            }

            // If teacher has automatic confirmation enabled, confirm and email to student
            if ($DB->get_field('tutorship_configs', 'autoconfirm', array('teacherid' => $teacherid))) {
                if ($DB->set_field('tutorship_reserves', 'confirmed', 1, $reservationconditions)) {
    
                    // Email to student
                    $autoconfirmsubject .= get_string('reservationconfirmed', 'tutorship');
                    $message            .= get_string('reservationconfirmedtxt', 'tutorship');
                    $message            .= ' <b>'.format_string(fullname($to)).'</b>.<br>';
                    $message            .= get_string('reservationdetails', 'tutorship');
                    $message            .= tutorship_get_reserve_date($timetableid, $USER->id);
                    if (! email_to_user ($USER, $to, $autoconfirmsubject, null, $message)) {
                        print_error('erremail', 'tutorship');
                    }

                } else {
                    print_error('errconfirmation', 'tutorship');
                }
            }

        } else {
            print_error('errreserve', 'tutorship');
        }

    } else {
        $reachedmaxreserves = true;
    }
}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Sets page properties
$urlparams = array();
$urlparams['id'] = $cm->id;
if ($t) {
    $urlparams['t'] = $course->id; 
}
if ($selectedteacher) {
    $urlparams['selectedteacher'] = $selectedteacher;
}
if ($selectedperiod) {
    $urlparams['selectedperiod'] = $selectedperiod;
}
if ($slotid) {
    $urlparams['slotid'] = $slotid;
}
if ($maxreserves) {
    $urlparams['maxreserves'] = $maxreserves;
}
if ($autoconfirm) {
    $urlparams['autoconfirm'] = $autoconfirm;
}
if ($notify) {
    $urlparams['notify'] = $notify;
}
if ($week) {
    $urlparams['week'] = $week;
}
if ($timetableid) {
    $urlparams['timetableid'] = $timetableid;
}
if ($reserveid) {
    $urlparams['reserveid'] = $reserveid;
}
if ($cancell) {
    $urlparams['cancell'] = $cancell;
}
if ($noreserves) {
    $urlparams['noreserves'] = $noreserves;
}
$PAGE->set_url(new moodle_url('/mod/tutorship/view.php', $urlparams));
$PAGE->set_context($context);
$PAGE->set_cacheable(false);
$PAGE->set_title($tutorship->name);
$PAGE->set_heading($course->shortname);
if (has_capability('mod/tutorship:update', $PAGE->context)) {
    $PAGE->set_button(update_module_button($cm->id, $course->id, get_string('nameandvisibility', 'tutorship')));
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Checks to see if groups are being used here
$groupmode = groups_get_activity_groupmode($cm);
$currentgroup = groups_get_activity_group($cm, true);
groups_print_activity_menu($cm, $CFG->wwwroot . "/mod/tutorship/view.php?id=$cm->id");
if ($currentgroup) {
    $groupselect = " AND groupid = '$currentgroup'";
    $groupparam  = "&amp;groupid=$currentgroup";
} else {
    $groupselect = "";
    $groupparam  = "";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Output starts here
echo $OUTPUT->header();

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Includes proper file depending on capability
if (has_capability('mod/tutorship:update', $context)) {
    include ($CFG->dirroot.'/mod/tutorship/teacherview.php');
} else if (has_capability('mod/tutorship:reserve', $context)) {
    include ($CFG->dirroot.'/mod/tutorship/studentview.php');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
// Finish the page
echo $OUTPUT->footer();
