<?php

namespace App\Filament\Resources\EventsResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\EventsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvents extends EditRecord
{
    protected static string $resource = EventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
