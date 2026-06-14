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

    protected static ?string $navigationIcon = 'heroicon-o-key';

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
                    ->disabledOn('edit')
                    ->required(fn($get) => !$get('category_id')),
                Select::make('category_id')
                    ->label('Kategori yang Diminta')
                    ->relationship('category', 'name')
                    ->disabledOn('edit')
                    ->required(fn($get) => !$get('video_id')),
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
                TextColumn::make('video.title')
                    ->label('Judul Video')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'expired' => 'gray',
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
                        'expired' => 'Expired',
                    ]),
                Tables\Filters\Filter::make('type')
                    ->label('Tipe Request')
                    ->form([
                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'video' => 'Per Video',
                                'category' => 'Per Kategori',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['type'] === 'video',
                                fn (Builder $query) => $query->whereNotNull('video_id')
                            )
                            ->when(
                                $data['type'] === 'category',
                                fn (Builder $query) => $query->whereNotNull('category_id')
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Beri Akses')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(AccessRequest $record): bool => $record->status !== 'approved')
                    ->form([
                        DateTimePicker::make('valid_until')
                            ->label('Batas Waktu Akses')
                            ->required()
                            ->default(now()->addDay())
                            ->displayFormat('d M Y H:i'),
                    ])
                    ->action(function (AccessRequest $record, array $data): void {
                        $record->update([
                            'status' => 'approved',
                            'valid_until' => $data['valid_until'],
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Beri Akses Menonton')
                    ->modalDescription('Tentukan batas waktu akses menonton untuk customer ini.')
                    ->modalSubmitActionLabel('Setujui & Beri Akses'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(AccessRequest $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (AccessRequest $record): void {
                        $record->update([
                            'status' => 'rejected',
                            'valid_until' => null,
                        ]);
                    })
                    ->modalHeading('Tolak Permintaan Akses')
                    ->modalDescription('Apakah Anda yakin ingin menolak permintaan akses menonton ini?')
                    ->modalSubmitActionLabel('Tolak Akses'),

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
