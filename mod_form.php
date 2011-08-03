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
 * The main tutorship configuration form.
 *
 * It uses the standard core Moodle formslib. This is where the main
 * tutorship configuration form is declared, describes the form you 
 * get at the module instance creation or at the instance editing time. 
 * The syntax is very simple and by reading the file it is simple to 
 * change it on your need.
 *
 * For more info, please visit: 
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die(); // Direct access to this file is forbidden

require_once($CFG->dirroot.'/course/moodleform_mod.php'); // Calls moodleform_mod.php

/**
 * The main tutorship class form.
 *
 * It uses the standar core moodleform_mod. This is where the
 * structure of the form is defined, it extends class moodleform.
 * Note the name that is given to the class is used as the id 
 * attribute of the form in html (any trailing '_form' is chopped off'). 
 * The form class name should be unique in order for it to be selectable 
 * in CSS by theme designers who may want to tweak the css just for that 
 * form. 
 *
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_tutorship_mod_form extends moodleform_mod { 
    function definition() { // Includes all the elements that are going to be use on the form.
        global $COURSE;
        $mform =& $this->_form;
//-------------------------------------------------------------------------------
    	// General adjustments: name and intro
    	// Adding the "general" fieldset, where all the common settings are showed

	    // Adding the standard "general" header
        $mform->addElement('header', 'general', get_string('general', 'form'));

    	// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('name', 'tutorship'), array('size'=>'30'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->setDefault('name', get_string('tutoringschedule', 'tutorship'));
        $mform->addRule('name', get_string('maximumchars', null, 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'name', 'tutorship');
//------------------------------------------------------------------------------
    	// Custom common module settings
        // Adding "standard" elements, common to all modules
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
    	// Bottom buttons
        // Adding "standard" buttons, common to all modules
	    // Save and return to course, Save and display, Cancel
        $this->add_action_buttons();
    }
}
