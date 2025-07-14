<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PatientResource\Pages;
use App\Filament\Admin\Resources\PatientResource\RelationManagers;
use App\Filament\Admin\Resources\PatientResource\Widgets\PatientOverview;
use App\Filament\Admin\Resources\PatientResource\Widgets\PatientTypeOverview;
use App\Models\Patient;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PatientResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = Patient::class;

    protected static int $globalSearchResultsLimit = 20; // limit so ket qua tim kiem toan cuc

    protected static ?string $modelLabel = 'Bệnh nhân'; // customize ten cua model

    protected static bool $hasTitleCaseModelLabel = false; // khong viet hoa chu cai dau tien trong ten cua model

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $activeNavigationIcon = 'heroicon-s-user'; // icon hien thi khi trang hien tai dang duoc chon

    protected static ?string $navigationBadgeTooltip = 'The number of patients in the system'; // customize tooltip hien thi khi hover vao badge

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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'cat' => 'Cat',
                        'dog' => 'Dog',
                        'rabbit' => 'Rabbit',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required()
                    ->maxDate(now())
                    ->native(false)
                    ->placeholder('dd/mm/YYYY')
                    ->displayFormat('d/m/Y'),
                Forms\Components\Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone number')
                            ->tel()
                            ->required(),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('date_of_birth')->date('d/m/Y')
                ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                ->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'cat' => 'Cat',
                        'dog' => 'Dog',
                        'rabbit' => 'Rabbit',
                    ]),
            ], layout: FiltersLayout::Modal)
//            ->hiddenFilterIndicators() // ẩn chi tiết bộ lọc đã áp dụng
            ->actions([
//                ActionGroup::make([ // sử dụng ActionGroup để nhóm các hành động vào icon menu
                    Tables\Actions\ViewAction::make()
                        ->label('Xem')
                        ->modalHeading('Thông tin bệnh nhân')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng')
                        ->infolist([
                            Grid::make(2)->schema([
                                TextEntry::make('name')->label('Tên'),
                                TextEntry::make('type')->label('Loài'),
                                TextEntry::make('date_of_birth')->label('Ngày sinh')->date('d/m/Y'),
                                TextEntry::make('owner.name')->label('Chủ sở hữu'),
                            ]),
                        ])
                        ->slideOver(),
                    Tables\Actions\EditAction::make()
                        ->label('Sửa'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Xóa'),
//                ])->tooltip('Actions')->size(ActionSize::Small),
            ])
            ->extremePaginationLinks() // hiển thị nút tới trang cuối/trang đầu tiên paginate
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
//            ->checkIfRecordIsSelectableUsing(
//                fn (Model|\Illuminate\Database\Eloquent\Model $record): bool => $record->type === 'cat', // chỉ cho phép chọn checkbox type = cat
//            );
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TreatmentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
//            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'type', 'owner.name'];
    }

    public static function getGlobalSearchResultDetails(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        // Customize hien thi chi tiet ket qua tim kiem toan cuc
        return [
            'Name' => $record->name,
            'Owner name' => $record->owner->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // eager-load relationship to optimize performance
        return parent::getGlobalSearchEloquentQuery()->with(['owner']);
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
