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

require_once(__DIR__ . '/../../../vendor/autoload.php');


use moodle_url;
use stored_file;
use context_user;
use Dompdf\Dompdf;
use Dompdf\Options;
use local_certificate_management\output\certificate;

class PdfService
{
    private static ?PdfService $service = null;

    private Dompdf $pdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $this->pdf = new Dompdf($options);
    }

    public function generateGradePdf(
        int $userId,
        int $courseId,
        int $issueId
    )
    {
        list($template, $gradeInfo) = $this->getCertificateTemplate(
            $userId,
            $courseId
        );

        $this->pdf->loadHtml($template);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $output = $this->pdf->output();

        $fileName = str_replace(' ', '_', strtolower($gradeInfo['fullname'])) . '_' . $issueId;

        $file = $this->generateFile($issueId, $fileName, $output);
        return $this->getFileUrl($file, $fileName, $issueId);
    }

    private function getFileUrl(stored_file $file, string $fileName, int $issueId): string
    {
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $issueId,
            $file->get_filepath(),
            $fileName . '.pdf'
        )->out();
    }

    private function generateFile(int $issueId, string $fileName, string $fileContents): \stored_file
    {
        $file = (object)[
            'contextid' => \context_system::instance()->id,
            'component' => 'local_certificate_management',
            'filearea' => 'issues_grade',
            'itemid' => $issueId,
            'filepath' => '/',
            'filename' => $fileName . '.pdf',
        ];

        $fs = get_file_storage();
        return $fs->create_file_from_string($file, $fileContents);
    }

    private function getCertificateTemplate(int $userId, int $courseId)
    {
        global $PAGE, $USER, $CFG;
        $PAGE->set_context(context_user::instance($USER->id));
        $output = $PAGE->get_renderer('local_certificate_management');
        $grade = UsersService::getService()->getUserGradeToPdf(
            $userId,
            $courseId,
        );
        $page = new certificate($grade);
        return [$output->certificate($page), $grade];
    }

    public static function getService(): PdfService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}