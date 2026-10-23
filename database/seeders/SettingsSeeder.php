<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero Section
            ['group' => 'hero', 'key' => 'hero_title', 'label' => 'Hero Title', 'type' => 'text', 'value' => 'Beauty That Speaks for Itself', 'sort_order' => 1],
            ['group' => 'hero', 'key' => 'hero_subtitle', 'label' => 'Hero Subtitle', 'type' => 'textarea', 'value' => 'Discover premium skincare, makeup, and wellness products curated for your everyday glow.', 'sort_order' => 2],
            ['group' => 'hero', 'key' => 'hero_cta_text', 'label' => 'Hero Button Text', 'type' => 'text', 'value' => 'Shop Now', 'sort_order' => 3],
            ['group' => 'hero', 'key' => 'hero_cta_link', 'label' => 'Hero Button Link', 'type' => 'text', 'value' => '/category/skincare', 'sort_order' => 4],

            // About / Footer
            ['group' => 'about', 'key' => 'footer_about', 'label' => 'Footer About Text', 'type' => 'textarea', 'value' => 'Quality cosmetics and beauty products delivered to your door.', 'sort_order' => 1],
            ['group' => 'about', 'key' => 'copyright_text', 'label' => 'Copyright Text', 'type' => 'text', 'value' => 'KRIEM Beauty. All rights reserved.', 'sort_order' => 2],

            // Contact Info
            ['group' => 'contact', 'key' => 'contact_heading', 'label' => 'Contact Page Heading', 'type' => 'text', 'value' => 'Contact Us', 'sort_order' => 1],
            ['group' => 'contact', 'key' => 'contact_subheading', 'label' => 'Contact Page Subheading', 'type' => 'textarea', 'value' => "Have a question or feedback? We'd love to hear from you.", 'sort_order' => 2],
            ['group' => 'contact', 'key' => 'contact_email', 'label' => 'Contact Email', 'type' => 'text', 'value' => 'hello@kriembeauty.com', 'sort_order' => 3],
            ['group' => 'contact', 'key' => 'contact_phone', 'label' => 'Contact Phone', 'type' => 'text', 'value' => '', 'sort_order' => 4],
            ['group' => 'contact', 'key' => 'contact_address', 'label' => 'Business Address', 'type' => 'textarea', 'value' => '', 'sort_order' => 5],

            // Announcement
            ['group' => 'general', 'key' => 'announcement_text', 'label' => 'Announcement Banner', 'type' => 'textarea', 'value' => '', 'sort_order' => 1],
            ['group' => 'general', 'key' => 'announcement_active', 'label' => 'Show Announcement Banner', 'type' => 'toggle', 'value' => '0', 'sort_order' => 2],

            // Shipping
            ['group' => 'shipping', 'key' => 'shipping_info_text', 'label' => 'Shipping Info (product page)', 'type' => 'text', 'value' => 'Free shipping on orders over $50 · Flat $5.99 otherwise', 'sort_order' => 1],

            // WhatsApp
            ['group' => 'whatsapp', 'key' => 'whatsapp_number', 'label' => 'WhatsApp Number', 'type' => 'text', 'value' => '', 'sort_order' => 1],
            ['group' => 'whatsapp', 'key' => 'whatsapp_message', 'label' => 'Default Message', 'type' => 'text', 'value' => 'Hi KRIEM Beauty! I have a question.', 'sort_order' => 2],

            // Social Links
            ['group' => 'social', 'key' => 'social_instagram', 'label' => 'Instagram URL', 'type' => 'text', 'value' => '', 'sort_order' => 1],
            ['group' => 'social', 'key' => 'social_facebook', 'label' => 'Facebook URL', 'type' => 'text', 'value' => '', 'sort_order' => 2],
            ['group' => 'social', 'key' => 'social_tiktok', 'label' => 'TikTok URL', 'type' => 'text', 'value' => '', 'sort_order' => 3],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
