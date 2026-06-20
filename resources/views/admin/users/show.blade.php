@extends('layouts.admin')
@section('title', 'ملف المستخدم')
@section('page-title', 'ملف المستخدم')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="stat-card text-center">
            <div class="testimonial-avatar-placeholder mx-auto mb-3" style="width:80px;height:80px;font-size:2rem;">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <h5 class="fw-bold">{{ $user->name }}</h5>
            <p class="text-muted small">{{ $user->email }}</p>
            @foreach($user->roles as $role)
                <span class="badge badge-gold">{{ $role->name }}</span>
            @endforeach

            <hr class="gold-divider">

            {{-- Update Role --}}
            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="mb-3">
                @csrf @method('PATCH')
                <label class="form-label small fw-bold text-muted">تغيير الصلاحية</label>
                <select name="role" class="form-select form-select-sm mb-2">
                    @foreach(['super-admin','admin','editor','teacher','customer'] as $r)
                    <option value="{{ $r }}" {{ $user->hasRole($r) ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
                <button class="btn btn-gold btn-sm w-100">تحديث الصلاحية</button>
            </form>

            {{-- Update Status --}}
            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                @csrf @method('PATCH')
                <label class="form-label small fw-bold text-muted">تغيير الحالة</label>
                <select name="status" class="form-select form-select-sm mb-2">
                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="banned" {{ $user->status === 'banned' ? 'selected' : '' }}>محظور</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm w-100">تحديث الحالة</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3 text-gold">المعلومات الشخصية</h6>
            <div class="row">
                @foreach([
                    ['label' => 'الهاتف', 'value' => $user->phone ?? '-'],
                    ['label' => 'الدولة', 'value' => $user->country ?? '-'],
                    ['label' => 'المدينة', 'value' => $user->city ?? '-'],
                    ['label' => 'اللغة المفضلة', 'value' => $user->preferred_language],
                    ['label' => 'تاريخ التسجيل', 'value' => $user->created_at->format('Y/m/d H:i')],
                    ['label' => 'آخر تسجيل', 'value' => $user->updated_at->format('Y/m/d H:i')],
                ] as $info)
                <div class="col-md-6 mb-2">
                    <small class="text-muted d-block">{{ $info['label'] }}</small>
                    <span class="fw-bold small">{{ $info['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-gold">أحدث الطلبات ({{ $user->orders->count() }})</h6>
            @if($user->orders->count())
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-nizari">
                        <tr><th>الرقم</th><th>النوع</th><th>المبلغ</th><th>الحالة</th><th>التاريخ</th></tr>
                    </thead>
                    <tbody>
                        @foreach($user->orders->take(10) as $order)
                        <tr>
                            <td><code class="small">{{ $order->order_number }}</code></td>
                            <td><small>{{ $order->order_type }}</small></td>
                            <td>{{ number_format($order->total_price, 0) }} MAD</td>
                            <td><span class="badge bg-secondary small">{{ $order->status }}</span></td>
                            <td><small>{{ $order->created_at->format('Y/m/d') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted small">لا توجد طلبات</p>
            @endif
        </div>
    </div>
</div>
@endsection
