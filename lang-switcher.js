(function () {
  const initLanguageSwitcher = () => {
    document.querySelectorAll('.site-nav-lang-wrap').forEach((wrap) => {
      const trigger = wrap.querySelector('.site-nav-lang');
      const menu = wrap.querySelector('.site-nav-lang-menu');
      const options = wrap.querySelectorAll('.site-nav-lang-option');

      if (!trigger || !menu || !options.length) return;

      const closeMenu = () => {
        wrap.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
      };

      trigger.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = wrap.classList.toggle('open');
        trigger.setAttribute('aria-expanded', String(isOpen));
      });

      options.forEach((option) => {
        option.addEventListener('click', (event) => {
          event.stopPropagation();
          if (window.CodebridgeI18n) {
            window.CodebridgeI18n.set(option.dataset.value);
          } else {
            options.forEach((item) => item.classList.remove('active'));
            option.classList.add('active');
            trigger.textContent = option.dataset.value;
            trigger.setAttribute('aria-label', `Language: ${option.dataset.label}`);
          }
          closeMenu();
        });
      });

      document.addEventListener('click', (event) => {
        if (!wrap.contains(event.target)) {
          closeMenu();
        }
      });
    });
  };

  const initMobileToggle = () => {
    const toggle = document.querySelector('.site-nav-toggle');
    const links = document.querySelector('.site-nav-links');
    if (!toggle || !links) return;

    toggle.addEventListener('click', (event) => {
      event.stopPropagation();
      const isOpen = links.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });

    links.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => links.classList.remove('open'));
    });

    document.addEventListener('click', (event) => {
      if (!links.contains(event.target) && event.target !== toggle) {
        links.classList.remove('open');
      }
    });
  };

  const initNavScroll = () => {
    const nav = document.querySelector('.site-nav');
    if (!nav) return;

    const updateScrolled = () => {
      nav.classList.toggle('is-scrolled', window.scrollY > 40);
    };

    updateScrolled();
    window.addEventListener('scroll', updateScrolled, { passive: true });
  };

  const initNavDropdowns = () => {
    document.querySelectorAll('.nav-item-caret').forEach((caret) => {
      caret.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        const item = caret.closest('.nav-item');
        const isOpen = item.classList.toggle('open');
        caret.setAttribute('aria-expanded', String(isOpen));
      });
    });
  };

  const loadAssetOnce = (selector, create) => {
    if (!document.querySelector(selector)) create();
  };

  const initEnhancementAssets = () => {
    loadAssetOnce('link[href*="cb-final.css"]', () => {
      const l = document.createElement('link');
      l.rel = 'stylesheet';
      l.href = 'cb-final.css?v=nav-logo-1';
      document.head.appendChild(l);
    });
    const afterExtra = () => {
      if (window.CodebridgeI18n) window.CodebridgeI18n.apply(window.CodebridgeI18n.current());
      loadAssetOnce('script[src*="cb-enhancements.js"]', () => {
        const s = document.createElement('script');
        s.src = 'cb-enhancements.js?v=preloader-1';
        document.body.appendChild(s);
      });
    };
    if (window.CB_I18N_EXTRA) {
      afterExtra();
      return;
    }
    loadAssetOnce('script[src*="i18n-extra.js"]', () => {
      const s = document.createElement('script');
      s.src = 'i18n-extra.js?v=db-ai-1';
      s.onload = afterExtra;
      document.head.appendChild(s);
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initLanguageSwitcher();
      initNavScroll();
      initMobileToggle();
      initNavDropdowns();
      initEnhancementAssets();
    });
  } else {
    initLanguageSwitcher();
    initNavScroll();
    initMobileToggle();
    initNavDropdowns();
    initEnhancementAssets();
  }
})();
