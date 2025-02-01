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
use core\event\question_category_updated;

/**
 * Helper class
 *
 * @package    qbank_quickrenamecategories
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_category_renamer {

    /**
     * Rename categories in multiple context.
     *
     * @param array $categorynames
     */
    public function rename_categories(array $categorynames): void {
        foreach ($categorynames as $contextid => $categorynamesarray) {
            $context = context::instance_by_id($contextid);
            require_capability('moodle/question:managecategory', $context);
            $this->rename_categories_in_context($categorynamesarray, $context);
        }
    }

    /**
     * Rename categories in one context.
     *
     * @param array $categorynames
     * @param context $context
     */
    private function rename_categories_in_context(array $categorynames, context $context): void {
        global $DB;

        foreach ($categorynames as $categoryid => $newcategoryname) {
            $category = $DB->get_record('question_categories', ['id' => $categoryid], 'id, name', MUST_EXIST);
            if ($category->name != $newcategoryname) {
                $category->name = $newcategoryname;
                $DB->update_record('question_categories', $category);
                $event = question_category_updated::create_from_question_category_instance($category, $context);
                $event->trigger();
            }
        }
    }

}
