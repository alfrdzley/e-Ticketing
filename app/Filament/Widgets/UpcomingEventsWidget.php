<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingEventsWidget extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Events';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->where('status', 'published')
                    ->where('start_date', '>', now())
                    ->withCount('bookings')
                    ->orderBy('start_date')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Event Name')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('start_date')
                    ->label('Date & Time')
                    ->dateTime('M j, Y \a\t H:i')
                    ->sortable(),

                TextColumn::make('location')
                    ->label('Location')
                    ->limit(30),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('quota')
                    ->label('Quota')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('bookings_count')
                    ->label('Booked')
                    ->badge()
                    ->color(fn ($record) => $record->bookings_count >= $record->quota * 0.8 ? 'danger' : 'success'),

                TextColumn::make('availability')
                    ->label('Availability')
                    ->state(function ($record) {
                        $available = $record->quota - $record->bookings_count;

                        return $available.' left';
                    })
                    ->badge()
                    ->color(function ($record) {
                        $available = $record->quota - $record->bookings_count;
                        if ($available <= 0) {
                            return 'danger';
                        }
                        if ($available <= $record->quota * 0.2) {
                            return 'warning';
                        }

                        return 'success';
                    }),
            ]);
    }
}
