@extends('layouts.admin')
@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">قائمة الطلبات</h6>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">جميع الحالات</option>
                @foreach(['new', 'confirmed', 'in_progress', 'ready', 'shipped', 'delivered', 'cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ __('admin.'.$s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="payment_status" class="form-select form-select-sm">
                <option value="">حالة الدفع</option>
                @foreach(['pending','paid','refunded','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('payment_status') === $s ? 'selected' : '' }}>{{ __('admin.'.$s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="بحث بالرقم أو الاسم أو البريد..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-gold btn-sm w-100">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>طريقة الدفع</th>
                    <th>الدفع</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><code class="small">{{ $order->order_number }}</code></td>
                    <td>
                        <div class="fw-bold small">{{ $order->customer_name }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->customer_email }}</div>
                    </td>
                    <td><span class="small">{{ $order->order_type }}</span></td>
                    <td class="fw-bold text-gold">{{ number_format($order->total_price, 0) }} MAD</td>
                    <td><span class="small text-muted">{{ $order->payment_method ?? '-' }}</span></td>
                    <td>
                        @php $pc = ['pending'=>'warning','paid'=>'success','partial'=>'info','refunded'=>'secondary','cancelled'=>'danger']; @endphp
                        <span class="badge bg-{{ $pc[$order->payment_status] ?? 'secondary' }} small">
                            {{ __('admin.'.$order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        @php $sc = ['new'=>'primary','confirmed'=>'info','in_progress'=>'warning','ready'=>'success','shipped'=>'secondary','delivered'=>'success','cancelled'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$order->status] ?? 'secondary' }} small">
                            {{ __('admin.'.$order->status) }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $order->created_at->format('Y/m/d') }}</small></td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">لا توجد طلبات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection
