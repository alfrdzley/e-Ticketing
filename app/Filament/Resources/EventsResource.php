<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventsResource\Pages\CreateEvents;
use App\Filament\Resources\EventsResource\Pages\EditEvents;
use App\Filament\Resources\EventsResource\Pages\ListEvents;
use App\Models\Event;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class EventsResource extends Resource
{
    protected static ?string $navigationGroup = 'Manage Events';

    protected static ?string $model = Event::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
//            ->components([
//                Group::make()
//                    ->schema([
//                        Section::make('Informasi Utama Event')
//                            ->schema([
//                                TextInput::make('name')
//                                    ->label('Nama Event')
//                                    ->live(onBlur: true)
//                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
//                                    ->required(),
//                                TextInput::make('slug')
//                                    ->required()
//                                    ->unique(Event::class, 'slug', ignoreRecord: true)
//                                    ->readonly(),
//                                RichEditor::make('description')
//                                    ->label('Deskripsi')
//                                    ->columnSpan('full')
//                                    ->required(),
//                                FileUpload::make('banner_image_url')
//                                    ->label('Gambar Banner')
//                                    ->directory('event-banners')
//                                    ->image()
//                                    ->imageEditor()
//                                    ->columnSpan('full'),
//                                TextInput::make('location')
//                                    ->label('Nama Lokasi (Gedung, Tempat, dll)')
//                                    ->required(),
//                                Textarea::make('address')
//                                    ->label('Alamat Lengkap'),
//
//                                DateTimePicker::make('start_date')
//                                    ->label('Tanggal Mulai')
//                                    ->required(),
//                                DateTimePicker::make('end_date')
//                                    ->label('Tanggal Selesai')
//                                    ->required()
//                                    ->after('start_date'),
//                                TextInput::make('price')
//                                    ->label('Harga')
//                                    ->numeric()
//                                    ->prefix('Rp')
//                                    ->required(),
//                                TextInput::make('quota')
//                                    ->label('Kuota')
//                                    ->numeric()
//                                    ->required(),
//                            ]),
//                    ])
//                    ->columnSpan(['lg' => 2]),
//                Group::make()
//                    ->schema([
//                        Section::make('Kategori & Penyelenggara')
//                            ->schema([
//                                Select::make('category_id')
//                                    ->label('Kategori')
//                                    ->relationship('category', 'name')
//                                    ->searchable()
//                                    ->required()
//                                    ->createOptionForm([
//                                        TextInput::make('name')
//                                            ->label('Nama Kategori')
//                                            ->required(),
//                                        TextInput::make('slug')
//                                            ->label('Slug')
//                                            ->unique(Event::class, 'slug', ignoreRecord: true)
//                                            ->required(),
//                                        Textarea::make('description'),
//                                        ColorPicker::make('color')
//                                            ->label('Warna Kategori')
//                                            ->default('#4F46E5')
//                                            ->required(),
//                                        TextInput::make('icon')
//                                            ->label('Ikon (FontAwesome, Heroicons, dll)')
//                                            ->default('heroicon-o-tag')
//                                            ->required(),
//                                        ToggleButtons::make('is_active')
//                                            ->label('Aktif')
//                                            ->options([
//                                                true => 'Ya',
//                                                false => 'Tidak',
//
//                                            ])
//                                    ]),
//                                Select::make('organizer_id')
//                                    ->label('Penyelenggara (Organizer)')
//                                    ->relationship('organizer', 'name')
//                                    ->searchable()
//                                    ->required()
//                                    ->createOptionForm([
//                                        TextInput::make('name')
//                                            ->label('Nama Penyelenggara')
//                                            ->live(onBlur: true)
//                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
//                                            ->required(),
//                                        Textarea::make('description')
//                                            ->label('Deskripsi'),
//                                        TextInput::make('slug')
//                                            ->label('Slug')
//                                            ->unique(table: 'organizers', column: 'slug', ignoreRecord: true)
//                                            ->required(),
//                                        TextInput::make('contact_email')
//                                            ->required()
//                                            ->label('Email Kontak')
//                                            ->email(),
//                                        TextInput::make('contact_phone')
//                                            ->label('Telepon Kontak'),
//                                        ToggleButtons::make('is_active')
//                                            ->label('Aktif')
//                                            ->options([
//                                                true => 'Ya',
//                                                false => 'Tidak',
//                                            ])->default(true),
//                                    ]),
//                                ToggleButtons::make('status')
//                                    ->options([
//                                        'draft' => 'Draft',
//                                        'published' => 'Published',
//                                        'cancelled' => 'Cancelled',
//                                        'completed' => 'Completed',
//                                    ])
//                                    ->default('draft')
//                                    ->inline()
//                                    ->required(),
//                            ]),
//                    ])
//                    ->columnSpan(['lg' => 1]),
//            ])->columns(3);
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Informasi Event')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Event')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                ->required(),
                            TextInput::make('slug')
//                                ->required()
                                ->unique(Event::class, 'slug', ignoreRecord: true)
                                ->readonly(),
                            RichEditor::make('description')
                                ->label('Deskripsi')
                                ->columnSpan('full')
                                ->required(),
                            TextInput::make('location')
                                ->label('Nama Lokasi (Gedung, Tempat, dll)')
                                ->required(),
                            Textarea::make('address')
                                ->label('Alamat Lengkap'),
                            Group::make()
                                ->schema([
                                    Section::make('Kategori & Penyelenggara')
                                        ->schema([
                                            Select::make('category_id')
                                                ->label('Kategori')
                                                ->relationship('category', 'name')
                                                ->searchable()
                                                ->required()
                                                ->createOptionForm([
                                                    TextInput::make('name')
                                                        ->label('Nama Kategori')
                                                        ->required(),
                                                    TextInput::make('slug')
                                                        ->label('Slug')
                                                        ->unique(Event::class, 'slug', ignoreRecord: true)
                                                        ->required(),
                                                    Textarea::make('description'),
                                                    ColorPicker::make('color')
                                                        ->label('Warna Kategori')
                                                        ->default('#4F46E5')
                                                        ->required(),
                                                    TextInput::make('icon')
                                                        ->label('Ikon (FontAwesome, Heroicons, dll)')
                                                        ->default('heroicon-o-tag')
                                                        ->required(),
                                                    ToggleButtons::make('is_active')
                                                        ->label('Aktif')
                                                        ->options([
                                                            true => 'Ya',
                                                            false => 'Tidak',
                                                        ]),
                                                ]),
                                            Select::make('organizer_id')
                                                ->label('Penyelenggara (Organizer)')
                                                ->relationship('organizer', 'name')
                                                ->searchable()
                                                ->required()
                                                ->createOptionForm([
                                                    TextInput::make('name')
                                                        ->label('Nama Penyelenggara')
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                                        ->required(),
                                                    Textarea::make('description')
                                                        ->label('Deskripsi'),
                                                    TextInput::make('slug')
                                                        ->label('Slug')
                                                        ->unique(table: 'organizers', column: 'slug', ignoreRecord: true)
                                                        ->required(),
                                                    TextInput::make('contact_email')
                                                        ->required()
                                                        ->label('Email Kontak')
                                                        ->email(),
                                                    TextInput::make('contact_phone')
                                                        ->label('Telepon Kontak'),
                                                    ToggleButtons::make('is_active')
                                                        ->label('Aktif')
                                                        ->options([
                                                            true => 'Ya',
                                                            false => 'Tidak',
                                                        ])->default(true),
                                                ]),
                                        ]),
                                    ToggleButtons::make('status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'cancelled' => 'Cancelled',
                                            'completed' => 'Completed',
                                        ])
                                        ->default('draft')
                                        ->inline()
                                        ->required(),
                                ]),
                        ]),
                    Wizard\Step::make('Upload Media')
                        ->schema([
                            FileUpload::make('banner_image_url')
                                ->label('Gambar Banner')
                                ->directory('event-banners')
                                ->image()
                                ->imageEditor()
                                ->columnSpan('full'),
                            DateTimePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required(),
                            DateTimePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required()
                                ->after('start_date'),
                        ]),
                    Wizard\Step::make('Price & Kuota')
                        ->schema([
                            TextInput::make('price')
                                ->label('Harga')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            TextInput::make('quota')
                                ->label('Kuota')
                                ->numeric()
                                ->required(),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Event & Lokasi')
                    ->description(fn (Event $record): string => $record->location)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Jadwal Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // TextColumn::make('quota')
                //     ->label('Kuota')
                //     ->formatStateUsing(function ($state, Event $record) {
                //         $booked = (int) $state;
                //         $quota = $record->quota;
                //         $remaining = $quota - $booked;

                //         return "{$booked}/{$quota} (Sisa: {$remaining})";
                //     })
                //     ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
            ])
            ->actions([
                \Filament\Tables\Actions\ActionGroup::make([
                    \Filament\Tables\Actions\ViewAction::make(),
                    \Filament\Tables\Actions\EditAction::make(),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withSum('bookings as bookings_sum_quantity', 'quantity');
    }

    public static function getRecordRouteKeyName(): string
    {
        return 'ulid';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvents::route('/create'),
            'edit' => EditEvents::route('/{record}/edit'),
        ];
    }
}
