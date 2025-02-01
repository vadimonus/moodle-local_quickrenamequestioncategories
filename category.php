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
 * Tool for quick renaming of many question categories.
 *
 * @package    qbank_quickrenamecategories
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../../config.php");
require_once("$CFG->dirroot/question/editlib.php");

use core_question\local\bank\helper as core_question_local_bank_helper;
use core_question\output\qbank_action_menu;
use qbank_quickrenamecategories\question_category_renamer;
use qbank_quickrenamecategories\question_category_object;

require_login();
core_question_local_bank_helper::require_plugin_enabled('qbank_quickrenamecategories');

[$thispageurl, $contexts, $cmid, $cm, $module, $pagevars] = question_edit_setup('categories',
        '/question/bank/quickrenamecategories/category.php');

$savebutton = optional_param('save', '', PARAM_RAW);
$cancelbutton = optional_param('cancel', '', PARAM_RAW);

$url = new moodle_url($thispageurl);
$url->remove_params(['cpage']);
$PAGE->set_url($url);
$PAGE->set_title(get_string('quickrenamecategories', 'qbank_quickrenamecategories'));
$PAGE->set_heading($COURSE->fullname);

if ($cancelbutton) {
    redirect($thispageurl);
} else if ($savebutton) {
    require_sesskey();
    $categorynames = $_POST['categoryname'];
    $categorynames = clean_param_array($categorynames, PARAM_RAW, true);
    $qcobject = new question_category_renamer();
    $qcobject->rename_categories($categorynames);
}
echo $OUTPUT->header();
$renderer = $PAGE->get_renderer('core_question', 'bank');
$qbankaction = new qbank_action_menu($thispageurl);
echo $renderer->render($qbankaction);

$qcobject = new question_category_object($pagevars['cpage'], $thispageurl,
        $contexts->having_cap('moodle/question:managecategory'), 0, $pagevars['cat'], 0, []);
$qcobject->output_edit_lists();
echo $OUTPUT->footer();
