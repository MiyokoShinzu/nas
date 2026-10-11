<?php

/* =========================================================
   ETS-ASYNC LEARNING PORTAL
   ADMIN - EDIT TOPIC
   =========================================================

   PURPOSE:
   ---------------------------------------------------------
   Updates an existing topic.

   DATABASE TABLE:
   ---------------------------------------------------------
   topics

   RELATIONSHIP:
   ---------------------------------------------------------
   topics.subject_id
          |
          v
   subjects.id

   IMPORTANT:
   ---------------------------------------------------------
   The selected topic must always belong to a valid subject.

   DATABASE CONNECTION:
   ---------------------------------------------------------
   $mysqli

   ========================================================= */

date_default_timezone_set('Asia/Manila');


/* =========================================================
   ADMIN AUTHENTICATION
   ========================================================= */

include './globals/checks.php';


/* =========================================================
   SESSION USER
   ========================================================= */

$user = $_SESSION["user"] ?? [];

$adminId = $user["id"] ?? null;


/* =========================================================
   DATABASE CONNECTION
   ========================================================= */

require_once "../src/connection.php";


/* =========================================================
   PAGE VARIABLES
   ========================================================= */

$error   = "";
$success = "";


/* =========================================================
   GET TOPIC ID
   ========================================================= */

$topicId = (int)($_GET["id"] ?? $_POST["topic_id"] ?? 0);


/* =========================================================
   VALIDATE TOPIC ID
   ========================================================= */

if ($topicId <= 0) {

    header("Location: topics.php?error=" . urlencode(
        "Invalid topic."
    ));

    exit;
}


/* =========================================================
   LOAD SUBJECTS
   ========================================================= */

$subjects = [];

$subjectQuery = $mysqli->query("
    SELECT
        id,
        name,
        code,
        status
    FROM subjects
    ORDER BY name ASC
");

if ($subjectQuery) {

    while ($row = $subjectQuery->fetch_assoc()) {

        $subjects[] = $row;
    }
}


/* =========================================================
   LOAD EXISTING TOPIC
   ========================================================= */

$stmt = $mysqli->prepare("
    SELECT
        id,
        subject_id,
        name,
        description,
        status
    FROM topics
    WHERE id = ?
    LIMIT 1
");

if (!$stmt) {

    die("Unable to prepare topic request.");
}

$stmt->bind_param("i", $topicId);

$stmt->execute();

$result = $stmt->get_result();

$topic = $result->fetch_assoc();

$stmt->close();


/* =========================================================
   TOPIC NOT FOUND
   ========================================================= */

if (!$topic) {

    header("Location: topics.php?error=" . urlencode(
        "Topic not found."
    ));

    exit;
}


/* =========================================================
   INITIAL FORM VALUES
   ========================================================= */

$subjectId   = (int)$topic["subject_id"];
$name        = $topic["name"];
$description = $topic["description"] ?? "";
$status      = $topic["status"];


/* =========================================================
   HANDLE UPDATE
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $subjectId   = (int)($_POST["subject_id"] ?? 0);
    $name        = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status      = $_POST["status"] ?? "active";


    /* =====================================================
       VALIDATE SUBJECT
       ===================================================== */

    if ($subjectId <= 0) {

        $error = "Please select a subject.";
    }


    /* =====================================================
       VALIDATE TOPIC NAME
       ===================================================== */ elseif ($name === "") {

        $error = "Topic name is required.";
    }


    /* =====================================================
       VALIDATE STATUS
       ===================================================== */ elseif (!in_array($status, ["active", "inactive"], true)) {

        $error = "Invalid topic status.";
    }


    /* =====================================================
       VERIFY SUBJECT
       ===================================================== */

    if ($error === "") {

        $checkSubject = $mysqli->prepare("
            SELECT id
            FROM subjects
            WHERE id = ?
            LIMIT 1
        ");

        if (!$checkSubject) {

            $error =
                "Unable to validate the selected subject.";
        } else {

            $checkSubject->bind_param(
                "i",
                $subjectId
            );

            $checkSubject->execute();

            $checkSubject->store_result();

            if ($checkSubject->num_rows === 0) {

                $error =
                    "The selected subject does not exist.";
            }

            $checkSubject->close();
        }
    }


    /* =====================================================
       CHECK DUPLICATE TOPIC
       ===================================================== */

    if ($error === "") {

        $checkDuplicate = $mysqli->prepare("
            SELECT id
            FROM topics
            WHERE subject_id = ?
              AND name = ?
              AND id != ?
            LIMIT 1
        ");

        if (!$checkDuplicate) {

            $error =
                "Unable to validate the topic.";
        } else {

            $checkDuplicate->bind_param(
                "isi",
                $subjectId,
                $name,
                $topicId
            );

            $checkDuplicate->execute();

            $checkDuplicate->store_result();

            if ($checkDuplicate->num_rows > 0) {

                $error =
                    "Another topic with this name already "
                    . "exists under the selected subject.";
            }

            $checkDuplicate->close();
        }
    }


    /* =====================================================
       UPDATE TOPIC
       ===================================================== */

    if ($error === "") {

        $update = $mysqli->prepare("
            UPDATE topics
            SET
                subject_id = ?,
                name = ?,
                description = NULLIF(?, ''),
                status = ?
            WHERE id = ?
        ");

        if (!$update) {

            $error =
                "Unable to prepare the topic update.";
        } else {

            $update->bind_param(
                "isssi",
                $subjectId,
                $name,
                $description,
                $status,
                $topicId
            );


            if ($update->execute()) {

                $success =
                    "Topic updated successfully.";
            } else {

                $error =
                    "Unable to update the topic. "
                    . "Please try again.";
            }

            $update->close();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include './globals/head.php'; ?>

    <title>Edit Topic | ETS-Async</title>

    <style>
        /* =================================================
           PAGE HEADER
           ================================================= */

        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.55rem;
            font-weight: 700;
            margin-bottom: .25rem;
        }

        .page-header p {
            margin: 0;
            color: #6c757d;
        }


        /* =================================================
           FORM CARD
           ================================================= */

        .form-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .07);
        }

        .form-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #ffffff;
        }

        .form-card-header h5 {
            margin: 0;
            font-weight: 700;
        }

        .form-card-header p {
            margin: .35rem 0 0;
            color: #6c757d;
            font-size: .9rem;
        }

        .form-card-body {
            padding: 1.5rem;
        }

        .form-card-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
            background: #fafafa;
        }


        /* =================================================
           FORM ELEMENTS
           ================================================= */

        .form-label {
            font-weight: 600;
            font-size: .9rem;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: .7rem .85rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0b4f8a;
            box-shadow: 0 0 0 .2rem rgba(11, 79, 138, .12);
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
        }

        .form-text {
            font-size: .8rem;
        }


        /* =================================================
           INFO BOX
           ================================================= */

        .info-box {
            padding: 1rem;
            border-radius: 12px;
            background: rgba(11, 79, 138, .06);
            border-left: 4px solid #0b4f8a;
        }

        .info-box strong {
            display: block;
            margin-bottom: .3rem;
        }

        .info-box p {
            margin: 0;
            color: #6c757d;
            font-size: .85rem;
        }


        /* =================================================
           DARK MODE
           ================================================= */

        html[data-theme="dark"] .form-card {
            background: #1e1e1e;
            color: #f1f1f1;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .25);
        }

        html[data-theme="dark"] .form-card-header {
            background: #1e1e1e;
            border-color: #343434;
        }

        html[data-theme="dark"] .form-card-footer {
            background: #181818;
            border-color: #343434;
        }

        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select {
            background-color: #242424;
            border-color: #444;
            color: #f1f1f1;
        }

        html[data-theme="dark"] .form-control::placeholder {
            color: #888;
        }

        html[data-theme="dark"] .form-text {
            color: #adb5bd !important;
        }

        html[data-theme="dark"] .info-box p {
            color: #adb5bd;
        }


        /* =================================================
           MOBILE
           ================================================= */

        @media (max-width: 767.98px) {

            .page-header h1 {
                font-size: 1.35rem;
            }

            .form-card-header,
            .form-card-body,
            .form-card-footer {
                padding: 1rem;
            }

        }
    </style>

</head>

<body>

    <?php include './globals/sidebar.php'; ?>

    <div class="main-content">

        <?php include './globals/topbar.php'; ?>


        <!-- =====================================================
         MAIN CONTENT
         ===================================================== -->

        <main class="container-fluid px-3 px-md-4 py-4">


            <!-- =================================================
             PAGE HEADER
             ================================================= -->

            <div class="page-header">

                <h1>

                    <i class="bi bi-pencil-square me-2"></i>

                    Edit Topic

                </h1>

                <p>
                    Update the topic information and subject association.
                </p>

            </div>


            <!-- =================================================
             ALERTS
             ================================================= -->

            <?php if ($success !== ""): ?>

                <div class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-check-circle me-2"></i>

                    <?= htmlspecialchars($success) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <?php if ($error !== ""): ?>

                <div class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    <?= htmlspecialchars($error) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
             FORM CARD
             ================================================= -->

            <div class="form-card">

                <div class="form-card-header">

                    <h5>

                        <i class="bi bi-diagram-3 me-2"></i>

                        Topic Information

                    </h5>

                    <p>
                        Modify the selected topic.
                    </p>

                </div>


                <!-- =================================================
                 FORM
                 ================================================= -->

                <form method="POST">

                    <input
                        type="hidden"
                        name="topic_id"
                        value="<?= (int)$topicId ?>">


                    <div class="form-card-body">

                        <div class="row g-4">


                            <!-- =========================================
                             SUBJECT
                             ========================================= -->

                            <div class="col-12">

                                <label
                                    for="subject_id"
                                    class="form-label">

                                    Subject

                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="subject_id"
                                    id="subject_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Subject
                                    </option>

                                    <?php foreach ($subjects as $subject): ?>

                                        <option
                                            value="<?= (int)$subject["id"] ?>"
                                            <?= $subjectId === (int)$subject["id"]
                                                ? "selected"
                                                : "" ?>>

                                            <?= htmlspecialchars(
                                                $subject["name"]
                                            ) ?>

                                            <?php if (!empty($subject["code"])): ?>

                                                —
                                                <?= htmlspecialchars(
                                                    $subject["code"]
                                                ) ?>

                                            <?php endif; ?>

                                            <?php if ($subject["status"] === "inactive"): ?>

                                                (Inactive)

                                            <?php endif; ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <div class="form-text">

                                    Changing the subject will move this topic
                                    under another subject.

                                </div>

                            </div>


                            <!-- =========================================
                             TOPIC NAME
                             ========================================= -->

                            <div class="col-12">

                                <label
                                    for="name"
                                    class="form-label">

                                    Topic Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    maxlength="150"
                                    value="<?= htmlspecialchars($name) ?>"
                                    required>

                            </div>


                            <!-- =========================================
                             DESCRIPTION
                             ========================================= -->

                            <div class="col-12">

                                <label
                                    for="description"
                                    class="form-label">

                                    Description

                                </label>

                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    maxlength="2000"
                                    placeholder="Briefly describe this topic..."><?= htmlspecialchars($description) ?></textarea>

                            </div>


                            <!-- =========================================
                             STATUS
                             ========================================= -->

                            <div class="col-12 col-md-6">

                                <label
                                    for="status"
                                    class="form-label">

                                    Status

                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select">

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

                            </div>


                            <!-- =========================================
                             INFORMATION
                             ========================================= -->

                            <div class="col-12">

                                <div class="info-box">

                                    <strong>

                                        <i class="bi bi-info-circle me-1"></i>

                                        Normalized Assessment Structure

                                    </strong>

                                    <p>

                                        Questions reference this topic using
                                        its numeric ID. Renaming this topic
                                        therefore does not require modifying
                                        existing question records.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                     FOOTER
                     ================================================= -->

                    <div class="form-card-footer">

                        <div class="d-flex flex-column flex-sm-row
                                justify-content-between gap-2">

                            <a
                                href="topics.php"
                                class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-left me-1"></i>

                                Back to Topics

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4">

                                <i class="bi bi-save me-1"></i>

                                Save Changes

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </main>

    </div>


    <!-- =========================================================
     GLOBAL SCRIPTS
     ========================================================= -->

    <?php include './globals/scripts.php'; ?>

</body>

</html>