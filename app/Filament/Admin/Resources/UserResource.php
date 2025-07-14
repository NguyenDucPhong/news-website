<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\GlobalSearch\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Người dùng'; // customize ten cua model

    protected static bool $hasTitleCaseModelLabel = false; // khong viet hoa chu cai dau tien trong ten cua model

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $activeNavigationIcon = 'heroicon-s-user';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit')
                    ->maxLength(255),

                static::getRolesFormComponent(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Xem')
                    ->modalHeading('Thông tin bệnh nhân')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(2)->schema([
                            TextEntry::make('name')->label('Tên'),
                            TextEntry::make('email')->label('Email'),
                            TextEntry::make('roles.name')->label('Vai trò'),
                        ]),
                    ]),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa')
                    ->successNotificationTitle('Đã xóa người dùng thành công'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRolesFormComponent()
    {
        return Select::make('roles')
//            ->multiple()
            ->relationship('roles', 'name')
            ->label('Roles')
            ->preload();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        // Customize hien thi chi tiet ket qua tim kiem toan cuc
        return [
            'Name' => $record->name,
            'Email' => $record->email,
        ];
    }

    public static function getGlobalSearchResultUrl(Model|\Illuminate\Database\Eloquent\Model $record): string
    {
        // link chuyen huong sau khi bam vao ban ghi
        return self::getUrl('edit', ['record' => $record]);
    }

    public static function getGlobalSearchResultActions(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        // cac hanh dong hien thi trong ket qua tim kiem toan cuc
        return [
            Action::make('edit')
                ->label('Sửa')
                ->button()
                ->url(static::getUrl('edit', ['record' => $record]), true),
        ];
    }

    public static function getNavigationBadge(): ?string // customize so luong hien thi trong badge
    {
        return static::getModel()::count();
    }

//    public static function getNavigationBadgeColor(): ?string // customize mau sac cua badge
//    {
//        return static::getModel()::count() > 10 ? 'danger' : 'success';
//    }
}
