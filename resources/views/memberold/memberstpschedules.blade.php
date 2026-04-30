@extends('member.layouts.app')

@section('content')

<div class="container-fluid py-3">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Member STP Schedules</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add Schedule
            </button>
        </div>
        <div class="card-body">

            {{-- ✅ FIX: Bulk Delete button is OUTSIDE the main table form --}}
            <div class="mb-2">
                <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                    Bulk Delete
                </button>
            </div>

            {{-- Hidden bulk delete form (no longer wrapping the table) --}}
            <form id="bulkDeleteForm"
                  action="{{ route('member.memberstpschedules.bulkDelete') }}"
                  method="POST"
                  style="display:none;">
                @csrf
                <div id="bulkDeleteInputs"></div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Member ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Running Hrs</th>
                            <th>Per Hrs Amount</th>
                            <th>Per Day Amount</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $schedule)
                        <tr>
                            <td>
                                <input type="checkbox" class="row-checkbox" value="{{ $schedule->id }}">
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $schedule->member_id }}</span>
                                @if(isset($memberInfos[$schedule->member_id]))
                                    <br><small class="text-dark">{{ $memberInfos[$schedule->member_id]->name }}</small>
                                    <br><small class="text-muted">📞 {{ $memberInfos[$schedule->member_id]->phone }}</small>
                                @endif
                            </td>
                            <td>{{ $schedule->start_date }}</td>
                            <td>{{ $schedule->end_date }}</td>
                            <td>{{ $schedule->running_hrs }}</td>
                            <td>{{ $schedule->per_hrs_amount }}</td>
                            <td>{{ $schedule->per_day_amount }}</td>
                            <td>{{ $schedule->total_amount }}</td>
                            <td>
                                {{-- ✅ FIX: standalone form, NOT nested --}}
                                <form action="{{ route('member.memberstpschedules.toggleStatus', $schedule->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $schedule->status == 1 ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $schedule->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>{{ $schedule->created_at }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $schedule->id }}">
                                    Edit
                                </button>

                                {{-- ✅ FIX: Delete uses its own standalone form --}}
                                <form action="{{ route('member.memberstpschedules.delete', $schedule->id) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Are you sure you want to delete?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">No records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- ✅ FIX: Edit modals are OUTSIDE the table entirely --}}
@foreach($schedules as $schedule)
<div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('member.memberstpschedules.update', $schedule->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Member ID (searchable) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Member ID
                                <small class="text-muted">(search by ID / Name / Phone)</small>
                            </label>
                            <div class="member-search-wrapper position-relative">
                                <input type="text"
                                    class="form-control member-search-input"
                                    data-target="edit_member_id_{{ $schedule->id }}"
                                    data-info="edit_member_info_{{ $schedule->id }}"
                                    placeholder="Type to search member..."
                                    value="{{ $schedule->member_id }}"
                                    autocomplete="off">
                                <div class="member-dropdown list-group position-absolute w-100 shadow"
                                    id="edit_member_dropdown_{{ $schedule->id }}"
                                    style="display:none; z-index:9999; max-height:200px; overflow-y:auto;">
                                </div>
                            </div>
                            <input type="hidden"
                                name="member_id"
                                id="edit_member_id_{{ $schedule->id }}"
                                value="{{ $schedule->member_id }}"
                                required>
                            <small id="edit_member_info_{{ $schedule->id }}" class="text-success small">
                                @if(isset($memberInfos[$schedule->member_id]))
                                    ✅ {{ $memberInfos[$schedule->member_id]->name }} | 📞 {{ $memberInfos[$schedule->member_id]->phone }}
                                @endif
                            </small>
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date"
                                name="start_date"
                                id="edit_start_date_{{ $schedule->id }}"
                                class="form-control"
                                value="{{ $schedule->start_date }}"
                                required>
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date"
                                name="end_date"
                                id="edit_end_date_{{ $schedule->id }}"
                                class="form-control"
                                value="{{ $schedule->end_date }}"
                                min="{{ $schedule->start_date }}"
                                required>
                        </div>

                        {{-- Running Hours Checkboxes --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Running Hrs</label>
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input edit-check-all"
                                        type="checkbox"
                                        id="edit_check_all_{{ $schedule->id }}"
                                        data-id="{{ $schedule->id }}">
                                    <label class="form-check-label fw-bold text-primary"
                                        for="edit_check_all_{{ $schedule->id }}">
                                        Select All (24hrs)
                                    </label>
                                </div>
                            </div>
                            <div class="border rounded p-2 bg-light" style="max-height:180px; overflow-y:auto;">
                                <div class="row g-1">
                                    @for($h = 0; $h < 24; $h++)
                                    <div class="col-md-3 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input edit-hr-{{ $schedule->id }}"
                                                type="checkbox"
                                                value="{{ $h }}hrs-{{ $h+1 }}hrs"
                                                id="edit_hr_{{ $schedule->id }}_{{ $h }}"
                                                data-id="{{ $schedule->id }}"
                                                @php
                                                    $slot    = $h.'hrs-'.($h+1).'hrs';
                                                    $saved   = $schedule->running_hrs;
                                                    $checked = ($saved === 'All (24hrs)' || str_contains($saved, $slot));
                                                @endphp
                                                {{ $checked ? 'checked' : '' }}>
                                            <label class="form-check-label small"
                                                for="edit_hr_{{ $schedule->id }}_{{ $h }}">
                                                {{ $h }}hrs - {{ $h+1 }}hrs
                                            </label>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <input type="hidden" name="running_hrs"
                                id="edit_running_hrs_hidden_{{ $schedule->id }}"
                                value="{{ $schedule->running_hrs }}">
                            <small id="edit_selected_hrs_label_{{ $schedule->id }}" class="text-primary small">
                                {{ $schedule->running_hrs }}
                            </small>
                        </div>

                        {{-- Per Hrs Amount --}}
                        <div class="col-md-6">
                            <label class="form-label">Per Hrs Amount (₹)</label>
                            <input type="number"
                                name="per_hrs_amount"
                                id="edit_per_hrs_amount_{{ $schedule->id }}"
                                class="form-control"
                                value="{{ $schedule->per_hrs_amount }}"
                                min="0" step="0.01" required>
                        </div>

                        {{-- Per Day Amount --}}
                        <div class="col-md-6">
                            <label class="form-label">Per Day Amount (₹)
                                <small class="text-success fw-semibold">(auto calculated)</small>
                            </label>
                            <input type="text"
                                name="per_day_amount"
                                id="edit_per_day_amount_{{ $schedule->id }}"
                                class="form-control bg-light fw-bold"
                                value="{{ $schedule->per_day_amount }}"
                                readonly>
                        </div>

                        {{-- Total Amount --}}
                        <div class="col-md-6">
                            <label class="form-label">Total Amount (₹)
                                <small class="text-success fw-semibold">(auto calculated)</small>
                            </label>
                            <input type="text"
                                name="total_amount"
                                id="edit_total_amount_{{ $schedule->id }}"
                                class="form-control bg-light fw-bold"
                                value="{{ $schedule->total_amount }}"
                                readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ $schedule->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $schedule->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
{{-- End Edit Modals --}}

{{-- ===== ADD MODAL ===== --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('member.memberstpschedules.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Member ID (searchable) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Member ID
                                <small class="text-muted">(search by ID / Name / Phone)</small>
                            </label>
                            <div class="member-search-wrapper position-relative">
                                <input type="text"
                                    class="form-control member-search-input"
                                    id="add_member_search"
                                    data-target="add_member_id"
                                    data-info="add_member_info"
                                    placeholder="Type to search member..."
                                    value="{{ session('member_memberID') }}"
                                    autocomplete="off">
                                <div class="member-dropdown list-group position-absolute w-100 shadow"
                                    id="add_member_dropdown"
                                    style="display:none; z-index:9999; max-height:200px; overflow-y:auto;">
                                </div>
                            </div>
                            <input type="hidden" name="member_id" id="add_member_id"
                                value="{{ session('member_memberID') }}" required>
                            <small id="add_member_info" class="text-success small"></small>
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="add_start_date"
                                class="form-control" required>
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="add_end_date"
                                class="form-control" required>
                        </div>

                        {{-- Running Hours Checkboxes --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Running Hrs</label>
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="check_all_hrs">
                                    <label class="form-check-label fw-bold text-primary" for="check_all_hrs">
                                        Select All (24hrs)
                                    </label>
                                </div>
                            </div>
                            <div class="border rounded p-2 bg-light" style="max-height:180px; overflow-y:auto;">
                                <div class="row g-1">
                                    @for($h = 0; $h < 24; $h++)
                                    <div class="col-md-3 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input hr-checkbox"
                                                type="checkbox"
                                                value="{{ $h }}hrs-{{ $h+1 }}hrs"
                                                id="hr_{{ $h }}">
                                            <label class="form-check-label small" for="hr_{{ $h }}">
                                                {{ $h }}hrs - {{ $h+1 }}hrs
                                            </label>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <input type="hidden" name="running_hrs" id="add_running_hrs_hidden">
                            <small class="text-muted" id="selected_hrs_label">No hours selected</small>
                        </div>

                        {{-- Per Hrs Amount --}}
                        <div class="col-md-6">
                            <label class="form-label">Per Hrs Amount (₹)</label>
                            <input type="number" name="per_hrs_amount" id="add_per_hrs_amount"
                                class="form-control" placeholder="Enter per hr amount"
                                min="0" step="0.01" required>
                        </div>

                        {{-- Per Day Amount --}}
                        <div class="col-md-6">
                            <label class="form-label">Per Day Amount (₹)
                                <small class="text-success fw-semibold">(auto calculated)</small>
                            </label>
                            <input type="text" name="per_day_amount" id="add_per_day_amount"
                                class="form-control bg-light fw-bold" readonly placeholder="Auto calculated">
                        </div>

                        {{-- Total Amount --}}
                        <div class="col-md-6">
                            <label class="form-label">Total Amount (₹)
                                <small class="text-success fw-semibold">(auto calculated)</small>
                            </label>
                            <input type="text" name="total_amount" id="add_total_amount"
                                class="form-control bg-light fw-bold" readonly placeholder="Auto calculated">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Add Modal --}}

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ============================================================
    // BULK DELETE
    // ============================================================
    document.getElementById('bulkDeleteBtn').addEventListener('click', function () {
        var checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) {
            alert('Please select at least one record to delete.');
            return;
        }
        if (!confirm('Delete selected records?')) return;

        var form   = document.getElementById('bulkDeleteForm');
        var inputs = document.getElementById('bulkDeleteInputs');
        inputs.innerHTML = '';
        checked.forEach(function (cb) {
            var inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'ids[]';
            inp.value = cb.value;
            inputs.appendChild(inp);
        });
        form.submit();
    });

    // ============================================================
    // SELECT ALL TABLE CHECKBOX
    // ============================================================
    var selectAllBox = document.getElementById('selectAll');
    if (selectAllBox) {
        selectAllBox.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(function (cb) {
                cb.checked = selectAllBox.checked;
            });
        });
    }

    // ============================================================
    // MEMBER SEARCH — shared AJAX helper
    // ============================================================
    var searchUrl  = "{{ route('member.memberstpschedules.searchMember') }}";
    var searchTimers = {};

    function initMemberSearch(inputEl, hiddenId, infoId, dropdownId) {
        if (!inputEl) return;

        var hiddenEl   = document.getElementById(hiddenId);
        var infoEl     = document.getElementById(infoId);
        var dropdownEl = document.getElementById(dropdownId);

        if (!hiddenEl || !infoEl || !dropdownEl) return;

        document.addEventListener('click', function (e) {
            if (!inputEl.contains(e.target) && !dropdownEl.contains(e.target)) {
                dropdownEl.style.display = 'none';
            }
        });

        inputEl.addEventListener('input', function () {
            var q = this.value.trim();

            hiddenEl.value     = '';
            infoEl.textContent = '';
            infoEl.className   = 'text-muted small';

            dropdownEl.innerHTML     = '';
            dropdownEl.style.display = 'none';

            if (q.length < 1) return;

            clearTimeout(searchTimers[dropdownId]);
            searchTimers[dropdownId] = setTimeout(function () {
                dropdownEl.innerHTML     = '<div class="list-group-item text-muted small">Searching…</div>';
                dropdownEl.style.display = 'block';

                fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    dropdownEl.innerHTML = '';

                    if (!data.length) {
                        dropdownEl.innerHTML     = '<div class="list-group-item text-danger small">No member found</div>';
                        dropdownEl.style.display = 'block';
                        return;
                    }

                    data.forEach(function (m) {
                        var item = document.createElement('button');
                        item.type      = 'button';
                        item.className = 'list-group-item list-group-item-action py-1 px-2 small';
                        item.innerHTML =
                            '<span class="fw-bold text-primary">' + escHtml(m.memberID) + '</span>' +
                            ' — ' + escHtml(m.name) +
                            ' <span class="text-muted">(' + escHtml(m.phone) + ')</span>';

                        item.addEventListener('click', function () {
                            inputEl.value            = m.memberID;
                            hiddenEl.value           = m.memberID;
                            infoEl.textContent       = '✅ ' + m.name + ' | ' + m.phone;
                            infoEl.className         = 'text-success small';
                            dropdownEl.style.display = 'none';
                        });

                        dropdownEl.appendChild(item);
                    });

                    dropdownEl.style.display = 'block';
                })
                .catch(function () {
                    dropdownEl.innerHTML     = '<div class="list-group-item text-danger small">Search error</div>';
                    dropdownEl.style.display = 'block';
                });
            }, 300);
        });

        inputEl.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') dropdownEl.style.display = 'none';
        });
    }

    function escHtml(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    // ── Wire ADD modal member search ──────────────────────────────────
    initMemberSearch(
        document.getElementById('add_member_search'),
        'add_member_id',
        'add_member_info',
        'add_member_dropdown'
    );

    // ── Wire EDIT modal member searches ──────────────────────────────
    document.querySelectorAll('.member-search-input').forEach(function (inputEl) {
        var targetId = inputEl.getAttribute('data-target');
        var infoId   = inputEl.getAttribute('data-info');
        if (!targetId || targetId === 'add_member_id') return;

        var wrapper    = inputEl.closest('.member-search-wrapper');
        var dropdownEl = wrapper ? wrapper.querySelector('.member-dropdown') : null;
        if (!dropdownEl) return;

        initMemberSearch(inputEl, targetId, infoId, dropdownEl.id);
    });

    // ============================================================
    // HELPER: Days between two date strings (inclusive)
    // ============================================================
    function daysBetween(startStr, endStr) {
        if (!startStr || !endStr) return 0;
        var s    = new Date(startStr);
        var e    = new Date(endStr);
        var diff = Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1;
        return diff > 0 ? diff : 0;
    }

    // ============================================================
    // ADD MODAL — DATE CHANGE LISTENERS
    // ============================================================
    var addStartDate = document.getElementById('add_start_date');
    var addEndDate   = document.getElementById('add_end_date');

    addStartDate.addEventListener('change', function () {
        addEndDate.min = this.value;
        if (addEndDate.value && addEndDate.value < this.value) {
            addEndDate.value = '';
        }
        addRecalculate();
    });

    addEndDate.addEventListener('change', function () {
        addRecalculate();
    });

    // ============================================================
    // ADD MODAL — CHECKBOXES
    // ============================================================
    var checkAll     = document.getElementById('check_all_hrs');
    var hrCheckboxes = document.querySelectorAll('.hr-checkbox');

    checkAll.addEventListener('change', function () {
        hrCheckboxes.forEach(function (cb) { cb.checked = checkAll.checked; });
        updateAddHidden();
        addRecalculate();
    });

    hrCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            var allChecked  = Array.from(hrCheckboxes).every(c => c.checked);
            var someChecked = Array.from(hrCheckboxes).some(c => c.checked);
            checkAll.checked       = allChecked;
            checkAll.indeterminate = !allChecked && someChecked;
            updateAddHidden();
            addRecalculate();
        });
    });

    function updateAddHidden() {
        var selected    = Array.from(hrCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        var hiddenField = document.getElementById('add_running_hrs_hidden');
        var label       = document.getElementById('selected_hrs_label');

        if (selected.length === 0) {
            hiddenField.value = '';
            label.textContent = 'No hours selected';
            label.className   = 'text-danger small';
        } else if (selected.length === 24) {
            hiddenField.value = 'All (24hrs)';
            label.textContent = '✅ All 24 hours selected';
            label.className   = 'text-success small fw-semibold';
        } else {
            hiddenField.value = selected.join(', ');
            label.textContent = '✅ ' + selected.length + ' hour(s) selected';
            label.className   = 'text-primary small';
        }
    }

    // ============================================================
    // ADD MODAL — RECALCULATE
    // ============================================================
    function addRecalculate() {
        var selectedCount = Array.from(hrCheckboxes).filter(cb => cb.checked).length;
        var perHrs        = parseFloat(document.getElementById('add_per_hrs_amount').value) || 0;
        var perDay        = selectedCount * perHrs;

        document.getElementById('add_per_day_amount').value = perDay > 0
            ? perDay.toFixed(2) : '';

        var startVal = document.getElementById('add_start_date').value;
        var endVal   = document.getElementById('add_end_date').value;
        var days     = daysBetween(startVal, endVal);

        document.getElementById('add_total_amount').value = (perDay > 0 && days > 0)
            ? (perDay * days).toFixed(2) : '';
    }

    document.getElementById('add_per_hrs_amount').addEventListener('input', addRecalculate);

    // ============================================================
    // ADD MODAL — RESET ON CLOSE
    // ============================================================
    document.getElementById('addModal').addEventListener('hidden.bs.modal', function () {
        addStartDate.value = '';
        addEndDate.value   = '';
        addEndDate.min     = '';
        hrCheckboxes.forEach(cb => cb.checked = false);
        checkAll.checked       = false;
        checkAll.indeterminate = false;
        document.getElementById('add_running_hrs_hidden').value   = '';
        document.getElementById('add_per_hrs_amount').value       = '';
        document.getElementById('add_per_day_amount').value       = '';
        document.getElementById('add_total_amount').value         = '';
        document.getElementById('selected_hrs_label').textContent = 'No hours selected';
        document.getElementById('selected_hrs_label').className   = 'text-muted small';
        document.getElementById('add_member_search').value        = '';
        document.getElementById('add_member_id').value            = '';
        document.getElementById('add_member_info').textContent    = '';
        document.getElementById('add_member_dropdown').style.display = 'none';
    });

    // ============================================================
    // EDIT MODALS — DATE + CHECKBOXES + CALCULATE
    // ============================================================
    @foreach($schedules as $schedule)
    (function () {
        var sid    = {{ $schedule->id }};
        var eStart = document.getElementById('edit_start_date_' + sid);
        var eEnd   = document.getElementById('edit_end_date_'   + sid);

        if (!eStart || !eEnd) return;

        eStart.addEventListener('change', function () {
            eEnd.min = this.value;
            if (eEnd.value && eEnd.value < this.value) { eEnd.value = ''; }
            editRecalculate(sid);
        });

        eEnd.addEventListener('change', function () { editRecalculate(sid); });

        var editCheckAll = document.getElementById('edit_check_all_' + sid);
        var editCbs      = document.querySelectorAll('.edit-hr-' + sid);

        if (editCheckAll) {
            var allSaved = Array.from(editCbs).every(c => c.checked);
            editCheckAll.checked       = allSaved;
            editCheckAll.indeterminate = !allSaved && Array.from(editCbs).some(c => c.checked);

            editCheckAll.addEventListener('change', function () {
                editCbs.forEach(function (cb) { cb.checked = editCheckAll.checked; });
                updateEditHidden(sid);
                editRecalculate(sid);
            });
        }

        editCbs.forEach(function (cb) {
            cb.addEventListener('change', function () {
                var allC  = Array.from(editCbs).every(c => c.checked);
                var someC = Array.from(editCbs).some(c => c.checked);
                if (editCheckAll) {
                    editCheckAll.checked       = allC;
                    editCheckAll.indeterminate = !allC && someC;
                }
                updateEditHidden(sid);
                editRecalculate(sid);
            });
        });

        var perHrsInput = document.getElementById('edit_per_hrs_amount_' + sid);
        if (perHrsInput) {
            perHrsInput.addEventListener('input', function () { editRecalculate(sid); });
        }
    })();
    @endforeach

    // ============================================================
    // EDIT — UPDATE HIDDEN FIELD & LABEL
    // ============================================================
    function updateEditHidden(sid) {
        var editCbs  = document.querySelectorAll('.edit-hr-' + sid);
        var selected = Array.from(editCbs).filter(cb => cb.checked).map(cb => cb.value);
        var hidden   = document.getElementById('edit_running_hrs_hidden_' + sid);
        var label    = document.getElementById('edit_selected_hrs_label_' + sid);

        if (selected.length === 0) {
            hidden.value      = '';
            label.textContent = 'No hours selected';
            label.className   = 'text-danger small';
        } else if (selected.length === 24) {
            hidden.value      = 'All (24hrs)';
            label.textContent = '✅ All 24 hours selected';
            label.className   = 'text-success small fw-semibold';
        } else {
            hidden.value      = selected.join(', ');
            label.textContent = '✅ ' + selected.length + ' hour(s) selected';
            label.className   = 'text-primary small';
        }
    }

    // ============================================================
    // EDIT — RECALCULATE
    // ============================================================
    function editRecalculate(sid) {
        var editCbs       = document.querySelectorAll('.edit-hr-' + sid);
        var selectedCount = Array.from(editCbs).filter(cb => cb.checked).length;
        var perHrs        = parseFloat(document.getElementById('edit_per_hrs_amount_' + sid).value) || 0;
        var perDay        = selectedCount * perHrs;

        document.getElementById('edit_per_day_amount_' + sid).value = perDay > 0
            ? perDay.toFixed(2) : '';

        var startVal = document.getElementById('edit_start_date_' + sid).value;
        var endVal   = document.getElementById('edit_end_date_'   + sid).value;
        var days     = daysBetween(startVal, endVal);

        document.getElementById('edit_total_amount_' + sid).value = (perDay > 0 && days > 0)
            ? (perDay * days).toFixed(2) : '';
    }

});
</script>
@endpush
