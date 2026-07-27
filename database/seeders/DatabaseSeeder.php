<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Post;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Shout;
use App\Models\Reaction;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Spatie Permissions
        $createTopics = Permission::firstOrCreate(['name' => 'create topics']);
        $deleteTopics = Permission::firstOrCreate(['name' => 'delete topics']);
        $manageCategories = Permission::firstOrCreate(['name' => 'manage categories']);
        $manageUsers = Permission::firstOrCreate(['name' => 'manage users']);
        $viewAuditLogs = Permission::firstOrCreate(['name' => 'view audit logs']);

        // Create Spatie Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $modRole = Role::firstOrCreate(['name' => 'moderator']);
        $modRole->givePermissionTo([$createTopics, $deleteTopics]);

        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $memberRole->givePermissionTo([$createTopics]);

        // Create Demo Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@twinsoniceforum.de'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('TwinsOnIce2026!'),
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=250&q=80',
                'bio' => 'Official Community Administrator for Twins on Ice Forum.',
                'rank_badge' => 'Ice Queen VIP',
                'role' => 'admin',
            ]
        );
        $adminUser->assignRole($adminRole);

        // Create Demo Fan Users
        $user1 = User::firstOrCreate(
            ['email' => 'emilia.fan@example.com'],
            [
                'name' => 'Emilia Fan',
                'username' => 'EmiliaFan',
                'password' => Hash::make('TwinsOnIce2026!'),
                'avatar_url' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=250&q=80',
                'bio' => 'Skating daily since 2021. Big fan of Emilia & Letizia!',
                'rank_badge' => 'Gold Skater',
                'role' => 'member',
            ]
        );
        $user1->assignRole($memberRole);

        $user2 = User::firstOrCreate(
            ['email' => 'letizia.squad@example.com'],
            [
                'name' => 'Letizia Squad',
                'username' => 'LetiziaSquad',
                'password' => Hash::make('TwinsOnIce2026!'),
                'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=250&q=80',
                'bio' => 'CHECK DAS is on repeat 24/7. Passionate about figure skating choreography.',
                'rank_badge' => 'Music Fanatic',
                'role' => 'member',
            ]
        );
        $user2->assignRole($memberRole);

        // Create Categories
        $categories = [
            [
                'name' => 'Eiskunstlauf & Training',
                'description' => 'Diskussionen ueber Kuer-Choreografien, Spruenge, Pirouetten & Schlittschuh-Equipment der Twins on Ice.',
                'icon' => 'skate',
                'badge_color' => 'cyan',
                'display_order' => 1,
            ],
            [
                'name' => 'Musik & "CHECK DAS"',
                'description' => 'Alles rund um die Single "CHECK DAS", Musikvideos, Beats, Lyrics & neue Releases.',
                'icon' => 'music',
                'badge_color' => 'cyan',
                'display_order' => 2,
            ],
            [
                'name' => 'Vlogs & Social Media',
                'description' => 'Behind the scenes vlogs, TikTok Trends, Instagram Outfits & YouTube Highlights.',
                'icon' => 'video',
                'badge_color' => 'blue',
                'display_order' => 3,
            ],
            [
                'name' => 'Fashion & Lifestyle',
                'description' => 'Eislauf-Outfits, Style-Guides, Hauls & Makeup-Inspirationen von Emilia & Letizia.',
                'icon' => 'fashion',
                'badge_color' => 'blue',
                'display_order' => 4,
            ],
            [
                'name' => 'Meet & Greets & Events',
                'description' => 'Termine fuer Shows, Meisterschaften, Autogrammstunden & Fan-Treffen.',
                'icon' => 'calendar',
                'badge_color' => 'amber',
                'display_order' => 5,
            ],
            [
                'name' => 'Fan Lounge & Off-Topic',
                'description' => 'Stelle dich der Community vor, teile Fan-Art oder quatsche im Off-Topic Bereich.',
                'icon' => 'chat',
                'badge_color' => 'blue',
                'display_order' => 6,
            ],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['name']] = Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Create Topics
        $topic1 = Topic::firstOrCreate(
            ['title' => 'Offizieller Diskussions-Thread: Single "CHECK DAS" & Musikvideo'],
            [
                'category_id' => $catModels['Musik & "CHECK DAS"']->id,
                'user_id' => $adminUser->id,
                'content' => "Willkommen im offiziellen Community-Thread zum Release von **\"CHECK DAS\"** von Twins on Ice (Emilia & Letizia Macula)!\n\nWie gefaellt euch der Track und die Choreo im Video? Welche Szene auf dem Eis hat euch am meisten umgehauen?\n\nLasst uns hier eure Gedanken, Lieblings-Lines und Feedback austauschen!",
                'is_pinned' => true,
                'views' => 342,
                'replies_count' => 3,
            ]
        );

        $topic2 = Topic::firstOrCreate(
            ['title' => 'Eiskunstlauf Guide: Die perfekten Schlittschuhe & Pflege-Tipps'],
            [
                'category_id' => $catModels['Eiskunstlauf & Training']->id,
                'user_id' => $adminUser->id,
                'content' => "Hallo liebe Twins on Ice Fans!\n\nDa viele in der Community selbst mit dem Eiskunstlaufen angefangen haben oder es ausprobieren moechten, sammeln wir hier die besten Tipps rund um Equipment, Schliff und Pflege der Kufen.\n\nWorauf achtet ihr beim Kauf eurer Schlittschuhe?",
                'is_pinned' => true,
                'views' => 218,
                'replies_count' => 2,
            ]
        );

        // Posts
        Post::firstOrCreate(
            ['topic_id' => $topic1->id, 'user_id' => $user2->id, 'content' => "Der Beat bei 0:45 Drop ist absolut stark! Und die Synchro-Pirouette auf dem Eis passt einfach perfekt zum Rhythmus. Hab mir den Song direkt in die Playlist gepackt!"]
        );

        Post::firstOrCreate(
            ['topic_id' => $topic1->id, 'user_id' => $user1->id, 'content' => "Ich liebe das Outfit mit den blauen Ice-Glitter Elementen! Hoffentlich kommt dazu bald ein Fashion-Breakdown Vlog."]
        );

        // Poll
        $poll = Poll::firstOrCreate(
            ['topic_id' => $topic1->id],
            ['question' => "Was ist euer Highlight beim Release von 'CHECK DAS'?"]
        );

        PollOption::firstOrCreate(['poll_id' => $poll->id, 'option_text' => "Die Synchro-Choreografie auf dem Eis"], ['votes' => 45]);
        PollOption::firstOrCreate(['poll_id' => $poll->id, 'option_text' => "Der Beat & Songwriting"], ['votes' => 32]);
        PollOption::firstOrCreate(['poll_id' => $poll->id, 'option_text' => "Die High-Fashion Schlittschuh-Outfits"], ['votes' => 19]);
        PollOption::firstOrCreate(['poll_id' => $poll->id, 'option_text' => "Das Behind-the-Scenes Feeling"], ['votes' => 14]);

        // Shouts
        Shout::firstOrCreate(['user_id' => $adminUser->id, 'message' => "Willkommen im neuen Twins on Ice Forum! Viel Spass beim Austauschen!"]);
        Shout::firstOrCreate(['user_id' => $user1->id, 'message' => "Hallo an alle Eiskunstlauf Fans! Wer schaut auch taeglich die Vlogs?"]);
        Shout::firstOrCreate(['user_id' => $user2->id, 'message' => "CHECK DAS laeuft im Loop!"]);

        // Reactions
        Reaction::firstOrCreate(['item_type' => 'topic', 'item_id' => $topic1->id, 'user_id' => $user1->id, 'reaction_type' => 'heart']);
        Reaction::firstOrCreate(['item_type' => 'topic', 'item_id' => $topic1->id, 'user_id' => $user2->id, 'reaction_type' => 'fire']);
    }
}
