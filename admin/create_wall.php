
<?php
include './globals/checks.php';

/* =========================================================
   ADMIN CREATE FREEDOM WALL
   ETS-Async Learning Portal
   ========================================================= */


/* =========================================================
   GET ADMIN USER
========================================================= */

$user = $_SESSION["user"] ?? [];

$adminId = $user["id"] ?? null;


/* =========================================================
   DATABASE CONNECTION
========================================================= */

require_once "../src/connection.php";


/* =========================================================
   HELPER
========================================================= */

function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}


/* =========================================================
   GET DEPARTMENTS
========================================================= */

$departments = [];

$departmentSQL = "
    SELECT DISTINCT department
    FROM accounts
    WHERE access = 'student'
      AND department IS NOT NULL
      AND department != ''
    ORDER BY department ASC
";

$departmentResult = $mysqli->query($departmentSQL);

if ($departmentResult) {

    while ($row = $departmentResult->fetch_assoc()) {

        $departments[] = trim(
            $row["department"]
        );
    }
}


/* =========================================================
   GET YEAR LEVELS AND SECTIONS
========================================================= */

$yearLevels = [];

$sections = [];

$yearSectionSQL = "
    SELECT DISTINCT year_section
    FROM accounts
    WHERE access = 'student'
      AND year_section IS NOT NULL
      AND year_section != ''
    ORDER BY year_section ASC
";

$yearSectionResult = $mysqli->query(
    $yearSectionSQL
);

if ($yearSectionResult) {

    while ($row = $yearSectionResult->fetch_assoc()) {

        $value = trim(
            $row["year_section"]
        );

        if ($value === "") {
            continue;
        }


        /*
           Examples:

           1-A
           1-B
           2-A
           3-B
           4-A

           Extract year level and section.
        */

        $parts = preg_split(
            '/[-\s]+/',
            $value,
            2
        );


        /* =============================================
           YEAR LEVEL
        ============================================= */

        if (
            isset($parts[0]) &&
            trim($parts[0]) !== ""
        ) {

            $yearLevels[] = trim(
                $parts[0]
            );
        }


        /* =============================================
           SECTION
        ============================================= */

        if (
            isset($parts[1]) &&
            trim($parts[1]) !== ""
        ) {

            $sections[] = trim(
                $parts[1]
            );
        }
    }
}


$yearLevels = array_values(
    array_unique($yearLevels)
);

$sections = array_values(
    array_unique($sections)
);

sort($yearLevels);

sort($sections);


/* =========================================================
   FORM DEFAULTS
========================================================= */

$title = "";

$department = "";

$yearLevel = "";

$section = "";

$description = "";

$status = "active";


/*
   Default Freedom Wall period:
   Today → 14 days from today
*/

$startDate = date(
    "Y-m-d\TH:i"
);

$dueDate = date(
    "Y-m-d\TH:i",
    strtotime("+14 days")
);


$error = "";

$success = "";


/* =========================================================
   PROCESS FORM
========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* =====================================================
       GET FORM DATA
    ===================================================== */

    $title =
        trim($_POST["title"] ?? "");

    $department =
        trim($_POST["department"] ?? "");

    $yearLevel =
        trim($_POST["year_level"] ?? "");

    $section =
        trim($_POST["section"] ?? "");

    $description =
        trim($_POST["description"] ?? "");

    $startDate =
        trim($_POST["start_date"] ?? "");

    $dueDate =
        trim($_POST["due_date"] ?? "");

    $status =
        trim($_POST["status"] ?? "active");


    /* =====================================================
       VALIDATION
    ===================================================== */

    if ($adminId === null) {

        $error =
            "Unable to identify the administrator account.";
    } elseif ($title === "") {

        $error =
            "Freedom Wall title is required.";
    } elseif (mb_strlen($title) > 255) {

        $error =
            "Freedom Wall title must not exceed 255 characters.";
    } elseif ($department === "") {

        $error =
            "Please select a department.";
    } elseif ($yearLevel === "") {

        $error =
            "Please select a year level.";
    } elseif ($startDate === "") {

        $error =
            "Start date is required.";
    } elseif ($dueDate === "") {

        $error =
            "Due date is required.";
    } elseif (
        strtotime($startDate) === false
    ) {

        $error =
            "Invalid start date.";
    } elseif (
        strtotime($dueDate) === false
    ) {

        $error =
            "Invalid due date.";
    } elseif (
        strtotime($dueDate) <=
        strtotime($startDate)
    ) {

        $error =
            "Due date must be later than the start date.";
    } elseif (
        !in_array(
            $status,
            ["active", "inactive"],
            true
        )
    ) {

        $error =
            "Invalid Freedom Wall status.";
    }


    /* =====================================================
       CREATE YEAR SECTION
    ===================================================== */

    if ($error === "") {

        /*
           If section is selected:

           3 + A = 3-A

           If section is blank:

           3 = 3

           The student-side audience query should treat
           a year-only value as ALL sections of that year.
        */

        if ($section !== "") {

            $yearSection =
                $yearLevel . "-" . $section;
        } else {

            $yearSection =
                $yearLevel;
        }
    }


    /* =====================================================
       SAVE FREEDOM WALL
    ===================================================== */

    if ($error === "") {

        $startTimestamp =
            strtotime($startDate);

        $dueTimestamp =
            strtotime($dueDate);


        if (
            $startTimestamp === false ||
            $dueTimestamp === false
        ) {

            $error =
                "Invalid date and time.";
        } else {

            $startDateDB =
                date(
                    "Y-m-d H:i:s",
                    $startTimestamp
                );

            $dueDateDB =
                date(
                    "Y-m-d H:i:s",
                    $dueTimestamp
                );


            /* =============================================
               INSERT
            ============================================= */

            $sql = "
                INSERT INTO freedom_walls (
                    title,
                    description,
                    department,
                    year_section,
                    status,
                    start_date,
                    due_date,
                    created_by
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ";


            $stmt =
                $mysqli->prepare($sql);


            if (!$stmt) {

                $error =
                    "Database error: " .
                    $mysqli->error;
            } else {

                $stmt->bind_param(
                    "sssssssi",
                    $title,
                    $description,
                    $department,
                    $yearSection,
                    $status,
                    $startDateDB,
                    $dueDateDB,
                    $adminId
                );


                if ($stmt->execute()) {

                    $success =
                        "Freedom Wall created successfully.";


                    /* =====================================
                       RESET FORM
                    ===================================== */

                    $title = "";

                    $department = "";

                    $yearLevel = "";

                    $section = "";

                    $description = "";

                    $status = "active";


                    $startDate =
                        date(
                            "Y-m-d\TH:i"
                        );


                    $dueDate =
                        date(
                            "Y-m-d\TH:i",
                            strtotime("+14 days")
                        );
                } else {

                    $error =
                        "Unable to save the Freedom Wall: " .
                        $stmt->error;
                }


                $stmt->close();
            }
        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">


<?php include 'globals/head.php'; ?>


<style>
    /* =========================================================
   CREATE FREEDOM WALL PAGE
   ETS-Async Learning Portal
========================================================= */


    /* =========================================================
   PAGE HEADER
========================================================= */

    .page-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        margin-bottom: 25px;
    }


    .page-header h2 {

        margin: 0;

        font-size: 1.65rem;

        font-weight: 700;

        color: #1f2937;
    }


    .page-header p {

        margin: 6px 0 0;

        color: #6b7280;

        font-size: 0.92rem;
    }


    /* =========================================================
   BACK / MANAGE BUTTON
========================================================= */

    .page-header .btn {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 4px;

        border-radius: 8px;

        font-size: 0.88rem;

        padding: 9px 15px;

        white-space: nowrap;
    }


    /* =========================================================
   ALERTS
========================================================= */

    .activity-alert {

        border: none;

        border-radius: 10px;

        padding: 13px 16px;

        margin-bottom: 20px;

        font-size: 0.9rem;

        display: flex;

        align-items: center;
    }


    .activity-alert.alert-success {

        background: #ecfdf3;

        color: #15803d;
    }


    .activity-alert.alert-danger {

        background: #fef2f2;

        color: #dc2626;
    }


    /* =========================================================
   MAIN FORM CARD
========================================================= */

    .activity-form-card {

        background: #ffffff;

        border: 1px solid #e5e7eb;

        border-radius: 14px;

        box-shadow:
            0 2px 8px rgba(15, 23, 42, 0.04);

        overflow: hidden;

        padding: 0;
    }


    /* =========================================================
   FORM HEADER
========================================================= */

    .activity-form-header {

        display: flex;

        align-items: center;

        gap: 15px;

        padding: 22px 25px;

        background: linear-gradient(135deg,
                #f8faff,
                #f5f3ff);

        border-bottom: 1px solid #e5e7eb;
    }


    .activity-form-icon {

        width: 48px;

        height: 48px;

        min-width: 48px;

        border-radius: 12px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: linear-gradient(135deg,
                #4f46e5,
                #6366f1);

        color: #ffffff;

        font-size: 1.35rem;

        box-shadow:
            0 5px 12px rgba(79,
                70,
                229,
                0.20);
    }


    .activity-form-header h5 {

        margin: 0;

        font-size: 1.05rem;

        font-weight: 700;

        color: #1f2937;
    }


    .activity-form-header p {

        margin: 4px 0 0;

        color: #6b7280;

        font-size: 0.85rem;
    }


    /* =========================================================
   FORM BODY
========================================================= */

    .activity-form-card form {

        padding: 25px;
    }


    /* =========================================================
   FORM LABELS
========================================================= */

    .activity-form-card .form-label {

        display: block;

        margin-bottom: 7px;

        color: #374151;

        font-size: 0.88rem;

        font-weight: 600;
    }


    .activity-form-card .form-label .text-danger {

        color: #dc2626 !important;
    }


    .activity-form-card .form-label .text-muted {

        font-size: 0.78rem;

        font-weight: 400;

        color: #9ca3af !important;
    }


    /* =========================================================
   FORM CONTROLS
========================================================= */

    .activity-form-card .form-control,

    .activity-form-card .form-select {

        min-height: 44px;

        border: 1px solid #d1d5db;

        border-radius: 8px;

        background-color: #ffffff;

        color: #1f2937;

        font-size: 0.9rem;

        padding: 9px 12px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background-color 0.2s ease;
    }


    .activity-form-card textarea.form-control {

        min-height: 120px;

        resize: vertical;

        line-height: 1.55;
    }


    /* =========================================================
   PLACEHOLDER
========================================================= */

    .activity-form-card .form-control::placeholder {

        color: #9ca3af;

        opacity: 1;
    }


    /* =========================================================
   FOCUS
========================================================= */

    .activity-form-card .form-control:focus,

    .activity-form-card .form-select:focus {

        border-color: #6366f1;

        box-shadow:
            0 0 0 3px rgba(99,
                102,
                241,
                0.12);

        outline: none;
    }


    /* =========================================================
   FORM HELP TEXT
========================================================= */

    .activity-form-card .form-text {

        margin-top: 6px;

        color: #9ca3af;

        font-size: 0.78rem;

        line-height: 1.4;
    }


    /* =========================================================
   DATETIME INPUT
========================================================= */

    .activity-form-card input[type="datetime-local"] {

        cursor: pointer;
    }


    /* =========================================================
   SELECT
========================================================= */

    .activity-form-card .form-select {

        cursor: pointer;
    }


    /* =========================================================
   FORM FOOTER
========================================================= */

    .activity-form-footer {

        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 10px;

        margin-top: 28px;

        padding-top: 20px;

        border-top: 1px solid #e5e7eb;
    }


    /* =========================================================
   BUTTONS
========================================================= */

    .activity-form-footer .btn {

        min-height: 42px;

        padding: 9px 17px;

        border-radius: 8px;

        font-size: 0.88rem;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        transition:
            transform 0.15s ease,
            box-shadow 0.2s ease,
            background-color 0.2s ease;
    }


    .activity-form-footer .btn-primary {

        background: #4f46e5;

        border-color: #4f46e5;
    }


    .activity-form-footer .btn-primary:hover {

        background: #4338ca;

        border-color: #4338ca;

        box-shadow:
            0 4px 10px rgba(79,
                70,
                229,
                0.20);

        transform: translateY(-1px);
    }


    .activity-form-footer .btn-light {

        background: #f3f4f6;

        border-color: #e5e7eb;

        color: #4b5563;
    }


    .activity-form-footer .btn-light:hover {

        background: #e5e7eb;

        border-color: #d1d5db;

        color: #1f2937;
    }


    /* =========================================================
   RESPONSIVE
========================================================= */

    @media (max-width: 768px) {

        .page-header {

            flex-direction: column;

            align-items: flex-start;

            gap: 15px;
        }


        .page-header>div:last-child {

            width: 100%;
        }


        .page-header .btn {

            width: 100%;
        }


        .activity-form-header {

            padding: 18px;
        }


        .activity-form-card form {

            padding: 18px;
        }


        .activity-form-footer {

            flex-direction: column-reverse;

            align-items: stretch;
        }


        .activity-form-footer .btn {

            width: 100%;
        }

    }


    /* =========================================================
   SMALL MOBILE
========================================================= */

    @media (max-width: 480px) {

        .page-header h2 {

            font-size: 1.4rem;
        }


        .page-header p {

            font-size: 0.84rem;
        }


        .activity-form-header {

            align-items: flex-start;
        }


        .activity-form-icon {

            width: 42px;

            height: 42px;

            min-width: 42px;

            font-size: 1.15rem;
        }


        .activity-form-header h5 {

            font-size: 0.98rem;
        }


        .activity-form-header p {

            font-size: 0.8rem;
        }


        .activity-form-card form {

            padding: 16px;
        }

    }
</style>


<body>


    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <?php include 'globals/sidebar.php'; ?>


    <!-- =========================================================
         TOPBAR
    ========================================================== -->

    <?php include 'globals/topbar.php'; ?>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <main class="main-content">


        <div class="content-wrapper">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="page-header">

                <div>

                    <h2>
                        Create Freedom Wall
                    </h2>

                    <p>
                        Create a Freedom Wall and make it available
                        to a specific group of students.
                    </p>

                </div>


                <div>

                    <a
                        href="freedom_walls.php"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>

                        Manage Freedom Walls

                    </a>

                </div>

            </div>


            <!-- =================================================
                 ALERTS
            ================================================== -->

            <?php if ($success !== ""): ?>

                <div
                    class="alert alert-success activity-alert">

                    <i class="bi bi-check-circle me-2"></i>

                    <?= e($success) ?>

                </div>

            <?php endif; ?>


            <?php if ($error !== ""): ?>

                <div
                    class="alert alert-danger activity-alert">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?= e($error) ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 FORM CARD
            ================================================== -->

            <div class="activity-form-card">


                <!-- =================================================
                     HEADER
                ================================================== -->

                <div class="activity-form-header">


                    <div class="activity-form-icon">

                        <i class="bi bi-chat-square-heart-fill"></i>

                    </div>


                    <div>

                        <h5>
                            Freedom Wall Information
                        </h5>

                        <p>
                            Provide the wall information, student
                            audience, and availability schedule.
                        </p>

                    </div>


                </div>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    method="POST"
                    action="create_wall.php">


                    <div class="row g-4">


                        <!-- =========================================
                             TITLE
                        ========================================== -->

                        <div class="col-12">

                            <label
                                for="title"
                                class="form-label">

                                Freedom Wall Title

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                id="title"
                                name="title"
                                class="form-control"
                                maxlength="255"
                                placeholder="Example: DSP Class Freedom Wall"
                                value="<?= e($title) ?>"
                                required>

                        </div>


                        <!-- =========================================
                             DEPARTMENT
                        ========================================== -->

                        <div class="col-md-4">

                            <label
                                for="department"
                                class="form-label">

                                Department

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                id="department"
                                name="department"
                                class="form-select"
                                required>


                                <option value="">
                                    Select Department
                                </option>


                                <?php foreach (
                                    $departments
                                    as $itemDepartment
                                ): ?>

                                    <option
                                        value="<?= e($itemDepartment) ?>"
                                        <?= $department === $itemDepartment
                                            ? "selected"
                                            : ""
                                        ?>>

                                        <?= e($itemDepartment) ?>

                                    </option>

                                <?php endforeach; ?>


                            </select>

                        </div>


                        <!-- =========================================
                             YEAR LEVEL
                        ========================================== -->

                        <div class="col-md-4">

                            <label
                                for="year_level"
                                class="form-label">

                                Year Level

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                id="year_level"
                                name="year_level"
                                class="form-select"
                                required>


                                <option value="">
                                    Select Year Level
                                </option>


                                <?php foreach (
                                    $yearLevels
                                    as $itemYear
                                ): ?>

                                    <option
                                        value="<?= e($itemYear) ?>"
                                        <?= $yearLevel === $itemYear
                                            ? "selected"
                                            : ""
                                        ?>>

                                        <?= e($itemYear) ?>

                                    </option>

                                <?php endforeach; ?>


                            </select>

                        </div>


                        <!-- =========================================
                             SECTION
                        ========================================== -->

                        <div class="col-md-4">

                            <label
                                for="section"
                                class="form-label">

                                Section

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>


                            <select
                                id="section"
                                name="section"
                                class="form-select">


                                <option value="">
                                    All Sections
                                </option>


                                <?php foreach (
                                    $sections
                                    as $itemSection
                                ): ?>

                                    <option
                                        value="<?= e($itemSection) ?>"
                                        <?= $section === $itemSection
                                            ? "selected"
                                            : ""
                                        ?>>

                                        <?= e($itemSection) ?>

                                    </option>

                                <?php endforeach; ?>


                            </select>


                            <div class="form-text">

                                Leave blank to make the wall
                                available to all sections.

                            </div>

                        </div>


                        <!-- =========================================
                             START DATE
                        ========================================== -->

                        <div class="col-md-6">

                            <label
                                for="start_date"
                                class="form-label">

                                Available From

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="datetime-local"
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                value="<?= e($startDate) ?>"
                                required>


                            <div class="form-text">

                                Students can access the Freedom Wall
                                starting from this date.

                            </div>

                        </div>


                        <!-- =========================================
                             DUE DATE
                        ========================================== -->

                        <div class="col-md-6">

                            <label
                                for="due_date"
                                class="form-label">

                                Due Date

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="datetime-local"
                                id="due_date"
                                name="due_date"
                                class="form-control"
                                value="<?= e($dueDate) ?>"
                                required>


                            <div class="form-text">

                                Default duration is two weeks.

                            </div>

                        </div>


                        <!-- =========================================
                             STATUS
                        ========================================== -->

                        <div class="col-md-6">

                            <label
                                for="status"
                                class="form-label">

                                Status

                            </label>


                            <select
                                id="status"
                                name="status"
                                class="form-select">


                                <option
                                    value="active"
                                    <?= $status === "active"
                                        ? "selected"
                                        : ""
                                    ?>>

                                    Active

                                </option>


                                <option
                                    value="inactive"
                                    <?= $status === "inactive"
                                        ? "selected"
                                        : ""
                                    ?>>

                                    Inactive

                                </option>


                            </select>

                        </div>


                        <!-- =========================================
                             DESCRIPTION
                        ========================================== -->

                        <div class="col-12">

                            <label
                                for="description"
                                class="form-label">

                                Description

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>


                            <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                rows="5"
                                placeholder="Example: Share your thoughts, experiences, and messages with your classmates. Keep your posts respectful and appropriate."><?= e($description) ?></textarea>


                            <div class="form-text">

                                This description will be displayed
                                to students before they enter the
                                Freedom Wall.

                            </div>

                        </div>


                    </div>


                    <!-- =================================================
                         FOOTER
                    ================================================== -->

                    <div class="activity-form-footer">


                        <a
                            href="freedom_walls.php"
                            class="btn btn-light">

                            <i class="bi bi-x-lg me-1"></i>

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-plus-lg me-1"></i>

                            Create Freedom Wall

                        </button>


                    </div>


                </form>


            </div>


        </div>


    </main>


    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <?php include 'globals/scripts.php'; ?>


</body>

</html>
