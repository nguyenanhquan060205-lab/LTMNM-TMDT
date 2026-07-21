@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
@php
    $activeTab = session('ActiveTab') ?? request()->query('tab') ?? 'product';
    $tabProductNav = $activeTab == 'product' ? 'active' : '';
    $tabCategoryNav = $activeTab == 'category' ? 'active' : '';
    $tabProductPane = $activeTab == 'product' ? 'show active' : '';
    $tabCategoryPane = $activeTab == 'category' ? 'show active' : '';
@endphp

<style>
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

    .table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 700;
        cursor: pointer;
        user-select: none;
        vertical-align: middle;
    }

        .table th:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

    .table td {
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .btn-category-toggle {
        background-color: #fff;
        color: #444;
        border: 1px solid #ced4da;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
    }

        .btn-category-toggle[aria-expanded="true"] {
            background-color: #e7f1ff;
            border-color: #0d6efd;
            color: #0d6efd;
        }

    /* Filter Section */
    .filter-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
        background: white;
    }

        .filter-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .filter-badge.active {
            border-color: #0d6efd;
            background: #e7f1ff;
            color: #0d6efd;
        }

    .filter-badge-all {
        border-color: #6c757d;
    }

        .filter-badge-all.active {
            border-color: #0d6efd;
            background: #e7f1ff;
            color: #0d6efd;
        }

    .filter-badge-approved {
        color: #198754;
    }

        .filter-badge-approved.active {
            border-color: #198754;
            background: #d1e7dd;
        }

    .filter-badge-hidden {
        color: #495057;
    }

        .filter-badge-hidden.active {
            border-color: #495057;
            background: #e9ecef;
        }

    .filter-badge-sold {
        color: #0dcaf0;
    }

        .filter-badge-sold.active {
            border-color: #0dcaf0;
            background: #cff4fc;
        }

    .status-count {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        background: rgba(0,0,0,0.1);
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid px-4 mt-4 pb-5">

    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <ul class="nav nav-tabs border-0 m-0" id="myTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link {{ $tabProductNav }}" id="product-tab" data-bs-toggle="tab" data-bs-target="#product-pane" type="button">
                    <i class="fa-solid fa-list me-2"></i> Duyệt tin đăng
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $tabCategoryNav }}" id="category-tab" data-bs-toggle="tab" data-bs-target="#category-pane" type="button">
                    <i class="fa-solid fa-tags me-2"></i> Quản lý Loại / Danh mục
                </button>
            </li>
        </ul>

    </div>

    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade {{ $tabProductPane }}" id="product-pane" role="tabpanel">

            <!-- Filter Section - Tách riêng -->
            <div class="filter-section">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="me-2">
                        <i class="fa-solid fa-filter text-muted me-2"></i>
                        <strong class="text-secondary">Lọc theo trạng thái:</strong>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="filter-badge filter-badge-all active" onclick="filterStatus('all', this)">
                            <i class="fa-solid fa-list-ul me-1"></i> Tất cả
                            <span class="status-count" id="count-all">0</span>
                        </span>
                        <span class="filter-badge filter-badge-approved" onclick="filterStatus('Đã duyệt', this)">
                            <i class="fa-solid fa-circle-check me-1"></i> Đã duyệt
                            <span class="status-count" id="count-approved">0</span>
                        </span>
                        <span class="filter-badge filter-badge-hidden" onclick="filterStatus('Ẩn', this)">
                            <i class="fa-solid fa-lock me-1"></i> Đã ẩn/Khóa
                            <span class="status-count" id="count-hidden">0</span>
                        </span>
                        <span class="filter-badge filter-badge-sold" onclick="filterStatus('Đã bán', this)">
                            <i class="fa-solid fa-circle-check me-1"></i> Đã bán
                            <span class="status-count" id="count-sold">0</span>
                        </span>
                    </div>
                    <div class="input-group" style="max-width: 350px;">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="fa-solid fa-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0 shadow-none" placeholder="Tìm tên sản phẩm, người đăng...">
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                            <i class="fa-solid fa-rotate-right me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success mb-3 py-2 small"><i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-3 py-2 small"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="productTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 35%; text-align: left;" onclick="sortTable(0, this)">
                                        Tên sản phẩm <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 20%;" onclick="sortTable(1, this)">
                                        Người đăng <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 15%;" onclick="sortTable(2, this)">
                                        Ngày tạo <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 10%;">Trạng thái</th>
                                    <th style="width: 20%;">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody id="tableBody">
                                @if (isset($dsSanPham))
                                    @foreach ($dsSanPham as $sp)
                                        @php
                                            $badgeClass = "bg-secondary";
                                            if ($sp->TrangThai == "Đã duyệt") { $badgeClass = "bg-success"; }
                                            else if ($sp->TrangThai == "Ẩn") { $badgeClass = "bg-dark text-white"; }
                                            else if ($sp->TrangThai == "Đã bán") { $badgeClass = "bg-info text-dark"; }
                                        @endphp

                                        <tr class="status-row text-center" data-status="{{ $sp->TrangThai }}">
                                            <td class="fw-bold text-start">
                                                <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}"
                                                   target="_blank"
                                                   class="text-primary text-decoration-none"
                                                   title="Xem chi tiết sản phẩm ngoài trang chủ">
                                                    {{ $sp->TenSP }} <i class="fa-solid fa-arrow-up-right-from-square small ms-1 text-muted"></i>
                                                </a>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center justify-content-center text-muted">
                                                    <i class="fa-solid fa-circle-user me-2"></i> {{ $sp->nguoiDung->HoTen ?? '' }}
                                                </div>
                                            </td>

                                            <td data-date="{{ $sp->NgayTao ? \Carbon\Carbon::parse($sp->NgayTao)->format('YmdHis') : '0' }}">
                                                {{ $sp->NgayTao ? \Carbon\Carbon::parse($sp->NgayTao)->format('d/m/Y') : '-' }}
                                            </td>

                                            <td>
                                                <span class="badge {{ $badgeClass }} rounded-pill fw-bold py-2" style="width: 100px;">
                                                    {{ $sp->TrangThai }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form action="{{ route('admin.doitrangthaisanpham') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $sp->MaSP }}" />
                                                        @if ($sp->TrangThai == "Đã duyệt")
                                                            <button type="submit" name="tt" value="Ẩn" class="btn btn-dark btn-sm fw-bold shadow-sm" style="width: 80px;">
                                                                <i class="fa-solid fa-lock me-1"></i> Khóa
                                                            </button>
                                                        @elseif ($sp->TrangThai == "Ẩn")
                                                            <button type="submit" name="tt" value="Đã duyệt" class="btn btn-success btn-sm fw-bold shadow-sm" style="width: 80px;">
                                                                <i class="fa-solid fa-unlock me-1"></i> Mở
                                                            </button>
                                                        @elseif ($sp->TrangThai == "Chưa duyệt" || $sp->TrangThai == "Chờ duyệt")
                                                            <button type="submit" name="tt" value="Đã duyệt" class="btn btn-primary btn-sm fw-bold shadow-sm" style="width: 80px;">
                                                                <i class="fa-solid fa-check me-1"></i> Duyệt
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-light btn-sm border" style="width: 80px;" disabled>
                                                                <i class="fa-solid fa-ban"></i>
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <form action="{{ route('admin.xoasanpham') }}" method="post" onsubmit="return confirm('CẢNH BÁO: Xóa vĩnh viễn sản phẩm này?');">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $sp->MaSP }}" />
                                                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm px-2" title="Xóa vĩnh viễn">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div id="noResults" class="text-center py-5 text-muted" style="display: none;">
                        <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Không tìm thấy sản phẩm nào</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade {{ $tabCategoryPane }}" id="category-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('loaisanpham.them') }}" method="post" class="row g-2 mb-4 align-items-center bg-light p-3 rounded border">
                        @csrf
                        <div class="col-auto"><label class="fw-bold text-secondary">Thêm mới:</label></div>
                        <div class="col-md-4"><input type="text" name="TenLoai" class="form-control form-control-sm" placeholder="Tên danh mục..." required /></div>
                        <div class="col-auto"><button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> Thêm</button></div>
                    </form>

                    <table class="table table-bordered align-middle mt-3">
                        <thead>
                            <tr>
                                <th style="width: 35%">Tên danh mục</th>
                                <th style="width: 45%">Sản phẩm hiển thị</th>
                                <th style="width: 20%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($dsLoaiSanPham))
                                @foreach ($dsLoaiSanPham as $loai)
                                    @php
                                        $spDaDuyet = [];
                                        if (isset($loai->sanPhams) && count($loai->sanPhams) > 0) {
                                            foreach($loai->sanPhams as $sp_con) {
                                                if ($sp_con->TrangThai == 'Đã duyệt') {
                                                    $spDaDuyet[] = $sp_con;
                                                }
                                            }
                                        }
                                        $soLuong = count($spDaDuyet);
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-dark bg-light"><i class="fa-regular fa-folder-open text-primary me-2"></i> {{ $loai->TenLoai }}</td>
                                        <td class="bg-white">
                                            @if ($soLuong > 0)
                                                <button class="btn btn-category-toggle w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#cat-{{ $loai->MaLoai }}">
                                                    <span class="text-success fw-bold small"><i class="fa-solid fa-circle-check me-1"></i> {{ $soLuong }} sản phẩm</span>
                                                    <i class="fa-solid fa-chevron-down small"></i>
                                                </button>
                                                <div class="collapse mt-2" id="cat-{{ $loai->MaLoai }}">
                                                    <div class="card card-body bg-light border-0 p-2 small">
                                                        <ul class="list-group list-group-flush rounded">
                                                            @foreach ($spDaDuyet as $spCon)
                                                                <li class="list-group-item bg-white py-1">{{ $spCon->TenSP }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small fst-italic ms-2">Trống</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary border-0" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $loai->MaLoai }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <form action="{{ route('loaisanpham.xoa') }}" method="post" class="d-inline" onsubmit="return confirm('Xóa {{ $loai->TenLoai }}?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $loai->MaLoai }}" />
                                                <button class="btn btn-sm btn-outline-danger border-0"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (isset($dsLoaiSanPham))
    @foreach ($dsLoaiSanPham as $loai)
        <div class="modal fade" id="modalEdit-{{ $loai->MaLoai }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white py-2">
                        <h6 class="modal-title">Cập nhật tên</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('loaisanpham.sua') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{ $loai->MaLoai }}" />
                            <input type="text" name="TenLoaiMoi" class="form-control" value="{{ $loai->TenLoai }}" required />
                        </div>
                        <div class="modal-footer py-1">
                            <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection

@section('scripts')
<script>
    let currentFilter = 'all';

    // Đếm số lượng theo trạng thái khi tải trang
    document.addEventListener('DOMContentLoaded', function () {
        updateStatusCounts();
    });

    function updateStatusCounts() {
        let rows = document.querySelectorAll('.status-row');
        let counts = {
            all: rows.length,
            'Đã duyệt': 0,
            'Ẩn': 0,
            'Đã bán': 0
        };

        rows.forEach(row => {
            let status = row.getAttribute('data-status');
            if (counts[status] !== undefined) {
                counts[status]++;
            }
        });

        document.getElementById('count-all').textContent = counts.all;
        document.getElementById('count-approved').textContent = counts['Đã duyệt'];
        document.getElementById('count-hidden').textContent = counts['Ẩn'];
        document.getElementById('count-sold').textContent = counts['Đã bán'];
    }

    // Tìm kiếm
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            let textName = row.cells[0].textContent.toLowerCase();
            let textUser = row.cells[1].textContent.toLowerCase();
            let matchesSearch = textName.includes(filter) || textUser.includes(filter);
            let matchesStatus = currentFilter === 'all' || row.getAttribute('data-status') === currentFilter;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
    });

    // Lọc trạng thái với badge active
    function filterStatus(status, element) {
        currentFilter = status;

        // Remove active từ tất cả badges
        document.querySelectorAll('.filter-badge').forEach(badge => {
            badge.classList.remove('active');
        });

        // Add active vào badge được chọn
        element.classList.add('active');

        let rows = document.querySelectorAll('.status-row');
        let visibleCount = 0;
        let searchValue = document.getElementById('searchInput').value.toLowerCase();

        rows.forEach(row => {
            let rowStatus = row.getAttribute('data-status');
            let textName = row.cells[0].textContent.toLowerCase();
            let textUser = row.cells[1].textContent.toLowerCase();
            let matchesSearch = !searchValue || textName.includes(searchValue) || textUser.includes(searchValue);
            let matchesStatus = status === 'all' || rowStatus === status;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('searchInput').value = '';
        filterStatus('all', document.querySelector('.filter-badge-all'));
    }

    // Sắp xếp
    function sortTable(n, thElement) {
        var table = document.getElementById("productTable");
        var rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        dir = "asc";

        var allIcons = table.querySelectorAll('th i');
        allIcons.forEach(icon => icon.className = "fa-solid fa-sort float-end text-muted mt-1");
        var currentIcon = thElement.querySelector('i');

        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                let xVal = n === 2 ? Number(x.getAttribute('data-date')) : x.innerText.toLowerCase();
                let yVal = n === 2 ? Number(y.getAttribute('data-date')) : y.innerText.toLowerCase();

                if (dir == "asc") {
                    if (xVal > yVal) { shouldSwitch = true; break; }
                } else if (dir == "desc") {
                    if (xVal < yVal) { shouldSwitch = true; break; }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
        if (dir === "asc") {
            currentIcon.className = "fa-solid fa-sort-up float-end text-primary mt-2";
        } else {
            currentIcon.className = "fa-solid fa-sort-down float-end text-primary mb-1";
        }
    }
</script>
@endsection
