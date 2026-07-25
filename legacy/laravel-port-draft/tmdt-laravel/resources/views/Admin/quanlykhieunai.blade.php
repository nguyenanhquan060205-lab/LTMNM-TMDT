@extends('layouts.admin')

@section('title', 'Quản lý khiếu nại')

@section('content')
<style>
    /* TAB */
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 600;
        border: none;
        padding: 12px 20px;
        transition: 0.2s;
    }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background: transparent;
        }

        .nav-tabs .nav-link:hover {
            color: #0a58ca;
        }

    /* TABLE */
    .table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: .5px;
        cursor: pointer;
        user-select: none;
        vertical-align: middle;
    }

        .table th:hover {
            background: #e9ecef;
            color: #0d6efd;
        }

    .table td {
        vertical-align: middle;
    }

    /* SEARCH BOX */
    .search-box .input-group-text {
        background-color: #fff;
        border-right: none;
        color: #aaa;
    }

    .search-box .form-control {
        border-left: none;
        box-shadow: none;
    }

    /* FILTER BUTTONS */
    .ts-filter-btn {
        border: 1px solid #d0d5dd;
        background: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: .15s;
        color: #555;
    }

        .ts-filter-btn:hover {
            background: #f1f4f8;
        }

        .ts-filter-btn.active {
            border-color: #0d6efd;
            background: #e7f0ff;
            color: #0d6efd;
        }

    .ts-warning {
        border-color: #e4a11b;
        color: #e4a11b;
    }

        .ts-warning.active {
            background: #fff3cd;
        }

    .ts-success {
        border-color: #198754;
        color: #198754;
    }

        .ts-success.active {
            background: #d1f3e0;
        }
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
    <div class="bg-white p-3 rounded border mb-3 d-flex flex-wrap gap-2 align-items-center">

        <div class="fw-bold text-secondary d-flex align-items-center me-2">
            <i class="fa-solid fa-filter text-muted me-2"></i> Lọc theo trạng thái:
        </div>

        <button class="ts-filter-btn active" onclick="filterStatus('all', this)">
            <i class="fa-solid fa-list me-1"></i> Tất cả
        </button>

        <button class="ts-filter-btn ts-warning" onclick="filterStatus('Chưa xử lý', this)">
            <i class="fa-solid fa-clock me-1"></i> Chưa xử lý
        </button>

        <button class="ts-filter-btn ts-success" onclick="filterStatus('Đã giải quyết', this)">
            <i class="fa-solid fa-check-circle me-1"></i> Đã giải quyết
        </button>
        <div class="input-group search-box" style="max-width: 300px;">
            <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm người gửi, nội dung...">
        </div>

        <div class="ms-auto">
            <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                <i class="fa-solid fa-rotate-right me-1"></i> Reset
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
                        @if (isset($dsKhieuNai) && count($dsKhieuNai) > 0)
                            @foreach ($dsKhieuNai as $item)
                                @php
                                    $badgeClass = $item->TrangThai == "Đã giải quyết" ? "bg-success" : "bg-warning text-dark";
                                @endphp

                                <tr class="status-row text-center" data-status="{{ $item->TrangThai }}">
                                    <td class="text-start fw-bold text-dark" style="padding-left:20px;">
                                        <i class="fa-solid fa-user me-2 text-muted"></i>
                                        {{ $item->nguoiDung->HoTen ?? "Ẩn danh" }}
                                    </td>

                                    <td class="text-start text-primary fw-semibold">
                                        {{ $item->sanPham->TenSP ?? "Sản phẩm đã xóa" }}
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
                                        @if ($item->TrangThai == "Chưa xử lý" || $item->TrangThai == "Đang chờ xử lý" || $item->TrangThai == "Đang chờ xữ lý")
                                            <form action="{{ route('admin.capnhattrangthaikn') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->MaKN }}" />
                                                <button type="submit" class="btn btn-success btn-sm fw-bold" style="width:100px;">
                                                    Xử lý xong
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-light btn-sm text-muted border" style="width:100px;" disabled>
                                                Hoàn tất
                                            </button>
                                            <form action="{{ route('admin.xoakhieunai') }}"
                                                  method="post"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xoá khiếu nại này?');"
                                                  style="display:inline-block">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->MaKN }}" />

                                                <button type="submit" class="btn btn-danger btn-sm fw-bold" style="width:100px;">
                                                    Xoá
                                                </button>
                                            </form>
                                        @endif
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
@endsection

@section('scripts')
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
