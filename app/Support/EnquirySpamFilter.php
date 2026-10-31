<?php

namespace App\Support;

/**
 * Spots promotional messages that get past the form's honeypot, such as
 * agencies selling videos, SEO or web design to the business.
 */
class EnquirySpamFilter
{
    /**
     * Indian mobile or landline, with optional 0 / 91 / 0091 prefix on mobiles.
     * Landlines need a prefix because a bare 10-digit number starting 1-5 is
     * the usual shape of a foreign number.
     */
    private const INDIAN_PHONE_PATTERN = '/^(?:(?:0|91|0091)?[6-9]\d{9}|(?:0|91|0091)[1-5]\d{9})$/';

    /** Foreign prices and web links: customers asking about solar in India don't send these. */
    private const SUSPICIOUS_CONTENT_PATTERN = '/[$£€]\s?\d|\b(?:usd|gbp)\b|https?:\/\/|\bwww\./i';

    private const PROMOTIONAL_PHRASES = [
        'our prices start',
        'our videos',
        'explainer video',
        'promotional video',
        'marketing video',
        'seo',
        'backlink',
        'backlinks',
        'guest post',
        'first page of google',
        'google ranking',
        'search engine',
        'digital marketing',
        'social media marketing',
        'lead generation',
        'web design',
        'website design',
        'redesign your website',
        'web development',
        'app development',
        'virtual assistant',
        'business loan',
        'crypto',
        'casino',
        'unsubscribe',
    ];

    public static function isSpam(string $phone, string $message): bool
    {
        return ! self::hasIndianPhone($phone) || self::hasPromotionalContent($message);
    }

    private static function hasIndianPhone(string $phone): bool
    {
        return preg_match(self::INDIAN_PHONE_PATTERN, preg_replace('/\D/', '', $phone)) === 1;
    }

    private static function hasPromotionalContent(string $message): bool
    {
        if (preg_match(self::SUSPICIOUS_CONTENT_PATTERN, $message) === 1) {
            return true;
        }

        $phrases = implode('|', array_map(
            fn (string $phrase): string => preg_quote($phrase, '/'),
            self::PROMOTIONAL_PHRASES,
        ));

        return preg_match('/\b(?:'.$phrases.')\b/i', $message) === 1;
    }
}
