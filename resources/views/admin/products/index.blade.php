@extends('layouts.admin')
@section('title', 'المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">قائمة المنتجات</h6>
        <a href="{{ route('admin.products.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus me-1"></i>منتج جديد
        </a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="type" class="form-select form-select-sm">
                <option value="">جميع الأنواع</option>
                <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>رقمي</option>
                <option value="physical" {{ request('type') === 'physical' ? 'selected' : '' }}>مادي</option>
                <option value="template" {{ request('type') === 'template' ? 'selected' : '' }}>قالب</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="بحث بالاسم..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-gold btn-sm w-100">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr>
                    <th>المنتج</th>
                    <th>النوع</th>
                    <th>السعر</th>
                    <th>المخزون</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($product->main_image)
                            <img src="{{ Storage::url($product->main_image) }}" alt="" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="fas fa-box text-muted small"></i>
                            </div>
                            @endif
                            <span class="small fw-bold">{{ Str::limit($product->name_ar, 35) }}</span>
                        </div>
                    </td>
                    <td><span class="badge bg-info small">{{ $product->type }}</span></td>
                    <td>
                        @if($product->sale_price)
                        <small class="text-decoration-line-through text-muted">{{ $product->price }} MAD</small>
                        <small class="text-danger fw-bold"> {{ $product->sale_price }} MAD</small>
                        @else
                        <small>{{ $product->price }} MAD</small>
                        @endif
                    </td>
                    <td>
                        @if($product->type === 'digital' || $product->unlimited_stock)
                        <small class="text-muted">-</small>
                        @else
                        <small class="{{ $product->stock < 5 ? 'text-danger fw-bold' : '' }}">{{ $product->stock }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} small">
                            {{ $product->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" data-confirm-delete="حذف هذا المنتج؟">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">لا توجد منتجات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
