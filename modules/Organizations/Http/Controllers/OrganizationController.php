<?php

declare(strict_types=1);

namespace Modules\Organizations\Http\Controllers;

use Hybridly\View\Factory as HybridlyView;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Enums\FlashMessage;
use Modules\Core\Http\Controllers\Controller;
use Modules\Organizations\Actions\DeleteOrganizationAction;
use Modules\Organizations\Data\OrganizationData;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Tables\OrganizationTable;

class OrganizationController extends Controller
{
    public function index(): HybridlyView
    {
        return hybridly('organizations::list-organizations', [
            'organizations' => OrganizationTable::make(),
        ]);
    }

    public function create(): HybridlyView
    {
        return hybridly('organizations::create-organization')
            ->base('organizations.index');
    }

    public function store(OrganizationData $data): RedirectResponse
    {
        Organization::create($data->toArray());

        return redirect()->route('organizations.index')
            ->with(FlashMessage::Success->value, 'Organization created successfully.');
    }

    public function edit(Organization $organization): HybridlyView
    {
        return hybridly('organizations::edit-organization', [
            'organization' => OrganizationData::from($organization),
        ])->base('organizations.index');
    }

    public function update(OrganizationData $data, Organization $organization): RedirectResponse
    {
        $organization->update($data->toArray());

        return redirect()->route('organizations.index')
            ->with(FlashMessage::Success->value, 'Organization updated successfully.');
    }

    public function destroy(Organization $organization, DeleteOrganizationAction $action): RedirectResponse
    {
        $action->execute($organization);

        return redirect()->route('organizations.index');
    }
}
