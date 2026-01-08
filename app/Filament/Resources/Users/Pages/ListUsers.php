<?php // DEPRECATED: Filament pages removed.

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords {}
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
