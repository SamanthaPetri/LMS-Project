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
 * Form for editing HTML block instances.
 *
 * @package   block_testblock
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_testblock extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_testblock');
    }

    /**
     * has_config
     *
     * @return bool
     */
    public function has_config() : bool {
        return true;
    }

    /**
     * instance_allow_config
     *
     * @return bool
     */
    public function instance_allow_config() : bool {
        return true;
    }

    /**
     * specialization
     *
     * @throws coding_exception
     */
    public function specialization() : void {

        // Load userdefined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_testblock');
        } else {
            $this->title = $this->config->title;
        }
    }
	
    function get_content() {
        global $CFG, $COURSE;

        require_once($CFG->libdir . '/formslib.php');
		
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '<div class="singlebutton">
                                    <form action="' . $CFG->wwwroot . '/blocks/testblock/form/form.php" method="get">
                                      <div>
                                        <input type="hidden" name="blockid" value="' . $this->instance->id . '"/>
                                        <input type="hidden" name="courseid" value="' . $COURSE->id . '"/>
                                        <input class="singlebutton btn btn-primary" type="submit" value="Go to form.php"/>
                                      </div>
                                    </form>
                                  </div>';		
		
		//$this->content->text = 'This is a text';
        $this->content->footer = 'This is a footer text';

        return $this->content;
    }
}