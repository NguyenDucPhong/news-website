<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Models\Post;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Post::class;
    protected static int $globalSearchResultsLimit = 20;  // limit so ket qua tim kiem toan cuc

    protected static ?string $modelLabel = 'Bài viết'; // customize nhan cua model

    protected static bool $hasTitleCaseModelLabel = false; // khong viet hoa chu cai dau tien trong nhan cua model

    protected static ?string $activeNavigationIcon = 'heroicon-s-newspaper'; // icon hien thi khi trang hien tai dang duoc chon

    protected static ?string $navigationBadgeTooltip = 'The number of owners in the system'; // customize tooltip hien thi khi hover vao badge

    protected static ?string $navigationGroup = 'Quản lý bài viết'; // customize nhom dieu huong ben trai
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
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
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('excerpt')
                    ->columnSpanFull(),
                Forms\Components\MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('featured_image_url')
                    ->image(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Archived',
                    ])
                    ->default(0),
                Forms\Components\DateTimePicker::make('published_at'),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_url')
                    ->disk('public')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Archived',
                        default => 'Unknown',
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
