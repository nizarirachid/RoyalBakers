@extends('layouts.admin')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    @php
    $cards = [
        ['icon' => 'fa-shopping-bag', 'color' => '#1B4332', 'bg' => 'rgba(27,67,50,0.1)', 'num' => $stats['total_orders'], 'lbl' => 'إجمالي الطلبات', 'link' => route('admin.orders.index')],
        ['icon' => 'fa-clock', 'color' => '#F59E0B', 'bg' => 'rgba(245,158,11,0.1)', 'num' => $stats['pending_orders'], 'lbl' => 'طلبات جديدة', 'link' => route('admin.orders.index', ['status' => 'new'])],
        ['icon' => 'fa-users', 'color' => '#3B82F6', 'bg' => 'rgba(59,130,246,0.1)', 'num' => $stats['total_users'], 'lbl' => 'المستخدمون', 'link' => route('admin.users.index')],
        ['icon' => 'fa-paint-brush', 'color' => '#8B5CF6', 'bg' => 'rgba(139,92,246,0.1)', 'num' => $stats['total_artworks'], 'lbl' => 'الأعمال الفنية', 'link' => route('admin.artworks.index')],
        ['icon' => 'fa-money-bill-wave', 'color' => '#10B981', 'bg' => 'rgba(16,185,129,0.1)', 'num' => number_format($stats['total_revenue'], 0) . ' MAD', 'lbl' => 'إجمالي الإيرادات', 'link' => '#'],
        ['icon' => 'fa-envelope', 'color' => '#EF4444', 'bg' => 'rgba(239,68,68,0.1)', 'num' => $stats['new_messages'], 'lbl' => 'رسائل جديدة', 'link' => route('admin.messages.index')],
        ['icon' => 'fa-graduation-cap', 'color' => '#C9A84C', 'bg' => 'rgba(201,168,76,0.1)', 'num' => $stats['active_courses'], 'lbl' => 'الدورات النشطة', 'link' => route('admin.courses.index')],
        ['icon' => 'fa-store', 'color' => '#06B6D4', 'bg' => 'rgba(6,182,212,0.1)', 'num' => $stats['total_products'], 'lbl' => 'المنتجات', 'link' => route('admin.products.index')],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="col-xl-3 col-md-4 col-sm-6">
        <a href="{{ $card['link'] }}" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon" style="background: {{ $card['bg'] }}; color: {{ $card['color'] }};">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>
                <div class="stat-num">{{ $card['num'] }}</div>
                <div class="stat-lbl">{{ $card['lbl'] }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- Recent Orders & Messages --}}
<div class="row g-4">
    <div class="col-lg-7">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--green-dark);">
                    <i class="fas fa-shopping-bag text-gold me-2"></i>أحدث الطلبات
                </h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-gold btn-sm">عرض الكل</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-nizari">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>النوع</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><code>{{ $order->order_number }}</code></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->order_type }}</td>
                            <td>{{ number_format($order->total_price, 0) }} MAD</td>
                            <td>
                                @php
                                $statusColors = [
                                    'new' => 'primary', 'confirmed' => 'info',
                                    'in_progress' => 'warning', 'ready' => 'success',
                                    'shipped' => 'secondary', 'delivered' => 'success',
                                    'cancelled' => 'danger', 'refunded' => 'dark',
                                ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ __('admin.' . $order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--green-dark);">
                    <i class="fas fa-envelope text-gold me-2"></i>آخر الرسائل
                </h6>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-gold btn-sm">عرض الكل</a>
            </div>

            @foreach($recentMessages as $msg)
            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                <div class="testimonial-avatar-placeholder flex-shrink-0" style="width:40px;height:40px;font-size:1rem;">
                    {{ mb_substr($msg->name, 0, 1) }}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:0.85rem;">{{ $msg->name }}</strong>
                        <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="text-muted mb-0" style="font-size:0.8rem;">{{ Str::limit($msg->message, 60) }}</p>
                    @if($msg->status === 'new')
                        <span class="badge bg-danger" style="font-size:0.65rem;">جديد</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Quick Actions --}}
        <div class="stat-card mt-4">
            <h6 class="fw-bold mb-3" style="color: var(--green-dark);">
                <i class="fas fa-bolt text-gold me-2"></i>إجراءات سريعة
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.artworks.create') }}" class="btn btn-outline-success btn-sm text-start">
                    <i class="fas fa-plus me-2"></i>إضافة عمل فني
                </a>
                <a href="{{ route('admin.blog.create') }}" class="btn btn-outline-primary btn-sm text-start">
                    <i class="fas fa-pen me-2"></i>كتابة مقال
                </a>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-warning btn-sm text-start">
                    <i class="fas fa-graduation-cap me-2"></i>إنشاء دورة
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-info btn-sm text-start">
                    <i class="fas fa-plus me-2"></i>إضافة منتج
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
