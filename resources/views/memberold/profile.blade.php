{{-- FILE: resources/views/member/profile.blade.php --}}
@extends('member.layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                {{-- Avatar & Name --}}
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width:80px;height:80px;background:linear-gradient(135deg,#1a3c5e,#1db87a);">
                        <span style="font-size:2rem;color:#fff;font-weight:700;">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </span>
                    </div>
                    <h5 class="fw-bold mb-0">{{ $member->name }}</h5>
                    <p class="text-muted mb-0" style="font-size:.85rem;">{{ $member->memberID }}</p>
                </div>

                <hr>

                <table class="table table-borderless mb-0" style="font-size:.92rem;">
                    <tr>
                        <td class="text-muted fw-semibold" style="width:40%;">Member ID</td>
                        <td>{{ $member->memberID }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Full Name</td>
                        <td>{{ $member->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Email</td>
                        <td>{{ $member->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Phone</td>
                        <td>{{ $member->phone }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Sponsor ID</td>
                        <td>{{ $member->sponser_id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Sponsor Name</td>
                        <td>{{ $member->sponser_name ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Joining Date</td>
                        <td>{{ $member->joining_date }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Smart Points</td>
                        <td><strong class="text-success">{{ number_format($member->smart_point, 4) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Smart Quantity</td>
                        <td><strong class="text-primary">{{ $member->smart_quanity ?: '0.0000' }}</strong></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>

@endsection
