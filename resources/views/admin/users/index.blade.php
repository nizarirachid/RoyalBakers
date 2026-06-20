@extends('layouts.admin')
@section('title', 'المستخدمون')
@section('page-title', 'إدارة المستخدمين')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">قائمة المستخدمين</h6>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="role" class="form-select form-select-sm">
                <option value="">جميع الصلاحيات</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">جميع الحالات</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
                <option value="banned">محظور</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="بحث بالاسم أو البريد..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-gold btn-sm w-100">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الصلاحية</th>
                    <th>الدولة</th>
                    <th>الحالة</th>
                    <th>تاريخ التسجيل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="testimonial-avatar-placeholder" style="width:32px;height:32px;font-size:0.8rem;">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <span class="small fw-bold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td><small>{{ $user->email }}</small></td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="badge badge-gold small">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td><small>{{ $user->country ?? '-' }}</small></td>
                    <td>
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'banned' ? 'danger' : 'secondary') }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $user->created_at->format('Y/m/d') }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$user->hasRole('super-admin'))
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
                                    data-confirm-delete="هل تريد حذف هذا المستخدم؟">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">لا يوجد مستخدمون</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
