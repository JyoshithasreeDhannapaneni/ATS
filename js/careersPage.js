// Legacy CATS button handlers (keep for compatibility)
function buttonMouseOver(txt, tf) {
    return false;
}

/**
 * Neutara Careers - Ultra Premium 3D Animation Engine V3
 */
(function() {
    'use strict';

    // ===== Scroll-triggered reveal with stagger =====
    function initScrollReveal() {
        var els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-rotate, .reveal-blur');
        if (!els.length) return;
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.06, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function(el) { observer.observe(el); });
    }

    // ===== Animated counters with spring easing =====
    function animateCounters() {
        var counters = document.querySelectorAll('.counter');
        counters.forEach(function(counter) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var target = parseInt(counter.getAttribute('data-target')) || 0;
                        var suffix = counter.getAttribute('data-suffix') || '';
                        var startTime = null;
                        function step(ts) {
                            if (!startTime) startTime = ts;
                            var p = Math.min((ts - startTime) / 2000, 1);
                            var eased = 1 - Math.pow(1 - p, 4);
                            counter.textContent = Math.floor(eased * target) + suffix;
                            if (p < 1) requestAnimationFrame(step);
                        }
                        requestAnimationFrame(step);
                        observer.unobserve(counter);
                    }
                });
            }, { threshold: 0.5 });
            observer.observe(counter);
        });
    }

    // ===== Hero parallax on mousemove =====
    function initParallax() {
        var hero = document.querySelector('.hero-premium');
        if (!hero) return;
        var shapes = hero.querySelectorAll('.fl-shape');
        var orbs = hero.querySelectorAll('.hero-orb');
        var targetX = 0, targetY = 0, curX = 0, curY = 0;

        hero.addEventListener('mousemove', function(e) {
            var r = hero.getBoundingClientRect();
            targetX = (e.clientX - r.left) / r.width - 0.5;
            targetY = (e.clientY - r.top) / r.height - 0.5;
        });

        function animate() {
            curX += (targetX - curX) * 0.06;
            curY += (targetY - curY) * 0.06;
            shapes.forEach(function(s, i) {
                var sp = (i + 1) * 25;
                var rot = (i % 2 === 0 ? 1 : -1) * curX * 20;
                s.style.transform = 'translate(' + (curX * sp) + 'px,' + (curY * sp) + 'px) rotate(' + rot + 'deg)';
            });
            orbs.forEach(function(o, i) {
                var sp = (i + 1) * 15;
                o.style.transform = 'translate(' + (curX * sp) + 'px,' + (curY * sp) + 'px)';
            });
            requestAnimationFrame(animate);
        }
        animate();
    }

    // ===== 3D tilt on cards =====
    function initTiltCards() {
        var cards = document.querySelectorAll('.tilt-card');
        cards.forEach(function(card) {
            var targetRX = 0, targetRY = 0, curRX = 0, curRY = 0;
            var hovering = false;

            card.addEventListener('mousemove', function(e) {
                hovering = true;
                var r = card.getBoundingClientRect();
                var x = (e.clientX - r.left) / r.width;
                var y = (e.clientY - r.top) / r.height;
                targetRX = (0.5 - y) * 20;
                targetRY = (x - 0.5) * 20;
                var shine = card.querySelector('.card-shine');
                if (shine) shine.style.background = 'radial-gradient(circle at ' + (x*100) + '% ' + (y*100) + '%, rgba(255,255,255,0.25) 0%, transparent 60%)';
            });

            card.addEventListener('mouseleave', function() {
                hovering = false;
                targetRX = 0;
                targetRY = 0;
                var shine = card.querySelector('.card-shine');
                if (shine) shine.style.background = 'transparent';
            });

            function animateCard() {
                curRX += (targetRX - curRX) * 0.1;
                curRY += (targetRY - curRY) * 0.1;
                var sc = hovering ? 1.04 : 1;
                card.style.transform = 'perspective(800px) rotateX(' + curRX + 'deg) rotateY(' + curRY + 'deg) scale3d(' + sc + ',' + sc + ',' + sc + ')';
                requestAnimationFrame(animateCard);
            }
            animateCard();
        });
    }

    // ===== Nav glassmorphism on scroll =====
    function initNavScroll() {
        var nav = document.querySelector('.career-header');
        if (!nav) return;
        var lastScroll = 0;
        window.addEventListener('scroll', function() {
            var y = window.scrollY;
            if (y > 60) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
            if (y > 400 && y > lastScroll) nav.classList.add('header-hide');
            else nav.classList.remove('header-hide');
            lastScroll = y;
        }, { passive: true });
    }

    // ===== Typing animation =====
    function initTyping() {
        var el = document.querySelector('.typing-text');
        if (!el) return;
        var words = ['Innovation', 'Excellence', 'Growth', 'Impact', 'Leadership'];
        var wi = 0, ci = 0, del = false;
        function tick() {
            var w = words[wi];
            el.textContent = del ? w.substring(0, --ci) : w.substring(0, ++ci);
            if (!del && ci === w.length) { setTimeout(function(){ del = true; tick(); }, 2200); return; }
            if (del && ci === 0) { del = false; wi = (wi + 1) % words.length; }
            setTimeout(tick, del ? 40 : 90);
        }
        setTimeout(tick, 800);
    }

    // ===== Stagger children =====
    function initStagger() {
        document.querySelectorAll('.stagger-children').forEach(function(c) {
            var obs = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var ch = entry.target.children;
                        for (var i = 0; i < ch.length; i++) {
                            (function(el, d) { setTimeout(function(){ el.classList.add('stagger-visible'); }, d); })(ch[i], i * 130);
                        }
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            obs.observe(c);
        });
    }

    // ===== Table row entrance animation =====
    function initTableAnimations() {
        var rows = document.querySelectorAll('tr.evenTableRow, tr.oddTableRow');
        rows.forEach(function(row, i) {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            setTimeout(function() {
                row.style.transition = 'opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1)';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 200 + i * 120);
        });
    }

    // ===== Magnetic button hover =====
    function initMagneticButtons() {
        document.querySelectorAll('.magnetic-btn').forEach(function(btn) {
            btn.addEventListener('mousemove', function(e) {
                var r = btn.getBoundingClientRect();
                btn.style.transform = 'translate(' + ((e.clientX-r.left-r.width/2)*0.25) + 'px,' + ((e.clientY-r.top-r.height/2)*0.25) + 'px)';
            });
            btn.addEventListener('mouseleave', function() { btn.style.transform = ''; });
        });
    }

    // ===== Advanced particle network with glow =====
    function initParticles() {
        var canvas = document.getElementById('particleCanvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var pts = [];
        var mouse = { x: -999, y: -999 };

        function resize() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = canvas.parentElement.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        canvas.addEventListener('mousemove', function(e) {
            var r = canvas.getBoundingClientRect();
            mouse.x = e.clientX - r.left;
            mouse.y = e.clientY - r.top;
        });
        canvas.addEventListener('mouseleave', function() { mouse.x = -999; mouse.y = -999; });

        for (var i = 0; i < 90; i++) {
            pts.push({
                x: Math.random() * canvas.width, y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5, vy: (Math.random() - 0.5) * 0.5,
                r: Math.random() * 2.5 + 0.5, o: Math.random() * 0.5 + 0.1,
                pulse: Math.random() * Math.PI * 2
            });
        }

        function frame() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (var i = 0; i < pts.length; i++) {
                pts[i].pulse += 0.02;
                var po = pts[i].o + Math.sin(pts[i].pulse) * 0.15;

                // Mouse attraction
                var dx0 = mouse.x - pts[i].x;
                var dy0 = mouse.y - pts[i].y;
                var d0 = Math.sqrt(dx0*dx0 + dy0*dy0);
                if (d0 < 200 && d0 > 0) {
                    pts[i].vx += (dx0 / d0) * 0.02;
                    pts[i].vy += (dy0 / d0) * 0.02;
                }

                for (var j = i + 1; j < pts.length; j++) {
                    var dx = pts[i].x - pts[j].x, dy = pts[i].y - pts[j].y;
                    var d = Math.sqrt(dx*dx + dy*dy);
                    if (d < 150) {
                        var alpha = 0.08 * (1 - d/150);
                        ctx.beginPath();
                        ctx.strokeStyle = 'rgba(165,140,255,' + alpha + ')';
                        ctx.lineWidth = 0.6;
                        ctx.moveTo(pts[i].x, pts[i].y);
                        ctx.lineTo(pts[j].x, pts[j].y);
                        ctx.stroke();
                    }
                }

                // Glow dot
                var grad = ctx.createRadialGradient(pts[i].x, pts[i].y, 0, pts[i].x, pts[i].y, pts[i].r * 4);
                grad.addColorStop(0, 'rgba(165,140,255,' + (po * 0.8) + ')');
                grad.addColorStop(0.5, 'rgba(99,102,241,' + (po * 0.3) + ')');
                grad.addColorStop(1, 'rgba(99,102,241,0)');
                ctx.beginPath();
                ctx.arc(pts[i].x, pts[i].y, pts[i].r * 4, 0, Math.PI * 2);
                ctx.fillStyle = grad;
                ctx.fill();

                ctx.beginPath();
                ctx.arc(pts[i].x, pts[i].y, pts[i].r, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(200,190,255,' + po + ')';
                ctx.fill();

                pts[i].vx *= 0.99;
                pts[i].vy *= 0.99;
                pts[i].x += pts[i].vx;
                pts[i].y += pts[i].vy;
                if (pts[i].x < 0 || pts[i].x > canvas.width) pts[i].vx *= -1;
                if (pts[i].y < 0 || pts[i].y > canvas.height) pts[i].vy *= -1;
            }

            // Mouse glow ring
            if (mouse.x > 0 && mouse.y > 0) {
                var mg = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, 120);
                mg.addColorStop(0, 'rgba(139,92,246,0.12)');
                mg.addColorStop(0.5, 'rgba(79,70,229,0.04)');
                mg.addColorStop(1, 'rgba(79,70,229,0)');
                ctx.beginPath();
                ctx.arc(mouse.x, mouse.y, 120, 0, Math.PI * 2);
                ctx.fillStyle = mg;
                ctx.fill();
            }

            requestAnimationFrame(frame);
        }
        frame();
    }

    // ===== Morphing blob SVG =====
    function initMorphBlobs() {
        var blobs = document.querySelectorAll('.morph-blob');
        blobs.forEach(function(blob) {
            var paths = blob.querySelectorAll('path');
            if (paths.length < 2) return;
            var d1 = paths[0].getAttribute('d');
            var d2 = paths[1] ? paths[1].getAttribute('d') : d1;
            paths[0].style.transition = 'none';
            var forward = true;
            setInterval(function() {
                paths[0].style.transition = 'd 4s cubic-bezier(0.45,0,0.55,1)';
                paths[0].setAttribute('d', forward ? d2 : d1);
                forward = !forward;
            }, 4000);
        });
    }

    // ===== Ripple click effect =====
    function initRipple() {
        document.querySelectorAll('.nav-btn, .hero-btn-primary, .hero-btn-secondary, input[type="submit"]').forEach(function(btn) {
            btn.style.position = 'relative';
            btn.style.overflow = 'hidden';
            btn.addEventListener('click', function(e) {
                var r = btn.getBoundingClientRect();
                var ripple = document.createElement('span');
                ripple.className = 'ripple-effect';
                ripple.style.left = (e.clientX - r.left) + 'px';
                ripple.style.top = (e.clientY - r.top) + 'px';
                btn.appendChild(ripple);
                setTimeout(function() { ripple.remove(); }, 800);
            });
        });
    }

    // ===== Smooth scroll for anchor links =====
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(a) {
            a.addEventListener('click', function(e) {
                var target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    // ===== Floating orbs ambient animation =====
    function initFloatingOrbs() {
        var container = document.querySelector('.hero-premium');
        if (!container) return;
        for (var i = 0; i < 5; i++) {
            var orb = document.createElement('div');
            orb.className = 'hero-orb';
            orb.style.cssText = 'position:absolute;border-radius:50%;pointer-events:none;z-index:3;' +
                'width:' + (60 + Math.random()*100) + 'px;' +
                'height:' + (60 + Math.random()*100) + 'px;' +
                'top:' + (Math.random()*80) + '%;' +
                'left:' + (Math.random()*80) + '%;' +
                'background:radial-gradient(circle,rgba(' + (i%2===0?'139,92,246':'56,189,248') + ',0.15) 0%,transparent 70%);' +
                'animation:orbFloat ' + (8+Math.random()*6) + 's ease-in-out infinite;' +
                'animation-delay:-' + (Math.random()*5) + 's;' +
                'filter:blur(' + (10+Math.random()*20) + 'px);';
            container.appendChild(orb);
        }
    }

    // ===== Gradient mesh background animation =====
    function initGradientMesh() {
        var mesh = document.getElementById('gradientMesh');
        if (!mesh) return;
        var ctx = mesh.getContext('2d');
        var w, h;
        var blobs = [];

        function resize() {
            w = mesh.width = mesh.parentElement.offsetWidth;
            h = mesh.height = mesh.parentElement.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        var colors = [
            [79, 70, 229, 0.3],
            [124, 58, 237, 0.25],
            [14, 165, 233, 0.2],
            [139, 92, 246, 0.2],
            [6, 182, 212, 0.15]
        ];

        for (var i = 0; i < 5; i++) {
            blobs.push({
                x: Math.random() * w, y: Math.random() * h,
                vx: (Math.random() - 0.5) * 0.8, vy: (Math.random() - 0.5) * 0.8,
                radius: 150 + Math.random() * 200,
                color: colors[i % colors.length]
            });
        }

        function frame() {
            ctx.clearRect(0, 0, w, h);
            blobs.forEach(function(b) {
                b.x += b.vx; b.y += b.vy;
                if (b.x < -100 || b.x > w + 100) b.vx *= -1;
                if (b.y < -100 || b.y > h + 100) b.vy *= -1;
                var g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, b.radius);
                g.addColorStop(0, 'rgba(' + b.color[0] + ',' + b.color[1] + ',' + b.color[2] + ',' + b.color[3] + ')');
                g.addColorStop(1, 'rgba(' + b.color[0] + ',' + b.color[1] + ',' + b.color[2] + ',0)');
                ctx.beginPath();
                ctx.arc(b.x, b.y, b.radius, 0, Math.PI * 2);
                ctx.fillStyle = g;
                ctx.fill();
            });
            requestAnimationFrame(frame);
        }
        frame();
    }

    // ===== 3D Scene depth - parallax sections on scroll =====
    function initScrollDepth() {
        var sections = document.querySelectorAll('.depth-section');
        if (!sections.length) return;
        window.addEventListener('scroll', function() {
            var scrollY = window.scrollY;
            sections.forEach(function(sec) {
                var rect = sec.getBoundingClientRect();
                var center = rect.top + rect.height / 2;
                var viewCenter = window.innerHeight / 2;
                var offset = (center - viewCenter) / window.innerHeight;
                var bg = sec.querySelector('.depth-bg');
                if (bg) bg.style.transform = 'translateY(' + (offset * -30) + 'px) scale(1.05)';
            });
        }, { passive: true });
    }

    // ===== Form progress bar =====
    function initFormProgress() {
        var form = document.getElementById('applyToJobForm');
        if (!form) return;
        var inputs = form.querySelectorAll('input[type="text"], input.inputBoxName, input.inputBoxNormal, textarea.inputBoxArea');
        var bar = document.getElementById('formProgress');
        if (!inputs.length || !bar) return;

        function update() {
            var filled = 0;
            inputs.forEach(function(inp) { if (inp.value.trim()) filled++; });
            var pct = Math.round((filled / inputs.length) * 100);
            bar.style.width = pct + '%';
            var lbl = document.getElementById('formProgressLabel');
            if (lbl) lbl.textContent = pct + '% complete';
        }
        inputs.forEach(function(inp) { inp.addEventListener('input', update); });
    }

    // ===== Cursor glow trail on hero =====
    function initCursorGlow() {
        var hero = document.querySelector('.hero-premium');
        if (!hero) return;
        var glow = document.createElement('div');
        glow.className = 'cursor-glow';
        hero.appendChild(glow);
        hero.addEventListener('mousemove', function(e) {
            var r = hero.getBoundingClientRect();
            glow.style.left = (e.clientX - r.left) + 'px';
            glow.style.top = (e.clientY - r.top) + 'px';
            glow.style.opacity = '1';
        });
        hero.addEventListener('mouseleave', function() { glow.style.opacity = '0'; });
    }

    // ===== Text split & wave animation =====
    function initTextWave() {
        document.querySelectorAll('.text-wave').forEach(function(el) {
            var text = el.textContent;
            el.textContent = '';
            for (var i = 0; i < text.length; i++) {
                var span = document.createElement('span');
                span.textContent = text[i] === ' ' ? ' ' : text[i];
                span.style.animationDelay = (i * 0.04) + 's';
                span.className = 'wave-char';
                el.appendChild(span);
            }
        });
    }

    // ===== Page load =====
    function initPageLoad() {
        document.body.classList.add('page-loaded');
    }

    // ===== Init all =====
    function init() {
        initPageLoad();
        initScrollReveal();
        animateCounters();
        initParallax();
        initTiltCards();
        initNavScroll();
        initTyping();
        initStagger();
        initTableAnimations();
        initMagneticButtons();
        initParticles();
        initMorphBlobs();
        initRipple();
        initSmoothScroll();
        initFloatingOrbs();
        initGradientMesh();
        initScrollDepth();
        initFormProgress();
        initCursorGlow();
        initTextWave();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
