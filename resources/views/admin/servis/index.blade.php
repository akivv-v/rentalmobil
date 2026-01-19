@extends('admin.layouts.app')

@section('content')
    <style>
        .card-stats {
            border-radius: 15px;
            border: none;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
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

        /* Membuat baris tabel terlihat bisa diklik */
        .clickable-row {
            cursor: pointer;
            transition: background 0.2s;
        }

        .clickable-row:hover {
            background-color: #f8fafc !important;
        }

        .catatan-preview {
            display: inline-block;
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.8rem;
            color: #64748b;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Pemeliharaan Mobil</h2>
                <p class="text-muted small">Klik pada baris mobil untuk melihat detail pengerjaan</p>
            </div>
            <div class="card card-stats bg-white shadow-sm px-3 py-2 d-flex flex-row align-items-center">
                <div class="bg-danger rounded-circle me-2" style="width:10px; height:10px;"></div>
                <span class="small fw-bold text-dark">{{ $totalPerluServis ?? 0 }} Unit Perlu Perhatian</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold">Unit Mobil</th>
                            <th class="py-3 text-muted small fw-bold text-center">Interval</th>
                            <th class="py-3 text-muted small fw-bold">Servis Terakhir</th>
                            <th class="py-3 text-muted small fw-bold">Jadwal Berikutnya</th>
                            <th class="py-3 text-muted small fw-bold">Status</th>
                            <th class="py-3 text-muted small fw-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mobils as $m)
                            @php
                                $lastService = $m->tgl_servis_terakhir
                                    ? \Carbon\Carbon::parse($m->tgl_servis_terakhir)
                                    : null;
                                $interval = $m->interval_servis ?? 6;
                                if ($lastService) {
                                    $nextService = $lastService->copy()->addMonths($interval);
                                    $isOverdue = now()->startOfDay()->greaterThanOrEqualTo($nextService->startOfDay());
                                } else {
                                    $nextService = null;
                                    $isOverdue = true;
                                }
                            @endphp

                            {{-- Baris ini bisa diklik untuk memicu Modal Detail --}}
                            <tr class="clickable-row" data-bs-toggle="modal"
                                data-bs-target="#modalDetail{{ $m->id }}">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-3 p-2 me-3">
                                            <i class="bi bi-car-front-fill text-dark"></i>
                                        </div>
                                        <div>
                                            <span class="car-info d-block">{{ $m->nama_mobil }}</span>
                                            <span class="text-muted small">{{ $m->plat_nomor }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-light text-dark border px-3 py-2 rounded-pill small">{{ $interval }}
                                        Bulan</span>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold text-dark">{{ $lastService ? $lastService->format('d M Y') : 'N/A' }}</span>
                                    @if ($m->catatan_servis)
                                        <br><span class="catatan-preview"><i class="bi bi-card-text"></i>
                                            {{ $m->catatan_servis }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($nextService)
                                        <span
                                            class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-primary' }}">{{ $nextService->format('d M Y') }}</span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge {{ $isOverdue ? 'bg-need-service' : 'bg-safe' }}">
                                        {{ $isOverdue ? 'PERLU SERVIS' : 'PRIMA' }}
                                    </span>
                                </td>
                                <td class="text-center" onclick="event.stopPropagation();">
                                    {{-- StopPropagation agar klik tombol update tidak memicu modal detail --}}
                                    <button type="button" class="btn btn-dark btn-sm rounded-3 px-3 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $m->id }}">
                                        <i class="bi bi-tools me-1"></i> Update
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL 1: DETAIL PENGERJAAN --}}
                            <div class="modal fade" id="modalDetail{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header bg-light border-0">
                                            <h5 class="fw-bold mb-0">Detail Pemeliharaan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-4">
                                            <div class="text-center mb-4">
                                                <div class="car-info fs-5">{{ $m->nama_mobil }}</div>
                                                <div class="text-muted">{{ $m->plat_nomor }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="text-muted small d-block">Tanggal Servis Terakhir:</label>
                                                <span
                                                    class="fw-bold">{{ $lastService ? $lastService->format('d F Y') : 'Belum tercatat' }}</span>
                                            </div>
                                            <div class="p-3 bg-light rounded-3 border-start border-primary border-4">
                                                <label class="fw-bold d-block mb-1 small text-primary">ISI CATATAN /
                                                    PENGERJAAN:</label>
                                                <p class="mb-0 text-dark" style="pre-wrap;">
                                                    {{ $m->catatan_servis ?? 'Tidak ada catatan pengerjaan.' }}</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary w-100 rounded-3"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MODAL 2: UPDATE DATA (FORM) --}}
                            <div class="modal fade" id="modalUpdate{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0">
                                            <h5 class="fw-bold">Input Riwayat Servis</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.servis.update', $m->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Tanggal Selesai Servis</label>
                                                    <input type="date" name="tgl_servis_terakhir" class="form-control"
                                                        value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label small fw-bold">Catatan Pengerjaan</label>
                                                    <textarea name="keterangan" class="form-control" rows="4" placeholder="Apa saja yang diganti/diperbaiki?">{{ $m->catatan_servis }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="submit" class="btn btn-dark px-4 w-100 shadow">Simpan
                                                    Data</button>
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
