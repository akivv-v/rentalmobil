@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-chat-left-text me-2 text-primary"></i> Daftar Pesan Masuk
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($users as $u)
                                @php
                                    $lastMsg = $u->messages->first();
                                    $unreadCount = $u->messages
                                        ->where('is_read', false)
                                        ->where('sender', 'user')
                                        ->count();
                                @endphp
                                <a href="{{ route('admin.chat.show', $u->id) }}"
                                    class="list-group-item list-group-item-action p-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <i class="bi bi-person-circle fs-1 text-secondary"></i>

                                            @if ($u->unread_count > 0)
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm"
                                                    style="font-size: 0.65rem;">
                                                    {{ $u->unread_count }}
                                                    <span class="visually-hidden">pesan belum dibaca</span>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6
                                                    class="mb-0 {{ $u->unread_count > 0 ? 'fw-bold text-dark' : 'text-secondary' }}">
                                                    {{ $u->name }}
                                                </h6>
                                                <small
                                                    class="{{ $u->unread_count > 0 ? 'text-primary fw-bold' : 'text-muted' }}">
                                                    {{ $u->messages->first()->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <p class="mb-0 small {{ $u->unread_count > 0 ? 'text-dark fw-medium' : 'text-muted' }} text-truncate"
                                                style="max-width: 300px;">
                                                {{ $u->messages->first()->message }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-chat-dots fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">Belum ada pelanggan yang mengirim pesan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
