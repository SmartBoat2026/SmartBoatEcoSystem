@extends('member.layouts.app')

@section('content')

    <div class="page-header">
        <div class="page-title">
            <h1>My Direct Bonus</h1>
            <p>Direct bonus from your team’s product purchases (as per plan slabs)</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #1a3a6b;">
                <div class="pb-stat-label">Total Records</div>
                <div class="pb-stat-value" style="color:#1a3a6b;">
                    {{ $bonuses->total() }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #27500a;">
                <div class="pb-stat-label">Total Bonus</div>
                <div class="pb-stat-value" style="color:#27500a;font-size:18px;">
                    ₹{{ number_format($bonuses->sum('bonus_amount'), 2) }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #3c3489;">
                <div class="pb-stat-label">Active</div>
                <div class="pb-stat-value" style="color:#3c3489;">
                    {{ $bonuses->where('status', 1)->count() }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #6c757d;">
                <div class="pb-stat-label">Inactive</div>
                <div class="pb-stat-value" style="color:#6c757d;">
                    {{ $bonuses->where('status', 0)->count() }}
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-gift me-2"></i>Direct Bonus History</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table id="directBonusTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th style="width:46px;">#</th>
                            <th>Bonus Type</th>
                            <th>Purchase Amount (₹)</th>
                            <th>Rate (%)</th>
                            <th>Bonus Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonuses as $bonus)
                        <tr class="bonus-row-direct" style="cursor:pointer;"
                            data-type="{{ $bonus->bonus_type }}"
                            data-qty="{{ $bonus->total_quantity }}"
                            data-rate="{{ $bonus->rate }}"
                            data-amount="{{ number_format($bonus->bonus_amount, 2) }}"
                            data-status="{{ $bonus->status }}"
                            data-date="{{ \Carbon\Carbon::parse($bonus->created_at)->format('d M Y, h:i A') }}">
                            <td class="text-center text-muted" style="font-size:12px;">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge"
                                      style="background:#e8f5e9;color:#1b5e20;font-size:11px;padding:4px 10px;border-radius:20px;font-weight:600;">
                                    {{ $bonus->bonus_type }}
                                </span>
                            </td>
                            <td style="font-weight:500;color:#495057;">{{ number_format($bonus->total_quantity, 2) }}</td>
                            <td style="font-weight:600;color:#1a3a6b;">{{ $bonus->rate }}</td>
                            <td style="font-weight:700;color:#27500a;white-space:nowrap;">
                                ₹{{ number_format($bonus->bonus_amount, 2) }}
                            </td>
                            <td>
                                @if($bonus->status == 1)
                                    <span class="badge bg-success" style="font-size:11px;padding:4px 10px;">Active</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size:11px;padding:4px 10px;">Inactive</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:500;color:#333;font-size:12px;">
                                    {{ \Carbon\Carbon::parse($bonus->created_at)->format('d M Y') }}
                                </div>
                                <div style="font-size:11px;color:#adb5bd;">
                                    {{ \Carbon\Carbon::parse($bonus->created_at)->format('h:i A') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color:#6c757d;font-size:13px;">
                                <i class="bi bi-cash-coin" style="font-size:30px;display:block;margin-bottom:10px;color:#dee2e6;"></i>
                                No direct bonus records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="directBonusMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a3a6b;color:#fff;padding:12px 20px;">
                    <h6 class="modal-title mb-0"
                        style="font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;">
                        <i class="bi bi-gift me-2"></i>Direct Bonus Detail
                    </h6>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="detail-field-label">Bonus Type</div>
                            <div id="mdb-type" class="detail-field-value" style="color:#1a3a6b;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Status</div>
                            <div id="mdb-status"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Purchase Amount (₹)</div>
                            <div id="mdb-qty" class="detail-field-value" style="color:#333;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Rate (%)</div>
                            <div id="mdb-rate" class="detail-field-value" style="color:#1a3a6b;"></div>
                        </div>
                        <div class="col-12">
                            <div style="background:#f4f7fb;border-left:4px solid #27500a;border-radius:0 6px 6px 0;padding:14px 18px;">
                                <div class="detail-field-label">Bonus Amount</div>
                                <div id="mdb-amount" style="font-size:28px;font-weight:800;color:#27500a;"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-field-label">Date &amp; Time</div>
                            <div id="mdb-date" style="font-size:13px;font-weight:500;color:#333;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    @if($bonuses->count())
    $('#directBonusTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable: false, searchable: false, targets: [0] }
        ],
        rowCallback: function (row, data, index) {
            $('td:eq(0)', row).text(index + 1);
        },
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className: 'buttons-excel',
                title: 'Direct Bonus History',
                exportOptions: { columns: [0,1,2,3,4,5,6] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className: 'buttons-pdf',
                title: 'Direct Bonus History',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0,1,2,3,4,5,6] }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i>Print',
                className: 'buttons-print',
                title: 'Direct Bonus History',
                exportOptions: { columns: [0,1,2,3,4,5,6] }
            }
        ],
        language: {
            search: '<i class="bi bi-search"></i>',
            searchPlaceholder: 'Search…',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty: 'No records found',
            paginate: { previous: '‹', next: '›' }
        },
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });
    @endif

    $(document).on('click', '#directBonusTable tbody .bonus-row-direct', function () {
        var row    = $(this);
        var status = row.data('status');

        $('#mdb-type').text(row.data('type'));
        $('#mdb-qty').text(row.data('qty'));
        $('#mdb-rate').text(row.data('rate'));
        $('#mdb-amount').text('₹' + row.data('amount'));
        $('#mdb-date').text(row.data('date'));
        $('#mdb-status').html(
            status == 1
            ? '<span class="badge bg-success" style="font-size:11px;">Active</span>'
            : '<span class="badge bg-secondary" style="font-size:11px;">Inactive</span>'
        );

        new bootstrap.Modal(document.getElementById('directBonusMemberModal')).show();
    });

});
</script>
@endpush
