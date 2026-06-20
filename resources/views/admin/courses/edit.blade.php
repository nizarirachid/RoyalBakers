@extends('layouts.admin')
@section('title', 'تعديل الدورة')
@section('page-title', 'تعديل الدورة')

@section('content')
<form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-graduation-cap me-2"></i>معلومات الدورة</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold small">العنوان (عربي) <span class="text-danger">*</span></label>
                        <input type="text" name="title_ar" class="form-control form-control-sm" value="{{ old('title_ar', $course->title_ar) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">العنوان (فرنسي)</label>
                        <input type="text" name="title_fr" class="form-control form-control-sm" value="{{ old('title_fr', $course->title_fr) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">العنوان (إنجليزي)</label>
                        <input type="text" name="title_en" class="form-control form-control-sm" value="{{ old('title_en', $course->title_en) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الوصف (عربي)</label>
                        <textarea name="description_ar" class="form-control form-control-sm" rows="4">{{ old('description_ar', $course->description_ar) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-cog me-2"></i>تفاصيل الدورة</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">نوع الدورة</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="online" {{ $course->type === 'online' ? 'selected' : '' }}>أونلاين</option>
                            <option value="in_person" {{ $course->type === 'in_person' ? 'selected' : '' }}>حضوري</option>
                            <option value="hybrid" {{ $course->type === 'hybrid' ? 'selected' : '' }}>هجين</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">المستوى</label>
                        <select name="level" class="form-select form-select-sm">
                            <option value="beginner" {{ $course->level === 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                            <option value="intermediate" {{ $course->level === 'intermediate' ? 'selected' : '' }}>متوسط</option>
                            <option value="advanced" {{ $course->level === 'advanced' ? 'selected' : '' }}>متقدم</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">المدة (ساعات)</label>
                        <input type="number" name="duration_hours" class="form-control form-control-sm" value="{{ old('duration_hours', $course->duration_hours) }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">السعر (MAD)</label>
                        <input type="number" name="price" class="form-control form-control-sm" value="{{ old('price', $course->price) }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الحد الأقصى</label>
                        <input type="number" name="max_students" class="form-control form-control-sm" value="{{ old('max_students', $course->max_students) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">تاريخ البدء</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ old('start_date', $course->start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">تاريخ الانتهاء</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ old('end_date', $course->end_date?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save me-1"></i>حفظ التعديلات
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold">صورة الغلاف</h6>
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="img-fluid rounded mb-2" alt="">
                @endif
                <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*">
            </div>
            <div class="stat-card mb-4">
                <h6 class="fw-bold mb-3 text-gold">حالة الدورة</h6>
                <div class="mb-3">
                    <label class="form-label small fw-bold">الحالة</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>مسودة</option>
                        <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>منشور</option>
                        <option value="archived" {{ $course->status === 'archived' ? 'selected' : '' }}>مؤرشف</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">رابط الاجتماع</label>
                    <input type="url" name="meeting_link" class="form-control form-control-sm" value="{{ old('meeting_link', $course->meeting_link) }}">
                </div>
            </div>
            <div class="stat-card">
                <h6 class="fw-bold mb-3 text-gold">إحصائيات</h6>
                <p class="small text-muted mb-1">المسجلون: <strong>{{ $course->enrolled_count ?? 0 }}</strong></p>
                @if($course->max_students)
                <p class="small text-muted mb-1">الحد الأقصى: <strong>{{ $course->max_students }}</strong></p>
                @endif
                <p class="small text-muted mb-0">تاريخ الإنشاء: <strong>{{ $course->created_at->format('Y/m/d') }}</strong></p>
            </div>
        </div>
    </div>
</form>
@endsection
