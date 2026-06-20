@extends('layouts.admin')
@section('title', 'تعديل المنتج')
@section('page-title', 'تعديل المنتج')

@section('content')
<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-box me-2"></i>معلومات المنتج</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الاسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control form-control-sm" value="{{ old('name_ar', $product->name_ar) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الاسم (فرنسي)</label>
                        <input type="text" name="name_fr" class="form-control form-control-sm" value="{{ old('name_fr', $product->name_fr) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الاسم (إنجليزي)</label>
                        <input type="text" name="name_en" class="form-control form-control-sm" value="{{ old('name_en', $product->name_en) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الوصف (عربي)</label>
                        <textarea name="description_ar" class="form-control form-control-sm" rows="3">{{ old('description_ar', $product->description_ar) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الوصف (فرنسي)</label>
                        <textarea name="description_fr" class="form-control form-control-sm" rows="2">{{ old('description_fr', $product->description_fr) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-tags me-2"></i>التسعير والمخزون</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">السعر</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required>
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">سعر التخفيض</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="sale_price" class="form-control" step="0.01" value="{{ old('sale_price', $product->sale_price) }}">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">نوع المنتج</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="digital" {{ $product->type === 'digital' ? 'selected' : '' }}>رقمي</option>
                            <option value="physical" {{ $product->type === 'physical' ? 'selected' : '' }}>مادي</option>
                            <option value="template" {{ $product->type === 'template' ? 'selected' : '' }}>قالب</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">المخزون</label>
                        <input type="number" name="stock" class="form-control form-control-sm" value="{{ old('stock', $product->stock) }}" min="0">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save me-1"></i>حفظ التعديلات
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold">صورة المنتج</h6>
                @if($product->main_image)
                <img src="{{ Storage::url($product->main_image) }}" class="img-fluid rounded mb-2" alt="">
                @endif
                <input type="file" name="main_image" class="form-control form-control-sm" accept="image/*">
                <div class="form-text">اتركه فارغًا للإبقاء على الصورة الحالية</div>
            </div>
            <div class="stat-card">
                <h6 class="fw-bold mb-3 text-gold">الحالة والإعدادات</h6>
                <div class="mb-3">
                    <label class="form-label small fw-bold">الحالة</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>مسودة</option>
                    </select>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label small" for="is_featured">مميز</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="unlimited_stock" id="unlimited_stock" value="1" {{ $product->unlimited_stock ? 'checked' : '' }}>
                    <label class="form-check-label small" for="unlimited_stock">مخزون غير محدود</label>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
