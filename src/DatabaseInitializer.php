<?php

namespace App;

use PDO;

class DatabaseInitializer
{
    public static function run(): void
    {
        $db = Database::getConnection();

        // Check if users table exists
        $tableExists = false;
        try {
            $result = $db->query("SHOW TABLES LIKE 'users'");
            $tableExists = $result && $result->rowCount() > 0;
        } catch (\Exception $e) {
            $tableExists = false;
        }

        if ($tableExists) {
            return;
        }

        // Create Users table
        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                avatar_url VARCHAR(255) DEFAULT '/assets/images/default-avatar.png',
                bio TEXT,
                rank_badge VARCHAR(50) DEFAULT 'Ice Cadet ⛸️',
                role VARCHAR(20) DEFAULT 'member',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Categories table
        $db->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(100) NOT NULL UNIQUE,
                description TEXT,
                icon VARCHAR(50) DEFAULT '⛸️',
                badge_color VARCHAR(30) DEFAULT 'cyan',
                display_order INT DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Topics table
        $db->exec("
            CREATE TABLE IF NOT EXISTS topics (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_id INT NOT NULL,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL,
                content LONGTEXT NOT NULL,
                is_pinned TINYINT(1) DEFAULT 0,
                is_locked TINYINT(1) DEFAULT 0,
                views INT DEFAULT 0,
                replies_count INT DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Posts (Replies) table
        $db->exec("
            CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                topic_id INT NOT NULL,
                user_id INT NOT NULL,
                parent_id INT DEFAULT NULL,
                content LONGTEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Reactions table
        $db->exec("
            CREATE TABLE IF NOT EXISTS reactions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                item_type ENUM('topic', 'post') NOT NULL DEFAULT 'topic',
                item_id INT NOT NULL,
                user_id INT NOT NULL,
                reaction_type VARCHAR(30) NOT NULL DEFAULT 'heart',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY user_item_reaction (item_type, item_id, user_id, reaction_type),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Polls table
        $db->exec("
            CREATE TABLE IF NOT EXISTS polls (
                id INT AUTO_INCREMENT PRIMARY KEY,
                topic_id INT NOT NULL UNIQUE,
                question VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Poll Options table
        $db->exec("
            CREATE TABLE IF NOT EXISTS poll_options (
                id INT AUTO_INCREMENT PRIMARY KEY,
                poll_id INT NOT NULL,
                option_text VARCHAR(255) NOT NULL,
                votes INT DEFAULT 0,
                FOREIGN KEY (poll_id) REFERENCES polls(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Poll Votes table
        $db->exec("
            CREATE TABLE IF NOT EXISTS poll_votes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                poll_id INT NOT NULL,
                option_id INT NOT NULL,
                user_id INT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_user_vote (poll_id, user_id),
                FOREIGN KEY (poll_id) REFERENCES polls(id) ON DELETE CASCADE,
                FOREIGN KEY (option_id) REFERENCES poll_options(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create Shoutbox table
        $db->exec("
            CREATE TABLE IF NOT EXISTS shouts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                message TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Seed Users
        $passwordHash = password_hash("TwinsOnIce2026!", PASSWORD_DEFAULT);
        
        $stmtUser = $db->prepare("INSERT INTO users (username, email, password_hash, avatar_url, bio, rank_badge, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // Admin
        $stmtUser->execute([
            'admin',
            'admin@twinsoniceforum.de',
            $passwordHash,
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=250&q=80',
            'Official Community Administrator for Twins on Ice Forum. Ice Skating & Music Enthusiast!',
            'Ice Queen 👑 VIP',
            'admin'
        ]);
        $adminId = $db->lastInsertId();

        // EmiliaFan
        $stmtUser->execute([
            'EmiliaFan',
            'emilia.fan@example.com',
            $passwordHash,
            'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=250&q=80',
            'Skating daily since 2021. Big fan of Emilia & Letizia!',
            'Gold Skater ⛸️',
            'member'
        ]);
        $user1Id = $db->lastInsertId();

        // LetiziaSquad
        $stmtUser->execute([
            'LetiziaSquad',
            'letizia.squad@example.com',
            $passwordHash,
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=250&q=80',
            'CHECK DAS is on repeat 24/7 🔥. Passionate about figure skating choreography.',
            'Music Fanatic 🎵',
            'member'
        ]);
        $user2Id = $db->lastInsertId();

        // IceSkater_Mia
        $stmtUser->execute([
            'IceSkater_Mia',
            'mia.skates@example.com',
            $passwordHash,
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=250&q=80',
            'Learning double axels! Inspired by Twins on Ice vlogs.',
            'Spin Specialist 🌀',
            'member'
        ]);
        $user3Id = $db->lastInsertId();

        // Seed Categories
        $categories = [
            [
                'name' => 'Eiskunstlauf & Training',
                'slug' => 'eiskunstlauf-training',
                'description' => 'Diskusssionen über Kür-Choreografien, Sprünge, Pirouetten & Schlittschuh-Equipment der Twins on Ice.',
                'icon' => '⛸️',
                'badge_color' => 'cyan',
                'display_order' => 1
            ],
            [
                'name' => 'Musik & "CHECK DAS"',
                'slug' => 'musik-check-das',
                'description' => 'Alles rund um die Single "CHECK DAS", Musikvideos, Beats, Lyrics & neue Releases.',
                'icon' => '🎵',
                'badge_color' => 'magenta',
                'display_order' => 2
            ],
            [
                'name' => 'Vlogs & Social Media',
                'slug' => 'vlogs-social-media',
                'description' => 'Behind the scenes vlogs, TikTok Trends, Instagram Outfits & YouTube Highlights.',
                'icon' => '📺',
                'badge_color' => 'purple',
                'display_order' => 3
            ],
            [
                'name' => 'Fashion & Lifestyle',
                'slug' => 'fashion-lifestyle',
                'description' => 'Eislauf-Outfits, Style-Guides, Hauls & Makeup-Inspirationen von Emilia & Letizia.',
                'icon' => '👗',
                'badge_color' => 'pink',
                'display_order' => 4
            ],
            [
                'name' => 'Meet & Greets & Events',
                'slug' => 'events-meet-greets',
                'description' => 'Termine für Shows, Meisterschaften, Autogrammstunden & Fan-Treffen.',
                'icon' => '🎟️',
                'badge_color' => 'amber',
                'display_order' => 5
            ],
            [
                'name' => 'Fan Lounge & Off-Topic',
                'slug' => 'fan-lounge-off-topic',
                'description' => 'Stelle dich der Community vor, teile Fan-Art oder quatsche im Off-Topic Bereich.',
                'icon' => '💬',
                'badge_color' => 'blue',
                'display_order' => 6
            ],
        ];

        $stmtCat = $db->prepare("INSERT INTO categories (name, slug, description, icon, badge_color, display_order) VALUES (?, ?, ?, ?, ?, ?)");
        $catIds = [];
        foreach ($categories as $cat) {
            $stmtCat->execute([$cat['name'], $cat['slug'], $cat['description'], $cat['icon'], $cat['badge_color'], $cat['display_order']]);
            $catIds[$cat['slug']] = $db->lastInsertId();
        }

        // Seed Topics
        $stmtTopic = $db->prepare("INSERT INTO topics (category_id, user_id, title, slug, content, is_pinned, views, replies_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Pinned Topic 1
        $stmtTopic->execute([
            $catIds['musik-check-das'],
            $adminId,
            '🔥 Offizieller Diskussions-Thread: Single "CHECK DAS" & Musikvideo!',
            'offizieller-diskussions-thread-check-das',
            "Willkommen im offiziellen Community-Thread zum Release von **\"CHECK DAS\"** von Twins on Ice (Emilia & Letizia Macula)!\n\nWie gefällt euch der Track und die Choreo im Video? Welche Szene auf dem Eis hat euch am meisten umgehauen?\n\nLasst uns hier eure Gedanken, Lieblings-Lines und Feedback austauschen! 🎶❄️",
            1,
            342,
            3
        ]);
        $topic1Id = $db->lastInsertId();

        // Pinned Topic 2
        $stmtTopic->execute([
            $catIds['eiskunstlauf-training'],
            $adminId,
            '⛸️ Eiskunstlauf Guide: Die perfekten Schlittschuhe & Pflege-Tipps',
            'eiskunstlauf-guide-schlittschuhe-pflege',
            "Hallo liebe Twins on Ice Fans!\n\nDa viele in der Community selbst mit dem Eiskunstlaufen angefangen haben oder es ausprobieren möchten, sammeln wir hier die besten Tipps rund um Equipment, Schliff und Pflege der Kufen.\n\nWorauf achtet ihr beim Kauf eurer Schlittschuhe?",
            1,
            218,
            2
        ]);
        $topic2Id = $db->lastInsertId();

        // Topic 3
        $stmtTopic->execute([
            $catIds['vlogs-social-media'],
            $user1Id,
            'Lieblings-Vlog 2026: Welches Video hat euch am besten gefallen?',
            'lieblings-vlog-2026',
            "Hey Leute! Der neue YouTube Vlog von den Mädels war einfach nur genial! Besonders der Behind-The-Scenes Einblick in die Meisterschaftsvorbereitung.\n\nWas war euer bisheriger Lieblingsvlog von Emilia & Letizia?",
            0,
            129,
            1
        ]);
        $topic3Id = $db->lastInsertId();

        // Seed Posts (Replies)
        $stmtPost = $db->prepare("INSERT INTO posts (topic_id, user_id, content) VALUES (?, ?, ?)");
        
        $stmtPost->execute([
            $topic1Id,
            $user2Id,
            "Der Beat bei 0:45 Drop ist absolut brutal! 💥 Und die Synchro-Pirouette auf dem Eis passt einfach perfekt zum Rhythmus. Hab mir den Song direkt in die Playlist gepackt!"
        ]);

        $stmtPost->execute([
            $topic1Id,
            $user3Id,
            "Ich liebe das Outfit mit den blauen Ice-Glitter Elementen! Hoffentlich kommt dazu bald ein Fashion-Breakdown Vlog. 💙"
        ]);

        $stmtPost->execute([
            $topic1Id,
            $adminId,
            "Danke für das Feedback Leute! Vergesst nicht, oben in der Umfrage abzustimmen!"
        ]);

        $stmtPost->execute([
            $topic2Id,
            $user3Id,
            "Toller Thread! Ich benutze aktuell Edea Schlittschuhe mit Wilson Kufen und kann sie jedem Anfänger bis Fortgeschrittenen nur empfehlen."
        ]);

        // Seed Poll
        $stmtPoll = $db->prepare("INSERT INTO polls (topic_id, question) VALUES (?, ?)");
        $stmtPoll->execute([$topic1Id, "Was ist euer Highlight beim Release von 'CHECK DAS'?"]);
        $pollId = $db->lastInsertId();

        $stmtOpt = $db->prepare("INSERT INTO poll_options (poll_id, option_text, votes) VALUES (?, ?, ?)");
        $stmtOpt->execute([$pollId, "Die Synchro-Choreografie auf dem Eis ⛸️", 45]);
        $opt1Id = $db->lastInsertId();
        $stmtOpt->execute([$pollId, "Der Beat & Songwriting 🎶", 32]);
        $opt2Id = $db->lastInsertId();
        $stmtOpt->execute([$pollId, "Die High-Fashion Schlittschuh-Outfits 👗", 19]);
        $opt3Id = $db->lastInsertId();
        $stmtOpt->execute([$pollId, "Das Behind-the-Scenes Feeling 📹", 14]);

        // Seed Shouts
        $stmtShout = $db->prepare("INSERT INTO shouts (user_id, message) VALUES (?, ?)");
        $stmtShout->execute([$adminId, "Willkommen im neuen Twins on Ice Forum! ❄️✨ Viel Spaß beim Austauschen!"]);
        $stmtShout->execute([$user1Id, "Heyy an alle Eiskunstlauf Fans! Wer schaut auch täglich die Vlogs?"]);
        $stmtShout->execute([$user2Id, "CHECK DAS läuft im Loop! 🎧"]);
        $stmtShout->execute([$user3Id, "Morgen wieder Eistraining, bin mega motiviert 💪"]);

        // Seed Reactions
        $stmtReact = $db->prepare("INSERT INTO reactions (item_type, item_id, user_id, reaction_type) VALUES (?, ?, ?, ?)");
        $stmtReact->execute(['topic', $topic1Id, $user1Id, 'heart']);
        $stmtReact->execute(['topic', $topic1Id, $user2Id, 'fire']);
        $stmtReact->execute(['topic', $topic1Id, $user3Id, 'skate']);
    }
}
