<?php

namespace local_certificate_management\local\services;

use local_certificate_management\local\repositories\CourseRepository;
use local_certificate_management\local\repositories\params\RetrieveCoursesParam;

class CourseService
{
    private static ?CourseService $service = null;
    private CourseRepository $repository;

    public function __construct()
    {
        $this->repository = new CourseRepository();
    }

    public function retrieveCourse(
        string $search = '',
        string $sort = 'ASC',
        int $page = 1,
        int $limit = 30
    )
    {
        $offset = ($page - 1) * $limit;
        $param = new RetrieveCoursesParam(
            $search,
            $limit,
            $offset,
            $sort
        );

        list($courses, $count) = $this->repository->retrieveCourses($param);

        return [
            'page' => $page,
            'total' => $count,
            'courses' => $courses,
        ];
    }


    public static function getService(): CourseService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}