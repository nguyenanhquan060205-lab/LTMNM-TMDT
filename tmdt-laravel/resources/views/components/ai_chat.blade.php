<!-- ===================== AI CHAT WIDGET ===================== -->
<style>
    /* ---- Floating Button ---- */
    #ai-widget-btn {
        position: fixed;
        bottom: 26px;
        right: 26px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--primary-color, #0d6efd);
        color: white;
        border: none;
        font-size: 22px;
        cursor: pointer;
        box-shadow: 0 4px 24px rgba(13,110,253,0.4);
        z-index: 9998;
        transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #ai-widget-btn:hover { transform: scale(1.12); box-shadow: 0 6px 32px rgba(13,110,253,0.55); }

    /* Unread badge */
    #ai-widget-btn .ai-badge {
        position: absolute;
        top: -2px; right: -2px;
        width: 16px; height: 16px;
        background: #ef4444;
        border-radius: 50%;
        border: 2px solid white;
        font-size: 9px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
    }

    /* ---- Widget Box ---- */
    #ai-widget-box {
        position: fixed;
        bottom: 94px;
        right: 26px;
        width: 440px;
        height: 560px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 12px 48px rgba(0,0,0,0.16), 0 2px 8px rgba(0,0,0,0.08);
        z-index: 9998;
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
    }
    #ai-widget-box.open { display: flex; animation: aiSlideUp .28s cubic-bezier(.16,1,.3,1); }
    @keyframes aiSlideUp {
        from { opacity: 0; transform: translateY(16px) scale(.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ---- Header ---- */
    .ai-header {
        background: var(--primary-color, #0d6efd);
        color: white;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }
    .ai-header .ai-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .ai-header .ai-info { flex: 1; min-width: 0; }
    .ai-header .ai-info b { display: block; font-size: 14px; font-weight: 600; }
    .ai-header .ai-info small {
        font-size: 11px; opacity: 0.85;
        display: flex; align-items: center; gap: 4px;
    }
    .ai-online-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 0 2px rgba(74,222,128,.3);
        display: inline-block;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0%,100% { box-shadow: 0 0 0 2px rgba(74,222,128,.3); }
        50%      { box-shadow: 0 0 0 5px rgba(74,222,128,.1); }
    }
    .ai-header .ai-close {
        background: rgba(255,255,255,.15); border: none; color: white;
        width: 28px; height: 28px; border-radius: 50%;
        font-size: 16px; cursor: pointer; display: flex;
        align-items: center; justify-content: center;
        transition: background .2s; flex-shrink: 0;
    }
    .ai-header .ai-close:hover { background: rgba(255,255,255,.3); }

    /* ---- Messages Area ---- */
    .ai-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #f8f9ff;
        display: flex;
        flex-direction: column;
        gap: 12px;
        scroll-behavior: smooth;
    }
    .ai-messages::-webkit-scrollbar { width: 4px; }
    .ai-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

    /* ---- Message Row ---- */
    .ai-msg { display: flex; gap: 8px; align-items: flex-end; }
    .ai-msg.user { flex-direction: row-reverse; }

    /* ---- Bubble ---- */
    .ai-bubble {
        max-width: 82%;
        padding: 10px 14px;
        border-radius: 18px;
        font-size: 13.5px;
        line-height: 1.55;
        word-break: break-word;
    }
    .ai-msg.bot  .ai-bubble {
        background: #fff;
        border: 1px solid #e8eaf0;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,.05);
    }
    .ai-msg.user .ai-bubble {
        background: var(--primary-color, #0d6efd);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 8px rgba(13,110,253,.35);
    }

    /* ---- Bot Icon ---- */
    .ai-bot-icon {
        width: 30px; height: 30px; border-radius: 50%;
        background: var(--primary-color, #0d6efd);
        color: white; display: flex; align-items: center;
        justify-content: center; font-size: 14px; flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(13,110,253,.4);
    }

    /* ---- Typing Dots ---- */
    .ai-bubble p { margin-bottom: 6px; }
    .ai-bubble p:last-child { margin-bottom: 0; }
    .ai-bubble a { color: #0d6efd; text-decoration: underline; font-weight: 500; }
    .ai-bubble ul { padding-left: 20px; margin-bottom: 6px; }
    
    .ai-typing { display: flex; gap: 5px; align-items: center; padding: 4px 2px; }
    .ai-typing span {
        width: 7px; height: 7px; border-radius: 50%;
        background: #9ca3af; display: inline-block;
        animation: aiDot 1.3s infinite ease-in-out;
    }
    .ai-typing span:nth-child(2) { animation-delay: .2s; }
    .ai-typing span:nth-child(3) { animation-delay: .4s; }
    @keyframes aiDot {
        0%,80%,100% { transform: scale(.8); opacity: .5; }
        40%          { transform: scale(1.15); opacity: 1; }
    }

    /* ---- Input Area ---- */
    .ai-input-area {
        padding: 12px 14px;
        background: #fff;
        border-top: 1px solid #f0f1f5;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-shrink: 0;
    }
    .ai-input-area input {
        flex: 1;
        border: 1.5px solid #e5e7eb;
        border-radius: 24px;
        padding: 9px 16px;
        font-size: 13.5px;
        outline: none;
        background: #f8f9ff;
        transition: border-color .2s, background .2s;
        color: #1e293b;
    }
    .ai-input-area input:focus {
        border-color: var(--primary-color, #0d6efd);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(13,110,253,.12);
    }
    .ai-input-area input:disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
    .ai-send-btn {
        width: 38px; height: 38px; border-radius: 50%;
        background: var(--primary-color, #0d6efd);
        color: white; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 8px rgba(13,110,253,.35);
    }
    .ai-send-btn:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(13,110,253,.5); }
    .ai-send-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }

    /* ---- Welcome bubble ---- */
    .ai-bubble.ai-welcome { max-width: 90%; }
    .ai-chips { display: flex; flex-wrap: wrap; gap: 5px; margin: 8px 0; }
    .ai-chips span {
        background: #eff6ff; color: #1d4ed8;
        border: 1px solid #bfdbfe; border-radius: 20px;
        padding: 3px 11px; font-size: 11.5px; font-weight: 500;
        white-space: nowrap; cursor: default;
    }
    .ai-suggest {
        margin: 6px 0 0 0; color: #94a3b8;
        font-size: 11px; font-style: italic;
        border-top: 1px dashed #e2e8f0;
        padding-top: 8px;
    }

    /* ---- Login button inside chat ---- */
    .ai-login-btn {
        display: inline-block;
        background: var(--primary-color, #0d6efd);
        color: white; padding: 7px 20px; border-radius: 20px;
        font-size: 12.5px; font-weight: 600; text-decoration: none;
        margin-top: 4px; transition: opacity .2s;
        box-shadow: 0 2px 8px rgba(13,110,253,.35);
    }
    .ai-login-btn:hover { opacity: .88; color: white; }

    /* Mobile */
    @media (max-width: 480px) {
        #ai-widget-box { width: calc(100vw - 32px); right: 16px; bottom: 86px; height: 70vh; }
        #ai-widget-btn { right: 16px; bottom: 16px; }
    }
</style>

<button id="ai-widget-btn" title="Hỏi TechBot AI">
    🤖
</button>

<div id="ai-widget-box">
    <!-- Header -->
    <div class="ai-header">
        <div class="ai-avatar">🤖</div>
        <div class="ai-info">
            <b>TechBot AI</b>
            <small><span class="ai-online-dot"></span> Đang hoạt động</small>
        </div>
        <button class="ai-close" onclick="toggleAiWidget()" title="Đóng">✕</button>
    </div>

    <!-- Messages -->
    <div class="ai-messages" id="aiMessages">
        <div class="ai-msg bot">
            <div class="ai-bot-icon">🤖</div>
            @if(isset($user) && $user)
            <div class="ai-bubble ai-welcome">
                <p style="margin:0 0 4px 0; font-weight:600;">Xin chào, {{ $user->HoTen }}! 👋</p>
                <p style="margin:0 0 8px 0; color:#64748b; font-size:12.5px;">Tôi có thể giúp bạn về:</p>
                <div class="ai-chips">
                    <span>🛒 Mua bán</span>
                    <span>📦 Đơn hàng</span>
                    <span>🔄 Huỷ / Hoàn</span>
                    <span>💬 Hỗ trợ</span>
                </div>
                <p class="ai-suggest">💡 "Làm sao để đặt hàng?" · "Tôi muốn huỷ đơn"</p>
            </div>
            @else
            <div class="ai-bubble ai-welcome">
                <p style="margin:0 0 6px 0; font-weight:600;">👋 Xin chào!</p>
                <p style="margin:0 0 10px 0; color:#64748b; font-size:12.5px;">
                    Tôi là <b>TechBot AI</b> của TechSecond.<br>
                    Đăng nhập để bắt đầu trò chuyện nhé!
                </p>
                <a href="{{ url('/taikhoan/dangnhap') }}" class="ai-login-btn">🔑 Đăng nhập ngay</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Input -->
    <div class="ai-input-area">
        <input type="text" id="aiInput"
               placeholder="{{ isset($user) && $user ? 'Hỏi TechBot bất cứ điều gì...' : 'Đăng nhập để sử dụng TechBot...' }}"
               autocomplete="off"
               {{ isset($user) && $user ? '' : 'disabled' }} />
        <button class="ai-send-btn" id="aiSendBtn" onclick="sendAiMessage()" {{ isset($user) && $user ? '' : 'disabled' }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </button>
    </div>
</div>

<script>
    function toggleAiWidget() {
        const box = document.getElementById('ai-widget-box');
        box.classList.toggle('open');
        if (box.classList.contains('open') && !document.getElementById('aiInput').disabled) {
            setTimeout(() => document.getElementById('aiInput').focus(), 100);
        }
    }

    document.getElementById('ai-widget-btn').addEventListener('click', toggleAiWidget);

    document.getElementById('aiInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendAiMessage(); }
    });

    function appendMsg(role, html) {
        const container = document.getElementById('aiMessages');
        const div = document.createElement('div');
        div.className = 'ai-msg ' + role;
        div.innerHTML = role === 'bot'
            ? `<div class="ai-bot-icon">🤖</div><div class="ai-bubble">${html}</div>`
            : `<div class="ai-bubble">${html}</div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function showTyping() {
        const container = document.getElementById('aiMessages');
        const div = document.createElement('div');
        div.className = 'ai-msg bot';
        div.id = 'aiTyping';
        div.innerHTML = `<div class="ai-bot-icon">🤖</div><div class="ai-bubble"><div class="ai-typing"><span></span><span></span><span></span></div></div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function removeTyping() {
        const t = document.getElementById('aiTyping');
        if (t) t.remove();
    }

    async function sendAiMessage() {
        const input   = document.getElementById('aiInput');
        const sendBtn = document.getElementById('aiSendBtn');
        const msg = input.value.trim();
        if (!msg || input.disabled) return;

        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;
        appendMsg('user', msg);
        showTyping();

        try {
            const res = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            removeTyping();

            if (data.requireLogin) {
                appendMsg('bot', `🔒 Phiên đăng nhập hết hạn.<br><br><a href="{{ url('/taikhoan/dangnhap') }}" class="ai-login-btn">🔑 Đăng nhập lại</a>`);
                return;
            }

            appendMsg('bot', data.reply || 'Xin lỗi, có lỗi xảy ra.');
        } catch {
            removeTyping();
            appendMsg('bot', '⚠️ Kết nối bị gián đoạn. Vui lòng thử lại!');
        } finally {
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
        }
    }
</script>
<!-- =========================================================== -->
