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

namespace local_certificate_management\local\services;

use tool_certificate\template;
use local_certificate_management\local\services\params\IssueCertificateParams;

class CertificateService
{
    private static ?CertificateService $service = null;

    public function issueCertificate(
        IssueCertificateParams $params
    )
    {
        $template = template::instance($params->templateId);
        $certificateId = $template->issue_certificate(
            userid: $params->userId,
            courseid: $params->courseId,
        );

        return [
            'certificateId' => $certificateId,
        ];
    }

    public static function getService(): CertificateService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}