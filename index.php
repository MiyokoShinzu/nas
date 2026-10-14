<?php
date_default_timezone_set('Asia/Manila');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f7faff" id="themeColor">
<meta name="description" content="NAS Club — Build the network, Protect the future.">
<title>NAS Club | Build the network, Protect the future</title>
<link rel="icon" type="image/png" href="./assets/pubmat/head.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{
  color-scheme:light;
  --bg:#f7faff;--surface:rgba(255,255,255,.8);--text:#132238;--muted:#60728a;
  --line:rgba(49,83,126,.15);--blue:#236be8;--blue2:#347df5;--cyan:#089eae;
  --purple:#8655d8;--shadow:0 24px 80px rgba(41,84,145,.12);
}
:root[data-theme="dark"]{
  color-scheme:dark;
  --bg:#0b1018;--surface:rgba(20,29,42,.78);--text:#f0f6fc;--muted:#a0aec0;
  --line:rgba(155,183,218,.18);--blue:#347df5;--blue2:#66a6ff;--cyan:#56d4dd;
  --purple:#a78bfa;--shadow:0 24px 80px rgba(0,0,0,.25);
}
*{box-sizing:border-box}
html,body{min-height:100%;margin:0}
html{scroll-behavior:smooth}
body{font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text);transition:background .25s,color .25s;overflow-x:hidden}
a{color:inherit;text-decoration:none}
button{font:inherit}
.hero{position:relative;isolation:isolate;min-height:100svh;display:flex;flex-direction:column;overflow:hidden;background:
 radial-gradient(ellipse at 16% 12%,color-mix(in srgb,var(--blue) 10%,transparent),transparent 35%),
 radial-gradient(ellipse at 85% 85%,color-mix(in srgb,var(--cyan) 9%,transparent),transparent 34%),var(--bg)}
.hero:before{content:"";position:absolute;inset:0;z-index:-1;opacity:.48;background-image:linear-gradient(color-mix(in srgb,var(--blue) 7%,transparent) 1px,transparent 1px),linear-gradient(90deg,color-mix(in srgb,var(--blue) 7%,transparent) 1px,transparent 1px);background-size:42px 42px;mask-image:linear-gradient(to bottom,black,transparent 92%);pointer-events:none}
.topline{height:3px;background:linear-gradient(90deg,var(--blue),var(--cyan),var(--purple))}
.nav{position:relative;z-index:5;width:min(1240px,calc(100% - 56px));height:86px;margin:auto;display:flex;align-items:center;justify-content:space-between;gap:22px}
.brand{display:flex;align-items:center;gap:12px;min-width:0}
.logo{width:45px;height:45px;object-fit:contain;padding:4px;border:1px solid var(--line);border-radius:14px;background:var(--surface)}
.brand-text{font-size:19px;font-weight:900;letter-spacing:-.055em;line-height:1.1}
.brand-text .dot{color:var(--blue2)}
.brand-sub{display:block;margin-top:5px;color:var(--muted);font-size:9px;letter-spacing:.02em;font-weight:500}
.nav-right{display:flex;align-items:center;gap:11px}
.theme-toggle{width:43px;height:43px;display:grid;place-items:center;border:1px solid var(--line);border-radius:50%;background:var(--surface);color:var(--text);font-size:17px;cursor:pointer;transition:transform .2s,border-color .2s}
.theme-toggle:hover{transform:rotate(-12deg);border-color:var(--blue2)}
.login{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:44px;padding:0 19px;border-radius:999px;background:var(--blue);border:1px solid var(--blue);color:white;font-size:12px;font-weight:800;box-shadow:0 8px 25px color-mix(in srgb,var(--blue) 22%,transparent);transition:transform .2s,box-shadow .2s}
.login:hover{transform:translateY(-2px);box-shadow:0 12px 28px color-mix(in srgb,var(--blue) 30%,transparent)}
.hero-main{position:relative;z-index:2;flex:1;display:flex;align-items:center;width:min(1240px,calc(100% - 56px));margin:0 auto;padding:50px 0 100px}
.copy{max-width:770px;position:relative;z-index:2}
.eyebrow{display:inline-flex;align-items:center;gap:9px;padding:8px 12px;border:1px solid color-mix(in srgb,var(--blue2) 23%,var(--line));background:color-mix(in srgb,var(--blue2) 7%,var(--surface));border-radius:999px;color:var(--blue2);font:500 10px "DM Mono",monospace;letter-spacing:.08em;text-transform:uppercase;margin-bottom:28px}
.pulse{width:7px;height:7px;border-radius:50%;background:var(--cyan);box-shadow:0 0 0 4px color-mix(in srgb,var(--cyan) 15%,transparent)}
h1{font-size:clamp(60px,9.2vw,124px);line-height:.91;letter-spacing:-.09em;font-weight:900;margin:0;max-width:950px}
h1 .nas{color:var(--blue2)}
.tagline{font-size:clamp(21px,3vw,34px);line-height:1.3;font-weight:750;letter-spacing:-.045em;margin:28px 0 0;color:var(--text)}
.tagline span{color:var(--cyan)}
.support{max-width:490px;color:var(--muted);font-size:14px;line-height:1.9;margin:20px 0 0}
.hero-actions{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-top:31px}
.login.large{min-height:51px;padding:0 23px;font-size:13px}
.text-link{display:inline-flex;align-items:center;gap:9px;padding:12px 4px;color:var(--muted);font-size:12px;font-weight:700;transition:color .2s}
.text-link:hover{color:var(--blue2)}
.background-network{position:absolute;z-index:0;inset:0;width:100%;height:100%;pointer-events:none}
.network-hint{position:absolute;z-index:1;right:5.5%;bottom:7%;font:9px "DM Mono",monospace;letter-spacing:.08em;color:var(--muted);opacity:.7;text-transform:uppercase;pointer-events:none}
.bottom-note{position:absolute;z-index:2;left:0;right:0;bottom:0;display:flex;align-items:center;justify-content:space-between;gap:20px;width:min(1240px,calc(100% - 56px));margin:auto;padding:19px 0;border-top:1px solid var(--line);color:var(--muted);font-size:10px}
.bottom-note .left{display:flex;align-items:center;gap:9px}.bottom-note i{color:var(--cyan)}
.bottom-note .right{font-family:"DM Mono",monospace;letter-spacing:.06em;text-transform:uppercase}
@media(max-width:760px){
 .nav{width:calc(100% - 34px);height:74px}.logo{width:39px;height:39px}.brand{gap:9px}.brand-text{font-size:16px}.brand-sub{font-size:8px;max-width:190px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
 .nav-right{gap:8px}.theme-toggle{width:40px;height:40px}.login{min-height:40px;padding:0 14px;font-size:11px}
 .hero-main{width:calc(100% - 34px);padding:50px 0 110px;align-items:center}
 .copy{max-width:620px}h1{font-size:clamp(55px,13vw,88px)}.eyebrow{font-size:9px;margin-bottom:23px}.tagline{margin-top:23px}.support{font-size:13px;max-width:430px}
 .network-hint{right:17px;bottom:83px;font-size:8px}.bottom-note{width:calc(100% - 34px);font-size:9px}.bottom-note .right{display:none}
}
@media(max-width:420px){
 .brand-sub{max-width:145px}.brand-text{font-size:15px}.nav{gap:8px}.nav-right{gap:6px}.login{padding:0 11px}.hero-main{padding-top:35px}h1{font-size:clamp(49px,13vw,64px)}.tagline{font-size:22px}.hero-actions{gap:8px}.text-link{font-size:11px}
}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{scroll-behavior:auto!important;transition-duration:.01ms!important}}
</style>
</head>
<body>
<section class="hero" id="home">
 <div class="topline"></div>
 <header class="nav">
  <a class="brand" href="#home" aria-label="NAS Club homepage">
   <img class="logo" src="./assets/pubmat/head.png" alt="NAS Club logo">
   <span><span class="brand-text"><span class="nas">NAS</span><span class="dot">.</span> CLUB</span><span class="brand-sub">Network Administrator and Security Club</span></span>
  </a>
  <div class="nav-right">
   <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode" title="Switch theme"><i class="bi bi-moon-stars"></i></button>
   <a class="login" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
  </div>
 </header>

 <canvas class="background-network" id="networkCanvas" aria-hidden="true"></canvas>

 <main class="hero-main">
  <div class="copy">
   <div class="eyebrow"><span class="pulse"></span> ICPEP.SE · CSU Carig Campus</div>
   <h1>Connect with<br><span class="nas">NAS.</span></h1>
   <p class="tagline">Build the network,<br><span>Protect the future.</span></p>
   <p class="support">A community for future computer engineers passionate about networking, infrastructure, and cybersecurity.</p>
   <div class="hero-actions">
    <a class="login large" href="login.php">Enter the network <i class="bi bi-arrow-up-right"></i></a>
    <a class="text-link" href="#home">Network Administrator and Security Club <i class="bi bi-arrow-down-right"></i></a>
   </div>
  </div>
 </main>

 <div class="network-hint">Binary data in motion · max 3 links per node</div>
 <div class="bottom-note">
  <div class="left"><i class="bi bi-diagram-3-fill"></i> <span>ICPEP.SE Organization · Cagayan State University, Carig Campus</span></div>
  <div class="right">College of Engineering and Architecture / Computer Engineering</div>
 </div>
</section>

<script>
const root=document.documentElement;
const toggle=document.getElementById('themeToggle');
const themeColor=document.getElementById('themeColor');
function setTheme(theme){
 root.dataset.theme=theme;
 const dark=theme==='dark';
 toggle.innerHTML=dark?'<i class="bi bi-sun"></i>':'<i class="bi bi-moon-stars"></i>';
 toggle.setAttribute('aria-label',dark?'Switch to light mode':'Switch to dark mode');
 toggle.title=dark?'Switch to light mode':'Switch to dark mode';
 themeColor.content=dark?'#0b1018':'#f7faff';
}
let stored=null;
try{stored=localStorage.getItem('nas-theme');}catch(e){}
setTheme(stored==='dark'?'dark':'light');
toggle.addEventListener('click',()=>{
 const next=root.dataset.theme==='dark'?'light':'dark';
 setTheme(next);
 try{localStorage.setItem('nas-theme',next);}catch(e){}
});

// Lightweight 3D-style network animation. Nodes connect only to nearby nodes,
// and every node is limited to three connections maximum.
(()=>{
 const canvas=document.getElementById('networkCanvas'),ctx=canvas.getContext('2d');
 const reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 let w=0,h=0,dpr=1,nodes=[],frame=0,last=0;
 const MAX_LINKS=3;
 const random=(min,max)=>min+Math.random()*(max-min);
 function resize(){
  const r=canvas.getBoundingClientRect();w=r.width;h=r.height;dpr=Math.min(window.devicePixelRatio||1,2);
  canvas.width=Math.round(w*dpr);canvas.height=Math.round(h*dpr);ctx.setTransform(dpr,0,0,dpr,0,0);
  const count=Math.max(28,Math.min(78,Math.round(w/17)));
  nodes=Array.from({length:count},()=>({x:random(0,w),y:random(0,h),z:random(.25,1),vx:random(-.12,.12),vy:random(-.10,.10),r:random(1.1,2.5),links:[],phase:random(0,6.28)}));
  for(let i=0;i<nodes.length;i++){
   const nearest=nodes.map((n,j)=>({j,d:Math.hypot(n.x-nodes[i].x,n.y-nodes[i].y)})).filter(v=>v.j!==i).sort((a,b)=>a.d-b.d);
   for(const c of nearest){
    if(nodes[i].links.length>=MAX_LINKS)break;
    if(c.d>Math.min(175,w*.23))break;
    if(nodes[c.j].links.length>=MAX_LINKS||nodes[i].links.includes(c.j))continue;
    if(Math.random()<.55){nodes[i].links.push(c.j);nodes[c.j].links.push(i);}
   }
  }
  paint(0);
 }
 function paint(t){
  ctx.clearRect(0,0,w,h);
  const dark=root.dataset.theme==='dark';
  const blue=dark?'102,166,255':'35,107,232';
  const cyan=dark?'86,212,221':'8,158,174';
  const dt=last?Math.min(2,(t-last)/16.67):1;last=t;
  if(!reduced){
   for(const n of nodes){
    n.x+=n.vx*dt;n.y+=n.vy*dt;n.phase+=.012*dt;
    if(n.x<0||n.x>w)n.vx*=-1;if(n.y<0||n.y>h)n.vy*=-1;
   }
   // Connections disappear when nodes drift apart.
   for(let i=0;i<nodes.length;i++){
    for(const j of [...nodes[i].links]){
     const d=Math.hypot(nodes[i].x-nodes[j].x,nodes[i].y-nodes[j].y);
     if(d>Math.min(180,w*.24)){
      nodes[i].links=nodes[i].links.filter(v=>v!==j);
      nodes[j].links=nodes[j].links.filter(v=>v!==i);
     }
    }
   }
   // Nearby nodes may connect if both still have available connection slots.
   for(let i=0;i<nodes.length;i++){
    const a=nodes[i];if(a.links.length>=MAX_LINKS)continue;
    let best=-1,bestD=Infinity;
    for(let j=0;j<nodes.length;j++){
     if(i===j||a.links.includes(j)||nodes[j].links.length>=MAX_LINKS)continue;
     const d=Math.hypot(a.x-nodes[j].x,a.y-nodes[j].y);
     if(d<Math.min(125,w*.17)&&d<bestD){best=j;bestD=d;}
    }
    if(best>=0&&Math.random()<.014*dt){a.links.push(best);nodes[best].links.push(i);}
   }
  }
  for(let i=0;i<nodes.length;i++){
   const a=nodes[i];
   for(const j of a.links){
    if(j<=i)continue;
    const b=nodes[j],d=Math.hypot(a.x-b.x,a.y-b.y),limit=Math.min(180,w*.24),alpha=Math.max(0,1-d/limit)*.62;
    // Connection wire: fades naturally as its nodes approach the link limit.
    ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);
    ctx.strokeStyle=`rgba(${blue},${alpha})`;ctx.lineWidth=.7+Math.min(a.z,b.z)*.75;ctx.stroke();
    if(alpha>.12){
     // Two binary packets travel along each link at a calm, varied speed.
     const elapsed=t*.00016 + (i*0.173+j*0.071);
     const progress=((elapsed%1)+1)%1;
     const reverse=((elapsed*.73+.43)%1+1)%1;
     const packets=[{p:progress,bit:((i+j)%2?'1':'0')},{p:reverse,bit:((i*j+1)%2?'0':'1')}];
     packets.forEach((packet,k)=>{
       const px=a.x+(b.x-a.x)*packet.p,py=a.y+(b.y-a.y)*packet.p;
       ctx.save();
       ctx.globalAlpha=alpha*(.62+k*.15);
       ctx.font=`500 ${Math.max(8,Math.min(10,9*Math.min(a.z,b.z)+2))}px "DM Mono", monospace`;
       ctx.textAlign='center';ctx.textBaseline='middle';
       ctx.shadowBlur=5;ctx.shadowColor=`rgba(${cyan},.25)`;
       ctx.fillStyle=`rgba(${cyan},1)`;
       ctx.fillText(packet.bit,px,py);
       ctx.restore();
     });
     // Small travelling signal glow behind the binary data.
     const pulse=(Math.sin(t*.001+i+j)+1)/2;
     ctx.beginPath();ctx.arc(a.x+(b.x-a.x)*pulse,a.y+(b.y-a.y)*pulse,1.3,0,Math.PI*2);
     ctx.fillStyle=`rgba(${cyan},${alpha*.7})`;ctx.fill();
    }
   }
  }
  for(const n of nodes){
   const radius=n.r*(.75+n.z*.7)+Math.sin(n.phase)*.25;
   ctx.beginPath();ctx.arc(n.x,n.y,radius,0,Math.PI*2);
   ctx.fillStyle=`rgba(${blue},${.28+n.z*.52})`;ctx.fill();
   ctx.beginPath();ctx.arc(n.x,n.y,radius+3,0,Math.PI*2);
   ctx.strokeStyle=`rgba(${blue},${.05+n.z*.09})`;ctx.lineWidth=1;ctx.stroke();
  }
  if(!reduced)frame=requestAnimationFrame(paint);
 }
 const ro=new ResizeObserver(()=>{cancelAnimationFrame(frame);last=0;resize();if(!reduced)frame=requestAnimationFrame(paint);});
 ro.observe(canvas);
 document.addEventListener('visibilitychange',()=>{if(document.hidden)cancelAnimationFrame(frame);else if(!reduced){last=0;frame=requestAnimationFrame(paint);}});
 resize();if(!reduced)frame=requestAnimationFrame(paint);
})();
</script>
</body>
</html>
