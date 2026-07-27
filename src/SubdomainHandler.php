<?php

namespace App;

class SubdomainHandler
{
    public static function getSubdomain(): ?string
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host = strtolower(explode(':', $host)[0]);

        // Ignore IP addresses
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return null;
        }

        // Split host parts
        $parts = explode('.', $host);
        if (count($parts) <= 2) {
            return null;
        }

        $subdomain = $parts[0];

        // Ignore www and main domain prefixes
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
                'title' => '🎵 Music & "CHECK DAS" Special Hub',
                'tagline' => 'Exklusive Musik-Station, Song-Lyrics, Beats & Musikvideos von Emilia & Letizia',
                'badge' => 'Music Special 🎧',
                'banner_color' => 'linear-gradient(135deg, rgba(236,72,153,0.3), rgba(168,85,247,0.3))'
            ],
            'skating' => [
                'name' => 'Figure Skating Hub',
                'category_slug' => 'eiskunstlauf-training',
                'title' => '⛸️ Eiskunstlauf & Training Academy Hub',
                'tagline' => 'Kür-Choreografien, Schlittschuh-Tipps, Pirouetten & Trainings-Geheimnisse',
                'badge' => 'Pro Skating ⛸️',
                'banner_color' => 'linear-gradient(135deg, rgba(56,189,248,0.3), rgba(14,165,233,0.3))'
            ],
            'vip' => [
                'name' => 'VIP Fan Lounge',
                'category_slug' => 'fan-lounge-off-topic',
                'title' => '👑 VIP Fan Lounge & Exclusive Club',
                'tagline' => 'Der exklusive Bereich für treue Twins on Ice Fans & Insider-Releases',
                'badge' => 'VIP Member Lounge 👑',
                'banner_color' => 'linear-gradient(135deg, rgba(251,191,36,0.3), rgba(245,158,11,0.3))'
            ],
            'vlog' => [
                'name' => 'Vlog & Video Portal',
                'category_slug' => 'vlogs-social-media',
                'title' => '📺 Vlog & Behind The Scenes Portal',
                'tagline' => 'YouTube Highlights, TikTok Trends & Lifestyle Stories',
                'badge' => 'Video Hub 📹',
                'banner_color' => 'linear-gradient(135deg, rgba(147,51,234,0.3), rgba(192,38,211,0.3))'
            ],
        ];

        return $configs[$sub] ?? [
            'name' => ucfirst($sub) . ' Hub',
            'category_slug' => null,
            'title' => '✨ ' . ucfirst($sub) . ' Community Hub',
            'tagline' => 'Dein spezialisierter Subdomain-Hub auf Twins on Ice Forum',
            'badge' => ucfirst($sub) . ' Subdomain',
            'banner_color' => 'linear-gradient(135deg, rgba(56,189,248,0.2), rgba(236,72,153,0.2))'
        ];
    }
}
