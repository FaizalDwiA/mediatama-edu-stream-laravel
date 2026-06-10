<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?string $pluralModelLabel = 'Activity Logs';

    protected static ?string $modelLabel = 'Activity Log';

    public static function form(Form $form): Form
    {
        // Karena bersifat read-only, form tidak perlu diisi secara detail
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_name')
                    ->label('Nama Admin')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('menu')
                    ->label('Menu / Modul')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('action')
                    ->label('Aksi (CRUD)')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Create' => 'success',
                        'Read' => 'info',
                        'Update' => 'warning',
                        'Delete' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('target_id')
                    ->label('ID Target')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Waktu Aktivitas')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('menu')
                    ->label('Filter Menu')
                    ->options([
                        'Category' => 'Category',
                        'Video' => 'Video',
                        'User' => 'User',
                        'Access Request' => 'Access Request',
                    ]),
                SelectFilter::make('action')
                    ->label('Filter Aksi')
                    ->options([
                        'Create' => 'Create',
                        'Read' => 'Read',
                        'Update' => 'Update',
                        'Delete' => 'Delete',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
