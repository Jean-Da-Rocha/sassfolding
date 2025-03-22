<?php

declare(strict_types=1);

namespace App\Tables\Concerns;

trait HasPerPageLimitation
{
    protected function getRecordsPerPage(): int
    {
        return $this->getRequest()->integer('per_page', 10);
    }
}
