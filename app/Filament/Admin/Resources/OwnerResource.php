<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OwnerResource\Pages;
use App\Filament\Admin\Resources\OwnerResource\RelationManagers;
use App\Models\Owner;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\GlobalSearch\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OwnerResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = Owner::class;

    protected static int $globalSearchResultsLimit = 20;  // limit so ket qua tim kiem toan cuc

    protected static ?string $modelLabel = 'Chủ sở hữu'; // customize nhan cua model

    protected static bool $hasTitleCaseModelLabel = false; // khong viet hoa chu cai dau tien trong nhan cua model

    protected static ?string $activeNavigationIcon = 'heroicon-s-user-group'; // icon hien thi khi trang hien tai dang duoc chon

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationBadgeTooltip = 'The number of owners in the system'; // customize tooltip hien thi khi hover vao badge

    protected static ?string $navigationGroup = 'Quản lý bệnh viện'; // customize nhom dieu huong ben trai

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
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Xem')
                    ->modalHeading('Thông tin chủ sở hữu')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(2)->schema([
                            TextEntry::make('name')->label('Tên'),
                            TextEntry::make('email')->label('Email'),
                            TextEntry::make('phone')->label('Số điện thoại'),
                        ]),
                    ]),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
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
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // dieu kien toan bo query
        return parent::getEloquentQuery();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    public static function getGlobalSearchResultDetails(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        // Customize hien thi chi tiet ket qua tim kiem toan cuc
        return [
            'Name' => $record->name,
            'Email' => $record->email,
            'Phone' => $record->phone,
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
