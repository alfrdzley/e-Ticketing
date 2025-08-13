<?php

namespace App\Filament\Resources\BookingsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\BookingsResource;
use App\Filament\Resources\BookingsResource\Widgets\BookingRevenueChart;
use App\Filament\Resources\BookingsResource\Widgets\BookingStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         BookingStatsOverview::class,
    //     ];
    // }

    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         BookingRevenueChart::class,
    //     ];
    // }
}
