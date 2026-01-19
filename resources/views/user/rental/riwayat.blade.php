@extends('user.layouts.app')

@section('content')
    <div class="container my-5" style="min-height: 50vh;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="bi bi-clock-history"></i> Riwayat Rental Anda</h3>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Beranda</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Mobil</th>
                                <th class="py-3">Tgl Sewa</th>
                                <th class="py-3 text-center">Lama</th>
                                <th class="py-3 text-end">Total Harga</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                                @php
                                    // Logika Real-time pengecekan waktu
                                    $tglKembali = \Carbon\Carbon::parse($r->tgl_kembali);
                                    $hariIni = \Carbon\Carbon::today();
                                    $isWaktunyaKembali = $hariIni->greaterThanOrEqualTo($tglKembali);
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="fw-bold text-primary">{{ $r->mobil->nama_mobil }}</span>
                                        <br><small class="text-muted">{{ $r->mobil->plat_nomor }}</small>
                                    </td>
                                    <td class="py-3">
                                        {{ date('d M Y', strtotime($r->tgl_sewa)) }}
                                        <i class="bi bi-arrow-right"></i>
                                        {{ date('d M Y', strtotime($r->tgl_kembali)) }}
                                    </td>
                                    <td class="py-3 text-center">{{ $r->lama_sewa }} Hari</td>
                                    <td class="py-3 text-end fw-bold">
                                        Rp {{ number_format($r->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge rounded-pill px-3 py-2 
                    {{ $r->status == 'booking' ? 'bg-warning text-dark' : ($r->status == 'disewa' ? 'bg-primary' : ($r->status == 'selesai' ? 'bg-success' : 'bg-secondary')) }}">
                                            @if ($r->status == 'booking')
                                                Menunggu Konfirmasi
                                            @elseif($r->status == 'disewa')
                                                Sedang Berjalan
                                            @elseif($r->status == 'selesai')
                                                Selesai
                                            @else
                                                {{ ucfirst($r->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if ($r->status == 'disewa')
                                            @if ($isWaktunyaKembali)
                                                {{-- WARNING REAL-TIME --}}
                                                <div
                                                    class="alert alert-warning mb-0 py-1 px-2 small fw-bold shadow-sm border-warning">
                                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                                    Segera kembalikan mobil! <br>
                                                    <span class="text-dark" style="font-size: 10px;">Batas:
                                                        {{ $tglKembali->format('d M Y') }}</span>
                                                </div>
                                            @else
                                                <span class="text-primary small fw-bold">
                                                    <i class="bi bi-info-circle"></i> Mobil sedang Anda gunakan
                                                </span>
                                            @endif
                                        @elseif($r->status == 'booking')
                                            <span class="text-muted small italic">Menunggu Verifikasi Admin</span>
                                        @elseif($r->status == 'selesai')
                                            <span class="text-success fw-bold">
                                                <i class="bi bi-check-all"></i> Sudah Dikembalikan
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/amber/calendar.svg" alt="empty"
                                            style="width: 150px;" class="mb-3">
                                        <p class="text-muted">Belum ada data pemesanan.</p>
                                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm">Sewa Mobil
                                            Sekarang</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
