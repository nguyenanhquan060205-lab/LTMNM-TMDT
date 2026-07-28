@extends('shared._layoutadmin')
@section('title', 'Quản lý khiếu nại')

@section('content')
<style>
    /* Tabs hiện đại */
    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    .nav-tabs .nav-link {
        color: #718096;
        font-weight: 600;
        border: none;
        padding: 12px 24px;
        transition: all 0.3s ease;
        border-radius: 10px 10px 0 0;
        position: relative;
    }
    .nav-tabs .nav-link:hover {
        color: #4a5568;
        background: #edf2f7;
    }
    .nav-tabs .nav-link.active {
        color: #667eea;
        background: transparent;
    }
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px 3px 0 0;
    }

    /* Bảng sang trọng */
    .card {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .table th {
        background: #f8f9fa;
        color: #4a5568;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .85rem;
        cursor: pointer;
        user-select: none;
        padding: 16px;
        border-bottom: 2px solid #edf2f7;
    }
    .table th:hover {
        background: #edf2f7;
        color: #667eea;
    }
    .table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.95rem;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Lọc trạng thái */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .ts-filter-btn {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #4a5568;
    }
    .ts-filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #cbd5e0;
    }
    .ts-filter-btn.active {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        color: #5a67d8;
    }

    .ts-warning { border-color: #f6ad55; color: #dd6b20; }
    .ts-warning.active { background: #fffaf0; border-color: #dd6b20; }
    .ts-success { border-color: #68d391; color: #2f855a; }
    .ts-success.active { background: #f0fff4; border-color: #2f855a; }
</style>

<div class="container-fluid px-4 mt-4 pb-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <ul class="nav nav-tabs border-0 m-0">
            <li class="nav-item">
                <button class="nav-link active">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Quản lý khiếu nại
                </button>
            </li>
        </ul>
    </div>

    <!-- FILTER -->
    <div class="filter-section d-flex flex-wrap gap-3 align-items-center mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted fw-bold me-2"><i class="fa-solid fa-filter me-1"></i> Lọc trạng thái:</span>
            <button class="ts-filter-btn active" onclick="filterStatus('all', this)">
                <i class="fa-solid fa-list me-1"></i> Tất cả
            </button>
            <button class="ts-filter-btn ts-warning" onclick="filterStatus('Chưa xử lý', this)">
                <i class="fa-solid fa-clock me-1"></i> Chưa xử lý
            </button>
            <button class="ts-filter-btn ts-success" onclick="filterStatus('Đã giải quyết', this)">
                <i class="fa-solid fa-check-circle me-1"></i> Đã giải quyết
            </button>
        </div>

        <div class="input-group search-box" style="max-width: 300px; margin-left: auto;">
            <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm người gửi, nội dung...">
            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="fa-solid fa-rotate-right"></i> Reset
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="complaintTable">
                    <thead>
                        <tr class="text-center">
                            <th style="text-align:left;padding-left:20px;" onclick="sortTable(0, this)">
                                Người gửi <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                            </th>
                            <th style="text-align:left;" onclick="sortTable(1, this)">
                                Sản phẩm <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                            </th>
                            <th style="text-align:left;">Nội dung chi tiết</th>
                            <th onclick="sortTable(3, this)">
                                Ngày gửi <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                            </th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        @if ($dsKhieuNai && $dsKhieuNai->count() > 0)
                            @foreach ($dsKhieuNai as $item)
                                @php
                                    $badgeClass = $item->TrangThai == 'Đã giải quyết' ? 'bg-success' : 'bg-warning text-dark';
                                @endphp

                                <tr class="status-row text-center" data-status="{{ $item->TrangThai }}">
                                    <td class="text-start fw-bold text-dark" style="padding-left:20px;">
                                        <i class="fa-solid fa-user me-2 text-muted"></i>
                                        {{ $item->nguoiDung->HoTen ?? 'Ẩn danh' }}
                                    </td>

                                    <td class="text-start text-primary fw-semibold">
                                        {{ $item->sanPham->TenSP ?? 'Sản phẩm đã xóa' }}
                                    </td>

                                    <td class="text-start text-muted small">{{ $item->MoTa }}</td>

                                    <td data-date="{{ $item->NgayGui ? \Carbon\Carbon::parse($item->NgayGui)->format('YmdHis') : '0' }}">
                                        {{ $item->NgayGui ? \Carbon\Carbon::parse($item->NgayGui)->format('d/m/Y') : '-' }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2" style="min-width:110px;">
                                            {{ $item->TrangThai }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <!-- Nút Chat -->
                                            <a href="{{ url('/tinnhan/chat?idNguoiNhan=' . $item->MaKH) }}" class="btn btn-light text-primary border rounded-circle shadow-sm d-flex align-items-center justify-content-center" title="Chat với Người Mua" target="_blank" style="width: 38px; height: 38px;">
                                                <i class="fa-solid fa-user-tag"></i>
                                            </a>
                                            <a href="{{ url('/tinnhan/chat?idNguoiNhan=' . ($item->sanPham->MaKH ?? '')) }}" class="btn btn-light text-info border rounded-circle shadow-sm d-flex align-items-center justify-content-center" title="Chat với Người Bán" target="_blank" style="width: 38px; height: 38px;">
                                                <i class="fa-solid fa-store"></i>
                                            </a>
                                            
                                            <div class="vr mx-1 text-muted"></div>
                                            
                                            <!-- Nút Thao tác -->
                                            @if ($item->TrangThai == 'Chưa xử lý')
                                                <form action="{{ url('/admin/capnhattrangthaikn') }}" method="post" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->MaKN }}" />
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                                        <i class="fa-solid fa-check me-1"></i> Xong
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ url('/admin/xoakhieunai') }}" method="post" class="m-0" onsubmit="event.preventDefault(); Swal.fire({ title: 'Xác nhận', text: 'Bạn có chắc chắn muốn xoá khiếu nại này?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Xóa', cancelButtonText: 'Hủy', confirmButtonColor: '#d33' }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->MaKN }}" />
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                                        <i class="fa-solid fa-trash-can me-1"></i> Xoá
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-check-double fs-1 mb-3 d-block text-success opacity-50"></i>
                                    Không có khiếu nại nào.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    // SEARCH
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableBody tr.status-row');

        rows.forEach(row => {
            let gui = row.cells[0].innerText.toLowerCase();
            let sp = row.cells[1].innerText.toLowerCase();

            row.style.display = (gui.includes(filter) || sp.includes(filter)) ? "" : "none";
        });
    });

    // FILTER STATUS
    function filterStatus(status, btn) {

        document.querySelectorAll('.ts-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        let search = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.querySelectorAll('.status-row');

        rows.forEach(row => {
            let st = row.getAttribute('data-status');
            let gui = row.cells[0].innerText.toLowerCase();
            let sp = row.cells[1].innerText.toLowerCase();

            let matchStatus = (status === "all" || st === status);
            let matchSearch = (gui.includes(search) || sp.includes(search));

            row.style.display = (matchStatus && matchSearch) ? "" : "none";
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = "";
        filterStatus('all', document.querySelector('.ts-filter-btn'));
    }

    // SORT
    function sortTable(n, thElement) {
        let tbody = document.querySelector("#tableBody");
        let rows = Array.from(tbody.rows);
        let dir = thElement.classList.contains("asc") ? "desc" : "asc";

        // reset icon + class
        document.querySelectorAll("th").forEach(th => th.classList.remove("asc", "desc"));
        thElement.classList.add(dir);

        rows.sort((a, b) => {
            let x = a.cells[n];
            let y = b.cells[n];

            let xVal = n === 3 ? Number(x.dataset.date || 0) : x.innerText.toLowerCase();
            let yVal = n === 3 ? Number(y.dataset.date || 0) : y.innerText.toLowerCase();

            if (xVal < yVal) return dir === "asc" ? -1 : 1;
            if (xVal > yVal) return dir === "asc" ? 1 : -1;
            return 0;
        });

        rows.forEach(r => tbody.appendChild(r));

        // icon
        let icon = thElement.querySelector("i");
        icon.className = dir === "asc"
            ? "fa-solid fa-sort-up float-end text-primary mt-2"
            : "fa-solid fa-sort-down float-end text-primary mb-1";
    }

</script>
@endsection

