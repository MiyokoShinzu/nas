
<?php
/* =========================================================
   ETS-ASYNC LEARNING PORTAL
   STUDENT ASSESSMENT PAGE

   FEATURES:
   ---------------------------------------------------------
   1. Student-only authentication
   2. Test-code entry
   3. Invalid test-code notification
   4. Assessment history
   5. Attempt number
   6. Score and percentage
   7. Passed / Failed status
   8. Responsive design
   9. Light / Dark theme
   10. Bootstrap 5
   11. Academic blue ETS styling
   ========================================================= */


/* =========================================================
   SESSION
   ========================================================= */

session_start();


/* =========================================================
   TIMEZONE
   ========================================================= */

date_default_timezone_set('Asia/Manila');


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


/* =========================================================
   CURRENT USER
   ========================================================= */

$user = $_SESSION["user"];


/* =========================================================
   STUDENT ID
   ========================================================= */

$student_id = trim(
    (string)($user["student_id"] ?? "")
);


/* =========================================================
   DATABASE CONNECTION
   =========================================================

   Your project uses:

       $mysqli

   NOT:

       $conn
   ========================================================= */

require_once "../src/connection.php";


/* =========================================================
   HELPER FUNCTION
   ========================================================= */

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        "UTF-8"
    );
}


/* =========================================================
   HELPER FUNCTION
   FORMAT DATE
   ========================================================= */

function formatAssessmentDate($date)
{
    if (empty($date)) {
        return "—";
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return "—";
    }

    return date(
        "M d, Y • h:i A",
        $timestamp
    );
}


/* =========================================================
   GET ERROR MESSAGE FROM SESSION
   =========================================================

   assessment_start.php can place an error message here
   before redirecting back to this page.

   Example:

       $_SESSION["assessment_error"] =
           "No assessment was found...";
   ========================================================= */

$assessment_error =
    $_SESSION["assessment_error"] ?? "";


/* =========================================================
   CLEAR ERROR AFTER READING
   =========================================================

   This prevents the same error from appearing again after
   the next page load.
   ========================================================= */

unset(
    $_SESSION["assessment_error"]
);


/* =========================================================
   GET ASSESSMENT HISTORY
   ========================================================= */

$assessment_history = [];


/* =========================================================
   ASSESSMENT STATISTICS
   ========================================================= */

$total_attempts = 0;

$passed_attempts = 0;

$failed_attempts = 0;


/* =========================================================
   ONLY QUERY HISTORY IF STUDENT ID EXISTS
   ========================================================= */

if ($student_id !== "") {


    /* =====================================================
       ASSESSMENT HISTORY QUERY
       ===================================================== */

    $sql = "
        SELECT
            aa.id,
            aa.assessment_id,
            aa.test_code,
            aa.attempt_number,
            aa.score,
            aa.total_items,
            aa.percentage,
            aa.status,
            aa.started_at,
            aa.submitted_at,
            aa.created_at,

            a.title AS assessment_title

        FROM assessment_attempts aa

        LEFT JOIN assessments a
            ON a.id = aa.assessment_id

        WHERE aa.student_id = ?

        ORDER BY
            COALESCE(
                aa.submitted_at,
                aa.created_at
            ) DESC,

            aa.id DESC
    ";


    /* =====================================================
       PREPARE
       ===================================================== */

    $stmt =
        $mysqli->prepare($sql);


    /* =====================================================
       EXECUTE
       ===================================================== */

    if ($stmt) {

        $stmt->bind_param(
            "s",
            $student_id
        );

        $stmt->execute();

        $result =
            $stmt->get_result();


        /* =================================================
           FETCH RECORDS
           ================================================= */

        while (
            $row = $result->fetch_assoc()
        ) {

            $assessment_history[] =
                $row;


            $total_attempts++;


            /* =============================================
               PASSED / FAILED
               ============================================= */

            if (
                strtolower(
                    trim(
                        $row["status"] ?? ""
                    )
                ) === "passed"
            ) {

                $passed_attempts++;
            } else {

                $failed_attempts++;
            }
        }


        $stmt->close();
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <?php include 'globals/head.php'; ?>


    <style>
        /* =====================================================
           ETS ASSESSMENT PAGE
           ===================================================== */

        :root {

            --assessment-primary: #0B4F8A;

            --assessment-primary-dark: #083B68;

            --assessment-bg: #f6f8fb;

            --assessment-card: #ffffff;

            --assessment-border: #e5e7eb;

            --assessment-text: #1f2937;

            --assessment-muted: #6b7280;

            --assessment-hover: #f8fafc;

            --assessment-success: #15803d;

            --assessment-success-bg: #dcfce7;

            --assessment-danger: #b91c1c;

            --assessment-danger-bg: #fee2e2;

            --assessment-warning: #b45309;

            --assessment-warning-bg: #fef3c7;
        }


        /* =====================================================
           DARK THEME
           ===================================================== */

        html[data-theme="dark"] {

            --assessment-bg: #0f172a;

            --assessment-card: #111827;

            --assessment-border: #273244;

            --assessment-text: #e5e7eb;

            --assessment-muted: #9ca3af;

            --assessment-hover: #172033;

            --assessment-primary: #4f9bd6;

            --assessment-primary-dark: #357fb7;

            --assessment-success: #4ade80;

            --assessment-success-bg: #12351f;

            --assessment-danger: #f87171;

            --assessment-danger-bg: #3a1717;

            --assessment-warning: #fbbf24;

            --assessment-warning-bg: #3a2b0c;
        }


        /* =====================================================
           BODY
           ===================================================== */

        body {

            background:
                var(--assessment-bg);

            color:
                var(--assessment-text);

        }


        /* =====================================================
           MAIN PAGE
           ===================================================== */

        .assessment-page {

            max-width: 1200px;

            margin: 0 auto;

        }


        /* =====================================================
           PAGE HEADER
           ===================================================== */

        .page-header {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;

        }


        .page-header h2 {

            margin: 0;

            font-size: 1.55rem;

            font-weight: 700;

            letter-spacing: -0.02em;

            color:
                var(--assessment-text);

        }


        .page-header p {

            margin: 6px 0 0;

            color:
                var(--assessment-muted);

            font-size: 0.92rem;

            line-height: 1.6;

        }


        /* =====================================================
           ERROR ALERT
           ===================================================== */

        .assessment-alert {

            display: flex;

            align-items: flex-start;

            gap: 12px;

            padding: 14px 16px;

            margin-bottom: 20px;

            border-radius: 10px;

            border: 1px solid var(--assessment-danger);

            background:
                var(--assessment-danger-bg);

            color:
                var(--assessment-danger);

        }


        .assessment-alert-icon {

            font-size: 1.05rem;

            line-height: 1.4;

            flex-shrink: 0;

        }


        .assessment-alert-content {

            flex: 1;

        }


        .assessment-alert-title {

            font-size: 0.86rem;

            font-weight: 700;

            margin-bottom: 2px;

        }


        .assessment-alert-message {

            font-size: 0.80rem;

            line-height: 1.5;

        }


        /* =====================================================
           CLOSE BUTTON
           ===================================================== */

        .assessment-alert-close {

            border: none;

            background: transparent;

            color:
                currentColor;

            opacity: 0.7;

            padding: 0;

            font-size: 1rem;

            cursor: pointer;

        }


        .assessment-alert-close:hover {

            opacity: 1;

        }


        /* =====================================================
           TEST CODE CARD
           ===================================================== */

        .test-code-card {

            background:
                var(--assessment-card);

            border:
                1px solid var(--assessment-border);

            border-radius: 12px;

            padding: 25px;

            margin-bottom: 25px;

            box-shadow:
                0 2px 6px rgba(15, 23, 42, 0.04);

        }


        /* =====================================================
           CARD HEADER
           ===================================================== */

        .test-code-header {

            display: flex;

            align-items: center;

            gap: 14px;

            margin-bottom: 20px;

        }


        .test-code-icon {

            width: 46px;

            height: 46px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                rgba(11, 79, 138, 0.10);

            color:
                var(--assessment-primary);

            font-size: 1.25rem;

            flex-shrink: 0;

        }


        .test-code-title {

            margin: 0;

            font-size: 1.05rem;

            font-weight: 700;

            color:
                var(--assessment-text);

        }


        .test-code-description {

            margin: 3px 0 0;

            font-size: 0.84rem;

            color:
                var(--assessment-muted);

        }


        /* =====================================================
           FORM
           ===================================================== */

        .test-code-form {

            display: flex;

            gap: 10px;

        }


        /* =====================================================
           INPUT
           ===================================================== */

        .test-code-input {

            flex: 1;

            min-width: 0;

            height: 46px;

            border:
                1px solid var(--assessment-border);

            border-radius: 8px;

            background:
                var(--assessment-bg);

            color:
                var(--assessment-text);

            padding: 0 14px;

            font-size: 0.92rem;

            outline: none;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;

        }


        .test-code-input::placeholder {

            color:
                var(--assessment-muted);

        }


        .test-code-input:focus {

            border-color:
                var(--assessment-primary);

            box-shadow:
                0 0 0 3px rgba(11, 79, 138, 0.10);

        }


        /* =====================================================
           START BUTTON
           ===================================================== */

        .btn-start-assessment {

            height: 46px;

            border: none;

            border-radius: 8px;

            padding: 0 20px;

            background:
                var(--assessment-primary);

            color: #ffffff;

            font-size: 0.88rem;

            font-weight: 600;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            white-space: nowrap;

            transition:
                background 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;

        }


        .btn-start-assessment:hover {

            background:
                var(--assessment-primary-dark);

            color: #ffffff;

            transform:
                translateY(-1px);

            box-shadow:
                0 4px 10px rgba(11, 79, 138, 0.20);

        }


        /* =====================================================
           HISTORY SECTION
           ===================================================== */

        .history-section {

            margin-top: 5px;

        }


        .section-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 14px;

        }


        .section-header h3 {

            margin: 0;

            font-size: 1.08rem;

            font-weight: 700;

            color:
                var(--assessment-text);

        }


        .section-header p {

            margin: 4px 0 0;

            font-size: 0.82rem;

            color:
                var(--assessment-muted);

        }


        /* =====================================================
           STATISTICS
           ===================================================== */

        .assessment-stats {

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 12px;

            margin-bottom: 18px;

        }


        .stat-card {

            background:
                var(--assessment-card);

            border:
                1px solid var(--assessment-border);

            border-radius: 10px;

            padding: 16px;

        }


        .stat-label {

            font-size: 0.75rem;

            color:
                var(--assessment-muted);

            margin-bottom: 5px;

        }


        .stat-value {

            font-size: 1.35rem;

            font-weight: 700;

            color:
                var(--assessment-text);

        }


        /* =====================================================
           HISTORY CARD
           ===================================================== */

        .history-card {

            background:
                var(--assessment-card);

            border:
                1px solid var(--assessment-border);

            border-radius: 12px;

            overflow: hidden;

            box-shadow:
                0 2px 6px rgba(15, 23, 42, 0.03);

        }


        /* =====================================================
           TABLE WRAPPER
           ===================================================== */

        .table-responsive {

            width: 100%;

            overflow-x: auto;

        }


        /* =====================================================
           TABLE
           ===================================================== */

        .assessment-table {

            width: 100%;

            min-width: 760px;

            border-collapse: collapse;

        }


        .assessment-table thead {

            background:
                var(--assessment-hover);

        }


        .assessment-table th {

            padding: 13px 16px;

            text-align: left;

            font-size: 0.72rem;

            font-weight: 700;

            color:
                var(--assessment-muted);

            text-transform: uppercase;

            letter-spacing: 0.04em;

            border-bottom:
                1px solid var(--assessment-border);

            white-space: nowrap;

        }


        .assessment-table td {

            padding: 15px 16px;

            border-bottom:
                1px solid var(--assessment-border);

            color:
                var(--assessment-text);

            font-size: 0.84rem;

            vertical-align: middle;

        }


        .assessment-table tbody tr:last-child td {

            border-bottom: none;

        }


        .assessment-table tbody tr {

            transition:
                background 0.15s ease;

        }


        .assessment-table tbody tr:hover {

            background:
                var(--assessment-hover);

        }


        /* =====================================================
           ASSESSMENT NAME
           ===================================================== */

        .assessment-name {

            display: flex;

            align-items: center;

            gap: 10px;

            min-width: 210px;

        }


        .assessment-mini-icon {

            width: 36px;

            height: 36px;

            border-radius: 8px;

            background:
                rgba(11, 79, 138, 0.10);

            color:
                var(--assessment-primary);

            display: flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

        }


        .assessment-title-text {

            font-weight: 600;

            color:
                var(--assessment-text);

        }


        .assessment-code {

            margin-top: 2px;

            font-size: 0.70rem;

            color:
                var(--assessment-muted);

            font-family: monospace;

        }


        /* =====================================================
           ATTEMPT BADGE
           ===================================================== */

        .attempt-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 30px;

            height: 27px;

            padding: 0 8px;

            border-radius: 7px;

            background:
                var(--assessment-hover);

            border:
                1px solid var(--assessment-border);

            font-size: 0.76rem;

            font-weight: 600;

            color:
                var(--assessment-text);

        }


        /* =====================================================
           SCORE
           ===================================================== */

        .score-text {

            font-weight: 600;

            white-space: nowrap;

        }


        .percentage-text {

            color:
                var(--assessment-muted);

            font-size: 0.75rem;

            margin-left: 4px;

        }


        /* =====================================================
           STATUS
           ===================================================== */

        .status-badge {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 999px;

            font-size: 0.70rem;

            font-weight: 700;

        }


        .status-passed {

            color:
                var(--assessment-success);

            background:
                var(--assessment-success-bg);

        }


        .status-failed {

            color:
                var(--assessment-danger);

            background:
                var(--assessment-danger-bg);

        }


        /* =====================================================
           DATE
           ===================================================== */

        .date-text {

            color:
                var(--assessment-muted);

            white-space: nowrap;

            font-size: 0.78rem;

        }


        /* =====================================================
           EMPTY STATE
           ===================================================== */

        .empty-state {

            padding: 55px 25px;

            text-align: center;

        }


        .empty-icon {

            width: 58px;

            height: 58px;

            margin:
                0 auto 15px;

            border-radius: 12px;

            background:
                var(--assessment-hover);

            color:
                var(--assessment-muted);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.5rem;

        }


        .empty-state h4 {

            margin:
                0 0 7px;

            font-size: 0.98rem;

            font-weight: 700;

            color:
                var(--assessment-text);

        }


        .empty-state p {

            margin: 0 auto;

            max-width: 460px;

            color:
                var(--assessment-muted);

            font-size: 0.82rem;

            line-height: 1.6;

        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media (max-width: 768px) {

            .page-header {

                margin-bottom: 20px;

            }


            .page-header h2 {

                font-size: 1.35rem;

            }


            .test-code-card {

                padding: 18px;

            }


            .test-code-form {

                flex-direction: column;

            }


            .btn-start-assessment {

                width: 100%;

            }


            .assessment-stats {

                gap: 8px;

            }


            .stat-card {

                padding:
                    13px 10px;

            }


            .stat-label {

                font-size: 0.68rem;

            }


            .stat-value {

                font-size: 1.1rem;

            }

        }


        @media (max-width: 480px) {

            .assessment-stats {

                grid-template-columns: 1fr;

            }


            .section-header {

                align-items: flex-start;

                flex-direction: column;

            }

        }
    </style>

</head>


<body>


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <?php include 'globals/sidebar.php'; ?>


    <!-- =====================================================
         TOPBAR
         ===================================================== -->

    <?php include 'globals/topbar.php'; ?>


    <!-- =====================================================
         MAIN CONTENT
         ===================================================== -->

    <main class="main-content">

        <div class="content-wrapper">

            <div class="assessment-page">


                <!-- =================================================
                     PAGE HEADER
                     ================================================= -->

                <div class="page-header">

                    <div>

                        <h2>
                            Assessments
                        </h2>

                        <p>
                            Enter the test-code provided by your
                            instructor to access an assessment.
                        </p>

                    </div>

                </div>



                <!-- =================================================
                     ERROR MESSAGE
                     ================================================= -->

                <?php if ($assessment_error !== ""): ?>

                    <div
                        class="assessment-alert"
                        id="assessmentAlert"
                        role="alert">

                        <div class="assessment-alert-icon">

                            <i class="bi bi-exclamation-triangle-fill"></i>

                        </div>


                        <div class="assessment-alert-content">

                            <div class="assessment-alert-title">

                                Assessment Not Found

                            </div>


                            <div class="assessment-alert-message">

                                <?= e($assessment_error) ?>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="assessment-alert-close"
                            onclick="
                                document
                                    .getElementById('assessmentAlert')
                                    .remove();
                            "
                            aria-label="Close">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>

                <?php endif; ?>



                <!-- =================================================
                     TEST CODE ENTRY CARD
                     ================================================= -->

                <section class="test-code-card">


                    <div class="test-code-header">


                        <div class="test-code-icon">

                            <i class="bi bi-key"></i>

                        </div>


                        <div>

                            <h3 class="test-code-title">

                                Enter Test-Code

                            </h3>


                            <p class="test-code-description">

                                Use the test-code provided by your
                                instructor to start an assessment.

                            </p>

                        </div>


                    </div>



                    <!-- =================================================
                         TEST CODE FORM
                         ================================================= -->

                    <form
                        class="test-code-form"
                        method="POST"
                        action="assessment_start.php"
                        autocomplete="off">


                        <input
                            type="text"
                            name="test_code"
                            id="testCode"
                            class="test-code-input"
                            placeholder="Enter test-code"
                            maxlength="100"
                            required
                            spellcheck="false"
                            autocomplete="off">


                        <button
                            type="submit"
                            class="btn-start-assessment">

                            <i class="bi bi-play-fill"></i>

                            Start Assessment

                        </button>


                    </form>


                </section>



                <!-- =================================================
                     ASSESSMENT HISTORY
                     ================================================= -->

                <section class="history-section">


                    <div class="section-header">

                        <div>

                            <h3>
                                Assessment History
                            </h3>

                            <p>
                                Review your previous assessment
                                attempts and results.
                            </p>

                        </div>

                    </div>



                    <!-- =================================================
                         STATISTICS
                         ================================================= -->

                    <div class="assessment-stats">


                        <div class="stat-card">

                            <div class="stat-label">
                                Total Attempts
                            </div>

                            <div class="stat-value">

                                <?= e($total_attempts) ?>

                            </div>

                        </div>


                        <div class="stat-card">

                            <div class="stat-label">
                                Passed
                            </div>

                            <div class="stat-value">

                                <?= e($passed_attempts) ?>

                            </div>

                        </div>


                        <div class="stat-card">

                            <div class="stat-label">
                                Failed
                            </div>

                            <div class="stat-value">

                                <?= e($failed_attempts) ?>

                            </div>

                        </div>


                    </div>



                    <!-- =================================================
                         HISTORY TABLE
                         ================================================= -->

                    <div class="history-card">


                        <?php if (!empty($assessment_history)): ?>


                            <div class="table-responsive">


                                <table class="assessment-table">


                                    <thead>

                                        <tr>

                                            <th>
                                                Assessment
                                            </th>

                                            <th>
                                                Attempt
                                            </th>

                                            <th>
                                                Score
                                            </th>

                                            <th>
                                                Status
                                            </th>

                                            <th>
                                                Date
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        <?php foreach (
                                            $assessment_history
                                            as $attempt
                                        ): ?>


                                            <?php

                                            $title =
                                                !empty($attempt["assessment_title"])
                                                ? $attempt["assessment_title"]
                                                : "Assessment";


                                            $score =
                                                $attempt["score"] ?? 0;


                                            $total_items =
                                                $attempt["total_items"]
                                                ?? 0;


                                            $percentage =
                                                $attempt["percentage"]
                                                ?? 0;


                                            $status =
                                                trim(
                                                    $attempt["status"]
                                                        ?? "Failed"
                                                );


                                            $is_passed =
                                                strtolower($status)
                                                === "passed";


                                            $date =
                                                $attempt["submitted_at"]
                                                ?? $attempt["created_at"];

                                            ?>


                                            <tr>


                                                <!-- =========================
                                                     ASSESSMENT
                                                     ========================= -->

                                                <td>

                                                    <div
                                                        class="assessment-name">

                                                        <div
                                                            class="assessment-mini-icon">

                                                            <i
                                                                class="bi bi-journal-check"></i>

                                                        </div>


                                                        <div>

                                                            <div
                                                                class="assessment-title-text">

                                                                <?= e($title) ?>

                                                            </div>


                                                            <div
                                                                class="assessment-code">

                                                                <?= e(
                                                                    $attempt["test_code"]
                                                                        ?? ""
                                                                ) ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>



                                                <!-- =========================
                                                     ATTEMPT
                                                     ========================= -->

                                                <td>

                                                    <span
                                                        class="attempt-badge">

                                                        <?= e(
                                                            $attempt["attempt_number"]
                                                                ?? 1
                                                        ) ?>

                                                    </span>

                                                </td>



                                                <!-- =========================
                                                     SCORE
                                                     ========================= -->

                                                <td>

                                                    <span
                                                        class="score-text">

                                                        <?= e($score) ?>

                                                        /

                                                        <?= e($total_items) ?>

                                                    </span>


                                                    <span
                                                        class="percentage-text">

                                                        (
                                                        <?= number_format(
                                                            (float)$percentage,
                                                            2
                                                        ) ?>%
                                                        )

                                                    </span>

                                                </td>



                                                <!-- =========================
                                                     STATUS
                                                     ========================= -->

                                                <td>


                                                    <?php if ($is_passed): ?>


                                                        <span
                                                            class="status-badge status-passed">

                                                            <i
                                                                class="bi bi-check-circle-fill"></i>

                                                            Passed

                                                        </span>


                                                    <?php else: ?>


                                                        <span
                                                            class="status-badge status-failed">

                                                            <i
                                                                class="bi bi-x-circle-fill"></i>

                                                            Failed

                                                        </span>


                                                    <?php endif; ?>


                                                </td>



                                                <!-- =========================
                                                     DATE
                                                     ========================= -->

                                                <td>

                                                    <span
                                                        class="date-text">

                                                        <?= e(
                                                            formatAssessmentDate(
                                                                $date
                                                            )
                                                        ) ?>

                                                    </span>

                                                </td>


                                            </tr>


                                        <?php endforeach; ?>


                                    </tbody>


                                </table>


                            </div>


                        <?php else: ?>


                            <!-- =========================================
                                 EMPTY HISTORY
                                 ========================================= -->

                            <div class="empty-state">


                                <div class="empty-icon">

                                    <i class="bi bi-clipboard-x"></i>

                                </div>


                                <h4>

                                    No assessment history yet

                                </h4>


                                <p>

                                    Your completed assessments and
                                    previous attempts will appear here
                                    once you take your first assessment.

                                </p>


                            </div>


                        <?php endif; ?>


                    </div>


                </section>


            </div>

        </div>

    </main>



    <!-- =========================================================
         GLOBAL SCRIPTS
         ========================================================= -->

    <?php include 'globals/scripts.php'; ?>


    <!-- =========================================================
         TEST CODE SCRIPT
         ========================================================= -->

    <script>
        /*
         * -------------------------------------------------------
         * Trim whitespace from the test-code before submission.
         *
         * We do NOT automatically convert the code to uppercase.
         * -------------------------------------------------------
         */

        const testCodeInput =
            document.getElementById("testCode");


        if (testCodeInput) {

            testCodeInput.addEventListener(
                "blur",
                function() {

                    this.value =
                        this.value.trim();

                }
            );

        }
    </script>


</body>

</html>
