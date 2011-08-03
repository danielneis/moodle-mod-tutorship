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
 * Prints a list of instances.
 * 
 * This php file lists all the tutorship module instances for a 
 * particular course.
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// Includes the specified files
/// A key problem to hierarchical include trees is that PHP processes 
/// include paths relative to the original file, not the current including file.
/// A solution to that, is to prefix all include paths with: dirname(__FILE__)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

/// Checks that course id is correct

$id = required_param('id', PARAM_INT);   
if (! $course = $DB->get_record('course', array('id' => $id))) {
    print_error('coursemisconf');//'Course ID is incorrect');
}

/// Checks that the actual user has right permissions

require_course_login($course);

/// Records user activity

add_to_log($course->id, 'tutorship', 'view all', "index.php?id=$course->id", '');

/// Output starts here, setting the page

$PAGE->set_url('/mod/tutorship/view.php', array('id' => $id));
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->shortname);
$PAGE->navbar->add(get_string('modulenameplural', 'tutorship'));

/// Prints the header

echo $OUTPUT->header();

/// Gets all the appropriate data

if (! $tutorships = get_all_instances_in_course('tutorship', $course)) {
    echo $OUTPUT->heading(get_string('notutorships', 'tutorship'), 2);
    echo $OUTPUT->continue_button(new moodle_url('/course/view.php', array('id' => $course->id)));
    echo $OUTPUT->footer();
    die();
}

///

$usesections = course_format_uses_sections($course->format);
if ($usesections) {
    $sections = get_all_sections($course->id);
}

/// Prints the list of instances

$timenow  = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic = get_string('topic');
$strsectionname  = get_string('sectionname', 'format_'.$course->format);
$strname  = get_string('name');

if ($course->format == 'weeks') {
    $table->head  = array ($strweek, $strname);
    $table->align = array ('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array ($strtopic, $strname);
    $table->align = array ('center', 'left', 'left', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left', 'left', 'left');
}
if ($usesections) {
    $table->head  = array ($strsectionname, $strname);
    $table->align = array ('center', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left');
}

foreach ($tutorships as $tutorship) {
    if (!$tutorship->visible) {
        //Show dimmed if the mod is hidden
        $link = '<a class="dimmed" href="view.php?id='.$tutorship->coursemodule.'">'.format_string($tutorship->name).'</a>';
    } else {
        //Show normal if the mod is visible
        $link = '<a href="view.php?id='.$tutorship->coursemodule.'">'.format_string($tutorship->name).'</a>';
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array ($tutorship->section, $link);
    } else {
        $table->data[] = array ($link);
    }
}

echo $OUTPUT->heading(get_string('modulenameplural', 'tutorship'), 2);
echo html_writer::table($table);

/// Finish the page

echo $OUTPUT->footer();
