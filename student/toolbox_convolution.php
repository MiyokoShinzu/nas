<?php

/* =========================================================
   COMMON Z-TRANSFORM PAIRS & ROC
   ETS-Async Learning Portal
   ========================================================= */

session_start();

/* =========================================================
   AUTHENTICATION
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

/* =========================================================
   USER DATA
   ========================================================= */

$user = $_SESSION["user"];

$firstName      = $user["first_name"] ?? "";
$lastName       = $user["last_name"] ?? "";
$middleInitial  = $user["middle_initial"] ?? "";
$extensionName  = $user["extension_name"] ?? "";

$studentId      = $user["student_id"] ?? "";
$department     = $user["department"] ?? "";
$yearSection    = $user["year_section"] ?? "";
$email          = $user["email"] ?? "";
$username       = $user["username"] ?? "";

$fullName = trim(
    $firstName . " " .
        ($middleInitial ? $middleInitial . ". " : "") .
        $lastName .
        ($extensionName ? " " . $extensionName : "")
);

$initials = "";

if ($firstName !== "") {
    $initials .= strtoupper(substr($firstName, 0, 1));
}

if ($lastName !== "") {
    $initials .= strtoupper(substr($lastName, 0, 1));
}

/* =========================================================
   DATABASE CONNECTION
   ========================================================= */

require_once "../src/connection.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'globals/head.php'; ?>

    <!-- =====================================================
         MATHJAX
         ===================================================== -->

    <script>
        window.MathJax = {
            tex: {
                inlineMath: [
                    ['\\(', '\\)']
                ],
                displayMath: [
                    ['\\[', '\\]']
                ]
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"
        async>
    </script>

</head>

<body>

    <?php include 'globals/sidebar.php'; ?>
    <?php include 'globals/topbar.php'; ?>

    <main class="main-content">

        <div class="content-wrapper">

            <!-- =================================================
             PAGE HEADER
             ================================================= -->

            <div class="page-header">

                <div>
                    <div class="page-title">
                        <i class="bi bi-table"></i>
                        Common Z-Transform Pairs & ROC
                    </div>

                    <div class="page-subtitle">
                        Digital Signal Processing reference table
                    </div>
                </div>

            </div>


            <!-- =================================================
             INTRODUCTION
             ================================================= -->

            <div class="info-card mb-4">

                <div class="info-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>

                    <div class="info-title">
                        Z-Transform Reference
                    </div>

                    <div class="info-text">

                        The Region of Convergence (ROC) is an essential part of
                        the Z-transform specification. The same algebraic
                        expression \(X(z)\) can correspond to different
                        time-domain sequences depending on the ROC.

                    </div>

                </div>

            </div>


            <!-- =================================================
             SEARCH
             ================================================= -->

            <div class="tool-card mb-4">

                <div class="tool-card-header">

                    <div>
                        <div class="tool-title">
                            <i class="bi bi-search"></i>
                            Search Transform Pairs
                        </div>

                        <div class="tool-description">
                            Search by sequence, transform, or ROC.
                        </div>
                    </div>

                </div>

                <div class="tool-card-body">

                    <div class="search-wrapper">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="searchPairs"
                            class="form-control"
                            placeholder="Search transform pairs...">

                    </div>

                </div>

            </div>


            <!-- =================================================
             COMMON Z-TRANSFORM PAIRS
             ================================================= -->

            <div class="tool-card mb-4">

                <div class="tool-card-header">

                    <div>

                        <div class="tool-title">
                            <i class="bi bi-list-ol"></i>
                            Common Z-Transform Pairs
                        </div>

                        <div class="tool-description">
                            Frequently used bilateral Z-transform pairs.
                        </div>

                    </div>

                    <span class="pair-count" id="pairCount">
                        30 Pairs
                    </span>

                </div>


                <div class="table-responsive">

                    <table
                        class="table transform-table"
                        id="transformTable">

                        <thead>

                            <tr>

                                <th width="60">#</th>

                                <th>Time-Domain Sequence \(x[n]\)</th>

                                <th>Z-Transform \(X(z)\)</th>

                                <th>ROC</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>\(\delta[n]\)</td>
                                <td>\(1\)</td>
                                <td>All \(z\)</td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>\(\delta[n-n_0]\)</td>
                                <td>\(z^{-n_0}\)</td>
                                <td>All \(z\), except \(z=0\) if \(n_0>0\)</td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>\(u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{z-1}\)</td>
                                <td>\(|z| > 1\)</td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>\(u[-n]\)</td>
                                <td>\(\displaystyle\frac{z}{z-1}\)</td>
                                <td>\(|z| < 1\)</td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>\(a^n u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{z-a}\)</td>
                                <td>\(|z|>|a|\)</td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td>\(-a^n u[-n-1]\)</td>
                                <td>\(\displaystyle\frac{z}{z-a}\)</td>
                                <td>\(|z| < |a|\) </td>
                            </tr>

                            <tr>
                                <td>7</td>
                                <td>\(n u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{(z-1)^2}\)</td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>\(n a^n u[n]\)</td>
                                <td>\(\displaystyle\frac{az}{(z-a)^2}\)</td>
                                <td>\(|z|>|a|\)</td>
                            </tr>

                            <tr>
                                <td>9</td>
                                <td>\(n^2u[n]\)</td>
                                <td>\(\displaystyle\frac{z(z+1)}{(z-1)^3}\)</td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>\(a^n u[n-n_0]\)</td>
                                <td>\(\displaystyle\frac{a^{n_0}z^{1-n_0}}{z-a}\)</td>
                                <td>\(|z|>|a|\)</td>
                            </tr>

                            <tr>
                                <td>11</td>
                                <td>\(u[n-n_0]\)</td>
                                <td>\(\displaystyle\frac{z^{1-n_0}}{z-1}\)</td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>12</td>
                                <td>\(\cos(\omega_0n)u[n]\)</td>
                                <td>
                                    \(\displaystyle
                                    \frac{
                                    z(z-\cos\omega_0)
                                    }{
                                    z^2-2z\cos\omega_0+1
                                    }\)
                                </td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>13</td>
                                <td>\(\sin(\omega_0n)u[n]\)</td>
                                <td>
                                    \(\displaystyle
                                    \frac{
                                    z\sin\omega_0
                                    }{
                                    z^2-2z\cos\omega_0+1
                                    }\)
                                </td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>14</td>
                                <td>\(r^n\cos(\omega_0n)u[n]\)</td>
                                <td>
                                    \(\displaystyle
                                    \frac{
                                    z(z-r\cos\omega_0)
                                    }{
                                    z^2-2rz\cos\omega_0+r^2
                                    }\)
                                </td>
                                <td>\(|z|>|r|\)</td>
                            </tr>

                            <tr>
                                <td>15</td>
                                <td>\(r^n\sin(\omega_0n)u[n]\)</td>
                                <td>
                                    \(\displaystyle
                                    \frac{
                                    rz\sin\omega_0
                                    }{
                                    z^2-2rz\cos\omega_0+r^2
                                    }\)
                                </td>
                                <td>\(|z|>|r|\)</td>
                            </tr>

                            <tr>
                                <td>16</td>
                                <td>\(e^{j\omega_0n}u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{z-e^{j\omega_0}}\)</td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>17</td>
                                <td>\(e^{-j\omega_0n}u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{z-e^{-j\omega_0}}\)</td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>18</td>
                                <td>\(r^ne^{j\omega_0n}u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{z-re^{j\omega_0}}\)</td>
                                <td>\(|z|>|r|\)</td>
                            </tr>

                            <tr>
                                <td>19</td>
                                <td>\((-a)^n u[n]\)</td>
                                <td>\(\displaystyle\frac{z}{z+a}\)</td>
                                <td>\(|z|>|a|\)</td>
                            </tr>

                            <tr>
                                <td>20</td>
                                <td>\(a^n u[-n-1]\)</td>
                                <td>\(\displaystyle-\frac{z}{z-a}\)</td>
                                <td>\(|z|<|a|\)</td>
                            </tr>

                            <tr>
                                <td>21</td>
                                <td>\(-n a^n u[-n-1]\)</td>
                                <td>\(\displaystyle\frac{az}{(z-a)^2}\)</td>
                                <td>\(|z|<|a|\)</td>
                            </tr>

                            <tr>
                                <td>22</td>
                                <td>\(\cos(\omega_0n+\phi)u[n]\)</td>
                                <td>
                                    \(\displaystyle
                                    \frac{
                                    z[
                                    z\cos\phi-\cos(\omega_0+\phi)
                                    ]
                                    }{
                                    z^2-2z\cos\omega_0+1
                                    }\)
                                </td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>23</td>
                                <td>\(\sin(\omega_0n+\phi)u[n]\)</td>
                                <td>
                                    \(\displaystyle
                                    \frac{
                                    z[
                                    z\sin\phi+\sin(\omega_0-\phi)
                                    ]
                                    }{
                                    z^2-2z\cos\omega_0+1
                                    }\)
                                </td>
                                <td>\(|z|>1\)</td>
                            </tr>

                            <tr>
                                <td>24</td>
                                <td>\((n+1)a^n u[n]\)</td>
                                <td>\(\displaystyle\frac{z^2}{(z-a)^2}\)</td>
                                <td>\(|z|>|a|\)</td>
                            </tr>

                            <tr>
                                <td>25</td>
                                <td>\(\displaystyle\frac{a^n}{n!}u[n]\)</td>
                                <td>\(\displaystyle e^{a/z}\)</td>
                                <td>\(z\neq0\)</td>
                            </tr>

                            <tr>
                                <td>26</td>
                                <td>\(u[n]-u[n-N]\)</td>
                                <td>
                                    \(\displaystyle
                                    \sum_{n=0}^{N-1}z^{-n}
                                    =
                                    \frac{1-z^{-N}}{1-z^{-1}}
                                    \)
                                </td>
                                <td>\(z\neq0\)</td>
                            </tr>

                            <tr>
                                <td>27</td>
                                <td>\(1,\quad 0\leq n\leq N\)</td>
                                <td>
                                    \(\displaystyle
                                    \sum_{n=0}^{N}z^{-n}
                                    \)
                                </td>
                                <td>\(z\neq0\)</td>
                            </tr>

                            <tr>
                                <td>28</td>
                                <td>\(\delta[n]+\delta[n-1]\)</td>
                                <td>\(1+z^{-1}\)</td>
                                <td>\(z\neq0\)</td>
                            </tr>

                            <tr>
                                <td>29</td>
                                <td>\(\delta[n]-\delta[n-1]\)</td>
                                <td>\(1-z^{-1}\)</td>
                                <td>\(z\neq0\)</td>
                            </tr>

                            <tr>
                                <td>30</td>
                                <td>\(a^n,\quad 0\leq n\leq N\)</td>
                                <td>
                                    \(\displaystyle
                                    \sum_{n=0}^{N}a^nz^{-n}
                                    \)
                                </td>
                                <td>\(z\neq0\)</td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =================================================
             Z-TRANSFORM PROPERTIES
             ================================================= -->

            <div class="tool-card mb-4">

                <div class="tool-card-header">

                    <div>

                        <div class="tool-title">
                            <i class="bi bi-arrow-left-right"></i>
                            Z-Transform Properties
                        </div>

                        <div class="tool-description">
                            Fundamental properties used in solving
                            Z-transform problems.
                        </div>

                    </div>

                    <span class="pair-count">
                        10 Properties
                    </span>

                </div>


                <div class="table-responsive">

                    <table class="table transform-table">

                        <thead>

                            <tr>

                                <th width="60">#</th>

                                <th>Property</th>

                                <th>Time Domain</th>

                                <th>Z Domain</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>1</td>

                                <td>Linearity</td>

                                <td>
                                    \(a x_1[n]+b x_2[n]\)
                                </td>

                                <td>
                                    \(aX_1(z)+bX_2(z)\)
                                </td>

                            </tr>


                            <tr>

                                <td>2</td>

                                <td>Time Delay</td>

                                <td>
                                    \(x[n-n_0]\)
                                </td>

                                <td>
                                    \(z^{-n_0}X(z)\)
                                </td>

                            </tr>


                            <tr>

                                <td>3</td>

                                <td>Time Advance</td>

                                <td>
                                    \(x[n+n_0]\)
                                </td>

                                <td>
                                    \(z^{n_0}X(z)\)
                                </td>

                            </tr>


                            <tr>

                                <td>4</td>

                                <td>Time Reversal</td>

                                <td>
                                    \(x[-n]\)
                                </td>

                                <td>
                                    \(X(z^{-1})\)
                                </td>

                            </tr>


                            <tr>

                                <td>5</td>

                                <td>Exponential Multiplication</td>

                                <td>
                                    \(a^nx[n]\)
                                </td>

                                <td>
                                    \(X(z/a)\)
                                </td>

                            </tr>


                            <tr>

                                <td>6</td>

                                <td>Multiplication by \(n\)</td>

                                <td>
                                    \(nx[n]\)
                                </td>

                                <td>
                                    \(\displaystyle
                                    -z\frac{dX(z)}{dz}
                                    \)
                                </td>

                            </tr>


                            <tr>

                                <td>7</td>

                                <td>Convolution</td>

                                <td>
                                    \(x[n]*h[n]\)
                                </td>

                                <td>
                                    \(X(z)H(z)\)
                                </td>

                            </tr>


                            <tr>

                                <td>8</td>

                                <td>First Difference</td>

                                <td>
                                    \(x[n]-x[n-1]\)
                                </td>

                                <td>
                                    \((1-z^{-1})X(z)\)
                                </td>

                            </tr>


                            <tr>

                                <td>9</td>

                                <td>Accumulation</td>

                                <td>
                                    \(\displaystyle
                                    \sum_{k=-\infty}^{n}x[k]
                                    \)
                                </td>

                                <td>
                                    \(\displaystyle
                                    \frac{X(z)}{1-z^{-1}}
                                    \)
                                </td>

                            </tr>


                            <tr>

                                <td>10</td>

                                <td>Complex Conjugation</td>

                                <td>
                                    \(x^*[n]\)
                                </td>

                                <td>
                                    \(X^*(z^*)\)
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =================================================
             ROC REFERENCE
             ================================================= -->

            <div class="tool-card mb-4">

                <div class="tool-card-header">

                    <div>

                        <div class="tool-title">
                            <i class="bi bi-bullseye"></i>
                            Region of Convergence (ROC)
                        </div>

                        <div class="tool-description">
                            Quick reference for determining the ROC.
                        </div>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table transform-table">

                        <thead>

                            <tr>

                                <th>Sequence / System Type</th>

                                <th>Typical ROC</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    Right-sided sequence
                                </td>

                                <td>
                                    Outside the outermost pole
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Left-sided sequence
                                </td>

                                <td>
                                    Inside the innermost pole
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Two-sided sequence
                                </td>

                                <td>
                                    Annulus between poles
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Finite-duration sequence
                                </td>

                                <td>
                                    Entire \(z\)-plane except possibly
                                    \(z=0\) and/or \(z=\infty\)
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Stable system
                                </td>

                                <td>
                                    ROC includes the unit circle
                                    \(|z|=1\)
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Causal rational LTI system
                                </td>

                                <td>
                                    Outside the outermost pole
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Anti-causal rational LTI system
                                </td>

                                <td>
                                    Inside the innermost pole
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Two-sided rational sequence
                                </td>

                                <td>
                                    Between pole radii
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =================================================
             IMPORTANT ROC RULES
             ================================================= -->

            <div class="roc-rules">

                <div class="roc-rule">

                    <div class="roc-rule-icon">
                        <i class="bi bi-1-circle"></i>
                    </div>

                    <div>

                        <strong>
                            ROC cannot contain a pole.
                        </strong>

                        <p>
                            A pole is a point where \(X(z)\) becomes
                            unbounded. Therefore, it cannot belong to
                            the ROC.
                        </p>

                    </div>

                </div>


                <div class="roc-rule">

                    <div class="roc-rule-icon">
                        <i class="bi bi-2-circle"></i>
                    </div>

                    <div>

                        <strong>
                            Right-sided sequences produce an exterior ROC.
                        </strong>

                        <p>
                            For a rational Z-transform, the ROC extends
                            outward from the outermost pole.
                        </p>

                    </div>

                </div>


                <div class="roc-rule">

                    <div class="roc-rule-icon">
                        <i class="bi bi-3-circle"></i>
                    </div>

                    <div>

                        <strong>
                            Left-sided sequences produce an interior ROC.
                        </strong>

                        <p>
                            The ROC extends inward toward the origin
                            from the innermost pole.
                        </p>

                    </div>

                </div>


                <div class="roc-rule">

                    <div class="roc-rule-icon">
                        <i class="bi bi-4-circle"></i>
                    </div>

                    <div>

                        <strong>
                            Stability requires the unit circle to be inside the ROC.
                        </strong>

                        <p>
                            For an LTI system, BIBO stability requires
                            the unit circle \(|z|=1\) to lie completely
                            inside the ROC.
                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
             SAME X(z), DIFFERENT ROC
             ================================================= -->

            <div class="example-card mt-4">

                <div class="example-header">

                    <i class="bi bi-lightbulb"></i>

                    <span>
                        Important Example
                    </span>

                </div>


                <div class="example-body">

                    <div class="equation-large">

                        \[
                        X(z)=\frac{z}{z-a}
                        \]

                    </div>


                    <div class="table-responsive">

                        <table class="table example-table">

                            <thead>

                                <tr>

                                    <th>ROC</th>

                                    <th>Time-Domain Sequence</th>

                                    <th>Sequence Type</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>
                                        \(|z|>|a|\)
                                    </td>

                                    <td>
                                        \(a^n u[n]\)
                                    </td>

                                    <td>
                                        Right-sided
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        \(|z|<|a|\)
                                            </td>

                                    <td>
                                        \(-a^n u[-n-1]\)
                                    </td>

                                    <td>
                                        Left-sided
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <div class="example-note">

                        <i class="bi bi-exclamation-triangle"></i>

                        <span>
                            The algebraic expression alone does not uniquely
                            determine the sequence. The ROC must also be known.
                        </span>

                    </div>

                </div>

            </div>


        </div>

    </main>


    <style>
        /* =========================================================
   Z-TRANSFORM PAIRS PAGE
   ========================================================= */

        :root {

            --zt-primary: #315efb;
            --zt-primary-dark: #2449c7;
            --zt-secondary: #6f42c1;

            --zt-bg: #f6f8fc;
            --zt-card: #ffffff;

            --zt-text: #202938;
            --zt-muted: #6b7280;

            --zt-border: #e5e7eb;

            --zt-header: #f3f6fb;

        }


        /* =========================================================
   PAGE HEADER
   ========================================================= */

        .page-header {

            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 24px;

        }


        .page-title {

            font-size: 25px;
            font-weight: 700;

            color: var(--zt-text);

            display: flex;
            align-items: center;
            gap: 10px;

        }


        .page-title i {

            color: var(--zt-primary);

        }


        .page-subtitle {

            margin-top: 5px;

            font-size: 14px;

            color: var(--zt-muted);

        }


        /* =========================================================
   INFO CARD
   ========================================================= */

        .info-card {

            display: flex;
            gap: 15px;

            padding: 18px 20px;

            border-radius: 12px;

            background: #eef4ff;

            border: 1px solid #dbe7ff;

        }


        .info-icon {

            width: 40px;
            height: 40px;

            flex-shrink: 0;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--zt-primary);

            color: white;

            font-size: 20px;

        }


        .info-title {

            font-size: 15px;

            font-weight: 700;

            color: var(--zt-text);

            margin-bottom: 3px;

        }


        .info-text {

            font-size: 14px;

            line-height: 1.6;

            color: #4b5563;

        }


        /* =========================================================
   TOOL CARD
   ========================================================= */

        .tool-card {

            background: var(--zt-card);

            border: 1px solid var(--zt-border);

            border-radius: 14px;

            overflow: hidden;

            box-shadow: 0 2px 8px rgba(0, 0, 0, .035);

        }


        .tool-card-header {

            padding: 18px 20px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid var(--zt-border);

        }


        .tool-title {

            font-size: 16px;

            font-weight: 700;

            color: var(--zt-text);

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .tool-title i {

            color: var(--zt-primary);

        }


        .tool-description {

            font-size: 13px;

            color: var(--zt-muted);

            margin-top: 4px;

        }


        .tool-card-body {

            padding: 18px 20px;

        }


        /* =========================================================
   SEARCH
   ========================================================= */

        .search-wrapper {

            position: relative;

        }


        .search-wrapper i {

            position: absolute;

            left: 14px;
            top: 50%;

            transform: translateY(-50%);

            color: #9ca3af;

        }


        .search-wrapper input {

            padding-left: 42px;

            height: 44px;

            border-radius: 9px;

            border: 1px solid var(--zt-border);

        }


        .search-wrapper input:focus {

            border-color: var(--zt-primary);

            box-shadow: 0 0 0 3px rgba(49, 94, 251, .10);

        }


        /* =========================================================
   PAIR COUNT
   ========================================================= */

        .pair-count {

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;

            background: #eef4ff;

            color: var(--zt-primary);

        }


        /* =========================================================
   TABLE
   ========================================================= */

        .transform-table {

            margin-bottom: 0;

            vertical-align: middle;

        }


        .transform-table thead th {

            background: var(--zt-header);

            color: #374151;

            font-size: 12px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .35px;

            border-bottom: 1px solid var(--zt-border);

            padding: 13px 15px;

            white-space: nowrap;

        }


        .transform-table tbody td {

            padding: 15px;

            border-bottom: 1px solid #edf0f4;

            color: var(--zt-text);

            font-size: 14px;

        }


        .transform-table tbody tr:last-child td {

            border-bottom: none;

        }


        .transform-table tbody tr:hover {

            background: #f8faff;

        }


        .transform-table tbody td:first-child {

            font-weight: 700;

            color: var(--zt-primary);

        }


        /* =========================================================
   EXAMPLE CARD
   ========================================================= */

        .example-card {

            border-radius: 14px;

            overflow: hidden;

            border: 1px solid #dbe4ff;

            background: var(--zt-card);

        }


        .example-header {

            padding: 15px 20px;

            background: #eef4ff;

            color: var(--zt-primary);

            font-weight: 700;

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .example-body {

            padding: 22px;

        }


        .equation-large {

            padding: 10px 0 20px;

            text-align: center;

            font-size: 20px;

        }


        .example-table {

            margin-bottom: 0;

        }


        .example-table th {

            background: var(--zt-header);

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: .3px;

        }


        .example-table td,
        .example-table th {

            padding: 13px 15px;

            vertical-align: middle;

        }


        .example-note {

            margin-top: 18px;

            padding: 13px 15px;

            border-radius: 9px;

            background: #fff8e7;

            border: 1px solid #f4dfab;

            color: #705b1c;

            font-size: 13px;

            display: flex;

            gap: 9px;

            align-items: flex-start;

        }


        .example-note i {

            margin-top: 2px;

        }


        /* =========================================================
   ROC RULES
   ========================================================= */

        .roc-rules {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 15px;

        }


        .roc-rule {

            background: var(--zt-card);

            border: 1px solid var(--zt-border);

            border-radius: 12px;

            padding: 18px;

            display: flex;

            gap: 14px;

        }


        .roc-rule-icon {

            width: 38px;
            height: 38px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: #eef4ff;

            color: var(--zt-primary);

            font-size: 19px;

        }


        .roc-rule strong {

            display: block;

            font-size: 14px;

            color: var(--zt-text);

            margin-bottom: 5px;

        }


        .roc-rule p {

            margin: 0;

            color: var(--zt-muted);

            font-size: 13px;

            line-height: 1.6;

        }


        /* =========================================================
   DARK MODE
   ========================================================= */

        html[data-theme="dark"] {

            --zt-bg: #111827;
            --zt-card: #1b2433;

            --zt-text: #f3f4f6;
            --zt-muted: #a8b0bf;

            --zt-border: #303b4d;

            --zt-header: #222c3d;

        }


        html[data-theme="dark"] .info-card {

            background: #172641;

            border-color: #284268;

        }


        html[data-theme="dark"] .info-text {

            color: #c7d0df;

        }


        html[data-theme="dark"] .transform-table tbody td {

            border-color: #303b4d;

        }


        html[data-theme="dark"] .transform-table tbody tr:hover {

            background: #222d40;

        }


        html[data-theme="dark"] .transform-table thead th {

            color: #dbe3ef;

        }


        html[data-theme="dark"] .search-wrapper input {

            background: #111827;

            color: #f3f4f6;

        }


        html[data-theme="dark"] .search-wrapper input::placeholder {

            color: #7f8999;

        }


        html[data-theme="dark"] .pair-count {

            background: #172641;

            color: #8eacff;

        }


        html[data-theme="dark"] .example-card {

            border-color: #30466e;

        }


        html[data-theme="dark"] .example-header {

            background: #172641;

        }


        html[data-theme="dark"] .example-note {

            background: #302b1a;

            border-color: #665622;

            color: #e4d69a;

        }


        html[data-theme="dark"] .roc-rule-icon {

            background: #172641;

        }


        /* =========================================================
   RESPONSIVE
   ========================================================= */

        @media (max-width: 900px) {

            .roc-rules {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 768px) {

            .page-title {

                font-size: 21px;

            }

            .tool-card-header {

                align-items: flex-start;

                gap: 12px;

            }

            .transform-table {

                min-width: 850px;

            }

        }
    </style>


    <script>
        /* =========================================================
   SEARCH TRANSFORM TABLE
   ========================================================= */

        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.getElementById("searchPairs");

            const table = document.getElementById("transformTable");

            const pairCount = document.getElementById("pairCount");

            if (!searchInput || !table) {
                return;
            }


            function updateSearch() {

                const searchValue =
                    searchInput.value
                    .toLowerCase()
                    .trim();


                const rows =
                    table.querySelectorAll("tbody tr");


                let visibleCount = 0;


                rows.forEach(function(row) {

                    const text =
                        row.textContent.toLowerCase();


                    if (
                        searchValue === "" ||
                        text.includes(searchValue)
                    ) {

                        row.style.display = "";

                        visibleCount++;

                    } else {

                        row.style.display = "none";

                    }

                });


                if (pairCount) {

                    pairCount.textContent =
                        visibleCount + " Pairs";

                }


                /* Re-typeset visible equations */

                if (
                    window.MathJax &&
                    window.MathJax.typesetPromise
                ) {

                    MathJax.typesetPromise([table]);

                }

            }


            searchInput.addEventListener(
                "input",
                updateSearch
            );

        });


        /* =========================================================
           ENSURE MATHJAX TYPESETS AFTER LOADING
           ========================================================= */

        function typesetMath() {

            if (
                window.MathJax &&
                window.MathJax.startup &&
                window.MathJax.startup.promise
            ) {

                MathJax.startup.promise.then(function() {

                    MathJax.typesetPromise();

                });

                return;

            }


            setTimeout(typesetMath, 300);

        }


        typesetMath();
    </script>


    <?php include 'globals/scripts.php'; ?>

</body>

</html>