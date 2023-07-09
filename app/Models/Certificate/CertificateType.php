<?php

namespace App\Models\Certificate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateType extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];   
        
    public static function forDropdown($show_none = false)
    {
        $query=CertificateType::pluck('name', 'id');
        $certificate_type=$query;
        if ($show_none) {
            $certificate_type->prepend(__('lang.none'), '');
        }

        return  $certificate_type;
    }
}
