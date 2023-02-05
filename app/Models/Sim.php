<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sim extends Model
{
    //protected $table = 'sims';
    protected $table = 'table_images';
    protected $guarded = ['id'];
    //protected $primaryKey  = 'Reg_no';
    public $timestamps = false;

}
