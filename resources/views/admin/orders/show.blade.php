@extends('layouts.admin')
@section('title', 'تفاصيل الطلب ' . $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold" style="color: var(--green-dark);">
                    <i class="fas fa-shopping-bag text-gold me-2"></i>
                    الطلب: <code>{{ $order->order_number }}</code>
                </h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i>العودة
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="text-gold border-bottom border-gold pb-2">معلومات العميل</h6>
                    <table class="table table-sm table-borderless">
                        <tr><th class="text-muted small" style="width:120px;">الاسم</th><td>{{ $order->customer_name }}</td></tr>
                        <tr><th class="text-muted small">البريد</th><td>{{ $order->customer_email }}</td></tr>
                        <tr><th class="text-muted small">الهاتف</th><td>{{ $order->customer_phone ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">الدولة</th><td>{{ $order->customer_country ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">المدينة</th><td>{{ $order->customer_city ?? '-' }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-gold border-bottom border-gold pb-2">تفاصيل الطلب</h6>
                    <table class="table table-sm table-borderless">
                        <tr><th class="text-muted small" style="width:120px;">النوع</th><td>{{ $order->order_type }}</td></tr>
                        <tr><th class="text-muted small">الأسلوب</th><td>{{ $order->calligraphy_style ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">المادة</th><td>{{ $order->material ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">المقاسات</th><td>{{ $order->width_cm ? $order->width_cm.'×'.$order->height_cm.' سم' : '-' }}</td></tr>
                        <tr><th class="text-muted small">التسليم</th><td>{{ $order->delivery_method }}</td></tr>
                    </table>
                </div>

                @if($order->text_to_write)
                <div class="col-12">
                    <div class="p-3 bg-beige rounded">
                        <label class="form-label text-gold fw-bold">النص المطلوب كتابته</label>
                        <p style="font-family: var(--font-arabic); font-size: 1.2rem;">{{ $order->text_to_write }}</p>
                    </div>
                </div>
                @endif

                @if($order->notes)
                <div class="col-12">
                    <label class="form-label text-muted fw-bold">ملاحظات العميل</label>
                    <p>{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Update Status --}}
            <hr class="gold-divider">
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">تحديث الحالة</label>
                        <select name="status" class="form-select form-select-sm">
                            @foreach(['new','confirmed','in_progress','ready','shipped','delivered','cancelled','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ __('admin.'.$s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ملاحظة</label>
                        <input type="text" name="note" class="form-control form-control-sm" placeholder="ملاحظة (اختياري)">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-gold btn-sm w-100">تحديث</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Status History --}}
        @if($order->statusHistory->count())
        <div class="stat-card mt-4">
            <h6 class="fw-bold mb-3 text-gold">سجل الحالات</h6>
            @foreach($order->statusHistory->sortByDesc('created_at') as $h)
            <div class="d-flex gap-3 mb-2 pb-2 border-bottom">
                <div class="text-gold"><i class="fas fa-circle" style="font-size:0.5rem; margin-top:6px;"></i></div>
                <div>
                    <span class="fw-bold small">{{ __('admin.'.$h->status) }}</span>
                    @if($h->note)<span class="text-muted small"> — {{ $h->note }}</span>@endif
                    <br>
                    <small class="text-muted">{{ $h->created_at->format('Y/m/d H:i') }}
                        @if($h->changedBy) · {{ $h->changedBy->name }} @endif
                    </small>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Pricing --}}
        <div class="price-calculator" style="border-radius: 12px;">
            <h5 class="mb-3"><i class="fas fa-calculator me-2"></i>الأسعار</h5>

            <form method="POST" action="{{ route('admin.orders.pricing', $order) }}">
                @csrf @method('PATCH')
                @foreach([
                    ['name' => 'artwork_price', 'label' => 'ثمن العمل الفني'],
                    ['name' => 'material_price', 'label' => 'ثمن المادة'],
                    ['name' => 'labor_price', 'label' => 'ثمن العمل'],
                    ['name' => 'digital_copy_price', 'label' => 'النسخة الرقمية'],
                    ['name' => 'shipping_price', 'label' => 'ثمن الشحن'],
                    ['name' => 'discount', 'label' => 'خصم'],
                ] as $field)
                <div class="price-row align-items-center" style="gap: 0.5rem;">
                    <label class="small mb-0">{{ $field['label'] }}</label>
                    <input type="number" name="{{ $field['name'] }}" step="0.01" min="0"
                        value="{{ $order->{$field['name']} }}"
                        class="form-control form-control-sm" style="max-width: 100px; background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: white;">
                </div>
                @endforeach

                <div class="price-total mt-2">
                    <span>الإجمالي</span>
                    <span>{{ number_format($order->total_price, 2) }} {{ $order->currency }}</span>
                </div>

                <button type="submit" class="btn btn-gold w-100 mt-3 btn-sm">
                    حفظ الأسعار
                </button>
            </form>
        </div>

        {{-- Payment Info --}}
        <div class="stat-card mt-4">
            <h6 class="fw-bold mb-3 text-gold">معلومات الدفع</h6>
            <table class="table table-sm table-borderless">
                <tr>
                    <th class="text-muted small">طريقة الدفع</th>
                    <td>{{ $order->payment_method ?? 'غير محدد' }}</td>
                </tr>
                <tr>
                    <th class="text-muted small">حالة الدفع</th>
                    <td>
                        @php $pc = ['pending'=>'warning','paid'=>'success','partial'=>'info','refunded'=>'secondary','cancelled'=>'danger']; @endphp
                        <span class="badge bg-{{ $pc[$order->payment_status] ?? 'secondary' }}">
                            {{ __('admin.'.$order->payment_status) }}
                        </span>
                    </td>
                </tr>
                @if($order->payment_reference)
                <tr>
                    <th class="text-muted small">مرجع الدفع</th>
                    <td><code class="small">{{ $order->payment_reference }}</code></td>
                </tr>
                @endif
            </table>

            <div class="d-grid gap-2 mt-2">
                <a href="mailto:{{ $order->customer_email }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-envelope me-1"></i>راسل العميل
                </a>
                @if($order->customer_phone)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer_phone) }}" target="_blank" class="btn btn-outline-success btn-sm">
                    <i class="fab fa-whatsapp me-1"></i>واتساب
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
