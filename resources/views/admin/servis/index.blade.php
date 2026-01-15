@extends('admin.layouts.app')

@section('content')
    <style>
        .card-stats {
            border-radius: 15px;
            border: none;
            transition: 0.3s;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .bg-need-service {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .bg-safe {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .car-info {
            font-weight: 600;
            color: #1e293b;
        }

        .next-service {
            font-size: 0.85rem;
            color: #64748b;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Pemeliharaan Mobil</h2>
                <p class="text-muted small">Pantau jadwal servis rutin dan ganti oli armada Anda</p>
            </div>
            <div class="d-flex gap-2">
                <div class="card card-stats bg-white shadow-sm px-3 py-2 d-flex flex-row align-items-center">
                    <div class="bg-danger rounded-circle me-2" style="width:10px; height:10px;"></div>
                    <span class="small fw-bold">{{ $totalPerluServis ?? 0 }} Perlu Servis</span>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">Mobil</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase text-center">Interval</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Servis Terakhir</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Jadwal Berikutnya</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Status</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mobils as $m)
                            @php
                                // 1. Ambil tanggal dari database. Karena sudah di-cast di Model, ini akan jadi objek Carbon.
                                // Jika null, kita set null agar bisa dideteksi.
                                $lastService = $m->tgl_servis_terakhir;

                                // 2. Tentukan interval (default 3 jika kosong)
                                $interval = $m->interval_servis ?? 3;

                                if ($lastService) {
                                    // Jika ada data servis terakhir, hitung jadwal berikutnya
                                    $nextService = $lastService->copy()->addMonths($interval);

                                    // Cek apakah hari ini sudah melewati atau sama dengan jadwal berikutnya
                                    // Kita gunakan startOfDay() agar perbandingannya adil (hanya tanggal, bukan jam)
                                    $isOverdue = now()->startOfDay()->greaterThanOrEqualTo($nextService->startOfDay());
                                } else {
                                    // Jika belum pernah servis (null), otomatis dianggap overdue
                                    $nextService = null;
                                    $isOverdue = true;
                                }
                            @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-3 p-2 me-3">
                                            <i class="bi bi-car-front-fill text-dark"></i>
                                        </div>
                                        <div>
                                            <span class="car-info d-block">{{ $m->nama_mobil }}</span>
                                            <span class="text-muted small">{{ $m->nopol }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $m->interval_servis }} Bulan</span>
                                </td>
                                <td>
                                    {{ $lastService ? $lastService->format('d M Y') : 'Belum Pernah' }}
                                </td>

                                <td>
                                    @if ($nextService)
                                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                            {{ $nextService->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-danger small">Segera Catat Servis</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isOverdue)
                                        <span class="status-badge bg-need-service">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> SERVIS SEKARANG
                                        </span>
                                    @else
                                        <span class="status-badge bg-safe">
                                            <i class="bi bi-check-circle-fill me-1"></i> AMAN
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-dark btn-sm rounded-3 px-3" data-bs-toggle="modal"
                                        data-bs-target="#modalServis{{ $m->id }}">
                                        <i class="bi bi-tools me-1"></i> Catat Servis
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalServis{{ $m->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-0">
                                            <h5 class="fw-bold">Konfirmasi Servis Selesai</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.servis.update', $m->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <p class="text-muted small">Apakah mobil
                                                    <strong>{{ $m->nama_mobil }}</strong> sudah selesai diservis hari ini?
                                                </p>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Tanggal Servis Hari Ini</label>
                                                    <input type="date" name="tgl_servis_terakhir"
                                                        class="form-control rounded-3" value="{{ date('Y-m-d') }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Catatan (Optional)</label>
                                                    <textarea name="keterangan" class="form-control rounded-3" placeholder="Contoh: Ganti oli, filter, rem..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light rounded-3"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-dark rounded-3">Simpan Data</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
