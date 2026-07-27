<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Category Header Card -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="background: #1e293b; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-md); border: 1px solid #334155; color: var(--primary-cyan);">
                {!! Icon::render($category->icon) !!}
            </div>
            <div>
                <span class="badge badge-{{ $category->badge_color }}" style="margin-bottom: 0.5rem; font-size: 0.85rem;">Kategorie</span>
                <h1 style="font-size: 2rem; color: #fff;">{{ $category->name }}</h1>
                <p style="color: var(--text-muted); margin-top: 0.25rem; font-size: 1rem;">{{ $category->description }}</p>
            </div>
        </div>

        <div>
            <a href="/topic/create?category={{ $category->id }}" class="btn btn-primary">{!! Icon::render('plus') !!} Thema in dieser Kategorie</a>
        </div>
    </div>

    <!-- Topics List -->
    <div class="glass-card" style="overflow: hidden;">
        @if ($topics->isEmpty())
            <div style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                <h3>Noch keine Themen in dieser Kategorie.</h3>
                <p style="margin-top: 0.5rem;">Starte jetzt die erste Diskussion!</p>
                <a href="/topic/create?category={{ $category->id }}" class="btn btn-primary" style="margin-top: 1.5rem;">Erstes Thema erstellen</a>
            </div>
        @else
            @foreach ($topics as $topic)
                <div class="topic-item">
                    <div class="topic-left">
                        <img src="{{ $topic->user->avatar_url }}" alt="Avatar" class="avatar-sm">
                        <div class="topic-details">
                            <a href="/topic/{{ $topic->id }}">
                                <h4>
                                    @if ($topic->is_pinned)
                                        <span class="badge badge-pinned">{!! Icon::render('pin') !!} ANGEPINNT</span>
                                    @endif
                                    {{ $topic->title }}
                                </h4>
                            </a>
                            <div class="topic-meta-tags">
                                <span>von <a href="/user/{{ urlencode($topic->user->username) }}" style="color: var(--text-main); font-weight: 600;">{{ $topic->user->username }}</a></span>
                                <span>• Erstellt am {{ $topic->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="topic-stats">
                        <div style="text-align: center;">
                            <div style="font-weight: 700; color: #fff;">{{ $topic->replies_count }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-dim);">Antworten</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-weight: 700; color: var(--primary-cyan);">{{ $topic->views }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-dim);">Aufrufe</div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
