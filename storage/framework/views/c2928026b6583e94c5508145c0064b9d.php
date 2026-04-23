<?php $__env->startPush('scripts'); ?>
<script>

let chatSender = null;
let chatReceiver = null;

/**
 * OPEN CHAT MODAL (reusable)
 */
function openChatModal() {
    const el = document.getElementById('chatModal');

    if (!el) return;

    const modal = bootstrap.Modal.getOrCreateInstance(el);
    modal.show();
}

/**
 * LOAD CHAT
 */
function loadChatHistory()
{
    $.get(chatLoadHistoryUrl, {
        sender: chatSender,
        receiver: chatReceiver
    }, function (res) {

        $('#chatMessages').html(res.html);

        // scroll bottom (latest message)
        let box = document.getElementById('chatMessages');
        box.scrollTop = box.scrollHeight;
    });
}
function loadChatName()
{
    $.get(chatLoadUrl, {
        sender: chatSender,
        receiver: chatReceiver
    }, function (res) {

        
        $('.chat-user-name').text(res.chatUserName);
        $('.chat-user-avatar').text(res.chatUserName.charAt(0).toUpperCase());
        openChatModal();
        loadChatHistory();
    });
}

/**
 * CLICK EVENT (GLOBAL)
 */
$(document).on('click', '.message-btn', function () {

    let sender = $(this).data('sender');
    let receiver = $(this).data('receiver');    

    chatSender = sender;
    chatReceiver = receiver;
    loadChatName();    
    
});

/**
 * SEND MESSAGE
 */
$('#chatInput').on('keypress', function (e) {
    if (e.which === 13) {
        sendMessage();
    }
});

$('#sendMsg').on('click', function () {
    sendMessage();
});
function sendMessage(){

    let message = $('#chatInput').val();

    if (message === '') {
        return;
    }

    $.post(chatSendUrl, {
        _token: '<?php echo e(csrf_token()); ?>',
        sender: chatSender,
        receiver: chatReceiver,
        message: message
    }, function () {

        $('#chatInput').val('');
        loadChatHistory();
    });
}
$(document).ready(function () {

    setInterval(function () {

        if ($('#chatModal').hasClass('show')) {
            loadChatHistory();
        }

    }, 3000);

});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/chat-script.blade.php ENDPATH**/ ?>