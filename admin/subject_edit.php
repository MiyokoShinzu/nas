
<?php

/* =========================================================
   SUBJECT EDIT
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

$subjectCode = "";

$subjectName = "";

$description = "";

$status = "active";


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


$stmt = $mysqli->prepare($sql);


if (!$stmt) {

    $error =
        "Unable to prepare the subject query. " .
        $mysqli->error;
} else {

    $stmt->bind_param(
        "i",
        $subjectId
    );

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows === 0) {

        $stmt->close();

        header(
            "Location: subjects.php"
        );

        exit;
    }


    $subject = $result->fetch_assoc();


    $subjectCode =
        $subject["subject_code"];

    $subjectName =
        $subject["subject_name"];

    $description =
        $subject["description"] ?? "";

    $status =
        $subject["status"];


    $stmt->close();
}


/* =========================================================
   FORM SUBMISSION
========================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* =====================================================
       GET FORM VALUES
    ====================================================== */

    $subjectCode = strtoupper(
        trim($_POST["subject_code"] ?? "")
    );

    $subjectName = trim(
        $_POST["subject_name"] ?? ""
    );

    $description = trim(
        $_POST["description"] ?? ""
    );

    $status = $_POST["status"] ?? "active";


    /* =====================================================
       VALIDATE SUBJECT CODE
    ====================================================== */

    if ($subjectCode === "") {

        $error =
            "Please enter the subject code.";
    } elseif (
        !preg_match(
            '/^[A-Z0-9_-]{2,50}$/',
            $subjectCode
        )
    ) {

        $error =
            "Subject code may contain only letters, numbers, underscores, and hyphens.";
    }


    /* =====================================================
       VALIDATE SUBJECT NAME
    ====================================================== */ elseif ($subjectName === "") {

        $error =
            "Please enter the subject name.";
    } elseif (mb_strlen($subjectName) > 150) {

        $error =
            "Subject name must not exceed 150 characters.";
    }


    /* =====================================================
       VALIDATE DESCRIPTION
    ====================================================== */ elseif (mb_strlen($description) > 5000) {

        $error =
            "Description must not exceed 5000 characters.";
    }


    /* =====================================================
       VALIDATE STATUS
    ====================================================== */ elseif (
        !in_array(
            $status,
            ["active", "inactive"],
            true
        )
    ) {

        $error =
            "Invalid subject status selected.";
    }


    /* =====================================================
       CHECK DUPLICATE SUBJECT CODE
    ====================================================== */

    if ($error === "") {

        $checkSql = "
            SELECT id
            FROM subjects
            WHERE subject_code = ?
              AND id <> ?
            LIMIT 1
        ";

        $checkStmt = $mysqli->prepare(
            $checkSql
        );


        if (!$checkStmt) {

            $error =
                "Unable to prepare the subject-code validation query. " .
                $mysqli->error;
        } else {

            $checkStmt->bind_param(
                "si",
                $subjectCode,
                $subjectId
            );

            $checkStmt->execute();

            $checkStmt->store_result();


            if ($checkStmt->num_rows > 0) {

                $error =
                    "Another subject already uses this subject code.";
            }


            $checkStmt->close();
        }
    }


    /* =====================================================
       CHECK DUPLICATE SUBJECT NAME
    ====================================================== */

    if ($error === "") {

        $checkSql = "
            SELECT id
            FROM subjects
            WHERE LOWER(subject_name) = LOWER(?)
              AND id <> ?
            LIMIT 1
        ";

        $checkStmt = $mysqli->prepare(
            $checkSql
        );


        if (!$checkStmt) {

            $error =
                "Unable to prepare the subject-name validation query. " .
                $mysqli->error;
        } else {

            $checkStmt->bind_param(
                "si",
                $subjectName,
                $subjectId
            );

            $checkStmt->execute();

            $checkStmt->store_result();


            if ($checkStmt->num_rows > 0) {

                $error =
                    "Another subject already uses this name.";
            }


            $checkStmt->close();
        }
    }


    /* =====================================================
       UPDATE SUBJECT
    ====================================================== */

    if ($error === "") {

        $updateSql = "
            UPDATE subjects
            SET
                subject_code = ?,
                subject_name = ?,
                description = ?,
                status = ?
            WHERE id = ?
        ";

        $updateStmt = $mysqli->prepare(
            $updateSql
        );


        if (!$updateStmt) {

            $error =
                "Unable to prepare the subject update query. " .
                $mysqli->error;
        } else {

            $updateStmt->bind_param(
                "ssssi",
                $subjectCode,
                $subjectName,
                $description,
                $status,
                $subjectId
            );


            if ($updateStmt->execute()) {

                $success =
                    "Subject updated successfully.";
            } else {

                $error =
                    "Unable to update the subject. " .
                    $updateStmt->error;
            }


            $updateStmt->close();
        }
    }
}


/* =========================================================
   LOAD CURRENT COUNTS
========================================================== */

$topicCount = 0;

$questionCount = 0;


$countSql = "
    SELECT
        (
            SELECT COUNT(*)
            FROM subject_topics
            WHERE subject_id = ?
        ) AS topic_count,

        (
            SELECT COUNT(*)
            FROM question_bank
            WHERE subject_id = ?
        ) AS question_count
";


$countStmt = $mysqli->prepare(
    $countSql
);


if ($countStmt) {

    $countStmt->bind_param(
        "ii",
        $subjectId,
        $subjectId
    );

    $countStmt->execute();

    $countResult =
        $countStmt->get_result();

    if ($countRow = $countResult->fetch_assoc()) {

        $topicCount =
            (int)$countRow["topic_count"];

        $questionCount =
            (int)$countRow["question_count"];
    }

    $countStmt->close();
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
           FORM CARD
        ================================================== */

        .form-card {

            border: 0;

            border-radius: 14px;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .07);

            overflow: hidden;

        }


        /* =================================================
           CARD HEADER
        ================================================== */

        .form-card-header {

            padding: 1.25rem 1.5rem;

            background: #f8f9fa;

            border-bottom: 1px solid #dee2e6;

        }


        .form-card-header h5 {

            margin: 0;

            font-weight: 700;

        }


        .form-card-header p {

            margin: .25rem 0 0;

            color: #6c757d;

            font-size: .9rem;

        }


        /* =================================================
           FORM BODY
        ================================================== */

        .form-card-body {

            padding: 1.5rem;

        }


        /* =================================================
           FORM LABELS
        ================================================== */

        .form-label {

            font-weight: 600;

            margin-bottom: .45rem;

        }


        .required-mark {

            color: #dc3545;

        }


        .form-text {

            font-size: .82rem;

        }


        /* =================================================
           INFORMATION CARDS
        ================================================== */

        .info-card {

            border: 1px solid #dee2e6;

            border-radius: 12px;

            padding: 1rem;

            height: 100%;

            background: #f8f9fa;

        }


        .info-label {

            color: #6c757d;

            font-size: .78rem;

            margin-bottom: .2rem;

        }


        .info-value {

            font-size: 1.3rem;

            font-weight: 700;

        }


        /* =================================================
           FORM FOOTER
        ================================================== */

        .form-card-footer {

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


        html[data-theme="dark"] .form-card {

            background: #1e1e1e;

            box-shadow:
                0 4px 18px rgba(0, 0, 0, .25);

        }


        html[data-theme="dark"] .form-card-header,

        html[data-theme="dark"] .form-card-footer {

            background: #252525;

            border-color: #3a3a3a;

        }


        html[data-theme="dark"] .form-card-header p,

        html[data-theme="dark"] .form-text {

            color: #adb5bd;

        }


        html[data-theme="dark"] .form-control,

        html[data-theme="dark"] .form-select {

            background-color: #2b2b2b;

            border-color: #444;

            color: #f8f9fa;

        }


        html[data-theme="dark"] .form-control::placeholder {

            color: #888;

        }


        html[data-theme="dark"] .form-control:focus,

        html[data-theme="dark"] .form-select:focus {

            background-color: #2b2b2b;

            color: #fff;

            border-color: #0d6efd;

        }


        html[data-theme="dark"] .info-card {

            background: #252525;

            border-color: #3a3a3a;

        }


        html[data-theme="dark"] .info-label {

            color: #adb5bd;

        }


        /* =================================================
           RESPONSIVE
        ================================================== */

        @media (max-width: 767.98px) {

            .form-card-body {

                padding: 1rem;

            }


            .form-card-header {

                padding: 1rem;

            }


            .form-card-footer {

                padding: 1rem;

                flex-direction: column-reverse;

                align-items: stretch;

            }


            .form-card-footer .btn {

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

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <h1 class="h3">

                            <i class="bi bi-pencil-square me-2"></i>

                            Edit Subject

                        </h1>

                        <p>

                            Update the subject information and availability.

                        </p>

                    </div>


                    <div class="d-flex gap-2">

                        <a
                            href="topics.php?subject_id=<?= $subjectId ?>"
                            class="btn btn-outline-info">

                            <i class="bi bi-diagram-3 me-1"></i>

                            Manage Topics

                        </a>

                        <a
                            href="subjects.php"
                            class="btn btn-outline-primary">

                            <i class="bi bi-arrow-left me-1"></i>

                            Manage Subjects

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

                    <?= htmlspecialchars(
                        $success,
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
                 ERROR MESSAGE
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
                 STATISTICS
            ================================================== -->

            <div class="row g-3 mb-4">


                <!-- =================================================
                     TOPICS
                ================================================== -->

                <div class="col-6 col-md-3">

                    <div class="info-card">

                        <div class="info-label">

                            Topics

                        </div>

                        <div class="info-value">

                            <?= number_format($topicCount) ?>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     QUESTIONS
                ================================================== -->

                <div class="col-6 col-md-3">

                    <div class="info-card">

                        <div class="info-label">

                            Questions

                        </div>

                        <div class="info-value">

                            <?= number_format($questionCount) ?>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     STATUS
                ================================================== -->

                <div class="col-6 col-md-3">

                    <div class="info-card">

                        <div class="info-label">

                            Current Status

                        </div>

                        <div class="info-value">

                            <?= ucfirst(
                                htmlspecialchars(
                                    $status,
                                    ENT_QUOTES,
                                    "UTF-8"
                                )
                            ) ?>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     SUBJECT ID
                ================================================== -->

                <div class="col-6 col-md-3">

                    <div class="info-card">

                        <div class="info-label">

                            Subject ID

                        </div>

                        <div class="info-value">

                            #<?= $subjectId ?>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 FORM CARD
            ================================================== -->

            <div class="card form-card">


                <!-- =================================================
                     CARD HEADER
                ================================================== -->

                <div class="form-card-header">

                    <h5>

                        <i class="bi bi-book-half me-2"></i>

                        Subject Information

                    </h5>

                    <p>

                        Modify the information stored in the
                        normalized subject table.

                    </p>

                </div>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    method="POST"
                    action="?id=<?= $subjectId ?>">


                    <!-- =================================================
                         FORM BODY
                    ================================================== -->

                    <div class="form-card-body">

                        <div class="row g-4">


                            <!-- =================================================
                                 SUBJECT CODE
                            ================================================== -->

                            <div class="col-md-4">

                                <label
                                    for="subject_code"
                                    class="form-label">

                                    Subject Code

                                    <span class="required-mark">*</span>

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="subject_code"
                                    name="subject_code"
                                    value="<?= htmlspecialchars(
                                                $subjectCode,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>"
                                    maxlength="50"
                                    required>

                                <div class="form-text">

                                    Example:

                                    <code>DSP</code>

                                </div>

                            </div>


                            <!-- =================================================
                                 SUBJECT NAME
                            ================================================== -->

                            <div class="col-md-8">

                                <label
                                    for="subject_name"
                                    class="form-label">

                                    Subject Name

                                    <span class="required-mark">*</span>

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="subject_name"
                                    name="subject_name"
                                    value="<?= htmlspecialchars(
                                                $subjectName,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>"
                                    maxlength="150"
                                    required>

                            </div>


                            <!-- =================================================
                                 DESCRIPTION
                            ================================================== -->

                            <div class="col-12">

                                <label
                                    for="description"
                                    class="form-label">

                                    Description

                                </label>

                                <textarea
                                    class="form-control"
                                    id="description"
                                    name="description"
                                    rows="5"
                                    maxlength="5000"><?= htmlspecialchars(
                                                            $description,
                                                            ENT_QUOTES,
                                                            "UTF-8"
                                                        ) ?></textarea>

                            </div>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <div class="col-md-4">

                                <label
                                    for="status"
                                    class="form-label">

                                    Status

                                </label>

                                <select
                                    class="form-select"
                                    id="status"
                                    name="status">

                                    <option
                                        value="active"
                                        <?= $status === "active"
                                            ? "selected"
                                            : "" ?>>

                                        Active

                                    </option>

                                    <option
                                        value="inactive"
                                        <?= $status === "inactive"
                                            ? "selected"
                                            : "" ?>>

                                        Inactive

                                    </option>

                                </select>

                                <div class="form-text">

                                    Inactive subjects remain in the
                                    database but should not be offered
                                    for new content.

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         FORM FOOTER
                    ================================================== -->

                    <div class="form-card-footer">

                        <a
                            href="subjects.php"
                            class="btn btn-outline-secondary">

                            <i class="bi bi-x-circle me-1"></i>

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-save me-1"></i>

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>


    <!-- =====================================================
         GLOBAL SCRIPTS
    ====================================================== -->

    <?php include 'globals/scripts.php'; ?>

</body>

</html>
