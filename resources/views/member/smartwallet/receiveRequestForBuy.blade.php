@extends('member.layouts.app')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header">
        <div class="page-title">
            <h1>Receive Request For Buy List</h1>
            <p>Record and track all Receive Request For Buy</p>
        </div>
    </div>


    {{-- ===== Sell Wallet History ===== --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Receive Request For Buy List </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">                

                <table id="receiveRfbHistoryTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>RFB ID</th>
                            <th>Requested Wallet Balance</th>
                            <th>RFB Date &amp; Time</th>
                            <th>Total No of Sellers</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

@endsection

@push('scripts')

<script>
$(document).ready(function () {   
    
    
    $('#receiveRfbHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('member.smartwallet.buySell.receiveRfbListData') }}",
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'rfb_id' },
            { data: 'amount' },
            { data: 'created_at' },
            { data: 'no_of_sellers' },
            { data: 'status' },
            { data: 'actions' }
        ],
        order: [[3, 'desc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,6] }
        ],
        
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Request For Buy History',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {
                            return $(node).text().trim();
                        }
                    }
                }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'Request For Buy History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {
                            return $(node).text().trim();
                        }
                    }
                }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'Request For Buy History',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {
                            return $('<div>').html(data).text().trim();
                        }
                    }
                }
            }
        ],
        language:{
            search:'<i class="bi bi-search"></i>',
            searchPlaceholder:'Search requests…',
            lengthMenu:'Show _MENU_ entries',
            info:'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty:'No records found',
            paginate:{ previous:'‹', next:'›' }
        },
        dom:"<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });
       
    
      
   $(document).on('click', '.accept-btn', function () {
        let rfbsellerId = $(this).data('rfbseller-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to accept this request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Accept it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('member.smartwallet.buySell.acceptRequest', ':id') }}".replace(':id', rfbsellerId),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Accepted!',
                            text: res.message ? res.message : 'Request accepted successfully'
                        });
                        $('#selfwalletBalanceForNavBar').html(
                            '<i class="bi bi-wallet2 me-1"></i> ₹' + res.selfwalletBalance
                        );
                        $('#lockedWalletBalanceForNavBar').html(
                            '<i class="bi bi-lock-fill me-1"></i> ₹' + res.lockedWalletBalance
                        );
                        $('#receiveRfbHistoryTable').DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        });
                    }
                });

            }
        });
    });

    $(document).on('click', '.paymentReceive-btn', function () {

        let rfbSellerId = $(this).data('rfbseller-id');

        Swal.fire({
            title: "Are you sure?",
            text: "You want to mark this as Payment Received?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, confirm it!"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('member.smartwallet.buySell.rfb.payment.receive') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        rfb_seller_id: rfbSellerId
                    },

                    beforeSend: function () {
                        Swal.fire({
                            title: "Processing...",
                            text: "Please wait",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },

                    success: function (response) {

                        if (response.success) {

                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 1500);

                        } else {

                            Swal.fire("Error!", response.message, "error");
                        }
                    },

                    error: function () {

                        Swal.fire("Error!", "Something went wrong!", "error");
                    }
                });
            }
        });

    });
    $(document).on('click', '.view-payment-btn', function () {

    let id = $(this).data('rfbseller-id');

    $.ajax({
        url: "{{ route('member.smartwallet.buySell.rfb.payment.details') }}",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            rfb_seller_id: id
        },

        beforeSend: function () {
            Swal.fire({
                title: "Loading...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },

        success: function (res) {

            if (res.success) {

                let d = res.payment_details;

                Swal.fire({
                    title: "Payment Details",
                    html: `
                    <div style="text-align:center">
                        ${res.qr}                       

                        <p><b>Amount:</b> ${d.amount}</p>

                        <p><b>Transaction ID:</b> ${d.transaction_id ?? '-'}</p>

                        <p><b>Comment:</b> ${d.comment ?? '-'}</p>

                    </div>
                `,
                    width: 500,
                    confirmButtonText: "Close"
                });

            } else {
                Swal.fire("Error", res.message, "error");
            }
        },

        error: function () {
            Swal.fire("Error", "Something went wrong!", "error");
        }
    });

});
  

});

</script>
@endpush
