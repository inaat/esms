<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    use HasFactory;

    const PENDING = 1;
	const SCHEDULE = 2;
	const FAILED = 3;
	const SUCCESS = 4;
	const PROCESSING = 5;


	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function whatsappGateway()
	{
		return $this->belongsTo(WhatsappDevice::class, 'whatsapp_id');
	} 
}
