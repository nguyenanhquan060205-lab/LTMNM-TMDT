@extends('Shared._LayoutAdmin')
@section('title', 'Quản lý người dùng')

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

    /* FILTER BUTTONS */
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

    .ts-role-admin { border-color: #fc8181; color: #e53e3e; }
    .ts-role-admin.active { background: #fff5f5; border-color: #e53e3e; }
    .ts-role-user { border-color: #a0aec0; color: #4a5568; }
    .ts-role-user.active { background: #f7fafc; border-color: #4a5568; }

    /* TABLE */
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
</style>

<div class="container-fluid px-4 mt-4 pb-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <ul class="nav nav-tabs border-0 m-0">
            <li class="nav-item">
                <button class="nav-link active">
                    <i class="fa-solid fa-users me-2"></i> Quản lý người dùng
                </button>
            </li>
        </ul>

        <!-- SEARCH BOX -->
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white text-muted border-end-0">
                <i class="fa-solid fa-search"></i>
            </span>
            <input id="searchInput" class="form-control border-start-0 shadow-none" placeholder="Tìm tên, email...">
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section bg-white p-3 rounded border mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="fw-bold text-secondary d-flex align-items-center me-2">
            <i class="fa-solid fa-filter text-muted me-2"></i> Lọc theo vai trò:
        </div>

        <button class="ts-filter-btn active" onclick="filterRole('all', this)">
            <i class="fa-solid fa-list me-1"></i> Tất cả
        </button>

        <button class="ts-filter-btn ts-role-user" onclick="filterRole('User', this)">
            <i class="fa-solid fa-user me-1"></i> User
        </button>

        <button class="ts-filter-btn ts-role-admin" onclick="filterRole('Admin', this)">
            <i class="fa-solid fa-user-shield me-1"></i> Admin
        </button>

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
                <table class="table table-hover align-middle mb-0" id="userTable">
                    <thead>
                        <tr class="text-center">
                            <th style="text-align:left; padding-left:20px;" onclick="sortTable(0, this)">
                                Họ và tên <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                            </th>

                            <th style="text-align:left;" onclick="sortTable(1, this)">
                                Email <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                            </th>

                            <th onclick="sortTable(2, this)">
                                Ngày tạo <i class="fa-solid fa-sort float-end text-muted mt-1"></i>
                            </th>

                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        @foreach ($dsNguoiDung as $nd)
                            @php
                                $isAdmin = $nd->VaiTro == 'Admin';
                                $roleBadge = $isAdmin ? 'bg-danger' : 'bg-secondary';
                                $statusBadge = $nd->Khoa ? 'bg-dark text-white' : 'bg-success';
                                $statusText = $nd->Khoa ? 'Đang khóa' : 'Hoạt động';
                            @endphp

                            <tr class="status-row text-center" data-role="{{ $nd->VaiTro }}">
                                <td class="text-start" style="padding-left:20px;">
                                    <i class="fa-solid fa-circle-user me-2 text-muted"></i>
                                    <span class="fw-bold text-primary">{{ $nd->HoTen }}</span>
                                </td>

                                <td class="text-start">{{ $nd->Email }}</td>

                                <td data-date="{{ $nd->NgayTao ? \Carbon\Carbon::parse($nd->NgayTao)->format('YmdHis') : '0' }}">
                                    {{ $nd->NgayTao ? \Carbon\Carbon::parse($nd->NgayTao)->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    <span class="badge {{ $roleBadge }} rounded-pill px-3 py-2">{{ $nd->VaiTro }}</span>
                                </td>

                                <td>
                                    <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2">{{ $statusText }}</span>
                                </td>

                                <td>
                                    @if ($isAdmin)
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fa-solid fa-ban"></i> Khóa
                                        </button>
                                    @else
                                        <form method="post" action="{{ url('/admin/doitrangthainguoidung') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $nd->MaKH }}" />

                                            @if ($nd->Khoa)
                                                <button type="submit" class="btn btn-sm btn-success fw-bold">
                                                    <i class="fa-solid fa-unlock"></i> Mở khóa
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-dark fw-bold">
                                                    <i class="fa-solid fa-lock"></i> Khóa
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
    /* SEARCH */
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(row => {
            let name = row.cells[0].innerText.toLowerCase();
            let email = row.cells[1].innerText.toLowerCase();
            row.style.display = (name.includes(value) || email.includes(value)) ? "" : "none";
        });
    });

    /* FILTER ROLE */
    function filterRole(role, btn) {
        document.querySelectorAll('.ts-filter-btn').forEach(e => e.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.status-row').forEach(row => {
            let r = row.getAttribute('data-role');
            row.style.display = (role === 'all' || r === role) ? "" : "none";
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = "";
        filterRole('all', document.querySelector('.ts-filter-btn'));
    }

    /* SORTING */
    function sortTable(n, th) {
        let table = document.getElementById("userTable");
        let switching = true;
        let dir = "asc";
        let switchcount = 0;

        let icons = table.querySelectorAll("th i");
        icons.forEach(i => i.className = "fa-solid fa-sort float-end text-muted mt-1");

        let icon = th.querySelector("i");

        while (switching) {
            switching = false;
            let rows = table.rows;
            let shouldSwitch = false;
            let i;

            for (i = 1; i < rows.length - 1; i++) {
                let x = rows[i].getElementsByTagName("TD")[n];
                let y = rows[i + 1].getElementsByTagName("TD")[n];

                let xVal = n === 2 ? Number(x.dataset.date) : x.innerText.toLowerCase();
                let yVal = n === 2 ? Number(y.dataset.date) : y.innerText.toLowerCase();

                if ((dir === "asc" && xVal > yVal) ||
                    (dir === "desc" && xVal < yVal)) {
                    shouldSwitch = true;
                    break;
                }
            }

            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }

        icon.className =
            dir === "asc"
                ? "fa-solid fa-sort-up float-end text-primary mt-2"
                : "fa-solid fa-sort-down float-end text-primary mb-1";
    }
</script>
@endsection

