<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    const DISK = 'public';
    const TYPE = 'default';
    const FOLDER_DOCUMENT = 'documents';

    public static function manageUploadDocument($files,$modelId,$model, $folder = Document::FOLDER_DOCUMENT, $disk = Document::DISK, $type = Document::TYPE)
    {
        $disk = config('filesystems.default');
        if ( is_array($files) ) {
            $fileIds = [];

            foreach ($files as $file) {
                array_push($fileIds, self::handleUpload($file, $modelId,$model,$folder, $disk, $type));
            }

            return $fileIds;
        }

        return self::handleUpload($files,$modelId,$model, $folder, $disk, $type);
    }



    private static function handleUpload($file,$modelId,$model, $folder, $disk, $type)
    {
        $name = $file->getClientOriginalName();
        $fileNameWithoutExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . str_replace('-', '', Str::slug($fileNameWithoutExtension)) . "." . $extension;
        $path = $folder . '/' . $fileName;
        $image=$file;
        $document = self::saveDocumentRecord($file, $modelId,$model,$path, $name, $fileName, $type, $disk);
        $image->move(public_path('documents'), $fileName);
        return $document->id;
    }
    private static function saveDocumentRecord($file, $modelId, $model, $path, $name, $filename, $type, $disk)
    {
//        dd();
        return Document::query()->create([
            'author_id'  => auth()->id(),
            'model_id'   => $modelId,
            'model_type' => $model,
            'type'       => $type,
            'path'       => $path,
            'filename'   => $filename,
            'name'       => $name,
            'disk'       => $disk,
            'mimetype'   => $file->getMimeType(),
            'size'       => $file->getSize(),
            'header'     => $file->getClientMimeType(),
            'extension'  => $file->getClientOriginalExtension(),
        ]);
    }

}
