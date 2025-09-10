<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SuperAdminManagement extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithHeaderActions;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $view = 'filament.pages.super-admin-management';

    protected static ?string $navigationLabel = 'Super Admin Management';

    protected static ?string $title = 'Super Admin Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin Management';

    public static function canAccess(): bool
    {
        return Auth::check() &&
               (Auth::user()->hasRole('super_admin') || Auth::user()->is_admin);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('assignSuperAdmin')
                ->label('Assign Super Admin')
                ->icon('heroicon-o-user-plus')
                ->form([
                    Select::make('user_id')
                        ->label('Select User')
                        ->options(User::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $user = User::find($data['user_id']);
                    $user->assignRole('super_admin');
                    $user->update(['is_admin' => true]);

                    Notification::make()
                        ->title('Super Admin Assigned')
                        ->body("User {$user->name} has been assigned as Super Admin")
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role('super_admin'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('removeSuperAdmin')
                    ->label('Remove Super Admin')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove Super Admin Access')
                    ->modalDescription('Are you sure you want to remove super admin access from this user?')
                    ->action(function (User $record) {
                        $record->removeRole('super_admin');
                        $record->update(['is_admin' => false]);

                        Notification::make()
                            ->title('Super Admin Removed')
                            ->body("Super admin access removed from {$record->name}")
                            ->success()
                            ->send();
                    })
                    ->hidden(fn (User $record) => $record->id === Auth::id()),
            ]);
    }
}
