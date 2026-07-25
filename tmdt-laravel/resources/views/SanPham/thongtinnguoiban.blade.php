@extends('Shared._Layout')
@section('title', 'Thông tin người bán')

@section('content')
<div class="container mt-5">
    <div class="row">

        <!-- ====================== -->
        <!-- THÔNG TIN NGƯỜI BÁN -->
        <!-- ====================== -->
        <div class="col-md-4 text-center mb-4">
            <img src="{{ url('Content/Avatars/' . ($nguoiBan->AnhDaiDien ?? 'default.jpg')) }}"
                 class="img-fluid rounded-circle mb-3"
                 style="width:150px;height:150px;object-fit:cover;" />

            <h4 class="fw-bold">{{ $nguoiBan->HoTen }}</h4>
            <p class="text-muted mb-1">{{ $nguoiBan->Email }}</p>
            <p class="text-muted mb-1">{{ $nguoiBan->SDT }}</p>
            <p class="text-muted">{{ $nguoiBan->DiaChi }}</p>

            <a href="javascript:void(0);"
               class="btn btn-warning fw-bold px-4 btn-chuyen-khoan"
               data-id="{{ $nguoiBan->MaKH }}">
                <i class="fa-solid fa-credit-card"></i> Thông tin chuyển khoản
            </a>
        </div>

        <!-- ====================== -->
        <!-- DANH SÁCH SẢN PHẨM NGƯỜI BÁN -->
        <!-- ====================== -->
        <div class="col-md-8">
            <h4 class="fw-bold mb-4">Sản phẩm của {{ $nguoiBan->HoTen }}</h4>

            @if ($sanPhams && count($sanPhams) > 0)
                <div class="row g-4 justify-content-center">
                    @foreach ($sanPhams as $sp)
                        @php
                            $anhBia = $sp->hinhAnhSPs->firstWhere('AnhBia', true)?->URLAnh ?? 'no-image.jpg';
                        @endphp
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <a href="{{ url('/sanpham/chitiet/' . $sp->MaSP) }}">
                                    <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                                        <img src="{{ url('Content/Images/' . $anhBia) }}"
                                             class="card-img-top p-3"
                                             style="object-fit: contain; width:100%; height:100%;" />
                                    </div>
                                </a>

                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <h6 class="fw-semibold text-truncate">{{ $sp->TenSP }}</h6>
                                        <p class="text-danger fw-bold mb-1">{{ number_format($sp->Gia, 0, ',', '.') }} ₫</p>
                                        <p class="small text-muted">{{ $sp->loaiSanPham->TenLoai ?? '' }}</p>
                                    </div>

                                    <a href="{{ url('/sanpham/chitiet/' . $sp->MaSP) }}"
                                       class="btn btn-warning w-100 fw-semibold mt-2 rounded-pill shadow-sm">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted">Người bán chưa có sản phẩm nào.</p>
            @endif
        </div>
    </div>
</div>

<!-- ======================= -->
<!-- MODAL THÔNG TIN CHUYỂN KHOẢN -->
<!-- ======================= -->
<div class="modal fade" id="modalChuyenKhoan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin chuyển khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="chuyenKhoanBody">
                <!-- Nội dung sẽ load bằng Ajax -->
            </div>
        </div>
    </div>
</div>

<!-- ======================= -->
<!-- SCRIPT AJAX + MODAL BOOTSTRAP 5 -->
<!-- ======================= -->
<script>
    $(document).ready(function() {
        const bankStyles = {
            'MB Bank': { color: '#0032A0', logo: 'mbbank.png', gradient: 'linear-gradient(135deg, #0032A0 0%, #0052CC 100%)' },
            'Vietcombank': { color: '#007C3F', logo: 'vietcombank.png', gradient: 'linear-gradient(135deg, #007C3F 0%, #00A854 100%)' },
            'Techcombank': { color: '#E31E24', logo: 'techcombank.png', gradient: 'linear-gradient(135deg, #E31E24 0%, #FF4444 100%)' },
            'ACB': { color: '#005BAA', logo: 'acb.png', gradient: 'linear-gradient(135deg, #005BAA 0%, #0077CC 100%)' },
            'VietinBank': { color: '#ED1C24', logo: 'viettinbank.png', gradient: 'linear-gradient(135deg, #ED1C24 0%, #FF4444 100%)' },
            'Agribank': { color: '#006838', logo: 'agribank.png', gradient: 'linear-gradient(135deg, #006838 0%, #008A4A 100%)' },
            'BIDV': { color: '#005BAA', logo: 'bidv.png', gradient: 'linear-gradient(135deg, #005BAA 0%, #0077CC 100%)' },
            'VPBank': { color: '#1BA05B', logo: 'vpbank.png', gradient: 'linear-gradient(135deg, #1BA05B 0%, #26D07C 100%)' },
            'TPBank': { color: '#8B3FFD', logo: 'tpbank.png', gradient: 'linear-gradient(135deg, #8B3FFD 0%, #A366FF 100%)' },
            'Sacombank': { color: '#004B9D', logo: 'sacombank.png', gradient: 'linear-gradient(135deg, #004B9D 0%, #0066CC 100%)' }
        };

        $(".btn-chuyen-khoan").click(function() {
            var idNguoiBan = $(this).data("id");

            $.ajax({
                url: '{{ url("/TaiKhoan/ThongTinChuyenKhoan") }}',
                type: 'GET',
                data: { idNguoiBan: idNguoiBan },
                success: function(data) {
                    if (data && data.SoTaiKhoan) {
                        var bankInfo = bankStyles[data.TenNganHang] || {
                            color: '#666',
                            logo: 'default-bank.png',
                            gradient: 'linear-gradient(135deg, #666 0%, #888 100%)'
                        };

                        var html = `
                            <style>
                                .bank-card { background: ${bankInfo.gradient}; border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin-bottom: 20px; }
                                .bank-logo { width: 80px; height: 80px; background: white; border-radius: 15px; padding: 10px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; }
                                .bank-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
                                .account-info { background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 12px; padding: 15px; margin-top: 15px; }
                                .info-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
                                .info-row:last-child { margin-bottom: 0; }
                                .info-label { font-size: 13px; opacity: 0.9; font-weight: 500; }
                                .info-value { font-size: 16px; font-weight: bold; text-align: right; }
                                .account-number { font-size: 24px !important; letter-spacing: 2px; font-family: 'Courier New', monospace; }
                                .copy-btn { background: white; color: ${bankInfo.color}; border: none; padding: 12px 25px; border-radius: 25px; font-weight: bold; width: 100%; margin-top: 15px; cursor: pointer; transition: all 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
                                .copy-btn:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(0,0,0,0.3); }
                                .qr-section { background: white; border-radius: 15px; padding: 20px; text-align: center; margin-top: 20px; }
                                .qr-title { color: #333; font-size: 14px; margin-bottom: 15px; font-weight: 600; }
                            </style>

                            <div class="bank-card">
                                <div class="bank-logo">
                                    <img src="/Content/BankLogos/${bankInfo.logo}" alt="${data.TenNganHang}" onerror="this.src='/Content/BankLogos/default-bank.png'">
                                </div>
                                <h5 class="mb-3" style="font-weight: 600; font-size: 18px;">${data.TenNganHang}</h5>

                                <div class="account-info">
                                    <div class="info-row">
                                        <span class="info-label">Chủ tài khoản</span>
                                        <span class="info-value">${data.HoTen.toUpperCase()}</span>
                                    </div>
                                    <div class="info-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.2);">
                                        <span class="info-label">Số tài khoản</span>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="info-value account-number">${data.SoTaiKhoan}</span>
                                    </div>
                                </div>

                                <button class="copy-btn" onclick="copyAccountNumber(event, '${data.SoTaiKhoan}')">
                                    <i class="fa-solid fa-copy"></i> Sao chép số tài khoản
                                </button>
                            </div>

                            <div class="qr-section">
                                <div class="qr-title">
                                    <i class="fa-solid fa-qrcode"></i> Quét mã QR để chuyển khoản
                                </div>
                                <div id="qrcode-container" style="display: flex; justify-content: center;"></div>
                                <p class="text-muted small mt-2 mb-0">Sử dụng app ngân hàng để quét mã</p>
                            </div>
                        `;

                        $("#chuyenKhoanBody").html(html);

                        if (typeof QRCode !== 'undefined') {
                            new QRCode(document.getElementById("qrcode-container"), {
                                text: `${data.TenNganHang}|${data.SoTaiKhoan}|${data.HoTen}`,
                                width: 200,
                                height: 200
                            });
                        }

                        var myModal = new bootstrap.Modal(document.getElementById('modalChuyenKhoan'));
                        myModal.show();
                    } else {
                        alert("Người bán chưa cập nhật thông tin chuyển khoản");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("Không thể tải thông tin chuyển khoản");
                }
            });
        });
    });

    function copyAccountNumber(event, accountNumber) {
        navigator.clipboard.writeText(accountNumber).then(function () {
            const btn = event.target.closest('.copy-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Đã sao chép!';
            btn.style.background = '#4CAF50';
            btn.style.color = 'white';

            setTimeout(function () {
                btn.innerHTML = originalText;
                btn.style.background = 'white';
                btn.style.color = '';
            }, 2000);
        }).catch(function (err) {
            alert("Không thể sao chép. Vui lòng thử lại!");
        });
    }
</script>
@endsection
