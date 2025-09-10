<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketsResource\Pages\CreateTickets;
use App\Filament\Resources\TicketsResource\Pages\EditTickets;
use App\Filament\Resources\TicketsResource\Pages\ListTickets;
use App\Models\Ticket;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TicketsResource extends Resource
{
    protected static ?string $navigationGroup = 'Manage Events';

    protected static ?string $model = Ticket::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                Section::make('Detail Tiket & Peserta')
                    ->schema([
                        Select::make('booking_id')
                            ->label('Kode Booking')
                            ->relationship('booking', 'booking_code')
                            ->searchable()
                            ->required(),
                        TextInput::make('ticket_code')
                            ->label('Kode Tiket')
                            ->default('TICKET-'.Str::upper(Str::random(10)))
                            ->required(),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('attendee_name')
                                    ->label('Nama Peserta')
                                    ->required(),
                                TextInput::make('attendee_email')
                                    ->label('Email Peserta')
                                    ->email(),
                                TextInput::make('attendee_phone')
                                    ->label('Telepon Peserta')
                                    ->tel(),
                            ])
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('booking.event.name')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ticket_code')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attendee_name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attendee_email')
                    ->label('Email Peserta')
                    ->searchable(),
                TextColumn::make('attendee_phone')
                    ->label('Telepon Peserta'),
                TextColumn::make('booking.status')
                    ->label('Status Booking')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                IconColumn::make('is_checked_in')
                    ->label('Status Check-in')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                TextColumn::make('checked_in_at')
                    ->label('Waktu Check-in')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('booking.created_at')
                    ->label('Tanggal Booking')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTickets::route('/create'),
            'edit' => EditTickets::route('/{record}/edit'),
        ];
    }
}
