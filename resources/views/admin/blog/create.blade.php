@extends('layouts.admin')
@section('title', 'مقال جديد')
@section('page-title', 'إنشاء مقال جديد')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-pen me-2"></i>محتوى المقال</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold small">العنوان (العربية) <span class="text-danger">*</span></label>
                    <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">العنوان (الفرنسية)</label>
                    <input type="text" name="title_fr" class="form-control" value="{{ old('title_fr') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">العنوان (الإنجليزية)</label>
                    <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">المقتطف (العربية)</label>
                    <textarea name="excerpt_ar" class="form-control" rows="2">{{ old('excerpt_ar') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">المحتوى الكامل (العربية) <span class="text-danger">*</span></label>
                    <textarea name="content_ar" class="form-control" rows="10" required>{{ old('content_ar') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">المحتوى (الفرنسية)</label>
                    <textarea name="content_fr" class="form-control" rows="6">{{ old('content_fr') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">المحتوى (الإنجليزية)</label>
                    <textarea name="content_en" class="form-control" rows="6">{{ old('content_en') }}</textarea>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-search me-2"></i>تحسين محركات البحث (SEO)</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold small">الرابط (Slug)</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="سيتم توليده تلقائيًا">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">وصف SEO (العربية)</label>
                    <textarea name="meta_description_ar" class="form-control" rows="2">{{ old('meta_description_ar') }}</textarea>
                    <div class="form-text">يظهر في نتائج البحث (مثالي: 160 حرف)</div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="status" value="draft" class="btn btn-outline-secondary">
                    <i class="fas fa-save me-1"></i>حفظ مسودة
                </button>
                <button type="submit" name="status" value="published" class="btn btn-gold">
                    <i class="fas fa-paper-plane me-1"></i>نشر المقال
                </button>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3 text-gold">الصورة الرئيسية</h6>
            <input type="file" name="featured_image" class="form-control form-control-sm" accept="image/*"
                form="blog-form-main" id="featured_image_input">
            <div id="image-preview" class="mt-2" style="display:none;">
                <img id="preview-img" src="" class="img-fluid rounded" alt="معاينة">
            </div>
            <div class="form-text">PNG, JPG, WebP — حد أقصى 2MB</div>
        </div>
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3 text-gold">تفاصيل النشر</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">الحالة</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="draft">مسودة</option>
                    <option value="published">منشور</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">تاريخ النشر</label>
                <input type="datetime-local" name="published_at" class="form-control form-control-sm"
                    value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
            </div>
        </div>
        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-gold">الصورة الرئيسية (الرفع)</h6>
            <input type="file" name="featured_image" class="form-control form-control-sm" accept="image/*">
            <div class="form-text">PNG, JPG, WebP — حد أقصى 2MB</div>
        </div>
    </div>
</div>
@endsection
