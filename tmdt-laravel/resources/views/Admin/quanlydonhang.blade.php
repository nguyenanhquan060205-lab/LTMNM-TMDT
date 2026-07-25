@extends('Shared._LayoutAdmin')
@section('title', 'Quản lý đơn hàng')

@section('content')
<style>
    /* CSS ĐỒNG BỘ */
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
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
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
        color: #333;
    }

    .search-box .input-group-text {
        background-color: #fff;
        border-right: none;
        color: #aaa;
    }

    .search-box .form-control {
        border-left: none;
        box-shadow: none;
    }

    .search-box .form-control:focus {
        border-color: #ced4da;
    }
</style>

<div class="container-fluid px-4 mt-4 pb-5">

    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <ul class="nav nav-tabs border-0 m-0" id="myTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="order-tab" data-bs-toggle="tab" data-bs-target="#order-pane" type="button">
                    <i class="fa-solid fa-file-invoice me-2"></i> Quản lý đơn hàng
                </button>
            </li>
        </ul>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted fw-semibold"><i class="fa-solid fa-filter me-1"></i> Lọc theo trạng thái:</span>
            <button class="btn btn-sm btn-outline-primary active" onclick="filterStatus('all', this)">
                <i class="fa-solid fa-list me-1"></i> Tất cả
            </button>
            <button class="btn btn-sm btn-outline-warning" onclick="filterStatus('Đang chờ xử lý', this)">
                <i class="fa-solid fa-clock me-1"></i> Đang chờ xử lý
            </button>
            <button class="btn btn-sm btn-outline-success" onclick="filterStatus('Đã thanh toán', this)">
                <i class="fa-solid fa-check-circle me-1"></i> Đã thanh toán
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="filterStatus('Đã Huỷ', this)">
                <i class="fa-solid fa-times-circle me-1"></i> Đã Huỷ
            </button>
        </div>

        <div class="input-group search-box" style="max-width: 350px;">
            <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm tên người mua, người bán...">
            <button class="btn btn-outline-secondary" type="button" onclick="resetSearch()">
                <i class="fa-solid fa-rotate-right"></i> Reset
            </button>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="order-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="orderTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 20%; text-align: left; padding-left: 20px;" onclick="sortTable(0, this)">
                                        Người mua <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 20%;" onclick="sortTable(1, this)">
                                        Người bán <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 15%;" onclick="sortTable(2, this)">
                                        Ngày đặt <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 15%;" onclick="sortTable(3, this)">
                                        Tổng tiền <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 20%;" onclick="sortTable(4, this)">
                                        Trạng thái <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                                    </th>
                                    <th style="width: 10%;">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @if ($donhangs && count($donhangs) > 0)
                                    @foreach ($donhangs as $item)
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if (mb_strtolower(trim($item['TrangThai'])) == 'đang chờ xử lý') { $badgeClass = 'bg-warning text-dark'; }
                                            elseif (mb_strtolower(trim($item['TrangThai'])) == 'đang vận chuyển') { $badgeClass = 'bg-info text-white'; }
                                            elseif (mb_strtolower(trim($item['TrangThai'])) == 'đã thanh toán') { $badgeClass = 'bg-success'; }
                                            elseif (mb_strtolower(trim($item['TrangThai'])) == 'đã huỷ') { $badgeClass = 'bg-danger'; }
                                        @endphp

                                        <tr class="status-row text-center" data-status="{{ $item['TrangThai'] }}">
                                            <td class="text-start fw-bold text-dark" style="padding-left: 20px;">
                                                <i class="fa-solid fa-user-tag text-muted me-2"></i> {{ $item['NguoiMua'] }}
                                            </td>

                                            <td class="text-muted">
                                                <i class="fa-solid fa-shop me-1"></i> {{ $item['NguoiBan'] }}
                                            </td>

                                            <td data-date="{{ $item['NgayDat'] ? \Carbon\Carbon::parse($item['NgayDat'])->format('YmdHis') : '0' }}">
                                                {{ $item['NgayDat'] ? \Carbon\Carbon::parse($item['NgayDat'])->format('d/m/Y') : '' }}
                                            </td>

                                            <td class="text-danger fw-bold" data-money="{{ $item['TongTien'] }}">
                                                {{ number_format($item['TongTien'], 0, ',', '.') }} ₫
                                            </td>

                                            <td>
                                                <span class="badge {{ $badgeClass }} rounded-pill fw-bold py-2 px-3" style="min-width: 110px;">
                                                    {{ $item['TrangThai'] }}
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ url('/taikhoan/ctlichsu/' . $item['MaHD']) }}"
                                                   class="btn btn-sm btn-outline-primary shadow-sm"
                                                   title="Xem chi tiết đơn hàng">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fa-solid fa-box-open fs-1 mb-3 d-block text-secondary opacity-50"></i>
                                            Chưa có đơn hàng nào.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFilter = 'all';

    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableBody tr.status-row');
        rows.forEach(row => {
            let textMua = row.cells[0].textContent.toLowerCase();
            let textBan = row.cells[1].textContent.toLowerCase();
            let matchesSearch = (textMua.includes(filter) || textBan.includes(filter));
            let matchesStatus = (currentFilter === 'all' || row.getAttribute('data-status') === currentFilter);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function resetSearch() {
        document.getElementById('searchInput').value = '';
        filterStatus('all', document.querySelector('.btn-outline-primary.active'));
    }

    function filterStatus(status, buttonElement) {
        currentFilter = status;

        document.querySelectorAll('.btn-outline-primary, .btn-outline-warning, .btn-outline-info, .btn-outline-success, .btn-outline-danger').forEach(btn => {
            btn.classList.remove('active');
        });

        if (buttonElement) {
            buttonElement.classList.add('active');
        }

        let searchFilter = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.querySelectorAll('.status-row');

        rows.forEach(row => {
            let rowStatus = row.getAttribute('data-status');
            let textMua = row.cells[0].textContent.toLowerCase();
            let textBan = row.cells[1].textContent.toLowerCase();
            let matchesSearch = !searchFilter || (textMua.includes(searchFilter) || textBan.includes(searchFilter));
            let matchesStatus = (status === 'all' || rowStatus === status);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function sortTable(n, thElement) {
        var table = document.getElementById("orderTable");
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

                let xVal, yVal;
                if (n === 2) { xVal = Number(x.getAttribute('data-date')); yVal = Number(y.getAttribute('data-date')); }
                else if (n === 3) { xVal = Number(x.getAttribute('data-money')); yVal = Number(y.getAttribute('data-money')); }
                else { xVal = x.innerText.toLowerCase(); yVal = y.innerText.toLowerCase(); }

                if (dir == "asc") { if (xVal > yVal) { shouldSwitch = true; break; } }
                else if (dir == "desc") { if (xVal < yVal) { shouldSwitch = true; break; } }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") { dir = "desc"; switching = true; }
            }
        }
        if (dir === "asc") { currentIcon.className = "fa-solid fa-sort-up float-end text-primary mt-2"; }
        else { currentIcon.className = "fa-solid fa-sort-down float-end text-primary mb-1"; }
    }
</script>
@endsection
