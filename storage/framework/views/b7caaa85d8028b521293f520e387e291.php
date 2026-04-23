
<!-- CHAT MODAL -->
<div class="modal fade" id="chatListModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content chat-modal">

            <!-- HEADER -->
            <div class="modal-header chat-header">
                <h6 class="chat-title">💬 Chats</h6>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- SEARCH -->
           <div class="chat-search-box" id="chatSearchBox">
                <div style="display:flex;align-items:center;background:#fff;border-radius:25px;padding:6px 10px;border:1px solid #e5e5e5;gap:6px;">
                    <i class="bi bi-search" style="font-size:14px;color:#888;flex-shrink:0;"></i>
                    <input type="text" id="chatSearch" class="form-control" placeholder="Search users..." style="border:none;outline:none;box-shadow:none;flex:1;background:transparent;min-width:0;font-size:14px;">
                </div>
            </div>

            <!-- CHAT LIST -->
            <div class="chat-body">
                <ul id="chatUserList" class="chat-list">
                    <li class="loading">Loading chats...</li>
                </ul>
            </div>

        </div>
    </div>
</div>
<!-- ================= CSS ================= -->
<style>
    .chat-modal{
        border-radius:16px;
        overflow:hidden;
        border:none;
        box-shadow:0 10px 35px rgba(0,0,0,0.2);
    }

    .chat-header{
        background:linear-gradient(135deg,#075e54,#128c7e);
        color:#fff;
        padding:12px 15px;
    }

    .chat-title{
        margin:0;
        font-weight:600;
    }

    .chat-search-box{
        padding:10px;
        border-bottom:1px solid #eee;
        background:#f9f9f9;
    }

    .chat-search-box input{
        border-radius:25px;
        padding:8px 14px;
    }

    .chat-body{
        height:420px;
        overflow-y:auto;
        background:#f0f2f5;
    }

    .chat-list{
        list-style:none;
        margin:0;
        padding:0;
    }

    .chat-user{
        display:flex;
        align-items:center;
        gap:12px;
        padding:12px 14px;
        border-bottom:1px solid #e6e6e6;
        cursor:pointer;
        background:#fff;
        transition:0.2s;
    }

    .chat-user:hover{
        background:#eaf7f4;
    }

    .chat-avatar{
        width:40px;
        height:40px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:600;
        color:#fff;
    }

    .chat-info{
        flex:1;
    }

    .chat-info strong{
        font-size:14px;
        display:block;
    }

    .chat-info small{
        font-size:12px;
        color:#777;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .chat-time{
        font-size:11px;
        color:#999;
    }

    .loading{
        text-align:center;
        padding:20px;
        color:#888;
    }
</style>

<!-- ================= SCRIPT ================= -->
<?php
    $chatPrefix = session()->has('member_logged_in') ? 'member.' : '';
?>
<?php echo $__env->make('chatbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('chat-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
window.chatListUrl = "<?php echo e(route($chatPrefix . 'chat.list')); ?>";

/* OPEN MODAL */
$('#smartBoatChatLink').on('click', function (e) {
    e.preventDefault();
    $('#chatSearch').val('');
    $('#chatListModal').modal('show');
    loadChatList('');
});

/* LOAD CHAT LIST (SERVER SMART SEARCH) */
function loadChatList(search = '') {

    $('#chatUserList').html('<li class="loading">Loading chats...</li>');

    $.ajax({
        url: window.chatListUrl,
        type: "GET",
        data: { search: search },
        success: function (res) {

            if (!res || !res.users || res.users.length === 0) {
                $('#chatUserList').html('<li class="loading">No users found</li>');
                return;
            }

            renderUserList(res.users);
        },
        error: function () {
            $('#chatUserList').html('<li class="loading" style="color:red;">Error loading chats</li>');
        }
    });
}

/* DEBOUNCE SEARCH */
let searchTimeout = null;

$('#chatSearch').on('keyup', function () {

    clearTimeout(searchTimeout);

    let value = $(this).val();

    searchTimeout = setTimeout(function () {

        loadChatList(value);

    }, 400);
});

/* INITIALS */
function getInitials(name){
    if(!name) return "U";

    return name
        .trim()
        .split(/\s+/)
        .filter(n => n.length > 0)
        .map(n => n[0])
        .join('')
        .substring(0,2)
        .toUpperCase();
}

/* COLOR */
function getColor(name){
    const colors = ["#25d366","#128c7e","#6c5ce7","#0984e3","#e17055","#fd79a8","#f39c12"];
    return colors[name.charCodeAt(0) % colors.length];
}

/* RENDER */
function renderUserList(users){

    let html = '';

    $.each(users, function(i, user){

        html += `
        <li class="chat-user message-btn"
            data-sender="${user.sender_member_id ?? ''}"
            data-receiver="${user.receiver_member_id ?? ''}">

            <div class="chat-avatar" style="background:${getColor(user.name)}">
                ${getInitials(user.name)}
            </div>

            <div class="chat-info">
                <strong>${user.name}</strong>
                <small>${user.last_message ?? 'Start chatting...'}</small>
            </div>

            <div class="chat-time">
                ${user.time ?? ''}
            </div>

        </li>`;
    });

    $('#chatUserList').html(html);
}
</script>
<?php $__env->stopPush(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\Main\resources\views/smartBoatChat.blade.php ENDPATH**/ ?>