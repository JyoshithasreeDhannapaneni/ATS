<?php
$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");


// =====================================================
// CSS - V3 Ultra 3D Premium
// =====================================================
$css = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-darker: #3730a3;
    --primary-light: #e0e7ff;
    --primary-50: #eef2ff;
    --accent: #06b6d4;
    --violet: #7c3aed;
    --violet-light: #ede9fe;
    --indigo: #4f46e5;
    --emerald: #059669;
    --emerald-light: #d1fae5;
    --amber: #d97706;
    --rose: #e11d48;
    --sky: #0ea5e9;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --font: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
    --radius: 12px;
    --radius-lg: 20px;
    --radius-xl: 28px;
    --radius-2xl: 32px;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
    --shadow-xl: 0 24px 60px rgba(0,0,0,0.1), 0 8px 24px rgba(0,0,0,0.06);
    --shadow-glow: 0 8px 40px rgba(79,70,229,0.3);
    --shadow-glow-lg: 0 16px 60px rgba(79,70,229,0.4);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body, html {
    font-family: var(--font);
    color: var(--gray-800);
    background: #f0f2f7;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}
body {
    display: flex; flex-direction: column; min-height: 100vh;
    opacity: 0; transition: opacity 0.6s ease;
}
body.page-loaded { opacity: 1; }
a { color: var(--primary); text-decoration: none; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
a:hover { color: var(--primary-dark); }
a:visited { color: var(--primary); }
img { border: none; }

/* ===== SCROLL REVEALS - 3D transforms ===== */
.reveal { opacity:0; transform:translateY(60px) rotateX(8deg); transition: opacity 1s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1); transform-origin: bottom center; }
.reveal.revealed { opacity:1; transform:translateY(0) rotateX(0); }
.reveal-left { opacity:0; transform:translateX(-80px) rotateY(8deg); transition: opacity 1s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1); }
.reveal-left.revealed { opacity:1; transform:translateX(0) rotateY(0); }
.reveal-right { opacity:0; transform:translateX(80px) rotateY(-8deg); transition: opacity 1s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1); }
.reveal-right.revealed { opacity:1; transform:translateX(0) rotateY(0); }
.reveal-scale { opacity:0; transform:scale(0.75) rotateX(10deg); transition: opacity 1s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1); }
.reveal-scale.revealed { opacity:1; transform:scale(1) rotateX(0); }
.reveal-rotate { opacity:0; transform:rotate3d(1,1,0,15deg) scale(0.9); transition: opacity 1s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1); }
.reveal-rotate.revealed { opacity:1; transform:rotate3d(0,0,0,0) scale(1); }
.reveal-blur { opacity:0; transform:translateY(30px); filter:blur(10px); transition: opacity 1s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1), filter 1s cubic-bezier(0.22,1,0.36,1); }
.reveal-blur.revealed { opacity:1; transform:translateY(0); filter:blur(0); }
.stagger-children > * { opacity:0; transform:translateY(40px) rotateX(5deg); transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1); }
.stagger-children > *.stagger-visible { opacity:1; transform:translateY(0) rotateX(0); }

/* ===== RIPPLE CLICK ===== */
.ripple-effect {
    position:absolute; border-radius:50%; background:rgba(255,255,255,0.4);
    width:10px; height:10px; transform:translate(-50%,-50%) scale(0);
    animation:rippleAnim 0.8s cubic-bezier(0,0,0.2,1) forwards; pointer-events:none; z-index:50;
}
@keyframes rippleAnim { to { transform:translate(-50%,-50%) scale(40); opacity:0; } }

/* ===== CURSOR GLOW ===== */
.cursor-glow {
    position:absolute; width:350px; height:350px; border-radius:50%; pointer-events:none; z-index:4;
    background:radial-gradient(circle, rgba(139,92,246,0.15) 0%, rgba(79,70,229,0.05) 40%, transparent 70%);
    transform:translate(-50%,-50%); opacity:0; transition:opacity 0.4s;
    mix-blend-mode: screen;
}

/* ===== ORB FLOAT ===== */
@keyframes orbFloat {
    0%,100% { transform:translateY(0) translateX(0) scale(1); }
    25% { transform:translateY(-30px) translateX(15px) scale(1.1); }
    50% { transform:translateY(10px) translateX(-20px) scale(0.95); }
    75% { transform:translateY(-15px) translateX(10px) scale(1.05); }
}

/* ===== WAVE TEXT ===== */
.wave-char { display:inline-block; animation:waveChar 0.6s cubic-bezier(0.22,1,0.36,1) both; }
@keyframes waveChar { from{opacity:0;transform:translateY(20px) rotateX(90deg);} to{opacity:1;transform:translateY(0) rotateX(0);} }

/* ===== HEADER - Premium Glass ===== */
.career-header {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
    background: rgba(255,255,255,0.65);
    backdrop-filter: blur(30px) saturate(200%); -webkit-backdrop-filter: blur(30px) saturate(200%);
    border-bottom: 1px solid rgba(255,255,255,0.4);
    transition: all 0.5s cubic-bezier(0.4,0,0.2,1);
}
.career-header.scrolled {
    background: rgba(255,255,255,0.9);
    box-shadow: 0 8px 40px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
}
.career-header.header-hide { transform: translateY(-100%); }
.header-inner {
    max-width: 1320px; margin: 0 auto;
    padding: 14px 40px;
    display: flex; align-items: center; justify-content: space-between;
}
.brand { display: flex; align-items: center; gap: 14px; text-decoration: none; }
.brand-icon {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 50%, #a855f7 100%);
    border-radius: 16px; display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 900; font-size: 21px;
    box-shadow: 0 4px 20px rgba(79,70,229,0.4), inset 0 1px 0 rgba(255,255,255,0.25);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    animation: brandPulse 3s ease-in-out infinite;
}
@keyframes brandPulse {
    0%,100% { box-shadow:0 4px 20px rgba(79,70,229,0.4); }
    50% { box-shadow:0 4px 30px rgba(124,58,237,0.6); }
}
.brand:hover .brand-icon { transform: rotate(-8deg) scale(1.1); box-shadow: 0 8px 35px rgba(79,70,229,0.5); }
.brand-text { font-size: 24px; font-weight: 800; color: var(--gray-900); letter-spacing: -0.03em; }
.brand-text span { background: linear-gradient(135deg, var(--primary), var(--violet), #a855f7); background-size:200% 200%; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; animation:gradientShift 4s ease infinite; }
.header-nav { display: flex; align-items: center; gap: 6px; }
.header-nav a { font-size: 14px; font-weight: 600; color: var(--gray-500); padding: 10px 18px; border-radius: var(--radius); transition: all 0.25s; }
.header-nav a:hover { color: var(--primary); background: var(--primary-50); }
.nav-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 26px; border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 50%, #a855f7 100%);
    background-size: 200% 200%; animation: gradientShift 4s ease infinite;
    color: #fff !important; font-weight: 700; font-size: 14px;
    box-shadow: var(--shadow-glow), inset 0 1px 0 rgba(255,255,255,0.2);
    transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
    position: relative; overflow: hidden;
}
.nav-btn::before {
    content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.7s ease;
}
.nav-btn:hover::before { left: 100%; }
.nav-btn:hover { transform: translateY(-3px); box-shadow: var(--shadow-glow-lg); }
.nav-btn:active { transform: translateY(0); }

/* ===== HERO SECTION - 3D Immersive ===== */
.hero-premium {
    position: relative; overflow: hidden;
    background: linear-gradient(160deg, #050816 0%, #0f0c29 20%, #1a1145 40%, #302b63 60%, #4f46e5 85%, #7c3aed 100%);
    padding: 170px 40px 110px; text-align: center; color: #fff;
    min-height: 600px; perspective: 1200px;
}
.hero-premium::before {
    content:''; position:absolute; inset:0; z-index:2;
    background: radial-gradient(ellipse 80% 50% at 50% 40%, rgba(79,70,229,0.15) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(6,182,212,0.1) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 80% 20%, rgba(168,85,247,0.1) 0%, transparent 60%);
}
.hero-premium::after {
    content:''; position:absolute; bottom:0; left:0; right:0; height:200px; z-index:5;
    background: linear-gradient(to top, #f0f2f7 0%, transparent 100%);
    pointer-events:none;
}
.hero-premium canvas { position: absolute; inset: 0; z-index: 3; }
#gradientMesh { position: absolute; inset: 0; z-index: 2; opacity: 0.6; }

.fl-shape {
    position: absolute; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
    transition: transform 0.15s ease-out; z-index: 4;
    animation: floatShape 12s ease-in-out infinite;
    backdrop-filter: blur(1px);
}
@keyframes floatShape {
    0%,100% { transform: translateY(0) scale(1) rotate(0deg); }
    25% { transform: translateY(-30px) scale(1.08) rotate(3deg); }
    50% { transform: translateY(10px) scale(0.96) rotate(-2deg); }
    75% { transform: translateY(-20px) scale(1.04) rotate(1deg); }
}

/* 3D Grid floor effect */
.hero-grid {
    position: absolute; bottom: 0; left: -20%; right: -20%; height: 60%; z-index: 2;
    background: linear-gradient(to top, rgba(79,70,229,0.15) 0%, transparent 100%);
    transform: perspective(500px) rotateX(60deg);
    transform-origin: bottom center;
    background-image:
        linear-gradient(rgba(99,102,241,0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99,102,241,0.1) 1px, transparent 1px);
    background-size: 60px 40px;
    animation: gridScroll 20s linear infinite;
    mask-image: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 80%);
}
@keyframes gridScroll { from{background-position:0 0;} to{background-position:0 -400px;} }

.hero-inner { position: relative; z-index: 10; max-width: 860px; margin: 0 auto; }
.hero-inner > * { animation: heroSlideUp 1.2s cubic-bezier(0.22,1,0.36,1) both; }
.hero-inner > *:nth-child(1) { animation-delay: 0.15s; }
.hero-inner > *:nth-child(2) { animation-delay: 0.3s; }
.hero-inner > *:nth-child(3) { animation-delay: 0.45s; }
.hero-inner > *:nth-child(4) { animation-delay: 0.6s; }
.hero-inner > *:nth-child(5) { animation-delay: 0.75s; }
@keyframes heroSlideUp {
    from { opacity:0; transform:translateY(60px) rotateX(10deg); filter:blur(8px); }
    to { opacity:1; transform:translateY(0) rotateX(0); filter:blur(0); }
}

.hero-badge {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 10px 24px; border-radius: 50px;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(16px); font-size: 13px; font-weight: 700;
    color: rgba(255,255,255,0.8); margin-bottom: 28px;
    letter-spacing: 0.04em; text-transform: uppercase;
    box-shadow: 0 4px 24px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.06);
}
.hero-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 16px #34d399, 0 0 40px rgba(52,211,153,0.3); animation: glow 2s infinite; }
@keyframes glow { 0%,100% { opacity:1; box-shadow:0 0 16px #34d399, 0 0 40px rgba(52,211,153,0.3); } 50% { opacity:0.5; box-shadow:0 0 6px #34d399; } }

.hero-premium h1 {
    font-size: 58px; font-weight: 900; letter-spacing: -0.05em;
    line-height: 1.06; margin-bottom: 24px;
    color: #fff; background: none; -webkit-text-fill-color: #fff;
    text-shadow: 0 4px 40px rgba(79,70,229,0.3);
}
.typing-text {
    background: linear-gradient(135deg, #38bdf8, #a78bfa, #fb7185, #fbbf24, #34d399);
    background-size: 400% 400%;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: gradientShift 5s ease infinite;
}
@keyframes gradientShift { 0%{background-position:0 50%} 50%{background-position:100% 50%} 100%{background-position:0 50%} }
.typing-cursor { display:inline-block; width:3px; height:0.85em; background:linear-gradient(to bottom,#a78bfa,#38bdf8); margin-left:4px; vertical-align:text-bottom; animation:blink 1s step-end infinite; border-radius:2px; }
@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0;} }
.hero-premium p { font-size: 20px; color: rgba(255,255,255,0.55); line-height: 1.75; max-width: 600px; margin: 0 auto 40px; font-weight: 400; }

.hero-cta { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
.hero-btn-primary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 18px 42px; border-radius: 18px;
    background: #fff; color: var(--primary-dark) !important; font-weight: 800; font-size: 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.2), 0 2px 8px rgba(0,0,0,0.1), inset 0 -2px 0 rgba(0,0,0,0.06);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    letter-spacing: -0.01em; position:relative; overflow:hidden;
}
.hero-btn-primary::after {
    content:''; position:absolute; inset:-2px; border-radius:20px; padding:2px; z-index:-1;
    background: linear-gradient(135deg, var(--primary), var(--violet), var(--accent));
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite: xor;
    mask-composite: exclude; opacity:0; transition:opacity 0.4s;
}
.hero-btn-primary:hover::after { opacity:1; }
.hero-btn-primary:hover { transform: translateY(-5px) scale(1.03); box-shadow: 0 24px 60px rgba(0,0,0,0.25), 0 4px 12px rgba(0,0,0,0.1); }
.hero-btn-secondary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 18px 42px; border-radius: 18px;
    background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.15);
    color: #fff !important; font-weight: 700; font-size: 16px;
    backdrop-filter: blur(16px); transition: all 0.4s; position:relative; overflow:hidden;
}
.hero-btn-secondary:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.3); transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.15); }

/* ===== STATS ROW - 3D Glass ===== */
.stats-glass {
    max-width: 920px; margin: -60px auto 0; position: relative; z-index: 20;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
    border-radius: var(--radius-2xl); padding: 8px;
    box-shadow: var(--shadow-xl), 0 0 0 1px rgba(255,255,255,0.6);
    border: 1px solid rgba(255,255,255,0.7);
    display: grid; grid-template-columns: repeat(4, 1fr);
    transform: translateZ(0);
}
.stat-glass {
    padding: 30px 20px; text-align: center;
    border-radius: var(--radius-xl); transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    position: relative;
}
.stat-glass:hover { background: var(--primary-50); transform: translateY(-4px); }
.stat-glass::after { content:''; position:absolute; right:0; top:20%; bottom:20%; width:1px; background:var(--gray-200); }
.stat-glass:last-child::after { display:none; }
.stat-glass .stat-num { font-size: 38px; font-weight: 900; color: var(--gray-900); display: block; letter-spacing: -0.04em; }
.stat-glass .stat-label { font-size: 12px; color: var(--gray-400); font-weight: 700; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.08em; }

/* ===== MAIN CONTENT ===== */
.main-content {
    max-width: 1320px; margin: 0 auto; padding: 80px 40px; flex: 1; width: 100%;
    margin-top: 72px;
}
.section-tag {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 20px; border-radius: 50px;
    background: linear-gradient(135deg, var(--primary-50), var(--violet-light));
    color: var(--primary); font-size: 12px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 18px;
    border: 1px solid var(--primary-light);
    box-shadow: 0 2px 12px rgba(79,70,229,0.08);
}
.section-title { font-size: 46px; font-weight: 900; color: var(--gray-900); margin-bottom: 14px; letter-spacing: -0.045em; line-height: 1.08; }
.section-subtitle { font-size: 18px; color: var(--gray-500); margin-bottom: 56px; max-width: 580px; line-height: 1.75; }

/* ===== 3D TILT CARDS ===== */
.tilt-card {
    background: #fff; border-radius: var(--radius-xl); padding: 40px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: var(--shadow-md);
    transition: box-shadow 0.5s;
    position: relative; overflow: hidden;
    transform-style: preserve-3d; will-change: transform;
}
.tilt-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background: linear-gradient(90deg, var(--primary), var(--violet), var(--accent));
    background-size: 200% 100%; animation: gradientShift 4s ease infinite;
    opacity:0; transition:opacity 0.4s;
}
.tilt-card:hover::before { opacity:1; }
.tilt-card:hover { box-shadow: var(--shadow-xl); }
.card-shine { position: absolute; inset: 0; pointer-events: none; transition: background 0.2s; z-index: 1; border-radius: var(--radius-xl); }
.card-icon {
    width: 64px; height: 64px; border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 30px; margin-bottom: 24px; position: relative; z-index: 2;
    transition: transform 0.4s cubic-bezier(0.4,0,0.2,1);
}
.tilt-card:hover .card-icon { transform: translateY(-4px) scale(1.1) rotate(-3deg); }
.card-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); box-shadow: 0 4px 16px rgba(59,130,246,0.15); }
.card-icon.violet { background: linear-gradient(135deg, #ede9fe, #ddd6fe); box-shadow: 0 4px 16px rgba(124,58,237,0.15); }
.card-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); box-shadow: 0 4px 16px rgba(5,150,105,0.15); }
.card-icon.amber { background: linear-gradient(135deg, #fef3c7, #fde68a); box-shadow: 0 4px 16px rgba(217,119,6,0.15); }
.tilt-card h3 { font-size: 21px; font-weight: 800; color: var(--gray-900); margin: 0 0 12px; z-index: 2; position: relative; }
.tilt-card p { font-size: 15px; color: var(--gray-500); line-height: 1.75; margin: 0; z-index: 2; position: relative; }

/* ===== TESTIMONIAL / CULTURE CARDS ===== */
.culture-card {
    background: #fff; border-radius: var(--radius-xl); padding: 36px;
    border: 1px solid rgba(0,0,0,0.03); position: relative;
    box-shadow: var(--shadow-md);
    transition: all 0.5s cubic-bezier(0.4,0,0.2,1);
    overflow:hidden;
}
.culture-card::before {
    content:''; position:absolute; inset:-1px; border-radius:inherit; padding:1px;
    background: linear-gradient(135deg, transparent 30%, var(--primary-light) 50%, transparent 70%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite: xor; mask-composite: exclude;
    opacity:0; transition:opacity 0.5s;
}
.culture-card:hover::before { opacity:1; }
.culture-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-xl); }
.culture-card .culture-icon {
    width:56px; height:56px; border-radius:16px; display:inline-flex; align-items:center; justify-content:center;
    font-size:28px; margin-bottom:20px;
}
.culture-card h3 { font-size:18px; font-weight:800; color:var(--gray-900); margin:0 0 10px; }
.culture-card p { font-size:14px; color:var(--gray-500); line-height:1.7; margin:0; }

/* ===== PROCESS / TIMELINE ===== */
.process-step {
    display:flex; align-items:flex-start; gap:24px; position:relative;
    padding:32px 0;
}
.process-step::before {
    content:''; position:absolute; left:27px; top:80px; bottom:0; width:2px;
    background: linear-gradient(to bottom, var(--primary-light), transparent);
}
.process-step:last-child::before { display:none; }
.process-num {
    width:56px; height:56px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    background: linear-gradient(135deg, var(--primary), var(--violet));
    color:#fff; font-weight:900; font-size:20px;
    box-shadow: var(--shadow-glow);
    position:relative; z-index:2;
}
.process-content h3 { font-size:20px; font-weight:800; color:var(--gray-900); margin:0 0 8px; }
.process-content p { font-size:15px; color:var(--gray-500); line-height:1.7; margin:0; }

/* ===== JOB TABLE → 3D CARD ROWS ===== */
table.sortable {
    width: 100%; border-collapse: separate; border-spacing: 0 16px;
    background: transparent;
    border: none; box-shadow: none; border-radius: 0; overflow: visible;
}
tr.rowHeading {
    background: transparent !important;
}
tr.rowHeading th {
    padding: 8px 28px; color: var(--gray-400); font-weight: 700; font-size: 11px;
    text-transform: uppercase; letter-spacing: 0.12em; border: none; text-align: left;
    background: transparent;
}
tr.evenTableRow, tr.oddTableRow {
    background: #fff !important;
    border-radius: var(--radius-lg);
    transition: all 0.5s cubic-bezier(0.4,0,0.2,1);
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    position: relative;
}
tr.evenTableRow:hover, tr.oddTableRow:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 24px 60px rgba(79,70,229,0.12), 0 8px 24px rgba(0,0,0,0.06);
    z-index: 2;
}
tr.evenTableRow td, tr.oddTableRow td {
    padding: 26px 28px;
    border-top: 1px solid var(--gray-100);
    border-bottom: 1px solid var(--gray-100);
    font-size: 15px; color: var(--gray-600); font-weight: 500;
    background: #fff;
    vertical-align: middle;
    transition: all 0.4s;
}
tr.evenTableRow td:first-child, tr.oddTableRow td:first-child {
    border-left: 1px solid var(--gray-100);
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
    border-left: 4px solid var(--primary);
    position:relative;
}
tr.evenTableRow:hover td:first-child::before, tr.oddTableRow:hover td:first-child::before {
    content:''; position:absolute; left:-4px; top:0; bottom:0; width:4px;
    background: linear-gradient(to bottom, var(--primary), var(--violet));
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}
tr.evenTableRow td:last-child, tr.oddTableRow td:last-child {
    border-right: 1px solid var(--gray-100);
    border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
}
tr.evenTableRow:hover td, tr.oddTableRow:hover td { border-color: rgba(79,70,229,0.12); background: #fefeff; }

tr.evenTableRow td a, tr.oddTableRow td a {
    color: var(--primary); font-weight: 800; font-size: 16px;
    letter-spacing: -0.01em;
    background: linear-gradient(135deg, var(--primary), var(--violet));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    transition: all 0.3s;
}
tr.evenTableRow:hover td a, tr.oddTableRow:hover td a {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    -webkit-background-clip: text; background-clip: text;
}

/* ===== JOB DETAILS ===== */
.job-detail-card {
    background: #fff; border-radius: var(--radius-2xl); padding: 56px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: var(--shadow-lg);
    position:relative; overflow:hidden;
}
.job-detail-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:4px;
    background: linear-gradient(90deg, var(--primary), var(--violet), var(--accent), var(--emerald));
    background-size:300% 100%; animation:gradientShift 5s ease infinite;
}
.job-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; border-radius: 50px; font-size: 13px; font-weight: 700;
    transition: all 0.3s;
}
.job-tag:hover { transform:translateY(-2px); }
.job-tag.location { background: #eff6ff; color: #2563eb; }
.job-tag.type { background: #f0fdf4; color: #059669; }
.job-tag.time { background: #fefce8; color: #ca8a04; }
.job-tag.openings { background: #faf5ff; color: #7c3aed; }
#detailsTable { display: none; }
div#discriptive { float: none; width: 100%; margin: 0; }
div#detailsTools { display: none; }

/* ===== FORM - 3D Depth ===== */
.form-section {
    background: #fff; border-radius: var(--radius-2xl); padding: 48px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: var(--shadow-md);
    margin-bottom: 24px; position: relative; overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
}
.form-section:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
.form-section::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--violet), var(--accent), var(--emerald));
    background-size: 300% 100%; animation: gradientShift 5s ease infinite;
}
.form-section h2 { font-size: 20px; color: var(--gray-900); margin: 0 0 32px; font-weight: 800; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.form-grid .full { grid-column: 1 / -1; }
label { font-weight: 700; color: var(--gray-700); display: block; margin-bottom: 8px; font-size: 14px; }

input.inputbox, input.inputBoxName, input.inputBoxNormal, input.inputBoxArea,
input.inputBoxFile, input#documentFile, input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%; padding: 15px 20px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 500 15px var(--font); color: var(--gray-800);
    transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
    background: var(--gray-50);
}
input.inputbox:hover, input.inputBoxName:hover, input.inputBoxNormal:hover,
input[type="text"]:hover { border-color: var(--gray-300); background: #fff; }
input.inputbox:focus, input.inputBoxName:focus, input.inputBoxNormal:focus,
input#documentFile:focus, input[type="text"]:focus, input[type="email"]:focus {
    outline: none; border-color: var(--primary); background: #fff;
    box-shadow: 0 0 0 4px rgba(79,70,229,0.1), 0 4px 16px rgba(79,70,229,0.08);
    transform: translateY(-1px);
}
textarea, textarea.inputBoxArea, textarea.inputboxlarge {
    width: 100%; padding: 15px 20px; min-height: 120px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 500 15px var(--font); color: var(--gray-800);
    resize: vertical; transition: all 0.35s; background: var(--gray-50);
}
textarea:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); transform: translateY(-1px); }

input.submitbutton, input.submitButton, input[type="submit"] {
    padding: 18px 48px; width: auto; min-width: 280px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 50%, #a855f7 100%);
    background-size: 200% 200%; animation: gradientShift 4s ease infinite;
    color: #fff; font: 800 16px var(--font); border: none;
    border-radius: 18px; cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    box-shadow: var(--shadow-glow), inset 0 1px 0 rgba(255,255,255,0.2);
    letter-spacing: -0.01em; position:relative; overflow:hidden;
}
input.submitbutton:hover, input.submitButton:hover, input[type="submit"]:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: var(--shadow-glow-lg);
}
input.submitbutton:active, input[type="submit"]:active { transform: translateY(0); }

.progress-bar-bg { width: 100%; height: 6px; background: var(--gray-100); border-radius: 3px; overflow: hidden; margin-bottom: 32px; }
.progress-bar-fill { height: 100%; width: 0%; background: linear-gradient(90deg, var(--primary), var(--violet), var(--accent)); background-size:300% 100%; animation:gradientShift 3s ease infinite; border-radius: 3px; transition: width 0.6s cubic-bezier(0.22,1,0.36,1); }

div.applyBoxLeft, div.applyBoxRight { float:none; width:100%; max-width:100%; border:none; box-shadow:none; padding:0; background:transparent; margin:0; }
div.applyBoxLeft div, div.applyBoxRight div { display:none; }
div.applyBoxLeft table, div.applyBoxRight table { display:none; }
td.label { display:none; }

/* ===== CTA BANNER ===== */
.cta-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
    border-radius: var(--radius-2xl); padding: 64px; text-align: center;
    position: relative; overflow: hidden;
    box-shadow: 0 24px 80px rgba(15,23,42,0.3);
}
.cta-banner::before {
    content:''; position:absolute; inset:0;
    background: radial-gradient(ellipse at 30% 50%, rgba(79,70,229,0.2) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(124,58,237,0.15) 0%, transparent 60%);
}
.cta-banner * { position:relative; z-index:2; }
.cta-banner h2 { color:#fff; font-size:36px; margin-bottom:14px; -webkit-text-fill-color:#fff; }
.cta-banner p { color:rgba(255,255,255,0.6); font-size:18px; margin-bottom:36px; }

/* ===== FOOTER ===== */
.career-footer {
    background: linear-gradient(180deg, var(--gray-900) 0%, #050816 100%);
    color: var(--gray-400); padding: 80px 40px 40px; margin-top: auto;
    position: relative;
}
.career-footer::before {
    content: ''; position: absolute; top: 0; left: 5%; right: 5%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(79,70,229,0.5), rgba(124,58,237,0.5), transparent);
}
.footer-inner {
    max-width: 1320px; margin: 0 auto;
    display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 64px;
}
.footer-brand .brand-text { color: #fff; font-size: 24px; }
.footer-brand .brand-text span { background: linear-gradient(135deg, #38bdf8, #a78bfa, #fb7185); background-size:200% 200%; -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation:gradientShift 4s ease infinite; }
.footer-brand p { font-size: 15px; margin-top: 14px; color: var(--gray-400); line-height: 1.8; max-width: 380px; }
.footer-col h4 { color: #fff; font-size: 11px; font-weight: 800; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 0.14em; }
.footer-col a { display: block; font-size: 15px; color: var(--gray-400); padding: 8px 0; transition: all 0.3s; }
.footer-col a:hover { color: #fff; transform: translateX(8px); }
.footer-bottom {
    max-width: 1320px; margin: 60px auto 0; padding-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.06);
    display: flex; justify-content: space-between; align-items: center;
    font-size: 14px; color: var(--gray-500);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .header-inner { padding: 12px 16px; }
    .header-nav a:not(.nav-btn) { display: none; }
    .hero-premium { padding: 140px 20px 80px; min-height: auto; }
    .hero-premium h1 { font-size: 32px; }
    .hero-premium p { font-size: 17px; }
    .hero-cta { flex-direction: column; align-items: center; }
    .hero-btn-primary, .hero-btn-secondary { width: 100%; max-width: 300px; justify-content: center; }
    .stats-glass { grid-template-columns: repeat(2, 1fr); margin: -40px 16px 0; }
    .stat-glass::after { display:none; }
    .main-content { padding: 40px 16px; margin-top: 60px; }
    .section-title { font-size: 30px; }
    .form-grid { grid-template-columns: 1fr; }
    .footer-inner { grid-template-columns: 1fr; gap: 32px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
    table.sortable { border-spacing: 0 10px; }
    tr.evenTableRow td, tr.oddTableRow td { padding: 18px 18px; }
    .cta-banner { padding:40px 24px; }
    .cta-banner h2 { font-size:24px; }
    .process-step { flex-direction:column; gap:16px; }
    .process-step::before { display:none; }
    .hero-grid { display:none; }
}

/* Generic overrides */
h1 { font: 900 36px var(--font); color: var(--gray-900); letter-spacing: -0.04em; margin: 0 0 16px; background:none; -webkit-text-fill-color: var(--gray-900); }
h2 { font: 800 24px var(--font); color: var(--gray-800); margin: 0 0 16px; border:none; padding:0; }
h3 { font: 700 18px var(--font); color: var(--gray-700); margin: 0 0 8px; }
p { font: 400 15px/1.7 var(--font); color: var(--gray-500); }
strong { font-weight: 800; color: var(--gray-800); }
#careerContent { clear:both; padding:24px 0; flex:1; }
#poweredCATS { display:none; }
.clearfix::after { content:''; display:table; clear:both; }
CSS;

$stmt->execute(['val' => $css, 'setting' => 'CSS']);
echo "CSS updated (" . strlen($css) . ")\n";


// =====================================================
// HEADER
// =====================================================
$header = <<<'TPL'
<div class="career-header">
    <div class="header-inner">
        <a href="index.php?m=careers" class="brand">
            <div class="brand-icon">N</div>
            <div class="brand-text">Neutara <span>Careers</span></div>
        </a>
        <nav class="header-nav">
            <a href="index.php?m=careers&p=showAll">Positions</a>
            <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Explore Jobs
            </a>
        </nav>
    </div>
</div>
TPL;
$stmt->execute(['val' => $header, 'setting' => 'Header']);
echo "Header updated\n";


// =====================================================
// CONTENT - MAIN → Auto-redirect
// =====================================================
$main = <<<'TPL'
<script>window.location.replace('index.php?m=careers&p=showAll');</script>
<div class="main-content" style="text-align:center;padding:120px 24px;">
    <p>Redirecting to open positions...</p>
</div>
TPL;
$stmt->execute(['val' => $main, 'setting' => 'Content - Main']);
echo "Content - Main updated\n";


// =====================================================
// CONTENT - SEARCH RESULTS (THE MAIN LANDING PAGE)
// =====================================================
$search = <<<'TPL'
<div class="hero-premium">
    <div class="hero-grid"></div>
    <canvas id="gradientMesh"></canvas>
    <canvas id="particleCanvas"></canvas>
    <div class="fl-shape" style="width:400px;height:400px;top:-100px;left:-120px;"></div>
    <div class="fl-shape" style="width:300px;height:300px;bottom:-80px;right:-100px;animation-delay:-3s;"></div>
    <div class="fl-shape" style="width:200px;height:200px;top:20%;right:6%;animation-delay:-6s;"></div>
    <div class="fl-shape" style="width:140px;height:140px;bottom:30%;left:10%;animation-delay:-2s;"></div>
    <div class="fl-shape" style="width:100px;height:100px;top:55%;left:25%;animation-delay:-4.5s;"></div>
    <div class="fl-shape" style="width:70px;height:70px;top:15%;left:60%;animation-delay:-7s;"></div>

    <div class="hero-inner">
        <div class="hero-badge"><span class="dot"></span> Now Hiring &mdash; Multiple Openings</div>
        <h1>Build Something<br>Extraordinary with <span class="typing-text"></span><span class="typing-cursor"></span></h1>
        <p>Join a team that turns ambitious ideas into products that shape industries. Your talent, our mission &mdash; let's build the future together.</p>
        <div class="hero-cta">
            <a href="#positions" class="hero-btn-primary magnetic-btn">
                View Open Roles
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
            <a href="#culture" class="hero-btn-secondary">
                Why Neutara
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

<div style="max-width:1320px;margin:0 auto;padding:0 40px;">
    <div class="stats-glass">
        <div class="stat-glass"><span class="stat-num counter" data-target="50" data-suffix="+">0</span><span class="stat-label">Team Members</span></div>
        <div class="stat-glass"><span class="stat-num counter" data-target="12" data-suffix="+">0</span><span class="stat-label">Countries</span></div>
        <div class="stat-glass"><span class="stat-num counter" data-target="98" data-suffix="%">0</span><span class="stat-label">Satisfaction</span></div>
        <div class="stat-glass"><span class="stat-num">4.8<span style="font-size:18px;">&#9733;</span></span><span class="stat-label">Glassdoor</span></div>
    </div>
</div>

<div class="main-content" id="culture" style="margin-top:0;">
    <div style="text-align:center;margin-bottom:64px;" class="reveal">
        <span class="section-tag">&#10024; Why Neutara</span>
        <h2 class="section-title">Where Great Careers Are Built</h2>
        <p class="section-subtitle" style="margin:0 auto;">We don't just offer jobs &mdash; we create a launchpad for extraordinary careers with world-class benefits and culture.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-bottom:100px;" class="stagger-children">
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon blue">&#127891;</div>
            <h3>Continuous Learning</h3>
            <p>$5K annual learning budget, conference sponsorships, and access to premium education platforms for every team member.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon violet">&#128640;</div>
            <h3>Rapid Career Growth</h3>
            <p>Clear advancement paths with quarterly performance reviews and dedicated 1-on-1 mentorship from senior leaders.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon emerald">&#127758;</div>
            <h3>Remote First Culture</h3>
            <p>Work from anywhere in the world. We believe great work happens when you have the freedom to choose your environment.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon amber">&#128150;</div>
            <h3>Premium Benefits</h3>
            <p>Top-tier health coverage, equity packages, unlimited PTO, parental leave, and comprehensive family-friendly policies.</p>
        </div>
    </div>

    <!-- How It Works / Hiring Process -->
    <div style="text-align:center;margin-bottom:56px;" class="reveal">
        <span class="section-tag">&#128736; Our Process</span>
        <h2 class="section-title">Simple & Transparent Hiring</h2>
        <p class="section-subtitle" style="margin:0 auto;">We respect your time. Our streamlined process takes 2&ndash;3 weeks from application to offer.</p>
    </div>

    <div style="max-width:700px;margin:0 auto 100px;" class="stagger-children">
        <div class="process-step">
            <div class="process-num">1</div>
            <div class="process-content">
                <h3>Submit Application</h3>
                <p>Apply online in under 5 minutes. Upload your resume and tell us why you're excited about the role.</p>
            </div>
        </div>
        <div class="process-step">
            <div class="process-num">2</div>
            <div class="process-content">
                <h3>Technical Assessment</h3>
                <p>A take-home or live assessment designed to showcase your skills &mdash; no trick questions, just real work.</p>
            </div>
        </div>
        <div class="process-step">
            <div class="process-num">3</div>
            <div class="process-content">
                <h3>Team Interview</h3>
                <p>Meet the team you'd work with. We focus on collaboration, communication, and culture alignment.</p>
            </div>
        </div>
        <div class="process-step">
            <div class="process-num">4</div>
            <div class="process-content">
                <h3>Welcome Aboard</h3>
                <p>Receive your offer and start your journey. We make onboarding smooth and exciting from day one.</p>
            </div>
        </div>
    </div>

    <!-- Positions -->
    <div id="positions" style="scroll-margin-top:100px;">
        <div style="text-align:center;margin-bottom:52px;" class="reveal">
            <span class="section-tag">&#128188; Open Roles</span>
            <h2 class="section-title">Find Your Perfect Role</h2>
            <p class="section-subtitle" style="margin:0 auto;"><numberOfSearchResults> position(s) currently available &mdash; each one an opportunity to make a real impact.</p>
        </div>
        <div class="reveal">
            <searchResultsTableUnformatted>
        </div>
    </div>

    <!-- CTA Banner -->
    <div class="cta-banner reveal" style="margin-top:80px;">
        <h2 style="font-size:34px;letter-spacing:-0.04em;">Don't See Your Dream Role?</h2>
        <p>We're always looking for exceptional talent. Send us your resume and we'll reach out when the right opportunity opens.</p>
        <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn" style="padding:16px 40px;font-size:16px;border-radius:18px;">
            Stay Connected
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
TPL;
$stmt->execute(['val' => $search, 'setting' => 'Content - Search Results']);
echo "Content - Search Results updated\n";


// =====================================================
// JOB DETAILS
// =====================================================
$jobDetails = <<<'TPL'
<div class="main-content" style="max-width:960px;">
    <div style="margin-bottom:28px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-400);display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:var(--radius);background:#fff;border:1px solid var(--gray-200);transition:all 0.3s;font-weight:600;box-shadow:var(--shadow-sm);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            All Positions
        </a>
    </div>

    <div class="job-detail-card reveal">
        <h1 style="font-size:40px;margin-bottom:18px;letter-spacing:-0.045em;"><title></h1>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:40px;">
            <span class="job-tag location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <city>, <state>
            </span>
            <span class="job-tag type">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                <type>
            </span>
            <span class="job-tag time">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Posted <daysOld> days ago
            </span>
            <span class="job-tag openings">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                <openings> opening(s)
            </span>
        </div>

        <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:48px;">
            <a-applyToJob class="nav-btn magnetic-btn" style="padding:16px 44px;font-size:16px;border-radius:18px;">
                Apply Now
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div style="border-top:1px solid var(--gray-100);padding-top:40px;">
            <h2 style="font-size:26px;margin:0 0 22px;font-weight:900;">About This Role</h2>
            <div style="color:var(--gray-600);line-height:2;font-size:15px;"><description></div>
        </div>

        <div style="margin-top:48px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            <div class="culture-card" style="padding:24px;">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Location
                </div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><city>, <state></div>
            </div>
            <div class="culture-card" style="padding:24px;">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--violet)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                    Type
                </div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><type></div>
            </div>
            <div class="culture-card" style="padding:24px;">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Recruiter
                </div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><recruiter></div>
            </div>
            <div class="culture-card" style="padding:24px;">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Posted
                </div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><created></div>
            </div>
        </div>

        <div class="cta-banner" style="margin-top:48px;padding:48px;border-radius:var(--radius-xl);">
            <h3 style="font-size:24px;margin:0 0 12px;color:#fff;font-weight:900;-webkit-text-fill-color:#fff;">Ready to Make an Impact?</h3>
            <p style="margin:0 0 28px;color:rgba(255,255,255,0.6);font-size:17px;">Your next chapter starts with a single click.</p>
            <a-applyToJob class="nav-btn magnetic-btn" style="padding:16px 44px;font-size:16px;border-radius:18px;background:#fff;color:var(--primary-dark) !important;box-shadow:0 8px 32px rgba(0,0,0,0.2);">
                Submit Your Application
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
TPL;
$stmt->execute(['val' => $jobDetails, 'setting' => 'Content - Job Details']);
echo "Job Details updated\n";


// =====================================================
// APPLY FOR POSITION
// =====================================================
$apply = <<<'TPL'
<div class="main-content" style="max-width:820px;">
    <div style="margin-bottom:28px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-400);display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:var(--radius);background:#fff;border:1px solid var(--gray-200);font-weight:600;box-shadow:var(--shadow-sm);transition:all 0.3s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    <div class="reveal" style="text-align:center;margin-bottom:40px;">
        <span class="section-tag">&#128221; Application</span>
        <h1 style="font-size:34px;margin-bottom:10px;">Apply for <title></h1>
        <p style="color:var(--gray-500);margin:0;font-size:16px;">Complete the form below. Fields marked with <span style="color:var(--rose);">*</span> are required.</p>
    </div>

    <div class="progress-bar-bg reveal"><div class="progress-bar-fill" id="formProgress"></div></div>
    <div style="text-align:right;margin:-24px 0 28px;font-size:13px;color:var(--gray-400);font-weight:600;" id="formProgressLabel" class="reveal">0% complete</div>

    <div class="form-section reveal">
        <h2>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="vertical-align:-4px;margin-right:10px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Personal Information
        </h2>
        <div class="form-grid">
            <div><label>First Name <span style="color:var(--rose);">*</span></label><input-firstName req></div>
            <div><label>Last Name <span style="color:var(--rose);">*</span></label><input-lastName req></div>
            <div><label>Email <span style="color:var(--rose);">*</span></label><input-email req></div>
            <div><label>Confirm Email <span style="color:var(--rose);">*</span></label><input-emailconfirm req></div>
            <div><label>Phone <span style="color:var(--rose);">*</span></label><input-phone req></div>
            <div><label>City</label><input-city></div>
            <div><label>State</label><input-state></div>
            <div><label>Zip Code</label><input-zip></div>
            <div class="full"><label>Address</label><input-address></div>
        </div>
    </div>

    <div class="form-section reveal">
        <h2>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--violet)" stroke-width="2" style="vertical-align:-4px;margin-right:10px;"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            Professional Background
        </h2>
        <div class="form-grid">
            <div><label>Key Skills</label><input-keySkills></div>
            <div><label>Current Employer</label><input-employer></div>
            <div class="full"><label>How did you hear about us?</label><input-source></div>
        </div>
    </div>

    <div class="form-section reveal">
        <h2>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="2" style="vertical-align:-4px;margin-right:10px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Resume &amp; Documents
        </h2>
        <div style="margin-bottom:24px;">
            <label>Upload Resume</label>
            <input-resumeUpload>
            <p style="font-size:13px;color:var(--gray-400);margin-top:8px;">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</p>
        </div>
        <div>
            <label>Cover Letter / Additional Notes</label>
            <input-extraNotes>
        </div>
    </div>

    <div class="reveal" style="text-align:center;padding:20px 0 56px;">
        <submit value="Submit Application" class="magnetic-btn" style="padding:18px 56px;min-width:320px;font-size:16px;border-radius:18px;">
    </div>
</div>
TPL;
$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Apply updated\n";


// =====================================================
// THANKS
// =====================================================
$thanks = <<<'TPL'
<div class="main-content" style="text-align:center;padding:100px 24px;max-width:720px;">
    <div class="reveal-scale" style="margin-bottom:40px;">
        <div style="width:110px;height:110px;background:linear-gradient(135deg,#059669,#34d399);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 20px 60px rgba(5,150,105,0.3);animation:thanksPulse 2.5s ease-in-out infinite;position:relative;">
            <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <div style="position:absolute;inset:-8px;border-radius:50%;border:2px solid rgba(52,211,153,0.3);animation:ringExpand 2.5s ease-in-out infinite;"></div>
        </div>
    </div>
    <style>
        @keyframes thanksPulse { 0%,100%{box-shadow:0 20px 60px rgba(5,150,105,0.3);transform:scale(1);} 50%{box-shadow:0 24px 80px rgba(5,150,105,0.45);transform:scale(1.05);} }
        @keyframes ringExpand { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.3);opacity:0;} }
    </style>
    <h1 class="reveal" style="font-size:42px;margin-bottom:18px;">Application Submitted!</h1>
    <p class="reveal" style="font-size:19px;color:var(--gray-500);max-width:530px;margin:0 auto 22px;line-height:1.8;">Thank you for your interest in joining Neutara. Our talent team will carefully review your application and reach out within 3&ndash;5 business days.</p>
    <p class="reveal" style="font-size:15px;color:var(--gray-400);max-width:460px;margin:0 auto 48px;">A confirmation email has been sent to your inbox.</p>
    <div class="reveal" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
        <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn" style="padding:16px 40px;font-size:16px;border-radius:18px;">
            Browse More Roles
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="index.php?m=careers" style="display:inline-flex;align-items:center;gap:8px;padding:16px 40px;border-radius:18px;border:2px solid var(--gray-200);font-weight:700;font-size:16px;color:var(--gray-600);transition:all 0.3s;">
            Back to Home
        </a>
    </div>
</div>
TPL;
$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Thanks updated\n";


// =====================================================
// FOOTER
// =====================================================
$footer = <<<'TPL'
<div class="career-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-text">Neutara <span>Careers</span></div>
            <p>Building the future with exceptional talent. We are committed to creating an inclusive, innovative workplace where every voice matters.</p>
        </div>
        <div class="footer-col">
            <h4>Careers</h4>
            <a href="index.php?m=careers&p=showAll">Open Positions</a>
            <a href="index.php?m=careers&p=showAll">Departments</a>
            <a href="index.php?m=careers&p=showAll">Internships</a>
        </div>
        <div class="footer-col">
            <h4>Company</h4>
            <a href="index.php?m=careers&p=showAll">About Us</a>
            <a href="index.php?m=careers&p=showAll">Culture & Values</a>
            <a href="index.php?m=careers&p=showAll">Blog</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; 2026 Neutara. All rights reserved.</span>
        <span style="display:flex;gap:24px;">
            <a href="#" style="color:var(--gray-500);font-weight:600;transition:color 0.3s;">Privacy</a>
            <a href="#" style="color:var(--gray-500);font-weight:600;transition:color 0.3s;">Terms</a>
            <a href="#" style="color:var(--gray-500);font-weight:600;transition:color 0.3s;">Accessibility</a>
        </span>
    </div>
</div>
TPL;
$stmt->execute(['val' => $footer, 'setting' => 'Footer']);
echo "Footer updated\n";

echo "\n=== Premium V3 Ultra 3D Deployed! ===\n";
echo "Visit: http://localhost:8000/index.php?m=careers&p=showAll\n";
