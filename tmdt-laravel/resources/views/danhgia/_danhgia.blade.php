

@foreach ($Model as $dg)
    <div class="border-bottom mb-3">
        <strong>{{ $dg->nguoiDung->HoTen ?? 'Người dùng' }}</strong>
        <span class="text-warning">{{ str_repeat('⭐', $dg->SoSao) }}</span>
        <p>{{ $dg->NoiDung }}</p>
        <small>{{ $dg->NgayDG ? \Carbon\Carbon::parse($dg->NgayDG)->format('d/m/Y H:i') : '' }}</small>
    </div>
@endforeach
