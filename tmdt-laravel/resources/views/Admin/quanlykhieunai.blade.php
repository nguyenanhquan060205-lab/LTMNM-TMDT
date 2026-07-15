@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-exclamation-triangle me-2 text-danger"></i>Quản Lý Khiếu Nại</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã KN</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($khieuNais as $kn)
                        <tr>
                            <td class="fw-bold">#{{ $kn->MaKN }}</td>
                            <td>{{ $kn->nguoiDung->TaiKhoan ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('sanpham.chitiet', ['id' => $kn->MaSP]) }}" class="text-primary text-decoration-none">SP #{{ $kn->MaSP }}</a>
                            </td>
                            <td><div class="text-truncate" style="max-width: 250px;">{{ $kn->MoTa }}</div></td>
                            <td>
                                @if ($kn->TrangThai == 'Chưa xử lý')
                                    <span class="badge bg-warning text-dark">Chưa xử lý</span>
                                @else
                                    <span class="badge bg-success">Đã xử lý</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.capnhattrangthaikn', ['id' => $kn->MaKN]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success rounded-pill px-2 py-1" title="Đã giải quyết" {{ $kn->TrangThai == 'Đã xử lý' ? 'disabled' : '' }}><i class="fa fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.xoakhieunai', ['id' => $kn->MaKN]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger rounded-pill px-2 py-1" title="Xóa" onclick="return confirm('Xóa khiếu nại này?');"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $khieuNais->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection