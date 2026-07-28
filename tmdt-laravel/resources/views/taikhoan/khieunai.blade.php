@extends('shared._layout')
@section('title', 'Quản lý khiếu nại')

@section('content')
<div class="container my-5">



    <h3 class="fw-bold mb-4 text-center">
        <i class="fa-solid fa-triangle-exclamation text-primary me-2"></i>
        Quản lý khiếu nại
    </h3>

    <!-- SEARCH -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <input type="text"
                   id="searchInput"
                   class="form-control rounded-pill shadow-sm"
                   placeholder="Tìm theo người gửi, sản phẩm hoặc nội dung khiếu nại..." />
        </div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-tabs justify-content-center mb-4" id="khieuNaiTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="ve-toi-tab" data-bs-toggle="tab" data-bs-target="#ve-toi" type="button" role="tab" aria-controls="ve-toi" aria-selected="true">
                Khiếu nại về sản phẩm của bạn
            </button>
        </li>
        <li class="nav-item ms-2" role="presentation">
            <button class="nav-link fw-bold" id="toi-gui-tab" data-bs-toggle="tab" data-bs-target="#toi-gui" type="button" role="tab" aria-controls="toi-gui" aria-selected="false">
                Khiếu nại bạn đã gửi
            </button>
        </li>
    </ul>

    <div class="tab-content" id="khieuNaiTabsContent">
        <!-- TAB: VỀ SẢN PHẨM CỦA BẠN -->
        <div class="tab-pane fade show active" id="ve-toi" role="tabpanel" aria-labelledby="ve-toi-tab">
            @if (!isset($dsKhieuNai) || collect($dsKhieuNai)->isEmpty())
                <div class="alert alert-success text-center shadow-sm rounded-4">
                    Hiện tại chưa có khiếu nại nào về sản phẩm của bạn.
                </div>
            @else
                <div class="card shadow-sm mb-4">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered align-middle mb-0 khieuNaiTable">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th style="width: 20%">Người gửi</th>
                                    <th style="width: 20%">Sản phẩm</th>
                                    <th style="width: 15%">Ngày gửi</th>
                                    <th style="width: 25%">Mô tả</th>
                                    <th style="width: 10%">Trạng thái</th>
                                    <th style="width: 10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dsKhieuNai as $kn)
                                    <tr>
                                        <td class="fw-semibold text-center">{{ $kn->nguoiDung->HoTen ?? '' }}</td>
                                        <td class="fw-semibold">{{ $kn->sanPham->TenSP ?? '' }}</td>
                                        <td class="text-center">{{ $kn->NgayGui ? \Carbon\Carbon::parse($kn->NgayGui)->format('d/m/Y HH:mm') : '' }}</td>
                                        <td>{{ $kn->MoTa }}</td>
                                        <td class="text-center">
                                            @if ($kn->TrangThai == 'Chưa xử lý')
                                                <span class="badge bg-warning text-dark">Chưa xử lý</span>
                                            @else
                                                <span class="badge bg-success">Đã giải quyết</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ url('/tinnhan/chat?idNguoiNhan=' . $kn->MaKH) }}" class="btn btn-sm btn-dark text-nowrap">
                                                    <i class="fa-solid fa-comment-dots"></i> Nhắn người gửi
                                                </a>
                                                @if(isset($adminId))
                                                <a href="{{ url('/tinnhan/chat?idNguoiNhan=' . $adminId) }}" class="btn btn-sm btn-outline-dark text-nowrap">
                                                    <i class="fa-solid fa-headset"></i> Nhắn Admin
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- TAB: BẠN ĐÃ GỬI -->
        <div class="tab-pane fade" id="toi-gui" role="tabpanel" aria-labelledby="toi-gui-tab">
            @if (!isset($dsKhieuNaiCuaToi) || collect($dsKhieuNaiCuaToi)->isEmpty())
                <div class="alert alert-info text-center shadow-sm rounded-4">
                    Bạn chưa gửi khiếu nại nào.
                </div>
            @else
                <div class="card shadow-sm mb-4">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered align-middle mb-0 khieuNaiTable">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th style="width: 20%">Người bị khiếu nại</th>
                                    <th style="width: 20%">Sản phẩm</th>
                                    <th style="width: 15%">Ngày gửi</th>
                                    <th style="width: 25%">Mô tả</th>
                                    <th style="width: 10%">Trạng thái</th>
                                    <th style="width: 10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dsKhieuNaiCuaToi as $kn)
                                    <tr>
                                        <td class="fw-semibold text-center">{{ $kn->sanPham->nguoiDung->HoTen ?? 'Người bán' }}</td>
                                        <td class="fw-semibold">{{ $kn->sanPham->TenSP ?? '' }}</td>
                                        <td class="text-center">{{ $kn->NgayGui ? \Carbon\Carbon::parse($kn->NgayGui)->format('d/m/Y HH:mm') : '' }}</td>
                                        <td>{{ $kn->MoTa }}</td>
                                        <td class="text-center">
                                            @if ($kn->TrangThai == 'Chưa xử lý')
                                                <span class="badge bg-warning text-dark">Chưa xử lý</span>
                                            @else
                                                <span class="badge bg-success">Đã giải quyết</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ url('/tinnhan/chat?idNguoiNhan=' . ($kn->sanPham->MaKH ?? '')) }}" class="btn btn-sm btn-dark text-nowrap">
                                                    <i class="fa-solid fa-store"></i> Nhắn người bán
                                                </a>
                                                @if(isset($adminId))
                                                <a href="{{ url('/tinnhan/chat?idNguoiNhan=' . $adminId) }}" class="btn btn-sm btn-outline-dark text-nowrap">
                                                    <i class="fa-solid fa-headset"></i> Nhắn Admin
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- SEARCH SCRIPT -->
<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const tables = document.querySelectorAll(".khieuNaiTable");
        
        tables.forEach(table => {
            const rows = table.querySelectorAll("tbody tr");
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        });
    });
</script>
@endsection
