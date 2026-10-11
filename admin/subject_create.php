
<?php

/* =========================================================
   SUBJECT CREATE
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
   FORM DEFAULT VALUES
========================================================== */

$subjectCode = "";

$subjectName = "";

$description = "";

$status = "active";


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
    ====================================================== */

    elseif ($subjectName === "") {

        $error =
            "Please enter the subject name.";

    } elseif (mb_strlen($subjectName) > 150) {

        $error =
            "Subject name must not exceed 150 characters.";
    }


    /* =====================================================
       VALIDATE DESCRIPTION
    ====================================================== */

    elseif (mb_strlen($description) > 5000) {

        $error =
            "Description must not exceed 5000 characters.";
    }


    /* =====================================================
       VALIDATE STATUS
    ====================================================== */

    elseif (
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

    else {

        $checkSql = "
            SELECT id
            FROM subjects
            WHERE subject_code = ?
            LIMIT 1
        ";

        $checkStmt = $mysqli->prepare(
            $checkSql
        );


        if (!$checkStmt) {

            $error =
                "Unable to prepare the duplicate-check query. " .
                $mysqli->error;

        } else {

            $checkStmt->bind_param(
                "s",
                $subjectCode
            );

            $checkStmt->execute();

            $checkStmt->store_result();


            if ($checkStmt->num_rows > 0) {

                $error =
                    "A subject with this subject code already exists.";
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
                "s",
                $subjectName
            );

            $checkStmt->execute();

            $checkStmt->store_result();


            if ($checkStmt->num_rows > 0) {

                $error =
                    "A subject with this name already exists.";
            }


            $checkStmt->close();
        }
    }


    /* =====================================================
       INSERT SUBJECT
    ====================================================== */

    if ($error === "") {

        $sql = "
            INSERT INTO subjects (
                subject_code,
                subject_name,
                description,
                status
            )
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $mysqli->prepare(
            $sql
        );


        /* =================================================
           CHECK PREPARED STATEMENT
        ================================================== */

        if (!$stmt) {

            $error =
                "Unable to prepare the subject creation query. " .
                $mysqli->error;

        } else {

            /* =============================================
               BIND VALUES
            ============================================== */

            $stmt->bind_param(
                "ssss",
                $subjectCode,
                $subjectName,
                $description,
                $status
            );


            /* =============================================
               EXECUTE
            ============================================== */

            if ($stmt->execute()) {

                $success =
                    "Subject created successfully.";


                /* =========================================
                   RESET FORM
                ========================================== */

                $subjectCode = "";

                $subjectName = "";

                $description = "";

                $status = "active";

            } else {

                $error =
                    "Unable to create the subject. " .
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
           FORM CARD HEADER
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


        /* =================================================
           FORM TEXT
        ================================================== */

        .form-text {

            font-size: .82rem;

        }


        /* =================================================
           SUBJECT PREVIEW
        ================================================== */

        .subject-preview {

            border: 1px solid #dee2e6;

            border-radius: 12px;

            padding: 1rem;

            background: #f8f9fa;

            margin-top: 1rem;

        }


        .preview-code {

            display: inline-block;

            padding: .3rem .55rem;

            border-radius: 7px;

            font-size: .75rem;

            font-weight: 700;

            color: #0d6efd;

            background: rgba(13, 110, 253, .1);

            margin-bottom: .5rem;

        }


        .preview-name {

            font-weight: 700;

            font-size: 1.05rem;

        }


        .preview-description {

            margin-top: .3rem;

            color: #6c757d;

            font-size: .85rem;

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


        html[data-theme="dark"] .subject-preview {

            background: #252525;

            border-color: #3a3a3a;

        }


        html[data-theme="dark"] .preview-description {

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

                            <i class="bi bi-plus-circle me-2"></i>

                            Create Subject

                        </h1>

                        <p>

                            Add a subject that can be used to organize
                            topics, questions, and assessments.

                        </p>

                    </div>


                    <div>

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
                 FORM CARD
            ================================================== -->

            <div class="card form-card">


                <!-- =================================================
                     CARD HEADER
                ================================================== -->

                <div class="form-card-header">

                    <h5>

                        <i class="bi bi-book me-2"></i>

                        Subject Information

                    </h5>

                    <p>

                        Enter the basic information for the subject.

                    </p>

                </div>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    method="POST"
                    action="">


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

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-upc-scan"></i>

                                    </span>

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
                                        placeholder="e.g. DSP"
                                        maxlength="50"
                                        required>

                                </div>

                                <div class="form-text">

                                    Use a short unique code such as
                                    <code>DSP</code>.

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

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-book"></i>

                                    </span>

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
                                        placeholder="e.g. Digital Signal Processing"
                                        maxlength="150"
                                        required>

                                </div>

                                <div class="form-text">

                                    Enter the formal subject name.

                                </div>

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
                                    maxlength="5000"
                                    placeholder="Describe the subject and the type of learning content it contains..."><?= htmlspecialchars(
                                        $description,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?></textarea>

                                <div class="form-text">

                                    This description is for administrative
                                    organization and can be displayed in
                                    future assessment interfaces.

                                </div>

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

                                    Active subjects can be used when
                                    creating questions and assessments.

                                </div>

                            </div>


                            <!-- =================================================
                                 PREVIEW
                            ================================================== -->

                            <div class="col-md-8">

                                <div class="subject-preview">

                                    <div
                                        id="previewCode"
                                        class="preview-code">

                                        <?= htmlspecialchars(
                                            $subjectCode !== ""
                                                ? $subjectCode
                                                : "SUBJECT CODE",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </div>

                                    <div
                                        id="previewName"
                                        class="preview-name">

                                        <?= htmlspecialchars(
                                            $subjectName !== ""
                                                ? $subjectName
                                                : "Subject Name",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </div>

                                    <div
                                        id="previewDescription"
                                        class="preview-description">

                                        <?= htmlspecialchars(
                                            $description !== ""
                                                ? $description
                                                : "Subject description preview.",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </div>

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

                            <i class="bi bi-check-circle me-1"></i>

                            Create Subject

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


    <!-- =====================================================
         LIVE PREVIEW SCRIPT
    ====================================================== -->

    <script>

        document.addEventListener(
            "DOMContentLoaded",
            function() {

                const codeInput =
                    document.getElementById(
                        "subject_code"
                    );

                const nameInput =
                    document.getElementById(
                        "subject_name"
                    );

                const descriptionInput =
                    document.getElementById(
                        "description"
                    );

                const previewCode =
                    document.getElementById(
                        "previewCode"
                    );

                const previewName =
                    document.getElementById(
                        "previewName"
                    );

                const previewDescription =
                    document.getElementById(
                        "previewDescription"
                    );


                /* =============================================
                   UPDATE PREVIEW
                ============================================== */

                function updatePreview() {

                    const code =
                        codeInput.value.trim();

                    const name =
                        nameInput.value.trim();

                    const description =
                        descriptionInput.value.trim();


                    previewCode.textContent =
                        code || "SUBJECT CODE";


                    previewName.textContent =
                        name || "Subject Name";


                    previewDescription.textContent =
                        description ||
                        "Subject description preview.";

                }


                /* =============================================
                   EVENTS
                ============================================== */

                codeInput.addEventListener(
                    "input",
                    updatePreview
                );

                nameInput.addEventListener(
                    "input",
                    updatePreview
                );

                descriptionInput.addEventListener(
                    "input",
                    updatePreview
                );

            }

        );

    </script>

</body>

</html>

