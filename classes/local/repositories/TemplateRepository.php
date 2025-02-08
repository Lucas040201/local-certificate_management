<?php

namespace local_certificate_management\local\repositories;

class TemplateRepository extends BaseRepository
{
    protected function setTable(): void
    {
        $this->table = 'tool_certificate_templates';
    }

    public function retrieveCertificate()
    {
        return $this->builder->get_records($this->table);
    }
}