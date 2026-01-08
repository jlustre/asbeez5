<?php // DEPRECATED: Filament pages removed.

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord {}
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
