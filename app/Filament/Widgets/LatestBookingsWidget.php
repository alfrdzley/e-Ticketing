<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Latest Bookings';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()->with(['user', 'event'])->latest()->limit(10)
            )
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Booking Code')
                    ->searchable()
                    ->copyable(),
                
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->default('Guest'),
                
                TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable()
                    ->limit(30),
                
                TextColumn::make('booker_name')
                    ->label('Booker Name')
                    ->searchable(),
                
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->badge(),
                
                TextColumn::make('final_amount')
                    ->label('Amount')
                    ->money('IDR'),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                
                TextColumn::make('created_at')
                    ->label('Booked At')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                ]);
            // ->actions([
            //     Action::make('view')
            //         ->url(fn (Booking $record): string => route('filament.admin.resources.bookings.edit', $record))
            //         ->icon('heroicon-m-eye'),
            // ]);
    }
}
