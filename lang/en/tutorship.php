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
 * English strings for tutorship.
 *
 * This file defines all the module strings that will be shown in
 * English language by calling the get_string function, with the
 * string name as first argument and the module name as second.
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['at'] = 'at';
$string['cancel'] = '<font color="#DF0101">Cancel reservation</font>';
$string['chooseteacher'] = 'Choose a teacher';
$string['configdesc'] = 'Establishes the period default description to be shown, common to all modules.';
$string['configendday'] = 'Establishes the period default end day common to all modules.';
$string['configendmonth'] = 'Establishes the period default end month common to all modules.';
$string['configendtime'] = 'Select an end hour to end days with for timetable.';
$string['configendyear'] = 'Establishes the period default end year common to all modules.';
$string['configfirstperiod'] = 'Defines the tutorship first valid period default values for timetable.';
$string['configintro'] = 'Initial configuration settings.<p>Configuration values setted here are used to adjust the tutorship time slot length, the start and end time for days within the timetable, and the tutorship timetable default periods, common to all module instances.</p><p>Do not changes these values unless there is not any tutorship instance, that would be at fresh module installation.</p>';
$string['configsecondperiod'] = 'Defines the tutorship second valid period default values for timetable.';
$string['configstartday'] = 'Establishes the period default start day common to all modules.';
$string['configstartmonth'] = 'Establishes the period default start month common to all modules.';
$string['configstarttime'] = 'Select a start hour to begin days with for timetable.';
$string['configstartyear'] = 'Establishes the period default start year common to all modules.';
$string['configthirdperiod'] = 'Defines the tutorship third valid period default values for timetable.';
$string['configtimeslot'] = 'Establishes time slot length, the number of minutes per interview session.';
$string['confirm'] = '<font color="#04B404">Confirm reservation</font>';
$string['confirmed'] = '<font color="#04B404">confirmed</font>';
$string['confirmselect'] = 'Automatic reserve confirmation';
$string['confirmselect_help'] = 'Select whether the reserve confirmation is manually or automatic. Manually confirmation is recommended.';
$string['confsettingintro'] = '<p>Remember that this configuration settings apply to all the period  <b>timetables</b>:</p>';
$string['confsettings'] = 'Configuration settings';
$string['current'] = 'Current week';
$string['currentweek'] = 'Show current week';
$string['day'] = 'day';
$string['disable'] = '<font color="#DF0101">Disable</font>';
$string['edit'] = 'Edit';
$string['empty'] = 'Empty';
$string['enable'] = '<font color="#04B404">Enable</font>';
$string['enddate'] = 'End date';
$string['endday'] = 'End day';
$string['endmonth'] = 'End month';
$string['endtime'] = 'End time';
$string['endyear'] = 'End year';
$string['errcancelconfirm'] = 'Reservation request could not be cancelled, so email to student was not sent.';
$string['errconfig'] = 'Configuration settings could not be saved.';
$string['errconfirmation'] = 'Reservation request could not be confirmed, so email to student was not sent.';
$string['errconfirm'] = 'Automatic confirmation setting could not be saved.';
$string['erremail'] = 'Could not send out mail.'; 
$string['errinstance'] = 'Only one tutorship module instance is allowed per course.';
$string['errnoreserves'] = 'Disable reservation setting could not be saved.';
$string['errnotify'] = 'Notifications setting could not be saved.';
$string['errperiods'] = 'Periods could not be created.';
$string['errperiodvalidation'] = 'The administrator has wrongly configurated the periods dates have been wrongly configurated, please contact administrator to solve the problem.';
$string['errreserve'] = 'Reservation could not be done.';
$string['errreserves'] = 'Your have reached your maximum number of reserves.';
$string['errslotdelete'] = 'Time slot could not be deleted.';
$string['errtimeslots'] = 'Time slots could not be created.';
$string['errtimetable'] = 'Time slot could not be enabled on timetable.';
$string['errunreserve'] = 'Unreservation could not be done.';
$string['firstperioddesc'] = 'First period description';
$string['firstperiod'] = 'First period';
$string['friday'] = 'Friday';
$string['gotoreservation'] = 'Go to reservation';
$string['hours'] = 'Hours';
$string['intro'] = 'Description';
$string['modulename_help'] = 'The tutorship module allows teachers to design and schedule tutoring sessions timetable in order to offer weekly time slots to the students. The students can then reserve any time slot available from the tutorship timetable.';
$string['modulenameplural'] = 'Tutorships';
$string['modulename'] = 'Tutorship';
$string['monday'] = 'Monday';
$string['month'] = 'Month';
$string['nameandvisibility'] = 'Tutorship name and visibility';
$string['name_help'] = 'Type the tutorship instance short name to be shown when added to a course.';
$string['name'] = 'Name';
$string['next'] = 'Next week';
$string['nextweek'] = 'Show next week';
$string['noreserves'] = 'Disable reservation';
$string['noreserves_help'] = 'Select whether you want to disable or not the student\'s reservations. If you disable reservations, the student will only be able to see the timetable but not reserve any slot.';
$string['notconfirmed'] = '<font color="#DF0101">not confirmed</font>';
$string['noteachers'] = 'No teachers enrolled in this course.';
$string['notifyselect_help'] = 'Select whether to send notifications or not. Enabled notifications is recommended.';
$string['notifyselect'] = 'Send notifications';
$string['notimetable'] = 'does not have a tutoring timetable.';
$string['periodselect_help'] = 'Select from the list the period you want the timetable to be available for.';
$string['periodselect'] = 'Period selection';
$string['pluginadministration'] = 'Plugin administration';
$string['reservationcancelled'] = 'Tutoring reservation request cancelled';
$string['reservationcancelledtxt'] = 'Tutoring reservation request was cancelled by';
$string['reservationconfirmed'] = 'Tutoring reservation request confirmed';
$string['reservationconfirmedtxt'] = 'Tutoring reservation request was confirmed by';
$string['reservationdetails'] = 'Reservation details: ';
$string['reservationrequest'] = 'Tutoring reservation request';
$string['reservationrequesttxt'] = 'Tutoring reservation request, please confirm or cancell it by clicking the link and loging in, you will see the timetable where you can confirm or cancell reservation. <br>It was requested by';
$string['reserved'] = '<font color="#A4A4A4">Reserved</font>';
$string['reserve'] = 'Reserve';
$string['reservesselect_help'] = 'Select from the list the maximum number of reserves the student can make within two weeks.';
$string['reservesselect'] = 'Maximum number of reserves selection';
$string['secondperioddesc'] = 'Second period description';
$string['secondperiod'] = 'Second period';
$string['singletutorship'] = 'Tutorship';
$string['startdate'] = 'Start date';
$string['startday'] = 'Start day';
$string['startmonth'] = 'Start month';
$string['starttime'] = 'Start time';
$string['startyear'] = 'Start year';
$string['studentheading_help'] = 'This is the tutorship module view, where the teacher\'s tutoring timetable is shown.<p>You see current week view and you can reserve up to three slots and up to next week slots, and slots from the same day or previous days can not be reserved.</p><p>You can choose between this and next week and slots from the same day or previous day can not be reserved.</p>';
$string['studentheading'] = 'Tutorship view help';
$string['teacherheading_help'] = 'This is the tutorship module view, where the time slots, period, maximum number of reserves, email notifications and confimation mode are set in timetable.';
$string['teacherheading'] = 'Tutorship view help';
$string['teacherselect_help'] = 'If you want to reserve a tutoring interview session, first select a teacher from the list, then you will see the tutoring timetable and the available time slot sessions to reserve for an interview.';
$string['teacherselect'] = 'Teacher selection';
$string['thirdperioddesc'] = 'Third period description';
$string['thirdperiod'] = 'Third period';
$string['thursday'] = 'Thursday';
$string['timeslotlength'] = 'Time slot length (minutes)';
$string['timetable'] = 'Timetable settings';
$string['tuesday'] = 'Tuesday';
$string['tutoringschedule'] = 'Tutoring schedule timetable';
$string['tutorshipadministration'] = 'Tutorship administration';
$string['tutorship:reserve'] = 'Tutorship reserve';
$string['tutorship'] = 'Tutorship';
$string['tutorship:update'] = 'Tutorship update';
$string['tutorship:view'] = 'Tutorship view';
$string['unreserve'] = 'Unreserve';
$string['view'] = 'View';
$string['wednesday'] = 'Wednesday';
$string['year'] = 'Year';
