<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; color: #fff; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                {!! Icon::render('shield') !!} Spatie Activity & Audit Log Inspector
            </h1>
            <p style="color: var(--text-muted);">Uebersicht aller System- und Benutzer-Aktivitaeten (Audit Logging)</p>
        </div>
        <a href="/admin" class="btn btn-secondary">&larr; Zurueck zum Admin Panel</a>
    </div>

    <!-- Audit Log Table -->
    <div class="glass-card" style="padding: 1.5rem; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid #334155; color: var(--text-muted);">
                    <th style="padding: 0.75rem;">ID</th>
                    <th style="padding: 0.75rem;">Log Name</th>
                    <th style="padding: 0.75rem;">Aktion / Beschreibung</th>
                    <th style="padding: 0.75rem;">Verursacher</th>
                    <th style="padding: 0.75rem;">Ziel (Subject)</th>
                    <th style="padding: 0.75rem;">Zeitstempel</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $act)
                    <tr style="border-bottom: 1px solid #1e293b;">
                        <td style="padding: 0.75rem; color: var(--text-dim);">#{{ $act->id }}</td>
                        <td style="padding: 0.75rem;">
                            <span class="badge badge-cyan">{{ $act->log_name }}</span>
                        </td>
                        <td style="padding: 0.75rem; color: #fff; font-weight: 600;">
                            {{ $act->description }}
                        </td>
                        <td style="padding: 0.75rem;">
                            @if ($act->causer)
                                <span style="color: var(--primary-cyan);">{{ $act->causer->username }}</span>
                            @else
                                <span style="color: var(--text-dim);">System</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem; color: var(--text-muted);">
                            {{ class_basename($act->subject_type) }} #{{ $act->subject_id }}
                        </td>
                        <td style="padding: 0.75rem; color: var(--text-dim); font-size: 0.8rem;">
                            {{ $act->created_at->format('d.m.Y H:i:s') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem;">
            {{ $activities->links() }}
        </div>
    </div>

</div>
@endsection
