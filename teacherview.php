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
 * Prints a particular instance of tutorship for teachers view.
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
echo $OUTPUT->heading($OUTPUT->help_icon('teacherheading', 'tutorship').format_string($tutorship->name));

// Security priviledges and layout
require_login($course, true, $cm);
require_capability('mod/tutorship:update', $PAGE->context);
$PAGE->set_pagelayout('admin');

if ($action == 1) { // view timetable

    // Prints the heading and edit button
    $urlparams['id'] = $cm->id;
    $urlparams['t'] = $course->id;
    $urlparams['slotid'] = 0; // We don't want to enable a slot
    $urlparams['selectedperiod'] = $selectedperiod;
    $urlparams['maxreserves'] = $maxreserves;
    $urlparams['notify'] = $notify;
    $urlparams['action'] = 2;
    echo '<p>';
    echo '<center>';
    echo $OUTPUT->single_button(new moodle_url('/mod/tutorship/view.php', $urlparams), get_string('edit', 'tutorship'));
    echo '</center>';
    echo '</p>';

    // Shows the teacher timetable
    $periodid = tutorship_get_current_period($today);
    if (tutorship_has_timetable($USER->id, $selectedperiod)) {
        // Gets necessary data for reservetable rows
        $timeslotlength = tutorship_get_slot_length();
        $teachertimeslots = $DB->get_records('tutorship_timetables', array('teacherid' => $USER->id, 
                                             'periodid' => $selectedperiod), 'timeslotid');

        // Preparing table
        $table        = new html_table();
        $table->head  = array();
        $table->align = array();
        $table->size  = array();

        // Table heading
        $table->head['0'] = get_string('hours', 'tutorship');
        $table->head['1'] = get_string('monday', 'tutorship');
        $table->head['2'] = get_string('tuesday', 'tutorship');
        $table->head['3'] = get_string('wednesday', 'tutorship');
        $table->head['4'] = get_string('thursday', 'tutorship');
        $table->head['5'] = get_string('friday', 'tutorship');

        // Table properties
        for ($i = 0; $i <= 5; $i++) {   // From column 0-Hours to column 5-Friday
            $table->align[$i] = 'center';
            $table->size[$i]  = '10%';
        }

        // Reserve rows
        if ($teachertimeslots) {
            $row      = array();
            $slots    = array();
            $numslots = 0;

            // Necessary information to confirm
            $weeknumber = date('W', $today);
            $daynumber  = date('N', $today) - 1;

            // Sets time slots object array
            foreach ($teachertimeslots as $teachertimeslot) {
                $slots[$numslots] = $DB->get_record('tutorship_timeslots', array('id' => $teachertimeslot->timeslotid));
                $numslots++;
            }

            // Sets and adds rows to table
            for ($i = 0; $i <= $numslots; $i++) {
                if ($slots[$i]->starttime == $slots[$i + 1]->starttime) {   // Same row
                    if (empty($row[0])) {                                   // First column: Hours
                        $row[0]  = gmdate('H:i', $slots[$i]->starttime).' - ';
                        $row[0] .= gmdate('H:i', $slots[$i]->starttime + $timeslotlength);
                    }

                    // Can't cofirm nor cancell previous days for current week
                    if ((($slots[$i]->day > $daynumber) and ($week == 1)) or ($week == 2)) {
                        // Adds element row cell with reservation information
                        $tableconditions = array('teacherid' => $USER->id, 'periodid' => $selectedperiod, 
                                                 'timeslotid' => $slots[$i]->id);
                        $timetableid = $DB->get_field('tutorship_timetables', 'id', $tableconditions);
                        $row[$slots[$i]->day + 1] = tutorship_get_reservation_info_link($course->id, $timetableid, 
                                                                                        $weeknumber, $urlparams);
                    } else {
                        $row[$slots[$i]->day + 1] = format_text(get_string('empty', 'tutorship'));
                    }
                } else { // End row set
                    if (empty($row[0])) {                                   // First column: Hours
                        $row[0]  = gmdate('H:i', $slots[$i]->starttime).' - ';
                        $row[0] .= gmdate('H:i', $slots[$i]->starttime + $timeslotlength);
                    }
                
                    // Can't cofirm nor cancell previous days for current week
                    if ((($slots[$i]->day > $daynumber) and ($week == 1)) or ($week == 2)) {
                        // Adds element row cell with reservation information 
                        $tableconditions = array('teacherid' => $USER->id, 'periodid' => $selectedperiod,
                                                 'timeslotid' => $slots[$i]->id);
                        $timetableid = $DB->get_field('tutorship_timetables', 'id', $tableconditions);
                        $row[$slots[$i]->day + 1] = tutorship_get_reservation_info_link($course->id, $timetableid, 
                                                                                        $weeknumber, $urlparams);
                    } else {
                        $row[$slots[$i]->day + 1] = format_text(get_string('empty', 'tutorship'));
                    }

                    // Sets empty row cells
                    for ($j = 1; $j <= 5; $j++) {
                        if (empty($row[$j])) {
                            $row[$j] = null;
                        }
                    }

                    // Adds row to table
                    $table->data[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
                    unset($row);
                    $row  = array();
                }
            }   
        }

        // Prints timetable period
        echo $OUTPUT->box_start();
        echo $OUTPUT->container_start();
        echo '<center>';
        $introtextrecord      = $DB->get_record('tutorship_periods', array('id' => $selectedperiod));
        $introtextdescription = $introtextrecord->description;
        $introtextstartdate   = date('d/m/y', $introtextrecord->startdate);
        $introtextenddate     = date('d/m/y', $introtextrecord->enddate);
        $introtext            = '<b>'.$introtextdescription.'</b> ('.$introtextstartdate.' - '.$introtextenddate.')';
        echo format_text($introtext);
        echo '</center>';
        echo $OUTPUT->container_end();
        echo $OUTPUT->box_end();

        // If selected period is current period, then show current/next week links
        // Next/Current week top link
        if ($week == 1) {
            echo '<div align=right>';
        }
        echo tutorship_get_week_link($week, $urlparams);
        if ($week == 1) {
            echo '</div>';
        }

        // Prints table
        echo html_writer::table($table);

        // If selected period is current period, then show current/next week links
        // Next/Current week top link
        if ($week == 1) {
            echo '<div align=right>';
        }
        echo tutorship_get_week_link($week, $urlparams);
        if ($week == 1) {
            echo '</div>';
        }    
    } else if (! tutorship_has_timetable($USER->id, $selectedperiod)) {
        echo '<center>';
        echo $OUTPUT->error_text(fullname($USER).' '.get_string('notimetable', 'tutorship'));
        echo '</center>';
    }

} else if ($action == 2) { // edit timetable

    // Prints the view button
    $urlparams['id'] = $cm->id;
    $urlparams['t'] = $t;
    $urlparams['slotid'] = 0; // We don't want to reserve or unreserve any slot now
    $urlparams['selectedperiod'] = $selectedperiod;
    $urlparams['maxreserves'] = $maxreserves;
    $urlparams['notify'] = $notify;
    $urlparams['action'] = 1;
    echo '<p>';
    echo '<center>';
    echo $OUTPUT->single_button(new moodle_url('/mod/tutorship/view.php', $urlparams), get_string('view', 'tutorship'));
    echo '</center>';
    echo '</p>';

    // Preparing timetable
    $timetable        = new html_table();
    $timetable->head  = array();
    $timetable->align = array();
    $timetable->size  = array();

    // Timetable heading
    $timetable->head['0'] = get_string('hours', 'tutorship');
    $timetable->head['1'] = get_string('monday', 'tutorship');
    $timetable->head['2'] = get_string('tuesday', 'tutorship');
    $timetable->head['3'] = get_string('wednesday', 'tutorship');
    $timetable->head['4'] = get_string('thursday', 'tutorship');
    $timetable->head['5'] = get_string('friday', 'tutorship');

    // Timetable properties
    for ($i = 0; $i <= 5; $i++) {   // From column 0-Hours to column 5-Friday
        $timetable->align[$i] = 'center';
        $timetable->size[$i]  = '10%';
    }

    // Gets necessary data for timetable rows
    $timeslotlength = tutorship_get_slot_length();
    $timeslots = $DB->get_records('tutorship_timeslots');

    // Timetable rows
    foreach ($timeslots as $timeslot) {
        if ($timeslot->day == TUTORSHIP_MONDAY) {
            $row       = array();
            $starttime = gmdate('H:i', $timeslot->starttime);
            $endtime   = gmdate('H:i', $timeslot->starttime + $timeslotlength);
            $row['0']  = $starttime.' - '.$endtime; // First column
            $row['1']  = tutorship_get_slot_link($timeslot->id, $USER->id, $selectedperiod, $urlparams);
        }
        if ($timeslot->day == TUTORSHIP_TUESDAY) {
            $row['2'] = tutorship_get_slot_link($timeslot->id, $USER->id, $selectedperiod, $urlparams);
        }
        if ($timeslot->day == TUTORSHIP_WEDNESDAY) {
            $row['3'] = tutorship_get_slot_link($timeslot->id, $USER->id, $selectedperiod, $urlparams);
        }
        if ($timeslot->day == TUTORSHIP_THURSDAY) {
            $row['4'] = tutorship_get_slot_link($timeslot->id, $USER->id, $selectedperiod, $urlparams);
        }
        if ($timeslot->day == TUTORSHIP_FRIDAY) {
            $row['5'] = tutorship_get_slot_link($timeslot->id, $USER->id, $selectedperiod, $urlparams);
            $timetable->data[] = $row;
            unset($row);
        }
    }

    // Prints timetable period
    echo $OUTPUT->box_start();
    echo $OUTPUT->container_start();
    echo '<center>';
    $introtextrecord      = $DB->get_record('tutorship_periods', array('id' => $selectedperiod));
    $introtextdescription = $introtextrecord->description;
    $introtextstartdate   = date('d/m/y', $introtextrecord->startdate);
    $introtextenddate     = date('d/m/y', $introtextrecord->enddate);
    $introtext            = '<b>'.$introtextdescription.'</b> ('.$introtextstartdate.' - '.$introtextenddate.')';
    echo format_text($introtext);
    echo '</center>';
    echo $OUTPUT->container_end();
    echo $OUTPUT->box_end();

    // Prints timetable
    echo html_writer::table($timetable);

    // Retreives information to initialize select fields
    $initreserves   = (int) $DB->get_field('tutorship_configs', 'maxreserves', $teacherconditions);
    $initconfirm    = (int) $DB->get_field('tutorship_configs', 'autoconfirm', $teacherconditions);
    $initnotify     = (int) $DB->get_field('tutorship_configs', 'notifications', $teacherconditions);

    // Gets periods and sets period array for select element
    $periods = $DB->get_records('tutorship_periods');
    if (isset($periods)) {
        $i = 1;
        $periodsdesc = array();
        foreach ($periods as $period) {
            $perioddate = '('.date('d/m/y', $period->startdate).' - '.date('d/m/y', $period->enddate).')';
            $periodsdesc[$i] = $period->description.' '.$perioddate;
            $i++;
        }
    }

    // Sets reserves array for select element
    $reserves = array();
    for ($i = 1; $i <= 4; $i++) {
        $reserves[$i] = $i;
    }

    // Sets attributes for selection elements
    $attributes = array('onchange' => 'this.form.submit()');

    // Starts box element
    echo $OUTPUT->box_start();
    echo $OUTPUT->container_start();

    // Prints introductory text
    echo format_text(get_string('confsettingintro', 'tutorship'));
    echo '<p><div align=right>';

    // Prints period select element
    echo html_writer::start_tag('form', array('id' => 'configform', 'method' => 'post', 'action' => ''));
    echo '<input type="hidden" name="id" value="'.$cm->id.'" />';
    echo '<input type="hidden" name="t" value="'.$course->id.'" />';
    echo '<input type="hidden" name="slotid" value="0" />';
    echo '<input type="hidden" name="action" value="2" />';
// Todo - implement sesskey in forms, may be using function is_post_with_sesskey().
//    echo '<input type="hidden" name="sesskey" value="'.sesskey().'" />';
    echo html_writer::start_tag('fieldset');
    echo html_writer::label(get_string('periodselect', 'tutorship'), 'selectperiodlabel');
    echo $OUTPUT->help_icon('periodselect', 'tutorship');
    echo html_writer::select($periodsdesc, 'selectedperiod', $selectedperiod, false, $attributes);
    echo html_writer::end_tag('fieldset');

    // Prints max reserves select element
    echo html_writer::start_tag('fieldset');
    echo html_writer::label(get_string('reservesselect', 'tutorship'), 'selectreserveslabel');
    echo $OUTPUT->help_icon('reservesselect', 'tutorship');
    echo html_writer::select($reserves, 'maxreserves', $initreserves, false, $attributes);
    echo html_writer::end_tag('fieldset');
    
    // Prints autoconfirmation select element
    echo html_writer::start_tag('fieldset');
    echo html_writer::label(get_string('confirmselect', 'tutorship'), 'selectconfirmlabel');
    echo $OUTPUT->help_icon('confirmselect', 'tutorship');
    echo html_writer::select_yes_no('autoconfirm', $initconfirm, $attributes);
    echo html_writer::end_tag('fieldset');

    // Prints notifications select element
    echo html_writer::start_tag('fieldset');
    echo html_writer::label(get_string('notifyselect', 'tutorship'), 'selectnotifylabel');
    echo $OUTPUT->help_icon('notifyselect', 'tutorship');
    echo html_writer::select_yes_no('notify', $initnotify, $attributes);
    echo html_writer::end_tag('fieldset');

    // Prints  select element
    echo html_writer::start_tag('fieldset');
    echo html_writer::label(get_string('noreserves', 'tutorship'), 'selectnoreserveslabel');
    echo $OUTPUT->help_icon('noreserves', 'tutorship');
    echo html_writer::select_yes_no('noreserves', $noreserves, $attributes);
    echo html_writer::end_tag('fieldset');
    echo html_writer::end_tag('form');

    // Ends box element
    echo '</div></p>';
    echo $OUTPUT->container_end();
    echo $OUTPUT->box_end();

}
