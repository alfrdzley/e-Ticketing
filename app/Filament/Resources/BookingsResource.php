<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use App\Models\Event;
use App\Filament\Resources\BookingsResource\Pages\ListBookings;
use App\Filament\Resources\BookingsResource\Pages\CreateBookings;
use App\Filament\Resources\BookingsResource\Pages\EditBookings;
use App\Models\Booking;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class BookingsResource extends Resource
{
    protected static ?string $navigationGroup = 'Manage Events';

    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?string $modelLabel = 'Bookings';

    protected static ?string $pluralModelLabel = 'Booking';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                Section::make('Informasi Booking')
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->disabled()
                            ->default(fn() => 'BOOK-' . strtoupper(uniqid())),

                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('event_id')
                            ->label('Event')
                            ->relationship('event', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $event = Event::find($state);
                                    if ($event) {
                                        $set('price', $event->price);
                                    }
                                }
                            }),
                    ])->columns(3),

                Section::make('Detail Pemesan')
                    ->schema([
                        TextInput::make('booker_name')
                            ->label('Nama Pemesan')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('booker_email')
                            ->label('Email Pemesan')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('booker_phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])->columns(3),

                Section::make('Harga & Payment')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Jumlah Tiket')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $quantity = $get('quantity') ?: 1;
                                $price = $get('price') ?: 0;
                                $set('total_amount', $quantity * $price);
                                $set('final_amount', $quantity * $price);
                            }),

                        // TextInput::make('price')
                        //     ->label('Harga per Tiket')
                        //     ->numeric()
                        //     ->prefix('Rp')
                        //     ->dehydrated(true) // Ensure the value is saved
                        //     ->reactive()
                        //     ->afterStateUpdated(function (callable $set, callable $get) {
                        //         $quantity = $get('quantity') ?: 1;
                        //         $price = $get('price') ?: 0;
                        //         $set('total_amount', $quantity * $price);
                        //         $set('final_amount', $quantity * $price);
                        //     }),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        // TextInput::make('final_amount')
                        //     ->label('Final Amount')
                        //     ->numeric()
                        //     ->prefix('Rp')
                        //     ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Bank Transfer',
                                'cash' => 'Cash',
                                'other' => 'Other',
                            ])
                            ->nullable(),
                    ])->columns(3),

                Section::make('Waktu & Catatan')
                    ->schema([
                        DateTimePicker::make('expired_at')
                            ->label('Batas Waktu Pembayaran')
                            ->default(now()->addDays(1))
                            ->required(),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('booker_name')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('booker_email')
                    ->label('Email Pemesan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable(),

                TextColumn::make('tickets_count')
                    ->label('Tickets Generated')
                    ->counts('tickets')
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->tickets_count === $record->quantity ? 'success' : 'warning'),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('final_amount')
                    ->label('Total Akhir')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal Booking')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('expired_at')
                    ->label('Batas Bayar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([]);
        // ->recordActions([
        // ])
        // ->toolbarActions([
        // ]);
    }

    /**
     * Modifikasi query untuk menyertakan count dari relasi
     */
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBookings::route('/create'),
            'edit' => EditBookings::route('/{record}/edit'),
        ];
    }
}
