@extends('admin.layouts.app')
@section('content')

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Direct Bonus</h1>
            <p>All direct bonuses credited to sponsors from product purchases</p>
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

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <div id="bulkActionBarDirect"
                 style="display:none;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;
                        padding:10px 16px;margin-bottom:12px;align-items:center;gap:12px;">
                <span id="selectedCountDirect" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                <span style="font-size:13px;color:#856404;">rows selected — export or view only</span>
                <button type="button" id="clearSelectionDirect" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x me-1"></i>Clear Selection
                </button>
            </div>

            <table id="directBonusAdminTable"
                   class="table table-bordered table-hover align-middle mb-0"
                   style="font-size:13px;width:100%;">
                <thead class="table-primary">
                    <tr>
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="selectAllDirect" style="cursor:pointer;width:15px;height:15px;">
                        </th>
                        <th style="width:46px;">#</th>
                        <th>Sponsor (Member ID)</th>
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
                        data-sponsor="{{ $bonus->member_id }}"
                        data-qty="{{ $bonus->total_quantity }}"
                        data-rate="{{ $bonus->rate }}"
                        data-amount="{{ number_format($bonus->bonus_amount, 2) }}"
                        data-status="{{ $bonus->status }}"
                        data-date="{{ \Carbon\Carbon::parse($bonus->created_at)->format('d M Y, h:i A') }}">
                        <td class="text-center">
                            <input type="checkbox" class="row-checkbox-direct" value="{{ $bonus->id }}"
                                   style="cursor:pointer;width:15px;height:15px;"
                                   onclick="event.stopPropagation();">
                        </td>
                        <td class="text-center text-muted" style="font-size:12px;">{{ $loop->iteration }}</td>
                        <td style="font-weight:600;color:#1a3a6b;">{{ $bonus->member_id }}</td>
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
                        <td colspan="9" class="text-center py-5" style="color:#6c757d;font-size:13px;">
                            <i class="bi bi-cash-coin" style="font-size:30px;display:block;margin-bottom:10px;color:#dee2e6;"></i>
                            No direct bonus records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="directBonusDetailModal" tabindex="-1" aria-hidden="true">
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
                        <div class="col-12">
                            <div class="detail-field-label">Sponsor (Member ID)</div>
                            <div id="dd-sponsor" class="detail-field-value" style="color:#1a3a6b;font-weight:700;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Bonus Type</div>
                            <div id="dd-type" class="detail-field-value" style="color:#1a3a6b;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Status</div>
                            <div id="dd-status"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Purchase Amount (₹)</div>
                            <div id="dd-qty" class="detail-field-value" style="color:#333;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Rate (%)</div>
                            <div id="dd-rate" class="detail-field-value" style="color:#1a3a6b;"></div>
                        </div>
                        <div class="col-12">
                            <div style="background:#f4f7fb;border-left:4px solid #27500a;border-radius:0 6px 6px 0;padding:14px 18px;">
                                <div class="detail-field-label">Bonus Amount</div>
                                <div id="dd-amount" style="font-size:28px;font-weight:800;color:#27500a;"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-field-label">Date &amp; Time</div>
                            <div id="dd-date" style="font-size:13px;font-weight:500;color:#333;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</main>

@push('scripts')
<script>
$(document).ready(function () {

    @if($bonuses->count())
    $('#directBonusAdminTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        ordering:  true,
        searching: true,
        responsive: true,
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, 1] }
        ],
        rowCallback: function (row, data, index) {
            $('td:eq(1)', row).text(index + 1);
        },
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className: 'buttons-excel',
                title: 'Direct Bonus History',
                exportOptions: { columns: [1,2,3,4,5,6,7,8] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className: 'buttons-pdf',
                title: 'Direct Bonus History',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1,2,3,4,5,6,7,8] }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i>Print',
                className: 'buttons-print',
                title: 'Direct Bonus History',
                exportOptions: { columns: [1,2,3,4,5,6,7,8] }
            }
        ],
        language: {
            search: '🔍 Search:',
            searchPlaceholder: 'Search…',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty: 'No records found',
            paginate: { previous: '← Prev', next: 'Next →' }
        },
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });
    @endif

    function updateBulkBarDirect() {
        const checked = $('.row-checkbox-direct:checked');
        const count   = checked.length;
        if (count > 0) {
            $('#bulkActionBarDirect').css('display', 'flex');
            $('#selectedCountDirect').text(count);
        } else {
            $('#bulkActionBarDirect').css('display', 'none');
            $('#selectedCountDirect').text('0');
        }
    }

    $('#selectAllDirect').on('change', function () {
        const isChecked = $(this).prop('checked');
        $('.row-checkbox-direct').prop('checked', isChecked);
        $('#directBonusAdminTable tbody tr').toggleClass('row-selected', isChecked);
        updateBulkBarDirect();
    });

    $(document).on('change', '.row-checkbox-direct', function () {
        $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));
        const total   = $('.row-checkbox-direct').length;
        const checked = $('.row-checkbox-direct:checked').length;
        $('#selectAllDirect').prop('indeterminate', checked > 0 && checked < total);
        $('#selectAllDirect').prop('checked', checked === total);
        updateBulkBarDirect();
    });

    $('#clearSelectionDirect').on('click', function () {
        $('.row-checkbox-direct').prop('checked', false);
        $('#directBonusAdminTable tbody tr').removeClass('row-selected');
        $('#selectAllDirect').prop('checked', false).prop('indeterminate', false);
        updateBulkBarDirect();
    });

    $(document).on('click', '#directBonusAdminTable tbody .bonus-row-direct', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return;

        var row    = $(this);
        var status = row.data('status');

        $('#dd-sponsor').text(row.data('sponsor'));
        $('#dd-type').text(row.data('type'));
        $('#dd-qty').text(row.data('qty'));
        $('#dd-rate').text(row.data('rate'));
        $('#dd-amount').text('₹' + row.data('amount'));
        $('#dd-date').text(row.data('date'));
        $('#dd-status').html(
            status == 1
            ? '<span class="badge bg-success" style="font-size:11px;">Active</span>'
            : '<span class="badge bg-secondary" style="font-size:11px;">Inactive</span>'
        );

        new bootstrap.Modal(document.getElementById('directBonusDetailModal')).show();
    });

});
</script>
@endpush
@endsection
