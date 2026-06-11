<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    // Simpan path lama sebelum disimpan
    protected ?string $oldThumbnail = null;
    protected ?string $oldVideoPath = null;

    protected function beforeSave(): void
    {
        $this->oldThumbnail = $this->record->getOriginal('thumbnail');
        $this->oldVideoPath = $this->record->getOriginal('video_path');
    }

    protected function afterSave(): void
    {
        $newThumbnail = $this->record->thumbnail;
        $newVideoPath = $this->record->video_path;

        // Hapus thumbnail lama jika diganti
        if ($this->oldThumbnail && $this->oldThumbnail !== $newThumbnail) {
            Storage::disk('public')->delete($this->oldThumbnail);
        }

        // Hapus file video lama jika diganti
        if ($this->oldVideoPath && $this->oldVideoPath !== $newVideoPath) {
            Storage::disk('public')->delete($this->oldVideoPath);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
