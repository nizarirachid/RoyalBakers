@extends('layouts.admin')
@section('title', 'المقالات')
@section('page-title', 'إدارة المقالات')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">قائمة المقالات</h6>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus me-1"></i>مقال جديد
        </a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">جميع الحالات</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>مؤرشف</option>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="بحث في العنوان..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-gold btn-sm w-100">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr>
                    <th>العنوان</th>
                    <th>الكاتب</th>
                    <th>الحالة</th>
                    <th>المشاهدات</th>
                    <th>التعليقات</th>
                    <th>تاريخ النشر</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" alt="" class="rounded" style="width:40px;height:30px;object-fit:cover;">
                            @endif
                            <span class="small fw-bold">{{ Str::limit($post->title_ar, 40) }}</span>
                        </div>
                    </td>
                    <td><small>{{ $post->author->name ?? '-' }}</small></td>
                    <td>
                        @php $colors = ['draft'=>'secondary','published'=>'success','archived'=>'dark']; @endphp
                        <span class="badge bg-{{ $colors[$post->status] ?? 'secondary' }} small">{{ $post->status }}</span>
                    </td>
                    <td><small>{{ number_format($post->views) }}</small></td>
                    <td><small>{{ $post->comments->count() }}</small></td>
                    <td><small class="text-muted">{{ $post->published_at?->format('Y/m/d') ?? '-' }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" data-confirm-delete="حذف هذا المقال؟">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">لا توجد مقالات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $posts->withQueryString()->links() }}</div>
</div>
@endsection
