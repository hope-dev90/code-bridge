/* Codebridge enhancements: cursor, AI widget, carousels, reduced-motion. */
(function () {
  if (window.__cbEnhancements) return;
  window.__cbEnhancements = true;

  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function t(key, fallback) {
    const langCode = (window.CodebridgeI18n && window.CodebridgeI18n.current()) || "EN";
    const lang = { EN: "en", FR: "fr", KINY: "kiny" }[langCode] || "en";
    const extra = (window.CB_I18N_EXTRA && window.CB_I18N_EXTRA[lang]) || {};
    return extra[key] || fallback;
  }

  function initCursor() {
    document.querySelectorAll(".cb-cursor, .cb-cursor-dot").forEach((el) => el.remove());
    document.body.classList.remove("cb-has-cursor");
  }

  const PANEL_MARKUP = `
      <div class="ai-chat-header">
        <div class="ai-chat-header-info">
          <div class="ai-chat-avatar" aria-hidden="true">
            <img src="/images/logo.png" alt="">
          </div>
          <div>
            <div class="ai-chat-title" data-i18n="ai.title">Codebridge Assistant</div>
            <div class="ai-chat-status" id="aiChatStatus" data-i18n="ai.status_online">Online</div>
          </div>
        </div>
        <button class="ai-chat-close" id="aiChatClose" type="button" data-i18n-aria="ai.close" aria-label="Close chat">&times;</button>
      </div>
      <div class="ai-chat-messages" id="aiChatMessages"></div>
      <div class="ai-chat-composer">
        <div class="ai-chat-input-row">
          <input type="text" id="aiChatInput" class="ai-chat-input" autocomplete="off" data-i18n-placeholder="ai.placeholder" placeholder="Ask about CodeBridge…">
          <button class="ai-chat-send" id="aiChatSend" type="button" data-i18n-aria="ai.send" aria-label="Send message">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
          </button>
        </div>
        <p class="ai-chat-footnote" data-i18n="ai.footnote">Answers use CodeBridge’s official data. Unrelated questions are not answered.</p>
      </div>`;

  function mountToBody(el) {
    if (el && el.parentElement !== document.body) document.body.appendChild(el);
    return el;
  }

  function ensureChatDom() {
    let overlay = document.getElementById("aiChatOverlay");
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.className = "ai-chat-overlay";
      overlay.id = "aiChatOverlay";
    }
    overlay.className = "ai-chat-overlay";
    mountToBody(overlay);

    let panel = document.getElementById("aiChatPanel");
    if (!panel) {
      panel = document.createElement("div");
      panel.id = "aiChatPanel";
    }
    panel.className = "ai-chat-panel";
    panel.setAttribute("role", "dialog");
    panel.setAttribute("aria-modal", "true");
    panel.setAttribute("aria-label", "Codebridge Assistant");
    panel.innerHTML = PANEL_MARKUP;
    mountToBody(panel);
    return { overlay, panel };
  }

  function ensureFab() {
    document.querySelectorAll(".hero-ai-btn").forEach((btn) => {
      btn.classList.add("ai-fab");
      btn.classList.remove("hero-ai-btn");
      btn.setAttribute("aria-label", "Open Codebridge Assistant");
      mountToBody(btn);
    });
    document.querySelectorAll(".ai-fab").forEach((btn, i) => {
      mountToBody(btn);
      if (i > 0) btn.remove();
    });
    if (!document.querySelector(".ai-fab")) {
      const fab = document.createElement("button");
      fab.className = "ai-fab";
      fab.type = "button";
      fab.setAttribute("aria-label", "Open Codebridge Assistant");
      fab.innerHTML = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L13.5 8.5L20 10L13.5 11.5L12 18L10.5 11.5L4 10L10.5 8.5Z"/></svg>';
      document.body.appendChild(fab);
    }
    document.body.classList.remove("has-hero-ai");
  }

  function cleanAssistantText(raw) {
    let s = String(raw || "").replace(/\r\n/g, "\n").trim();
    s = s.replace(/\*\*([^*]+)\*\*/g, "$1");
    s = s.replace(/__([^_]+)__/g, "$1");
    s = s.replace(/`([^`]+)`/g, "$1");
    s = s.replace(/^[ \t]*#{1,6}[ \t]+/gm, "");
    s = s.replace(/^[ \t]*-{2,}[ \t]*$/gm, "");
    s = s.replace(/^[ \t]*(?:--+|[-–—*•])[ \t]+/gm, "");
    s = s.replace(/^[ \t]*--[ \t]*/gm, "");
    s = s.replace(/[ \t]+--[ \t]+/g, "; ");
    s = s.replace(/\n{3,}/g, "\n\n");
    return s.trim();
  }

  function renderBotContent(bubble, text) {
    const cleaned = cleanAssistantText(text);
    const lines = cleaned.split(/\n+/).map((line) => line.trim()).filter(Boolean);
    const numbered = lines.filter((line) => /^\d+[.)]\s+/.test(line));
    if (numbered.length >= 2 && numbered.length === lines.length) {
      const list = document.createElement("ol");
      list.className = "ai-msg-list";
      lines.forEach((line) => {
        const item = document.createElement("li");
        item.textContent = line.replace(/^\d+[.)]\s+/, "");
        list.appendChild(item);
      });
      bubble.appendChild(list);
      return;
    }
    lines.forEach((line, i) => {
      if (i) bubble.appendChild(document.createElement("br"));
      bubble.appendChild(document.createTextNode(line));
    });
  }

  function renderEmpty(messages) {
    messages.innerHTML = "";
    const welcome = document.createElement("div");
    welcome.className = "ai-welcome";
    const mark = document.createElement("div");
    mark.className = "ai-welcome-mark";
    mark.setAttribute("aria-hidden", "true");
    mark.innerHTML = '<img src="/images/logo.png" alt="">';
    const title = document.createElement("h2");
    title.className = "ai-welcome-title";
    title.textContent = t("ai.empty_title", "How can we help?");
    const body = document.createElement("p");
    body.className = "ai-welcome-body";
    body.textContent = t(
      "ai.empty_body",
      "Ask about CodeBridge services, projects, news, or public statistics. Unrelated questions are not answered."
    );
    welcome.appendChild(mark);
    welcome.appendChild(title);
    welcome.appendChild(body);
    messages.appendChild(welcome);

    const chips = document.createElement("div");
    chips.className = "ai-suggestions";
    [
      ["ai.q1", "What services does Codebridge offer?"],
      ["ai.q2", "What is IKiraro Innovation Hub?"],
      ["ai.q3", "How can I contact Codebridge?"],
    ].forEach(([key, fallback]) => {
      const b = document.createElement("button");
      b.type = "button";
      b.textContent = t(key, fallback);
      b.addEventListener("click", () => {
        const input = document.getElementById("aiChatInput");
        input.value = b.textContent;
        input.dispatchEvent(new Event("cb:send"));
      });
      chips.appendChild(b);
    });
    messages.appendChild(chips);
  }

  function initChat() {
    ensureFab();
    const { overlay, panel } = ensureChatDom();
    const closeBtn = document.getElementById("aiChatClose");
    const messages = document.getElementById("aiChatMessages");
    const input = document.getElementById("aiChatInput");
    const sendBtn = document.getElementById("aiChatSend");
    const status = document.getElementById("aiChatStatus");
    if (!panel || !messages || !input || !sendBtn) return;

    let history = [];

    function applyUi() {
      const title = panel.querySelector(".ai-chat-title");
      if (title) title.textContent = t("ai.title", "Codebridge Assistant");
      if (status && !status.dataset.busy) status.textContent = t("ai.status_online", "Online");
      input.placeholder = t("ai.placeholder", "Type your message...");
      sendBtn.setAttribute("aria-label", t("ai.send", "Send message"));
      if (closeBtn) closeBtn.setAttribute("aria-label", t("ai.close", "Close chat"));
      const footnote = panel.querySelector(".ai-chat-footnote");
      if (footnote) footnote.textContent = t("ai.footnote", "Answers use CodeBridge’s official data. Unrelated questions are not answered.");
      if (!history.length) renderEmpty(messages);
    }

    function openChat() {
      panel.classList.add("active");
      overlay.classList.add("active");
      document.body.classList.add("cb-chat-open");
      setTimeout(() => input.focus(), 160);
    }
    function closeChat() {
      panel.classList.remove("active");
      overlay.classList.remove("active");
      document.body.classList.remove("cb-chat-open");
    }

    document.querySelectorAll(".ai-fab").forEach((b) => {
      b.addEventListener("click", openChat);
    });
    if (closeBtn) closeBtn.addEventListener("click", closeChat);
    if (overlay) overlay.addEventListener("click", closeChat);
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && panel.classList.contains("active")) closeChat();
    });

    function addMessage(text, sender, isError) {
      const chips = messages.querySelector(".ai-suggestions");
      if (chips) chips.remove();
      const welcome = messages.querySelector(".ai-welcome");
      if (welcome && sender === "user") welcome.remove();
      const row = document.createElement("div");
      row.className = "ai-msg ai-msg--" + sender + (isError ? " ai-msg--error" : "");
      if (sender === "bot") {
        const av = document.createElement("div");
        av.className = "ai-msg-avatar";
        av.setAttribute("aria-hidden", "true");
        av.innerHTML = '<img src="/images/logo.png" alt="">';
        row.appendChild(av);
      }
      const bubble = document.createElement("div");
      bubble.className = "ai-msg-bubble";
      if (sender === "bot" && !isError) {
        renderBotContent(bubble, text);
      } else {
        bubble.textContent = text;
      }
      row.appendChild(bubble);
      messages.appendChild(row);
      messages.scrollTop = messages.scrollHeight;
      return row;
    }

    function showTyping() {
      const row = document.createElement("div");
      row.className = "ai-msg ai-msg--bot ai-msg--typing";
      row.innerHTML = '<div class="ai-msg-bubble ai-msg-bubble--typing"><span class="ai-typing-dot"></span><span class="ai-typing-dot"></span><span class="ai-typing-dot"></span></div>';
      messages.appendChild(row);
      messages.scrollTop = messages.scrollHeight;
      return row;
    }

    async function sendMessage() {
      const text = input.value.trim();
      if (!text || sendBtn.disabled) return;
      addMessage(text, "user");
      history.push({ role: "user", text });
      input.value = "";
      sendBtn.disabled = true;
      if (status) {
        status.dataset.busy = "1";
        status.textContent = t("ai.status_thinking", "Thinking…");
      }
      const typingRow = showTyping();
      try {
        const lang = (window.CodebridgeI18n && window.CodebridgeI18n.current()) || "EN";
        const res = await fetch("/chat.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            message: text,
            lang,
            history: history.slice(0, -1).slice(-8),
          }),
        });
        const data = await res.json().catch(() => ({}));
        typingRow.remove();
        const reply = cleanAssistantText(data.reply || t("ai.error", "The assistant could not complete that request."));
        addMessage(reply, "bot", !data.ok);
        history.push({ role: "bot", text: reply });
      } catch (e) {
        typingRow.remove();
        addMessage(t("ai.error_network", "I'm having trouble connecting right now. Please try again shortly."), "bot", true);
      } finally {
        sendBtn.disabled = false;
        if (status) {
          delete status.dataset.busy;
          status.textContent = t("ai.status_online", "Online");
        }
        input.focus();
      }
    }

    sendBtn.addEventListener("click", sendMessage);
    input.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });
    input.addEventListener("cb:send", sendMessage);

    applyUi();
    window.addEventListener("cb:lang", applyUi);
  }

  function stepSize(track, scroller) {
    const card = track && track.children[0];
    if (!card) return Math.max(240, (scroller || track).clientWidth * 0.8);
    const styles = window.getComputedStyle(track);
    const gap = parseFloat(styles.columnGap || styles.gap) || 24;
    return card.getBoundingClientRect().width + gap;
  }

  function bindScroller(scroller, track, prev, next, options) {
    if (!scroller || scroller.dataset.cbBound === "1") return;
    scroller.dataset.cbBound = "1";
    if (track) track.classList.add("cb-carousel-track");
    const opts = options || {};
    const cards = track || scroller;

    Array.from(cards.querySelectorAll("a")).forEach((a) => {
      a.setAttribute("draggable", "false");
    });

    function step() {
      return stepSize(cards, scroller);
    }
    function go(dir) {
      scroller.scrollBy({ left: dir * step(), behavior: reduced ? "auto" : "smooth" });
    }
    function atEnd() {
      return scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 8;
    }

    let paused = false;
    let raf = 0;
    let lastTs = 0;
    let carry = 0;
    const PX_PER_SEC = 36;
    const RESUME_MS = 5000;

    function startAuto() {
      if (!opts.autoplay || reduced || paused || raf) return;
      lastTs = 0;
      carry = 0;
      function tick(ts) {
        raf = requestAnimationFrame(tick);
        if (paused || document.hidden) {
          lastTs = ts;
          return;
        }
        if (!lastTs) {
          lastTs = ts;
          return;
        }
        const dt = Math.min(48, ts - lastTs);
        lastTs = ts;
        if (atEnd()) {
          scroller.scrollLeft = 0;
          carry = 0;
          return;
        }
        carry += PX_PER_SEC * (dt / 1000);
        const px = Math.floor(carry);
        if (px >= 1) {
          scroller.scrollLeft += px;
          carry -= px;
        }
      }
      raf = requestAnimationFrame(tick);
    }
    function stopAuto() {
      if (raf) cancelAnimationFrame(raf);
      raf = 0;
      lastTs = 0;
    }
    function userTookOver() {
      paused = true;
      stopAuto();
      window.clearTimeout(scroller._cbResume);
      scroller._cbResume = window.setTimeout(() => {
        paused = false;
        startAuto();
      }, RESUME_MS);
    }

    if (prev) prev.addEventListener("click", () => { go(-1); userTookOver(); });
    if (next) next.addEventListener("click", () => { go(1); userTookOver(); });

    let pid = null, x0 = 0, s0 = 0, moved = false;
    scroller.addEventListener("pointerdown", (e) => {
      if (e.pointerType !== "mouse" || e.button !== 0) return;
      if (e.target.closest("button, input, textarea, select")) return;
      pid = e.pointerId;
      x0 = e.clientX;
      s0 = scroller.scrollLeft;
      moved = false;
    });
    scroller.addEventListener("pointermove", (e) => {
      if (pid == null || e.pointerId !== pid) return;
      const dx = e.clientX - x0;
      if (!moved) {
        if (Math.abs(dx) < 6) return;
        moved = true;
        userTookOver();
        scroller.classList.add("is-dragging");
        try { scroller.setPointerCapture(pid); } catch (err) { /* ignore */ }
      }
      e.preventDefault();
      scroller.scrollLeft = s0 - dx;
    });
    function endDrag(e) {
      if (pid == null || (e && e.pointerId !== pid)) return;
      pid = null;
      scroller.classList.remove("is-dragging");
    }
    scroller.addEventListener("pointerup", endDrag);
    scroller.addEventListener("pointercancel", endDrag);
    scroller.addEventListener("click", (e) => {
      if (moved) {
        e.preventDefault();
        e.stopPropagation();
        moved = false;
      }
    }, true);
    scroller.addEventListener("touchstart", userTookOver, { passive: true });

    document.addEventListener("visibilitychange", () => {
      if (document.hidden) stopAuto();
      else if (!paused) startAuto();
    });
    startAuto();
  }

  function wrapCarousel(grid, visibleClass, options) {
    if (!grid) return grid;
    const opts = Object.assign({}, options);
    let wrap = grid.closest(".cb-carousel");
    let viewport;
    if (wrap) {
      viewport = wrap.querySelector(".cb-carousel-viewport") || grid.parentElement;
    } else {
      wrap = document.createElement("div");
      wrap.className = "cb-carousel" + (visibleClass ? " " + visibleClass : "");
      viewport = document.createElement("div");
      viewport.className = "cb-carousel-viewport";
      viewport.setAttribute("tabindex", "0");
      viewport.setAttribute("aria-label", opts.label || "Carousel");
      grid.parentNode.insertBefore(wrap, grid);
      wrap.appendChild(viewport);
      viewport.appendChild(grid);
      grid.classList.add("cb-carousel-track");
      if (opts.nav !== false && !opts.prev && !opts.next) {
        const nav = document.createElement("div");
        nav.className = "cb-carousel-nav";
        nav.innerHTML = '<button type="button" class="svc-arrow cb-carousel-prev" aria-label="Previous">&#8592;</button><button type="button" class="svc-arrow cb-carousel-next" aria-label="Next">&#8594;</button>';
        wrap.appendChild(nav);
        opts.prev = nav.querySelector(".cb-carousel-prev");
        opts.next = nav.querySelector(".cb-carousel-next");
      }
    }
    bindScroller(viewport, grid, opts.prev, opts.next, opts);
    if (opts.autoplay) fillTrack(grid, viewport);
    return grid;
  }

  function fillTrack(track, scroller) {
    if (!track || !scroller) return;
    window.requestAnimationFrame(() => {
      const originals = Array.from(track.children).filter((el) => el.dataset.clone !== "1");
      if (!originals.length) return;
      let n = 0;
      while (track.scrollWidth <= scroller.clientWidth + 24 && n < 6) {
        originals.forEach((node) => {
          const clone = node.cloneNode(true);
          clone.dataset.clone = "1";
          clone.setAttribute("aria-hidden", "true");
          clone.removeAttribute("id");
          clone.querySelectorAll("[id]").forEach((el) => el.removeAttribute("id"));
          track.appendChild(clone);
        });
        n += 1;
      }
    });
  }

  function initCarousels() {
    const featured = document.getElementById("featuredServicesTrack") || document.querySelector(".featured-services-viewport .services-grid");
    if (featured) {
      wrapCarousel(featured, "cb-carousel--services", {
        autoplay: true,
        prev: document.getElementById("featPrev"),
        next: document.getElementById("featNext")
      });
    }

    const svc = document.getElementById("svcTrack");
    if (svc) {
      wrapCarousel(svc, "cb-carousel--services", {
        autoplay: true,
        prev: document.getElementById("svcPrev"),
        next: document.getElementById("svcNext")
      });
    }

    document.querySelectorAll(".cb-partners-grid").forEach((el) => {
      wrapCarousel(el, "cb-carousel--partners", { autoplay: true, nav: false, label: "Partners" });
    });
    const news = document.getElementById("newsCarousel");
    if (news) {
      wrapCarousel(news, "cb-carousel--news", { autoplay: true, nav: false, label: "Articles" });
      const newsViewport = news.closest(".cb-carousel-viewport");
      const mo = new MutationObserver(() => fillTrack(news, newsViewport));
      mo.observe(news, { childList: true });
    }

    const reels = document.getElementById("reelsGrid");
    if (reels) {
      wrapCarousel(reels, "cb-carousel--reels", { autoplay: true, label: "Technical Reels" });
      const reelsViewport = reels.closest(".cb-carousel-viewport");
      const reelsMo = new MutationObserver(() => fillTrack(reels, reelsViewport));
      reelsMo.observe(reels, { childList: true });
    }
  }

  function initAosMotion() {
    if (!window.AOS) return;
    if (reduced) {
      window.AOS.init({ duration: 0, once: true, disable: true });
    }
  }

  function initVoiceStories() {
    /* Stories are a static three-card grid now. */
  }

  function hidePreloader() {
    const overlay = document.getElementById("cb-preloader");
    if (!overlay || overlay.classList.contains("is-done")) return;
    document.documentElement.classList.remove("cb-loading");
    overlay.classList.add("is-done");
    overlay.setAttribute("aria-busy", "false");
    window.setTimeout(() => overlay.remove(), 380);
  }

  function boot() {
    initCursor();
    initChat();
    document.querySelectorAll(".cb-cursor, .cb-cursor-dot").forEach((el) => {
      document.body.appendChild(el);
    });
    initCarousels();
    initVoiceStories();
    initAosMotion();
    if (document.readyState === "complete") hidePreloader();
    else window.addEventListener("load", hidePreloader);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
