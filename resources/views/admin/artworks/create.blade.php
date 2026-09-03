@extends('layouts.admin')
@section('title', 'إضافة عمل فني')
@section('page-title', 'إضافة عمل فني جديد')

@section('content')
<div class="stat-card">
    <form method="POST" action="{{ route('admin.artworks.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">العنوان بالعربية *</label>
                <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                    value="{{ old('title_ar') }}" required>
                @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">العنوان بالفرنسية</label>
                <input type="text" name="title_fr" class="form-control" value="{{ old('title_fr') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">العنوان بالإنجليزية</label>
                <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">التصنيف</label>
                <select name="category_id" class="form-select">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">أسلوب الخط *</label>
                <select name="calligraphy_style" class="form-select" required>
                    @foreach(['moroccan'=>'مغربي','andalusian'=>'أندلسي','naskh'=>'نسخ','thuluth'=>'ثلث','kufic'=>'كوفي','ruqah'=>'رقعة','diwani'=>'ديواني','other'=>'أخرى'] as $v=>$l)
                    <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">المادة *</label>
                <select name="material" class="form-select" required>
                    @foreach(['paper'=>'ورق','wood'=>'خشب','glass'=>'زجاج','plaster'=>'جبس','canvas'=>'قماش','leather'=>'جلد','fabric'=>'قماش','digital'=>'رقمي','other'=>'أخرى'] as $v=>$l)
                    <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">العرض (سم)</label>
                <input type="number" name="width_cm" class="form-control" step="0.5" min="0">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">الارتفاع (سم)</label>
                <input type="number" name="height_cm" class="form-control" step="0.5" min="0">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">السعر (MAD)</label>
                <input type="number" name="price" class="form-control" step="0.01" min="0">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">الحالة *</label>
                <select name="status" class="form-select" required>
                    <option value="published">منشور</option>
                    <option value="draft">مسودة</option>
                    <option value="archived">مؤرشف</option>
                </select>
            </div>

            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_for_sale" id="is_for_sale" value="1">
                    <label class="form-check-label" for="is_for_sale">متاح للبيع</label>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                    <label class="form-check-label" for="is_featured">عمل مميز (يظهر في الرئيسية)</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">النص المكتوب</label>
                <input type="text" name="text_content" class="form-control" placeholder="النص الخطي في اللوحة...">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">الوصف بالعربية</label>
                <textarea name="description_ar" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">الصورة الرئيسية *</label>
                <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror"
                    accept="image/*" required>
                @error('main_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">صور إضافية</label>
                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save me-1"></i>حفظ العمل الفني
                </button>
                <a href="{{ route('admin.artworks.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>
    </form>
</div>
@endsection
