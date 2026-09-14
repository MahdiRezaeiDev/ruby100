<?php

return [
    'phone' => env('RUBY_PHONE', '+61401724002'),
    'phone_display' => env('RUBY_PHONE_DISPLAY', '+61 401 724 002'),
    'notify_email' => env('RUBY_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS')),
    'address' => '79 Aldridge St, Endeavour Hills VIC 3802',
    'area' => 'Endeavour Hills & surrounds, Victoria, Australia',

    'services' => [
        [
            'number' => '01',
            'title' => 'Towing Services',
            'copy' => 'Emergency roadside towing and vehicle transport for cars, vans, and light trucks. Prompt dispatch, modern equipment, careful handling from pickup to drop-off.',
            'icon' => 'tow',
        ],
        [
            'number' => '02',
            'title' => 'Car Removal',
            'copy' => 'Clear old, damaged, or unregistered vehicles fast. We remove from driveway or roadside and offer competitive cash — simple, transparent, stress-free.',
            'icon' => 'pin',
        ],
        [
            'number' => '03',
            'title' => 'Scrap Metal Collection',
            'copy' => 'Eco-friendly scrap collection and recycling with fair payouts. We haul it away and process responsibly — better for your wallet and the planet.',
            'icon' => 'recycle',
        ],
    ],

    'reasons' => [
        ['title' => 'Fast & Reliable Service', 'copy' => 'Rapid dispatch when you\'re stranded — we show up ready to finish the job right.', 'icon' => 'bolt'],
        ['title' => 'Instant Cash for Cars & Scrap', 'copy' => 'Competitive on-the-spot payouts for unwanted vehicles and scrap metal.', 'icon' => 'cash'],
        ['title' => 'Fully Equipped Fleet', 'copy' => 'Modern gear for safe recovery — cars, vans, and light trucks covered.', 'icon' => 'truck'],
        ['title' => 'Eco-Friendly Recycling', 'copy' => 'Responsible recycling that clears your space and keeps scrap out of landfill.', 'icon' => 'globe'],
        ['title' => '24/7 Customer Support', 'copy' => 'Day or night, weekend or public holiday — we\'re on call when you need us.', 'icon' => 'clock'],
        ['title' => 'Experienced & Trusted Team', 'copy' => 'Years in the industry, transparent pricing, and a reputation for showing up.', 'icon' => 'shield'],
    ],
];
