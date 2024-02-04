<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCustomPageNavbar extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
 
    public function custom_pages()
    {
        return $this->hasMany(FrontCustomPage::class, 'front_page_navbar_id');
    }
    public static function forDropdown()
    {
        $query=FrontCustomPageNavbar::
         where('status','publish')->get();
     

        return  $query->pluck('title', 'id');
    }
}