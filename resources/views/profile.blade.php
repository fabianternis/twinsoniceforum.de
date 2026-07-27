<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">

    <!-- User Header Card -->
    <div class="glass-card" style="padding: 2.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1.75rem;">
            <img src="{{ $profileUser->avatar_url }}" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-cyan);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                    <h1 style="font-size: 2.2rem; color: #fff; font-weight: 800;">{{ $profileUser->username }}</h1>
                    <span class="badge badge-cyan">{{ $profileUser->rank_badge }}</span>
                    @if ($profileUser->role === 'admin' || $profileUser->hasRole('admin'))
                        <span class="badge badge-amber">Admin</span>
                    @endif
                </div>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">
                    {{ !empty($profileUser->bio) ? $profileUser->bio : 'Keine Bio angegeben.' }}
                </p>

                <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: var(--text-dim);">
                    <span>Mitglied seit {{ $profileUser->created_at->format('M Y') }}</span>
                    <span>{{ $postCount }} Antworten verfasst</span>
                    <span>{{ count($userTopics) }} Themen gestartet</span>
                </div>
            </div>
        </div>

        @auth
            @if (Auth::id() === $profileUser->id)
                <div>
                    <button onclick="document.getElementById('editProfileModal').style.display='flex'" class="btn btn-secondary">
                        {!! Icon::render('edit') !!} Profil bearbeiten
                    </button>
                </div>
            @endif
        @endauth
    </div>

    <!-- User Topics List -->
    <h2 style="font-size: 1.4rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        {!! Icon::render('pin') !!} Erstellte Themen von {{ $profileUser->username }}
    </h2>

    <div class="glass-card" style="overflow: hidden;">
        @if ($userTopics->isEmpty())
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                Dieser Benutzer hat bisher noch keine Themen verfasst.
            </div>
        @else
            @foreach ($userTopics as $topic)
                <div class="topic-item">
                    <div class="topic-left">
                        <div class="topic-details">
                            <a href="/topic/{{ $topic->id }}">
                                <h4>{{ $topic->title }}</h4>
                            </a>
                            <div class="topic-meta-tags">
                                <span class="badge badge-{{ $topic->category->badge_color }}">{!! Icon::render($topic->category->icon) !!} {{ $topic->category->name }}</span>
                                <span>• Erstellt am {{ $topic->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div style="text-align: center;">
                            <div style="font-weight: 700; color: #fff;">{{ $topic->replies_count }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-dim);">Antworten</div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>

<!-- Edit Profile Modal -->
@auth
    @if (Auth::id() === $profileUser->id)
        <div class="modal-overlay" id="editProfileModal">
            <div class="modal-box" style="max-width: 500px;">
                <div class="modal-header">
                    <h3 style="color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                        {!! Icon::render('edit') !!} Profil bearbeiten
                    </h3>
                    <button onclick="document.getElementById('editProfileModal').style.display='none'" style="background: none; border: none; color: #fff; cursor: pointer;">
                        {!! Icon::render('close') !!}
                    </button>
                </div>
                <div style="padding: 1.5rem;">
                    <form action="/user/update" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Avatar Bild-URL</label>
                            <input type="url" name="avatar_url" class="input-field" value="{{ $profileUser->avatar_url }}" required>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Eis-Rang Badge Text</label>
                            <input type="text" name="rank_badge" class="input-field" value="{{ $profileUser->rank_badge }}">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Biografie / Status</label>
                            <textarea name="bio" class="input-field" rows="3">{{ $profileUser->bio }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Aenderungen speichern</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection
