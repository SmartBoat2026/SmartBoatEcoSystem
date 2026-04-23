@extends('member.layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">My Passive Bonus</h4>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Bonus Type</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Bonus Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bonuses as $bonus)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bonus->bonus_type }}</td>
                        <td>{{ $bonus->total_quantity }}</td>
                        <td>{{ $bonus->rate }}</td>
                        <td>{{ number_format($bonus->bonus_amount, 2) }}</td>
                        <td>
                            @if($bonus->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($bonus->created_at)->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No bonus records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bonuses->links() }}
        </div>
    </div>
</div>
@endsection
