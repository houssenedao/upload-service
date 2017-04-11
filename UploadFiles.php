<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class UploadFiles
{
    public static function saveFile($file, $type, $path, $query) {
        $url  = $file->store($path);
        $save = $query->uploads()->create([
            'url' => $url,
            'type' => $type
        ]);
        if ($save) return true;
    }

    public static function updateFile($file, $path, $query, $queryFile){
        $url = $file->store($path);

        if(self::deleteFile($queryFile))
            $queryFile->url = $url;

        if ($query->uploads()->save($queryFile))
            return true;
    }

    public static function deleteFile($queryFile) {
        Storage::delete($queryFile->url);
        $queryFile->delete();
        return true;
    }

}
