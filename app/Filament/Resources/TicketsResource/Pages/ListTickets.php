<?php

namespace App\Filament\Resources\TicketsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TicketsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketsResource::class;

    protected static ?string $title = 'Tiket';

//    protected static ?string $breadcrumb = 'Daftar';


    protected function getHeaderActions(): array
    {
        return [
//            CreateAction::make(),
        ];
    }
}
