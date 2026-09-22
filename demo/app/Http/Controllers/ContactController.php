<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $subjects = [
            'General Inquiry',
            'Order Support',
            'Returns & Refunds',
            'Shipping Question',
            'Product Feedback',
            'Partnership',
        ];

        $contactChannels = [
            [
                'icon'  => 'fa-solid fa-location-dot',
                'label' => 'Our Location',
                'lines' => ['123 Green Park, Near City Mall', 'Kolkata, West Bengal 700001'],
            ],
            [
                'icon'  => 'fa-solid fa-envelope',
                'label' => 'Email Us',
                'lines' => ['support@shopease.com'],
                'href'  => 'mailto:support@shopease.com',
            ],
            [
                'icon'  => 'fa-solid fa-phone',
                'label' => 'Call Us',
                'lines' => ['+91 98765 43210'],
                'href'  => 'tel:+919876543210',
            ],
            [
                'icon'  => 'fa-solid fa-clock',
                'label' => 'Working Hours',
                'lines' => ['Mon - Sat: 9:00 AM - 8:00 PM', 'Sunday: 10:00 AM - 4:00 PM'],
            ],
        ];

        $socials = [
            ['name' => 'Facebook',  'icon' => 'fa-brands fa-facebook-f', 'url' => '#'],
            ['name' => 'Instagram', 'icon' => 'fa-brands fa-instagram',  'url' => '#'],
            ['name' => 'YouTube',   'icon' => 'fa-brands fa-youtube',    'url' => '#'],
            ['name' => 'Twitter',   'icon' => 'fa-brands fa-twitter',    'url' => '#'],
            ['name' => 'Pinterest', 'icon' => 'fa-brands fa-pinterest',  'url' => '#'],
        ];

        $storeName = 'AgriExpress';
        $storeAddress = [
            'line1' => '123 Green Park, Near City Mall',
            'city'  => 'Kolkata, West Bengal 700001',
        ];

        // Keyless OpenStreetMap embed so the map renders without configuration.
        // Swap for a Google Maps embed URL if you have an API key.
        $mapEmbedUrl = 'https://www.openstreetmap.org/export/embed.html?bbox=88.32%2C22.53%2C88.40%2C22.59&layer=mapnik&marker=22.5626%2C88.3630';

        return view('contact', compact(
            'subjects',
            'contactChannels',
            'socials',
            'storeName',
            'storeAddress',
            'mapEmbedUrl'
        ));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // TODO: persist and/or dispatch a Mailable, e.g.:
        // Mail::to(config('mail.from.address'))->send(new ContactMessage($data));

        return back()->with('status', "Thanks for reaching out! We'll get back to you shortly.");
    }
}
