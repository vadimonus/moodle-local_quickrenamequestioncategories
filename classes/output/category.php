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
 * @copyright  2025 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qbank_quickrenamecategories\output;

use context;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Output component for a single category
 *
 * @package    qbank_quickrenamecategories
 * @copyright  2025 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class category implements renderable, templatable {

    /**
     * Constructor
     *
     * @param stdClass $category The record of category we are rendering
     * @param context $context The context the category belongs to
     */
    public function __construct(
        /** @var stdClass $category The record of category we are rendering */
        protected stdClass $category,
        /** @var context $context The context the category belongs to. */
        protected context $context,
    ) {
    }

    /**
     * Create the template data for a category, and call recursively for child categories.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        $children = [];
        if (!empty($this->category->children)) {
            foreach ($this->category->children as $child) {
                $childcategory = new category($child, $this->context);
                $children[] = $childcategory->export_for_template($output);
            }
        }
        return [
            'categoryid' => $this->category->id,
            'contextid' => $this->context->id,
            'categoryname' => $this->category->name,
            'haschildren' => !empty($children),
            'children' => $children,
        ];
    }
}
