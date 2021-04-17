<?php
//DATABASE operations
include('db_conn.php');


function create($sql)
{
    global $conn;
}

function loggedStudent($creator_id, $student_id, $present = true)
{
    global $conn;
    $sql = "INSERT INTO guard_attendance (student_id, present, creator_id) VALUES(?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $student_id, $present, $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        return false;
    }
    return true;
}
function findAll($table, $id = '', $fieldID = "id", $num_rows = false)
{
    global $conn;
    $sql = "SELECT * FROM " . $table . " ORDER by id DESC";
    if (!empty($id) && (int)$id) { //if id is set
        $sql = "SELECT * FROM " . $table . " WHERE " . $fieldID . " =? ORDER by id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
    } else {
        $stmt = $conn->prepare($sql);
    }
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($num_rows) {
        return $result->num_rows;
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}
function wildCardFindAll($table, $id = '', $fieldID = "id")
{
    global $conn;
    $id = $id . "%";
    $sql = "SELECT * FROM " . $table . " WHERE " . $fieldID . " LIKE ? ORDER by id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}
//subjectAttendance
function wildCardFindAllSubjectAttendance($dateToday, $student_id, $subject_id)
{
    global $conn;
    $sql = "SELECT * FROM subject_attendance WHERE date_created LIKE ? AND student_id=? AND subject_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $dateToday, $student_id, $subject_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}

function findAllEnrolledStudents($s)
{
    global $conn;
    $sql = "SELECT students.id, students.fname, students.mname, students.lname
                        FROM `enrolled_subjects` INNER JOIN students ON students.id=enrolled_subjects.student_id
                        WHERE subject_id=? AND year_id=?  AND course_id=? AND section_id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $s[0], $s[1], $s[2], $s[3]);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}

function findOne($sql, $dataToFind, $num_rows = false)
{
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dataToFind);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($num_rows) {
        return $result->num_rows;
    }
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}
function findAllOpenQuery($sql, $dataToFind, $num_rows = false) //one binding parameter
{
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dataToFind);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($num_rows) {
        return $result->num_rows;
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}
function openQuery($sql, $num_rows = false) //executes only without binding
{
    global $conn;
    $result = $conn->query($sql);
    if ($result) {
        if ($num_rows) {
            return $result->num_rows;
        }
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    } else {
        echo "Error:'$sql' " . "<br>" . $conn->error;
        return false;
    }
}

function countData($table, $id = "", $column = "id")
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM $table";
    if (!empty($id) && !empty($column)) {
        $sql .= " WHERE $column =" . $id;
    }
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'];
    }
    return 0;
}

function update($sql)
{
    global $conn;
}
function delete($sql)
{
    global $conn;
}

function execute($stmt)
{
    if ($stmt->execute() === TRUE) {
        return true;
    }
    return false;
}


//accountType
function displayAccountBadge($id, $fullName, $student = false, $active = 1)
{
    global $conn;
    if ($student) {
        echo accountBadge(1, $active) . " " . $fullName;
        return '1'; //this part is kinda hardcoded
    }
    $sql3 = "SELECT account_type FROM accountlist WHERE account_id=?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("s", $id);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    if ($result3->num_rows > 0) {
        $badge = $result3->fetch_assoc();
        echo accountBadge($badge['account_type'], $active) . " " . $fullName;
        return $badge['account_type'];
    } else {
        echo "Account ID: " . $id . " Doesn't Exist in List";
    }
}

function displayFileExtension($id, $account_type = 2)
{
    global $conn;
    $result = getAccountTypeById($id, $account_type);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sql = "SELECT file_extension FROM accounts_photos WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $row['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['file_extension'];
        }
    }
}

function displayCreator($id)
{

    global $conn;
    $sql = "SELECT fname, mname, lname FROM staffs WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $creator = $result->fetch_assoc();
        echo fullName($creator['fname'], $creator['mname'], $creator['lname']);
    } else {
        echo "Creator id: " . $id . " Deleted";
    }
}

function getAccountTypeById($id, $account_type = 2)
{
    global $conn;
    $sql = "SELECT id, account_type FROM accountlist WHERE account_id =? AND account_type";
    if ($account_type >= 2 && $account_type <= 4) {
        $sql .= ">=? LIMIT 1";
    } else {
        $sql .= "=? LIMIT 1";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id, $account_type);
    $stmt->execute();
    return  $stmt->get_result();
}

function getAccountDetails($id, $account_type = 1)
{
    global $conn;

    $sql = "SELECT * FROM";
    if ($account_type >= 2 && $account_type <= 4) {
        $sql .= " staffs";
    } else {
        $sql .= " students";
    }
    $sql .= "  WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    return  $stmt->get_result();
}

function getDataFromAccountList($id, $column_data = "account_type")
{
    global $conn;
    $sql = "SELECT " . $column_data . " FROM accountlist WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()[$column_data];
}
function getDataFromTable($id, $column_data, $table, $col_id = 'id')
{
    global $conn;
    $sql = "SELECT " . $column_data . " FROM " . $table . " WHERE " . $col_id . "=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()[$column_data];
    }
    return 0;
}
function getDatasFromTable($id, $column_data, $table, $col_id = 'id')
{
    global $conn;
    $data = [];
    $sql = "SELECT " . $column_data . " FROM " . $table . " WHERE " . $col_id . "=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row[$column_data];
        }
    }
    if (count($data) > 0) {
        return $data;
    }
    return -1;
}

function hasQRcodeName($id)
{
    global $conn;
    $sql = "SELECT * FROM qr_codes WHERE student_id =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['qrcode_name'];
    }
    return false;
}

function numFields($table)
{
    global $conn;
    $sql = "SELECT * FROM " . $table;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows;
}
function getFullNameFromDB($table = "staffs", $id)
{
    global $conn;
    $sql = "SELECT fname, mname,lname FROM " . $table . " WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
    return fullName($staff['fname'], $staff['mname'], $staff['lname']);
}

function totalStudentRecords($id)
{
    $totalRecordsInDB = 0;
    $tables = ['enrolled_subjects', 'guard_attendance', 'subject_attendance', 'qr_codes'];
    $count = countDatas($tables, $id, 'student_id');
    $totalRecordsInDB = sumRecords($count);
    return $totalRecordsInDB;
}

function countDatas($tables, $id, $column)
{
    foreach ($tables as $table) {
        $count = countData($table, $id, $column);
        $counts[] = $count;
    }
    return $counts;
}
function sumRecords($counts)
{
    $sum = 0;
    foreach ($counts as $count) {
        $sum += $count;
    }
    return $sum;
}
