@extends('admin.layouts.app')
@section('content')

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Member Activation Requests</h1>
            <p>Manage and track all your member activation requests in one place</p>
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

            <table id="memberTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>

                        <th style="width:50px">#</th>
                        <th>Member ID</th>
                        <th>Member Info</th>
                        <th>Sponsor ID</th>
                        <th>Amount</th>
                        <th>Upload File</th>
                        <th>UTR No</th>
                        <th>Message</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $key => $row)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $row->memberID }}</td>

                            <td>
                                <div class="fw-semibold">{{ $row->name }}</div>
                                <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $row->email ?: '—' }}</div>
                                <div class="text-muted small"><i class="bi bi-phone me-1"></i>{{ $row->phone ?: '—' }}</div>
                            </td>

                            <td>{{ $row->sponser_id }}</td>
                            <td>{{ $row->amount }}</td>
                            <!--<td><img src="{{ asset('public/storage/'.$row->verification_payment_screenshot) }}" width="200"></td>-->
                            <td>
                                @if(!empty($row->verification_payment_screenshot) && file_exists(public_path('storage/'.$row->verification_payment_screenshot)))
                                    <img src="{{ asset('public/storage/'.$row->verification_payment_screenshot) }}" width="100"
                                    style="cursor:pointer"  onclick="openImageModal('{{ asset('public/storage/'.$row->verification_payment_screenshot) }}')">
                                @endif
                            </td>
                            <td>{{ $row->payment_utr_no }}</td>
                            <td>{{ $row->verification_message }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <button
                                        class="btn btn-sm toggle-status btn-success"
                                        data-id="{{ $row->member_id }}"
                                        data-status="1">
                                        Approve
                                    </button>
                                    <button
                                        class="btn btn-sm toggle-status btn-danger"
                                        data-id="{{ $row->member_id }}"
                                        data-status="3">
                                        Reject
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    {{--  Verification Image Zoom In Popup --}}
    <div id="imageModal" style="display:none; position:fixed; z-index:9999; padding-top:50px; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8);">
        <span onclick="closeModal()" style="position:absolute; top:20px; right:35px; color:#fff; font-size:40px; cursor:pointer;">&times;</span>

        <img id="modalImage" style="display:block; margin:auto; max-width:90%; max-height:80%;">
    </div>

</main>



@push('scripts')


<script>
$(document).on('click', '.toggle-status', function () {
    const btn = $(this);
    const id = btn.data('id');
    const status = btn.data('status'); // 👈 NEW VALUE

    $.ajax({
        url: "{{ route('managereport.toggleStatus', ':id') }}".replace(':id', id),
        type: 'POST',
        data: {
            status: status, // 👈 send to backend
            _token: '{{ csrf_token() }}'
        },
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function () {
            alert('Something went wrong.');
        }
    });
});
</script>

<script>
function openImageModal(src) {
    document.getElementById("imageModal").style.display = "block";
    document.getElementById("modalImage").src = src;
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>

<script>
$(document).ready(function () {

    let table = $('#memberTable').DataTable({
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

@endpush
@endsection
