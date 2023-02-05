<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteBookStatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'note_book_status';

}
