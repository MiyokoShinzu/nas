<?php
date_default_timezone_set('Asia/Manila');
$currentYear = date('Y');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f6f9ff" id="themeColor">
<meta name="description" content="NAS Club — Network Administrator and Security Club at Cagayan State University Carig Campus.">
<title>NAS Club | Build the network, Protect the future</title>
<link rel="icon" type="image/png" href="./assets/pubmat/head.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{color-scheme:light;--bg:#f6f9ff;--surface:#ffffff;--surface2:#edf3fb;--line:#dce5f0;--text:#14243a;--muted:#63748a;--blue:#2169e8;--blue2:#2f81f7;--cyan:#089eae;--purple:#8255d8;--green:#16834a;--max:1220px}
:root[data-theme="dark"]{color-scheme:dark;--bg:#0d1117;--surface:#161b22;--surface2:#1c2128;--line:#30363d;--text:#f0f6fc;--muted:#9ba7b4;--blue:#2f81f7;--blue2:#58a6ff;--cyan:#56d4dd;--purple:#a371f7;--green:#3fb950}

*{box-sizing:border-box}html{scroll-behavior:smooth;scroll-padding-top:100px}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,system-ui,sans-serif;line-height:1.65;overflow-x:hidden}a{color:inherit;text-decoration:none}button{font:inherit}.wrap{width:min(var(--max),calc(100% - 44px));margin:auto}.mono{font-family:"DM Mono",monospace;letter-spacing:.07em;text-transform:uppercase}
.topline{height:3px;background:linear-gradient(90deg,var(--blue),var(--cyan),var(--purple))}
header{position:sticky;top:0;z-index:30;background:color-mix(in srgb,var(--bg) 88%,transparent);backdrop-filter:blur(18px);border-bottom:1px solid var(--line)}
.nav{height:82px;display:flex;align-items:center;justify-content:space-between;gap:24px}
.brand{display:flex;align-items:center;gap:11px;min-width:0}.logo{width:43px;height:43px;object-fit:contain;padding:4px;border-radius:13px;background:var(--surface);border:1px solid var(--line)}.brand-name{font-size:17px;font-weight:900;letter-spacing:-.04em;line-height:1.1}.brand-sub{display:block;color:var(--muted);font-size:9px;margin-top:5px;letter-spacing:.01em}
.navlinks{display:flex;align-items:center;gap:27px;font-size:12px;color:var(--muted)}.navlinks>a:not(.navbutton):hover{color:var(--blue2)}.navbutton,.btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:12px 17px;border:1px solid rgba(88,166,255,.42);border-radius:999px;background:var(--blue);color:#fff;font-weight:750;font-size:12px;transition:.22s}.navbutton:hover,.btn:hover{background:#1f6feb;transform:translateY(-2px)}.theme-toggle{width:42px;height:42px;display:grid;place-items:center;flex:0 0 auto;border:1px solid var(--line);border-radius:50%;background:var(--surface);color:var(--text);font-size:17px;cursor:pointer;transition:transform .2s,border-color .2s}.theme-toggle:hover{transform:rotate(-10deg);border-color:var(--blue2)}.menu{display:none;background:transparent;color:var(--text);border:1px solid var(--line);border-radius:12px;width:43px;height:43px;font-size:22px}
.hero{position:relative;padding:80px 0 72px;isolation:isolate}.hero:before{content:"";position:absolute;z-index:-1;width:720px;height:720px;right:-230px;top:-180px;background:radial-gradient(circle,rgba(47,129,247,.19),rgba(86,212,221,.055) 42%,transparent 70%);pointer-events:none}
.hero-grid{display:grid;grid-template-columns:.95fr 1.05fr;gap:46px;align-items:center}
.eyebrow{display:inline-flex;align-items:center;gap:9px;padding:7px 11px;border:1px solid rgba(88,166,255,.25);background:color-mix(in srgb,var(--blue) 9%,var(--surface));border-radius:999px;color:var(--blue2);font-size:10px;font-weight:700;margin-bottom:22px}.eyebrow i{font-size:13px}
h1{font-size:clamp(45px,5.8vw,75px);line-height:1.02;letter-spacing:-.075em;margin:0;font-weight:900}h1 .blue{color:var(--blue2)}h1 .cyan{color:var(--cyan)}
.tagline{font-size:clamp(19px,2.3vw,25px);line-height:1.35;font-weight:800;letter-spacing:-.04em;margin:24px 0 13px}.description{font-size:14px;line-height:1.9;color:var(--muted);max-width:540px;margin:0}.actions{display:flex;gap:11px;flex-wrap:wrap;margin-top:27px}.btn.outline{background:transparent;border-color:var(--line);color:var(--text)}.btn.outline:hover{border-color:var(--blue2);background:rgba(88,166,255,.07)}.trustline{display:flex;align-items:center;gap:9px;margin-top:24px;color:var(--muted);font-size:10px}.trustline i{color:var(--green)}
/* The right-hand visual is a networking dashboard, not a static image. */
.visual{position:relative;min-height:495px;border:1px solid rgba(139,148,158,.23);border-radius:28px;background:linear-gradient(145deg,var(--surface),color-mix(in srgb,var(--surface) 78%,var(--blue2)));overflow:hidden;box-shadow:0 24px 70px rgba(40,80,140,.13)}
.visual:before{content:"";position:absolute;inset:0;background-image:linear-gradient(rgba(88,166,255,.055) 1px,transparent 1px),linear-gradient(90deg,rgba(88,166,255,.055) 1px,transparent 1px);background-size:28px 28px;mask-image:linear-gradient(to bottom,black,transparent 96%)}
.visual-top{position:relative;display:flex;align-items:center;justify-content:space-between;padding:17px 19px;border-bottom:1px solid var(--line);font-size:10px;color:var(--muted)}.status{color:#8ee6a0;display:flex;align-items:center;gap:7px}.status:before{content:"";width:7px;height:7px;background:var(--green);border-radius:50%;box-shadow:0 0 12px var(--green)}
.map{position:relative;height:330px;margin:0 8px}.wires{position:absolute;width:100%;height:100%;inset:0}.wires line,.wires path{stroke:var(--blue2);stroke-width:1.25;stroke-opacity:.52;stroke-dasharray:5 7;fill:none;animation:flow 15s linear infinite}.wires .secure{stroke:var(--cyan);stroke-opacity:.68}
.node{position:absolute;z-index:2;width:56px;height:56px;border:1px solid rgba(88,166,255,.55);border-radius:17px;background:color-mix(in srgb,var(--surface) 88%,var(--blue2));color:var(--blue2);display:grid;place-items:center;font-size:22px;box-shadow:0 0 26px rgba(47,129,247,.12);animation:float 5s ease-in-out infinite}.node em{position:absolute;top:61px;font-style:normal;font-family:"DM Mono",monospace;font-size:8px;white-space:nowrap;color:var(--muted);letter-spacing:.04em}.node.router{left:calc(50% - 31px);top:7%;animation-delay:-1s}.node.firewall{right:9%;top:25%;animation-delay:-2s}.node.client{left:8%;top:25%;animation-delay:-.7s}.node.cloud{right:13%;bottom:14%;animation-delay:-2.7s}.node.server{left:12%;bottom:14%;animation-delay:-1.5s}.node.laptop{left:calc(50% - 28px);bottom:5%;animation-delay:-3s}.node.core{width:78px;height:78px;left:calc(50% - 39px);top:calc(50% - 39px);border-radius:24px;background:linear-gradient(145deg,#1b3b67,#102033);border-color:var(--cyan);color:var(--cyan);font-size:29px;box-shadow:0 0 38px rgba(86,212,221,.17);animation:pulse 3s ease-in-out infinite}.node.core em{top:84px;color:var(--cyan)}
.floating-label{position:absolute;z-index:3;padding:8px 10px;border-radius:10px;border:1px solid var(--line);background:var(--surface);font-size:9px;color:var(--text);box-shadow:0 10px 30px rgba(40,80,140,.12)}.floating-label i{color:var(--cyan);margin-right:5px}.label-a{left:20px;top:10px}.label-b{right:17px;bottom:12px}
.network-canvas-wrap{position:relative;height:365px;overflow:hidden}
#networkCanvas{display:block;width:100%;height:100%;touch-action:pan-y}
.canvas-chip{position:absolute;z-index:2;padding:8px 11px;border:1px solid var(--line);border-radius:999px;background:color-mix(in srgb,var(--surface) 88%,transparent);box-shadow:0 7px 22px rgba(40,80,140,.09);font-size:9px;color:var(--muted);pointer-events:none}
.canvas-chip i{color:var(--blue2);margin-right:5px}.chip-left{left:16px;top:15px}.chip-right{right:16px;top:15px}.canvas-hint{position:absolute;bottom:12px;left:0;right:0;text-align:center;font-size:8px;color:var(--muted);pointer-events:none}
.visual-bottom{position:relative;display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--line)}.stat{padding:15px 17px;border-right:1px solid var(--line)}.stat:last-child{border:0}.stat strong{display:block;font-size:12px}.stat span{font-size:9px;color:var(--muted)}
.logostrip{border-top:1px solid var(--line);border-bottom:1px solid var(--line);background:var(--surface2)}.strip{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;padding:17px 0;color:var(--muted);font-size:10px}.strip span{display:flex;align-items:center;gap:8px}.strip i{color:var(--cyan);font-size:14px}
.section{padding:90px 0}.section-head{display:flex;justify-content:space-between;align-items:end;gap:30px;margin-bottom:34px}.label{font-size:10px;color:var(--blue2);font-weight:700;margin-bottom:12px}h2{font-size:clamp(31px,4.2vw,49px);line-height:1.1;letter-spacing:-.065em;margin:0;font-weight:850}.section-intro{max-width:430px;font-size:13px;line-height:1.9;color:var(--muted);margin:0}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:15px}.card{position:relative;overflow:hidden;padding:26px;border:1px solid var(--line);border-radius:20px;background:var(--surface);min-height:225px;transition:transform .22s,border-color .22s}.card:hover{transform:translateY(-5px);border-color:rgba(88,166,255,.55)}.card:after{content:"";position:absolute;width:150px;height:150px;right:-70px;bottom:-80px;border-radius:50%;background:rgba(47,129,247,.09);filter:blur(3px)}.card-icon{width:46px;height:46px;display:grid;place-items:center;border:1px solid rgba(88,166,255,.25);border-radius:14px;background:color-mix(in srgb,var(--blue) 9%,var(--surface));color:var(--blue2);font-size:21px}.card h3{font-size:16px;margin:20px 0 8px;letter-spacing:-.03em}.card p{font-size:12px;line-height:1.85;color:var(--muted);margin:0;max-width:310px}
.about{display:grid;grid-template-columns:.9fr 1.1fr;gap:18px}.about-main,.about-detail{padding:31px;border:1px solid var(--line);border-radius:22px;background:var(--surface)}.about-main{background:linear-gradient(145deg,color-mix(in srgb,var(--blue) 9%,var(--surface)),var(--surface) 70%);display:flex;flex-direction:column;justify-content:space-between;min-height:290px}.about-main h3{font-size:25px;line-height:1.2;letter-spacing:-.05em;margin:30px 0 12px}.about-main p,.about-detail p{color:var(--muted);font-size:13px;line-height:1.9;margin:0}.about-detail h3{font-size:18px;margin:0 0 11px}.org{color:var(--cyan);font-size:10px;margin-top:22px}.detail-row{display:grid;grid-template-columns:34px 1fr;gap:12px;padding:17px 0;border-bottom:1px solid var(--line)}.detail-row:last-child{border:0;padding-bottom:0}.detail-row i{font-size:19px;color:var(--blue2)}
.statement{padding:0 0 88px}.statement-box{position:relative;overflow:hidden;padding:45px;border:1px solid rgba(88,166,255,.3);border-radius:26px;background:linear-gradient(110deg,color-mix(in srgb,var(--blue) 9%,var(--surface)),var(--surface) 58%,color-mix(in srgb,var(--purple) 7%,var(--surface)))}.statement-box:after{content:"⌘";position:absolute;right:28px;bottom:-85px;font-size:240px;color:rgba(88,166,255,.05);font-weight:900;pointer-events:none}.statement-box h2{font-size:clamp(34px,5vw,58px);max-width:800px}.statement-box h2 span{color:var(--cyan)}.statement-box>p{max-width:690px;color:var(--muted);font-size:13px;line-height:1.9;margin:18px 0 0}.values{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:28px}.value{border-top:1px solid #ffffff2b;padding-top:15px;font-size:12px;font-weight:750}.value span{display:block;color:var(--muted);font-size:11px;font-weight:400;margin-top:5px}
.join{padding:0 0 86px}.joinbox{display:flex;align-items:center;justify-content:space-between;gap:30px;padding:33px;border:1px solid var(--line);border-radius:22px;background:var(--surface)}.joinbox h2{font-size:clamp(27px,3.4vw,39px)}.joinbox p{font-size:13px;color:var(--muted);margin:10px 0 0}
footer{border-top:1px solid var(--line);padding:27px 0 33px}.foot{display:flex;align-items:center;justify-content:space-between;gap:22px}.footcopy{font-size:10px;line-height:1.9;color:var(--muted)}.footcopy strong{color:var(--text)}.footlinks{display:flex;gap:17px;flex-wrap:wrap;font-size:10px;color:var(--muted)}.footlinks a:hover{color:var(--blue2)}
.reveal{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s ease}.reveal.show{opacity:1;transform:translateY(0)}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}@keyframes pulse{0%,100%{box-shadow:0 0 25px rgba(86,212,221,.1)}50%{box-shadow:0 0 43px rgba(86,212,221,.25)}}@keyframes flow{to{stroke-dashoffset:-150}}
@media(max-width:950px){.hero-grid{grid-template-columns:1fr;gap:40px}.hero-copy{max-width:750px}.visual{max-width:720px;width:100%;margin:auto}.section-head{align-items:start;flex-direction:column}.section-intro{max-width:650px}.about{grid-template-columns:1fr}}
@media(max-width:700px){.wrap{width:calc(100% - 30px)}.nav{gap:10px}.brand{gap:8px}.logo{width:38px;height:38px}.brand-name{font-size:15px}.brand-sub{max-width:175px;font-size:8px}.nav{height:70px}.theme-toggle{margin-left:auto}.brand-sub{max-width:205px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.menu{display:block}.navlinks{display:none;position:absolute;top:69px;left:0;right:0;padding:15px 22px 22px;background:var(--bg);border-bottom:1px solid var(--line);flex-direction:column;align-items:stretch;gap:0}.navlinks.open{display:flex}.navlinks>a{padding:12px 0}.navlinks .navbutton{margin-top:8px;padding:12px}.hero{padding:55px 0 55px}h1{font-size:clamp(44px,12vw,64px)}.visual{min-height:0}.network-canvas-wrap{height:320px}.node{width:47px;height:47px;border-radius:14px;font-size:19px}.node em{top:52px;font-size:7px}.node.core{width:68px;height:68px;left:calc(50% - 34px);top:calc(50% - 34px)}.node.core em{top:74px}.node.client{left:4%;top:24%}.node.firewall{right:4%;top:24%}.node.server{left:6%;bottom:13%}.node.cloud{right:6%;bottom:13%}.node.laptop{left:calc(50% - 23px);bottom:4%}.label-a{left:10px;top:8px}.label-b{right:10px;bottom:8px}.visual-bottom .stat{padding:12px 9px}.stat strong{font-size:10px}.stat span{font-size:8px}.strip{justify-content:flex-start;gap:14px 22px}.section{padding:67px 0}.cards{grid-template-columns:1fr}.card{min-height:0}.about-main,.about-detail{padding:24px}.statement{padding-bottom:66px}.statement-box{padding:28px 23px}.statement-box:after{font-size:150px;right:5px}.values{grid-template-columns:1fr;gap:16px}.join{padding-bottom:65px}.joinbox{align-items:flex-start;flex-direction:column;padding:25px}.foot{align-items:flex-start;flex-direction:column}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}.reveal{opacity:1;transform:none}}
</style>
</head>
<body>
<div class="topline"></div>
<header>
 <div class="wrap nav">
  <a href="#home" class="brand" aria-label="NAS Club home">
   <img class="logo" src="./assets/pubmat/head.png" alt="NAS Club logo">
   <span><span class="brand-name">NAS<span style="color:var(--blue2)">.</span> CLUB</span><span class="brand-sub">Network Administrator and Security Club</span></span>
  </a>
  <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode" title="Toggle light/dark theme"><i class="bi bi-moon-stars"></i></button>
  <button class="menu" id="menuBtn" aria-label="Toggle menu" aria-expanded="false"><i class="bi bi-list"></i></button>
  <nav class="navlinks" id="navLinks"><a href="#about">About NAS</a><a href="#focus">Our Focus</a><a href="#direction">Our Direction</a><a href="#join" class="navbutton"><i class="bi bi-arrow-up-right"></i> Get involved</a></nav>
 </div>
</header>
<main id="home">
 <section class="hero">
  <div class="wrap hero-grid">
   <div class="hero-copy">
    <div class="eyebrow"><i class="bi bi-diagram-3-fill"></i><span class="mono">ICPEP.SE · CSU Carig Campus</span></div>
    <h1>Where every<br>connection has a<br><span class="blue">purpose.</span></h1>
    <p class="tagline">Build the network,<br><span class="cyan">Protect the future.</span></p>
    <p class="description">Meet NAS Club — a community of future computer engineers exploring network administration, cybersecurity, and the systems that keep our digital world connected.</p>
    <div class="actions"><a class="btn" href="#about"><i class="bi bi-shield-check"></i> Discover NAS</a><a class="btn outline" href="#focus">Explore our world <i class="bi bi-arrow-down-right"></i></a></div>
    <div class="trustline"><i class="bi bi-check-circle-fill"></i> Learn together. Build responsibly. Defend intelligently.</div>
   </div>
   <div class="visual reveal" aria-label="Interactive 3D-style network background">
    <div class="visual-top"><span class="mono">NAS / Network topology</span><span class="status mono">Live simulation</span></div>
    <div class="network-canvas-wrap">
      <canvas id="networkCanvas" aria-label="Animated network nodes connecting and disconnecting"></canvas>
      <div class="canvas-chip chip-left"><i class="bi bi-router-fill"></i> Routers · Nodes · Links</div>
      <div class="canvas-chip chip-right"><i class="bi bi-shield-lock-fill"></i> Secure network</div>
      <div class="canvas-hint mono">Dynamic topology / max 3 links per node</div>
    </div>
    <div class="visual-bottom"><div class="stat"><strong>Connect</strong><span>Network infrastructure</span></div><div class="stat"><strong>Protect</strong><span>Digital security</span></div><div class="stat"><strong>Innovate</strong><span>Future-ready skills</span></div></div>
   </div>
  </div>
 </section>
 <div class="logostrip"><div class="wrap strip mono"><span><i class="bi bi-router"></i> Network Administration</span><span><i class="bi bi-shield-lock"></i> Cybersecurity</span><span><i class="bi bi-hdd-network"></i> Infrastructure</span><span><i class="bi bi-people"></i> Community Learning</span></div></div>
 <section class="section" id="focus">
  <div class="wrap">
   <div class="section-head reveal"><div><div class="label mono">01 / Explore NAS</div><h2>Technology is better<br>when we build it together.</h2></div><p class="section-intro">From the first cable to a protected network, we make complex concepts easier to explore through curiosity, practice, and teamwork.</p></div>
   <div class="cards">
    <article class="card reveal"><div class="card-icon"><i class="bi bi-router-fill"></i></div><h3>Network Administration</h3><p>Explore network topologies, routing, switching, IP addressing, server services, and troubleshooting.</p></article>
    <article class="card reveal"><div class="card-icon"><i class="bi bi-shield-lock-fill"></i></div><h3>Cybersecurity</h3><p>Learn about defensive security, threat awareness, access control, safe practices, and digital resilience.</p></article>
    <article class="card reveal"><div class="card-icon"><i class="bi bi-hdd-network-fill"></i></div><h3>Systems & Infrastructure</h3><p>Discover servers, operating systems, cloud concepts, connected devices, and dependable systems.</p></article>
   </div>
  </div>
 </section>
 <section class="section" id="about" style="padding-top:0">
  <div class="wrap">
   <div class="section-head reveal"><div><div class="label mono">02 / Who we are</div><h2>A club for the curious,<br>connected, and security-minded.</h2></div></div>
   <div class="about">
    <article class="about-main reveal"><div><div class="card-icon"><i class="bi bi-diagram-3-fill"></i></div><h3>Build skills.<br>Make connections.</h3><p>NAS Club brings students together to learn, collaborate, and explore technologies that connect people and protect information.</p></div><div class="org mono">ICPEP.SE Organization</div></article>
    <article class="about-detail reveal"><h3>Rooted in Computer Engineering</h3><p>We encourage responsible experimentation, peer-to-peer learning, and practical understanding of networking and information security.</p>
     <div class="detail-row"><i class="bi bi-mortarboard-fill"></i><div><strong>Academic community</strong><p>Cagayan State University — Carig Campus</p></div></div>
     <div class="detail-row"><i class="bi bi-building"></i><div><strong>College of Engineering and Architecture</strong><p>Computer Engineering Department</p></div></div>
     <div class="detail-row"><i class="bi bi-people-fill"></i><div><strong>Learn as a team</strong><p>Share knowledge, solve problems, and grow your technical confidence.</p></div></div>
    </article>
   </div>
  </div>
 </section>
 <section class="statement" id="direction"><div class="wrap"><div class="statement-box reveal">
  <div class="label mono">03 / Our direction</div><h2>Build the network,<br><span>Protect the future.</span></h2>
  <p>We believe a better digital future begins with people who can design reliable connections and protect the systems behind them. NAS champions continuous learning, ethical practice, and technology people can trust.</p>
  <div class="values"><div class="value">Learn by doing<span>Turn concepts into practical skills.</span></div><div class="value">Secure by design<span>Consider safety at every step.</span></div><div class="value">Grow together<span>Share knowledge and lift each other up.</span></div></div>
 </div></div></section>
 <section class="join" id="join"><div class="wrap"><div class="joinbox reveal"><div><div class="label mono">04 / Get involved</div><h2>Ready to connect with NAS?</h2><p>Explore networking, strengthen your security mindset, and help shape a safer connected future.</p></div><a class="btn" href="#home"><i class="bi bi-arrow-up-right"></i> Explore the club</a></div></div></section>
</main>
<footer><div class="wrap foot"><div class="footcopy"><strong>NAS Club — Network Administrator and Security Club</strong><br>ICPEP.SE Organization · Cagayan State University, Carig Campus<br>College of Engineering and Architecture · Computer Engineering Department<br>© <?= htmlspecialchars($currentYear, ENT_QUOTES, 'UTF-8') ?> NAS Club. Build the network, Protect the future.</div><div class="footlinks"><a href="#about">About</a><a href="#focus">Our Focus</a><a href="#direction">Our Direction</a><a href="#home">Back to top ↑</a></div></div></footer>
<script>
const root=document.documentElement;
const themeToggle=document.getElementById('themeToggle');
const themeColor=document.getElementById('themeColor');
function applyTheme(theme){
  root.dataset.theme=theme;
  const dark=theme==='dark';
  themeToggle.innerHTML=dark?'<i class="bi bi-sun"></i>':'<i class="bi bi-moon-stars"></i>';
  themeToggle.setAttribute('aria-label',dark?'Switch to light mode':'Switch to dark mode');
  themeToggle.title=dark?'Switch to light mode':'Switch to dark mode';
  themeColor.content=dark?'#0d1117':'#f6f9ff';
}
let savedTheme=null;
try{savedTheme=localStorage.getItem('nas-theme');}catch(e){}
applyTheme(savedTheme==='dark'?'dark':'light');
themeToggle.addEventListener('click',()=>{
  const next=root.dataset.theme==='dark'?'light':'dark';
  applyTheme(next);
  try{localStorage.setItem('nas-theme',next);}catch(e){}
});
const menuBtn=document.getElementById('menuBtn'),navLinks=document.getElementById('navLinks');
menuBtn.addEventListener('click',()=>{const open=navLinks.classList.toggle('open');menuBtn.setAttribute('aria-expanded',String(open));menuBtn.innerHTML=open?'<i class="bi bi-x-lg"></i>':'<i class="bi bi-list"></i>';});
navLinks.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{navLinks.classList.remove('open');menuBtn.setAttribute('aria-expanded','false');menuBtn.innerHTML='<i class="bi bi-list"></i>';}));
// Responsive 3D-style particle network. Each node can have at most three connections.
(()=>{
 const canvas=document.getElementById('networkCanvas');
 if(!canvas)return;
 const ctx=canvas.getContext('2d');
 const reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 let width=0,height=0,dpr=1,nodes=[],raf=0,last=0;
 const maxLinks=3;
 const rand=(a,b)=>a+Math.random()*(b-a);
 function resize(){
   const rect=canvas.getBoundingClientRect(); dpr=Math.min(window.devicePixelRatio||1,2);
   width=Math.max(1,rect.width);height=Math.max(1,rect.height);
   canvas.width=Math.round(width*dpr);canvas.height=Math.round(height*dpr);
   ctx.setTransform(dpr,0,0,dpr,0,0);
   const count=Math.max(24,Math.min(58,Math.round(width/13)));
   nodes=Array.from({length:count},(_,i)=>({x:rand(8,width-8),y:rand(32,height-24),z:rand(.35,1),vx:rand(-.22,.22),vy:rand(-.18,.18),r:rand(1.7,3.1),phase:rand(0,Math.PI*2),links:[]}));
   // Seed a sparse graph with no more than three links per node.
   for(let i=0;i<nodes.length;i++){
     const candidates=nodes.map((n,j)=>({j,d:Math.hypot(n.x-nodes[i].x,n.y-nodes[i].y)})).filter(o=>o.j!==i&&o.d<Math.min(150,width*.27)).sort((a,b)=>a.d-b.d);
     for(const c of candidates){
       if(nodes[i].links.length>=maxLinks)break;
       if(nodes[c.j].links.length>=maxLinks||nodes[i].links.includes(c.j))continue;
       if(Math.random()<.42){nodes[i].links.push(c.j);nodes[c.j].links.push(i);}
     }
   }
   draw(0);
 }
 function draw(time){
   ctx.clearRect(0,0,width,height);
   const dark=root.dataset.theme==='dark';
   const lineColor=dark?'88,166,255':'47,129,247';
   const dotColor=dark?'88,166,255':'33,105,232';
   // Motion is paused when reduced motion is requested.
   const dt=last?Math.min(2,(time-last)/16.67):1;last=time;
   if(!reduced){
     nodes.forEach(n=>{
       n.x+=n.vx*dt;n.y+=n.vy*dt;
       if(n.x<5||n.x>width-5)n.vx*=-1;
       if(n.y<25||n.y>height-15)n.vy*=-1;
       n.phase+=.012*dt;
     });
     // Drop links that have drifted too far; then create nearby links only when both nodes have capacity.
     for(let i=0;i<nodes.length;i++){
       const n=nodes[i];
       for(const j of [...n.links]){
         const other=nodes[j],d=Math.hypot(n.x-other.x,n.y-other.y);
         if(d>Math.min(155,width*.29)){
           n.links=n.links.filter(k=>k!==j);other.links=other.links.filter(k=>k!==i);
         }
       }
     }
     for(let i=0;i<nodes.length;i++){
       const n=nodes[i];if(n.links.length>=maxLinks)continue;
       let nearest=-1,nearestD=Infinity;
       for(let j=0;j<nodes.length;j++){
         if(i===j||n.links.includes(j)||nodes[j].links.length>=maxLinks)continue;
         const d=Math.hypot(n.x-nodes[j].x,n.y-nodes[j].y);
         if(d<Math.min(112,width*.22)&&d<nearestD){nearest=j;nearestD=d;}
       }
       if(nearest>=0&&Math.random()<.018*dt){n.links.push(nearest);nodes[nearest].links.push(i);}
     }
   }
   // Draw each edge once; links naturally fade as the endpoints separate.
   for(let i=0;i<nodes.length;i++){
     const n=nodes[i];
     n.links.forEach(j=>{
       if(j<=i)return;
       const o=nodes[j],d=Math.hypot(n.x-o.x,n.y-o.y),maxD=Math.min(155,width*.29),alpha=Math.max(0,1-d/maxD)*.68;
       ctx.beginPath();ctx.moveTo(n.x,n.y);ctx.lineTo(o.x,o.y);
       ctx.strokeStyle=`rgba(${lineColor},${alpha})`;ctx.lineWidth=.7+Math.min(n.z,o.z)*.65;ctx.stroke();
       if(!reduced&&alpha>.24&&Math.random()<.0018){ctx.beginPath();ctx.arc((n.x+o.x)/2,(n.y+o.y)/2,1.5,0,Math.PI*2);ctx.fillStyle=`rgba(${dark?'86,212,221':'8,158,174'},.9)`;ctx.fill();}
     });
   }
   nodes.forEach(n=>{
     const r=n.r*(.7+n.z*.5)+Math.sin(n.phase)*.35;
     ctx.beginPath();ctx.arc(n.x,n.y,r,0,Math.PI*2);ctx.fillStyle=`rgba(${dotColor},${.42+n.z*.48})`;ctx.fill();
     ctx.beginPath();ctx.arc(n.x,n.y,r+3,0,Math.PI*2);ctx.strokeStyle=`rgba(${lineColor},${.08+n.z*.12})`;ctx.lineWidth=1;ctx.stroke();
   });
   if(!reduced)raf=requestAnimationFrame(draw);
 }
 const resizeObserver=new ResizeObserver(()=>{cancelAnimationFrame(raf);last=0;resize();if(!reduced)raf=requestAnimationFrame(draw);});
 resizeObserver.observe(canvas);
 window.addEventListener('resize',()=>{cancelAnimationFrame(raf);last=0;resize();if(!reduced)raf=requestAnimationFrame(draw);},{passive:true});
 document.addEventListener('visibilitychange',()=>{if(document.hidden)cancelAnimationFrame(raf);else if(!reduced){last=0;raf=requestAnimationFrame(draw);}});
 resize();
 if(!reduced)raf=requestAnimationFrame(draw);
})();
const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('show');observer.unobserve(entry.target);}}),{threshold:.12});
document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));
</script>
</body>
</html>
