
{{-- FILE: resources/views/member/dashboard.blade.php --}}
@extends('member.layouts.app')

@section('content')

{{-- ── Welcome Banner ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold" style="color:#1a3c5e;">
            Welcome back, {{ $member->name }} 👋
        </h4>
        <small class="text-muted">Member ID: <strong>{{ $member->memberID }}</strong>
            &nbsp;|&nbsp; Joined: {{ $member->joining_date }}
            &nbsp;|&nbsp; Sponsor: {{ $member->sponser_id }}
        </small>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card card-green">
            <div class="stat-label">Smart Points</div>
            <div class="stat-value">{{ number_format($member->smart_point, 4) }}</div>
            <i class="bi bi-star-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-blue">
            <div class="stat-label">Smart Quantity</div>
            <div class="stat-value">{{ $member->smart_quanity ?: '0.0000' }}</div>
            <i class="bi bi-box-seam stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-purple">
            <div class="stat-label">Total Purchases</div>
            <div class="stat-value">{{ $purchases->count() }}</div>
            <i class="bi bi-cart-check stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-orange">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value">
                ₹{{ number_format($purchases->sum('amount') ?? 0, 2) }}
            </div>
            <i class="bi bi-currency-rupee stat-icon"></i>
        </div>
    </div>
</div>

{{-- ── My Purchases / Invoices ── --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0" style="color:#1a3c5e;"><i class="bi bi-receipt me-2"></i>My Purchases & Invoices</h6>
    </div>
    <div class="card-body p-0">
        @if($purchases->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
                <p class="mt-2">No purchases found.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background:#f8f9fa; font-size:.83rem; text-transform:uppercase; letter-spacing:.5px;">
                        <tr>
                            <th class="px-3">#</th>
                            <th>Invoice No</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Smart Pts</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $i => $p)
                        <tr>
                            <td class="px-3 text-muted">{{ $i + 1 }}</td>
                            <td><code style="font-size:.82rem;">{{ $p->invoice_no ?? 'N/A' }}</code></td>
                            <td>{{ $p->product_name ?? '-' }}</td>
                            <td class="fw-bold text-success">₹{{ number_format($p->amount ?? 0, 2) }}</td>
                            <td>{{ $p->smart_point ?? '0.0000' }}</td>
                            <td style="font-size:.82rem;">{{ $p->purchase_date ?? '-' }}</td>
                            <td>
                                <span class="badge" style="background:#1db87a; font-size:.75rem;">Active</span>
                            </td>
                            <td>
                                @if(!empty($p->invoice_no))
                                    <a href="{{ route('member.invoice', $p->invoice_no) }}"
                                       class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size:.8rem;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
