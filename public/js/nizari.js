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

});
