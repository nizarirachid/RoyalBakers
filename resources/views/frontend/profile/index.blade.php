@extends('layouts.app')
@section('title', 'حسابي - النزاري للخط العربي')

@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            {{-- Profile Sidebar --}}
            <div class="col-lg-3">
                <div class="stat-card text-center mb-4">
                    <div class="testimonial-avatar-placeholder mx-auto mb-3" style="width:80px;height:80px;font-size:2rem;">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <h6 class="fw-bold">{{ auth()->user()->name }}</h6>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                    <div class="mt-2">
                        @foreach(auth()->user()->roles as $role)
                        <span class="badge badge-gold">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="stat-card">
                    <ul class="nav flex-column nav-pills">
                        <li class="nav-item">
                            <a href="#orders" class="nav-link active text-start" data-bs-toggle="pill">
                                <i class="fas fa-shopping-bag me-2"></i>طلباتي
                                <span class="badge bg-secondary float-end">{{ $orders->total() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#courses" class="nav-link text-start" data-bs-toggle="pill">
                                <i class="fas fa-graduation-cap me-2"></i>دوراتي
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#purchases" class="nav-link text-start" data-bs-toggle="pill">
                                <i class="fas fa-download me-2"></i>مشترياتي
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#settings" class="nav-link text-start" data-bs-toggle="pill">
                                <i class="fas fa-cog me-2"></i>الإعدادات
                            </a>
                        </li>
                        <li class="nav-item border-top mt-2 pt-2">
                            <a href="{{ route('change-password') }}" class="nav-link text-start text-warning">
                                <i class="fas fa-key me-2"></i>تغيير كلمة المرور
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-lg-9">
                <div class="tab-content">
                    {{-- Orders Tab --}}
                    <div class="tab-pane fade show active" id="orders">
                        <div class="stat-card">
                            <h5 class="fw-bold text-gold mb-4"><i class="fas fa-shopping-bag me-2"></i>طلباتي</h5>
                            @forelse($orders as $order)
                            <div class="border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <code class="fw-bold">{{ $order->order_number }}</code>
                                        <span class="badge bg-{{ ['pending'=>'secondary','confirmed'=>'info','in_progress'=>'warning','completed'=>'success','cancelled'=>'danger'][$order->status] ?? 'secondary' }} ms-2">
                                            {{ ['pending'=>'قيد الانتظار','confirmed'=>'مؤكد','in_progress'=>'قيد التنفيذ','completed'=>'مكتمل','cancelled'=>'ملغي'][$order->status] ?? $order->status }}
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $order->created_at->format('Y/m/d') }}</small>
                                </div>
                                <div class="row g-2 small text-muted">
                                    <div class="col-md-4">
                                        <span>النوع: {{ $order->order_type }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>المبلغ: <strong class="text-gold">{{ number_format($order->total_price, 0) }} MAD</strong></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>الدفع: {{ $order->payment_method }}</span>
                                    </div>
                                </div>
                                @if($order->customer_notes)
                                <p class="small text-muted mt-2 mb-0 border-top pt-2">{{ Str::limit($order->customer_notes, 80) }}</p>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-bag text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">لا توجد طلبات حتى الآن</p>
                                <a href="{{ route('order.create') }}" class="btn btn-gold btn-sm">
                                    <i class="fas fa-plus me-1"></i>اطلب لوحة
                                </a>
                            </div>
                            @endforelse
                            @if($orders->total())
                            <div class="mt-3">{{ $orders->links() }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Courses Tab --}}
                    <div class="tab-pane fade" id="courses">
                        <div class="stat-card">
                            <h5 class="fw-bold text-gold mb-4"><i class="fas fa-graduation-cap me-2"></i>دوراتي</h5>
                            @forelse($enrollments ?? [] as $enrollment)
                            <div class="border rounded-3 p-3 mb-3 d-flex gap-3 align-items-center">
                                @if($enrollment->course->cover_image)
                                <img src="{{ Storage::url($enrollment->course->cover_image) }}" class="rounded" style="width:70px;height:50px;object-fit:cover;" alt="">
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $enrollment->course->title_ar }}</h6>
                                    <div class="d-flex gap-3 small text-muted">
                                        <span>{{ $enrollment->course->duration_hours }} ساعة</span>
                                        <span>تسجيل: {{ $enrollment->created_at->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $enrollment->status === 'completed' ? 'success' : 'info' }}">
                                    {{ $enrollment->status === 'completed' ? 'مكتمل' : 'جارٍ' }}
                                </span>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-graduation-cap text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">لم تسجّل في أي دورة حتى الآن</p>
                                <a href="{{ route('courses.index') }}" class="btn btn-gold btn-sm">
                                    استعرض الدورات
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Purchases Tab --}}
                    <div class="tab-pane fade" id="purchases">
                        <div class="stat-card">
                            <h5 class="fw-bold text-gold mb-4"><i class="fas fa-download me-2"></i>مشترياتي الرقمية</h5>
                            <div class="text-center py-4">
                                <i class="fas fa-box-open text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">يمكنك شراء منتجاتنا الرقمية من المتجر</p>
                                <a href="{{ route('shop.index') }}" class="btn btn-gold btn-sm">استعرض المتجر</a>
                            </div>
                        </div>
                    </div>

                    {{-- Settings Tab --}}
                    <div class="tab-pane fade" id="settings">
                        <div class="stat-card">
                            <h5 class="fw-bold text-gold mb-4"><i class="fas fa-cog me-2"></i>إعدادات الحساب</h5>
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf @method('PATCH')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">الاسم الكامل</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">البريد الإلكتروني</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">الدولة</label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">المدينة</label>
                                        <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()->city) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">اللغة المفضلة</label>
                                        <select name="preferred_language" class="form-select">
                                            <option value="ar" {{ auth()->user()->preferred_language === 'ar' ? 'selected' : '' }}>العربية</option>
                                            <option value="fr" {{ auth()->user()->preferred_language === 'fr' ? 'selected' : '' }}>Français</option>
                                            <option value="en" {{ auth()->user()->preferred_language === 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">العنوان</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}">
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-gold">
                                            <i class="fas fa-save me-1"></i>حفظ التعديلات
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
