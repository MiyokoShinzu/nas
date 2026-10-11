<?php

/* =========================================================
   ETS-ASYNC LEARNING PORTAL
   STUDENT - BINARY SNAKE
   =========================================================

   GAME TYPE:
   ---------------------------------------------------------
   Binary collection / arcade learning game

   CORE CONCEPT:
   ---------------------------------------------------------
   The player controls a circular snake.

   The game generates a decimal target and its equivalent
   8-bit binary representation.

   Example:

       Decimal: 173
       Binary : 10101101

   The player must collect exactly 8 binary digits.

   Each collected 0 or 1 becomes a circular segment of the
   snake and is displayed inside that segment.

   If the collected binary matches the target:

       SUCCESS
           ↓
       Score increases
           ↓
       New target
           ↓
       Obstacles appear

   If the collected binary does NOT match:

       DIGITAL EXPLOSION
           ↓
       Binary fragments scatter
           ↓
       Sequence resets

   ADDITIONAL FEATURES:
   ---------------------------------------------------------
   • 120 Hz / requestAnimationFrame rendering
   • Smooth continuous movement
   • Screen-edge wrapping
   • Speed boost
   • Boost energy
   • Shield power
   • Magnet power
   • Ghost power
   • Slow Time power
   • Turbo power
   • Double Score power
   • Teleport gates
   • Dynamic obstacles
   • Full-screen gameplay
   • Responsive controls
   • Mobile controls
   • Light / dark theme compatibility

   IMPORTANT:
   ---------------------------------------------------------
   This page does not require a database connection.
   Authentication is still required.
   ========================================================= */


session_start();


/* =========================================================
   STUDENT AUTHENTICATION
   ========================================================= */

if (
    !isset($_SESSION["logged_in"]) ||
    $_SESSION["logged_in"] !== true ||
    !isset($_SESSION["user"]) ||
    ($_SESSION["user"]["access"] ?? "") !== "student"
) {
    header("Location: ../login.php");
    exit;
}


$user = $_SESSION["user"];


/* =========================================================
   STUDENT DISPLAY NAME
   ========================================================= */

$firstName = trim(
    $user["first_name"]
        ?? $user["firstname"]
        ?? $user["name"]
        ?? "Student"
);

$studentName = htmlspecialchars(
    $firstName ?: "Student",
    ENT_QUOTES,
    "UTF-8"
);


/* =========================================================
   GLOBAL PAGE COMPONENTS
   ========================================================= */

include "globals/head.php";
include "globals/sidebar.php";
include "globals/topbar.php";

?>

<style>
    /* =========================================================
   BINARY SNAKE
   FUTURISTIC SPACE / DIGITAL BACKGROUND
   ========================================================= */


    /* =========================================================
   ROOT VARIABLES
   ========================================================= */

    :root {

        --snake-blue: #0B4F8A;
        --snake-blue-light: #1687d9;
        --snake-cyan: #22d3ee;

        --snake-orange: #f59e0b;
        --snake-orange-light: #fbbf24;

        --snake-purple: #8b5cf6;
        --snake-green: #22c55e;
        --snake-red: #ef4444;

        --snake-bg: #eef4fa;

        --snake-panel: rgba(255, 255, 255, .86);

        --snake-text: #102a43;
        --snake-muted: #627d98;

        --snake-border: rgba(11, 79, 138, .14);

        --snake-shadow:
            0 25px 70px rgba(15, 23, 42, .14);
    }


    /* =========================================================
   DARK THEME
   ========================================================= */

    html[data-theme="dark"] {

        --snake-bg: #050914;

        --snake-panel: rgba(10, 17, 32, .90);

        --snake-text: #e6f1ff;

        --snake-muted: #8ca3bd;

        --snake-border: rgba(148, 163, 184, .15);

        --snake-shadow:
            0 25px 80px rgba(0, 0, 0, .45);
    }


    /* =========================================================
   PAGE
   ========================================================= */

    .binary-snake-page {

        min-height: 100vh;
        padding-bottom: 40px;
    }


    /* =========================================================
   INTRO CARD
   ========================================================= */

    .binary-intro {

        position: relative;

        overflow: hidden;

        border-radius: 24px;

        padding: 35px;

        margin-bottom: 25px;

        background:
            linear-gradient(135deg,
                rgba(11, 79, 138, .98),
                rgba(10, 58, 102, .96));

        color: white;

        box-shadow:
            0 25px 60px rgba(11, 79, 138, .22);
    }


    .binary-intro::before {

        content: "";

        position: absolute;

        width: 320px;
        height: 320px;

        right: -100px;
        top: -160px;

        border-radius: 50%;

        background:
            radial-gradient(circle,
                rgba(34, 211, 238, .32),
                transparent 68%);
    }


    .binary-intro::after {

        content: "";

        position: absolute;

        width: 240px;
        height: 240px;

        left: -100px;
        bottom: -150px;

        border-radius: 50%;

        background:
            radial-gradient(circle,
                rgba(245, 158, 11, .20),
                transparent 68%);
    }


    .binary-intro-content {

        position: relative;

        z-index: 2;
    }


    .binary-intro h1 {

        font-weight: 800;

        margin: 0 0 8px;

        letter-spacing: -.7px;
    }


    .binary-intro p {

        margin: 0;

        opacity: .82;

        max-width: 720px;
    }


    .binary-launch {

        position: relative;

        z-index: 2;

        margin-top: 25px;

        border: 0;

        border-radius: 14px;

        padding: 13px 24px;

        background: var(--snake-orange);

        color: #111827;

        font-weight: 800;

        box-shadow:
            0 10px 25px rgba(245, 158, 11, .28);

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .binary-launch:hover {

        transform: translateY(-2px);

        box-shadow:
            0 15px 30px rgba(245, 158, 11, .35);
    }


    /* =========================================================
   GAME SHELL
   ========================================================= */

    .binary-game-shell {

        position: fixed;

        inset: 0;

        z-index: 99999;

        display: none;

        overflow: hidden;

        background: #030712;
    }


    .binary-game-shell.active {

        display: block;
    }


    /* =========================================================
   GAME WORLD
   ========================================================= */

    .binary-world {

        position: absolute;

        inset: 0;

        overflow: hidden;

        background:

            radial-gradient(ellipse at 15% 20%,
                rgba(14, 165, 233, .16),
                transparent 32%),

            radial-gradient(ellipse at 85% 15%,
                rgba(139, 92, 246, .13),
                transparent 30%),

            radial-gradient(ellipse at 70% 80%,
                rgba(34, 197, 94, .08),
                transparent 32%),

            radial-gradient(ellipse at 25% 85%,
                rgba(245, 158, 11, .08),
                transparent 30%),

            linear-gradient(145deg,
                #020617 0%,
                #071426 42%,
                #030712 100%);
    }


    /* =========================================================
   SOFT SPACE CLOUDS
   ========================================================= */

    .space-cloud {

        position: absolute;

        border-radius: 50%;

        filter: blur(70px);

        pointer-events: none;

        opacity: .38;

        animation:
            cloudFloat 16s ease-in-out infinite alternate;
    }


    .cloud-one {

        width: 320px;
        height: 220px;

        left: 8%;
        top: 15%;

        background:
            rgba(14, 165, 233, .16);
    }


    .cloud-two {

        width: 360px;
        height: 280px;

        right: 5%;
        bottom: 10%;

        background:
            rgba(139, 92, 246, .13);

        animation-delay: -5s;
    }


    .cloud-three {

        width: 260px;
        height: 220px;

        left: 42%;
        bottom: -60px;

        background:
            rgba(245, 158, 11, .10);

        animation-delay: -8s;
    }


    @keyframes cloudFloat {

        from {
            transform: translate3d(-20px, -10px, 0) scale(1);
        }

        to {
            transform: translate3d(25px, 15px, 0) scale(1.12);
        }
    }


    /* =========================================================
   FLOATING PARTICLES
   ========================================================= */

    .space-particle {

        position: absolute;

        width: 3px;
        height: 3px;

        border-radius: 50%;

        background: rgba(255, 255, 255, .72);

        box-shadow:
            0 0 10px rgba(255, 255, 255, .55);

        animation:
            particleFloat 8s ease-in-out infinite alternate;
    }


    @keyframes particleFloat {

        from {

            transform:
                translate3d(0, 0, 0);

            opacity: .25;
        }

        to {

            transform:
                translate3d(20px, -35px, 0);

            opacity: .9;
        }
    }


    /* =========================================================
   GAME ARENA
   ========================================================= */

    .binary-arena {

        position: absolute;

        inset: 0;

        overflow: hidden;
    }


    /* =========================================================
   VIGNETTE
   ========================================================= */

    .binary-arena::after {

        content: "";

        position: absolute;

        inset: 0;

        pointer-events: none;

        background:
            radial-gradient(ellipse at center,
                transparent 45%,
                rgba(0, 0, 0, .34) 100%);

        z-index: 20;
    }


    /* =========================================================
   HUD
   ========================================================= */

    .game-hud {

        position: absolute;

        top: 18px;

        left: 18px;
        right: 18px;

        z-index: 100;

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        gap: 15px;

        pointer-events: none;
    }


    .hud-left,
    .hud-right {

        display: flex;

        gap: 10px;

        flex-wrap: wrap;
    }


    .hud-card {

        min-width: 110px;

        padding: 10px 14px;

        border-radius: 15px;

        background:
            rgba(5, 15, 30, .76);

        border:
            1px solid rgba(148, 163, 184, .16);

        backdrop-filter:
            blur(14px);

        box-shadow:
            0 10px 30px rgba(0, 0, 0, .18);

        color: white;
    }


    .hud-label {

        display: block;

        font-size: 10px;

        text-transform: uppercase;

        letter-spacing: 1.1px;

        color: #94a3b8;

        font-weight: 700;
    }


    .hud-value {

        display: block;

        margin-top: 2px;

        font-size: 18px;

        font-weight: 800;
    }


    /* =========================================================
   TARGET CARD
   ========================================================= */

    .target-card {

        position: absolute;

        left: 50%;

        top: 20px;

        transform: translateX(-50%);

        z-index: 105;

        min-width: 230px;

        padding: 11px 18px;

        text-align: center;

        border-radius: 17px;

        background:
            rgba(3, 12, 26, .82);

        border:
            1px solid rgba(34, 211, 238, .24);

        backdrop-filter:
            blur(15px);

        color: white;

        pointer-events: none;
    }


    .target-label {

        font-size: 9px;

        text-transform: uppercase;

        letter-spacing: 1.5px;

        color: #67e8f9;

        font-weight: 800;
    }


    .target-decimal {

        font-size: 23px;

        line-height: 1.1;

        font-weight: 900;
    }


    .target-binary {

        margin-top: 2px;

        font-family:
            "Courier New",
            monospace;

        font-size: 15px;

        letter-spacing: 4px;

        color: #fbbf24;

        font-weight: 800;
    }


    /* =========================================================
   BINARY COLLECTION DISPLAY
   ========================================================= */

    .sequence-display {

        position: absolute;

        left: 50%;

        bottom: 18px;

        transform: translateX(-50%);

        z-index: 110;

        padding: 10px 17px;

        border-radius: 16px;

        background:
            rgba(3, 12, 26, .82);

        border:
            1px solid rgba(148, 163, 184, .16);

        backdrop-filter:
            blur(15px);

        color: white;

        text-align: center;

        pointer-events: none;
    }


    .sequence-label {

        display: block;

        font-size: 9px;

        text-transform: uppercase;

        letter-spacing: 1.4px;

        color: #94a3b8;
    }


    .sequence-value {

        font-family:
            "Courier New",
            monospace;

        font-size: 20px;

        letter-spacing: 5px;

        font-weight: 900;

        min-width: 150px;

        display: inline-block;
    }


    .sequence-decimal {

        display: block;

        font-size: 11px;

        color: #67e8f9;

        margin-top: 2px;
    }


    /* =========================================================
   BOOST BAR
   ========================================================= */

    .boost-container {

        position: absolute;

        bottom: 20px;

        right: 20px;

        z-index: 115;

        width: 190px;

        padding: 10px 12px;

        border-radius: 15px;

        background:
            rgba(3, 12, 26, .80);

        border:
            1px solid rgba(148, 163, 184, .15);

        backdrop-filter:
            blur(15px);

        color: white;
    }


    .boost-top {

        display: flex;

        justify-content: space-between;

        font-size: 10px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 1px;

        margin-bottom: 6px;
    }


    .boost-track {

        height: 7px;

        border-radius: 99px;

        overflow: hidden;

        background:
            rgba(148, 163, 184, .15);
    }


    .boost-fill {

        height: 100%;

        width: 100%;

        border-radius: inherit;

        background:
            linear-gradient(90deg,
                #22d3ee,
                #3b82f6);

        transition:
            width .08s linear;
    }


    /* =========================================================
   GAME OBJECT LAYERS
   ========================================================= */

    #bitLayer,
    #powerLayer,
    #obstacleLayer,
    #gateLayer,
    #snakeLayer,
    #fxLayer {

        position: absolute;

        inset: 0;

        pointer-events: none;
    }


    /* =========================================================
   BINARY BIT
   ========================================================= */

    .binary-bit {

        position: absolute;

        width: 36px;

        height: 36px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        font-family:
            "Courier New",
            monospace;

        font-size: 18px;

        font-weight: 900;

        color: white;

        background:
            radial-gradient(circle at 35% 30%,
                rgba(255, 255, 255, .28),
                rgba(11, 79, 138, .85));

        border:
            2px solid rgba(103, 232, 249, .65);

        box-shadow:
            0 0 16px rgba(34, 211, 238, .25);

        transform:
            translate3d(-50%, -50%, 0);

        animation:
            bitPulse 1.5s ease-in-out infinite;
    }


    .binary-bit.bit-zero {

        border-color:
            rgba(34, 211, 238, .72);

        box-shadow:
            0 0 18px rgba(34, 211, 238, .28);
    }


    .binary-bit.bit-one {

        border-color:
            rgba(245, 158, 11, .78);

        box-shadow:
            0 0 18px rgba(245, 158, 11, .30);
    }


    @keyframes bitPulse {

        0%,
        100% {

            transform:
                translate3d(-50%, -50%, 0) scale(1);
        }

        50% {

            transform:
                translate3d(-50%, -50%, 0) scale(1.12);
        }
    }


    /* =========================================================
   SNAKE CIRCLES
   ========================================================= */

    .snake-segment {

        position: absolute;

        width: 48px;

        height: 48px;

        transform:
            translate3d(-50%, -50%, 0);

        will-change:
            transform;

        display: flex;

        align-items: center;

        justify-content: center;
    }


    .snake-svg {

        position: absolute;

        inset: 0;

        width: 100%;

        height: 100%;

        overflow: visible;
    }


    .snake-bit-text {

        position: relative;

        z-index: 3;

        font-family:
            "Courier New",
            monospace;

        font-size: 18px;

        font-weight: 900;

        color: white;

        text-shadow:
            0 2px 5px rgba(0, 0, 0, .5);

        user-select: none;
    }


    .snake-head {

        width: 55px;

        height: 55px;
    }


    .snake-head .snake-bit-text {

        font-size: 20px;
    }


    /* =========================================================
   OBSTACLE
   ========================================================= */

    .binary-obstacle {

        position: absolute;

        width: 80px;

        height: 28px;

        transform:
            translate3d(-50%, -50%, 0) rotate(var(--rotation));

        border-radius: 10px;

        background:
            linear-gradient(90deg,
                #111827,
                #334155,
                #111827);

        border:
            1px solid rgba(248, 113, 113, .55);

        box-shadow:
            0 0 22px rgba(239, 68, 68, .14);

        animation:
            obstacleAppear .35s ease-out;
    }


    .binary-obstacle::before {

        content:
            "01 01 01";

        position: absolute;

        inset: 0;

        display: flex;

        align-items: center;

        justify-content: center;

        font-family:
            "Courier New",
            monospace;

        font-size: 10px;

        letter-spacing: 2px;

        color: rgba(248, 113, 113, .72);
    }


    @keyframes obstacleAppear {

        from {

            opacity: 0;

            transform:
                translate3d(-50%, -50%, 0) rotate(var(--rotation)) scale(.5);
        }

        to {

            opacity: 1;

            transform:
                translate3d(-50%, -50%, 0) rotate(var(--rotation)) scale(1);
        }
    }


    /* =========================================================
   TELEPORT GATES
   ========================================================= */

    .teleport-gate {

        position: absolute;

        width: 68px;

        height: 68px;

        border-radius: 50%;

        transform:
            translate3d(-50%, -50%, 0);

        border:
            3px solid rgba(139, 92, 246, .75);

        box-shadow:
            0 0 20px rgba(139, 92, 246, .35),
            inset 0 0 18px rgba(139, 92, 246, .22);

        animation:
            gatePulse 1.4s ease-in-out infinite;
    }


    .teleport-gate::before {

        content: "";

        position: absolute;

        inset: 10px;

        border-radius: 50%;

        border:
            2px dashed rgba(34, 211, 238, .72);

        animation:
            gateRotate 3s linear infinite;
    }


    .teleport-label {

        position: absolute;

        left: 50%;

        top: 50%;

        transform:
            translate(-50%, -50%);

        font-size: 10px;

        font-weight: 900;

        color: white;
    }


    @keyframes gatePulse {

        0%,
        100% {

            transform:
                translate3d(-50%, -50%, 0) scale(.95);
        }

        50% {

            transform:
                translate3d(-50%, -50%, 0) scale(1.08);
        }
    }


    @keyframes gateRotate {

        to {
            transform: rotate(360deg);
        }
    }


    /* =========================================================
   POWER-UP
   ========================================================= */

    .power-up {

        position: absolute;

        width: 48px;

        height: 48px;

        transform:
            translate3d(-50%, -50%, 0);

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        background:
            rgba(3, 12, 26, .88);

        border:
            2px solid currentColor;

        color: #22d3ee;

        box-shadow:
            0 0 25px currentColor;

        animation:
            powerFloat 1.3s ease-in-out infinite;
    }


    .power-up i {

        font-size: 21px;
    }


    .power-up.shield {

        color: #60a5fa;
    }


    .power-up.magnet {

        color: #f472b6;
    }


    .power-up.ghost {

        color: #c084fc;
    }


    .power-up.slow {

        color: #22d3ee;
    }


    .power-up.turbo {

        color: #fbbf24;
    }


    .power-up.double {

        color: #4ade80;
    }


    @keyframes powerFloat {

        0%,
        100% {

            transform:
                translate3d(-50%, -50%, 0) translateY(0) scale(1);
        }

        50% {

            transform:
                translate3d(-50%, -50%, 0) translateY(-8px) scale(1.08);
        }
    }


    /* =========================================================
   POWER STATUS
   ========================================================= */

    .power-status {

        position: absolute;

        top: 105px;

        right: 18px;

        z-index: 105;

        display: flex;

        flex-direction: column;

        align-items: flex-end;

        gap: 6px;

        pointer-events: none;
    }


    .power-status-item {

        display: none;

        padding: 6px 10px;

        border-radius: 10px;

        background:
            rgba(3, 12, 26, .78);

        border:
            1px solid rgba(148, 163, 184, .15);

        color: white;

        font-size: 10px;

        font-weight: 800;

        backdrop-filter:
            blur(10px);
    }


    .power-status-item.active {

        display: block;
    }


    /* =========================================================
   GAME OVER / START / PAUSE OVERLAY
   ========================================================= */

    .game-overlay {

        position: absolute;

        inset: 0;

        z-index: 500;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 25px;

        background:
            radial-gradient(circle at center,
                rgba(11, 79, 138, .18),
                rgba(2, 6, 23, .84));

        backdrop-filter:
            blur(8px);

        opacity: 1;

        visibility: visible;

        pointer-events: auto;

        transition:
            opacity .25s ease,
            visibility .25s ease;
    }


    .game-overlay.hidden {

        opacity: 0;

        visibility: hidden;

        pointer-events: none;
    }


    .game-panel {

        width: min(550px, 94vw);

        padding: 38px;

        text-align: center;

        border-radius: 28px;

        background:
            rgba(7, 18, 35, .92);

        border:
            1px solid rgba(148, 163, 184, .16);

        box-shadow:
            0 35px 100px rgba(0, 0, 0, .55);

        color: white;

        backdrop-filter:
            blur(22px);
    }


    .game-logo {

        width: 78px;

        height: 78px;

        margin:
            0 auto 20px;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        background:
            radial-gradient(circle at 35% 30%,
                #38bdf8,
                #0B4F8A 60%,
                #020617);

        border:
            2px solid rgba(103, 232, 249, .65);

        box-shadow:
            0 0 40px rgba(34, 211, 238, .24);
    }


    .game-logo span {

        font-family:
            "Courier New",
            monospace;

        font-size: 25px;

        font-weight: 900;

        color: white;
    }


    .game-panel h2 {

        margin-bottom: 8px;

        font-weight: 900;
    }


    .game-panel p {

        color: #94a3b8;

        line-height: 1.65;

        margin-bottom: 25px;
    }


    .game-target-preview {

        margin: 20px auto;

        padding: 15px;

        border-radius: 16px;

        background:
            rgba(255, 255, 255, .04);

        border:
            1px solid rgba(148, 163, 184, .10);
    }


    .preview-label {

        display: block;

        font-size: 9px;

        letter-spacing: 1.4px;

        color: #64748b;

        text-transform: uppercase;
    }


    .preview-binary {

        font-family:
            "Courier New",
            monospace;

        font-size: 25px;

        letter-spacing: 5px;

        font-weight: 900;

        color: #fbbf24;
    }


    .game-primary-button {

        width: 100%;

        border: 0;

        border-radius: 14px;

        padding: 14px;

        background:
            linear-gradient(135deg,
                #f59e0b,
                #fbbf24);

        color: #111827;

        font-weight: 900;

        box-shadow:
            0 12px 30px rgba(245, 158, 11, .20);

        transition:
            transform .2s ease;
    }


    .game-primary-button:hover {

        transform:
            translateY(-2px);
    }


    .game-secondary-button {

        margin-top: 10px;

        width: 100%;

        border:
            1px solid rgba(148, 163, 184, .18);

        border-radius: 14px;

        padding: 12px;

        background:
            rgba(255, 255, 255, .05);

        color: white;

        font-weight: 800;
    }


    /* =========================================================
   CONTROLS
   ========================================================= */

    .game-controls {

        position: absolute;

        left: 20px;

        bottom: 20px;

        z-index: 120;

        display: flex;

        gap: 8px;

        pointer-events: auto;
    }


    .control-button {

        width: 48px;

        height: 48px;

        border-radius: 14px;

        border:
            1px solid rgba(148, 163, 184, .16);

        background:
            rgba(3, 12, 26, .78);

        color: white;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 18px;

        backdrop-filter:
            blur(12px);

        user-select: none;

        touch-action: none;
    }


    .control-button:active {

        transform:
            scale(.94);
    }


    .boost-button {

        width: auto;

        padding:
            0 16px;

        font-size: 11px;

        font-weight: 900;

        letter-spacing: 1px;

        color: #fbbf24;

        border-color:
            rgba(245, 158, 11, .28);
    }


    /* =========================================================
   PAUSE BUTTON
   ========================================================= */

    .pause-button {

        position: absolute;

        top: 20px;

        right: 20px;

        z-index: 150;

        width: 44px;

        height: 44px;

        border-radius: 13px;

        border:
            1px solid rgba(148, 163, 184, .16);

        background:
            rgba(3, 12, 26, .76);

        color: white;

        display: flex;

        align-items: center;

        justify-content: center;

        pointer-events: auto;
    }


    /* =========================================================
   EXPLOSION
   ========================================================= */

    .explosion-ring {

        position: absolute;

        width: 20px;

        height: 20px;

        border-radius: 50%;

        transform:
            translate3d(-50%, -50%, 0);

        border:
            3px solid #f59e0b;

        box-shadow:
            0 0 30px rgba(245, 158, 11, .85);

        animation:
            explosionRing .65s cubic-bezier(.1, .7, .2, 1) forwards;
    }


    @keyframes explosionRing {

        from {

            opacity: 1;

            width: 20px;

            height: 20px;
        }

        to {

            opacity: 0;

            width: 230px;

            height: 230px;
        }
    }


    .explosion-particle {

        position: absolute;

        transform:
            translate3d(-50%, -50%, 0);

        font-family:
            "Courier New",
            monospace;

        font-size: 16px;

        font-weight: 900;

        color: #fbbf24;

        text-shadow:
            0 0 12px #f59e0b;

        animation:
            explosionParticle .85s cubic-bezier(.1, .7, .2, 1) forwards;
    }


    @keyframes explosionParticle {

        from {

            opacity: 1;

            transform:
                translate3d(-50%, -50%, 0) translate3d(0, 0, 0) scale(1);
        }

        to {

            opacity: 0;

            transform:
                translate3d(-50%, -50%, 0) translate3d(var(--dx),
                    var(--dy),
                    0) rotate(var(--rotation)) scale(.4);
        }
    }


    .screen-shake {

        animation:
            screenShake .38s ease-out;
    }


    @keyframes screenShake {

        0%,
        100% {
            transform: translate3d(0, 0, 0);
        }

        20% {
            transform: translate3d(-8px, 5px, 0);
        }

        40% {
            transform: translate3d(7px, -5px, 0);
        }

        60% {
            transform: translate3d(-5px, -3px, 0);
        }

        80% {
            transform: translate3d(4px, 3px, 0);
        }
    }


    /* =========================================================
   RESPONSIVE
   ========================================================= */

    @media (max-width: 800px) {

        .hud-card {

            min-width: 85px;

            padding:
                8px 10px;
        }

        .hud-value {

            font-size: 15px;
        }

        .target-card {

            top: 80px;

            min-width: 205px;
        }

        .sequence-display {

            bottom: 90px;
        }

        .boost-container {

            bottom: 20px;

            right: 15px;

            width: 145px;
        }

        .game-controls {

            bottom: 20px;

            left: 15px;
        }

        .control-button {

            width: 42px;

            height: 42px;
        }

    }


    @media (max-width: 560px) {

        .hud-right {

            display: none;
        }

        .hud-card {

            min-width: 75px;
        }

        .game-panel {

            padding: 28px 20px;
        }

        .boost-container {

            display: none;
        }

        .sequence-display {

            max-width: 180px;

            bottom: 85px;
        }

        .game-controls {

            gap: 5px;
        }

    }


    /* =========================================================
   ACCESSIBILITY
   ========================================================= */

    button {

        -webkit-tap-highlight-color:
            transparent;
    }


    button:focus-visible {

        outline:
            2px solid #22d3ee;

        outline-offset:
            3px;
    }
</style>


<!-- =========================================================
     PAGE CONTENT
     ========================================================= -->

<main class="main-content binary-snake-page">

    <div class="content-wrapper">

        <div class="binary-intro">

            <div class="binary-intro-content">

                <div class="small text-uppercase fw-bold opacity-75 mb-2">
                    ETS-ASYNC • ACADEMIC GAME
                </div>

                <h1>
                    <i class="bi bi-binary"></i>
                    Binary Snake
                </h1>

                <p>
                    Collect the correct sequence of binary digits,
                    complete the target value, avoid obstacles,
                    and use power-ups to survive longer.
                </p>

                <button
                    type="button"
                    id="launchGameBtn"
                    class="binary-launch">
                    <i class="bi bi-controller me-2"></i>
                    Launch Binary Snake
                </button>

            </div>

        </div>

    </div>

</main>


<!-- =========================================================
     FULL-SCREEN GAME
     ========================================================= -->

<div
    id="gameShell"
    class="binary-game-shell"
    aria-hidden="true">

    <div
        id="binaryWorld"
        class="binary-world">

        <!-- BACKGROUND CLOUDS -->

        <div class="space-cloud cloud-one"></div>
        <div class="space-cloud cloud-two"></div>
        <div class="space-cloud cloud-three"></div>


        <!-- FLOATING PARTICLES -->

        <?php for ($i = 0; $i < 35; $i++): ?>

            <div
                class="space-particle"
                style="
                    left: <?= rand(2, 98) ?>%;
                    top: <?= rand(2, 98) ?>%;
                    animation-delay: -<?= rand(0, 700) / 100 ?>s;
                    transform: scale(<?= rand(50, 140) / 100 ?>);
                "></div>

        <?php endfor; ?>


        <!-- GAME ARENA -->

        <div
            id="gameArena"
            class="binary-arena">

            <!-- OBJECT LAYERS -->

            <div id="gateLayer"></div>

            <div id="bitLayer"></div>

            <div id="powerLayer"></div>

            <div id="obstacleLayer"></div>

            <div id="snakeLayer"></div>

            <div id="fxLayer"></div>


            <!-- =================================================
                 HUD
                 ================================================= -->

            <div class="game-hud">

                <div class="hud-left">

                    <div class="hud-card">

                        <span class="hud-label">
                            Score
                        </span>

                        <span
                            id="scoreValue"
                            class="hud-value">
                            0
                        </span>

                    </div>


                    <div class="hud-card">

                        <span class="hud-label">
                            Round
                        </span>

                        <span
                            id="roundValue"
                            class="hud-value">
                            1
                        </span>

                    </div>


                    <div class="hud-card">

                        <span class="hud-label">
                            Time
                        </span>

                        <span
                            id="timeValue"
                            class="hud-value">
                            90
                        </span>

                    </div>

                </div>


                <div class="hud-right">

                    <div class="hud-card">

                        <span class="hud-label">
                            Bits
                        </span>

                        <span
                            id="bitsValue"
                            class="hud-value">
                            0 / 8
                        </span>

                    </div>


                    <div class="hud-card">

                        <span class="hud-label">
                            Shield
                        </span>

                        <span
                            id="shieldValue"
                            class="hud-value">
                            0
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 TARGET
                 ================================================= -->

            <div class="target-card">

                <div class="target-label">
                    Target Decimal
                </div>

                <div
                    id="targetDecimal"
                    class="target-decimal">
                    0
                </div>

                <div
                    id="targetBinary"
                    class="target-binary">
                    00000000
                </div>

            </div>


            <!-- =================================================
                 POWER STATUS
                 ================================================= -->

            <div class="power-status">

                <div
                    id="magnetStatus"
                    class="power-status-item">
                    <i class="bi bi-magnet-fill me-1"></i>
                    Magnet
                    <span id="magnetTimer"></span>
                </div>


                <div
                    id="ghostStatus"
                    class="power-status-item">
                    <i class="bi bi-ghost-fill me-1"></i>
                    Ghost
                    <span id="ghostTimer"></span>
                </div>


                <div
                    id="slowStatus"
                    class="power-status-item">
                    <i class="bi bi-hourglass-split me-1"></i>
                    Slow Time
                    <span id="slowTimer"></span>
                </div>


                <div
                    id="turboStatus"
                    class="power-status-item">
                    <i class="bi bi-lightning-fill me-1"></i>
                    Turbo
                    <span id="turboTimer"></span>
                </div>


                <div
                    id="doubleStatus"
                    class="power-status-item">
                    <i class="bi bi-2-circle-fill me-1"></i>
                    x2 Score
                    <span id="doubleTimer"></span>
                </div>

            </div>


            <!-- =================================================
                 SEQUENCE
                 ================================================= -->

            <div class="sequence-display">

                <span class="sequence-label">
                    Collected Binary
                </span>

                <span
                    id="sequenceValue"
                    class="sequence-value">
                    --------
                </span>

                <span
                    id="sequenceDecimal"
                    class="sequence-decimal">
                    Decimal: —
                </span>

            </div>


            <!-- =================================================
                 BOOST
                 ================================================= -->

            <div class="boost-container">

                <div class="boost-top">

                    <span>
                        <i class="bi bi-lightning-fill"></i>
                        Boost
                    </span>

                    <span id="boostValue">
                        100%
                    </span>

                </div>

                <div class="boost-track">

                    <div
                        id="boostFill"
                        class="boost-fill"></div>

                </div>

            </div>


            <!-- =================================================
                 PAUSE
                 ================================================= -->

            <button
                id="pauseButton"
                class="pause-button"
                type="button"
                aria-label="Pause game">
                <i class="bi bi-pause-fill"></i>
            </button>


            <!-- =================================================
                 CONTROLS
                 ================================================= -->

            <div class="game-controls">

                <button
                    id="upButton"
                    class="control-button"
                    type="button"
                    aria-label="Move up">
                    <i class="bi bi-chevron-up"></i>
                </button>

                <button
                    id="downButton"
                    class="control-button"
                    type="button"
                    aria-label="Move down">
                    <i class="bi bi-chevron-down"></i>
                </button>

                <button
                    id="leftButton"
                    class="control-button"
                    type="button"
                    aria-label="Move left">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <button
                    id="rightButton"
                    class="control-button"
                    type="button"
                    aria-label="Move right">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <button
                    id="boostButton"
                    class="control-button boost-button"
                    type="button">
                    <i class="bi bi-lightning-fill me-1"></i>
                    BOOST
                </button>

            </div>


            <!-- =================================================
                 START / GAME OVER / PAUSE OVERLAY
                 ================================================= -->

            <div
                id="gameOverlay"
                class="game-overlay">

                <div class="game-panel">

                    <div class="game-logo">

                        <span>
                            01
                        </span>

                    </div>


                    <h2 id="overlayTitle">
                        Binary Snake
                    </h2>


                    <p id="overlayMessage">

                        Collect eight binary digits in the
                        correct order to create the target
                        binary number.

                        <br><br>

                        Move with
                        <strong>WASD</strong>
                        or
                        <strong>Arrow Keys</strong>.

                        Hold
                        <strong>SPACE</strong>
                        or
                        <strong>SHIFT</strong>
                        to boost.

                    </p>


                    <div class="game-target-preview">

                        <span class="preview-label">
                            Example Target
                        </span>

                        <div
                            id="overlayTarget"
                            class="preview-binary">
                            10101101
                        </div>

                    </div>


                    <button
                        id="startGameButton"
                        class="game-primary-button"
                        type="button">
                        <i class="bi bi-play-fill me-1"></i>
                        Start Game
                    </button>


                    <button
                        id="exitGameButton"
                        class="game-secondary-button"
                        type="button">
                        Exit

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<?php

include "globals/scripts.php";

?>


<script>
    /* =========================================================
   BINARY SNAKE
   JAVASCRIPT GAME ENGINE
   ========================================================= */


    /* =========================================================
       DOM REFERENCES
       ========================================================= */

    const gameShell =
        document.getElementById("gameShell");

    const gameArena =
        document.getElementById("gameArena");

    const binaryWorld =
        document.getElementById("binaryWorld");

    const gameOverlay =
        document.getElementById("gameOverlay");

    const overlayTitle =
        document.getElementById("overlayTitle");

    const overlayMessage =
        document.getElementById("overlayMessage");

    const overlayTarget =
        document.getElementById("overlayTarget");

    const startGameButton =
        document.getElementById("startGameButton");

    const exitGameButton =
        document.getElementById("exitGameButton");

    const launchGameBtn =
        document.getElementById("launchGameBtn");

    const pauseButton =
        document.getElementById("pauseButton");

    const bitLayer =
        document.getElementById("bitLayer");

    const powerLayer =
        document.getElementById("powerLayer");

    const obstacleLayer =
        document.getElementById("obstacleLayer");

    const gateLayer =
        document.getElementById("gateLayer");

    const snakeLayer =
        document.getElementById("snakeLayer");

    const fxLayer =
        document.getElementById("fxLayer");


    /* =========================================================
       HUD
       ========================================================= */

    const scoreValue =
        document.getElementById("scoreValue");

    const roundValue =
        document.getElementById("roundValue");

    const timeValue =
        document.getElementById("timeValue");

    const bitsValue =
        document.getElementById("bitsValue");

    const shieldValue =
        document.getElementById("shieldValue");

    const targetDecimal =
        document.getElementById("targetDecimal");

    const targetBinary =
        document.getElementById("targetBinary");

    const sequenceValue =
        document.getElementById("sequenceValue");

    const sequenceDecimal =
        document.getElementById("sequenceDecimal");

    const boostFill =
        document.getElementById("boostFill");

    const boostValue =
        document.getElementById("boostValue");


    /* =========================================================
       POWER STATUS
       ========================================================= */

    const magnetStatus =
        document.getElementById("magnetStatus");

    const ghostStatus =
        document.getElementById("ghostStatus");

    const slowStatus =
        document.getElementById("slowStatus");

    const turboStatus =
        document.getElementById("turboStatus");

    const doubleStatus =
        document.getElementById("doubleStatus");

    const magnetTimer =
        document.getElementById("magnetTimer");

    const ghostTimer =
        document.getElementById("ghostTimer");

    const slowTimer =
        document.getElementById("slowTimer");

    const turboTimer =
        document.getElementById("turboTimer");

    const doubleTimer =
        document.getElementById("doubleTimer");


    /* =========================================================
       GAME CONSTANTS
       ========================================================= */

    const TARGET_BITS = 8;

    const GAME_DURATION = 90;

    /* =========================================================
   SNAKE SPEED
   ========================================================= */

    const BASE_SPEED = 260;

    const BOOST_SPEED = 500;

    const TURBO_SPEED = 700;

    const MAX_BOOST = 100;

    const BOOST_DRAIN = 38;

    const BOOST_RECHARGE = 21;

    const SEGMENT_SPACING = .72;

    const MAX_BITS = 32;

    const MAX_POWERUPS = 5;

    const INITIAL_OBSTACLES = 0;


    /* =========================================================
       GAME STATE
       ========================================================= */

    let gameRunning = false;

    let gamePaused = false;

    let score = 0;

    let round = 1;

    let timeRemaining = GAME_DURATION;

    let targetDecimalNumber = 0;

    let targetBinaryString = "";

    let collectedBits = "";

    let boostEnergy = MAX_BOOST;

    let boosting = false;

    let lastFrameTime = 0;

    let animationFrameId = null;

    let gameWidth = 1;

    let gameHeight = 1;

    let worldScale = 1;

    let lastDirectionChange = 0;


    /* =========================================================
       DIRECTION
       ========================================================= */

    let direction = {
        x: 1,
        y: 0
    };

    let nextDirection = {
        x: 1,
        y: 0
    };


    /* =========================================================
       SNAKE
       ========================================================= */

    let snake = [];

    let snakeElements = [];


    /* =========================================================
       COLLECTIBLES
       ========================================================= */

    let bits = [];

    let powers = [];

    let obstacles = [];

    let gates = [];


    /* =========================================================
       POWER STATE
       ========================================================= */

    let shieldCount = 0;

    let magnetUntil = 0;

    let ghostUntil = 0;

    let slowUntil = 0;

    let turboUntil = 0;

    let doubleUntil = 0;


    /* =========================================================
       UNIQUE IDS
       ========================================================= */

    let objectId = 0;


    /* =========================================================
       PERFORMANCE
       ========================================================= */

    let previousTime = performance.now();

    let accumulator = 0;


    /* =========================================================
       RANDOM HELPERS
       ========================================================= */

    function randomFloat(min, max) {

        return Math.random() *
            (max - min) +
            min;
    }


    function randomInt(min, max) {

        return Math.floor(
            Math.random() *
            (max - min + 1)
        ) + min;
    }


    function clamp(value, min, max) {

        return Math.max(
            min,
            Math.min(max, value)
        );
    }


    /* =========================================================
       DISTANCE
       ========================================================= */

    function distance(a, b) {

        const dx =
            a.x - b.x;

        const dy =
            a.y - b.y;

        return Math.sqrt(
            dx * dx +
            dy * dy
        );
    }


    /* =========================================================
       WRAP COORDINATES
       ========================================================= */

    function wrapPosition(position) {

        if (position.x < 0) {

            position.x += gameWidth;
        }

        if (position.x >= gameWidth) {

            position.x -= gameWidth;
        }

        if (position.y < 0) {

            position.y += gameHeight;
        }

        if (position.y >= gameHeight) {

            position.y -= gameHeight;
        }
    }


    /* =========================================================
       RANDOM TARGET
       ========================================================= */

    function generateTarget() {

        targetDecimalNumber =
            randomInt(1, 255);

        targetBinaryString =
            targetDecimalNumber
            .toString(2)
            .padStart(8, "0");

        targetDecimal.textContent =
            targetDecimalNumber;

        targetBinary.textContent =
            targetBinaryString;

        overlayTarget.textContent =
            targetBinaryString;
    }


    /* =========================================================
       RESET SEQUENCE
       ========================================================= */

    function resetSequence() {

        collectedBits = "";

        updateSequenceUI();
    }


    /* =========================================================
       SEQUENCE UI
       ========================================================= */

    function updateSequenceUI() {

        let display =
            collectedBits;

        while (display.length < TARGET_BITS) {

            display += "-";
        }

        sequenceValue.textContent =
            display;

        bitsValue.textContent =
            collectedBits.length +
            " / " +
            TARGET_BITS;

        if (collectedBits.length > 0) {

            sequenceDecimal.textContent =
                "Decimal: " +
                parseInt(
                    collectedBits,
                    2
                );

        } else {

            sequenceDecimal.textContent =
                "Decimal: —";
        }
    }


    /* =========================================================
       SCORE
       ========================================================= */

    function addScore(points) {

        const multiplier =
            doubleUntil > performance.now() ?
            2 :
            1;

        score +=
            points * multiplier;

        scoreValue.textContent =
            Math.floor(score);
    }


    /* =========================================================
       RESIZE
       ========================================================= */

    function resizeGame() {

        const rect =
            gameArena.getBoundingClientRect();

        gameWidth =
            rect.width;

        gameHeight =
            rect.height;

        worldScale =
            Math.min(
                gameWidth,
                gameHeight
            ) / 900;
    }


    /* =========================================================
       INITIAL SNAKE
       ========================================================= */

    function createSnake() {

        snake = [];

        snakeElements = [];

        const startX =
            gameWidth / 2;

        const startY =
            gameHeight / 2;

        for (
            let i = 0; i < 4; i++
        ) {

            snake.push({

                x: startX -
                    i * 38,

                y: startY,

                previousX: startX -
                    i * 38,

                previousY: startY,

                bit: null

            });
        }


        snake.forEach(
            (segment, index) => {

                const element =
                    createSnakeElement(
                        segment,
                        index === 0
                    );

                snakeElements.push(
                    element
                );

                snakeLayer.appendChild(
                    element
                );
            }
        );
    }


    /* =========================================================
       CREATE SVG SNAKE CIRCLE
       ========================================================= */

    function createSnakeElement(
        segment,
        isHead
    ) {

        const element =
            document.createElement("div");

        element.className =
            "snake-segment" +
            (isHead ?
                " snake-head" :
                "");

        element.innerHTML = `

        <svg
            class="snake-svg"
            viewBox="0 0 100 100"
            aria-hidden="true"
        >

            <defs>

                <radialGradient
                    id="snakeGradient${objectId}"
                    cx="35%"
                    cy="30%"
                >

                    <stop
                        offset="0%"
                        stop-color="#60a5fa"
                    />

                    <stop
                        offset="55%"
                        stop-color="#0B4F8A"
                    />

                    <stop
                        offset="100%"
                        stop-color="#020617"
                    />

                </radialGradient>

            </defs>

            <circle
                cx="50"
                cy="50"
                r="44"
                fill="url(#snakeGradient${objectId})"
                stroke="#22d3ee"
                stroke-width="3"
            />

            <circle
                cx="37"
                cy="32"
                r="9"
                fill="rgba(255,255,255,.13)"
            />

            ${
                isHead
                ? `
                    <circle
                        cx="37"
                        cy="46"
                        r="4"
                        fill="#ffffff"
                    />

                    <circle
                        cx="63"
                        cy="46"
                        r="4"
                        fill="#ffffff"
                    />

                    <circle
                        cx="37"
                        cy="46"
                        r="1.8"
                        fill="#020617"
                    />

                    <circle
                        cx="63"
                        cy="46"
                        r="1.8"
                        fill="#020617"
                    />
                `
                : ""
            }

        </svg>

        <span class="snake-bit-text"></span>
    `;

        objectId++;

        return element;
    }


    /* =========================================================
       RENDER SNAKE
       ========================================================= */

    function renderSnake() {

        snake.forEach(
            (segment, index) => {

                const element =
                    snakeElements[index];

                if (!element) {
                    return;
                }

                element.style.transform =
                    `translate3d(
                    ${segment.x}px,
                    ${segment.y}px,
                    0
                ) translate3d(-50%,-50%,0)`;

                const text =
                    element.querySelector(
                        ".snake-bit-text"
                    );

                text.textContent =
                    segment.bit ?? "";
            }
        );
    }


    /* =========================================================
       MOVE SNAKE
       ========================================================= */

    function moveSnake(dt) {

        const now =
            performance.now();

        if (
            now -
            lastDirectionChange >
            50
        ) {

            direction.x =
                nextDirection.x;

            direction.y =
                nextDirection.y;

            lastDirectionChange =
                now;
        }


        let speed =
            BASE_SPEED;


        if (
            boosting &&
            boostEnergy > 0
        ) {

            speed =
                BOOST_SPEED;
        }


        if (
            turboUntil >
            now
        ) {

            speed =
                TURBO_SPEED;
        }


        if (
            slowUntil >
            now
        ) {

            speed *= .55;
        }


        const movement =
            speed *
            dt;


        snake[0].previousX =
            snake[0].x;

        snake[0].previousY =
            snake[0].y;


        snake[0].x +=
            direction.x *
            movement;

        snake[0].y +=
            direction.y *
            movement;


        /* =====================================================
           EDGE WRAP
           ===================================================== */

        wrapPosition(
            snake[0]
        );


        /* =====================================================
           BODY FOLLOWING
           ===================================================== */

        for (
            let i = 1; i < snake.length; i++
        ) {

            const previous =
                snake[i - 1];

            const current =
                snake[i];


            current.previousX =
                current.x;

            current.previousY =
                current.y;


            let dx =
                previous.x -
                current.x;

            let dy =
                previous.y -
                current.y;


            /*
             * Handle wrapping distance.
             */

            if (
                Math.abs(dx) >
                gameWidth / 2
            ) {

                dx =
                    dx > 0 ?
                    dx - gameWidth :
                    dx + gameWidth;
            }


            if (
                Math.abs(dy) >
                gameHeight / 2
            ) {

                dy =
                    dy > 0 ?
                    dy - gameHeight :
                    dy + gameHeight;
            }


            const distanceToPrevious =
                Math.sqrt(
                    dx * dx +
                    dy * dy
                );


            const desired =
                38;


            if (
                distanceToPrevious >
                desired
            ) {

                const ratio =
                    (
                        distanceToPrevious -
                        desired
                    ) /
                    distanceToPrevious;

                current.x +=
                    dx *
                    ratio;

                current.y +=
                    dy *
                    ratio;
            }


            wrapPosition(
                current
            );
        }
    }


    /* =========================================================
       CREATE BIT
       ========================================================= */

    function createBit(value) {

        const bit = {

            id:
                ++objectId,

            value: String(value),

            x: randomFloat(
                70,
                Math.max(
                    71,
                    gameWidth - 70
                )
            ),

            y: randomFloat(
                140,
                Math.max(
                    141,
                    gameHeight - 100
                )
            )

        };


        if (
            isPositionDangerous(
                bit,
                100
            )
        ) {

            return createBit(value);
        }


        const element =
            document.createElement("div");

        element.className =
            "binary-bit " +
            (
                value === "0" ?
                "bit-zero" :
                "bit-one"
            );

        element.textContent =
            value;

        element.dataset.id =
            bit.id;


        bit.element =
            element;


        bitLayer.appendChild(
            element
        );


        bits.push(
            bit
        );


        return bit;
    }


    /* =========================================================
       CREATE INITIAL BITS
       ========================================================= */

    function createBits() {

        bits.forEach(
            bit => bit.element?.remove()
        );

        bits = [];


        /*
         * Ensure the current target digits
         * exist somewhere in the arena.
         */

        for (
            let i = 0; i < TARGET_BITS; i++
        ) {

            createBit(
                targetBinaryString[i]
            );
        }


        /*
         * Add additional random bits.
         */

        for (
            let i = TARGET_BITS; i < MAX_BITS; i++
        ) {

            createBit(
                Math.random() <
                .5 ?
                "0" :
                "1"
            );
        }


        renderBits();
    }


    /* =========================================================
       RENDER BITS
       ========================================================= */

    function renderBits() {

        bits.forEach(
            bit => {

                if (!bit.element) {
                    return;
                }

                bit.element.style.left =
                    bit.x + "px";

                bit.element.style.top =
                    bit.y + "px";
            }
        );
    }


    /* =========================================================
       SAFE POSITION CHECK
       ========================================================= */

    function isPositionDangerous(
        position,
        margin = 60
    ) {

        if (
            snake.length &&
            distance(
                position,
                snake[0]
            ) < margin
        ) {

            return true;
        }


        for (
            const obstacle of obstacles
        ) {

            if (
                distance(
                    position,
                    obstacle
                ) <
                margin + 45
            ) {

                return true;
            }
        }


        return false;
    }


    /* =========================================================
       COLLECT BITS
       ========================================================= */

    function checkBitCollection() {

        if (!snake.length) {
            return;
        }


        const head =
            snake[0];


        for (
            let i = bits.length - 1; i >= 0; i--
        ) {

            const bit =
                bits[i];


            let collectDistance =
                34;


            if (
                magnetUntil >
                performance.now()
            ) {

                collectDistance =
                    45;
            }


            if (
                distance(
                    head,
                    bit
                ) <
                collectDistance
            ) {

                collectBit(
                    bit,
                    i
                );
            }
        }
    }


    /* =========================================================
       COLLECT SINGLE BIT
       ========================================================= */

    function collectBit(
        bit,
        index
    ) {

        const value =
            bit.value;


        bit.element?.remove();

        bits.splice(
            index,
            1
        );


        /*
         * Add bit to the snake.
         */

        collectedBits +=
            value;


        const tail =
            snake[
                snake.length - 1
            ];


        const newSegment = {

            x: tail ?
                tail.x : snake[0].x,

            y: tail ?
                tail.y : snake[0].y,

            previousX: tail ?
                tail.x : snake[0].x,

            previousY: tail ?
                tail.y : snake[0].y,

            bit: value

        };


        snake.push(
            newSegment
        );


        const element =
            createSnakeElement(
                newSegment,
                false
            );


        snakeElements.push(
            element
        );


        snakeLayer.appendChild(
            element
        );


        addScore(10);


        updateSequenceUI();


        /*
         * Check target after exactly
         * eight bits have been collected.
         */

        if (
            collectedBits.length >=
            TARGET_BITS
        ) {

            validateBinary();
        }


        /*
         * Maintain the field of bits.
         */

        while (
            bits.length <
            MAX_BITS
        ) {

            createBit(
                Math.random() < .5 ?
                "0" :
                "1"
            );
        }
    }


    /* =========================================================
       MAGNET EFFECT
       ========================================================= */

    function updateMagnet(dt) {

        if (
            magnetUntil <=
            performance.now()
        ) {

            return;
        }


        const head =
            snake[0];


        bits.forEach(
            bit => {

                const dx =
                    head.x -
                    bit.x;

                const dy =
                    head.y -
                    bit.y;

                const dist =
                    Math.sqrt(
                        dx * dx +
                        dy * dy
                    );


                if (
                    dist > 5 &&
                    dist < 260
                ) {

                    const strength =
                        180 *
                        dt *
                        (
                            1 -
                            dist / 260
                        );


                    bit.x +=
                        (
                            dx /
                            dist
                        ) *
                        strength;

                    bit.y +=
                        (
                            dy /
                            dist
                        ) *
                        strength;
                }
            }
        );
    }


    /* =========================================================
       VALIDATE BINARY
       ========================================================= */

    function validateBinary() {

        const binaryMatch =
            collectedBits ===
            targetBinaryString;


        const decimalValue =
            parseInt(
                collectedBits,
                2
            );


        const decimalMatch =
            decimalValue ===
            targetDecimalNumber;


        if (
            binaryMatch &&
            decimalMatch
        ) {

            completeRound();

        } else {

            binaryExplosion();
        }
    }


    /* =========================================================
       COMPLETE ROUND
       ========================================================= */

    function completeRound() {

        addScore(100);

        round++;

        roundValue.textContent =
            round;


        /*
         * Create obstacles ONLY after
         * successful binary completion.
         */

        spawnObstacleWave();


        /*
         * Spawn powers after success.
         */

        spawnPowerWave();


        /*
         * Small time reward.
         */

        timeRemaining =
            Math.min(
                GAME_DURATION,
                timeRemaining + 5
            );


        /*
         * Reset snake bits.
         * Keep only the head and original
         * body circles.
         */

        trimSnake();


        resetSequence();

        generateTarget();

        createBits();


        showFloatingMessage(
            "BINARY COMPLETE!",
            "#22c55e"
        );
    }


    /* =========================================================
       TRIM SNAKE
       ========================================================= */

    function trimSnake() {

        while (
            snake.length >
            4
        ) {

            const index =
                snake.length - 1;

            snakeElements[
                index
            ]?.remove();

            snakeElements.pop();

            snake.pop();
        }


        snake.forEach(
            segment => {

                segment.bit =
                    null;
            }
        );
    }


    /* =========================================================
       BINARY EXPLOSION
       ========================================================= */

    function binaryExplosion() {

        const head =
            snake[0];


        if (!head) {
            return;
        }


        /*
         * Freeze sequence before reset.
         */

        const failedBinary =
            collectedBits;


        /*
         * Main explosion ring.
         */

        const ring =
            document.createElement(
                "div"
            );

        ring.className =
            "explosion-ring";

        ring.style.left =
            head.x + "px";

        ring.style.top =
            head.y + "px";

        fxLayer.appendChild(
            ring
        );


        /*
         * Binary fragments.
         */

        for (
            let i = 0; i < 26; i++
        ) {

            const particle =
                document.createElement(
                    "div"
                );

            particle.className =
                "explosion-particle";

            particle.textContent =
                Math.random() <
                .5 ?
                "0" :
                "1";


            particle.style.left =
                head.x + "px";

            particle.style.top =
                head.y + "px";


            const angle =
                Math.random() *
                Math.PI *
                2;

            const force =
                randomFloat(
                    70,
                    260
                );


            particle.style.setProperty(
                "--dx",
                Math.cos(angle) *
                force +
                "px"
            );

            particle.style.setProperty(
                "--dy",
                Math.sin(angle) *
                force +
                "px"
            );

            particle.style.setProperty(
                "--rotation",
                randomInt(
                    -360,
                    360
                ) + "deg"
            );


            fxLayer.appendChild(
                particle
            );


            setTimeout(
                () => {
                    particle.remove();
                },
                900
            );
        }


        /*
         * Screen shake.
         */

        binaryWorld.classList.remove(
            "screen-shake"
        );

        void binaryWorld.offsetWidth;

        binaryWorld.classList.add(
            "screen-shake"
        );


        /*
         * Display failure.
         */

        showFloatingMessage(
            "BINARY MISMATCH!",
            "#ef4444"
        );


        /*
         * Reset after the animation begins.
         */

        resetSequence();

        trimSnake();


        setTimeout(
            () => {

                ring.remove();

            },
            700
        );
    }


    /* =========================================================
       FLOATING MESSAGE
       ========================================================= */

    function showFloatingMessage(
        message,
        color
    ) {

        const element =
            document.createElement(
                "div"
            );

        element.textContent =
            message;


        element.style.position =
            "absolute";

        element.style.left =
            "50%";

        element.style.top =
            "50%";

        element.style.zIndex =
            "450";

        element.style.transform =
            "translate(-50%,-50%)";

        element.style.fontSize =
            "clamp(24px,4vw,46px)";

        element.style.fontWeight =
            "900";

        element.style.letterSpacing =
            "3px";

        element.style.color =
            color;

        element.style.textShadow =
            `0 0 25px ${color}`;

        element.style.pointerEvents =
            "none";

        element.style.animation =
            "messagePop .9s ease forwards";


        fxLayer.appendChild(
            element
        );


        setTimeout(
            () => {
                element.remove();
            },
            950
        );
    }


    /* =========================================================
       MESSAGE ANIMATION
       ========================================================= */

    const messageStyle =
        document.createElement("style");

    messageStyle.textContent = `

@keyframes messagePop {

    0% {

        opacity: 0;

        transform:
            translate(-50%,-50%)
            scale(.65);
    }

    20% {

        opacity: 1;

        transform:
            translate(-50%,-50%)
            scale(1.08);
    }

    75% {

        opacity: 1;

        transform:
            translate(-50%,-50%)
            scale(1);
    }

    100% {

        opacity: 0;

        transform:
            translate(-50%,-65%)
            scale(1.05);
    }
}

`;

    document.head.appendChild(
        messageStyle
    );


    /* =========================================================
       OBSTACLE WAVE
       ========================================================= */

    function spawnObstacleWave() {

        const amount =
            Math.min(
                4 + round * 2,
                22
            );


        for (
            let i = 0; i < amount; i++
        ) {

            spawnObstacle();
        }
    }


    /* =========================================================
       SPAWN OBSTACLE
       ========================================================= */

    function spawnObstacle() {

        const obstacle = {

            id:
                ++objectId,

            x: randomFloat(
                80,
                Math.max(
                    81,
                    gameWidth - 80
                )
            ),

            y: randomFloat(
                150,
                Math.max(
                    151,
                    gameHeight - 130
                )
            ),

            rotation: randomInt(
                -35,
                35
            ),

            width: randomInt(
                65,
                110
            )

        };


        if (
            isPositionDangerous(
                obstacle,
                120
            )
        ) {

            return;
        }


        const element =
            document.createElement(
                "div"
            );

        element.className =
            "binary-obstacle";

        element.style.left =
            obstacle.x + "px";

        element.style.top =
            obstacle.y + "px";

        element.style.width =
            obstacle.width + "px";

        element.style.setProperty(
            "--rotation",
            obstacle.rotation + "deg"
        );


        obstacle.element =
            element;


        obstacleLayer.appendChild(
            element
        );


        obstacles.push(
            obstacle
        );
    }


    /* =========================================================
       OBSTACLE COLLISION
       ========================================================= */

    function checkObstacleCollision() {

        if (
            ghostUntil >
            performance.now()
        ) {

            return;
        }


        const head =
            snake[0];


        for (
            const obstacle of obstacles
        ) {

            if (
                distance(
                    head,
                    obstacle
                ) <
                48 +
                obstacle.width /
                2
            ) {

                handleObstacleHit(
                    obstacle
                );

                return;
            }
        }
    }


    /* =========================================================
       OBSTACLE HIT
       ========================================================= */

    function handleObstacleHit(
        obstacle
    ) {

        if (
            shieldCount > 0
        ) {

            shieldCount--;

            updateShieldUI();

            obstacle.element?.remove();

            obstacles =
                obstacles.filter(
                    item =>
                    item !== obstacle
                );


            showFloatingMessage(
                "SHIELD BLOCK!",
                "#60a5fa"
            );

            return;
        }


        gameOver(
            "Your snake collided with a data barrier."
        );
    }


    /* =========================================================
       TELEPORT GATES
       ========================================================= */

    function createGates() {

        gates.forEach(
            gate =>
            gate.element?.remove()
        );

        gates = [];


        const gateA =
            createGate("A");

        const gateB =
            createGate("B");


        gates.push(
            gateA,
            gateB
        );


        renderGates();
    }


    /* =========================================================
       CREATE GATE
       ========================================================= */

    function createGate(label) {

        let gate;

        let attempts = 0;


        do {

            gate = {

                id:
                    ++objectId,

                label,

                x: randomFloat(
                    100,
                    Math.max(
                        101,
                        gameWidth - 100
                    )
                ),

                y: randomFloat(
                    160,
                    Math.max(
                        161,
                        gameHeight - 160
                    )
                )

            };

            attempts++;

        } while (
            isPositionDangerous(
                gate,
                120
            ) &&
            attempts < 50
        );


        const element =
            document.createElement(
                "div"
            );

        element.className =
            "teleport-gate";

        element.innerHTML =
            `<span class="teleport-label">${label}</span>`;


        gate.element =
            element;


        gateLayer.appendChild(
            element
        );


        return gate;
    }


    /* =========================================================
       RENDER GATES
       ========================================================= */

    function renderGates() {

        gates.forEach(
            gate => {

                gate.element.style.left =
                    gate.x + "px";

                gate.element.style.top =
                    gate.y + "px";
            }
        );
    }


    /* =========================================================
       GATE COLLISION
       ========================================================= */

    function checkGateCollision() {

        if (
            gates.length <
            2
        ) {

            return;
        }


        const head =
            snake[0];


        for (
            const gate of gates
        ) {

            if (
                distance(
                    head,
                    gate
                ) <
                45
            ) {

                const destination =
                    gates.find(
                        other =>
                        other !== gate
                    );


                if (
                    destination &&
                    distance(
                        head,
                        destination
                    ) > 80
                ) {

                    head.x =
                        destination.x;

                    head.y =
                        destination.y;


                    showFloatingMessage(
                        "TELEPORT!",
                        "#a78bfa"
                    );
                }
            }
        }
    }


    /* =========================================================
       POWER TYPES
       ========================================================= */

    const POWER_TYPES = [

        {
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
            type: "turbo",
            icon: "bi-lightning-fill"
        },

        {
            type: "double",
            icon: "bi-2-circle-fill"
        }

    ];


    /* =========================================================
       POWER WAVE
       ========================================================= */

    function spawnPowerWave() {

        spawnPower();


        if (
            Math.random() <
            .35
        ) {

            spawnPower();
        }
    }


    /* =========================================================
       SPAWN POWER
       ========================================================= */

    function spawnPower() {

        if (
            powers.length >=
            MAX_POWERUPS
        ) {

            return;
        }


        const definition =
            POWER_TYPES[
                randomInt(
                    0,
                    POWER_TYPES.length - 1
                )
            ];


        const power = {

            id:
                ++objectId,

            type: definition.type,

            x: randomFloat(
                80,
                Math.max(
                    81,
                    gameWidth - 80
                )
            ),

            y: randomFloat(
                150,
                Math.max(
                    151,
                    gameHeight - 130
                )
            )

        };


        if (
            isPositionDangerous(
                power,
                110
            )
        ) {

            return;
        }


        const element =
            document.createElement(
                "div"
            );

        element.className =
            "power-up " +
            power.type;

        element.innerHTML =
            `<i class="bi ${definition.icon}"></i>`;


        power.element =
            element;


        powerLayer.appendChild(
            element
        );


        powers.push(
            power
        );


        renderPowers();
    }


    /* =========================================================
       RENDER POWERS
       ========================================================= */

    function renderPowers() {

        powers.forEach(
            power => {

                power.element.style.left =
                    power.x + "px";

                power.element.style.top =
                    power.y + "px";
            }
        );
    }


    /* =========================================================
       POWER COLLISION
       ========================================================= */

    function checkPowerCollection() {

        const head =
            snake[0];


        for (
            let i = powers.length - 1; i >= 0; i--
        ) {

            const power =
                powers[i];


            if (
                distance(
                    head,
                    power
                ) <
                45
            ) {

                activatePower(
                    power
                );


                power.element?.remove();


                powers.splice(
                    i,
                    1
                );
            }
        }
    }


    /* =========================================================
       ACTIVATE POWER
       ========================================================= */

    function activatePower(
        power
    ) {

        const now =
            performance.now();


        switch (
            power.type
        ) {


            /* ================================================
               SHIELD
               ================================================ */

            case "shield":

                shieldCount =
                    Math.min(
                        shieldCount + 1,
                        2
                    );

                updateShieldUI();

                showFloatingMessage(
                    "SHIELD!",
                    "#60a5fa"
                );

                break;


                /* ================================================
                   MAGNET
                   ================================================ */

            case "magnet":

                magnetUntil =
                    now + 7000;

                showFloatingMessage(
                    "MAGNET!",
                    "#f472b6"
                );

                break;


                /* ================================================
                   GHOST
                   ================================================ */

            case "ghost":

                ghostUntil =
                    now + 7000;

                showFloatingMessage(
                    "GHOST MODE!",
                    "#c084fc"
                );

                break;


                /* ================================================
                   SLOW
                   ================================================ */

            case "slow":

                slowUntil =
                    now + 7000;

                showFloatingMessage(
                    "SLOW TIME!",
                    "#22d3ee"
                );

                break;


                /* ================================================
                   TURBO
                   ================================================ */

            case "turbo":

                turboUntil =
                    now + 6000;

                showFloatingMessage(
                    "TURBO!",
                    "#fbbf24"
                );

                break;


                /* ================================================
                   DOUBLE
                   ================================================ */

            case "double":

                doubleUntil =
                    now + 8000;

                showFloatingMessage(
                    "DOUBLE SCORE!",
                    "#4ade80"
                );

                break;

        }
    }


    /* =========================================================
       SHIELD UI
       ========================================================= */

    function updateShieldUI() {

        shieldValue.textContent =
            shieldCount;
    }


    /* =========================================================
       POWER STATUS UI
       ========================================================= */

    function updatePowerStatus() {

        const now =
            performance.now();


        updatePowerTimer(
            magnetStatus,
            magnetTimer,
            magnetUntil,
            now
        );


        updatePowerTimer(
            ghostStatus,
            ghostTimer,
            ghostUntil,
            now
        );


        updatePowerTimer(
            slowStatus,
            slowTimer,
            slowUntil,
            now
        );


        updatePowerTimer(
            turboStatus,
            turboTimer,
            turboUntil,
            now
        );


        updatePowerTimer(
            doubleStatus,
            doubleTimer,
            doubleUntil,
            now
        );
    }


    /* =========================================================
       INDIVIDUAL POWER TIMER
       ========================================================= */

    function updatePowerTimer(
        element,
        timerElement,
        until,
        now
    ) {

        const remaining =
            until -
            now;


        if (
            remaining > 0
        ) {

            element.classList.add(
                "active"
            );

            timerElement.textContent =
                " " +
                (
                    remaining /
                    1000
                ).toFixed(1) +
                "s";

        } else {

            element.classList.remove(
                "active"
            );

            timerElement.textContent =
                "";
        }
    }


    /* =========================================================
       BOOST
       ========================================================= */

    function updateBoost(dt) {

        if (
            boosting &&
            boostEnergy > 0
        ) {

            boostEnergy -=
                BOOST_DRAIN *
                dt;

            if (
                boostEnergy <= 0
            ) {

                boostEnergy = 0;

                boosting = false;
            }

        } else {

            boostEnergy +=
                BOOST_RECHARGE *
                dt;
        }


        boostEnergy =
            clamp(
                boostEnergy,
                0,
                MAX_BOOST
            );


        boostFill.style.width =
            boostEnergy + "%";

        boostValue.textContent =
            Math.round(
                boostEnergy
            ) + "%";
    }


    /* =========================================================
       KEYBOARD INPUT
       ========================================================= */

    document.addEventListener(
        "keydown",
        event => {

            if (
                !gameRunning
            ) {

                return;
            }


            const key =
                event.key.toLowerCase();


            if (
                [
                    "arrowup",
                    "arrowdown",
                    "arrowleft",
                    "arrowright",
                    " ",
                    "shift"
                ].includes(
                    key
                )
            ) {

                event.preventDefault();
            }


            switch (key) {

                case "arrowup":
                case "w":

                    setDirection(
                        0,
                        -1
                    );

                    break;


                case "arrowdown":
                case "s":

                    setDirection(
                        0,
                        1
                    );

                    break;


                case "arrowleft":
                case "a":

                    setDirection(
                        -1,
                        0
                    );

                    break;


                case "arrowright":
                case "d":

                    setDirection(
                        1,
                        0
                    );

                    break;


                case " ":
                case "shift":

                    boosting = true;

                    break;


                case "p":

                    togglePause();

                    break;

            }
        }
    );


    /* =========================================================
       KEY RELEASE
       ========================================================= */

    document.addEventListener(
        "keyup",
        event => {

            const key =
                event.key.toLowerCase();


            if (
                key === " " ||
                key === "shift"
            ) {

                boosting = false;
            }
        }
    );


    /* =========================================================
       SET DIRECTION
       ========================================================= */

    function setDirection(
        x,
        y
    ) {

        /*
         * Prevent immediate reversal.
         */

        if (
            direction.x === -x &&
            direction.y === -y
        ) {

            return;
        }


        nextDirection = {
            x,
            y
        };
    }


    /* =========================================================
       MOBILE / POINTER CONTROLS
       ========================================================= */

    function bindDirectionButton(
        id,
        x,
        y
    ) {

        const button =
            document.getElementById(id);


        button.addEventListener(
            "pointerdown",
            event => {

                event.preventDefault();

                setDirection(
                    x,
                    y
                );
            }
        );
    }


    bindDirectionButton(
        "upButton",
        0,
        -1
    );


    bindDirectionButton(
        "downButton",
        0,
        1
    );


    bindDirectionButton(
        "leftButton",
        -1,
        0
    );


    bindDirectionButton(
        "rightButton",
        1,
        0
    );


    /* =========================================================
       BOOST BUTTON
       ========================================================= */

    const boostButton =
        document.getElementById(
            "boostButton"
        );


    boostButton.addEventListener(
        "pointerdown",
        event => {

            event.preventDefault();

            boosting = true;

            boostButton.setPointerCapture?.(
                event.pointerId
            );
        }
    );


    boostButton.addEventListener(
        "pointerup",
        event => {

            event.preventDefault();

            boosting = false;
        }
    );


    boostButton.addEventListener(
        "pointercancel",
        () => {

            boosting = false;
        }
    );


    boostButton.addEventListener(
        "pointerleave",
        () => {

            boosting = false;
        }
    );


    /* =========================================================
       PAUSE
       ========================================================= */

    function togglePause() {

        if (
            !gameRunning
        ) {

            return;
        }


        gamePaused = !gamePaused;


        if (
            gamePaused
        ) {

            showPauseOverlay();

        } else {

            hideOverlay();

            lastFrameTime =
                performance.now();
        }
    }


    /* =========================================================
       SHOW PAUSE
       ========================================================= */

    function showPauseOverlay() {

        overlayTitle.textContent =
            "Game Paused";

        overlayMessage.innerHTML =
            `
        Your game is currently paused.
        <br><br>
        Press <strong>P</strong> or the button below
        to continue.
        `;

        overlayTarget.textContent =
            targetBinaryString;

        startGameButton.innerHTML =
            `
        <i class="bi bi-play-fill me-1"></i>
        Resume Game
        `;

        exitGameButton.textContent =
            "Exit Game";

        gameOverlay.classList.remove(
            "hidden"
        );
    }


    /* =========================================================
       HIDE OVERLAY
       ========================================================= */

    function hideOverlay() {

        gameOverlay.classList.add(
            "hidden"
        );
    }


    /* =========================================================
       OPEN GAME
       ========================================================= */

    launchGameBtn.addEventListener(
        "click",
        openGame
    );


    /* =========================================================
       OPEN GAME FUNCTION
       ========================================================= */

    async function openGame() {

        gameShell.classList.add(
            "active"
        );

        gameShell.setAttribute(
            "aria-hidden",
            "false"
        );


        resizeGame();


        /*
         * Request browser fullscreen.
         *
         * CSS fullscreen remains active even
         * if the browser rejects the request.
         */

        try {

            if (
                !document.fullscreenElement
            ) {

                await document.documentElement
                    .requestFullscreen();

            }

        } catch (error) {

            /*
             * Fullscreen permission can be denied.
             * The CSS fixed game shell still works.
             */

        }


        showStartScreen();
    }


    /* =========================================================
       START SCREEN
       ========================================================= */

    function showStartScreen() {

        gameRunning = false;

        gamePaused = false;


        overlayTitle.textContent =
            "Binary Snake";

        overlayMessage.innerHTML =
            `
        Collect eight binary digits in the
        correct order to create the target
        binary number.

        <br><br>

        Move with
        <strong>WASD</strong>
        or
        <strong>Arrow Keys</strong>.

        Hold
        <strong>SPACE</strong>
        or
        <strong>SHIFT</strong>
        to boost.

        <br><br>

        <small>
            The arena wraps around at every edge.
            Power-ups can change the rules of the game.
        </small>
        `;


        startGameButton.innerHTML =
            `
        <i class="bi bi-play-fill me-1"></i>
        Start Game
        `;


        exitGameButton.textContent =
            "Exit";


        generateTarget();


        gameOverlay.classList.remove(
            "hidden"
        );
    }


    /* =========================================================
       START GAME
       ========================================================= */

    startGameButton.addEventListener(
        "click",
        () => {

            if (
                gamePaused
            ) {

                gamePaused = false;

                hideOverlay();

                lastFrameTime =
                    performance.now();

                return;
            }


            startGame();
        }
    );


    /* =========================================================
       START GAME FUNCTION
       ========================================================= */

    function startGame() {

        /*
         * IMPORTANT:
         * Explicitly remove the start overlay.
         * This prevents the start screen from
         * remaining over the game.
         */

        gameOverlay.classList.add(
            "hidden"
        );

        gameOverlay.setAttribute(
            "aria-hidden",
            "true"
        );


        gameRunning = true;

        gamePaused = false;


        score = 0;

        round = 1;

        timeRemaining =
            GAME_DURATION;


        boostEnergy =
            MAX_BOOST;


        boosting = false;


        shieldCount = 0;

        magnetUntil = 0;

        ghostUntil = 0;

        slowUntil = 0;

        turboUntil = 0;

        doubleUntil = 0;


        scoreValue.textContent =
            "0";

        roundValue.textContent =
            "1";

        timeValue.textContent =
            GAME_DURATION;


        updateShieldUI();


        resizeGame();


        /*
         * Clear previous game objects.
         */

        clearGameObjects();


        /*
         * Reset movement.
         */

        direction = {
            x: 1,
            y: 0
        };

        nextDirection = {
            x: 1,
            y: 0
        };


        /*
         * Create game objects.
         */

        createSnake();

        generateTarget();

        createBits();

        createGates();


        /*
         * IMPORTANT:
         * No obstacles are created at round 1.
         *
         * They will appear only after the
         * player successfully completes a binary.
         */

        obstacles = [];


        resetSequence();


        lastFrameTime =
            performance.now();


        previousTime =
            performance.now();


        accumulator = 0;


        if (
            animationFrameId
        ) {

            cancelAnimationFrame(
                animationFrameId
            );
        }


        animationFrameId =
            requestAnimationFrame(
                gameLoop
            );
    }


    /* =========================================================
       CLEAR GAME OBJECTS
       ========================================================= */

    function clearGameObjects() {

        bitLayer.innerHTML = "";

        powerLayer.innerHTML = "";

        obstacleLayer.innerHTML = "";

        gateLayer.innerHTML = "";

        snakeLayer.innerHTML = "";

        fxLayer.innerHTML = "";


        bits = [];

        powers = [];

        obstacles = [];

        gates = [];

        snake = [];

        snakeElements = [];
    }


    /* =========================================================
       GAME LOOP
       =========================================================

       requestAnimationFrame is used instead of setInterval.

       The browser can therefore render at the monitor's
       available refresh rate.

       On a 120 Hz display, the game can render at approximately
       120 frames per second.

       The simulation uses delta time so game speed remains
       independent of the rendering refresh rate.
       ========================================================= */

    function gameLoop(timestamp) {

        if (
            !gameRunning
        ) {

            return;
        }


        animationFrameId =
            requestAnimationFrame(
                gameLoop
            );


        let dt =
            (
                timestamp -
                previousTime
            ) / 1000;


        previousTime =
            timestamp;


        /*
         * Protect against large time jumps
         * caused by tab switching.
         */

        dt =
            Math.min(
                dt,
                .05
            );


        if (
            gamePaused
        ) {

            return;
        }


        /*
         * Boost energy.
         */

        updateBoost(dt);


        /*
         * Game timer.
         *
         * Slow Time reduces the timer
         * drain as well.
         */

        let timerMultiplier =
            slowUntil >
            timestamp ?
            .5 :
            1;


        timeRemaining -=
            dt *
            timerMultiplier;


        if (
            timeRemaining <= 0
        ) {

            timeRemaining = 0;

            timeValue.textContent =
                "0";

            gameOver(
                "Time has expired."
            );

            return;
        }


        timeValue.textContent =
            Math.ceil(
                timeRemaining
            );


        /*
         * Continuous movement.
         */

        moveSnake(dt);


        /*
         * Magnet.
         */

        updateMagnet(dt);


        /*
         * Collision systems.
         */

        checkBitCollection();

        checkPowerCollection();

        checkObstacleCollision();

        checkGateCollision();


        /*
         * Render.
         */

        renderSnake();

        renderBits();

        renderPowers();

        renderGates();

        updatePowerStatus();


        /*
         * Continue until the browser
         * schedules the next frame.
         */
    }


    /* =========================================================
       GAME OVER
       ========================================================= */

    function gameOver(
        reason
    ) {

        if (
            !gameRunning
        ) {

            return;
        }


        gameRunning = false;

        boosting = false;


        if (
            animationFrameId
        ) {

            cancelAnimationFrame(
                animationFrameId
            );

            animationFrameId =
                null;
        }


        overlayTitle.textContent =
            "Game Over";


        overlayMessage.innerHTML =
            `
        ${reason}

        <br><br>

        <strong>
            Final Score:
            ${Math.floor(score)}
        </strong>

        <br>

        <strong>
            Rounds Completed:
            ${Math.max(0, round - 1)}
        </strong>
        `;


        overlayTarget.textContent =
            targetBinaryString;


        startGameButton.innerHTML =
            `
        <i class="bi bi-arrow-repeat me-1"></i>
        Play Again
        `;


        exitGameButton.textContent =
            "Exit Game";


        gameOverlay.setAttribute(
            "aria-hidden",
            "false"
        );


        gameOverlay.classList.remove(
            "hidden"
        );
    }


    /* =========================================================
       EXIT GAME
       ========================================================= */

    exitGameButton.addEventListener(
        "click",
        async () => {

            gameRunning = false;

            gamePaused = false;

            boosting = false;


            if (
                animationFrameId
            ) {

                cancelAnimationFrame(
                    animationFrameId
                );

                animationFrameId =
                    null;
            }


            gameShell.classList.remove(
                "active"
            );

            gameShell.setAttribute(
                "aria-hidden",
                "true"
            );


            try {

                if (
                    document.fullscreenElement
                ) {

                    await document.exitFullscreen();
                }

            } catch (error) {}

        }
    );


    /* =========================================================
       FULLSCREEN CHANGE
       ========================================================= */

    document.addEventListener(
        "fullscreenchange",
        () => {

            setTimeout(
                () => {

                    resizeGame();

                },
                50
            );
        }
    );


    /* =========================================================
       WINDOW RESIZE
       ========================================================= */

    window.addEventListener(
        "resize",
        resizeGame
    );


    /* =========================================================
       INITIAL UI
       ========================================================= */

    updateSequenceUI();

    updateShieldUI();
</script>