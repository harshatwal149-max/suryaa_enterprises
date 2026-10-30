<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
    ];

    /**
     * Customer's phone as digits with country code, ready for wa.me links.
     */
    public function whatsappDigits(): string
    {
        return SiteSetting::toWhatsappDigits($this->phone);
    }
}
