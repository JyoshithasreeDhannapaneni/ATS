<?php
/**
 * Career Portal — Merged V5
 * Combines V3 job listing styles + V4 apply page redesign
 * Fixes: V4 replaced V3 CSS breaking the job listing page
 */

$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");


// =====================================================
// CSS — Merged V3 + V4
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
    --success: #16a34a;
    --success-light: #dcfce7;
    --warning: #f59e0b;
    --danger: #dc2626;
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

/* ===== JOB TABLE - 3D CARD ROWS ===== */
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

/* ===== FORM - V3 3D Depth ===== */
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

/* ===== V4 APPLY PAGE ADDITIONS ===== */

/* Select dropdowns */
select, select.inputBoxNormal {
    width: 100%; padding: 15px 20px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 500 15px var(--font); color: var(--gray-800);
    background: var(--gray-50);
    outline: none; cursor: pointer;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
    transition: all 0.35s;
}
select:focus {
    border-color: var(--primary); background-color: #fff;
    box-shadow: 0 0 0 4px rgba(79,70,229,0.1);
}

/* Step Indicator */
.step-indicator {
    display: flex; align-items: center; gap: 0;
    padding: 24px 0; border-bottom: 1px solid var(--gray-200);
    margin-bottom: 32px; overflow-x: auto;
}
.step-item {
    display: flex; align-items: center; gap: 10px; white-space: nowrap; flex: 1;
}
.step-item:not(:last-child)::after {
    content: ''; flex: 1; height: 1px;
    background: var(--gray-200); margin: 0 16px; min-width: 24px;
}
.step-number {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font: 600 13px var(--font); flex-shrink: 0;
}
.step-number.active { background: var(--primary); color: #fff; }
.step-number.inactive { background: var(--gray-100); color: var(--gray-500); border: 1.5px solid var(--gray-300); }
.step-number.completed { background: var(--success); color: #fff; }
.step-label { font: 500 14px var(--font); color: var(--gray-700); }
.step-label.inactive { color: var(--gray-400); }

/* Apply Page Layout */
.apply-layout {
    display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start;
}

/* Progress Section */
.progress-section {
    display: flex; align-items: center; gap: 12px; margin-bottom: 8px;
}
.progress-label {
    font: 500 13px var(--font); color: var(--gray-500); white-space: nowrap;
}

/* Form Section Overrides for Apply Page */
.form-section-header {
    display: flex; align-items: flex-start; gap: 12px; margin-bottom: 24px;
}
.form-section-icon {
    width: 40px; height: 40px; background: var(--primary-50); border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.form-section-icon svg { color: var(--primary); }
.form-section-title h2 { font: 600 16px var(--font); color: var(--gray-900); margin-bottom: 2px; }
.form-section-title p { font: 400 13px var(--font); color: var(--gray-500); margin: 0; }
label .req { color: var(--danger); margin-left: 2px; }

/* File Upload Area */
.file-upload-area {
    border: 2px dashed var(--gray-300); border-radius: var(--radius-lg);
    padding: 32px 24px; text-align: center;
    transition: all 0.2s ease; cursor: pointer; background: var(--gray-50);
}
.file-upload-area:hover { border-color: var(--primary); background: var(--primary-50); }
.file-upload-area .upload-icon { margin-bottom: 8px; color: var(--gray-400); }
.file-upload-area p { font: 400 14px var(--font); color: var(--gray-500); margin: 0 0 4px; }
.file-upload-area .or-text { font: 400 13px var(--font); color: var(--gray-400); margin: 4px 0 12px; }
.choose-file-btn {
    display: inline-block; padding: 8px 20px;
    background: var(--primary); color: #fff;
    font: 600 13px var(--font); border-radius: 6px;
    cursor: pointer; transition: all 0.2s; border: none;
}
.choose-file-btn:hover { background: var(--primary-dark); }
.file-types { font: 400 12px var(--font); color: var(--gray-400); margin-top: 12px; }
input.inputBoxFile, input[type="file"] { display: none; }

/* Action Buttons */
.form-actions {
    display: flex; align-items: center; justify-content: space-between; padding: 24px 0 48px;
}
.btn-draft {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border: 1px solid var(--gray-300);
    border-radius: var(--radius); background: #fff;
    color: var(--gray-700); font: 600 14px var(--font);
    cursor: pointer; transition: all 0.2s;
}
.btn-draft:hover { background: var(--gray-50); border-color: var(--gray-400); }
.btn-submit {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 32px; background: var(--primary);
    color: #fff; font: 600 14px var(--font);
    border: none; border-radius: var(--radius);
    cursor: pointer; transition: all 0.2s;
}
.btn-submit:hover { background: var(--primary-dark); box-shadow: 0 4px 12px rgba(79,70,229,0.25); transform: translateY(-1px); }

/* Sidebar */
.apply-sidebar { position: sticky; top: 100px; }
.summary-card {
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl); padding: 24px;
}
.summary-card h3 { font: 600 16px var(--font); color: var(--gray-900); margin-bottom: 4px; }
.summary-card .summary-subtitle { font: 400 13px var(--font); color: var(--gray-500); margin-bottom: 20px; }
.summary-item {
    display: flex; align-items: flex-start; gap: 12px; padding: 12px 0;
}
.summary-item:not(:last-child) { border-bottom: 1px solid var(--gray-100); }
.summary-dot {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--gray-300); flex-shrink: 0; margin-top: 2px;
}
.summary-dot.completed { border-color: var(--success); background: var(--success); }
.summary-dot.active { border-color: var(--primary); background: var(--primary); }
.summary-item-text h4 { font: 500 14px var(--font); color: var(--gray-700); margin: 0 0 2px; }
.summary-item-text p { font: 400 12px var(--font); color: var(--gray-400); margin: 0; }

.tip-box {
    margin-top: 20px; padding: 16px;
    background: var(--primary-50); border-radius: var(--radius-lg);
    border: 1px solid var(--primary-light);
}
.tip-box .tip-header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.tip-box .tip-header svg { color: var(--primary); }
.tip-box .tip-header span { font: 600 13px var(--font); color: var(--primary-dark); }
.tip-box p { font: 400 13px/1.5 var(--font); color: var(--primary-dark); margin: 0; }

/* Footer social icons (V4 addition) */
.footer-social { display: flex; gap: 12px; margin-top: 20px; }
.footer-social a {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    border-radius: 8px; background: rgba(255,255,255,0.08); color: var(--gray-400); transition: all 0.3s;
}
.footer-social a:hover { background: rgba(255,255,255,0.15); color: #fff; transform: translateY(-2px); }

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
    .apply-layout { grid-template-columns: 1fr; }
    .apply-sidebar { position: static; }
    .step-indicator { gap: 8px; overflow-x: auto; padding-bottom: 16px; }
    .step-item:not(:last-child)::after { min-width: 12px; margin: 0 8px; }
    .step-label { font-size: 12px; }
}
@media (max-width: 600px) {
    .form-section { padding: 24px; }
    .form-actions { flex-direction: column; gap: 12px; }
    .step-label { display: none; }
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
echo "CSS updated (" . strlen($css) . " bytes)\n";


// =====================================================
// HEADER — V3 Premium Glass
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
echo "Header updated (V3 glass)\n";


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
// APPLY FOR POSITION — V4 Redesign
// =====================================================
$apply = <<<'TPL'
<div class="main-content">
    <!-- Back link -->
    <div style="padding-top:24px;margin-bottom:16px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Jobs
        </a>
    </div>

    <!-- Page title + progress -->
    <div class="reveal" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:28px;margin-bottom:4px;">Apply for <title></h1>
            <p style="color:var(--gray-500);margin:0;font-size:14px;">Fields marked <span style="color:var(--danger);">*</span> are required.</p>
        </div>
        <div style="text-align:right;min-width:200px;">
            <div class="progress-section">
                <span class="progress-label" id="formProgressLabel">0% complete</span>
            </div>
            <div class="progress-bar-bg" style="margin-top:6px;">
                <div class="progress-bar-fill" id="formProgress"></div>
            </div>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator reveal">
        <div class="step-item">
            <div class="step-number active">1</div>
            <span class="step-label">Personal Information</span>
        </div>
        <div class="step-item">
            <div class="step-number inactive">2</div>
            <span class="step-label inactive">Professional Background</span>
        </div>
        <div class="step-item">
            <div class="step-number inactive">3</div>
            <span class="step-label inactive">Resume & Documents</span>
        </div>
        <div class="step-item">
            <div class="step-number inactive">4</div>
            <span class="step-label inactive">Review & Submit</span>
        </div>
    </div>

    <!-- 2-Column Layout -->
    <div class="apply-layout">
        <!-- LEFT: Form sections -->
        <div>
            <!-- Section 1: Personal Information -->
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="form-section-title">
                        <h2>Personal Information</h2>
                        <p>Tell us who you are.</p>
                    </div>
                </div>
                <div class="form-grid">
                    <div>
                        <label>First Name <span class="req">*</span></label>
                        <input-firstName req>
                    </div>
                    <div>
                        <label>Last Name <span class="req">*</span></label>
                        <input-lastName req>
                    </div>
                    <div>
                        <label>Email <span class="req">*</span></label>
                        <input-email req>
                    </div>
                    <div>
                        <label>Confirm Email <span class="req">*</span></label>
                        <input-emailconfirm req>
                    </div>
                    <div>
                        <label>Phone <span class="req">*</span></label>
                        <input-phone req>
                    </div>
                    <div>
                        <label>City <span class="req">*</span></label>
                        <input-city req>
                    </div>
                    <div>
                        <label>State <span class="req">*</span></label>
                        <input-state req>
                    </div>
                    <div>
                        <label>Zip Code <span class="req">*</span></label>
                        <input-zip req>
                    </div>
                    <div class="full">
                        <label>Address <span class="req">*</span></label>
                        <input-address req>
                    </div>
                </div>
            </div>

            <!-- Section 2: Professional Background -->
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:#f0fdf4;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                    </div>
                    <div class="form-section-title">
                        <h2>Professional Background</h2>
                        <p>Help us understand your professional experience.</p>
                    </div>
                </div>
                <div class="form-grid">
                    <div>
                        <label>Key Skills <span class="req">*</span></label>
                        <input-keySkills req>
                    </div>
                    <div>
                        <label>Current Employer</label>
                        <input-employer>
                    </div>
                    <div class="full">
                        <label>How did you hear about us? <span class="req">*</span></label>
                        <input-source req>
                    </div>
                    <div class="full">
                        <label>Additional Information</label>
                        <input-extraNotes>
                    </div>
                </div>
            </div>

            <!-- Section 3: Resume & Documents -->
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:#eff6ff;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                    </div>
                    <div class="form-section-title">
                        <h2>Resume & Documents</h2>
                        <p>Upload your resume and add any additional documents.</p>
                    </div>
                </div>
                <div>
                    <label>Upload Resume <span class="req">*</span></label>
                    <div class="file-upload-area" onclick="document.getElementById('resume') ? document.getElementById('resume').click() : (document.getElementById('resumeFile') ? document.getElementById('resumeFile').click() : null)">
                        <div class="upload-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <p>Drag and drop your file here</p>
                        <div class="or-text">or</div>
                        <span class="choose-file-btn">Choose File</span>
                    </div>
                    <input-resumeUpload>
                    <div class="file-types">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</div>
                </div>
                <div style="margin-top:20px;">
                    <label>Cover Letter / Notes</label>
                    <input-extraNotes>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions reveal">
                <button type="button" class="btn-draft" onclick="alert('Draft saved!');">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                    Save Draft
                </button>
                <submit value="Save & Continue →" class="btn-submit">
            </div>
        </div>

        <!-- RIGHT: Sidebar -->
        <div class="apply-sidebar">
            <div class="summary-card reveal">
                <h3>Application Summary</h3>
                <p class="summary-subtitle">Review your progress</p>

                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Personal Information</h4>
                        <p>Not started</p>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Professional Background</h4>
                        <p>Not started</p>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Resume & Documents</h4>
                        <p>Not started</p>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Review & Submit</h4>
                        <p>Not started</p>
                    </div>
                </div>

                <div class="tip-box">
                    <div class="tip-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span>Tip</span>
                    </div>
                    <p>Complete all sections to increase your chances of getting noticed by our team.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form progress tracker
(function() {
    function updateProgress() {
        var inputs = document.querySelectorAll('#applyToJobForm input[type="text"], #applyToJobForm input[type="email"], #applyToJobForm input[type="tel"], #applyToJobForm textarea, #applyToJobForm select');
        var filled = 0;
        var total = 0;
        inputs.forEach(function(input) {
            if (input.type !== 'hidden' && input.offsetParent !== null) {
                total++;
                if (input.value && input.value.trim() !== '') filled++;
            }
        });
        var pct = total > 0 ? Math.round((filled / total) * 100) : 0;
        var bar = document.getElementById('formProgress');
        var label = document.getElementById('formProgressLabel');
        if (bar) bar.style.width = pct + '%';
        if (label) label.textContent = pct + '% complete';

        var dots = document.querySelectorAll('.summary-dot');
        var statuses = document.querySelectorAll('.summary-item-text p');
        var s1 = ['firstName','lastName','email','phone'].filter(function(id) { var el = document.getElementById(id); return el && el.value.trim(); }).length;
        if (s1 >= 4) { dots[0].style.borderColor = '#16a34a'; dots[0].style.background = '#16a34a'; statuses[0].textContent = 'Completed'; }
        else if (s1 > 0) { dots[0].style.borderColor = '#4f46e5'; dots[0].style.background = '#4f46e5'; statuses[0].textContent = 'In progress'; }
        var s2 = ['keySkills','source'].filter(function(id) { var el = document.getElementById(id); return el && el.value.trim(); }).length;
        if (s2 >= 2) { dots[1].style.borderColor = '#16a34a'; dots[1].style.background = '#16a34a'; statuses[1].textContent = 'Completed'; }
        else if (s2 > 0) { dots[1].style.borderColor = '#4f46e5'; dots[1].style.background = '#4f46e5'; statuses[1].textContent = 'In progress'; }
        var fileEl = document.getElementById('resume') || document.getElementById('resumeFile');
        if (fileEl && fileEl.value) { dots[2].style.borderColor = '#16a34a'; dots[2].style.background = '#16a34a'; statuses[2].textContent = 'Completed'; }

        var steps = document.querySelectorAll('.step-number');
        if (s1 >= 4 && steps[0]) { steps[0].className = 'step-number completed'; steps[0].innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'; }
        if (s2 >= 2 && steps[1]) { steps[1].className = 'step-number completed'; steps[1].innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'; }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setInterval(updateProgress, 500);
    });
})();

// Scroll reveal
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-loaded');
        var els = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function(el) { el.classList.add('revealed'); });
            return;
        }
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        els.forEach(function(el) { observer.observe(el); });
    });
})();

// Placeholder text
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var placeholders = {
            'firstName': 'Enter your first name',
            'lastName': 'Enter your last name',
            'email': 'Enter your email address',
            'emailconfirm': 'Re-enter your email address',
            'phone': 'Enter your phone number',
            'city': 'Enter your city',
            'zip': 'Enter zip code',
            'address': 'Enter your full address',
            'keySkills': 'Enter your key skills (comma separated)',
            'employer': 'Enter your current employer',
            'extraNotes': 'Add any other relevant information'
        };
        Object.keys(placeholders).forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.placeholder = placeholders[id];
        });
    });
})();
</script>
TPL;
$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Apply page updated (V4)\n";


// =====================================================
// THANKS FOR SUBMISSION — V4
// =====================================================
$thanks = <<<'TPL'
<div class="main-content" style="text-align:center;padding:80px 24px;max-width:600px;">
    <div class="reveal" style="margin-bottom:32px;">
        <div style="width:80px;height:80px;background:var(--success-light);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1 style="font-size:24px;margin-bottom:8px;">Application Submitted!</h1>
        <p style="font-size:15px;color:var(--gray-500);max-width:400px;margin:0 auto 24px;">Thank you for applying. We have received your application and will review it shortly.</p>
        <a href="index.php?m=careers&p=showAll" class="nav-btn" style="display:inline-flex;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Positions
        </a>
    </div>
</div>
TPL;
$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Thanks page updated\n";


// =====================================================
// FOOTER — V3 Premium Dark
// =====================================================
$footer = <<<'TPL'
<div class="career-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-text">Neutara <span>Careers</span></div>
            <p>Building the future with exceptional talent. We are committed to creating an inclusive, innovative workplace where every voice matters.</p>
            <div class="footer-social">
                <a href="#" title="LinkedIn"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
                <a href="#" title="Twitter"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                <a href="#" title="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 100 12.324 6.162 6.162 0 100-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 11-2.88 0 1.441 1.441 0 012.88 0z"/></svg></a>
                <a href="#" title="Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
            </div>
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
echo "Footer updated (V3 dark + social icons)\n";


echo "\n=== Merged V5 Deployed! ===\n";
echo "Both pages should now work:\n";
echo "  Job Listing: http://localhost:8000/careers/index.php?m=careers&p=showAll\n";
echo "  Apply Page:  Click on any job → Apply\n";
