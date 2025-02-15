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

namespace qbank_quickrenamecategories;

use context;
use html_writer;
use qbank_managecategories\question_category_object as base_question_category_object;

/**
 * Class representing custom question category
 *
 * @package    qbank_quickrenamecategories
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_category_object extends base_question_category_object {

    /**
     * Initializes this classes general category-related variables
     *
     * @param int $page
     * @param array $contexts
     * @param int $currentcat
     * @param int $defaultcategory
     * @param int $todelete
     * @param array $addcontexts
     */
    public function initialize($page, $contexts, $currentcat, $defaultcategory, $todelete, $addcontexts): void {
        $lastlist = null;
        foreach ($contexts as $context) {
            $this->editlists[$context->id] = new question_category_list('ul', '', true,
                $this->pageurl, $page, 'cpage', QUESTION_PAGE_LENGTH, $context);
            $this->editlists[$context->id]->lastlist = &$lastlist;
            if ($lastlist !== null) {
                $lastlist->nextlist = &$this->editlists[$context->id];
            }
            $lastlist = &$this->editlists[$context->id];
        }

        $count = 1;
        $paged = false;
        foreach ($this->editlists as $key => $list) {
            [$paged, $count] = $this->editlists[$key]->list_from_records($paged, $count);
        }
    }

    /**
     * Outputs a list to allow editing/rearranging of existing categories
     * $this->initialize() must have already been called
     */
    public function output_edit_lists(): void {
        global $OUTPUT;

        echo $OUTPUT->heading(get_string('quickrenamecategories', 'qbank_quickrenamecategories'));

        $attributes = [];
        $attributes['action'] = $this->pageurl;
        $attributes['method'] = 'post';
        $attributes['accept-charset'] = 'utf-8';
        $attributes['autocomplete'] = 'off';
        $attributes['class'] = 'mform';
        echo html_writer::start_tag('form', $attributes);

        $attributes = [];
        $attributes['name'] = 'sesskey';
        $attributes['value'] = sesskey();
        $attributes['type'] = 'hidden';
        echo html_writer::empty_tag('input', $attributes);

        foreach ($this->editlists as $context => $list) {
            $listhtml = $list->to_html(0, ['str' => $this->str]);
            if ($listhtml) {
                $classes = 'boxwidthwide boxaligncenter generalbox questioncategories';
                $classes .= ' contextlevel' . $list->context->contextlevel;
                echo $OUTPUT->box_start($classes);
                $fullcontext = context::instance_by_id($context);
                echo $OUTPUT->heading(get_string('questioncatsfor', 'question', $fullcontext->get_context_name()), 3);
                echo $listhtml;
                echo $OUTPUT->box_end();
            }
        }

        $attributes = [];
        $attributes['name'] = 'save';
        $attributes['value'] = '1';
        $attributes['type'] = 'submit';
        $attributes['class'] = 'btn btn-primary';
        $attributes['id'] = 'id_submitbutton';
        $savebutton = html_writer::tag('button', get_string('savechanges'), $attributes);

        $attributes = [];
        $attributes['name'] = 'cancel';
        $attributes['value'] = '1';
        $attributes['type'] = 'submit';
        $attributes['class'] = 'btn btn-cancel';
        $attributes['id'] = 'id_cancel';
        $cancelbutton = html_writer::tag('button', get_string('cancel'), $attributes);

        $internaldiv = html_writer::div($savebutton . $cancelbutton, 'felement fgroup');
        $externaldiv = html_writer::div($internaldiv, 'fitem fitem_actionbuttons fitem_fgroup');
        echo $externaldiv;

        echo html_writer::end_tag('form');

        if (!empty($list)) {
            echo $list->display_page_numbers();
        }
    }

}
