@extends('layouts.admin')
@section('title', 'رسالة من ' . $message->name)
@section('page-title', 'تفاصيل الرسالة')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="stat-card mb-4">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold" style="color: var(--green-dark);">رسالة من: {{ $message->name }}</h6>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i>العودة
                </a>
            </div>
            <table class="table table-borderless table-sm mb-3">
                <tr><th class="text-muted" style="width:120px;">الاسم</th><td>{{ $message->name }}</td></tr>
                <tr><th class="text-muted">البريد</th><td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td></tr>
                @if($message->phone)
                <tr><th class="text-muted">الهاتف</th><td>{{ $message->phone }}</td></tr>
                @endif
                <tr><th class="text-muted">الموضوع</th><td>{{ $message->subject ?? '-' }}</td></tr>
                <tr><th class="text-muted">التاريخ</th><td>{{ $message->created_at->format('Y/m/d H:i') }}</td></tr>
            </table>
            <hr class="gold-divider">
            <h6 class="text-gold mb-2">الرسالة:</h6>
            <p style="line-height: 1.9; white-space: pre-wrap;">{{ $message->message }}</p>
        </div>

        @if($message->admin_reply)
        <div class="stat-card mb-4" style="border-left: 3px solid var(--gold);">
            <h6 class="text-gold mb-2">
                <i class="fas fa-reply me-2"></i>الرد المرسل ({{ $message->replied_at?->format('Y/m/d H:i') }})
            </h6>
            <p style="white-space: pre-wrap;">{{ $message->admin_reply }}</p>
        </div>
        @endif

        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-gold">الرد على الرسالة</h6>
            <form method="POST" action="{{ route('admin.messages.reply', $message) }}">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <textarea name="reply" class="form-control" rows="5"
                        placeholder="اكتب ردك هنا..." required>{{ old('reply', $message->admin_reply) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-gold">
                        <i class="fas fa-paper-plane me-1"></i>إرسال الرد
                    </button>
                    <a href="mailto:{{ $message->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-1"></i>الرد عبر البريد
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
