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
 * Prints a particular instance of tutorship for students view.
 *
 * The tutorship instance view that shows the teacher's tutoring
 * timetable configuration with time slots for student to reserve.
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die(); // Direct access to this file is forbidden

// Prints the heading
echo $OUTPUT->heading($OUTPUT->help_icon('studentheading', 'tutorship').format_string($tutorship->name));

// Security priviledges
require_login($course, true, $cm);
require_capability('mod/tutorship:reserve', $PAGE->context);

// Gets teachers enrolled in this course
$teachers = tutorship_get_teachers($course->id);
if (isset($teachers)) {

    // Sets enrolled teachers array
    $i = 1;
    $teachersfullnames = array();
    foreach ($teachers as $teacher) {
        $teachersfullnames[$i] = fullname($teacher);
        $i++;
    }

    // Sets necessary parameters for select element
    $nothingselection = array('0' => get_string('chooseteacher', 'tutorship'));
    $attributes = array('onchange' => 'this.form.submit()');

    // Starts box element
    echo $OUTPUT->box_start();
    echo $OUTPUT->container_start();

    // Prints teacher select element
    echo '<center>';
    echo html_writer::start_tag('form', array('id' => 'teacherform', 'method' => 'post', 'action' => ''));
    echo html_writer::label(get_string('teacherselect', 'tutorship'), 'selectteacherlabel');
    echo $OUTPUT->help_icon('teacherselect', 'tutorship');
    echo html_writer::select($teachersfullnames, 'selectedteacher', '0', $nothingselection, $attributes);
// Todo - implement sesskey in forms, may be using function is_post_with_sesskey().
//    echo '<input type="hidden" name="sesskey" value="'.sesskey().'" />';
    echo html_writer::end_tag('form');
    echo '</center>';
    
    // Ends box element
    echo $OUTPUT->container_end();
    echo $OUTPUT->box_end();

    // Prints selected teacher
    if ($selectedteacher) {
        foreach ($teachers as $teacher) {
            if (fullname($teacher) == $teachersfullnames[$selectedteacher]) {
                $teacherobject = $teacher;
            }
        }
        echo $OUTPUT->notification(fullname($teacherobject), 'notifysuccess');
    }

} else {
    echo '<center>';
    echo $OUTPUT->error_text(get_string('noteachers', 'tutorship'));
    echo '</center>';
}

// Shows the teacher timetable
$periodid = tutorship_get_current_period($today);
if (isset($teacherobject) and tutorship_has_timetable($teacherobject->id, $periodid)) {
    // Gets necessary data for reservetable rows
    $timeslotlength = tutorship_get_slot_length();
    $teachertimeslots = $DB->get_records('tutorship_timetables', array('teacherid' => $teacherobject->id, 
                                         'periodid' => $periodid), 'timeslotid');

    // Preparing reservetable
    $reservetable        = new html_table();
    $reservetable->head  = array();
    $reservetable->align = array();
    $reservetable->size  = array();

    // Reservetable heading
    $reservetable->head['0'] = get_string('hours', 'tutorship');
    $reservetable->head['1'] = get_string('monday', 'tutorship');
    $reservetable->head['2'] = get_string('tuesday', 'tutorship');
    $reservetable->head['3'] = get_string('wednesday', 'tutorship');
    $reservetable->head['4'] = get_string('thursday', 'tutorship');
    $reservetable->head['5'] = get_string('friday', 'tutorship');

    // Reservetable properties
    for ($i = 0; $i <= 5; $i++) {   // From column 0-Hours to column 5-Friday
        $reservetable->align[$i] = 'center';
        $reservetable->size[$i]  = '10%';
    }

    // Reserve rows
    if ($teachertimeslots) {
        $row      = array();
        $slots    = array();
        $numslots = 0;

        // Necessary information to reserve
        $daynumber  = date('N', $today) - 1;

        // Sets time slots object array
        foreach ($teachertimeslots as $teachertimeslot) {
            $slots[$numslots] = $DB->get_record('tutorship_timeslots', array('id' => $teachertimeslot->timeslotid));
            $numslots++;
        }

        // Sets and adds rows to reservetable
        for ($i = 0; $i <= $numslots; $i++) {
            if ($slots[$i]->starttime == $slots[$i + 1]->starttime) {   // Same row
                if (empty($row[0])) {                                   // First column: Hours
                    $row[0]  = gmdate('H:i', $slots[$i]->starttime).' - ';
                    $row[0] .= gmdate('H:i', $slots[$i]->starttime + $timeslotlength);
                }

                // Can't reserve today nor previous days for current week
                if ((($slots[$i]->day > $daynumber) and ($week == 1)) or ($week == 2)) { 
                    // Adds element row cell
                    // Information for making reserve links
                    $timetableconditions = array('teacherid' => $teacherobject->id, 'periodid' => $periodid, 
                                                 'timeslotid' => $slots[$i]->id);
                    $timetableid = $DB->get_field('tutorship_timetables', 'id', $timetableconditions);
                    $row[$slots[$i]->day + 1] = tutorship_get_reserve_link($timetableid, $USER->id, $course->id, $today,
                                                                           $urlparams);
                } else {
                    $noreserve = $DB->get_field('tutorship_configs', 'noreserves', array('teacherid' => $teacherobject->id));
                    if ($noreserve) {
                        $row[$slots[$i]->day + 1] = format_text(get_string('singletutorship', 'tutorship'));
                    } else {
                        $row[$slots[$i]->day + 1] = format_text(get_string('reserve', 'tutorship'));
                    }
                }
            } else { // End row set
                if (empty($row[0])) {                                   // First column: Hours
                    $row[0]  = gmdate('H:i', $slots[$i]->starttime).' - ';
                    $row[0] .= gmdate('H:i', $slots[$i]->starttime + $timeslotlength);
                }

                // Can't reserve today nor previous days for current week
                if ((($slots[$i]->day > $daynumber) and ($week == 1)) or ($week == 2)) {
                    // Adds element row cell
                    // Information for making reserve links
                    $timetableconditions = array('teacherid' => $teacherobject->id, 'periodid' => $periodid, 
                                                 'timeslotid' => $slots[$i]->id);
                    $timetableid = $DB->get_field('tutorship_timetables', 'id', $timetableconditions);
                    $row[$slots[$i]->day + 1] = tutorship_get_reserve_link($timetableid, $USER->id, $course->id, $today, 
                                                                           $urlparams);
                } else {
                    $noreserve = $DB->get_field('tutorship_configs', 'noreserves', array('teacherid' => $teacherobject->id));
                    if ($noreserve) {
                        $row[$slots[$i]->day + 1] = format_text(get_string('singletutorship', 'tutorship'));
                    } else {
                        $row[$slots[$i]->day + 1] = format_text(get_string('reserve', 'tutorship'));
                    }
                }

                // Sets empty row cells
                for ($j = 1; $j <= 5; $j++) {
                    if (empty($row[$j])) {
                        $row[$j] = null;
                    }
                }

                // Adds row to reservetable
                $reservetable->data[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
                unset($row);
                $row  = array();
            }
        }
    }

    // Next/Current week top link
    if ($week == 1) {
        echo '<div align=right>';
    }
    echo tutorship_get_week_link($week, $urlparams);
    if ($week == 1) {
        echo '</div>';
    }
    
    // Prints timetable
    echo html_writer::table($reservetable);

    // Next/Current week bottom link
    if ($week == 1) {
        echo '<div align=right>';
    }
    echo tutorship_get_week_link($week, $urlparams);
    if ($week == 1) {
        echo '</div>';
    }
    
    // You have reached max reserves message
    if (isset($reachedmaxreserves) and $reachedmaxreserves) {
        echo $OUTPUT->error_text(get_string('errreserves', 'tutorship'));
    }
} else if (isset($teacherobject)) {
    echo '<center>';
    echo $OUTPUT->error_text(fullname($teacherobject).' '.get_string('notimetable', 'tutorship'));
    echo '</center>';
}
