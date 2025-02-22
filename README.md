Quick question categories renaming Moodle plugin
================================================

Requirements
------------
- Moodle 4.5 (build 2024100700) or later.

Installation
------------
Copy the quickrenamecategories folder into your Moodle /question/bank directory and visit your Admin Notification page to complete the installation.

Usage
-----
Question bank navigation node will be extended with "Quick categories rename" item. You will be able to rename all categories in one form.
This can be useful when working with large question banks together with qbank_resortcategory and qbank_renumbercategory plugins.

Author
------
- Vadim Dvorovenko (Vadimon@mail.ru)

Links
-----
- Updates: https://moodle.org/plugins/view.php?plugin=qbank_quickrenamecategories
- Latest code: https://github.com/vadimonus/moodle-qbank_quickrenamecategories

Changes
-------
- Release 0.9 (build 2016041800):
    - First public release.
- Release 1.0 (build 2016042800):
    - Fix error with "<" and ">" chars in category name.
- Release 1.0.1 (build 2016051000):
    - Additional capability checks.
- Release 1.1 (build 2020061200):
    - Privacy API support.
    - Question bank tabs.
- Release 2.0 (build 2025020100)
    - Renamed from local_quickrenamequestioncategories to qbank_quickrenamecategories.
    - Refactored for Moodle 4 question bank changes.
    - Renaming category fires event and is logged.
- Release 2.0.1 (build 2025021500)
    - Category name validation changed to PARAM_TEXT 
- Release 3.0 (build 2025022300)
    - Refactored for Moodle 4.5 question bank management changes. 
