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

use core_completion\progress;
use local_certificate_management\local\repositories\UsersRepository;
use local_certificate_management\local\repositories\GradeRepository;
use local_certificate_management\local\repositories\CertificateRepository;
use local_certificate_management\local\repositories\params\RetrieveUsersParam;

class UsersService
{
    private static ?UsersService $service = null;
    private UsersRepository $repository;

    private GradeRepository $gradeRepository;

    private CertificateRepository $certificateRepository;

    public function __construct()
    {
        $this->repository = new UsersRepository();
        $this->gradeRepository = new GradeRepository();
        $this->certificateRepository = new CertificateRepository();
    }

    public function retrieveUsers(
        int $courseId,
        string $search = '',
        string $sort = 'ASC',
        int $page = 1,
        int $limit = 30
    )
    {
        $course = get_course($courseId);

        $offset = ($page - 1) * $limit;
        $param = new RetrieveUsersParam(
            $courseId,
            $search,
            $limit,
            $offset,
            $sort
        );

        list($users, $count) = $this->repository->retrieveUsers($param);

        return [
            'page' => $page,
            'total' => $count,
            'users' => array_map(function ($user) use ($course) {
                $progress = (int) progress::get_course_progress_percentage($course, $user->id) ?? 0;
                $user->progress = $progress . '%';
                $user->has_certificate = !!$user->has_certificate;
                $user->has_history = $this->hasHistory($user->id, $course->id, $user->name);
                return $user;
            }, $users),
        ];
    }

    private function hasHistory($userId, $courseId, $userFullName)
    {
        $certificate = $this->certificateRepository->findByUserIdAndCourse(
            $userId,
            $courseId
        );

        if(empty($certificate)) {
            return false;
        }

        return !!PdfService::getService()->getHistoryGradeUrl(
            $certificate->id,
            $userFullName
        );
    }

    public function getUserGradeToPdf(
        int $userId,
        int $courseId
    )
    {
        $grades = $this->gradeRepository->getUserGradeFromCourseCompletionCriteria($courseId, $userId);
        $activity1 = $grades[0]->grade ?? 0;
        $activity2 = $grades[1]->grade ?? 0;
        $activity3 = $grades[2]->grade ?? 0;
        $activity4 = $grades[3]->grade ?? 0;
        $activity5 = $grades[4]->grade ?? 0;

        $total = array_reduce($grades, function ($current, $next) {
            return $current + $next->grade;
        }, 0);

        $average = 0;
        if($total > 0) {
            $average = ceil($total / count($grades));
        }

        $user = $this->repository->getUser($userId);
        $fullname = $user->firstname . ' ' . $user->lastname;
        return [
            'grade1' => ceil($activity1),
            'grade2' => ceil($activity2),
            'grade3' => ceil($activity3),
            'grade4' => ceil($activity4),
            'grade5' => ceil($activity5),
            'average' => $average,
            'fullname' => $fullname,
            'document' => $this->formatCpfDocument($user->document)
        ];
    }

    private function formatCpfDocument(?string $cpf)
    {
        if (empty($cpf)) {
            return '';
        }

        $formatedRegex = '/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';

        if (preg_match($formatedRegex, $cpf)) {
            return $cpf;
        }

        $cpf = preg_replace('/\D/', '', $cpf);

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    public static function getService(): UsersService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}