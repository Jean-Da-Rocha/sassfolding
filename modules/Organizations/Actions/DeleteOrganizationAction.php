<?php

declare(strict_types=1);

namespace Modules\Organizations\Actions;

use Modules\Core\Enums\FlashMessage;
use Modules\Organizations\Models\Organization;

final class DeleteOrganizationAction
{
    public function execute(Organization $organization): void
    {
        $organization->delete();

        session()->flash(FlashMessage::Success->value, 'Organization deleted successfully.');
    }
}
