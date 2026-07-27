<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; color: #fff; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                {!! Icon::render('shield') !!} Papierkorb & SoftDeletes Manager
            </h1>
            <p style="color: var(--text-muted);">Verwalte als SoftDelete geloeschte Themen und stelle sie bei Bedarf wieder her.</p>
        </div>
        <a href="/admin" class="btn btn-secondary">&larr; Zurueck zum Admin Panel</a>
    </div>

    @if (session('status'))
        <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid #166534; color: #4ade80; padding: 0.85rem 1.2rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.9rem;">
            {{ session('status') }}
        </div>
    @endif

    <div class="glass-card" style="padding: 1.5rem;">
        @if ($trashedTopics->isEmpty())
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                Der Papierkorb ist aktuell leer. Es gibt keine geloeschten Themen.
            </div>
        @else
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="border-bottom: 1px solid #334155; color: var(--text-muted);">
                        <th style="padding: 0.75rem;">Thema Titel</th>
                        <th style="padding: 0.75rem;">Kategorie</th>
                        <th style="padding: 0.75rem;">Autor</th>
                        <th style="padding: 0.75rem;">Geloescht am</th>
                        <th style="padding: 0.75rem; text-align: right;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trashedTopics as $topic)
                        <tr style="border-bottom: 1px solid #1e293b;">
                            <td style="padding: 0.75rem; color: #fff; font-weight: 600;">
                                {{ $topic->title }}
                            </td>
                            <td style="padding: 0.75rem;">
                                <span class="badge badge-cyan">{{ $topic->category->name }}</span>
                            </td>
                            <td style="padding: 0.75rem; color: var(--text-muted);">
                                {{ $topic->user->username }}
                            </td>
                            <td style="padding: 0.75rem; color: var(--text-dim); font-size: 0.8rem;">
                                {{ $topic->deleted_at->format('d.m.Y H:i') }}
                            </td>
                            <td style="padding: 0.75rem; text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <form action="/admin/topic/{{ $topic->id }}/restore" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                        Wiederherstellen
                                    </button>
                                </form>
                                <form action="/admin/topic/{{ $topic->id }}/force-delete" method="POST" onsubmit="return confirm('Endgueltig loeschen?');">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; color: #ef4444; border-color: #7f1d1d;">
                                        Endgueltig loeschen
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 1.5rem;">
                {{ $trashedTopics->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
