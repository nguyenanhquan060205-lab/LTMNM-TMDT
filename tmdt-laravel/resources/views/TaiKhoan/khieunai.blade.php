@extends('layouts.app')

@section('title', 'Khiếu nại về sản phẩm')

@section('content')
<div class="container my-5">

    <h3 class="fw-bold mb-4 text-center">
        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
        Danh sách khiếu nại liên quan đến sản phẩm của bạn
    </h3>

    <!-- SEARCH -->
    <div class="row mb-3">
        <div class="col-md-6 mx-auto">
            <input type="text"
                   id="searchInput"
                   class="form-control rounded-pill shadow-sm"
                   placeholder="🔍 Tìm theo người gửi, sản phẩm hoặc nội dung khiếu nại..." />
        </div>
    </div>

    @if ($dsKhieuNai->isEmpty())
        <div class="alert alert-success text-center shadow-sm">
            🎉 Hiện tại chưa có khiếu nại nào về sản phẩm của bạn!
        </div>
    @else
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0" id="khieuNaiTable">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 20%">Người gửi</th>
                            <th style="width: 20%">Sản phẩm</th>
                            <th style="width: 15%">Ngày gửi</th>
                            <th style="width: 30%">Mô tả</th>
                            <th style="width: 15%">Trạng thái</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($dsKhieuNai as $kn)
                            <tr>
                                <td class="fw-semibold text-center">
                                    {{ $kn->nguoiDung->HoTen ?? 'Unknown' }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $kn->sanPham->TenSP ?? 'Unknown' }}
                                </td>

                                <td class="text-center">
                                    {{ $kn->NgayGui ? \Carbon\Carbon::parse($kn->NgayGui)->format('dd/MM/yyyy HH:mm') : '-' }}
                                </td>

                                <td>
                                    {{ $kn->MoTa }}
                                </td>

                                <td class="text-center">
                                    @if ($kn->TrangThai == "Chưa xử lý")
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            Chưa xử lý
                                        </span>
                                    @else
                                        <span class="badge bg-success px-3 py-2">
                                            Đã giải quyết
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<!-- SEARCH SCRIPT -->
<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll("#khieuNaiTable tbody tr");

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
</script>
@endsection

