
<?php

/* =========================================================
   SUBJECT MANAGEMENT
   ETS-Async Learning Portal
   ========================================================= */


/* =========================================================
   ADMIN AUTHENTICATION
========================================================== */

include './globals/checks.php';


/* =========================================================
   SESSION USER
========================================================== */

$user = $_SESSION["user"];

$adminId = $user["id"] ?? null;


/* =========================================================
   DATABASE CONNECTION
========================================================== */

require_once "../src/connection.php";


/* =========================================================
   PAGE VARIABLES
========================================================== */

$error   = "";
$success = "";


/* =========================================================
   HANDLE STATUS TOGGLE
   ---------------------------------------------------------
   This allows the administrator to activate or deactivate
   a subject directly from the management page.
========================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    $subjectId = (int)($_POST["subject_id"] ?? 0);


    /* =====================================================
       TOGGLE SUBJECT STATUS
    ====================================================== */

    if ($action === "toggle_status" && $subjectId > 0) {

        $sql = "
            UPDATE subjects
            SET status = CASE
                WHEN status = 'active'
                THEN 'inactive'
                ELSE 'active'
            END
            WHERE id = ?
        ";

        $stmt = $mysqli->prepare($sql);


        /* =================================================
           CHECK PREPARED STATEMENT
        ================================================== */

        if (!$stmt) {

            $error =
                "Unable to prepare the status update query. " .
                $mysqli->error;
        } else {

            $stmt->bind_param(
                "i",
                $subjectId
            );


            /* =============================================
               EXECUTE
            ============================================== */

            if ($stmt->execute()) {

                $success =
                    "Subject status updated successfully.";
            } else {

                $error =
                    "Unable to update the subject status. " .
                    $stmt->error;
            }


            /* =============================================
               CLOSE STATEMENT
            ============================================== */

            $stmt->close();
        }
    }
}


/* =========================================================
   LOAD SUBJECTS
   ---------------------------------------------------------
   The topic and question counts are calculated using
   correlated subqueries so the administrator can immediately
   see how much content belongs to each subject.
========================================================== */

$subjects = [];


$subjectQuery = "
    SELECT
        s.id,
        s.subject_code,
        s.subject_name,
        s.description,
        s.status,
        s.created_at,
        s.updated_at,

        (
            SELECT COUNT(*)
            FROM subject_topics t
            WHERE t.subject_id = s.id
        ) AS topic_count,

        (
            SELECT COUNT(*)
            FROM question_bank q
            WHERE q.subject_id = s.id
        ) AS question_count

    FROM subjects s

    ORDER BY
        CASE
            WHEN s.status = 'active' THEN 0
            ELSE 1
        END,
        s.subject_name ASC
";


$subjectResult = $mysqli->query(
    $subjectQuery
);


if ($subjectResult) {

    while ($row = $subjectResult->fetch_assoc()) {

        $subjects[] = $row;
    }
} else {

    $error =
        "Unable to load subjects. " .
        $mysqli->error;
}


/* =========================================================
   STATISTICS
========================================================== */

$totalSubjects  = count($subjects);

$activeSubjects = 0;

$inactiveSubjects = 0;

$totalTopics = 0;

$totalQuestions = 0;


foreach ($subjects as $subject) {

    if ($subject["status"] === "active") {

        $activeSubjects++;
    } else {

        $inactiveSubjects++;
    }


    $totalTopics +=
        (int)$subject["topic_count"];


    $totalQuestions +=
        (int)$subject["question_count"];
}


/* =========================================================
   PAGE
========================================================== */

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <?php include 'globals/head.php'; ?>


    <style>
        /* =================================================
           PAGE HEADER
        ================================================== */

        .page-header {

            margin-bottom: 1.5rem;

        }


        .page-header h1 {

            font-weight: 700;

            margin-bottom: .35rem;

        }


        .page-header p {

            margin-bottom: 0;

            color: #6c757d;

        }


        /* =================================================
           STATISTICS CARDS
        ================================================== */

        .stat-card {

            border: 0;

            border-radius: 14px;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .07);

            height: 100%;

        }


        .stat-card-body {

            padding: 1.25rem;

            display: flex;

            align-items: center;

            gap: 1rem;

        }


        .stat-icon {

            width: 52px;

            height: 52px;

            min-width: 52px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.35rem;

        }


        .stat-icon-blue {

            color: #0d6efd;

            background: rgba(13, 110, 253, .12);

        }


        .stat-icon-green {

            color: #198754;

            background: rgba(25, 135, 84, .12);

        }


        .stat-icon-orange {

            color: #fd7e14;

            background: rgba(253, 126, 20, .12);

        }


        .stat-icon-purple {

            color: #6f42c1;

            background: rgba(111, 66, 193, .12);

        }


        .stat-label {

            color: #6c757d;

            font-size: .82rem;

            margin-bottom: .15rem;

        }


        .stat-value {

            font-size: 1.5rem;

            font-weight: 700;

            line-height: 1.2;

        }


        /* =================================================
           CONTENT CARD
        ================================================== */

        .content-card {

            border: 0;

            border-radius: 14px;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .07);

            overflow: hidden;

        }


        /* =================================================
           CONTENT CARD HEADER
        ================================================== */

        .content-card-header {

            padding: 1.25rem 1.5rem;

            background: #f8f9fa;

            border-bottom: 1px solid #dee2e6;

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 1rem;

        }


        .content-card-header h5 {

            margin: 0;

            font-weight: 700;

        }


        .content-card-header p {

            margin: .25rem 0 0;

            color: #6c757d;

            font-size: .9rem;

        }


        /* =================================================
           TABLE
        ================================================== */

        .table {

            margin-bottom: 0;

        }


        .table thead th {

            font-size: .78rem;

            text-transform: uppercase;

            letter-spacing: .03em;

            color: #6c757d;

            white-space: nowrap;

            background: #f8f9fa;

            border-bottom-width: 1px;

        }


        .table tbody td {

            vertical-align: middle;

        }


        .subject-name {

            font-weight: 700;

        }


        .subject-code {

            font-size: .78rem;

            font-weight: 600;

            color: #0d6efd;

            margin-top: .15rem;

        }


        .subject-description {

            color: #6c757d;

            font-size: .84rem;

            max-width: 420px;

        }


        /* =================================================
           COUNT BADGES
        ================================================== */

        .count-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 34px;

            height: 28px;

            padding: 0 .55rem;

            border-radius: 8px;

            font-size: .8rem;

            font-weight: 700;

        }


        .count-topics {

            color: #0d6efd;

            background: rgba(13, 110, 253, .1);

        }


        .count-questions {

            color: #6f42c1;

            background: rgba(111, 66, 193, .1);

        }


        /* =================================================
           STATUS BADGES
        ================================================== */

        .status-badge {

            display: inline-flex;

            align-items: center;

            gap: .35rem;

            padding: .35rem .65rem;

            border-radius: 50rem;

            font-size: .75rem;

            font-weight: 600;

        }


        .status-active {

            color: #198754;

            background: rgba(25, 135, 84, .12);

        }


        .status-inactive {

            color: #6c757d;

            background: rgba(108, 117, 125, .12);

        }


        /* =================================================
           ACTION BUTTONS
        ================================================== */

        .action-buttons {

            display: flex;

            justify-content: flex-end;

            gap: .35rem;

        }


        .action-buttons .btn {

            width: 34px;

            height: 34px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 0;

            border-radius: 8px;

        }


        /* =================================================
           EMPTY STATE
        ================================================== */

        .empty-state {

            padding: 4rem 1.5rem;

            text-align: center;

        }


        .empty-state-icon {

            width: 70px;

            height: 70px;

            margin: 0 auto 1rem;

            border-radius: 16px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 2rem;

            color: #0d6efd;

            background: rgba(13, 110, 253, .1);

        }


        .empty-state h5 {

            font-weight: 700;

            margin-bottom: .5rem;

        }


        .empty-state p {

            color: #6c757d;

            max-width: 520px;

            margin: 0 auto 1.25rem;

        }


        /* =================================================
           DARK MODE
        ================================================== */

        html[data-theme="dark"] .page-header p {

            color: #adb5bd;

        }


        html[data-theme="dark"] .stat-card,

        html[data-theme="dark"] .content-card {

            background: #1e1e1e;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .25);

        }


        html[data-theme="dark"] .stat-label,

        html[data-theme="dark"] .subject-description {

            color: #adb5bd;

        }


        html[data-theme="dark"] .content-card-header,

        html[data-theme="dark"] .table thead th {

            background: #252525;

            border-color: #3a3a3a;

        }


        html[data-theme="dark"] .content-card-header p {

            color: #adb5bd;

        }


        html[data-theme="dark"] .table {

            --bs-table-bg: #1e1e1e;

            --bs-table-color: #f8f9fa;

            --bs-table-border-color: #3a3a3a;

        }


        html[data-theme="dark"] .table thead th {

            color: #adb5bd;

        }


        html[data-theme="dark"] .empty-state p {

            color: #adb5bd;

        }


        /* =================================================
           RESPONSIVE
        ================================================== */

        @media (max-width: 767.98px) {

            .content-card-header {

                padding: 1rem;

                flex-direction: column;

                align-items: stretch;

            }


            .content-card-header .btn {

                width: 100%;

            }


            .table-responsive {

                border-radius: 0;

            }


            .action-buttons {

                justify-content: flex-start;

            }

        }
    </style>

</head>


<body>


    <?php include 'globals/sidebar.php'; ?>

    <?php include 'globals/topbar.php'; ?>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">

        <div class="container-fluid py-4">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="page-header">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <h1 class="h3">

                            <i class="bi bi-book me-2"></i>

                            Subjects

                        </h1>

                        <p>

                            Manage the subjects used by the ETS-Async
                            question bank and assessments.

                        </p>

                    </div>


                    <div>

                        <a
                            href="subject_create.php"
                            class="btn btn-primary">

                            <i class="bi bi-plus-circle me-1"></i>

                            Create Subject

                        </a>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 SUCCESS MESSAGE
            ================================================== -->

            <?php if ($success !== ""): ?>

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    <?= htmlspecialchars($success, ENT_QUOTES, "UTF-8") ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 ERROR MESSAGE
            ================================================== -->

            <?php if ($error !== ""): ?>

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 STATISTICS
            ================================================== -->

            <div class="row g-3 mb-4">


                <!-- =================================================
                     TOTAL SUBJECTS
                ================================================== -->

                <div class="col-6 col-xl-3">

                    <div class="card stat-card">

                        <div class="stat-card-body">

                            <div class="stat-icon stat-icon-blue">

                                <i class="bi bi-book"></i>

                            </div>

                            <div>

                                <div class="stat-label">

                                    Total Subjects

                                </div>

                                <div class="stat-value">

                                    <?= number_format($totalSubjects) ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     ACTIVE SUBJECTS
                ================================================== -->

                <div class="col-6 col-xl-3">

                    <div class="card stat-card">

                        <div class="stat-card-body">

                            <div class="stat-icon stat-icon-green">

                                <i class="bi bi-check-circle"></i>

                            </div>

                            <div>

                                <div class="stat-label">

                                    Active

                                </div>

                                <div class="stat-value">

                                    <?= number_format($activeSubjects) ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     TOPICS
                ================================================== -->

                <div class="col-6 col-xl-3">

                    <div class="card stat-card">

                        <div class="stat-card-body">

                            <div class="stat-icon stat-icon-orange">

                                <i class="bi bi-diagram-3"></i>

                            </div>

                            <div>

                                <div class="stat-label">

                                    Topics

                                </div>

                                <div class="stat-value">

                                    <?= number_format($totalTopics) ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     QUESTIONS
                ================================================== -->

                <div class="col-6 col-xl-3">

                    <div class="card stat-card">

                        <div class="stat-card-body">

                            <div class="stat-icon stat-icon-purple">

                                <i class="bi bi-question-circle"></i>

                            </div>

                            <div>

                                <div class="stat-label">

                                    Questions

                                </div>

                                <div class="stat-value">

                                    <?= number_format($totalQuestions) ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 SUBJECT TABLE CARD
            ================================================== -->

            <div class="card content-card">


                <!-- =================================================
                     CARD HEADER
                ================================================== -->

                <div class="content-card-header">

                    <div>

                        <h5>

                            <i class="bi bi-list-ul me-2"></i>

                            Subject List

                        </h5>

                        <p>

                            Subjects available for question-bank
                            organization and assessment creation.

                        </p>

                    </div>


                    <a
                        href="subject_create.php"
                        class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Subject

                    </a>

                </div>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <?php if (!empty($subjects)): ?>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>

                                <tr>

                                    <th class="ps-4">

                                        Subject

                                    </th>

                                    <th>

                                        Topics

                                    </th>

                                    <th>

                                        Questions

                                    </th>

                                    <th>

                                        Status

                                    </th>

                                    <th class="text-end pe-4">

                                        Actions

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($subjects as $subject): ?>

                                    <tr>


                                        <!-- =================================
                                             SUBJECT
                                        ================================== -->

                                        <td class="ps-4">

                                            <div class="subject-name">

                                                <?= htmlspecialchars(
                                                    $subject["subject_name"],
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                ) ?>

                                            </div>

                                            <div class="subject-code">

                                                <?= htmlspecialchars(
                                                    $subject["subject_code"],
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                ) ?>

                                            </div>

                                            <?php if (
                                                trim((string)$subject["description"]) !== ""
                                            ): ?>

                                                <div class="subject-description mt-1">

                                                    <?= htmlspecialchars(
                                                        $subject["description"],
                                                        ENT_QUOTES,
                                                        "UTF-8"
                                                    ) ?>

                                                </div>

                                            <?php endif; ?>

                                        </td>


                                        <!-- =================================
                                             TOPICS
                                        ================================== -->

                                        <td>

                                            <span
                                                class="count-badge count-topics">

                                                <?= number_format(
                                                    (int)$subject["topic_count"]
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- =================================
                                             QUESTIONS
                                        ================================== -->

                                        <td>

                                            <span
                                                class="count-badge count-questions">

                                                <?= number_format(
                                                    (int)$subject["question_count"]
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- =================================
                                             STATUS
                                        ================================== -->

                                        <td>

                                            <?php if (
                                                $subject["status"] === "active"
                                            ): ?>

                                                <span
                                                    class="status-badge status-active">

                                                    <i class="bi bi-check-circle-fill"></i>

                                                    Active

                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="status-badge status-inactive">

                                                    <i class="bi bi-dash-circle-fill"></i>

                                                    Inactive

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- =================================
                                             ACTIONS
                                        ================================== -->

                                        <td class="pe-4">

                                            <div class="action-buttons">


                                                <!-- =================================
                                                     TOPICS
                                                ================================== -->

                                                <a
                                                    href="topics.php?subject_id=<?= (int)$subject["id"] ?>"
                                                    class="btn btn-outline-info"
                                                    title="Manage Topics">

                                                    <i class="bi bi-diagram-3"></i>

                                                </a>


                                                <!-- =================================
                                                     EDIT
                                                ================================== -->

                                                <a
                                                    href="subject_edit.php?id=<?= (int)$subject["id"] ?>"
                                                    class="btn btn-outline-primary"
                                                    title="Edit Subject">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                <!-- =================================
                                                     STATUS
                                                ================================== -->

                                                <form
                                                    method="POST"
                                                    action=""
                                                    class="d-inline">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="toggle_status">

                                                    <input
                                                        type="hidden"
                                                        name="subject_id"
                                                        value="<?= (int)$subject["id"] ?>">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-warning"
                                                        title="<?= $subject["status"] === "active" ? "Deactivate Subject" : "Activate Subject" ?>">

                                                        <?php if (
                                                            $subject["status"] === "active"
                                                        ): ?>

                                                            <i class="bi bi-pause-circle"></i>

                                                        <?php else: ?>

                                                            <i class="bi bi-play-circle"></i>

                                                        <?php endif; ?>

                                                    </button>

                                                </form>


                                                <!-- =================================
                                                     DELETE
                                                ================================== -->

                                                <a
                                                    href="subject_delete.php?id=<?= (int)$subject["id"] ?>"
                                                    class="btn btn-outline-danger"
                                                    title="Delete Subject"
                                                    onclick="return confirm('Are you sure you want to delete this subject? Subjects with existing topics or questions may not be deleted.');">

                                                    <i class="bi bi-trash"></i>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>


                    <!-- =================================================
                         EMPTY STATE
                    ================================================== -->

                    <div class="empty-state">

                        <div class="empty-state-icon">

                            <i class="bi bi-book"></i>

                        </div>

                        <h5>

                            No subjects yet

                        </h5>

                        <p>

                            Create your first subject to begin organizing
                            topics and questions in the ETS-Async
                            question bank.

                        </p>

                        <a
                            href="subject_create.php"
                            class="btn btn-primary">

                            <i class="bi bi-plus-circle me-1"></i>

                            Create First Subject

                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </main>


    <!-- =====================================================
         GLOBAL SCRIPTS
    ====================================================== -->

    <?php include 'globals/scripts.php'; ?>

</body>

</html>
