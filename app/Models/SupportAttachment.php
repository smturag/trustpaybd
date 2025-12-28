<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportAttachment extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket');
    }
}
