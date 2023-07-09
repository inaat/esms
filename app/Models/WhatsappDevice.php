<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappDevice extends Model
{
    use HasFactory;
    protected $table = 'wa_device';
    protected $guarded = []; 
}
