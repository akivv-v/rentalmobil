@extends('user.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Berikan Ulasan</h5>
                    <form action="{{ route('user.ulasan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih Mobil</label>
                            <select name="mobil_id" class="form-select" required>
                                @foreach($mobils_pernah_disewa as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_mobil }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="text-warning fs-4">
                                <input type="number" name="bintang" min="1" max="5" class="form-control" placeholder="1-5 Bintang" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ulasan Anda</label>
                            <textarea name="komentar" class="form-control" rows="3" placeholder="Ceritakan pengalaman Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Ulasan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h4 class="fw-bold mb-4">Ulasan Pengguna</h4>
            @forelse($semua_ulasan as $u)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">{{ $u->user->name }}</h6>
                            <small class="text-muted">{{ $u->created_at->format('d M Y') }}</small>
                        </div>
                        <div class="text-warning my-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill {{ $i <= $u->bintang ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="text-dark ms-2 small">({{ $u->mobil->nama_mobil }})</span>
                        </div>
                        <p class="text-secondary">{{ $u->komentar }}</p>

                        @if($u->balasan_admin)
                            <div class="bg-light p-3 rounded border-start border-primary border-4 mt-3">
                                <small class="fw-bold text-primary"><i class="bi bi-reply-fill"></i> Balasan Admin:</small>
                                <p class="mb-0 small italic">{{ $u->balasan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada ulasan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection