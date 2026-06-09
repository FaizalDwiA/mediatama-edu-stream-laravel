<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessRequestResource\Pages;
use App\Filament\Resources\AccessRequestResource\RelationManagers;
use App\Models\AccessRequest;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccessRequestResource extends Resource
{
    protected static ?string $model = AccessRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->required()
                    ->disabledOn('edit'), // Menghindari admin salah ubah nama pemohon
                Select::make('video_id')
                    ->label('Video yang Diminta')
                    ->relationship('video', 'title')
                    ->required()
                    ->disabledOn('edit'),
                Select::make('status')
                    ->label('Status Persetujuan')
                    ->options([
                        'pending' => 'Pending / Menunggu',
                        'approved' => 'Approved / Disetujui',
                        'rejected' => 'Rejected / Ditolak',
                    ])
                    ->required(),
                DateTimePicker::make('valid_until')
                    ->label('Batas Waktu Akses Menonton')
                    ->required(fn($get) => $get('status') === 'approved') // Wajib diisi jika status disetujui
                    ->displayFormat('d M Y H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama Customer')->searchable(),
                TextColumn::make('video.title')->label('Judul Video')->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('valid_until')
                    ->label('Batas Waktu Akses')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum ditentukan / Habis'),
                TextColumn::make('created_at')->label('Tanggal Request')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAccessRequests::route('/'),
            'create' => Pages\CreateAccessRequest::route('/create'),
            'edit' => Pages\EditAccessRequest::route('/{record}/edit'),
        ];
    }
}
