@php

    $sources = [
        'facebook',
        'instagram',
        'twitter',
        'x',
        'linkedin',
        'youtube',
        'tiktok',
        'snapchat',
        'pinterest',
        'reddit',
        'threads',
        'whatsapp',
        'telegram',
        'wechat',
        'line',

        'google_search',
        'google_ads',
        'google_display_network',
        'google_maps',
        'bing_search',
        'yahoo_search',
        'duckduckgo',

        'website_direct',
        'referral_website',
        'blog',
        'guest_post',
        'medium',
        'quora',
        'stack_overflow',
        'github',

        'email_marketing',
        'newsletter',
        'cold_email',

        'sms_marketing',
        'call_center',
        'direct_call',

        'affiliate',
        'influencer',
        'partner',
        'reseller',

        'event',
        'webinar',
        'conference',
        'workshop',

        'print_media',
        'newspaper',
        'magazine',

        'radio',
        'tv',

        'flyer',
        'brochure',
        'billboard',

        'friend_referral',
        'employee_referral',
        'word_of_mouth',

        'marketplace',
        'upwork',
        'fiverr',
        "Friend",
        'app_store',
        'play_store',
        'other',
    ];
@endphp
<div class="col-md-4">
    <div class="form-group">
        <label>Source <span class="text-danger"></span></label>
        <select name="source" class="form-control select">
            <x-select-options :items="$sources" :selected="$is_update ? $inquiry?->source : ''" />
        </select>
    </div>
</div>
