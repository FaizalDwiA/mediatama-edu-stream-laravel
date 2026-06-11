<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('profile_photo')
                    ->label('Foto Profil')
                    ->directory('profile_photos')
                    ->visibility('public')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->maxSize(1024)
                    ->nullable()
                    ->saveUploadedFileUsing(function ($file) {
                        $filename = 'avatar_' . uniqid() . '.webp';
                        $dirPath = storage_path('app/public/profile_photos');
                        $path = $dirPath . '/' . $filename;

                        if (!file_exists($dirPath)) {
                            mkdir($dirPath, 0755, true);
                        }

                        try {
                            $image = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());
                            $image->cover(300, 300);

                            $encoded = $image->toWebp(80);
                            file_put_contents($path, (string) $encoded);

                            return 'profile_photos/' . $filename;
                        } catch (\Exception $e) {
                            return $file->storeAs('profile_photos', $file->hashName(), 'public');
                        }
                    }),
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->placeholder(fn(string $context): string => $context === 'edit' ? 'Kosongkan jika tidak diubah' : ''),
                Select::make('role')
                    ->label('Level Pengguna')
                    ->options([
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                    ])
                    ->required()
                    ->default('customer'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Foto')
                    ->circular(),
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'customer' => 'success',
                    }),
                TextColumn::make('created_at')->label('Terdaftar Pada')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'customer' => 'Customer',
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
