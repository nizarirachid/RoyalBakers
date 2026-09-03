@extends('layouts.admin')
@section('title', 'الإعدادات')
@section('page-title', 'إعدادات الموقع')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        {{-- General Settings --}}
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-cog me-2"></i>الإعدادات العامة</h6>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="row g-3">
                    @foreach([
                        ['key' => 'site_name_ar', 'label' => 'اسم الموقع (العربية)', 'type' => 'text'],
                        ['key' => 'site_name_fr', 'label' => 'اسم الموقع (الفرنسية)', 'type' => 'text'],
                        ['key' => 'site_name_en', 'label' => 'اسم الموقع (الإنجليزية)', 'type' => 'text'],
                        ['key' => 'contact_email', 'label' => 'البريد الإلكتروني', 'type' => 'email'],
                        ['key' => 'contact_phone', 'label' => 'رقم الهاتف', 'type' => 'text'],
                        ['key' => 'contact_address_ar', 'label' => 'العنوان', 'type' => 'text'],
                    ] as $s)
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">{{ $s['label'] }}</label>
                        <input type="{{ $s['type'] }}" name="{{ $s['key'] }}" class="form-control form-control-sm"
                            value="{{ \App\Models\Setting::get($s['key']) }}">
                    </div>
                    @endforeach

                    @foreach([
                        ['key' => 'facebook_url', 'label' => 'رابط Facebook', 'icon' => 'fab fa-facebook'],
                        ['key' => 'instagram_url', 'label' => 'رابط Instagram', 'icon' => 'fab fa-instagram'],
                        ['key' => 'youtube_url', 'label' => 'رابط YouTube', 'icon' => 'fab fa-youtube'],
                        ['key' => 'whatsapp_number', 'label' => 'رقم واتساب', 'icon' => 'fab fa-whatsapp'],
                    ] as $s)
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">
                            <i class="{{ $s['icon'] }} me-1"></i>{{ $s['label'] }}
                        </label>
                        <input type="text" name="{{ $s['key'] }}" class="form-control form-control-sm"
                            value="{{ \App\Models\Setting::get($s['key']) }}">
                    </div>
                    @endforeach

                    <div class="col-12">
                        <label class="form-label fw-bold small">وصف الموقع (العربية)</label>
                        <textarea name="site_description_ar" class="form-control form-control-sm" rows="2">{{ \App\Models\Setting::get('site_description_ar') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-gold btn-sm">
                            <i class="fas fa-save me-1"></i>حفظ الإعدادات
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Pricing --}}
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-tags me-2"></i>إعدادات الأسعار</h6>
            <form method="POST" action="{{ route('admin.settings.pricing') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">سعر السنتيمتر المربع</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="price_per_cm" class="form-control" step="0.01"
                                value="{{ $pricingConfig->price_per_cm }}">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">سعر النسخة الرقمية</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="digital_copy_price" class="form-control" step="0.01"
                                value="{{ $pricingConfig->digital_copy_price }}">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الشحن المحلي</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="shipping_local_price" class="form-control" step="0.01"
                                value="{{ $pricingConfig->shipping_local_price }}">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">الشحن الدولي</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="shipping_international_price" class="form-control" step="0.01"
                                value="{{ $pricingConfig->shipping_international_price }}">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">العملة</label>
                        <select name="currency" class="form-select form-select-sm">
                            <option value="MAD" {{ $pricingConfig->currency === 'MAD' ? 'selected' : '' }}>MAD - درهم مغربي</option>
                            <option value="EUR" {{ $pricingConfig->currency === 'EUR' ? 'selected' : '' }}>EUR - يورو</option>
                            <option value="USD" {{ $pricingConfig->currency === 'USD' ? 'selected' : '' }}>USD - دولار</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-gold btn-sm">
                            <i class="fas fa-save me-1"></i>حفظ الأسعار
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Materials --}}
        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-gold"><i class="fas fa-layer-group me-2"></i>أسعار المواد</h6>
            <form method="POST" action="{{ route('admin.settings.materials.store') }}" class="mb-3">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-2"><input type="text" name="name_ar" class="form-control form-control-sm" placeholder="بالعربية" required></div>
                    <div class="col-md-2"><input type="text" name="name_fr" class="form-control form-control-sm" placeholder="بالفرنسية" required></div>
                    <div class="col-md-2"><input type="text" name="name_en" class="form-control form-control-sm" placeholder="بالإنجليزية" required></div>
                    <div class="col-md-2"><input type="number" name="price_per_unit" class="form-control form-control-sm" step="0.01" placeholder="السعر" required></div>
                    <div class="col-md-2"><input type="text" name="unit" class="form-control form-control-sm" value="cm²"></div>
                    <div class="col-md-2"><button class="btn btn-gold btn-sm w-100">إضافة</button></div>
                </div>
            </form>
            <table class="table table-sm">
                <thead class="table-nizari">
                    <tr><th>الاسم</th><th>السعر</th><th>الوحدة</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($materials as $mat)
                    <tr>
                        <td>{{ $mat->name_ar }}</td>
                        <td>{{ $mat->price_per_unit }} MAD</td>
                        <td>{{ $mat->unit }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.settings.materials.destroy', $mat) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" data-confirm-delete="حذف هذه المادة؟">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- System Info --}}
        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-gold">معلومات النظام</h6>
            <table class="table table-borderless table-sm">
                <tr><th class="text-muted small">إصدار PHP</th><td><small>{{ PHP_VERSION }}</small></td></tr>
                <tr><th class="text-muted small">إصدار Laravel</th><td><small>{{ app()->version() }}</small></td></tr>
                <tr><th class="text-muted small">البيئة</th><td><small>{{ app()->environment() }}</small></td></tr>
                <tr><th class="text-muted small">التاريخ</th><td><small>{{ now()->format('Y/m/d H:i') }}</small></td></tr>
            </table>
        </div>
    </div>
</div>
@endsection
