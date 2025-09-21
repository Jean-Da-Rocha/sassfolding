<?php

declare(strict_types=1);

namespace Modules\Users\Tables\Concerns;

// TODO: move to dedicated folder
trait HasPerPageLimitation
{
    protected function getRecordsPerPage(): int
    {
        return $this->getRequest()->integer('per_page', 5);
    }
}
