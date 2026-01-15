@extends('user.layouts.app')

@section('content')
<style>
    /* CSS untuk Rating Bintang yang dapat diklik */
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-input input {
        display: none;
    }

    .rating-input label {
        cursor: pointer;
        padding: 0 5px;
    }

    .rating-input label i {
        font-size: 2rem;
        color: #ddd;
        transition: 0.2s;
    }

    /* Efek Hover dan Checked */
    .rating-input input:checked ~ label i,
    .rating-input label:hover i,
    .rating-input label:hover ~ label i {
        color: #ffc107;
    }

    /* Mengubah icon jadi fill saat dipilih/hover */
    .rating-input input:checked ~ label i::before,
    .rating-input label:hover i::before,
    .rating-input label:hover ~ label i::before {
        content: "\f586"; /* Bootstrap Icon bi-star-fill */
    }
</style>

<div class="container my-5">
    <div class="row">
        {{-- KIRI: FORM ULASAN UMUM --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Berikan Testimoni</h5>
                    <form action="{{ route('user.ulasan.store') }}" method="POST">
                        @csrf
                        
                        {{-- Bagian Pilih Mobil Dihapus --}}

                        <div class="mb-3">
                            <label class="form-label d-block">Rating Layanan</label>
                            <div class="rating-input">
                                <input type="radio" name="bintang" id="star5" value="5" required>
                                <label for="star5"><i class="bi bi-star"></i></label>
                                
                                <input type="radio" name="bintang" id="star4" value="4">
                                <label for="star4"><i class="bi bi-star"></i></label>
                                
                                <input type="radio" name="bintang" id="star3" value="3">
                                <label for="star3"><i class="bi bi-star"></i></label>
                                
                                <input type="radio" name="bintang" id="star2" value="2">
                                <label for="star2"><i class="bi bi-star"></i></label>
                                
                                <input type="radio" name="bintang" id="star1" value="1">
                                <label for="star1"><i class="bi bi-star"></i></label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Komentar / Pengalaman</label>
                            <textarea name="komentar" class="form-control" rows="4" placeholder="Apa pendapat Anda tentang layanan kami?" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Testimoni</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KANAN: LIST ULASAN --}}
        <div class="col-md-8">
            <h4 class="fw-bold mb-4">Apa Kata Pelanggan</h4>
            @forelse($semua_ulasan as $u)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">{{ $u->user->name }}</h6>
                            <small class="text-muted">{{ $u->created_at->format('d M Y') }}</small>
                        </div>

                        <div class="text-warning my-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $u->bintang)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                            {{-- Nama mobil dihapus dari tampilan ulasan --}}
                        </div>

                        <p class="text-secondary mb-0">"{{ $u->komentar }}"</p>

                        @if ($u->balasan_admin)
                            <div class="bg-light p-3 rounded border-start border-primary border-4 mt-3">
                                <small class="fw-bold text-primary">
                                    <i class="bi bi-reply-fill"></i> Balasan Admin:
                                </small>
                                <p class="mb-0 small italic text-muted">{{ $u->balasan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-chat-left-dots text-muted fs-1"></i>
                    <p class="text-muted mt-2">Belum ada ulasan layanan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection