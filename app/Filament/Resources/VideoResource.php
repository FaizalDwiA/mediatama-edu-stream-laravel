<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

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
                    ->fetchFileInformation(false)
                    ->nullable()
                    ->saveUploadedFileUsing(function ($file) {
                        $filename = 'thumbnail_' . uniqid() . '.webp';
                        $dirPath = storage_path('app/public/thumbnails');
                        $path = $dirPath . '/' . $filename;

                        // Pastikan folder thumbnails ada
                        if (!file_exists($dirPath)) {
                            mkdir($dirPath, 0755, true);
                        }

                        try {
                            // Menggunakan Intervention Image (v3) untuk membaca file
                            $image = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());

                            // Lakukan resize jika lebar lebih dari 1280px secara proporsional
                            if ($image->width() > 1280) {
                                $image->scale(width: 1280);
                            }

                            // Konversi ke format WebP dengan kualitas 80 (sangat hemat size & tetap tajam)
                            $encoded = $image->toWebp(80);
                            file_put_contents($path, (string) $encoded);

                            return 'thumbnails/' . $filename;
                        } catch (\Exception $e) {
                            // Fallback jika terjadi kesalahan
                            return $file->storeAs('thumbnails', $file->hashName(), 'public');
                        }
                    }),
                Placeholder::make('status_info')
                    ->label('Status Pemrosesan Video')
                    ->content(fn($record) => $record ? match ($record->status) {
                        'processing' => new HtmlString('<span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 9999px;"><svg class="animate-spin" fill="none" viewBox="0 0 24 24" style="width: 16px; height: 16px; margin-right: 4px; display: inline;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sedang Diproses (Kompresi FFmpeg sedang berjalan di background)</span>'),
                        'ready' => new HtmlString('<span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 9999px;">✓ Selesai (Video siap ditonton)</span>'),
                        'failed' => new HtmlString('<span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 9999px;">✗ Gagal (Terjadi kesalahan saat kompresi)</span>'),
                        default => 'Tidak diketahui',
                    } : 'Akan otomatis dikompresi setelah disimpan')
                    ->visible(fn($record) => $record !== null),
                FileUpload::make('video_path')
                    ->label('Upload File Video (.mp4)')
                    ->directory('videos/temp')
                    ->visibility('public')
                    ->acceptedFileTypes(['video/mp4'])
                    ->maxSize(1048576) // Maksimal 1GB (1024 * 1024 KB)
                    ->fetchFileInformation(false)
                    ->required()
                    ->saveUploadedFileUsing(function ($file) {
                        $filename = 'temp_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        return $file->storeAs('videos/temp', $filename, 'public');
                    }),
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

                TextColumn::make('status')
                    ->label('Status Kompresi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'processing' => 'warning',
                        'ready' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'processing' => 'Sedang Diproses',
                        'ready' => 'Selesai',
                        'failed' => 'Gagal',
                        default => $state,
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'processing' => 'heroicon-m-arrow-path',
                        'ready' => 'heroicon-m-check-circle',
                        'failed' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),

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
                // Filter Status yang baru ditambahkan
                SelectFilter::make('status')
                    ->label('Saring Status')
                    ->options([
                        'processing' => 'Sedang Diproses',
                        'ready' => 'Selesai',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
