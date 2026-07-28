@extends('shared._layout')
@section('title', 'Tin đăng của tôi')

@section('content')
<div class="container my-5">
    <style>
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .table th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: .85rem;
            padding: 18px 16px;
            border: none;
        }
        .table td {
            padding: 18px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
            color: #334155;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #edf2f7;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .badge {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
            white-space: nowrap;
            display: inline-block;
        }
        .badge-success-custom { background-color: #0d6efd; color: white; }
        .badge-pending-custom { background-color: white; color: #1e293b; border: 1px solid #1e293b; }
        .badge-danger-custom { background-color: #1e293b; color: white; }
    </style>

    <h3 class="fw-bold mb-4 text-center" style="color: #2a2a40; margin-top: 20px;">
        <i class="fa-solid fa-clipboard-list text-primary me-2"></i> Tin đăng của tôi
    </h3>

    <!-- SEARCH -->
    <div class="row mb-3">
        <div class="col-md-6 mx-auto">
            <input type="text"
                   id="searchInput"
                   class="form-control rounded-pill shadow-sm"
                   placeholder="🔍 Tìm theo tên sản phẩm, trạng thái, giá..." />
        </div>
    </div>

    <div class="table-container mb-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" id="sanPhamTable">
                <thead class="text-center">
                    <tr>
                        <th style="width: 25%">Tên sản phẩm</th>
                        <th style="width: 15%">Ngày tạo</th>
                        <th style="width: 15%">Trạng thái</th>
                        <th style="width: 10%">Số lượng</th>
                        <th style="width: 15%">Giá (VNĐ)</th>
                        <th style="width: 20%">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($list as $sp)
                        @php
                            $anhObj = collect($sp->hinhAnhs ?? $sp->hinhAnhSPs)->firstWhere('AnhBia', true);
                            if (!$anhObj) {
                                $anhObj = collect($sp->hinhAnhs ?? $sp->hinhAnhSPs)->first();
                            }
                            $anh = $anhObj ? $anhObj->URLAnh : ($sp->AnhBia ?? "noimage.jpg");
                            $anhUrl = str_starts_with($anh, 'http') ? $anh : url('/Content/Images/' . $anh);
                        @endphp
                        <tr>
                            <td class="text-start ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $anhUrl }}" alt="{{ $sp->TenSP }}" class="product-img">
                                    <span class="fw-bold text-dark">{{ $sp->TenSP }}</span>
                                </div>
                            </td>
                            <td class="text-muted">
                                {{ $sp->NgayTao ? \Carbon\Carbon::parse($sp->NgayTao)->format('d/m/Y') : '' }}
                            </td>
                            <td>
                                @if ($sp->TrangThai == 'Đã duyệt')
                                    <span class="badge badge-success-custom px-3 py-2">Đang hiển thị</span>
                                @elseif ($sp->TrangThai == 'Ẩn')
                                    <span class="badge badge-danger-custom px-3 py-2">Đã ẩn / Khóa</span>
                                @else
                                    <span class="badge badge-pending-custom px-3 py-2">{{ $sp->TrangThai }}</span>
                                @endif
                            </td>
                            <td>{{ $sp->SoLuong }}</td>
                            <td class="fw-bold fs-6" style="color: #0d6efd;">{{ number_format($sp->Gia, 0, ',', '.') }} đ</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ url('/sanpham/chitiet/' . $sp->MaSP) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold shadow-sm" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye me-1"></i> Xem
                                    </a>
                                    <a href="{{ url('/sanpham/sua/' . $sp->MaSP) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen"></i> Sửa
                                    </a>
                                    <div class="vr mx-1 text-muted"></div>
                                    <a href="{{ url('/sanpham/xoa/' . $sp->MaSP) }}" class="btn btn-sm text-danger fw-bold"
                                       onclick="return confirm('Xác nhận xóa sản phẩm này?')">
                                        Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll("#sanPhamTable tbody tr");

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
</script>
@endsection
