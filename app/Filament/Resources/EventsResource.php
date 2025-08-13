<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Set;
use App\Filament\Resources\EventsResource\Pages\ListEvents;
use App\Filament\Resources\EventsResource\Pages\CreateEvents;
use App\Filament\Resources\EventsResource\Pages\EditEvents;
use App\Models\Event;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class EventsResource extends Resource
{
    protected static ?string $navigationGroup = 'Manage Events';

    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';


    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Informasi Utama Event')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Event')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(Event::class, 'slug', ignoreRecord: true)
                                    ->readonly(),
                                RichEditor::make('description')
                                    ->label('Deskripsi')
                                    ->columnSpan('full')
                                    ->required(),
                                FileUpload::make('banner_image_url')
                                    ->label('Gambar Banner')
                                    ->directory('event-banners')
                                    ->image()
                                    ->imageEditor()
                                    ->columnSpan('full'),
                            ])->columns(2),

                        Section::make('Kebijakan & Kontak')
                            ->schema([
                                RichEditor::make('terms_conditions')
                                    ->label('Syarat & Ketentuan'),
                                RichEditor::make('refund_policy')
                                    ->label('Kebijakan Refund'),
                                TextInput::make('contact_email')
                                    ->label('Email Kontak')
                                    ->email(),
                                TextInput::make('contact_phone')
                                    ->label('Telepon Kontak')
                                    ->tel(),
                            ])->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status & Jadwal')
                            ->schema([
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
                                DateTimePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->required(),
                                DateTimePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                                    ->required()
                                    ->after('start_date'),
                            ]),

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
                                            ])
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
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
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
                                    ])
                            ]),

                        Section::make('Lokasi')
                            ->schema([
                                TextInput::make('location')
                                    ->label('Nama Lokasi (Gedung, Tempat, dll)')
                                    ->required(),
                                Textarea::make('address')
                                    ->label('Alamat Lengkap'),
                            ]),

                        Section::make('Harga & Kuota')
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
                        Section::make('SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title'),
                                Textarea::make('meta_description')
                                    ->label('Meta Description'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner_image_url')
                    ->label('Banner')
                    ->size(60)
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama Event & Lokasi')
                    ->description(fn(Event $record): string => $record->location)
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'draft',
                        'success' => 'published',
                        'danger' => 'cancelled',
                        'warning' => 'completed',
                    ])
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Jadwal Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('quota')
                    ->label('Kuota')
                    ->formatStateUsing(function ($state, Event $record) {
                        $booked = (int) $state;
                        $quota = $record->quota;
                        $remaining = $quota - $booked;
                        return "{$booked}/{$quota} (Sisa: {$remaining})";
                    })
                    ->sortable(),
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
            ]);
        //            ->actions([
        //                ActionGroup::make([
        //                    Actions\ViewAction::make(),
        //                    Actions\EditAction::make(),
        //                    Actions\DeleteAction::make(),
        //                ]),
        //            ])
        //            ->bulkActions([
        //                Actions\BulkActionGroup::make([
        //                    Actions\DeleteBulkAction::make(),
        //                ]),
        //            ]);
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


    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvents::route('/create'),
            'edit' => EditEvents::route('/{record}/edit'),
        ];
    }
}
