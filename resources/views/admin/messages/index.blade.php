@extends('layouts.admin')
@section('title', 'الرسائل')
@section('page-title', 'إدارة الرسائل')

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color: var(--green-dark);">رسائل التواصل</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary btn-sm {{ !request('status') ? 'active' : '' }}">الكل</a>
            <a href="{{ route('admin.messages.index', ['status' => 'new']) }}" class="btn btn-outline-danger btn-sm {{ request('status') === 'new' ? 'active' : '' }}">
                جديد
                @php $c = \App\Models\ContactMessage::where('status','new')->count(); @endphp
                @if($c) <span class="badge bg-danger">{{ $c }}</span> @endif
            </a>
            <a href="{{ route('admin.messages.index', ['status' => 'replied']) }}" class="btn btn-outline-success btn-sm">تم الرد</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-nizari">
                <tr><th>المرسل</th><th>البريد</th><th>الموضوع</th><th>الرسالة</th><th>الحالة</th><th>التاريخ</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr class="{{ $msg->status === 'new' ? 'fw-bold' : '' }}">
                    <td>{{ $msg->name }}</td>
                    <td><small>{{ $msg->email }}</small></td>
                    <td><small>{{ Str::limit($msg->subject, 30) ?? '-' }}</small></td>
                    <td><small class="text-muted">{{ Str::limit($msg->message, 50) }}</small></td>
                    <td>
                        @php $mc = ['new'=>'danger','read'=>'secondary','replied'=>'success','archived'=>'dark']; @endphp
                        <span class="badge bg-{{ $mc[$msg->status] ?? 'secondary' }} small">{{ $msg->status }}</span>
                    </td>
                    <td><small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small></td>
                    <td>
                        <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">لا توجد رسائل</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $messages->links() }}</div>
</div>
@endsection
