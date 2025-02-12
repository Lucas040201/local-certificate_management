<?php

namespace local_certificate_management\local\repositories;

class CertificateRepository extends BaseRepository
{

    protected function setTable(): void
    {
        $this->table = 'tool_certificate_issues';
    }

    /**
     * @throws \dml_exception
     */
    public function findAllUserIssueByCourse(
        int $courseId,
        int $userId
    ): array
    {
        return array_values($this->builder->get_records($this->table, [
            'courseid' => $courseId,
            'userid' => $userId,
        ]));
    }

    /**
     * @throws \dml_exception
     */
    public function findById(
        int $id,
    )
    {
        return $this->builder->get_record($this->table, [
            'id' => $id,
        ]);
    }

    public function findByUserIdAndCourse(
        int $userId,
        int $courseId
    )
    {
        return $this->builder->get_record($this->table, [
            'userid' => $userId,
            'courseid' => $courseId
        ]);
    }
}