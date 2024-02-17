<?php

namespace App\Models;
use App\Models\GuestInvitation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;
    public function guestInvitation()
    {
        return $this->belongsTo(GuestInvitation::class);
    }
}
