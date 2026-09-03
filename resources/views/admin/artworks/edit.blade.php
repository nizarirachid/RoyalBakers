@extends('layouts.admin')
@section('title', 'تعديل العمل الفني')
@section('page-title', 'تعديل العمل الفني')

@section('content')
<div class="stat-card">
    <form method="POST" action="{{ route('admin.artworks.update', $artwork) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">العنوان بالعربية *</label>
                <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $artwork->title_ar) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">العنوان بالفرنسية</label>
                <input type="text" name="title_fr" class="form-control" value="{{ old('title_fr', $artwork->title_fr) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">العنوان بالإنجليزية</label>
                <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $artwork->title_en) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">التصنيف</label>
                <select name="category_id" class="form-select">
                    <option value="">بدون تصنيف</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $artwork->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_ar }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">أسلوب الخط *</label>
                <select name="calligraphy_style" class="form-select" required>
                    @foreach(['moroccan'=>'مغربي','andalusian'=>'أندلسي','naskh'=>'نسخ','thuluth'=>'ثلث','kufic'=>'كوفي','ruqah'=>'رقعة','diwani'=>'ديواني','other'=>'أخرى'] as $v=>$l)
                    <option value="{{ $v }}" {{ $artwork->calligraphy_style === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">المادة *</label>
                <select name="material" class="form-select" required>
                    @foreach(['paper'=>'ورق','wood'=>'خشب','glass'=>'زجاج','plaster'=>'جبس','canvas'=>'قماش','leather'=>'جلد','digital'=>'رقمي','other'=>'أخرى'] as $v=>$l)
                    <option value="{{ $v }}" {{ $artwork->material === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">العرض (سم)</label>
                <input type="number" name="width_cm" class="form-control" step="0.5" value="{{ $artwork->width_cm }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">الارتفاع (سم)</label>
                <input type="number" name="height_cm" class="form-control" step="0.5" value="{{ $artwork->height_cm }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">السعر (MAD)</label>
                <input type="number" name="price" class="form-control" step="0.01" value="{{ $artwork->price }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">الحالة *</label>
                <select name="status" class="form-select" required>
                    @foreach(['published'=>'منشور','draft'=>'مسودة','archived'=>'مؤرشف'] as $v=>$l)
                    <option value="{{ $v }}" {{ $artwork->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_for_sale" value="1" {{ $artwork->is_for_sale ? 'checked' : '' }}>
                    <label class="form-check-label">متاح للبيع</label>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ $artwork->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label">عمل مميز</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">النص المكتوب</label>
                <input type="text" name="text_content" class="form-control" value="{{ $artwork->text_content }}">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">الوصف</label>
                <textarea name="description_ar" class="form-control" rows="3">{{ $artwork->description_ar }}</textarea>
            </div>

            @if($artwork->main_image)
            <div class="col-12">
                <label class="form-label fw-bold">الصورة الحالية</label><br>
                <img src="{{ asset('storage/'.$artwork->main_image) }}" style="height:120px;object-fit:cover;border-radius:8px;">
            </div>
            @endif

            <div class="col-md-6">
                <label class="form-label fw-bold">تغيير الصورة الرئيسية</label>
                <input type="file" name="main_image" class="form-control" accept="image/*">
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save me-1"></i>حفظ التغييرات
                </button>
                <a href="{{ route('admin.artworks.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>
    </form>
</div>
@endsection
