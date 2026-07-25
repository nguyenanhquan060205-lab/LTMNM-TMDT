@extends('layouts.admin')

@section('title', 'Duyệt tin đăng')

@section('content')
<div class="container my-4">
    <h4 class="fw-bold text-primary mb-4">
        <i class="bi bi-check2-circle"></i> Duyệt tin đăng
    </h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover text-center align-middle shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>Mã</th>
                <th>Tên sản phẩm</th>
                <th>Người đăng</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($dsSanPham))
                @foreach ($dsSanPham as $sp)
                    <tr>
                        <td>{{ $sp->MaSP }}</td>
                        <td>{{ $sp->TenSP }}</td>
                        <td>{{ $sp->nguoiDung->HoTen ?? '' }}</td>
                        <td>{{ $sp->NgayTao ? \Carbon\Carbon::parse($sp->NgayTao)->format('d/m/Y') : '' }}</td>
                        <td>
                            <span class="badge {{ $sp->TrangThai == 'Đã duyệt' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $sp->TrangThai }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.doitrangthai') }}" method="post" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $sp->MaSP }}" />
                                @if ($sp->TrangThai == "Chờ duyệt")
                                    <button type="submit" name="tt" value="Đã duyệt" class="btn btn-success btn-sm">Duyệt</button>
                                @else
                                    <button type="submit" name="tt" value="Chờ duyệt" class="btn btn-warning btn-sm">Thu hồi</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection

