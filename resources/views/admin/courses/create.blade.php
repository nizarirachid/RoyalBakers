@extends('layouts.admin')
@section('title', 'دورة جديدة')
@section('page-title', 'إضافة دورة جديدة')

@section('content')
<form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-graduation-cap me-2"></i>معلومات الدورة</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold small">العنوان (عربي) <span class="text-danger">*</span></label>
                        <input type="text" name="title_ar" class="form-control form-control-sm" value="{{ old('title_ar') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">العنوان (فرنسي)</label>
                        <input type="text" name="title_fr" class="form-control form-control-sm" value="{{ old('title_fr') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">العنوان (إنجليزي)</label>
                        <input type="text" name="title_en" class="form-control form-control-sm" value="{{ old('title_en') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الوصف (عربي) <span class="text-danger">*</span></label>
                        <textarea name="description_ar" class="form-control form-control-sm" rows="4" required>{{ old('description_ar') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">المنهج الدراسي / ما ستتعلمه</label>
                        <textarea name="curriculum" class="form-control form-control-sm" rows="3" placeholder="نقطة بنقطة، سطر لكل نقطة">{{ old('curriculum') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-cog me-2"></i>تفاصيل الدورة</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">نوع الدورة</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="online">أونلاين</option>
                            <option value="in_person">حضوري</option>
                            <option value="hybrid">هجين</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">المستوى</label>
                        <select name="level" class="form-select form-select-sm">
                            <option value="beginner">مبتدئ</option>
                            <option value="intermediate">متوسط</option>
                            <option value="advanced">متقدم</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">المدة (ساعات)</label>
                        <input type="number" name="duration_hours" class="form-control form-control-sm" value="{{ old('duration_hours', 10) }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">السعر (MAD)</label>
                        <input type="number" name="price" class="form-control form-control-sm" value="{{ old('price', 0) }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الحد الأقصى للطلاب</label>
                        <input type="number" name="max_students" class="form-control form-control-sm" value="{{ old('max_students') }}" placeholder="اتركه فارغًا لا حد">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">تاريخ البدء</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ old('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">تاريخ الانتهاء</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save me-1"></i>إضافة الدورة
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold">صورة الغلاف</h6>
                <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*">
                <div class="form-text">PNG, JPG, WebP — يُفضل 800×450</div>
            </div>
            <div class="stat-card">
                <h6 class="fw-bold mb-3 text-gold">حالة الدورة</h6>
                <div class="mb-3">
                    <label class="form-label small fw-bold">الحالة</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="draft">مسودة</option>
                        <option value="published">منشور</option>
                        <option value="archived">مؤرشف</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">رابط الاجتماع (أونلاين)</label>
                    <input type="url" name="meeting_link" class="form-control form-control-sm" value="{{ old('meeting_link') }}" placeholder="https://zoom.us/...">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">الموقع (حضوري)</label>
                    <input type="text" name="location" class="form-control form-control-sm" value="{{ old('location') }}" placeholder="الرباط - المغرب">
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
