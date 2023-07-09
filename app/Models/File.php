<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $hidden = ["deleted_at", "created_at", "updated_at"];
    protected $appends = array('file_extension', 'type_detail');

    protected static function boot() {
        parent::boot();
        static::deleting(function ($file) { // before delete() method call this
            if (Storage::disk('public')->exists($file->file_url)) {
                Storage::disk('public')->delete($file->file_url);
            }
        });
    }

    public function modal() {
        return $this->morphTo();
    }

    //Getter Attributes
    public function getFileUrlAttribute($value) {
        if ($this->type == 1 || $this->type == 3) {
            // IF type is File Upload or Video Upload then add Full URL.
            return url(Storage::url($value));
        } else {
            // ELSE return the value as it is.
            return $value;
        }
    }

    //Getter Attributes
    public function getFileThumbnailAttribute($value) {
        if (!empty($value)) {
            return url(Storage::url($value));
        } else {
            return "";
        }
    }

    public function getFileExtensionAttribute() {
        if (!empty($this->file_url)) {
            return pathinfo(url(Storage::url($this->file_url)), PATHINFO_EXTENSION);
        } else {
            return "";
        }
    }

    public function getTypeDetailAttribute() {
        //1 = File Upload, 2 = Youtube Link, 3 = Video Upload, 4 = Other Link
        if ($this->type == 1) {
            return "File Upload";
        } elseif ($this->type == 2) {
            return "Youtube Link";
        } elseif ($this->type == 3) {
            return "Video Upload";
        } elseif ($this->type == 4) {
            return "Other Link";
        }
    }
}
