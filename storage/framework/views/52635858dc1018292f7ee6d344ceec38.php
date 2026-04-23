<?php $__env->startSection('content'); ?>

<div class="modal fade" id="chatModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5>Chat Box</h5>
      </div>

      <div class="modal-body">
        <div id="chatMessages" style="height:300px; overflow-y:auto;"></div>

        <textarea id="chatInput" class="form-control mt-2"></textarea>

        <button class="btn btn-primary mt-2" id="sendMsg">Send</button>
      </div>

    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
    const chatLoadUrl = "<?php echo e(route('member.chat.load')); ?>";
</script>
<?php $__env->stopPush(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\resources\views/member/smartwallet/chat.blade.php ENDPATH**/ ?>