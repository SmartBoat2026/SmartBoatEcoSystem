<div class="modal fade" id="chatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">

            {{-- HEADER --}}
            <div class="modal-header" style="background:#075e54;color:#fff;padding:10px 15px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span  id="backToChatList" ><i class="bi bi-arrow-left"></i></span>
                    

                    <div class="chat-user-avatar" style="width:35px;height:35px;border-radius:50%;background:#fff;color:#075e54;
            display:flex;align-items:center;justify-content:center;font-weight:bold;"></div>

                    <div>
                        <h6 class="chat-user-name" style="margin:0;font-size:14px;">
                            User
                        </h6>
                        <small class="chat-user-member-id" style="font-size:10px;color:#ddd;">
                            ID: 12345
                        </small>
                    </div>

                </div>

                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY (EMPTY - JS will load here) --}}
            <div class="modal-body"
                 id="chatMessages"
                 style="background:#ece5dd;height:400px;overflow-y:auto;padding:10px;">
                
                <div style="text-align:center;color:#777;margin-top:20px;">
                    Loading...
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer"
              style="background:#f0f0f0;display:flex;gap:8px;padding:10px;align-items:center;">

              <input type="text"
                    id="chatInput"
                    class="form-control"
                    placeholder="Type a message..."
                    style="border-radius:20px;flex:1;">

              <button id="sendMsg"
                      class="btn"
                      style="background:#075e54;color:#fff;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                  ➤
              </button>

          </div>

        </div>
    </div>
</div>


@php
    $chatPrefix = session()->has('member_logged_in') ? 'member.' : '';
@endphp
@push('scripts')
<script>
    const chatLoadUrl = "{{ route($chatPrefix . 'chat.load.name') }}";
    const chatLoadHistoryUrl = "{{ route($chatPrefix . 'chat.load.history') }}";
    const chatSendUrl = "{{ route($chatPrefix . 'chat.send') }}";
</script>
@endpush