<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\Page;
use App\Models\Post;
use App\Models\Reason;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Administrators must be created explicitly with artisan make:filament-user.
        // Seeding content must never create or reset a privileged password.

        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'phone' => '+61401724002',
            'phone_display' => '+61 401 724 002',
            'whatsapp_number' => '61401724002',
            'whatsapp_message' => 'Hi Ruby100, I need help with towing / car removal.',
            'address' => '79 Aldridge St, Endeavour Hills VIC 3802',
            'area' => 'Endeavour Hills & surrounds, Victoria, Australia',
            'hero_kicker' => 'Towing & Car Removal Services',
            'hero_title' => 'RUBY100',
            'hero_subtitle' => '24/7 emergency towing and car removal across Melbourne’s southeast — fast response, honest pricing, zero stress.',
            'hero_image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=2000&q=80',
            'about_title' => 'Comprehensive Towing & Car Removal Solutions You Can Rely On',
            'about_body' => "At Ruby100, we’re dedicated to providing fast, reliable, and stress-free towing and car removal services 24/7. With years of experience in the industry, we understand how frustrating unexpected breakdowns or unwanted vehicles can be.\n\nThat’s why our team is committed to delivering prompt assistance with a focus on customer satisfaction, safety, and transparency. Whether you need emergency roadside towing, accidental recovery, or simply want to get rid of an old, damaged, or unregistered vehicle, we make the process easy and efficient.\n\nOur fully equipped fleet and professional operators ensure your vehicle is handled with care from start to finish. At Ruby100, we don’t just tow cars — we build trust by being there when you need us most.",
            'about_image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=1600&q=80',
            'seo_title' => 'Ruby100 - #1 Towing & Car Removal Service in Australia | 24/7 Emergency',
            'seo_description' => 'Get fast, reliable towing and car removal services across Australia. Ruby100 offers 24/7 emergency assistance, instant cash for scrap cars, and eco-friendly recycling. Call +61 401 724 002 now.',
            'notify_email' => 'quotes@ruby100.ltd',
            'google_reviews_embed' => null,
        ]);

        $services = [
            [
                'title' => 'Towing Services',
                'slug' => 'towing-services',
                'summary' => 'Emergency roadside towing and vehicle transport for cars, vans, and light trucks.',
                'body' => 'Prompt dispatch, modern equipment, and careful handling from pickup to drop-off — day or night.',
                'image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 1,
            ],
            [
                'title' => 'Car Removal',
                'slug' => 'car-removal',
                'summary' => 'Clear old, damaged, or unregistered vehicles fast with competitive cash offers.',
                'body' => 'We remove from driveway or roadside — simple, transparent, and stress-free.',
                'image' => 'https://images.unsplash.com/photo-1492143974600-56917be74add?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 2,
            ],
            [
                'title' => 'Scrap Metal Collection',
                'slug' => 'scrap-metal-collection',
                'summary' => 'Eco-friendly scrap collection and recycling with fair payouts.',
                'body' => 'We haul it away and process responsibly — better for your wallet and the environment.',
                'image' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(['slug' => $service['slug']], $service + ['is_active' => true]);
        }

        $reasons = [
            ['title' => 'Fast & Reliable Service', 'copy' => 'Rapid dispatch when you’re stranded — we show up ready to finish the job right.', 'icon' => 'bolt', 'sort_order' => 1],
            ['title' => 'Instant Cash for Cars & Scrap', 'copy' => 'Competitive on-the-spot payouts for unwanted vehicles and scrap metal.', 'icon' => 'cash', 'sort_order' => 2],
            ['title' => 'Fully Equipped Fleet', 'copy' => 'Modern gear for safe recovery — cars, vans, and light trucks covered.', 'icon' => 'truck', 'sort_order' => 3],
            ['title' => 'Eco-Friendly Recycling', 'copy' => 'Responsible recycling that clears your space and keeps scrap out of landfill.', 'icon' => 'globe', 'sort_order' => 4],
            ['title' => '24/7 Customer Support', 'copy' => 'Day or night, weekend or public holiday — we’re on call when you need us.', 'icon' => 'clock', 'sort_order' => 5],
            ['title' => 'Experienced & Trusted Team', 'copy' => 'Years in the industry, transparent pricing, and a reputation for showing up.', 'icon' => 'shield', 'sort_order' => 6],
        ];

        foreach ($reasons as $reason) {
            Reason::query()->updateOrCreate(['title' => $reason['title']], $reason + ['is_active' => true]);
        }

        $gallery = [
            ['image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Ready for roadside recovery', 'sort_order' => 1],
            ['image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Professional vehicle care', 'sort_order' => 2],
            ['image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Workshop & recovery support', 'sort_order' => 3],
            ['image' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1400&q=80', 'caption' => 'On the road across Victoria', 'sort_order' => 4],
        ];

        GalleryImage::query()->delete();
        foreach ($gallery as $item) {
            GalleryImage::query()->create($item + ['is_active' => true]);
        }

        $areas = [
            'Endeavour Hills', 'Dandenong', 'Cranbourne', 'Narre Warren', 'Berwick',
            'Hallam', 'Fountain Gate', 'Pakenham', 'Clyde North', 'Officer',
            'Rowville', 'Scoresby', 'Mulgrave', 'Springvale', 'Keysborough',
        ];

        foreach ($areas as $index => $name) {
            ServiceArea::query()->updateOrCreate(['name' => $name], [
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        Page::query()->updateOrCreate(['slug' => 'privacy'], [
            'title' => 'Privacy Policy',
            'body' => "Ruby100 (“we”, “us”) provides towing and car removal services in Australia.\n\nWhen you request a quote or contact us, we may collect your name, phone number, email address, service details, and message content.\n\nWe use your details solely to respond to enquiries, provide quotes, deliver services, and improve our operations. We do not sell your personal information.\n\nUnder the Australian Privacy Principles, you may request access to or correction of your personal information by calling +61 401 724 002.",
            'is_active' => true,
        ]);

        Post::query()->updateOrCreate(['slug' => 'what-to-do-when-your-car-breaks-down'], [
            'title' => 'What to Do When Your Car Breaks Down in Melbourne',
            'excerpt' => 'Stay safe, get help fast, and know what to expect from a 24/7 towing team.',
            'body' => "Pull over safely, switch on hazards, and call a trusted towing service.\n\nRuby100 covers Endeavour Hills and surrounding suburbs with round-the-clock response. We’ll confirm your location, ETA, and pricing before we hook up — so there are no surprises on the roadside.",
            'cover_image' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1400&q=80',
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $this->call(ServiceGuidesSeeder::class);
    }
}
