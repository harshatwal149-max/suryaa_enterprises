<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_SPAM = 'spam';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
    ];

    /**
     * @param  Builder<Enquiry>  $query
     * @return Builder<Enquiry>
     */
    public function scopeSpam(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SPAM);
    }

    /**
     * @param  Builder<Enquiry>  $query
     * @return Builder<Enquiry>
     */
    public function scopeNotSpam(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_SPAM);
    }

    /**
     * Customer's phone as digits with country code, ready for wa.me links.
     */
    public function whatsappDigits(): string
    {
        return SiteSetting::toWhatsappDigits($this->phone);
    }
}
