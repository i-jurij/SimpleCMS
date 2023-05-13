<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Upload
{
    public string $disk;
    public string $folder;
    public string $filename;

    public function UploadFile(UploadedFile $file)
    {
        $name_of_file = !is_null($this->filename) ? $this->filename : Str::random(10);
        $folder = !is_null($this->folder) ? $this->folder : null;
        $disk = !is_null($this->disk) ? $this->disk : 'public';

        return $file->storeAs(
            $folder,
            $name_of_file.'.'.$file->getClientOriginalExtension(),
            $disk
        );
    }

    public function deleteFile($path, $disk = 'public')
    {
        if (Storage::disk($disk)->delete($path)) {
            return true;
        } else {
            return false;
        }
    }
}
