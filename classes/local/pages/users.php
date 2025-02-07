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
 * Version metadata for the local_manage_enrollments plugin.
 *
 * @package   local_certificate_management
 * @copyright 2025 Lucas Mendes <lucas.mendes.dev@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_certificate_management\local\pages;

use Exception;
use RuntimeException;
use local_certificate_management\output\users as users_output;

class users extends base_page
{
    protected function setPageToRender(): void
    {
        $this->pageToRender = new users_output();
    }

    protected function setPageName(): void
    {
        $this->pageName = 'users';
    }

    protected function canSeePage(): bool
    {
        try {
            get_course($this->params['id']);
        } catch (Exception $exception) {
            throw new RuntimeException(get_string('course_not_found', 'local_certificate_management'));
        }

        return true;
    }

    protected function setParams()
    {
        $id = required_param('id', PARAM_INT);
        $this->params['id'] = $id;
        $this->paramsToTemplate['courseid'] = $id;
    }
}