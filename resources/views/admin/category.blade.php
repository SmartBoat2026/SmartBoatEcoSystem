@extends('admin.layouts.app')
@section('content')

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Manage Category</h1>
            <p>Manage and track all your Category & Sub-category in one place</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> New Category
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            {{-- ── BULK DELETE BAR (hidden until rows selected) ── --}}
            <div id="bulkActionBar"
                 style="display:none;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;
                        padding:10px 16px;margin-bottom:12px;align-items:center;gap:12px;">
                <span id="selectedCount" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                <form id="bulkDeleteForm" method="POST" action="{{ route('category.bulkDelete') }}" style="display:inline;">
                    @csrf
                    <div id="bulkDeleteIds"></div>
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete selected categories? This cannot be undone.')">
                        <i class="bi bi-trash me-1"></i>Delete Selected
                    </button>
                </form>
                <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x me-1"></i>Clear Selection
                </button>
            </div>

            <table id="categoryTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        {{-- ── SELECT ALL checkbox ── --}}
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="selectAll" style="cursor:pointer;width:15px;height:15px;">
                        </th>
                        <th style="width:50px">#</th>
                        <th>Category Name</th>
                        <th>Subcategory</th>
                        <th style="width:120px">Status</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                    <tr>
                        {{-- ── ROW checkbox ── --}}
                        <td class="text-center">
                            <input type="checkbox" class="row-checkbox" value="{{ $row['id'] }}"
                                   style="cursor:pointer;width:15px;height:15px;">
                        </td>
                        <td>{{ $row['serial'] }}</td>
                        <td>{{ $row['category'] }}</td>
                        <td>
                            @if($row['subcategory'] === '-')
                                <span class="text-muted fst-italic">-</span>
                            @else
                                {{ $row['subcategory'] }}
                            @endif
                        </td>
                        <td>
                            <button
                                class="btn btn-sm toggle-status {{ $row['status'] == 1 ? 'btn-success' : 'btn-danger' }}"
                                data-id="{{ $row['id'] }}">
                                {{ $row['status'] == 1 ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning px-2 py-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="{{ $row['id'] }}"
                                    data-name="{{ $row['subcategory'] === '-' ? $row['category'] : $row['subcategory'] }}"
                                    data-parent="{{ $row['parent_id'] ?? '' }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="{{ route('category.delete', $row['id']) }}"
                                class="btn btn-sm btn-danger px-2 py-1"
                                onclick="return confirm('Delete this record?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ===================== ADD MODAL ===================== --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('category.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category / Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Type</label>
                            <select id="add_type" name="type" class="form-select">
                                <option value="category">Category</option>
                                @if($parentOptions->count() > 0)
                                    <option value="subcategory">Subcategory</option>
                                @endif
                            </select>
                            @if($parentOptions->count() === 0)
                                <div class="form-text text-warning">
                                    <i class="bi bi-info-circle"></i>
                                    No categories exist yet. Please create a <strong>Category</strong> first.
                                </div>
                            @endif
                        </div>

                        @if($parentOptions->count() > 0)
                        <div class="mb-3" id="add_parent_wrap" style="display:none;">
                            <label class="form-label fw-bold">Parent Category <span class="text-danger">*</span></label>
                            <select name="parent_id" id="add_parent_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach($parentOptions as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- ===================== EDIT MODAL ===================== --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" id="editForm" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Type</label>
                            <select id="edit_type" class="form-select">
                                <option value="category">Category</option>
                                @if($parentOptions->count() > 0)
                                    <option value="subcategory">Subcategory</option>
                                @endif
                            </select>
                        </div>

                        @if($parentOptions->count() > 0)
                        <div class="mb-3" id="edit_parent_wrap" style="display:none;">
                            <label class="form-label fw-bold">Parent Category <span class="text-danger">*</span></label>
                            <select name="parent_id" id="edit_parent_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach($parentOptions as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</main>



@push('scripts')

<script>
    $(document).ready(function () {

        // ── DataTable ──
        $('#categoryTable').DataTable({
            pageLength: 10,
            ordering:   true,
            searching:  true,
            responsive: true,
            columnDefs: [
                { orderable: false, searchable: false, targets: [0, 3, 4, 5] }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                    className: 'buttons-excel',
                    title: 'Manage Categories',
                    exportOptions: { columns: [1, 2, 3, 4] }   // skip checkbox & action cols
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                    className: 'buttons-pdf',
                    title: 'Manage Categories',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { columns: [1, 2, 3, 4] }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer me-1"></i>Print',
                    className: 'buttons-print',
                    title: 'Manage Categories',
                    exportOptions: { columns: [1, 2, 3, 4] }
                }
            ],
            language: {
                search:     "🔍 Search:",
                lengthMenu: "Show _MENU_ records",
                info:       "Showing _START_ to _END_ of _TOTAL_ records",
                paginate:   { previous: "← Prev", next: "Next →" }
            },
            dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
        });

        // ════════════════════════════════════════════════════════════
        // BULK DELETE — checkbox logic
        // ════════════════════════════════════════════════════════════
        function updateBulkBar() {
            const checked = $('.row-checkbox:checked');
            const count   = checked.length;

            if (count > 0) {
                $('#bulkActionBar').addClass('show');
                $('#selectedCount').text(count + ' selected');
                let inputs = '';
                checked.each(function () {
                    inputs += `<input type="hidden" name="ids[]" value="${$(this).val()}">`;
                });
                $('#bulkDeleteIds').html(inputs);
            } else {
                $('#bulkActionBar').removeClass('show');
                $('#bulkDeleteIds').html('');
            }
        }

        // Select All — works across ALL DataTable pages
        $('#selectAll').on('change', function () {
            const isChecked = $(this).prop('checked');
            $('.row-checkbox').prop('checked', isChecked);
            $('#categoryTable tbody tr').toggleClass('row-selected', isChecked);
            updateBulkBar();
        });

        // Individual row checkbox
        $(document).on('change', '.row-checkbox', function () {
            $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));
            const total   = $('.row-checkbox').length;
            const checked = $('.row-checkbox:checked').length;
            $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
            $('#selectAll').prop('checked', checked === total);
            updateBulkBar();
        });

        // Clear selection
        $('#clearSelection').on('click', function () {
            $('.row-checkbox').prop('checked', false);
            $('#categoryTable tbody tr').removeClass('row-selected');
            $('#selectAll').prop('checked', false).prop('indeterminate', false);
            updateBulkBar();
        });

        // ── ADD MODAL: Type toggle ──
        $('#add_type').on('change', function () {
            if ($(this).val() === 'subcategory') {
                $('#add_parent_wrap').show();
                $('#add_parent_id').attr('required', true);
            } else {
                $('#add_parent_wrap').hide();
                $('#add_parent_id').removeAttr('required').val('');
            }
        });

        // ── EDIT MODAL: Populate on open ──
        $('#editModal').on('show.bs.modal', function (e) {
            const btn      = $(e.relatedTarget);
            const id       = btn.data('id');
            const name     = btn.data('name');
            const parentId = btn.data('parent');

            let url = "{{ url('category/update') }}/" + id;
            $('#editForm').attr('action', url);
            $('#edit_name').val(name);

            if (parentId) {
                $('#edit_type').val('subcategory');
                $('#edit_parent_wrap').show();
                $('#edit_parent_id').attr('required', true).val(parentId);
            } else {
                $('#edit_type').val('category');
                $('#edit_parent_wrap').hide();
                $('#edit_parent_id').removeAttr('required').val('');
            }
        });

        // ── EDIT MODAL: Type toggle ──
        $('#edit_type').on('change', function () {
            if ($(this).val() === 'subcategory') {
                $('#edit_parent_wrap').show();
                $('#edit_parent_id').attr('required', true);
            } else {
                $('#edit_parent_wrap').hide();
                $('#edit_parent_id').removeAttr('required').val('');
            }
        });

        // ── TOGGLE STATUS — AJAX ──
        $(document).on('click', '.toggle-status', function () {
            const btn = $(this);
            const id  = btn.data('id');

            $.ajax({
            url: "{{ route('category.toggleStatus', ':id') }}".replace(':id', id),
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.status == 1) {
                        btn.removeClass('btn-danger').addClass('btn-success').text('Active');
                    } else {
                        btn.removeClass('btn-success').addClass('btn-danger').text('Inactive');
                    }
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        });

    });
</script>
@endpush

@endsection
