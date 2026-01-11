@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold" style="color:#1e3038;">Manajemen Transaksi Rental</h2>
        
        {{-- Tombol Tambah dihapus karena Admin hanya menerima data dari User --}}
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('admin.rental.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                placeholder="Cari nama penyewa..." value="{{ request('search') }}">

            <button class="btn me-1" style="background:#1e3038; color:white;">Search</button>

            @if (request('search'))
                <a href="{{ route('admin.rental.index') }}" class="btn"
                    style="background:#1e3038; color:white;">Reset</a>
            @endif
        </div>
    </form>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
    @endif

    {{-- TABLE --}}
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="text-white text-center" style="background: #1e3038;">
                <tr>
                    <th class="py-3">Penyewa</th>
                    <th class="py-3">Mobil</th>
                    <th class="py-3">Total Bayar</th>
                    <th class="py-3">Status Transaksi</th>
                    <th class="py-3">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody style="background: white;">
                @forelse ($rentals as $item)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-bold">{{ $item->penyewa->nama }}</span>
                        </td>
                        <td class="text-center">{{ $item->mobil->nama_mobil }}</td>
                        <td class="text-center fw-bold text-primary">
                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if ($item->status == 'booking')
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-clock-history"></i> Menunggu Konfirmasi
                                </span>
                            @elseif($item->status == 'disewa')
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="bi bi-car-front"></i> Sedang Berjalan
                                </span>
                            @elseif($item->status == 'selesai')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle"></i> Selesai
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->status == 'booking')
                                {{-- Route disesuaikan dengan web.php baru --}}
                                <form action="{{ route('admin.rental.konfirmasi', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success fw-bold px-3" 
                                        onclick="return confirm('Konfirmasi bahwa pembayaran sudah masuk?')">
                                        Konfirmasi Bayar
                                    </button>
                                </form>
                            @elseif($item->status == 'disewa')
                                {{-- Route disesuaikan dengan web.php baru --}}
                                <form action="{{ route('admin.rental.set_kembali', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-info text-white fw-bold px-3"
                                        onclick="return confirm('Mobil sudah kembali dalam keadaan baik?')">
                                        Selesaikan Rental
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small italic">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi rental.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $rentals->links() }}
    </div>
@endsection