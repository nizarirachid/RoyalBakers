@extends('layouts.admin')
@section('title', 'الأعمال الفنية')
@section('page-title', 'إدارة الأعمال الفنية')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">الأعمال الفنية</h6>
        <a href="{{ route('admin.artworks.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus me-1"></i>إضافة عمل جديد
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr>
                    <th width="80">صورة</th>
                    <th>العنوان</th>
                    <th>الأسلوب</th>
                    <th>المادة</th>
                    <th>السعر</th>
                    <th>للبيع</th>
                    <th>الحالة</th>
                    <th>المشاهدات</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($artworks as $artwork)
                <tr>
                    <td>
                        @if($artwork->main_image)
                            <img src="{{ asset('storage/'.$artwork->main_image) }}" style="width:60px;height:45px;object-fit:cover;border-radius:4px;">
                        @else
                            <div class="placeholder-img" style="width:60px;height:45px;font-size:1.2rem;">✦</div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold small">{{ $artwork->title_ar }}</div>
                        <small class="text-muted">{{ $artwork->category?->name_ar }}</small>
                    </td>
                    <td><small>{{ $artwork->calligraphy_style }}</small></td>
                    <td><small>{{ $artwork->material }}</small></td>
                    <td>{{ $artwork->price ? number_format($artwork->price, 0).' MAD' : '-' }}</td>
                    <td>
                        @if($artwork->is_for_sale)
                            @if($artwork->is_sold)
                                <span class="badge bg-secondary">مباع</span>
                            @else
                                <span class="badge bg-success">نعم</span>
                            @endif
                        @else
                            <span class="badge bg-light text-muted">لا</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $artwork->status === 'published' ? 'success' : ($artwork->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ $artwork->status }}
                        </span>
                    </td>
                    <td><small>{{ number_format($artwork->views) }}</small></td>
                    <td><small class="text-muted">{{ $artwork->created_at->format('Y/m/d') }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.artworks.edit', $artwork) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.artworks.destroy', $artwork) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" data-confirm-delete="هل أنت متأكد من حذف هذا العمل؟">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">لا توجد أعمال فنية بعد</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $artworks->links() }}</div>
</div>
@endsection
