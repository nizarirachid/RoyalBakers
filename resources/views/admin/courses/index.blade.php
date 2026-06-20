@extends('layouts.admin')
@section('title', 'الدورات')
@section('page-title', 'إدارة الدورات')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">قائمة الدورات</h6>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus me-1"></i>دورة جديدة
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr>
                    <th>الدورة</th>
                    <th>النوع</th>
                    <th>المستوى</th>
                    <th>السعر</th>
                    <th>المسجلون</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="" class="rounded" style="width:40px;height:30px;object-fit:cover;">
                            @endif
                            <div>
                                <span class="small fw-bold d-block">{{ Str::limit($course->title_ar, 35) }}</span>
                                <small class="text-muted">{{ $course->duration_hours }} ساعة</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-info small">{{ $course->type }}</span></td>
                    <td><span class="badge bg-secondary small">{{ $course->level }}</span></td>
                    <td>
                        @if($course->price == 0)
                        <span class="badge bg-success small">مجاني</span>
                        @else
                        <small>{{ $course->price }} MAD</small>
                        @endif
                    </td>
                    <td>
                        <small>{{ $course->enrolled_count ?? 0 }}
                            @if($course->max_students) / {{ $course->max_students }} @endif
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }} small">
                            {{ $course->status === 'published' ? 'منشور' : 'مسودة' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" data-confirm-delete="حذف هذه الدورة؟">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">لا توجد دورات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $courses->withQueryString()->links() }}</div>
</div>
@endsection
