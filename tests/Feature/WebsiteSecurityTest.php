<?php

namespace Tests\Feature;

use App\Mail\QuoteRequestMail;
use App\Models\Post;
use App\Models\SiteSetting;
use Database\Seeders\ServiceGuidesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WebsiteSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function enquiry(): array
    {
        return ['name' => 'Test Customer', 'phone' => '0401 724 002', 'email' => 'customer@example.com', 'service' => 'Towing Services'];
    }

    public function test_quote_is_saved_and_notification_is_queued(): void
    {
        Mail::fake();
        SiteSetting::current()->update(['notify_email' => 'quotes@example.com']);
        $this->post('/quote', $this->enquiry())->assertSessionHas('quote_success');
        $this->assertDatabaseCount('quote_requests', 1);
        Mail::assertQueued(QuoteRequestMail::class);
    }

    public function test_invalid_service_and_phone_are_rejected(): void
    {
        $this->post('/quote', array_replace($this->enquiry(), ['service' => 'Injected', 'phone' => 'abcdefghijk']))->assertSessionHasErrors(['service', 'phone']);
        $this->assertDatabaseCount('quote_requests', 0);
    }

    public function test_honeypot_does_not_save_or_send(): void
    {
        Mail::fake();
        $this->post('/quote', $this->enquiry() + ['website' => 'spam'])->assertRedirect();
        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    public function test_cash_service_cannot_be_overridden(): void
    {
        Mail::fake();
        $this->post('/cash-for-cars', $this->enquiry())->assertSessionHas('cash_success');
        $this->assertDatabaseHas('quote_requests', ['service' => 'Cash for Cars']);
    }

    public function test_headers_and_escaping(): void
    {
        SiteSetting::current()->update(['seo_description' => '</script><script>alert(1)</script>', 'google_reviews_embed' => '<script>alert(2)</script>']);
        $this->get('/')->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<script>alert(2)</script>', false);
    }

    public function test_embed_host_validation(): void
    {
        $settings = new SiteSetting;
        foreach (['javascript:alert(1)', 'https://www.google.com.evil.test/maps/embed', '<script>alert(1)</script>'] as $url) {
            $settings->google_reviews_embed = $url;
            $this->assertNull($settings->reviewsEmbedUrl());
        }
        $settings->google_reviews_embed = 'https://www.google.com/maps/embed?pb=test';
        $this->assertSame($settings->google_reviews_embed, $settings->reviewsEmbedUrl());
    }

    public function test_four_guides_are_idempotent_and_drafts_are_hidden(): void
    {
        $this->seed(ServiceGuidesSeeder::class);
        $this->seed(ServiceGuidesSeeder::class);
        $this->assertDatabaseCount('posts', 4);
        $this->get('/blog')->assertOk();
        foreach (Post::all() as $post) {
            $this->get('/blog/'.$post->slug)->assertOk()->assertSee($post->title);
        }
        $post = Post::first();
        $post->update(['is_published' => false]);
        $this->get('/blog/'.$post->slug)->assertNotFound();
    }

    public function test_rate_limiting(): void
    {
        for ($i = 0; $i < 8; $i++) {
            $this->post('/quote', $this->enquiry() + ['website' => 'bot'])->assertRedirect();
        }
        $this->post('/quote', $this->enquiry())->assertStatus(429);
    }
}
