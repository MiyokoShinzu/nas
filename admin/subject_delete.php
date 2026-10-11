
<?php

/* =========================================================
   SUBJECT DELETE
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
   GET SUBJECT ID
========================================================== */

$subjectId = (int)($_GET["id"] ?? 0);


if ($subjectId <= 0) {

    header(
        "Location: subjects.php"
    );

    exit;
}


/* =========================================================
   LOAD SUBJECT
========================================================== */

$subject = null;


$sql = "
    SELECT
        id,
        subject_code,
        subject_name,
        description,
        status
    FROM subjects
    WHERE id = ?
    LIMIT 1
";


$stmt = $mysqli->prepare(
    $sql
);


if (!$stmt) {

    die("Unable to prepare the subject query. " .
        htmlspecialchars(
            $mysqli->error,
            ENT_QUOTES,
            "UTF-8"
        ));
}


$stmt->bind_param(
    "i",
    $subjectId
);


$stmt->execute();


$result =
    $stmt->get_result();


if ($result->num_rows === 0) {

    $stmt->close();

    header(
        "Location: subjects.php"
    );

    exit;
}


$subject =
    $result->fetch_assoc();


$stmt->close();


/* =========================================================
   DEPENDENCY COUNTS
========================================================== */

$topicCount = 0;

$questionCount = 0;

$ruleCount = 0;


/* =========================================================
   TOPIC COUNT
========================================================== */

$topicSql = "
    SELECT COUNT(*) AS total
    FROM subject_topics
    WHERE subject_id = ?
";


$topicStmt =
    $mysqli->prepare(
        $topicSql
    );


if ($topicStmt) {

    $topicStmt->bind_param(
        "i",
        $subjectId
    );

    $topicStmt->execute();

    $topicResult =
        $topicStmt->get_result();

    if ($topicRow =
        $topicResult->fetch_assoc()
    ) {

        $topicCount =
            (int)$topicRow["total"];
    }

    $topicStmt->close();
}


/* =========================================================
   QUESTION COUNT
========================================================== */

$questionSql = "
    SELECT COUNT(*) AS total
    FROM question_bank
    WHERE subject_id = ?
";


$questionStmt =
    $mysqli->prepare(
        $questionSql
    );


if ($questionStmt) {

    $questionStmt->bind_param(
        "i",
        $subjectId
    );

    $questionStmt->execute();

    $questionResult =
        $questionStmt->get_result();

    if ($questionRow =
        $questionResult->fetch_assoc()
    ) {

        $questionCount =
            (int)$questionRow["total"];
    }

    $questionStmt->close();
}


/* =========================================================
   ASSESSMENT RULE COUNT
========================================================== */

$ruleSql = "
    SELECT COUNT(*) AS total
    FROM assessment_question_rules
    WHERE subject_id = ?
";


$ruleStmt =
    $mysqli->prepare(
        $ruleSql
    );


if ($ruleStmt) {

    $ruleStmt->bind_param(
        "i",
        $subjectId
    );

    $ruleStmt->execute();

    $ruleResult =
        $ruleStmt->get_result();

    if ($ruleRow =
        $ruleResult->fetch_assoc()
    ) {

        $ruleCount =
            (int)$ruleRow["total"];
    }

    $ruleStmt->close();
}


/* =========================================================
   HANDLE DELETE
========================================================== */

$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $confirm =
        $_POST["confirm_delete"] ?? "";


    /* =====================================================
       REQUIRE EXPLICIT CONFIRMATION
    ====================================================== */

    if ($confirm !== "DELETE") {

        $error =
            "Please confirm the deletion before continuing.";
    }


    /* =====================================================
       BLOCK IF DEPENDENCIES EXIST
    ====================================================== */ elseif (
        $topicCount > 0 ||
        $questionCount > 0 ||
        $ruleCount > 0
    ) {

        $error =
            "This subject cannot be deleted because it is currently referenced by existing topics, questions, or assessment rules.";
    }


    /* =====================================================
       DELETE SUBJECT
    ====================================================== */ else {

        $deleteSql = "
            DELETE FROM subjects
            WHERE id = ?
        ";


        $deleteStmt =
            $mysqli->prepare(
                $deleteSql
            );


        if (!$deleteStmt) {

            $error =
                "Unable to prepare the delete query. " .
                $mysqli->error;
        } else {

            $deleteStmt->bind_param(
                "i",
                $subjectId
            );


            if ($deleteStmt->execute()) {

                $deleteStmt->close();


                header(
                    "Location: subjects.php"
                );

                exit;
            } else {

                $error =
                    "Unable to delete the subject. " .
                    $deleteStmt->error;

                $deleteStmt->close();
            }
        }
    }
}


/* =========================================================
   DETERMINE WHETHER DELETE IS ALLOWED
========================================================== */

$canDelete =
    (
        $topicCount === 0 &&
        $questionCount === 0 &&
        $ruleCount === 0
    );


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
           DELETE CARD
        ================================================== */

        .delete-card {

            border: 0;

            border-radius: 14px;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .07);

            overflow: hidden;

        }


        /* =================================================
           DELETE HEADER
        ================================================== */

        .delete-header {

            padding: 1.5rem;

            background: #fff5f5;

            border-bottom: 1px solid #f1c7c7;

        }


        .delete-icon {

            width: 58px;

            height: 58px;

            border-radius: 14px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #dc3545;

            background: rgba(220, 53, 69, .12);

            font-size: 1.5rem;

            margin-bottom: 1rem;

        }


        .delete-header h5 {

            margin: 0;

            font-weight: 700;

        }


        .delete-header p {

            margin: .35rem 0 0;

            color: #6c757d;

        }


        /* =================================================
           DELETE BODY
        ================================================== */

        .delete-body {

            padding: 1.5rem;

        }


        .subject-summary {

            padding: 1rem;

            border: 1px solid #dee2e6;

            border-radius: 12px;

            margin-bottom: 1.25rem;

        }


        .subject-code {

            display: inline-block;

            padding: .3rem .55rem;

            border-radius: 7px;

            color: #0d6efd;

            background: rgba(13, 110, 253, .1);

            font-size: .75rem;

            font-weight: 700;

            margin-bottom: .4rem;

        }


        .subject-name {

            font-size: 1.1rem;

            font-weight: 700;

        }


        /* =================================================
           DEPENDENCY CARDS
        ================================================== */

        .dependency-card {

            padding: 1rem;

            border: 1px solid #dee2e6;

            border-radius: 12px;

            height: 100%;

        }


        .dependency-label {

            color: #6c757d;

            font-size: .8rem;

            margin-bottom: .25rem;

        }


        .dependency-value {

            font-size: 1.4rem;

            font-weight: 700;

        }


        /* =================================================
           FORM FOOTER
        ================================================== */

        .delete-footer {

            padding: 1rem 1.5rem;

            background: #f8f9fa;

            border-top: 1px solid #dee2e6;

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 1rem;

        }


        /* =================================================
           DARK MODE
        ================================================== */

        html[data-theme="dark"] .page-header p {

            color: #adb5bd;

        }


        html[data-theme="dark"] .delete-card {

            background: #1e1e1e;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .25);

        }


        html[data-theme="dark"] .delete-header {

            background: #2b2021;

            border-color: #593033;

        }


        html[data-theme="dark"] .delete-header p {

            color: #adb5bd;

        }


        html[data-theme="dark"] .subject-summary,

        html[data-theme="dark"] .dependency-card {

            background: #252525;

            border-color: #3a3a3a;

        }


        html[data-theme="dark"] .dependency-label {

            color: #adb5bd;

        }


        html[data-theme="dark"] .delete-footer {

            background: #252525;

            border-color: #3a3a3a;

        }


        html[data-theme="dark"] .form-control {

            background: #2b2b2b;

            border-color: #444;

            color: #fff;

        }


        /* =================================================
           RESPONSIVE
        ================================================== */

        @media (max-width: 767.98px) {

            .delete-header,

            .delete-body {

                padding: 1rem;

            }


            .delete-footer {

                padding: 1rem;

                flex-direction: column-reverse;

                align-items: stretch;

            }


            .delete-footer .btn {

                width: 100%;

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

                <h1 class="h3">

                    <i class="bi bi-trash3 me-2"></i>

                    Delete Subject

                </h1>

                <p>

                    Review the subject before permanently removing it.

                </p>

            </div>


            <!-- =================================================
                 ERROR
            ================================================== -->

            <?php if ($error !== ""): ?>

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    <?= htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 DELETE CARD
            ================================================== -->

            <div class="card delete-card">


                <!-- =================================================
                     HEADER
                ================================================== -->

                <div class="delete-header">

                    <div class="delete-icon">

                        <i class="bi bi-trash3"></i>

                    </div>

                    <h5>

                        Confirm Subject Deletion

                    </h5>

                    <p>

                        Deleting a subject is permanent. Existing
                        topics, questions, and assessment rules must
                        be removed or reassigned first.

                    </p>

                </div>


                <!-- =================================================
                     BODY
                ================================================== -->

                <div class="delete-body">


                    <!-- =================================================
                         SUBJECT SUMMARY
                    ================================================== -->

                    <div class="subject-summary">

                        <div class="subject-code">

                            <?= htmlspecialchars(
                                $subject["subject_code"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </div>

                        <div class="subject-name">

                            <?= htmlspecialchars(
                                $subject["subject_name"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </div>

                    </div>


                    <!-- =================================================
                         DEPENDENCIES
                    ================================================== -->

                    <div class="row g-3 mb-4">


                        <!-- =============================================
                             TOPICS
                        ============================================== -->

                        <div class="col-12 col-md-4">

                            <div class="dependency-card">

                                <div class="dependency-label">

                                    Topics

                                </div>

                                <div class="dependency-value">

                                    <?= number_format(
                                        $topicCount
                                    ) ?>

                                </div>

                            </div>

                        </div>


                        <!-- =============================================
                             QUESTIONS
                        ============================================== -->

                        <div class="col-12 col-md-4">

                            <div class="dependency-card">

                                <div class="dependency-label">

                                    Questions

                                </div>

                                <div class="dependency-value">

                                    <?= number_format(
                                        $questionCount
                                    ) ?>

                                </div>

                            </div>

                        </div>


                        <!-- =============================================
                             ASSESSMENT RULES
                        ============================================== -->

                        <div class="col-12 col-md-4">

                            <div class="dependency-card">

                                <div class="dependency-label">

                                    Assessment Rules

                                </div>

                                <div class="dependency-value">

                                    <?= number_format(
                                        $ruleCount
                                    ) ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    <?php if (!$canDelete): ?>

                        <div class="alert alert-warning mb-0">

                            <i class="bi bi-info-circle-fill me-2"></i>

                            This subject currently has dependent
                            records. For data integrity, it cannot be
                            deleted. You can deactivate it instead.

                        </div>

                    <?php else: ?>


                        <!-- =================================================
                             CONFIRMATION
                        ================================================== -->

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-triangle-fill me-2"></i>

                            This subject has no dependent records and
                            can be permanently deleted.

                        </div>


                        <form
                            method="POST"
                            action="?id=<?= $subjectId ?>">

                            <div class="mb-3">

                                <label
                                    for="confirm_delete"
                                    class="form-label fw-semibold">

                                    Type

                                    <code>DELETE</code>

                                    to confirm

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="confirm_delete"
                                    name="confirm_delete"
                                    placeholder="DELETE"
                                    autocomplete="off"
                                    required>

                            </div>


                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="finalConfirm"
                                    required>

                                <label
                                    class="form-check-label"
                                    for="finalConfirm">

                                    I understand that this action
                                    permanently deletes the subject.

                                </label>

                            </div>


                            <div class="delete-footer mt-4">

                                <a
                                    href="subjects.php"
                                    class="btn btn-outline-secondary">

                                    <i class="bi bi-arrow-left me-1"></i>

                                    Cancel

                                </a>


                                <button
                                    type="submit"
                                    class="btn btn-danger">

                                    <i class="bi bi-trash3 me-1"></i>

                                    Permanently Delete

                                </button>

                            </div>

                        </form>

                    <?php endif; ?>


                    <?php if (!$canDelete): ?>

                        <div class="mt-4">

                            <a
                                href="subjects.php"
                                class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-left me-1"></i>

                                Return to Subjects

                            </a>

                            <a
                                href="subject_edit.php?id=<?= $subjectId ?>"
                                class="btn btn-primary">

                                <i class="bi bi-pencil me-1"></i>

                                Edit Subject

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </main>


    <!-- =====================================================
         GLOBAL SCRIPTS
    ====================================================== -->

    <?php include 'globals/scripts.php'; ?>

</body>

</html>
