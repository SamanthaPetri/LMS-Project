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
 * View Webcam
 *
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_testblock
 **/
require_once(__DIR__ . '/../../../config.php');
defined('MOODLE_INTERNAL') || die;

require_login();

$courseid = required_param('courseid', PARAM_INT);
$parentcourse = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

$context = context_course::instance($courseid);
$PAGE->set_course($parentcourse);
$PAGE->set_url('/blocks/testblock/form/form.php', [
    'courseid' => $courseid,
]);

$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('snapshotpage', 'block_testblock'));
$PAGE->navbar->add(get_string('snapshotpage', 'block_testblock'));

$renderer = $PAGE->get_renderer('block_testblock');
//$renderer->add_javascript_module();

echo $OUTPUT->header();

if ($CFG->disableuserimages) {
    throw new moodle_exception('disableuserimages', 'block_testblock');
}

echo $renderer->snapshot_tool();

echo $OUTPUT->footer();