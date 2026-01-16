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

        /* Style khusus untuk kotak catatan agar admin mudah membaca */
        .catatan-box {
            font-size: 0.75rem;
            color: #475569;
            background-color: #f8fafc;
            border-left: 3px solid #0ea5e9;
            padding: 8px 12px;
            margin-top: 8px;
            border-radius: 4px;
            max-width: 250px;
            line-height: 1.4;
        }
    </style>

    <div class="container-fluid py-4">
        {{-- Header & Ringkasan --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Pemeliharaan Armada</h2>
                <p class="text-muted small">Kelola jadwal servis rutin dan pantau kesehatan mesin mobil</p>
            </div>
            <div class="card card-stats bg-white shadow-sm px-3 py-2 d-flex flex-row align-items-center">
                <div class="bg-danger rounded-circle me-2" style="width:10px; height:10px;"></div>
                <span class="small fw-bold text-dark">{{ $totalPerluServis ?? 0 }} Unit Perlu Perhatian</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Utama --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-uppercase">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold">Unit Mobil</th>
                            <th class="py-3 text-muted small fw-bold text-center">Interval</th>
                            <th class="py-3 text-muted small fw-bold">Servis Terakhir & Catatan</th>
                            <th class="py-3 text-muted small fw-bold">Jadwal Berikutnya</th>
                            <th class="py-3 text-muted small fw-bold">Status Kesehatan</th>
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
                            <tr>
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
                                        class="badge bg-light text-dark border-secondary-subtle px-3 py-2 rounded-pill small">
                                        {{ $interval }} Bulan
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">
                                        {{ $lastService ? $lastService->format('d M Y') : 'Belum Pernah' }}
                                    </span>

                                    {{-- BAGIAN CATATAN SERVIS --}}
                                    @if ($m->catatan_servis)
                                        <div class="catatan-box">
                                            <strong class="d-block text-uppercase mb-1"
                                                style="font-size: 0.65rem; color: #0ea5e9;">Detail Pengerjaan:</strong>
                                            "{{ $m->catatan_servis }}"
                                        </div>
                                    @else
                                        <div class="mt-1 small text-muted fst-italic">Tidak ada catatan pengerjaan</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($nextService)
                                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-primary' }}">
                                            {{ $nextService->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isOverdue)
                                        <span class="status-badge bg-need-service">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> PERLU SERVIS
                                        </span>
                                    @else
                                        <span class="status-badge bg-safe">
                                            <i class="bi bi-check-circle-fill me-1"></i> KONDISI PRIMA
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-dark btn-sm rounded-3 px-3 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalServis{{ $m->id }}">
                                        <i class="bi bi-tools me-1"></i> Update
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Update Servis --}}
                            <div class="modal fade" id="modalServis{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold">Update Histori Servis</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.servis.update', $m->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body py-4">
                                                <div class="mb-4 text-center p-3 bg-light rounded-3">
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $m->nama_mobil }}</h6>
                                                    <span class="small text-muted">{{ $m->plat_nomor }}</span>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Tanggal Servis Selesai</label>
                                                    <input type="date" name="tgl_servis_terakhir" class="form-control"
                                                        value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label small fw-bold">Catatan (Apa saja yang
                                                        diservis?)</label>
                                                    <textarea name="keterangan" class="form-control" rows="3"
                                                        placeholder="Misal: Ganti oli mesin, cek rem, ganti filter udara.">{{ $m->catatan_servis }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light px-4"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-dark px-4">Simpan Data</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
