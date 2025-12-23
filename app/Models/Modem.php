<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function agent()
    {
        return $this->belongsTo(User::class, 'member_code', 'member_code');
    }
}
