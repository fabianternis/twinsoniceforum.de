<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    
    <!-- Breadcrumb -->
    <div style="margin-bottom: 1.5rem; font-size: 0.9rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
        <a href="/">Forum</a>
        <span>&rsaquo;</span>
        <a href="/category/{{ $topic->category->slug }}">{{ $topic->category->name }}</a>
        <span>&rsaquo;</span>
        <span style="color: #fff;">{{ $topic->title }}</span>
    </div>

    <!-- Main Topic Box -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 2rem;">
        
        <!-- Header info -->
        <div style="border-bottom: 1px solid #1e293b; padding-bottom: 1.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <span class="badge badge-{{ $topic->category->badge_color }}">
                        {!! Icon::render($topic->category->icon) !!} {{ $topic->category->name }}
                    </span>
                    @if ($topic->is_pinned)
                        <span class="badge badge-pinned">{!! Icon::render('pin') !!} ANGEPINNT</span>
                    @endif
                    <span style="font-size: 0.85rem; color: var(--text-dim);">{{ $topic->created_at->format('d.m.Y H:i') }}</span>
                </div>

                <h1 style="font-size: 2rem; color: #fff; line-height: 1.3; font-weight: 800;">{{ $topic->title }}</h1>
            </div>

            @auth
                @if (Auth::id() === $topic->user_id || Auth::user()->role === 'admin')
                    <form action="/topic/{{ $topic->id }}/delete" method="POST" onsubmit="return confirm('Thema in den Papierkorb verschieben (SoftDelete)?');">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="font-size: 0.8rem; color: #ef4444; border-color: #7f1d1d;">
                            SoftDelete Thema
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        <!-- Author & Content Layout -->
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 2rem;">
            
            <!-- Author Card -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; border-right: 1px solid #1e293b; padding-right: 1.5rem;">
                <img src="{{ $topic->user->avatar_url }}" class="avatar-sm" style="width: 80px; height: 80px; margin-bottom: 0.75rem; border: 2px solid #334155;">
                <a href="/user/{{ urlencode($topic->user->username) }}" style="font-weight: 700; font-size: 1.1rem; color: #fff; margin-bottom: 0.25rem;">
                    {{ $topic->user->username }}
                </a>
                <span class="badge badge-cyan" style="margin-bottom: 0.75rem;">{{ $topic->user->rank_badge }}</span>
                
                @if (!empty($topic->user->bio))
                    <p style="font-size: 0.8rem; color: var(--text-muted); font-style: italic; line-height: 1.4; max-width: 180px;">
                        "{{ $topic->user->bio }}"
                    </p>
                @endif
            </div>

            <!-- Content Area -->
            <div>
                <div style="color: var(--text-main); font-size: 1.05rem; line-height: 1.8; white-space: pre-wrap; margin-bottom: 2rem;">
                    {!! nl2br(e($topic->content)) !!}
                </div>

                <!-- Poll Widget (if available) -->
                @if ($topic->poll)
                    <div style="background: #0f172a; border: 1px solid #334155; padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
                        <h3 style="font-size: 1.1rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            {!! Icon::render('poll') !!}
                            <span>Umfrage: {{ $topic->poll->question }}</span>
                        </h3>

                        <form action="/topic/{{ $topic->id }}/vote-poll" method="POST">
                            @csrf
                            <input type="hidden" name="poll_id" value="{{ $topic->poll->id }}">
                            
                            @php
                                $totalVotes = $topic->poll->options->sum('votes');
                            @endphp

                            <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 1rem;">
                                @foreach ($topic->poll->options as $opt)
                                    @php
                                        $pct = $totalVotes > 0 ? round(($opt->votes / $totalVotes) * 100) : 0;
                                    @endphp
                                    <label style="display: flex; flex-direction: column; gap: 0.3rem; cursor: pointer; background: #151e32; padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid #1e293b;">
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <div style="display: flex; align-items: center; gap: 0.6rem; color: #fff;">
                                                <input type="radio" name="option_id" value="{{ $opt->id }}" required>
                                                <span>{{ $opt->option_text }}</span>
                                            </div>
                                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary-cyan);">{{ $pct }}% ({{ $opt->votes }} Stimmen)</span>
                                        </div>
                                        
                                        <div style="height: 6px; width: 100%; background: #334155; border-radius: 99px; overflow: hidden; margin-top: 4px;">
                                            <div style="height: 100%; width: {{ $pct }}%; background: #0284c7; border-radius: 99px;"></div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1.2rem;">Abstimmen</button>
                            <span style="font-size: 0.8rem; color: var(--text-dim); margin-left: 1rem;">{{ $totalVotes }} Gesamtstimmen</span>
                        </form>
                    </div>
                @endif

                <!-- Reactions Bar -->
                <div style="display: flex; align-items: center; gap: 0.75rem; border-top: 1px solid #1e293b; padding-top: 1rem;">
                    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Reaktionen:</span>
                    
                    <button onclick="toggleReaction({{ $topic->id }}, 'heart')" class="btn btn-secondary" style="padding: 0.35rem 0.8rem; font-size: 0.85rem;">
                        {!! Icon::render('heart') !!} Gefaellt mir
                    </button>
                    <button onclick="toggleReaction({{ $topic->id }}, 'skate')" class="btn btn-secondary" style="padding: 0.35rem 0.8rem; font-size: 0.85rem;">
                        {!! Icon::render('skate') !!} Ice
                    </button>
                    <button onclick="toggleReaction({{ $topic->id }}, 'fire')" class="btn btn-secondary" style="padding: 0.35rem 0.8rem; font-size: 0.85rem;">
                        {!! Icon::render('fire') !!} Feuer
                    </button>
                </div>

            </div>
        </div>

    </div>

    <!-- Replies Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.4rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
            {!! Icon::render('chat') !!} Antworten ({{ count($topic->posts) }})
        </h2>
    </div>

    <!-- Posts / Replies List -->
    <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 3rem;">
        @foreach ($topic->posts as $post)
            <div class="glass-card" style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1.5rem;">
                    
                    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; border-right: 1px solid #1e293b; padding-right: 1rem;">
                        <img src="{{ $post->user->avatar_url }}" class="avatar-sm" style="width: 50px; height: 50px; margin-bottom: 0.5rem;">
                        <a href="/user/{{ urlencode($post->user->username) }}" style="font-weight: 700; color: #fff; font-size: 0.95rem;">
                            {{ $post->user->username }}
                        </a>
                        <span class="badge badge-cyan" style="font-size: 0.7rem; margin-top: 4px;">{{ $post->user->rank_badge }}</span>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; color: var(--text-dim); margin-bottom: 0.75rem;">
                            Geschrieben am {{ $post->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div style="color: var(--text-main); font-size: 0.95rem; line-height: 1.7; white-space: pre-wrap;">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <!-- Reply Form -->
    <div class="glass-card" style="padding: 2rem;">
        <h3 style="font-size: 1.2rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            {!! Icon::render('edit') !!} Antwort auf dieses Thema verfassen
        </h3>

        @auth
            <form action="/topic/{{ $topic->id }}/reply" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <textarea name="content" class="input-field" rows="4" placeholder="Schreibe deinen Beitrag... Sei respektvoll und halte dich an die Community-Regeln." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Antwort absenden</button>
            </form>
        @else
            <div style="text-align: center; padding: 2rem; background: #0f172a; border-radius: var(--radius-md);">
                <p style="color: var(--text-muted); margin-bottom: 1rem;">Du musst angemeldet sein, um auf dieses Thema zu antworten.</p>
                <a href="/login" class="btn btn-primary">Jetzt Anmelden</a>
                <a href="/register" class="btn btn-secondary" style="margin-left: 0.5rem;">Registrieren</a>
            </div>
        @endauth
    </div>

</div>
@endsection
