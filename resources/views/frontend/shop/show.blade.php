@extends('layouts.app')
@section('title', $product->name_ar . ' - المتجر')

@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">المتجر</a></li>
                <li class="breadcrumb-item active">{{ $product->name_ar }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-6">
                @if($product->main_image)
                <img src="{{ Storage::url($product->main_image) }}" class="img-fluid rounded shadow" alt="{{ $product->name_ar }}">
                @else
                <div class="zellige-pattern rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-box" style="font-size: 5rem; color: var(--gold);"></i>
                </div>
                @endif
            </div>

            <div class="col-lg-6">
                <span class="badge mb-2" style="background: var(--gold); color: var(--green-dark);">
                    {{ $product->type === 'digital' ? 'منتج رقمي' : 'منتج مادي' }}
                </span>
                <h1 class="fw-bold mb-3" style="font-family: 'Amiri', serif; font-size: 2rem;">{{ $product->name_ar }}</h1>

                <div class="mb-4">
                    @if($product->sale_price)
                    <span class="text-muted text-decoration-line-through fs-5 me-2">{{ number_format($product->price) }} MAD</span>
                    <span class="fw-bold text-gold fs-3">{{ number_format($product->sale_price) }} MAD</span>
                    @else
                    <span class="fw-bold text-gold fs-3">{{ number_format($product->price) }} MAD</span>
                    @endif
                </div>

                <div class="mb-4" style="line-height: 1.9;">
                    <p>{{ $product->description_ar }}</p>
                </div>

                @if($product->type !== 'digital')
                <div class="alert alert-warning mb-4" style="border-color: var(--gold); background: rgba(201,168,76,0.1);">
                    <small><i class="fas fa-truck me-2"></i>يتوفر هذا المنتج للشحن داخل المغرب والدول العربية</small>
                </div>
                @endif

                <div class="d-flex gap-3 mb-4">
                    @auth
                    <form method="POST" action="{{ route('shop.purchase', $product) }}">
                        @csrf
                        <button class="btn btn-gold btn-lg px-4">
                            <i class="fas fa-shopping-cart me-2"></i>شراء الآن
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-gold btn-lg px-4">
                        <i class="fas fa-sign-in-alt me-2"></i>سجّل دخولك للشراء
                    </a>
                    @endauth
                    <a href="{{ route('contact') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-question-circle me-2"></i>استفسار
                    </a>
                </div>

                <div class="border-top pt-3">
                    <table class="table table-borderless table-sm mb-0">
                        <tr><th class="text-muted small">النوع</th><td class="small">{{ $product->type === 'digital' ? 'منتج رقمي قابل للتحميل' : 'منتج مادي' }}</td></tr>
                        @if($product->type !== 'digital' && !$product->unlimited_stock)
                        <tr><th class="text-muted small">المخزون</th>
                            <td class="small {{ $product->stock < 5 ? 'text-danger' : 'text-success' }}">
                                {{ $product->stock > 0 ? 'متوفر (' . $product->stock . ')' : 'غير متوفر' }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
