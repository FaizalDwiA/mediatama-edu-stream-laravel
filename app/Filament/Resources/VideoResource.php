<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Video';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul Video')
                    ->required()
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Kategori Video')
                    ->relationship('category', 'name')
                    ->placeholder('Pilih Kategori')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->nullable(),
                FileUpload::make('thumbnail')
                    ->label('Gambar Sampul (Thumbnail)')
                    ->directory('thumbnails') // Disimpan di folder storage/app/public/thumbnails
                    ->visibility('public')
                    ->image() // Memastikan file yang diupload wajib berupa gambar (jpg, png, webp)
                    ->imageEditor() // (Opsional) Memunculkan fitur potong/crop gambar bawaan Filament biar keren
                    ->maxSize(2048) // Batasi maksimal ukuran gambar 2MB
                    ->nullable(),
                FileUpload::make('video_path')
                    ->label('Upload File Video (.mp4)')
                    ->directory('videos')
                    ->visibility('public')
                    ->acceptedFileTypes(['video/mp4'])
                    ->maxSize(51200) // Maksimal 50MB
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Video')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->placeholder('Tanpa Kategori'),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50) // Memotong teks jika terlalu panjang
                    ->placeholder('Tidak ada deskripsi'),

                ImageColumn::make('thumbnail')
                    ->label('Sampul')
                    ->circular() // Membuat bentuk bulat kecil miring seperti avatar
                    ->defaultImageUrl(url('images/default-thumbnail.png')), // Gambar cadangan jika video tidak punya sampul


                TextColumn::make('video_path')
                    ->label('Nama File Video')
                    ->icon('heroicon-o-video-camera') // Memberi ikon kamera video biar keren
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // 3. Menambahkan Fitur Menyaring (Filter) Video Berdasarkan Kategori
                SelectFilter::make('category_id')
                    ->label('Saring Kategori')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVideo::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
