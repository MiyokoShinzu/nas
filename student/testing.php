<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CPE Bit Builder · ETS-ASYNC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* =========================================================
           CPE BIT BUILDER — STYLES
           ========================================================= */
        :root {
            --cpe-navy: #0a1e3c;
            --cpe-blue: #2563eb;
            --cpe-cyan: #06b6d4;
            --cpe-green: #22c55e;
            --cpe-amber: #f59e0b;
            --cpe-red: #ef4444;
            --cpe-purple: #8b5cf6;
            --cpe-bg: #f1f5f9;
            --cpe-panel: #ffffff;
            --cpe-text: #0f172a;
            --cpe-muted: #64748b;
            --cpe-border: #e2e8f0;
            --cpe-shadow: 0 20px 60px rgba(15, 23, 42, .10);
        }

        html[data-theme="dark"] {
            --cpe-bg: #050914;
            --cpe-panel: #0f172a;
            --cpe-text: #e2e8f0;
            --cpe-muted: #94a3b8;
            --cpe-border: #1e293b;
            --cpe-shadow: 0 20px 60px rgba(0, 0, 0, .50);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--cpe-bg);
            color: var(--cpe-text);
            min-height: 100vh;
            transition: background .25s ease, color .25s ease;
        }

        .cpe-page {
            min-height: 100vh;
            padding-bottom: 40px;
        }

        .cpe-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        /* ----- INTRO CARD ----- */
        .cpe-intro {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            padding: 32px;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #0a1e3c 0%, #1e3a8a 50%, #0369a1 100%);
            color: white;
            box-shadow: 0 24px 60px rgba(10, 30, 60, .30);
        }

        .cpe-intro::before {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            right: -120px;
            top: -180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, .35), transparent 68%);
        }

        .cpe-intro::after {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            left: -100px;
            bottom: -160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, .22), transparent 68%);
        }

        .cpe-intro-content {
            position: relative;
            z-index: 2;
        }

        .cpe-tagline {
            font-size: 11px;
            letter-spacing: 2px;
            opacity: .75;
            margin-bottom: 6px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .cpe-intro h1 {
            font-weight: 800;
            margin: 0 0 8px;
            letter-spacing: -.5px;
            font-size: 1.9rem;
        }

        .cpe-intro h1 i {
            margin-right: 10px;
            color: #22d3ee;
        }

        .cpe-intro p {
            margin: 0;
            opacity: .85;
            max-width: 680px;
            line-height: 1.6;
        }

        .cpe-launch {
            position: relative;
            z-index: 2;
            margin-top: 22px;
            border: 0;
            border-radius: 12px;
            padding: 12px 22px;
            background: var(--cpe-amber);
            color: #111827;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 10px 25px rgba(245, 158, 11, .30);
            transition: transform .2s ease, box-shadow .2s ease;
            cursor: pointer;
        }

        .cpe-launch:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(245, 158, 11, .40);
        }

        .cpe-launch i {
            margin-right: 8px;
        }

        /* ----- INFO CARD ----- */
        .cpe-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .cpe-info-card {
            background: var(--cpe-panel);
            border: 1px solid var(--cpe-border);
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: var(--cpe-shadow);
        }

        .cpe-info-card h3 {
            margin: 0 0 8px;
            font-size: .95rem;
            color: var(--cpe-blue);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
        }

        .cpe-info-card p {
            margin: 0;
            font-size: .85rem;
            color: var(--cpe-muted);
            line-height: 1.55;
        }

        /* =========================================================
           GAME SHELL
           ========================================================= */
        .cpe-game-shell {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: none;
            overflow: hidden;
            background: #020617;
        }

        .cpe-game-shell.active {
            display: block;
        }

        .cpe-world {
            position: absolute;
            inset: 0;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 15% 20%, rgba(6, 182, 212, .14), transparent 32%),
                radial-gradient(ellipse at 85% 15%, rgba(139, 92, 246, .10), transparent 30%),
                radial-gradient(ellipse at 70% 80%, rgba(34, 197, 94, .07), transparent 32%),
                linear-gradient(145deg, #020617 0%, #071426 42%, #030712 100%);
        }

        .cpe-arena {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .cpe-arena::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 50%, rgba(0, 0, 0, .28) 100%);
            z-index: 20;
        }

        /* Layer containers */
        #cpeBitLayer,
        #cpePowerLayer,
        #cpeGateLayer,
        #cpePlayerLayer,
        #cpeFxLayer {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        /* ----- HUD ----- */
        .cpe-hud {
            position: absolute;
            top: 16px;
            left: 16px;
            right: 16px;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            pointer-events: none;
        }

        .cpe-hud-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cpe-hud-card {
            min-width: 100px;
            padding: 9px 13px;
            border-radius: 14px;
            background: rgba(5, 15, 30, .78);
            border: 1px solid rgba(148, 163, 184, .16);
            backdrop-filter: blur(14px);
            color: white;
        }

        .cpe-hud-label {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.1px;
            color: #94a3b8;
            font-weight: 700;
        }

        .cpe-hud-value {
            display: block;
            margin-top: 2px;
            font-size: 17px;
            font-weight: 800;
        }

        /* ----- Target Card ----- */
        .cpe-target-card {
            position: absolute;
            left: 50%;
            top: 18px;
            transform: translateX(-50%);
            z-index: 105;
            min-width: 240px;
            padding: 10px 18px;
            text-align: center;
            border-radius: 16px;
            background: rgba(3, 12, 26, .84);
            border: 1px solid rgba(6, 182, 212, .28);
            backdrop-filter: blur(15px);
            color: white;
            pointer-events: none;
        }

        .cpe-target-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #67e8f9;
            font-weight: 800;
        }

        .cpe-target-gate {
            font-size: 20px;
            font-weight: 900;
            color: #fbbf24;
            margin: 2px 0;
        }

        .cpe-target-bits {
            font-family: "Courier New", monospace;
            font-size: 15px;
            letter-spacing: 5px;
            color: #22d3ee;
            font-weight: 800;
        }

        /* ----- Sequence Display ----- */
        .cpe-sequence {
            position: absolute;
            left: 50%;
            bottom: 16px;
            transform: translateX(-50%);
            z-index: 110;
            padding: 9px 16px;
            border-radius: 15px;
            background: rgba(3, 12, 26, .84);
            border: 1px solid rgba(148, 163, 184, .16);
            backdrop-filter: blur(15px);
            color: white;
            text-align: center;
            pointer-events: none;
        }

        .cpe-sequence-label {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: #94a3b8;
        }

        .cpe-sequence-value {
            font-family: "Courier New", monospace;
            font-size: 19px;
            letter-spacing: 5px;
            font-weight: 900;
            min-width: 140px;
            display: inline-block;
        }

        /* ----- Power Status ----- */
        .cpe-power-status {
            position: absolute;
            top: 100px;
            right: 16px;
            z-index: 105;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            pointer-events: none;
        }

        .cpe-power-item {
            display: none;
            padding: 5px 10px;
            border-radius: 9px;
            background: rgba(3, 12, 26, .78);
            border: 1px solid rgba(148, 163, 184, .15);
            color: white;
            font-size: 10px;
            font-weight: 800;
            backdrop-filter: blur(10px);
        }

        .cpe-power-item.active {
            display: block;
        }

        /* ----- Player ----- */
        .cpe-player {
            position: absolute;
            width: 46px;
            height: 46px;
            transform: translate3d(-50%, -50%, 0);
            will-change: transform;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .cpe-player-core {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #38bdf8, #0B4F8A 60%, #020617);
            border: 2px solid #22d3ee;
            box-shadow: 0 0 22px rgba(34, 211, 238, .50);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: box-shadow .2s;
        }

        .cpe-player.shielded .cpe-player-core {
            box-shadow: 0 0 30px rgba(96, 165, 250, .90), inset 0 0 15px rgba(96, 165, 250, .40);
            border-color: #60a5fa;
        }

        .cpe-player.ghost .cpe-player-core {
            opacity: .45;
            box-shadow: 0 0 30px rgba(192, 132, 252, .90);
            border-color: #c084fc;
        }

        .cpe-player-icon {
            font-size: 20px;
            color: white;
            z-index: 2;
            text-shadow: 0 0 8px rgba(34, 211, 238, .80);
        }

        /* ----- Bit Tokens ----- */
        .cpe-bit {
            position: absolute;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-family: "Courier New", monospace;
            font-size: 16px;
            font-weight: 900;
            color: white;
            background: radial-gradient(circle at 35% 30%, rgba(255, 255, 255, .30), rgba(11, 79, 138, .85));
            border: 2px solid rgba(103, 232, 249, .70);
            box-shadow: 0 0 16px rgba(34, 211, 238, .30);
            transform: translate3d(-50%, -50%, 0);
            animation: cpeBitPulse 1.4s ease-in-out infinite;
        }

        .cpe-bit.zero {
            border-color: rgba(34, 211, 238, .80);
            box-shadow: 0 0 18px rgba(34, 211, 238, .35);
        }

        .cpe-bit.one {
            border-color: rgba(245, 158, 11, .85);
            box-shadow: 0 0 18px rgba(245, 158, 11, .40);
        }

        @keyframes cpeBitPulse {

            0%,
            100% {
                transform: translate3d(-50%, -50%, 0) scale(1);
            }

            50% {
                transform: translate3d(-50%, -50%, 0) scale(1.10);
            }
        }

        /* ----- Gate (Target Component) ----- */
        .cpe-gate {
            position: absolute;
            width: 60px;
            height: 60px;
            transform: translate3d(-50%, -50%, 0);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(15, 23, 42, .90);
            border: 2px solid #8b5cf6;
            box-shadow: 0 0 25px rgba(139, 92, 246, .40);
            color: white;
            animation: cpeGatePulse 1.5s ease-in-out infinite;
        }

        .cpe-gate-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #c4b5fd;
        }

        .cpe-gate-icon {
            font-size: 22px;
            margin-top: 2px;
        }

        @keyframes cpeGatePulse {

            0%,
            100% {
                transform: translate3d(-50%, -50%, 0) scale(.96);
            }

            50% {
                transform: translate3d(-50%, -50%, 0) scale(1.06);
            }
        }

        /* ----- Power-ups ----- */
        .cpe-power {
            position: absolute;
            width: 46px;
            height: 46px;
            transform: translate3d(-50%, -50%, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(3, 12, 26, .90);
            border: 2px solid currentColor;
            color: #22d3ee;
            box-shadow: 0 0 25px currentColor;
            animation: cpePowerFloat 1.3s ease-in-out infinite;
        }

        .cpe-power i {
            font-size: 20px;
        }

        .cpe-power.shield {
            color: #60a5fa;
        }

        .cpe-power.magnet {
            color: #f472b6;
        }

        .cpe-power.ghost {
            color: #c084fc;
        }

        .cpe-power.slow {
            color: #22d3ee;
        }

        .cpe-power.double {
            color: #4ade80;
        }

        .cpe-power.freeze {
            color: #a5f3fc;
        }

        @keyframes cpePowerFloat {

            0%,
            100% {
                transform: translate3d(-50%, -50%, 0) translateY(0) scale(1);
            }

            50% {
                transform: translate3d(-50%, -50%, 0) translateY(-7px) scale(1.08);
            }
        }

        /* ----- Overlay ----- */
        .cpe-overlay {
            position: absolute;
            inset: 0;
            z-index: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
            background: radial-gradient(circle at center, rgba(11, 79, 138, .20), rgba(2, 6, 23, .86));
            backdrop-filter: blur(8px);
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity .25s ease, visibility .25s ease;
        }

        .cpe-overlay.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .cpe-panel {
            width: min(520px, 94vw);
            padding: 34px;
            text-align: center;
            border-radius: 26px;
            background: rgba(7, 18, 35, .94);
            border: 1px solid rgba(148, 163, 184, .18);
            box-shadow: 0 35px 100px rgba(0, 0, 0, .55);
            color: white;
            backdrop-filter: blur(22px);
        }

        .cpe-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 35% 30%, #38bdf8, #0B4F8A 60%, #020617);
            border: 2px solid rgba(103, 232, 249, .65);
            box-shadow: 0 0 40px rgba(34, 211, 238, .30);
        }

        .cpe-logo i {
            font-size: 30px;
            color: #22d3ee;
        }

        .cpe-panel h2 {
            margin-bottom: 8px;
            font-weight: 900;
            font-size: 1.5rem;
        }

        .cpe-panel p {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 22px;
            font-size: .92rem;
        }

        .cpe-preview {
            margin: 18px auto;
            padding: 14px;
            border-radius: 15px;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(148, 163, 184, .12);
        }

        .cpe-preview-label {
            display: block;
            font-size: 9px;
            letter-spacing: 1.4px;
            color: #64748b;
            text-transform: uppercase;
        }

        .cpe-preview-value {
            font-family: "Courier New", monospace;
            font-size: 22px;
            letter-spacing: 4px;
            font-weight: 900;
            color: #fbbf24;
        }

        .cpe-btn-primary {
            width: 100%;
            border: 0;
            border-radius: 13px;
            padding: 13px;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: #111827;
            font-weight: 900;
            box-shadow: 0 12px 30px rgba(245, 158, 11, .22);
            transition: transform .2s ease;
            cursor: pointer;
            font-size: 1rem;
        }

        .cpe-btn-primary:hover {
            transform: translateY(-2px);
        }

        .cpe-btn-secondary {
            margin-top: 9px;
            width: 100%;
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 13px;
            padding: 11px;
            background: rgba(255, 255, 255, .05);
            color: white;
            font-weight: 800;
            cursor: pointer;
            font-size: .95rem;
        }

        .cpe-btn-secondary:hover {
            background: rgba(255, 255, 255, .10);
        }

        /* ----- Controls ----- */
        .cpe-controls {
            position: absolute;
            left: 18px;
            bottom: 18px;
            z-index: 120;
            display: flex;
            gap: 7px;
            pointer-events: auto;
        }

        .cpe-control-btn {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            border: 1px solid rgba(148, 163, 184, .16);
            background: rgba(3, 12, 26, .80);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            backdrop-filter: blur(12px);
            user-select: none;
            touch-action: none;
            cursor: pointer;
            transition: background .15s;
        }

        .cpe-control-btn:hover {
            background: rgba(30, 58, 95, .90);
        }

        .cpe-control-btn:active {
            transform: scale(.94);
        }

        .cpe-boost-btn {
            width: auto;
            padding: 0 15px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #fbbf24;
            border-color: rgba(245, 158, 11, .30);
        }

        /* ----- Pause ----- */
        .cpe-pause-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            z-index: 150;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, .16);
            background: rgba(3, 12, 26, .78);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: auto;
            cursor: pointer;
            font-size: 1.1rem;
        }

        /* ----- FX ----- */
        .cpe-ring {
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            transform: translate3d(-50%, -50%, 0);
            border: 3px solid #22c55e;
            box-shadow: 0 0 30px rgba(34, 197, 94, .85);
            animation: cpeRing .65s cubic-bezier(.1, .7, .2, 1) forwards;
        }

        @keyframes cpeRing {
            from {
                opacity: 1;
                width: 18px;
                height: 18px;
            }

            to {
                opacity: 0;
                width: 220px;
                height: 220px;
            }
        }

        .cpe-spark {
            position: absolute;
            transform: translate3d(-50%, -50%, 0);
            font-family: "Courier New", monospace;
            font-size: 15px;
            font-weight: 900;
            color: #fbbf24;
            text-shadow: 0 0 12px #f59e0b;
            animation: cpeSpark .8s cubic-bezier(.1, .7, .2, 1) forwards;
        }

        @keyframes cpeSpark {
            from {
                opacity: 1;
                transform: translate3d(-50%, -50%, 0) translate3d(0, 0, 0) scale(1);
            }

            to {
                opacity: 0;
                transform: translate3d(-50%, -50%, 0) translate3d(var(--dx), var(--dy), 0) rotate(var(--rot)) scale(.3);
            }
        }

        .cpe-shake {
            animation: cpeShake .35s ease-out;
        }

        @keyframes cpeShake {

            0%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            20% {
                transform: translate3d(-7px, 4px, 0);
            }

            40% {
                transform: translate3d(6px, -4px, 0);
            }

            60% {
                transform: translate3d(-4px, -2px, 0);
            }

            80% {
                transform: translate3d(3px, 2px, 0);
            }
        }

        @keyframes cpeMessagePop {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(.65);
            }

            20% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.08);
            }

            75% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }

            100% {
                opacity: 0;
                transform: translate(-50%, -65%) scale(1.05);
            }
        }

        /* ----- Responsive ----- */
        @media (max-width: 800px) {
            .cpe-hud-card {
                min-width: 80px;
                padding: 7px 9px;
            }

            .cpe-hud-value {
                font-size: 14px;
            }

            .cpe-target-card {
                top: 76px;
                min-width: 200px;
            }

            .cpe-sequence {
                bottom: 84px;
            }

            .cpe-controls {
                bottom: 18px;
                left: 12px;
            }

            .cpe-control-btn {
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 560px) {
            .cpe-hud-group.right {
                display: none;
            }

            .cpe-panel {
                padding: 26px 18px;
            }

            .cpe-sequence {
                max-width: 170px;
                bottom: 80px;
            }
        }

        button {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible {
            outline: 2px solid #22d3ee;
            outline-offset: 3px;
        }
    </style>
</head>

<body>

    <!-- =========================================================
         PAGE CONTENT
         ========================================================= -->
    <main class="cpe-page">
        <div class="cpe-wrapper">
            <div class="cpe-intro">
                <div class="cpe-intro-content">
                    <div class="cpe-tagline">ETS-ASYNC • CPE ACADEMIC GAME</div>
                    <h1><i class="bi bi-cpu"></i>CPE Bit Builder</h1>
                    <p>
                        A fast-paced logic game for Computer Engineering students.
                        Collect the correct binary inputs to match the target logic gate output.
                        Use power-ups, avoid glitches, and climb the leaderboard.
                    </p>
                    <button type="button" id="cpeLaunchBtn" class="cpe-launch">
                        <i class="bi bi-play-fill"></i> Launch CPE Bit Builder
                    </button>
                </div>
            </div>

            <div class="cpe-info-grid">
                <div class="cpe-info-card">
                    <h3><i class="bi bi-1-circle-fill"></i> Collect Binary Bits</h3>
                    <p>Grab 0s and 1s from the circuit board. Each one becomes part of your input sequence.</p>
                </div>
                <div class="cpe-info-card">
                    <h3><i class="bi bi-2-circle-fill"></i> Match the Gate</h3>
                    <p>Each round shows a target gate (AND, OR, XOR, NAND). Collect the exact input pattern to pass.</p>
                </div>
                <div class="cpe-info-card">
                    <h3><i class="bi bi-3-circle-fill"></i> Use Power-Ups</h3>
                    <p>Shields, magnets, ghost mode, slow time, and double score help you survive longer.</p>
                </div>
                <div class="cpe-info-card">
                    <h3><i class="bi bi-4-circle-fill"></i> Avoid Glitches</h3>
                    <p>Corrupted data nodes appear after each round. Touch one and your circuit resets.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- =========================================================
         FULL-SCREEN GAME
         ========================================================= -->
    <div id="cpeGameShell" class="cpe-game-shell" aria-hidden="true">
        <div id="cpeWorld" class="cpe-world">
            <div id="cpeArena" class="cpe-arena">

                <!-- LAYERS -->
                <div id="cpeGateLayer"></div>
                <div id="cpeBitLayer"></div>
                <div id="cpePowerLayer"></div>
                <div id="cpePlayerLayer"></div>
                <div id="cpeFxLayer"></div>

                <!-- HUD -->
                <div class="cpe-hud">
                    <div class="cpe-hud-group left">
                        <div class="cpe-hud-card">
                            <span class="cpe-hud-label">Score</span>
                            <span id="cpeScore" class="cpe-hud-value">0</span>
                        </div>
                        <div class="cpe-hud-card">
                            <span class="cpe-hud-label">Round</span>
                            <span id="cpeRound" class="cpe-hud-value">1</span>
                        </div>
                        <div class="cpe-hud-card">
                            <span class="cpe-hud-label">Time</span>
                            <span id="cpeTime" class="cpe-hud-value">60</span>
                        </div>
                    </div>
                    <div class="cpe-hud-group right">
                        <div class="cpe-hud-card">
                            <span class="cpe-hud-label">Bits</span>
                            <span id="cpeBits" class="cpe-hud-value">0 / 4</span>
                        </div>
                        <div class="cpe-hud-card">
                            <span class="cpe-hud-label">Shield</span>
                            <span id="cpeShield" class="cpe-hud-value">0</span>
                        </div>
                    </div>
                </div>

                <!-- TARGET GATE -->
                <div class="cpe-target-card">
                    <div class="cpe-target-label">Target Gate</div>
                    <div id="cpeTargetGate" class="cpe-target-gate">AND</div>
                    <div id="cpeTargetBits" class="cpe-target-bits">0000</div>
                </div>

                <!-- POWER STATUS -->
                <div class="cpe-power-status">
                    <div id="cpeMagnetStatus" class="cpe-power-item">
                        <i class="bi bi-magnet-fill"></i> Magnet <span id="cpeMagnetTimer"></span>
                    </div>
                    <div id="cpeGhostStatus" class="cpe-power-item">
                        <i class="bi bi-ghost-fill"></i> Ghost <span id="cpeGhostTimer"></span>
                    </div>
                    <div id="cpeSlowStatus" class="cpe-power-item">
                        <i class="bi bi-hourglass-split"></i> Slow <span id="cpeSlowTimer"></span>
                    </div>
                    <div id="cpeDoubleStatus" class="cpe-power-item">
                        <i class="bi bi-2-circle-fill"></i> x2 Score <span id="cpeDoubleTimer"></span>
                    </div>
                    <div id="cpeFreezeStatus" class="cpe-power-item">
                        <i class="bi bi-snow"></i> Freeze <span id="cpeFreezeTimer"></span>
                    </div>
                </div>

                <!-- SEQUENCE -->
                <div class="cpe-sequence">
                    <span class="cpe-sequence-label">Collected Bits</span>
                    <span id="cpeSequence" class="cpe-sequence-value">----</span>
                </div>

                <!-- PAUSE -->
                <button id="cpePauseBtn" class="cpe-pause-btn" type="button" aria-label="Pause">
                    <i class="bi bi-pause-fill"></i>
                </button>

                <!-- CONTROLS -->
                <div class="cpe-controls">
                    <button id="cpeUp" class="cpe-control-btn" type="button" aria-label="Up"><i class="bi bi-chevron-up"></i></button>
                    <button id="cpeDown" class="cpe-control-btn" type="button" aria-label="Down"><i class="bi bi-chevron-down"></i></button>
                    <button id="cpeLeft" class="cpe-control-btn" type="button" aria-label="Left"><i class="bi bi-chevron-left"></i></button>
                    <button id="cpeRight" class="cpe-control-btn" type="button" aria-label="Right"><i class="bi bi-chevron-right"></i></button>
                    <button id="cpeBoost" class="cpe-control-btn cpe-boost-btn" type="button">
                        <i class="bi bi-lightning-fill"></i> BOOST
                    </button>
                </div>

                <!-- OVERLAY -->
                <div id="cpeOverlay" class="cpe-overlay">
                    <div class="cpe-panel">
                        <div class="cpe-logo"><i class="bi bi-cpu"></i></div>
                        <h2 id="cpeOverlayTitle">CPE Bit Builder</h2>
                        <p id="cpeOverlayMessage">
                            Collect four binary bits in the correct order to match the target logic gate.
                            <br><br>
                            Move with <strong>WASD</strong> or <strong>Arrow Keys</strong>.
                            Hold <strong>SPACE</strong> or <strong>SHIFT</strong> to boost.
                        </p>
                        <div class="cpe-preview">
                            <span class="cpe-preview-label">Example Target</span>
                            <div id="cpeOverlayTarget" class="cpe-preview-value">AND · 1010</div>
                        </div>
                        <button id="cpeStartBtn" class="cpe-btn-primary" type="button">
                            <i class="bi bi-play-fill"></i> Start Game
                        </button>
                        <button id="cpeExitBtn" class="cpe-btn-secondary" type="button">Exit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- =========================================================
         GAME ENGINE
         ========================================================= -->
    <script>
        (function() {
            "use strict";

            /* =========================================================
               DOM REFERENCES
               ========================================================= */
            const shell = document.getElementById("cpeGameShell");
            const world = document.getElementById("cpeWorld");
            const arena = document.getElementById("cpeArena");
            const overlay = document.getElementById("cpeOverlay");
            const overlayTitle = document.getElementById("cpeOverlayTitle");
            const overlayMessage = document.getElementById("cpeOverlayMessage");
            const overlayTarget = document.getElementById("cpeOverlayTarget");
            const startBtn = document.getElementById("cpeStartBtn");
            const exitBtn = document.getElementById("cpeExitBtn");
            const launchBtn = document.getElementById("cpeLaunchBtn");
            const pauseBtn = document.getElementById("cpePauseBtn");

            const gateLayer = document.getElementById("cpeGateLayer");
            const bitLayer = document.getElementById("cpeBitLayer");
            const powerLayer = document.getElementById("cpePowerLayer");
            const playerLayer = document.getElementById("cpePlayerLayer");
            const fxLayer = document.getElementById("cpeFxLayer");

            const scoreEl = document.getElementById("cpeScore");
            const roundEl = document.getElementById("cpeRound");
            const timeEl = document.getElementById("cpeTime");
            const bitsEl = document.getElementById("cpeBits");
            const shieldEl = document.getElementById("cpeShield");
            const targetGateEl = document.getElementById("cpeTargetGate");
            const targetBitsEl = document.getElementById("cpeTargetBits");
            const sequenceEl = document.getElementById("cpeSequence");

            const magnetStatus = document.getElementById("cpeMagnetStatus");
            const ghostStatus = document.getElementById("cpeGhostStatus");
            const slowStatus = document.getElementById("cpeSlowStatus");
            const doubleStatus = document.getElementById("cpeDoubleStatus");
            const freezeStatus = document.getElementById("cpeFreezeStatus");
            const magnetTimer = document.getElementById("cpeMagnetTimer");
            const ghostTimer = document.getElementById("cpeGhostTimer");
            const slowTimer = document.getElementById("cpeSlowTimer");
            const doubleTimer = document.getElementById("cpeDoubleTimer");
            const freezeTimer = document.getElementById("cpeFreezeTimer");

            /* =========================================================
               CONSTANTS
               ========================================================= */
            const BITS_PER_ROUND = 4;
            const GAME_DURATION = 60;
            const BASE_SPEED = 240;
            const BOOST_SPEED = 460;
            const MAX_BOOST = 100;
            const BOOST_DRAIN = 40;
            const BOOST_RECHARGE = 22;
            const MAX_BITS = 22;
            const MAX_POWERS = 4;

            const GATES = [{
                    name: "AND",
                    icon: "bi-diagram-2",
                    color: "#22d3ee"
                },
                {
                    name: "OR",
                    icon: "bi-diagram-3",
                    color: "#4ade80"
                },
                {
                    name: "XOR",
                    icon: "bi-shuffle",
                    color: "#fbbf24"
                },
                {
                    name: "NAND",
                    icon: "bi-slash-circle",
                    color: "#f472b6"
                },
                {
                    name: "NOR",
                    icon: "bi-dash-circle",
                    color: "#c084fc"
                }
            ];

            /* =========================================================
               STATE
               ========================================================= */
            let running = false;
            let paused = false;
            let score = 0;
            let round = 1;
            let timeLeft = GAME_DURATION;
            let boostEnergy = MAX_BOOST;
            let boosting = false;
            let animationId = null;
            let lastTime = performance.now();
            let width = 1,
                height = 1;
            let objectId = 0;
            let lastDirChange = 0;

            let direction = {
                x: 1,
                y: 0
            };
            let nextDirection = {
                x: 1,
                y: 0
            };

            let player = {
                x: 0,
                y: 0
            };
            let playerEl = null;

            let targetGate = GATES[0];
            let targetBits = "0000";
            let collected = "";

            let bits = [];
            let powers = [];
            let glitches = [];

            let shieldCount = 0;
            let magnetUntil = 0;
            let ghostUntil = 0;
            let slowUntil = 0;
            let doubleUntil = 0;
            let freezeUntil = 0;

            /* =========================================================
               HELPERS
               ========================================================= */
            const rand = (a, b) => Math.random() * (b - a) + a;
            const randInt = (a, b) => Math.floor(Math.random() * (b - a + 1)) + a;
            const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
            const dist = (a, b) => Math.hypot(a.x - b.x, a.y - b.y);

            function wrap(p) {
                if (p.x < 0) p.x += width;
                if (p.x >= width) p.x -= width;
                if (p.y < 0) p.y += height;
                if (p.y >= height) p.y -= height;
            }

            function resize() {
                const r = arena.getBoundingClientRect();
                width = r.width;
                height = r.height;
            }

            /* =========================================================
               TARGET GENERATION
               ========================================================= */
            function generateTarget() {
                targetGate = GATES[randInt(0, GATES.length - 1)];
                let bits = "";
                for (let i = 0; i < BITS_PER_ROUND; i++) {
                    bits += Math.random() < .5 ? "0" : "1";
                }
                targetBits = bits;

                targetGateEl.textContent = targetGate.name;
                targetGateEl.style.color = targetGate.color;
                targetBitsEl.textContent = targetBits;
                overlayTarget.textContent = targetGate.name + " · " + targetBits;
            }

            /* =========================================================
               SEQUENCE UI
               ========================================================= */
            function updateSequenceUI() {
                let d = collected;
                while (d.length < BITS_PER_ROUND) d += "-";
                sequenceEl.textContent = d;
                bitsEl.textContent = collected.length + " / " + BITS_PER_ROUND;
            }

            function resetSequence() {
                collected = "";
                updateSequenceUI();
            }

            /* =========================================================
               SCORE
               ========================================================= */
            function addScore(points) {
                const mult = doubleUntil > performance.now() ? 2 : 1;
                score += points * mult;
                scoreEl.textContent = Math.floor(score);
            }

            /* =========================================================
               PLAYER
               ========================================================= */
            function createPlayer() {
                player.x = width / 2;
                player.y = height / 2;

                playerEl = document.createElement("div");
                playerEl.className = "cpe-player";
                playerEl.innerHTML = `
                    <div class="cpe-player-core">
                        <i class="bi bi-cpu-fill cpe-player-icon"></i>
                    </div>
                `;
                playerLayer.appendChild(playerEl);
            }

            function renderPlayer() {
                if (!playerEl) return;
                playerEl.style.transform =
                    `translate3d(${player.x}px, ${player.y}px, 0) translate3d(-50%,-50%,0)`;

                playerEl.classList.toggle("shielded", shieldCount > 0);
                playerEl.classList.toggle("ghost", ghostUntil > performance.now());
            }

            function movePlayer(dt) {
                const now = performance.now();
                if (now - lastDirChange > 50) {
                    direction.x = nextDirection.x;
                    direction.y = nextDirection.y;
                    lastDirChange = now;
                }

                let speed = BASE_SPEED;
                if (boosting && boostEnergy > 0) speed = BOOST_SPEED;
                if (slowUntil > now) speed *= .55;
                if (freezeUntil > now) speed *= .75;

                player.x += direction.x * speed * dt;
                player.y += direction.y * speed * dt;
                wrap(player);
            }

            /* =========================================================
               BITS
               ========================================================= */
            function createBit(value) {
                const bit = {
                    id: ++objectId,
                    value: String(value),
                    x: rand(70, Math.max(71, width - 70)),
                    y: rand(130, Math.max(131, height - 90))
                };

                const el = document.createElement("div");
                el.className = "cpe-bit " + (value === "0" ? "zero" : "one");
                el.textContent = value;
                bit.el = el;
                bitLayer.appendChild(el);
                bits.push(bit);
                return bit;
            }

            function createBits() {
                bits.forEach(b => b.el?.remove());
                bits = [];

                // Ensure target bits exist
                for (let i = 0; i < BITS_PER_ROUND; i++) {
                    createBit(targetBits[i]);
                }
                // Fill with random bits
                for (let i = BITS_PER_ROUND; i < MAX_BITS; i++) {
                    createBit(Math.random() < .5 ? "0" : "1");
                }
                renderBits();
            }

            function renderBits() {
                bits.forEach(b => {
                    if (!b.el) return;
                    b.el.style.left = b.x + "px";
                    b.el.style.top = b.y + "px";
                });
            }

            /* =========================================================
               BIT COLLECTION
               ========================================================= */
            function checkBitCollection() {
                for (let i = bits.length - 1; i >= 0; i--) {
                    const b = bits[i];
                    const collectDist = magnetUntil > performance.now() ? 44 : 32;
                    if (dist(player, b) < collectDist) {
                        collectBit(b, i);
                    }
                }
            }

            function collectBit(bit, index) {
                const value = bit.value;
                bit.el?.remove();
                bits.splice(index, 1);

                collected += value;
                addScore(10);
                updateSequenceUI();

                if (collected.length >= BITS_PER_ROUND) {
                    validate();
                }

                while (bits.length < MAX_BITS) {
                    createBit(Math.random() < .5 ? "0" : "1");
                }
            }

            /* =========================================================
               MAGNET
               ========================================================= */
            function updateMagnet(dt) {
                if (magnetUntil <= performance.now()) return;
                bits.forEach(b => {
                    const dx = player.x - b.x;
                    const dy = player.y - b.y;
                    const d = Math.hypot(dx, dy);
                    if (d > 5 && d < 250) {
                        const strength = 170 * dt * (1 - d / 250);
                        b.x += (dx / d) * strength;
                        b.y += (dy / d) * strength;
                    }
                });
            }

            /* =========================================================
               VALIDATION
               ========================================================= */
            function validate() {
                if (collected === targetBits) {
                    completeRound();
                } else {
                    glitchExplosion();
                }
            }

            function completeRound() {
                addScore(100);
                round++;
                roundEl.textContent = round;

                spawnGlitchWave();
                spawnPowerWave();

                timeLeft = Math.min(GAME_DURATION, timeLeft + 4);

                resetSequence();
                generateTarget();
                createBits();

                showMessage("CIRCUIT COMPLETE!", "#22c55e");
            }

            /* =========================================================
               GLITCHES
               ========================================================= */
            function spawnGlitchWave() {
                const amount = Math.min(3 + round, 14);
                for (let i = 0; i < amount; i++) spawnGlitch();
            }

            function spawnGlitch() {
                const g = {
                    id: ++objectId,
                    x: rand(80, Math.max(81, width - 80)),
                    y: rand(140, Math.max(141, height - 120)),
                    width: randInt(55, 95)
                };

                // Avoid spawning on player
                if (dist(g, player) < 140) return;

                const el = document.createElement("div");
                el.className = "binary-obstacle"; // reuse style
                el.style.position = "absolute";
                el.style.width = g.width + "px";
                el.style.height = "26px";
                el.style.left = g.x + "px";
                el.style.top = g.y + "px";
                el.style.transform = `translate3d(-50%,-50%,0) rotate(${randInt(-30,30)}deg)`;
                el.style.borderRadius = "8px";
                el.style.background = "linear-gradient(90deg,#1e1b4b,#7f1d1d,#1e1b4b)";
                el.style.border = "1px solid rgba(248,113,113,.7)";
                el.style.boxShadow = "0 0 20px rgba(239,68,68,.25)";
                el.style.display = "flex";
                el.style.alignItems = "center";
                el.style.justifyContent = "center";
                el.style.fontFamily = "'Courier New', monospace";
                el.style.fontSize = "9px";
                el.style.letterSpacing = "2px";
                el.style.color = "rgba(248,113,113,.85)";
                el.textContent = "ERR 0x1F";

                g.el = el;
                gateLayer.appendChild(el); // use gateLayer as obstacle layer
                glitches.push(g);
            }

            function checkGlitchCollision() {
                if (ghostUntil > performance.now()) return;
                for (let i = glitches.length - 1; i >= 0; i--) {
                    const g = glitches[i];
                    if (dist(player, g) < 40 + g.width / 2) {
                        handleGlitchHit(g, i);
                        return;
                    }
                }
            }

            function handleGlitchHit(g, index) {
                if (shieldCount > 0) {
                    shieldCount--;
                    shieldEl.textContent = shieldCount;
                    g.el?.remove();
                    glitches.splice(index, 1);
                    showMessage("SHIELD BLOCK!", "#60a5fa");
                    return;
                }
                gameOver("Your circuit was corrupted by a glitch node.");
            }

            /* =========================================================
               POWERS
               ========================================================= */
            const POWER_TYPES = [{
                    type: "shield",
                    icon: "bi-shield-fill-check"
                },
                {
                    type: "magnet",
                    icon: "bi-magnet-fill"
                },
                {
                    type: "ghost",
                    icon: "bi-ghost-fill"
                },
                {
                    type: "slow",
                    icon: "bi-hourglass-split"
                },
                {
                    type: "double",
                    icon: "bi-2-circle-fill"
                },
                {
                    type: "freeze",
                    icon: "bi-snow"
                }
            ];

            function spawnPowerWave() {
                spawnPower();
                if (Math.random() < .4) spawnPower();
            }

            function spawnPower() {
                if (powers.length >= MAX_POWERS) return;

                const def = POWER_TYPES[randInt(0, POWER_TYPES.length - 1)];
                const p = {
                    id: ++objectId,
                    type: def.type,
                    x: rand(80, Math.max(81, width - 80)),
                    y: rand(140, Math.max(141, height - 120))
                };

                if (dist(p, player) < 110) return;

                const el = document.createElement("div");
                el.className = "cpe-power " + p.type;
                el.innerHTML = `<i class="bi ${def.icon}"></i>`;
                p.el = el;
                powerLayer.appendChild(el);
                powers.push(p);
                renderPowers();
            }

            function renderPowers() {
                powers.forEach(p => {
                    if (!p.el) return;
                    p.el.style.left = p.x + "px";
                    p.el.style.top = p.y + "px";
                });
            }

            function checkPowerCollection() {
                for (let i = powers.length - 1; i >= 0; i--) {
                    const p = powers[i];
                    if (dist(player, p) < 42) {
                        activatePower(p);
                        p.el?.remove();
                        powers.splice(i, 1);
                    }
                }
            }

            function activatePower(p) {
                const now = performance.now();
                switch (p.type) {
                    case "shield":
                        shieldCount = Math.min(shieldCount + 1, 2);
                        shieldEl.textContent = shieldCount;
                        showMessage("SHIELD!", "#60a5fa");
                        break;
                    case "magnet":
                        magnetUntil = now + 7000;
                        showMessage("MAGNET!", "#f472b6");
                        break;
                    case "ghost":
                        ghostUntil = now + 7000;
                        showMessage("GHOST MODE!", "#c084fc");
                        break;
                    case "slow":
                        slowUntil = now + 7000;
                        showMessage("SLOW TIME!", "#22d3ee");
                        break;
                    case "double":
                        doubleUntil = now + 8000;
                        showMessage("DOUBLE SCORE!", "#4ade80");
                        break;
                    case "freeze":
                        freezeUntil = now + 5000;
                        showMessage("FREEZE!", "#a5f3fc");
                        break;
                }
            }

            function updatePowerTimers() {
                const now = performance.now();
                updateTimer(magnetStatus, magnetTimer, magnetUntil, now);
                updateTimer(ghostStatus, ghostTimer, ghostUntil, now);
                updateTimer(slowStatus, slowTimer, slowUntil, now);
                updateTimer(doubleStatus, doubleTimer, doubleUntil, now);
                updateTimer(freezeStatus, freezeTimer, freezeUntil, now);
            }

            function updateTimer(el, timerEl, until, now) {
                const rem = until - now;
                if (rem > 0) {
                    el.classList.add("active");
                    timerEl.textContent = " " + (rem / 1000).toFixed(1) + "s";
                } else {
                    el.classList.remove("active");
                    timerEl.textContent = "";
                }
            }

            /* =========================================================
               BOOST
               ========================================================= */
            function updateBoost(dt) {
                if (boosting && boostEnergy > 0) {
                    boostEnergy -= BOOST_DRAIN * dt;
                    if (boostEnergy <= 0) {
                        boostEnergy = 0;
                        boosting = false;
                    }
                } else {
                    boostEnergy += BOOST_RECHARGE * dt;
                }
                boostEnergy = clamp(boostEnergy, 0, MAX_BOOST);
            }

            /* =========================================================
               EXPLOSION
               ========================================================= */
            function glitchExplosion() {
                const ring = document.createElement("div");
                ring.className = "cpe-ring";
                ring.style.left = player.x + "px";
                ring.style.top = player.y + "px";
                ring.style.borderColor = "#ef4444";
                ring.style.boxShadow = "0 0 30px rgba(239,68,68,.85)";
                fxLayer.appendChild(ring);

                for (let i = 0; i < 22; i++) {
                    const s = document.createElement("div");
                    s.className = "cpe-spark";
                    s.textContent = Math.random() < .5 ? "0" : "1";
                    s.style.left = player.x + "px";
                    s.style.top = player.y + "px";
                    const angle = Math.random() * Math.PI * 2;
                    const force = rand(60, 220);
                    s.style.setProperty("--dx", Math.cos(angle) * force + "px");
                    s.style.setProperty("--dy", Math.sin(angle) * force + "px");
                    s.style.setProperty("--rot", randInt(-360, 360) + "deg");
                    fxLayer.appendChild(s);
                    setTimeout(() => s.remove(), 850);
                }

                world.classList.remove("cpe-shake");
                void world.offsetWidth;
                world.classList.add("cpe-shake");

                showMessage("GLITCH!", "#ef4444");
                resetSequence();

                setTimeout(() => ring.remove(), 700);
            }

            /* =========================================================
               MESSAGES
               ========================================================= */
            function showMessage(msg, color) {
                const el = document.createElement("div");
                el.textContent = msg;
                el.style.position = "absolute";
                el.style.left = "50%";
                el.style.top = "50%";
                el.style.zIndex = "450";
                el.style.transform = "translate(-50%,-50%)";
                el.style.fontSize = "clamp(22px,4vw,42px)";
                el.style.fontWeight = "900";
                el.style.letterSpacing = "3px";
                el.style.color = color;
                el.style.textShadow = `0 0 25px ${color}`;
                el.style.pointerEvents = "none";
                el.style.animation = "cpeMessagePop .9s ease forwards";
                fxLayer.appendChild(el);
                setTimeout(() => el.remove(), 950);
            }

            /* =========================================================
               INPUT
               ========================================================= */
            function setDir(x, y) {
                if (direction.x === -x && direction.y === -y) return;
                nextDirection = {
                    x,
                    y
                };
            }

            document.addEventListener("keydown", e => {
                if (!running) return;
                const k = e.key.toLowerCase();
                if (["arrowup", "arrowdown", "arrowleft", "arrowright", " ", "shift"].includes(k)) {
                    e.preventDefault();
                }
                switch (k) {
                    case "arrowup":
                    case "w":
                        setDir(0, -1);
                        break;
                    case "arrowdown":
                    case "s":
                        setDir(0, 1);
                        break;
                    case "arrowleft":
                    case "a":
                        setDir(-1, 0);
                        break;
                    case "arrowright":
                    case "d":
                        setDir(1, 0);
                        break;
                    case " ":
                    case "shift":
                        boosting = true;
                        break;
                    case "p":
                        togglePause();
                        break;
                }
            });

            document.addEventListener("keyup", e => {
                const k = e.key.toLowerCase();
                if (k === " " || k === "shift") boosting = false;
            });

            function bindBtn(id, x, y) {
                const b = document.getElementById(id);
                if (!b) return;
                b.addEventListener("pointerdown", e => {
                    e.preventDefault();
                    setDir(x, y);
                });
            }

            bindBtn("cpeUp", 0, -1);
            bindBtn("cpeDown", 0, 1);
            bindBtn("cpeLeft", -1, 0);
            bindBtn("cpeRight", 1, 0);

            const boostBtn = document.getElementById("cpeBoost");
            boostBtn.addEventListener("pointerdown", e => {
                e.preventDefault();
                boosting = true;
            });
            boostBtn.addEventListener("pointerup", () => boosting = false);
            boostBtn.addEventListener("pointercancel", () => boosting = false);
            boostBtn.addEventListener("pointerleave", () => boosting = false);

            /* =========================================================
               PAUSE
               ========================================================= */
            function togglePause() {
                if (!running) return;
                paused = !paused;
                if (paused) {
                    overlayTitle.textContent = "Paused";
                    overlayMessage.innerHTML = "Game paused. Press <strong>P</strong> or Resume to continue.";
                    startBtn.innerHTML = `<i class="bi bi-play-fill"></i> Resume`;
                    exitBtn.textContent = "Exit Game";
                    overlay.classList.remove("hidden");
                } else {
                    overlay.classList.add("hidden");
                    lastTime = performance.now();
                }
            }

            /* =========================================================
               START / EXIT
               ========================================================= */
            function clearAll() {
                bitLayer.innerHTML = "";
                powerLayer.innerHTML = "";
                gateLayer.innerHTML = "";
                playerLayer.innerHTML = "";
                fxLayer.innerHTML = "";
                bits = [];
                powers = [];
                glitches = [];
            }

            function startGame() {
                overlay.classList.add("hidden");
                overlay.setAttribute("aria-hidden", "true");

                running = true;
                paused = false;
                score = 0;
                round = 1;
                timeLeft = GAME_DURATION;
                boostEnergy = MAX_BOOST;
                boosting = false;
                shieldCount = 0;
                magnetUntil = 0;
                ghostUntil = 0;
                slowUntil = 0;
                doubleUntil = 0;
                freezeUntil = 0;

                scoreEl.textContent = "0";
                roundEl.textContent = "1";
                timeEl.textContent = GAME_DURATION;
                shieldEl.textContent = "0";

                resize();
                clearAll();

                direction = {
                    x: 1,
                    y: 0
                };
                nextDirection = {
                    x: 1,
                    y: 0
                };

                createPlayer();
                generateTarget();
                createBits();

                resetSequence();

                lastTime = performance.now();
                if (animationId) cancelAnimationFrame(animationId);
                animationId = requestAnimationFrame(loop);
            }

            function gameOver(reason) {
                if (!running) return;
                running = false;
                boosting = false;
                if (animationId) {
                    cancelAnimationFrame(animationId);
                    animationId = null;
                }
                overlayTitle.textContent = "Circuit Over";
                overlayMessage.innerHTML = `
                    ${reason}
                    <br><br>
                    <strong>Final Score: ${Math.floor(score)}</strong>
                    <br>
                    <strong>Rounds Completed: ${Math.max(0, round - 1)}</strong>
                `;
                startBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Play Again`;
                exitBtn.textContent = "Exit Game";
                overlay.setAttribute("aria-hidden", "false");
                overlay.classList.remove("hidden");
            }

            async function openGame() {
                shell.classList.add("active");
                shell.setAttribute("aria-hidden", "false");
                resize();
                try {
                    if (!document.fullscreenElement) {
                        await document.documentElement.requestFullscreen();
                    }
                } catch (e) {
                    /* ignore */ }

                // Show start screen
                running = false;
                paused = false;
                overlayTitle.textContent = "CPE Bit Builder";
                overlayMessage.innerHTML = `
                    Collect four binary bits in the correct order to match the target logic gate.
                    <br><br>
                    Move with <strong>WASD</strong> or <strong>Arrow Keys</strong>.
                    Hold <strong>SPACE</strong> or <strong>SHIFT</strong> to boost.
                    <br><br>
                    <small>The arena wraps around at every edge. Power-ups can change the rules.</small>
                `;
                startBtn.innerHTML = `<i class="bi bi-play-fill"></i> Start Game`;
                exitBtn.textContent = "Exit";
                generateTarget();
                overlay.classList.remove("hidden");
            }

            /* =========================================================
               LOOP
               ========================================================= */
            function loop(ts) {
                if (!running) return;
                animationId = requestAnimationFrame(loop);

                let dt = (ts - lastTime) / 1000;
                lastTime = ts;
                dt = Math.min(dt, .05);

                if (paused) return;

                updateBoost(dt);

                const timerMult = slowUntil > ts ? .5 : 1;
                timeLeft -= dt * timerMult;
                if (timeLeft <= 0) {
                    timeLeft = 0;
                    timeEl.textContent = "0";
                    gameOver("Time has expired.");
                    return;
                }
                timeEl.textContent = Math.ceil(timeLeft);

                movePlayer(dt);
                updateMagnet(dt);

                checkBitCollection();
                checkPowerCollection();
                checkGlitchCollision();

                renderPlayer();
                renderBits();
                renderPowers();
                updatePowerTimers();
            }

            /* =========================================================
               EVENT LISTENERS
               ========================================================= */
            launchBtn.addEventListener("click", openGame);

            startBtn.addEventListener("click", () => {
                if (paused) {
                    paused = false;
                    overlay.classList.add("hidden");
                    lastTime = performance.now();
                    return;
                }
                startGame();
            });

            exitBtn.addEventListener("click", async () => {
                running = false;
                paused = false;
                boosting = false;
                if (animationId) {
                    cancelAnimationFrame(animationId);
                    animationId = null;
                }
                shell.classList.remove("active");
                shell.setAttribute("aria-hidden", "true");
                try {
                    if (document.fullscreenElement) await document.exitFullscreen();
                } catch (e) {
                    /* ignore */ }
            });

            pauseBtn.addEventListener("click", togglePause);

            document.addEventListener("fullscreenchange", () => setTimeout(resize, 50));
            window.addEventListener("resize", resize);

            /* =========================================================
               INIT
               ========================================================= */
            updateSequenceUI();
        })();
    </script>
</body>

</html>