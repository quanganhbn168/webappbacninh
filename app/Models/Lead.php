<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    /** Ad parameters kept from the address the visitor arrived on. */
    public const CAMPAIGN_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gbraid', 'wbraid', 'fbclid', 'ttclid'];

    protected $guarded = [];

    protected $casts = ['meta' => 'array'];

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    /** Short wording of where an advertised visitor came from, for example "google · khuyen-mai". */
    public function campaignLabel(): ?string
    {
        $campaign = $this->meta['campaign'] ?? [];
        $source = $campaign['utm_source'] ?? match (true) {
            isset($campaign['gclid']) || isset($campaign['gbraid']) || isset($campaign['wbraid']) => 'Google Ads',
            isset($campaign['fbclid']) => 'Facebook',
            isset($campaign['ttclid']) => 'TikTok',
            default => null,
        };

        return implode(' · ', array_filter([$source, $campaign['utm_campaign'] ?? null])) ?: null;
    }
}
