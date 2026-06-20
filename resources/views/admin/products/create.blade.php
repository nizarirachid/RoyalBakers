@extends('layouts.admin')
@section('title', 'منتج جديد')
@section('page-title', 'إضافة منتج جديد')

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-box me-2"></i>معلومات المنتج</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الاسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control form-control-sm" value="{{ old('name_ar') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الاسم (فرنسي)</label>
                        <input type="text" name="name_fr" class="form-control form-control-sm" value="{{ old('name_fr') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الاسم (إنجليزي)</label>
                        <input type="text" name="name_en" class="form-control form-control-sm" value="{{ old('name_en') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الوصف (عربي) <span class="text-danger">*</span></label>
                        <textarea name="description_ar" class="form-control form-control-sm" rows="3" required>{{ old('description_ar') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الوصف (فرنسي)</label>
                        <textarea name="description_fr" class="form-control form-control-sm" rows="2">{{ old('description_fr') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-tags me-2"></i>التسعير والمخزون</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">السعر <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">سعر التخفيض</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="sale_price" class="form-control" step="0.01" value="{{ old('sale_price') }}">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">نوع المنتج</label>
                        <select name="type" class="form-select form-select-sm" id="product_type_select">
                            <option value="digital" {{ old('type') === 'digital' ? 'selected' : '' }}>رقمي</option>
                            <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>مادي</option>
                            <option value="template" {{ old('type') === 'template' ? 'selected' : '' }}>قالب</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="stock_field">
                        <label class="form-label fw-bold small">المخزون</label>
                        <input type="number" name="stock" class="form-control form-control-sm" value="{{ old('stock', 0) }}" min="0">
                    </div>
                </div>
            </div>

            <div class="stat-card mb-4" id="digital_file_card" style="display:none;">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-download me-2"></i>الملف الرقمي</h6>
                <input type="file" name="download_file" class="form-control form-control-sm">
                <div class="form-text">PDF, ZIP, PSD — يمكن للمشتري تحميله بعد الشراء</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save me-1"></i>إضافة المنتج
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold">صورة المنتج</h6>
                <input type="file" name="main_image" class="form-control form-control-sm" accept="image/*">
                <div class="form-text">PNG, JPG, WebP — حد أقصى 2MB</div>
            </div>
            <div class="stat-card">
                <h6 class="fw-bold mb-3 text-gold">إعدادات</h6>
                <div class="mb-3">
                    <label class="form-label small fw-bold">الحالة</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="draft">مسودة</option>
                    </select>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                    <label class="form-check-label small" for="is_featured">مميز</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="unlimited_stock" id="unlimited_stock" value="1">
                    <label class="form-check-label small" for="unlimited_stock">مخزون غير محدود</label>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('product_type_select').addEventListener('change', function() {
    const isDigital = this.value === 'digital' || this.value === 'template';
    document.getElementById('stock_field').style.display = isDigital ? 'none' : '';
    document.getElementById('digital_file_card').style.display = isDigital ? '' : 'none';
});
</script>
@endpush
@endsection
