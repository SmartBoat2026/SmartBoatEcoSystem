@extends('admin.layouts.app')
@section('content')

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Smart Wallet Balance</h1>
            <p>Manage and track all your smart wallet balance in one place</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> Add Wallet Balance
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table id="transitionTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>

                        <th style="width:50px">#</th>
                        <th>Member ID</th>
                        <th>Member Info</th>
                        <th>Smart Wallet Balance</th>
                        <th>Type</th>
                        <th>Joining Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $key => $row)
                        <tr>

                            <td>{{ $key + 1 }}</td>
                            <td>{{ $row->member_id }}</td>

                            <td>
                                <div class="fw-semibold">{{ $row->name }}</div>
                                <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $row->email ?: '—' }}</div>
                                <div class="text-muted small"><i class="bi bi-phone me-1"></i>{{ $row->phone ?: '—' }}</div>
                            </td>

                            <td>{{ $row->amount }}</td>
                            <td>{{ $row->type }}</td>
                            <td>{{ $row->created_at }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No Data Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('smartwallet.store') }}" id="addMemberForm">
                @csrf
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus me-2"></i>Add Smart Wallet Balance
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- ── Member ID (Live Search) ── --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Member ID <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="sponsorSearch"
                                   class="form-control mb-1"
                                   placeholder="Search by ID, Name, Email or Phone…"
                                   autocomplete="off" >

                            <input type="hidden" name="sponser_id"   id="selectedSponsorId"   value="M000001">
                            <input type="hidden" name="sponser_name" id="selectedSponsorName" value="">

                            <div id="sponsorResults"
                                 class="border rounded shadow-sm bg-white"
                                 style="display:none; max-height:200px; overflow-y:auto; position:relative; z-index:9999;">
                            </div>

                            <div id="selectedSponsorBadge" class="mt-1" style="display:none;">
                                <span class="badge bg-success fs-6 py-2 px-3" id="selectedSponsorLabel"></span>
                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary ms-1"
                                        id="clearSponsor">✕ Clear</button>
                            </div>
                            <div class="form-text text-muted">
                                Default: <code>M000001</code> if left blank.
                            </div>
                        </div>

                        {{-- ── Smart Wallet Balance ── --}}
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                Smart Wallet Balance <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="amount" name="amount" class="form-control" placeholder="Eg: 10000" required>
                        </div>

                        @error('terms')
                            <div class="alert alert-danger py-1 small">{{ $message }}</div>
                        @enderror

                    </div>{{-- /.modal-body --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitMemberBtn" disabled>
                            <i class="bi bi-person-plus me-1"></i> Add Smart Wallet Balance
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</main>



@push('scripts')


<script>
$(document).ready(function () {

    function checkForm() {
        let sponsorName = $('#selectedSponsorName').val().trim();
        let amount = $('#amount').val().trim();

        if (sponsorName !== '' && amount !== '' && parseFloat(amount) > 0) {
            $('#submitMemberBtn').prop('disabled', false); // enable
        } else {
            $('#submitMemberBtn').prop('disabled', true); // disable
        }
    }

    // Amount input change
    $('#amount').on('keyup change', function () {
        checkForm();
    });

    // Clear sponsor
    $('#clearSponsor').click(function () {
        $('#selectedSponsorNamw').val('');
        checkForm();
    });

    // Page load par check
    checkForm();

});
</script>

<script>
$(document).ready(function () {

    let table = $('#transitionTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        responsive: true,

        // ✅ Correct column handling
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, -1] } // # and Action
        ],

        // ✅ Buttons
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",

        buttons: [
            {
                extend: 'excelHtml5',
                text: '📊 Excel',
                className: 'buttons-excel',
                title: 'Member Report',
                exportOptions: {
                    columns: ':not(:first-child):not(:last-child)' // auto exclude # & Action
                }
            },
            {
                extend: 'pdfHtml5',
                text: '📄 PDF',
                className: 'buttons-pdf',
                title: 'Member Report',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':not(:first-child):not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '🖨 Print',
                className: 'buttons-print',
                title: 'Member Report',
                exportOptions: {
                    columns: ':not(:first-child):not(:last-child)'
                }
            }
        ],

        // ✅ UI Text
        language: {
            search: "🔍 Search:",
            emptyTable: "No Data Found",
            lengthMenu: "Show _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ members",
            paginate: {
                previous: "← Prev",
                next: "Next →"
            }
        }
    });

});
</script>

{{-- ── Success SweetAlert after registration ── --}}
@if(session('new_memberID'))
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var memberName   = @json(session('new_name'));
        var memberID     = @json(session('new_memberID'));
        var Amount   = @json(session('amount'));

        var html = '<div style="text-align:left; background:#f8f9fa; padding:16px 20px;' +
                   'border-radius:10px; margin-top:10px; font-size:15px; line-height:2.8;">';

        html += '<b style="color:#6c757d;">Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>: <span style="font-weight:600;">' + memberName + '</span><br>';
        html += '<b style="color:#6c757d;">Member ID&nbsp;</b>: <span style="color:#0d6efd; font-weight:700; font-size:18px;">' + memberID + '</span><br>';
        html += '<b style="color:#6c757d;">Smart Wallet Balance&nbsp;&nbsp;</b>: <span style="color:#fd7e14; font-weight:700; font-size:18px;">' + Amount + '</span><br>';

        html += '</div>';

        Swal.fire({
            icon: 'success',
            title: 'Smart Waller Balance Added Successful! 🎉',
            html: html,
            confirmButtonText: '✅ OK, Got it!',
            confirmButtonColor: '#0d6efd',
            allowOutsideClick: false,
        });
    });
</script>
@endif

{{-- ── Sponsor Live Search JS ── --}}
<script>
(function () {
    const searchInput = document.getElementById('sponsorSearch');
    const resultsBox  = document.getElementById('sponsorResults');
    const hiddenId    = document.getElementById('selectedSponsorId');
    const hiddenName  = document.getElementById('selectedSponsorName');
    const badge       = document.getElementById('selectedSponsorBadge');
    const badgeLabel  = document.getElementById('selectedSponsorLabel');
    const clearBtn    = document.getElementById('clearSponsor');
    const submitBtn   = document.getElementById('submitMemberBtn')

    // Live search with 300ms debounce
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();

        if (q.length < 2) {
            resultsBox.style.display = 'none';
            resultsBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('managereport.member-search') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (!data.length) {
                        resultsBox.innerHTML =
                            '<div class="px-3 py-2 text-muted small">No matches found.</div>';
                        resultsBox.style.display = 'block';
                        return;
                    }
                    data.forEach(member => {
                        const item = document.createElement('div');
                        item.className = 'px-3 py-2 border-bottom sponsor-item';
                        item.style.cursor = 'pointer';
                        item.innerHTML =
                            `<strong>${member.memberID}</strong> — ${member.name}` +
                            `<br><small class="text-muted">` +
                            `${member.email || ''} ${member.phone ? '· ' + member.phone : ''}` +
                            `</small>`;

                        item.addEventListener('mouseenter', () => item.style.background = '#e9f3ff');
                        item.addEventListener('mouseleave', () => item.style.background = '');
                        item.addEventListener('click', () => {
                            hiddenId.value   = member.memberID;
                            hiddenName.value = member.name;
                            badgeLabel.textContent = `✔ ${member.memberID} — ${member.name}`;
                            badge.style.display    = 'inline-block';
                            searchInput.value        = '';
                            resultsBox.style.display = 'none';
                            resultsBox.innerHTML     = '';
                        });
                        resultsBox.appendChild(item);
                    });
                    resultsBox.style.display = 'block';
                })
                .catch(() => {
                    resultsBox.innerHTML =
                        '<div class="px-3 py-2 text-danger small">Search failed. Try again.</div>';
                    resultsBox.style.display = 'block';
                });
        }, 300);
    });

    // Clear sponsor
    clearBtn.addEventListener('click', () => {
        hiddenId.value         = 'M000001';
        hiddenName.value       = '';
        badge.style.display    = 'none';
        badgeLabel.textContent = '';
        searchInput.value      = '';
    });

    // Close dropdown on outside click
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.style.display = 'none';
        }
    });

    // Reset entire modal on close
    document.getElementById('addModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('addMemberForm').reset();
        hiddenId.value           = 'M000001';
        hiddenName.value         = '';
        badge.style.display      = 'none';
        badgeLabel.textContent   = '';
        resultsBox.style.display = 'none';
        submitBtn.disabled       = true;
        // Reset phone hidden field
        document.getElementById('addPhoneHidden').value = '';
    });
})();
</script>
@endpush

@endsection
