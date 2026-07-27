<?php

namespace App;

class SubdomainHandler
{
    public static function getSubdomain(): ?string
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host = strtolower(explode(':', $host)[0]);

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return null;
        }

        $parts = explode('.', $host);
        if (count($parts) <= 2) {
            return null;
        }

        $subdomain = $parts[0];

        if (in_array($subdomain, ['www', 'twinsoniceforum', 'web01'])) {
            return null;
        }

        return $subdomain;
    }

    public static function getSubdomainConfig(): ?array
    {
        $sub = self::getSubdomain();
        if (!$sub) {
            return null;
        }

        $configs = [
            'music' => [
                'name' => 'Twins on Ice Music Hub',
                'category_slug' => 'musik-check-das',
                'title' => 'Music & "CHECK DAS" Hub',
                'tagline' => 'Exklusive Musik-Station, Song-Lyrics, Beats & Musikvideos von Emilia & Letizia',
                'badge' => 'Music Special',
                'banner_color' => '#151e32'
            ],
            'skating' => [
                'name' => 'Figure Skating Hub',
                'category_slug' => 'eiskunstlauf-training',
                'title' => 'Eiskunstlauf & Training Academy Hub',
                'tagline' => 'Kuer-Choreografien, Schlittschuh-Tipps, Pirouetten & Trainings-Geheimnisse',
                'badge' => 'Pro Skating',
                'banner_color' => '#151e32'
            ],
            'vip' => [
                'name' => 'VIP Fan Lounge',
                'category_slug' => 'fan-lounge-off-topic',
                'title' => 'VIP Fan Lounge & Exclusive Club',
                'tagline' => 'Der exklusive Bereich fuer treue Twins on Ice Fans & Insider-Releases',
                'badge' => 'VIP Member Lounge',
                'banner_color' => '#151e32'
            ],
            'vlog' => [
                'name' => 'Vlog & Video Portal',
                'category_slug' => 'vlogs-social-media',
                'title' => 'Vlog & Behind The Scenes Portal',
                'tagline' => 'YouTube Highlights, TikTok Trends & Lifestyle Stories',
                'badge' => 'Video Hub',
                'banner_color' => '#151e32'
            ],
        ];

        return $configs[$sub] ?? [
            'name' => ucfirst($sub) . ' Hub',
            'category_slug' => null,
            'title' => ucfirst($sub) . ' Community Hub',
            'tagline' => 'Dein spezialisierter Subdomain-Hub auf Twins on Ice Forum',
            'badge' => ucfirst($sub) . ' Subdomain',
            'banner_color' => '#151e32'
        ];
    }
}
