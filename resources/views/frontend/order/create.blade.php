@extends('layouts.app')
@section('title', 'طلب عمل فني - رشيد النزاري')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>{{ __('messages.order_form_title') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.order') }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="order-form-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="form-card">
                    <h4 class="mb-4" style="color: var(--green-dark); font-family: var(--font-arabic);">
                        <i class="fas fa-pen-nib text-gold me-2"></i>تفاصيل الطلب
                    </h4>

                    <form method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data" id="orderForm">
                        @csrf

                        <div class="row g-3">
                            {{-- Customer Info --}}
                            <div class="col-12"><h6 class="text-gold border-bottom border-gold pb-2 mb-3">معلومات الزبون</h6></div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.customer_name') }} *</label>
                                <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                                    value="{{ old('customer_name', auth()->user()?->name) }}" required>
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.customer_email') }} *</label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror"
                                    value="{{ old('customer_email', auth()->user()?->email) }}" required>
                                @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.customer_phone') }}</label>
                                <input type="tel" name="customer_phone" class="form-control"
                                    value="{{ old('customer_phone', auth()->user()?->phone) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.customer_country') }}</label>
                                <select name="customer_country" class="form-select" id="customer_country">
                                    <option value="">-- اختر الدولة --</option>
                                    <option value="المغرب" {{ old('customer_country') === 'المغرب' ? 'selected' : '' }}>🇲🇦 المغرب</option>
                                    <option value="فرنسا" {{ old('customer_country') === 'فرنسا' ? 'selected' : '' }}>🇫🇷 فرنسا</option>
                                    <option value="السعودية">🇸🇦 السعودية</option>
                                    <option value="الإمارات">🇦🇪 الإمارات</option>
                                    <option value="قطر">🇶🇦 قطر</option>
                                    <option value="الكويت">🇰🇼 الكويت</option>
                                    <option value="مصر">🇪🇬 مصر</option>
                                    <option value="ألمانيا">🇩🇪 ألمانيا</option>
                                    <option value="بلجيكا">🇧🇪 بلجيكا</option>
                                    <option value="هولندا">🇳🇱 هولندا</option>
                                    <option value="إسبانيا">🇪🇸 إسبانيا</option>
                                    <option value="أمريكا">🇺🇸 أمريكا</option>
                                    <option value="كندا">🇨🇦 كندا</option>
                                    <option value="أخرى">أخرى</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.customer_city') }}</label>
                                <input type="text" name="customer_city" class="form-control" value="{{ old('customer_city') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.customer_address') }}</label>
                                <input type="text" name="customer_address" class="form-control" value="{{ old('customer_address') }}">
                            </div>

                            {{-- Order Details --}}
                            <div class="col-12 mt-2"><h6 class="text-gold border-bottom border-gold pb-2 mb-3">تفاصيل العمل الفني</h6></div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.order_type') }} *</label>
                                <select name="order_type" class="form-select" required>
                                    <option value="">-- اختر نوع الطلب --</option>
                                    <option value="artwork">لوحة فنية</option>
                                    <option value="name_writing">كتابة اسم</option>
                                    <option value="verse_hadith">آية أو حديث</option>
                                    <option value="institution">لوحة مؤسسة</option>
                                    <option value="home_decor">ديكور منزلي</option>
                                    <option value="digital_copy">نسخة رقمية فقط</option>
                                    <option value="custom">طلب خاص</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.calligraphy_style') }}</label>
                                <select name="calligraphy_style" class="form-select">
                                    <option value="">-- اختر أسلوب الخط --</option>
                                    <option value="moroccan">{{ __('messages.moroccan') }}</option>
                                    <option value="andalusian">{{ __('messages.andalusian') }}</option>
                                    <option value="naskh">{{ __('messages.naskh') }}</option>
                                    <option value="thuluth">{{ __('messages.thuluth') }}</option>
                                    <option value="kufic">{{ __('messages.kufic') }}</option>
                                    <option value="ruqah">{{ __('messages.ruqah') }}</option>
                                    <option value="diwani">{{ __('messages.diwani') }}</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ __('messages.text_to_write') }}</label>
                                <textarea name="text_to_write" class="form-control" rows="3"
                                    placeholder="اكتب هنا النص الذي تريد كتابته بالخط العربي...">{{ old('text_to_write') }}</textarea>
                            </div>

                            {{-- Dimensions & Material --}}
                            <div class="col-12 mt-2"><h6 class="text-gold border-bottom border-gold pb-2 mb-3">المقاسات والمواد</h6></div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('messages.width') }}</label>
                                <input type="number" name="width_cm" id="width_cm" class="form-control" min="5" max="500"
                                    value="{{ old('width_cm') }}" placeholder="مثال: 40">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('messages.height') }}</label>
                                <input type="number" name="height_cm" id="height_cm" class="form-control" min="5" max="500"
                                    value="{{ old('height_cm') }}" placeholder="مثال: 60">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.material') }}</label>
                                <select name="material" id="material_id" class="form-select">
                                    <option value="">-- اختر المادة --</option>
                                    @foreach($materials as $mat)
                                    <option value="{{ $mat->id }}" data-price="{{ $mat->price_per_unit }}">
                                        {{ $mat->name }} ({{ $mat->price_per_unit }} MAD/cm²)
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Delivery --}}
                            <div class="col-12 mt-2"><h6 class="text-gold border-bottom border-gold pb-2 mb-3">طريقة التسليم</h6></div>

                            <div class="col-12">
                                <div class="row g-3">
                                    @foreach([
                                        ['value' => 'digital', 'icon' => 'fa-file-image', 'label' => 'نسخة رقمية (PNG/PDF/SVG)', 'desc' => 'مثالية للطباعة'],
                                        ['value' => 'postal', 'icon' => 'fa-shipping-fast', 'label' => 'شحن بريدي', 'desc' => 'يصل إلى باب منزلك'],
                                        ['value' => 'pickup', 'icon' => 'fa-store', 'label' => 'استلام شخصي بمراكش', 'desc' => 'مجاناً'],
                                    ] as $del)
                                    <div class="col-md-4">
                                        <label class="d-block">
                                            <input type="radio" name="delivery_method" id="delivery_method" value="{{ $del['value'] }}"
                                                class="d-none delivery-radio" {{ old('delivery_method', 'digital') === $del['value'] ? 'checked' : '' }}>
                                            <div class="delivery-option p-3 rounded border text-center cursor-pointer" style="cursor:pointer; transition: all 0.2s;">
                                                <i class="fas {{ $del['icon'] }} fa-2x text-gold mb-2 d-block"></i>
                                                <strong style="font-size:0.9rem;">{{ $del['label'] }}</strong>
                                                <small class="text-muted d-block">{{ $del['desc'] }}</small>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="include_digital" id="include_digital" value="1">
                                    <label class="form-check-label" for="include_digital">
                                        إضافة نسخة رقمية عالية الدقة (+{{ $pricingConfig?->digital_copy_price ?? 50 }} MAD)
                                    </label>
                                </div>
                            </div>

                            {{-- Notes & Files --}}
                            <div class="col-12 mt-2"><h6 class="text-gold border-bottom border-gold pb-2 mb-3">ملاحظات وصور مرجعية</h6></div>

                            <div class="col-12">
                                <label class="form-label">{{ __('messages.notes') }}</label>
                                <textarea name="notes" class="form-control" rows="3"
                                    placeholder="أي تفاصيل إضافية أو متطلبات خاصة...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ __('messages.reference_images') }}</label>
                                <input type="file" name="reference_images[]" class="form-control" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع صور مرجعية أو نماذج مشابهة للعمل المطلوب. (JPG, PNG, حتى 5MB لكل صورة)</div>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-gold btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>{{ __('messages.submit_order') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Price Calculator --}}
            <div class="col-lg-4">
                <div class="price-calculator">
                    <h5><i class="fas fa-calculator me-2"></i>{{ __('messages.calculate_price') }}</h5>

                    <div class="price-row">
                        <span>ثمن العمل الفني</span>
                        <span id="calc_artwork">0.00 MAD</span>
                    </div>
                    <div class="price-row">
                        <span>ثمن المادة</span>
                        <span id="calc_material">0.00 MAD</span>
                    </div>
                    <div class="price-row">
                        <span>ثمن العمل اليدوي</span>
                        <span id="calc_labor">0.00 MAD</span>
                    </div>
                    <div class="price-row">
                        <span>النسخة الرقمية</span>
                        <span id="calc_digital">0.00 MAD</span>
                    </div>
                    <div class="price-row">
                        <span>ثمن الشحن</span>
                        <span id="calc_shipping">0.00 MAD</span>
                    </div>
                    <div class="price-total">
                        <span>{{ __('messages.estimated_cost') }}</span>
                        <span id="calc_total">0.00 MAD</span>
                    </div>

                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-top: 1rem;">
                        * هذا السعر تقديري. السعر النهائي يحدده الفنان بعد مراجعة الطلب.
                    </p>
                </div>

                {{-- Why us --}}
                <div class="mt-4 p-3 bg-beige rounded">
                    <h6 class="text-green fw-bold mb-3">لماذا تختارنا؟</h6>
                    @foreach([
                        ['icon' => 'fa-medal', 'text' => 'جودة عالية وأصالة مغربية'],
                        ['icon' => 'fa-shipping-fast', 'text' => 'شحن سريع لجميع دول العالم'],
                        ['icon' => 'fa-shield-alt', 'text' => 'ضمان الرضا أو الاسترداد'],
                        ['icon' => 'fa-comments', 'text' => 'تواصل مباشر مع الفنان'],
                        ['icon' => 'fa-file-image', 'text' => 'نسخة رقمية عالية الدقة'],
                    ] as $item)
                    <div class="d-flex gap-2 mb-2 align-items-center">
                        <i class="fas {{ $item['icon'] }} text-gold"></i>
                        <small>{{ $item['text'] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@section('scripts')
<script>
// Delivery option visual selection
document.querySelectorAll('.delivery-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.delivery-option').forEach(opt => {
            opt.style.borderColor = '';
            opt.style.background = '';
        });
        if (radio.checked) {
            const opt = radio.nextElementSibling;
            opt.style.borderColor = 'var(--gold)';
            opt.style.background = 'rgba(201,168,76,0.08)';
        }
    });
    if (radio.checked) {
        const opt = radio.nextElementSibling;
        opt.style.borderColor = 'var(--gold)';
        opt.style.background = 'rgba(201,168,76,0.08)';
    }
});

// Click on delivery-option selects the radio
document.querySelectorAll('.delivery-option').forEach(opt => {
    opt.addEventListener('click', () => {
        const radio = opt.previousElementSibling;
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection

@endsection
