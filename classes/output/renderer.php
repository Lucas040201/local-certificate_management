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

namespace local_certificate_management\output;

use core\output\templatable;
use core\output\plugin_renderer_base;
use stdClass;

class renderer extends plugin_renderer_base
{

    public function render_pages(
        string $pageName,
        templatable $template,
    )
    {
        $templateName = "local_certificate_management/page/$pageName";
        return $this->$pageName($template, $templateName);
    }
    public function courses(courses $courses, string $templateName): bool|string
    {
        return $this->render_from_template($templateName, $this->getContext($courses));
    }

    private function getContext(templatable $template): array|stdClass
    {
        $context = $template->export_for_template($this);
        return $context;
    }
}