
<?php
/* =========================================================
   ETS-ASYNC LEARNING PORTAL
   ASSESSMENT START HANDLER

   FILE:
   ---------------------------------------------------------
   assessment_start.php

   PURPOSE:
   ---------------------------------------------------------
   This file receives the test-code entered by a student
   from assessment.php.

   PROCESS:
   ---------------------------------------------------------
   1. Verify student authentication
   2. Receive test-code
   3. Validate the assessment
   4. Check assessment status
   5. Check start and due dates
   6. Determine student's next attempt number
   7. Create a new assessment attempt
   8. Store the attempt ID in session
   9. Redirect to assessment_take.php

   IMPORTANT:
   ---------------------------------------------------------
   This file does NOT contain the actual questions.

   The actual assessment interface will be handled by:

       assessment_take.php
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
   MAKE SURE STUDENT ID EXISTS
   ========================================================= */

if ($student_id === "") {

    $_SESSION["assessment_error"] =
        "Your student account could not be identified.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   DATABASE CONNECTION
   ========================================================= */

require_once "../src/connection.php";


/* =========================================================
   ONLY ALLOW POST REQUEST
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   RECEIVE TEST CODE
   ========================================================= */

$test_code = trim(
    (string)($_POST["test_code"] ?? "")
);


/* =========================================================
   VALIDATE TEST CODE
   ========================================================= */

if ($test_code === "") {

    $_SESSION["assessment_error"] =
        "Please enter the assessment test-code.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   LIMIT TEST CODE LENGTH
   ========================================================= */

if (strlen($test_code) > 100) {

    $_SESSION["assessment_error"] =
        "The test-code is invalid.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   CURRENT TIME
   ========================================================= */

$current_datetime =
    date("Y-m-d H:i:s");


/* =========================================================
   FIND ASSESSMENT
   =========================================================

   We search using the test-code.

   The assessment must exist before the student can start it.
   ========================================================= */

$sql = "
    SELECT
        id,
        title,
        description,
        test_code,
        total_items,
        passing_percentage,
        time_limit_minutes,
        status,
        start_date,
        due_date

    FROM assessments

    WHERE test_code = ?

    LIMIT 1
";


$stmt = $mysqli->prepare($sql);


/* =========================================================
   PREPARE ERROR
   ========================================================= */

if (!$stmt) {

    $_SESSION["assessment_error"] =
        "Unable to prepare the assessment request.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   BIND TEST CODE
   ========================================================= */

$stmt->bind_param(
    "s",
    $test_code
);


/* =========================================================
   EXECUTE
   ========================================================= */

$stmt->execute();


/* =========================================================
   GET RESULT
   ========================================================= */

$result = $stmt->get_result();


/* =========================================================
   CLOSE STATEMENT
   ========================================================= */

$stmt->close();


/* =========================================================
   CHECK ASSESSMENT
   ========================================================= */

if (!$result || $result->num_rows === 0) {

    $_SESSION["assessment_error"] =
        "No assessment was found for the test-code you entered.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   FETCH ASSESSMENT
   ========================================================= */

$assessment =
    $result->fetch_assoc();


/* =========================================================
   ASSESSMENT ID
   ========================================================= */

$assessment_id =
    (int)$assessment["id"];


/* =========================================================
   ASSESSMENT STATUS
   ========================================================= */

$assessment_status =
    strtolower(
        trim(
            (string)($assessment["status"] ?? "")
        )
    );


/* =========================================================
   CHECK STATUS
   ========================================================= */

if ($assessment_status !== "active") {

    $_SESSION["assessment_error"] =
        "This assessment is currently inactive.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   START DATE CHECK
   ========================================================= */

$start_date =
    $assessment["start_date"] ?? null;


/*
 * If a start date exists and the current date/time is
 * earlier than the start date, the assessment cannot
 * be started yet.
 */

if (
    !empty($start_date) &&
    strtotime($current_datetime) < strtotime($start_date)
) {

    $_SESSION["assessment_error"] =
        "This assessment is not yet available. It will open on "
        . date(
            "M d, Y • h:i A",
            strtotime($start_date)
        )
        . ".";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   DUE DATE CHECK
   ========================================================= */

$due_date =
    $assessment["due_date"] ?? null;


/*
 * If a due date exists and the current date/time is already
 * past the due date, the assessment is closed.
 */

if (
    !empty($due_date) &&
    strtotime($current_datetime) > strtotime($due_date)
) {

    $_SESSION["assessment_error"] =
        "This assessment is already closed.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   DETERMINE NEXT ATTEMPT NUMBER
   =========================================================

   Example:

   Existing attempts:

       Attempt 1
       Attempt 2

   New attempt becomes:

       Attempt 3
   ========================================================= */

$attempt_sql = "
    SELECT
        COALESCE(
            MAX(attempt_number),
            0
        ) AS last_attempt

    FROM assessment_attempts

    WHERE
        student_id = ?
        AND assessment_id = ?
";


$attempt_stmt =
    $mysqli->prepare($attempt_sql);


/* =========================================================
   PREPARE ATTEMPT ERROR
   ========================================================= */

if (!$attempt_stmt) {

    $_SESSION["assessment_error"] =
        "Unable to determine your assessment attempt.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   BIND PARAMETERS
   ========================================================= */

$attempt_stmt->bind_param(
    "si",
    $student_id,
    $assessment_id
);


/* =========================================================
   EXECUTE
   ========================================================= */

$attempt_stmt->execute();


/* =========================================================
   GET ATTEMPT RESULT
   ========================================================= */

$attempt_result =
    $attempt_stmt->get_result();


/* =========================================================
   DEFAULT ATTEMPT NUMBER
   ========================================================= */

$last_attempt = 0;


/* =========================================================
   READ LAST ATTEMPT
   ========================================================= */

if ($attempt_result) {

    $attempt_row =
        $attempt_result->fetch_assoc();

    if ($attempt_row) {

        $last_attempt =
            (int)($attempt_row["last_attempt"] ?? 0);
    }
}


/* =========================================================
   CLOSE ATTEMPT STATEMENT
   ========================================================= */

$attempt_stmt->close();


/* =========================================================
   NEXT ATTEMPT
   ========================================================= */

$attempt_number =
    $last_attempt + 1;


/* =========================================================
   CHECK WHETHER THERE IS AN ACTIVE UNFINISHED ATTEMPT
   =========================================================

   This prevents the student from repeatedly opening the
   same assessment and creating multiple unfinished attempts.

   We consider an attempt unfinished when submitted_at is NULL.
   ========================================================= */

$active_sql = "
    SELECT
        id,
        started_at

    FROM assessment_attempts

    WHERE
        student_id = ?
        AND assessment_id = ?
        AND submitted_at IS NULL

    ORDER BY id DESC

    LIMIT 1
";


$active_stmt =
    $mysqli->prepare($active_sql);


if ($active_stmt) {

    $active_stmt->bind_param(
        "si",
        $student_id,
        $assessment_id
    );

    $active_stmt->execute();

    $active_result =
        $active_stmt->get_result();

    if (
        $active_result &&
        $active_result->num_rows > 0
    ) {

        /*
         * An unfinished attempt already exists.
         *
         * Reuse it instead of creating another attempt.
         */

        $active_attempt =
            $active_result->fetch_assoc();

        $active_attempt_id =
            (int)$active_attempt["id"];

        $active_stmt->close();


        /* ================================================
           STORE CURRENT ASSESSMENT SESSION
           ================================================ */

        $_SESSION["assessment"] = [

            "attempt_id" =>
                $active_attempt_id,

            "assessment_id" =>
                $assessment_id,

            "test_code" =>
                $assessment["test_code"],

            "student_id" =>
                $student_id,

            "attempt_number" =>
                $last_attempt

        ];


        /* ================================================
           REDIRECT
           ================================================ */

        header(
            "Location: assessment_take.php"
        );

        exit;
    }

    $active_stmt->close();
}


/* =========================================================
   START NEW ATTEMPT
   =========================================================

   The initial score is zero.

   total_items is copied from the assessment.

   percentage starts at zero.

   status defaults to Failed but should only be treated as
   final after submission. The assessment page can use
   submitted_at IS NULL to identify an in-progress attempt.
   ========================================================= */

$score = 0;

$total_items =
    (int)($assessment["total_items"] ?? 0);

$percentage = 0;

$status = "Failed";

$started_at =
    $current_datetime;


/* =========================================================
   INSERT NEW ATTEMPT
   ========================================================= */

$insert_sql = "
    INSERT INTO assessment_attempts (
        student_id,
        assessment_id,
        test_code,
        attempt_number,
        score,
        total_items,
        percentage,
        status,
        started_at,
        submitted_at
    )

    VALUES (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        NULL
    )
";


$insert_stmt =
    $mysqli->prepare($insert_sql);


/* =========================================================
   INSERT ERROR
   ========================================================= */

if (!$insert_stmt) {

    $_SESSION["assessment_error"] =
        "Unable to start the assessment.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   BIND INSERT PARAMETERS
   ========================================================= */

$insert_stmt->bind_param(
    "sisiiidss",
    $student_id,
    $assessment_id,
    $test_code,
    $attempt_number,
    $score,
    $total_items,
    $percentage,
    $status,
    $started_at
);


/* =========================================================
   EXECUTE INSERT
   ========================================================= */

if (!$insert_stmt->execute()) {

    $insert_stmt->close();

    $_SESSION["assessment_error"] =
        "The assessment could not be started.";

    header("Location: assessment.php");

    exit;
}


/* =========================================================
   GET NEW ATTEMPT ID
   ========================================================= */

$attempt_id =
    $insert_stmt->insert_id;


/* =========================================================
   CLOSE INSERT STATEMENT
   ========================================================= */

$insert_stmt->close();


/* =========================================================
   STORE ASSESSMENT SESSION
   =========================================================

   This allows assessment_take.php to know exactly which
   assessment and attempt the student is currently taking.

   We do NOT trust a URL parameter such as:

       ?attempt_id=123

   by itself.

   The server-side session gives us an additional authorization
   boundary.
   ========================================================= */

$_SESSION["assessment"] = [

    "attempt_id" =>
        $attempt_id,

    "assessment_id" =>
        $assessment_id,

    "test_code" =>
        $assessment["test_code"],

    "student_id" =>
        $student_id,

    "attempt_number" =>
        $attempt_number

];


/* =========================================================
   CLEAR OLD ASSESSMENT ERROR
   ========================================================= */

unset(
    $_SESSION["assessment_error"]
);


/* =========================================================
   REDIRECT TO ASSESSMENT
   ========================================================= */

header(
    "Location: assessment_take.php"
);

exit;

