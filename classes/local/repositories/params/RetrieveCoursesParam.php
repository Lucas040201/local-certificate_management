<?php

namespace local_certificate_management\local\repositories\params;

use local_certificate_management\local\utils\MagicMethods;

class RetrieveCoursesParam
{
    use MagicMethods;

    public function __construct(
        protected string $search = '',
        protected int $limit = 10,
        protected int $offset = 0,
        protected string $sort = 'ASC'
    )
    {
    }
}