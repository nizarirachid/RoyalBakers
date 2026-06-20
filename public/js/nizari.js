/* ============================================================
   NIZARI Rachid - Main JavaScript
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll effect ──────────────────────────────────
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    // ── Scroll to Top ─────────────────────────────────────────
    const scrollBtn = document.getElementById('scrollTop');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            scrollBtn.classList.toggle('visible', window.scrollY > 400);
        });
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ── Counter Animation ─────────────────────────────────────
    const counters = document.querySelectorAll('.stat-number[data-target]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.target);
                let count = 0;
                const step = Math.ceil(target / 60);
                const timer = setInterval(() => {
                    count += step;
                    if (count >= target) {
                        entry.target.textContent = target + (entry.target.dataset.suffix || '');
                        clearInterval(timer);
                    } else {
                        entry.target.textContent = count + (entry.target.dataset.suffix || '');
                    }
                }, 25);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));

    // ── AI Assistant ──────────────────────────────────────────
    const aiToggle = document.getElementById('aiToggle');
    const aiPanel  = document.getElementById('aiPanel');
    const aiClose  = document.getElementById('aiClose');
    const aiInput  = document.getElementById('aiInput');
    const aiSend   = document.getElementById('aiSend');
    const aiMsgs   = document.getElementById('aiMessages');

    const aiKnowledge = {
        'خط': 'يوجد لدينا عدة أساليب: الخط المغربي الأصيل، الخط الأندلسي، خط النسخ، خط الثلث، والخط الكوفي. أي أسلوب يناسبك؟',
        'مقاس': 'المقاسات الشائعة: صغير (20×30cm)، متوسط (40×60cm)، كبير (60×90cm). يمكن أيضاً تحديد مقاسات مخصصة حسب طلبك.',
        'سعر': 'يتم حساب السعر بناءً على: حجم اللوحة × سعر السنتيمتر + ثمن المادة + ثمن العمل. يمكنني مساعدتك في الحساب التلقائي.',
        'مادة': 'المواد المتاحة: ورق، خشب، زجاج، جبس، قماش، جلد. كل مادة لها خصائص فنية مميزة.',
        'توصيل': 'نوفر ثلاث طرق: نسخة رقمية (PNG/PDF/SVG)، شحن بريدي (المغرب أو دولي)، أو استلام شخصي بمراكش.',
        'وقت': 'مدة الإنجاز: 3-7 أيام للأعمال البسيطة، 7-14 يوماً للأعمال الكبيرة أو المعقدة.',
        'تعليم': 'نقدم دروساً فردية وجماعية، حضورياً بمراكش وعن بعد أونلاين. يمكن الاطلاع على الدورات المتاحة في قسم التعليم.',
        'رقمي': 'النسخة الرقمية تُقدم بصيغ عالية الدقة (PNG 300dpi، PDF vector، SVG) مناسبة للطباعة بأي حجم.',
        'مرحبا': 'أهلاً وسهلاً! يسعدني مساعدتك. هل تريد معلومات عن: الأسعار، الخدمات، الدورات، أم طريقة الطلب؟',
        'شكرا': 'شكراً لتواصلك! لا تتردد في طرح أي سؤال آخر. يمكنك أيضاً التواصل مباشرة عبر صفحة الاتصال.',
    };

    function addMsg(text, type) {
        const div = document.createElement('div');
        div.className = `ai-message ai-${type}`;
        div.innerHTML = `<p>${text}</p>`;
        aiMsgs.appendChild(div);
        aiMsgs.scrollTop = aiMsgs.scrollHeight;
    }

    function getReply(text) {
        const lower = text.toLowerCase();
        for (const [key, reply] of Object.entries(aiKnowledge)) {
            if (lower.includes(key)) return reply;
        }
        return 'شكراً لسؤالك. للحصول على معلومات أكثر تفصيلاً، يمكنك التواصل مع الفنان مباشرة عبر صفحة الاتصال أو بالنقر على "طلب عمل فني".';
    }

    function sendAI() {
        const text = aiInput.value.trim();
        if (!text) return;
        addMsg(text, 'user');
        aiInput.value = '';
        setTimeout(() => addMsg(getReply(text), 'bot'), 600);
    }

    if (aiToggle) {
        aiToggle.addEventListener('click', () => {
            const visible = aiPanel.style.display !== 'none';
            aiPanel.style.display = visible ? 'none' : 'flex';
            aiPanel.style.flexDirection = 'column';
        });
    }

    if (aiClose) aiClose.addEventListener('click', () => aiPanel.style.display = 'none');
    if (aiSend) aiSend.addEventListener('click', sendAI);
    if (aiInput) aiInput.addEventListener('keydown', e => e.key === 'Enter' && sendAI());

    // ── Price Calculator ──────────────────────────────────────
    function calculatePrice() {
        const width = parseFloat(document.getElementById('width_cm')?.value) || 0;
        const height = parseFloat(document.getElementById('height_cm')?.value) || 0;
        const materialId = document.getElementById('material_id')?.value;
        const delivery = document.getElementById('delivery_method')?.value;
        const country = document.getElementById('customer_country')?.value || '';
        const includeDigital = document.getElementById('include_digital')?.checked;

        if (!width || !height) return;

        const data = new FormData();
        data.append('width', width);
        data.append('height', height);
        data.append('material_id', materialId);
        data.append('delivery', delivery);
        data.append('country', country);
        data.append('include_digital', includeDigital ? '1' : '0');
        data.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

        fetch('/order/calculate-price', {
            method: 'POST',
            body: data
        })
        .then(r => r.json())
        .then(data => {
            const currency = data.currency || 'MAD';
            const set = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val.toFixed(2) + ' ' + currency;
            };
            set('calc_artwork', data.artwork_price);
            set('calc_material', data.material_price);
            set('calc_labor', data.labor_price);
            set('calc_digital', data.digital_copy_price);
            set('calc_shipping', data.shipping_price);
            set('calc_total', data.total);
        })
        .catch(() => {});
    }

    ['width_cm', 'height_cm', 'material_id', 'delivery_method', 'include_digital']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', calculatePrice);
        });

    // ── Image Lightbox (simple) ───────────────────────────────
    document.querySelectorAll('[data-lightbox]').forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => {
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:99999;
                display:flex;align-items:center;justify-content:center;cursor:zoom-out;
            `;
            const i = document.createElement('img');
            i.src = img.dataset.lightbox || img.src;
            i.style.cssText = 'max-width:90vw;max-height:90vh;object-fit:contain;border-radius:8px;';
            overlay.appendChild(i);
            overlay.addEventListener('click', () => document.body.removeChild(overlay));
            document.body.appendChild(overlay);
        });
    });

    // ── Gallery Filter ────────────────────────────────────────
    document.querySelectorAll('[data-filter-btn]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-filter-btn]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filterBtn;
            document.querySelectorAll('[data-filter-item]').forEach(item => {
                if (filter === 'all' || item.dataset.filterItem === filter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // ── Admin Sidebar Toggle (mobile) ─────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // ── Delete Confirm ────────────────────────────────────────
    document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
        btn.addEventListener('click', e => {
            const msg = btn.dataset.confirmDelete || 'هل أنت متأكد من الحذف؟';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ── Auto-dismiss alerts ───────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // ── Screen Reader Announcer ───────────────────────────────
    function announce(msg) {
        const el = document.getElementById('a11y-announce');
        if (!el) return;
        el.textContent = '';
        setTimeout(() => { el.textContent = msg; }, 50);
    }

    // ── Dark / Light Mode ─────────────────────────────────────
    const html        = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon   = document.getElementById('themeIcon');
    const a11yTheme   = document.getElementById('a11yTheme');

    function setTheme(dark) {
        if (dark) {
            html.setAttribute('data-theme', 'dark');
            localStorage.setItem('nizari-theme', 'dark');
            if (themeIcon) { themeIcon.className = 'fas fa-sun'; }
            if (a11yTheme) a11yTheme.setAttribute('aria-pressed', 'true');
            announce(document.documentElement.lang === 'ar' ? 'تم تفعيل الوضع الداكن' : 'Dark mode enabled');
        } else {
            html.removeAttribute('data-theme');
            localStorage.setItem('nizari-theme', 'light');
            if (themeIcon) { themeIcon.className = 'fas fa-moon'; }
            if (a11yTheme) a11yTheme.setAttribute('aria-pressed', 'false');
            announce(document.documentElement.lang === 'ar' ? 'تم تفعيل الوضع الفاتح' : 'Light mode enabled');
        }
    }

    // Sync icon on load
    if (html.getAttribute('data-theme') === 'dark') {
        if (themeIcon) themeIcon.className = 'fas fa-sun';
        if (a11yTheme) a11yTheme.setAttribute('aria-pressed', 'true');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            setTheme(html.getAttribute('data-theme') !== 'dark');
        });
    }
    if (a11yTheme) {
        a11yTheme.addEventListener('click', () => {
            setTheme(html.getAttribute('data-theme') !== 'dark');
        });
    }

    // ── Fullscreen ────────────────────────────────────────────
    const fsToggle = document.getElementById('fullscreenToggle');
    const fsIcon   = document.getElementById('fullscreenIcon');

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
        } else {
            document.exitFullscreen();
        }
    }

    document.addEventListener('fullscreenchange', () => {
        const isFs = !!document.fullscreenElement;
        if (fsIcon) fsIcon.className = isFs ? 'fas fa-compress' : 'fas fa-expand';
        if (fsToggle) fsToggle.setAttribute('aria-label', isFs ? 'خروج من ملء الشاشة' : 'ملء الشاشة');
    });

    if (fsToggle) fsToggle.addEventListener('click', toggleFullscreen);

    // ── Accessibility Toolbar ─────────────────────────────────
    const a11yToggle  = document.getElementById('a11y-toggle');
    const a11yPanel   = document.getElementById('a11y-panel');
    const a11yContrast  = document.getElementById('a11yContrast');
    const a11yTextNormal = document.getElementById('a11yTextNormal');
    const a11yTextLg  = document.getElementById('a11yTextLg');
    const a11yTextXl  = document.getElementById('a11yTextXl');
    const a11yLinks   = document.getElementById('a11yLinks');
    const a11yCursor  = document.getElementById('a11yCursor');
    const a11yGuide   = document.getElementById('a11yGuide');
    const a11yReset   = document.getElementById('a11yReset');

    function getA11y() {
        try { return JSON.parse(localStorage.getItem('nizari-a11y') || '{}'); } catch(e) { return {}; }
    }
    function saveA11y(obj) {
        localStorage.setItem('nizari-a11y', JSON.stringify(obj));
    }

    // Sync buttons to current state on load
    function syncA11yUI() {
        const s = getA11y();
        if (a11yContrast)  a11yContrast.setAttribute('aria-pressed', s.contrast ? 'true' : 'false');
        if (a11yLinks)     a11yLinks.setAttribute('aria-pressed', s.links ? 'true' : 'false');
        if (a11yCursor)    a11yCursor.setAttribute('aria-pressed', s.cursor ? 'true' : 'false');
        if (a11yTextNormal) a11yTextNormal.setAttribute('aria-pressed', (!s.textSize) ? 'true' : 'false');
        if (a11yTextLg)    a11yTextLg.setAttribute('aria-pressed', s.textSize === 'lg' ? 'true' : 'false');
        if (a11yTextXl)    a11yTextXl.setAttribute('aria-pressed', s.textSize === 'xl' ? 'true' : 'false');
    }
    syncA11yUI();

    // Toggle panel
    if (a11yToggle && a11yPanel) {
        a11yToggle.addEventListener('click', () => {
            const open = !a11yPanel.hidden;
            a11yPanel.hidden = open;
            a11yToggle.setAttribute('aria-expanded', !open);
            if (!open) a11yPanel.querySelector('.a11y-btn')?.focus();
        });
        // Close on Escape
        a11yPanel.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                a11yPanel.hidden = true;
                a11yToggle.setAttribute('aria-expanded', 'false');
                a11yToggle.focus();
            }
        });
    }

    // High contrast
    if (a11yContrast) {
        a11yContrast.addEventListener('click', () => {
            const s = getA11y();
            s.contrast = !s.contrast;
            saveA11y(s);
            if (s.contrast) {
                html.setAttribute('data-contrast', 'high');
                a11yContrast.setAttribute('aria-pressed', 'true');
                announce(html.lang === 'ar' ? 'تم تفعيل التباين العالي' : 'High contrast enabled');
            } else {
                html.removeAttribute('data-contrast');
                a11yContrast.setAttribute('aria-pressed', 'false');
                announce(html.lang === 'ar' ? 'تم إلغاء التباين العالي' : 'High contrast disabled');
            }
        });
    }

    // Text size
    function setTextSize(size) {
        const s = getA11y();
        s.textSize = size;
        saveA11y(s);
        if (size) { html.setAttribute('data-text', size); }
        else      { html.removeAttribute('data-text'); }
        syncA11yUI();
        const labels = { '': 'حجم الخط الافتراضي', 'lg': 'حجم خط كبير', 'xl': 'حجم خط كبير جداً' };
        announce(html.lang === 'ar' ? labels[size || ''] : 'Text size changed');
    }

    if (a11yTextNormal) a11yTextNormal.addEventListener('click', () => setTextSize(''));
    if (a11yTextLg)     a11yTextLg.addEventListener('click',     () => setTextSize('lg'));
    if (a11yTextXl)     a11yTextXl.addEventListener('click',     () => setTextSize('xl'));

    // Underline links
    if (a11yLinks) {
        a11yLinks.addEventListener('click', () => {
            const s = getA11y();
            s.links = !s.links;
            saveA11y(s);
            if (s.links) { html.setAttribute('data-links', 'underline'); a11yLinks.setAttribute('aria-pressed', 'true'); }
            else         { html.removeAttribute('data-links');            a11yLinks.setAttribute('aria-pressed', 'false'); }
            announce(html.lang === 'ar' ? (s.links ? 'تم تسطير الروابط' : 'تم إلغاء تسطير الروابط') : (s.links ? 'Links underlined' : 'Links not underlined'));
        });
    }

    // Large cursor
    if (a11yCursor) {
        a11yCursor.addEventListener('click', () => {
            const s = getA11y();
            s.cursor = !s.cursor;
            saveA11y(s);
            if (s.cursor) { html.setAttribute('data-cursor', 'large'); a11yCursor.setAttribute('aria-pressed', 'true'); }
            else          { html.removeAttribute('data-cursor');         a11yCursor.setAttribute('aria-pressed', 'false'); }
            announce(html.lang === 'ar' ? (s.cursor ? 'مؤشر كبير مفعّل' : 'مؤشر عادي') : (s.cursor ? 'Large cursor enabled' : 'Normal cursor'));
        });
    }

    // Reading guide
    const readingGuide = document.getElementById('reading-guide');
    let guideActive = false;

    if (a11yGuide && readingGuide) {
        a11yGuide.addEventListener('click', () => {
            guideActive = !guideActive;
            readingGuide.hidden = !guideActive;
            a11yGuide.setAttribute('aria-pressed', guideActive ? 'true' : 'false');
            announce(html.lang === 'ar' ? (guideActive ? 'تم تفعيل دليل القراءة' : 'تم إلغاء دليل القراءة') : (guideActive ? 'Reading guide on' : 'Reading guide off'));
        });
        document.addEventListener('mousemove', e => {
            if (!guideActive) return;
            readingGuide.style.top = (e.clientY - 20) + 'px';
        });
    }

    // Reset all accessibility settings
    if (a11yReset) {
        a11yReset.addEventListener('click', () => {
            saveA11y({});
            html.removeAttribute('data-contrast');
            html.removeAttribute('data-text');
            html.removeAttribute('data-links');
            html.removeAttribute('data-cursor');
            if (readingGuide) { readingGuide.hidden = true; guideActive = false; }
            if (a11yGuide) a11yGuide.setAttribute('aria-pressed', 'false');
            syncA11yUI();
            announce(html.lang === 'ar' ? 'تم إعادة ضبط إمكانية الوصول' : 'Accessibility settings reset');
        });
    }

    // ── Keyboard Navigation: Tab trap in modals ───────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Tab') {
            const modal = document.querySelector('.modal.show');
            if (!modal) return;
            const focusable = modal.querySelectorAll('a[href],button:not([disabled]),input,select,textarea,[tabindex]:not([tabindex="-1"])');
            if (!focusable.length) return;
            const first = focusable[0], last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
    });

    // ── F11 key for fullscreen ────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'F11') { e.preventDefault(); toggleFullscreen(); }
    });

});
