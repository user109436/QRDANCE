<?php
include('../../../private/config.php');

$sql = "SELECT 
students.id,
students.fname,
students.mname,
students.lname,
year_id,
section_id,
course_id,
students.creator_id,
students.date_created,
year,
section,
course,
staffs.fname,
staffs.mname,
staffs.lname,
accountlist.active
FROM `students` 
INNER JOIN 
year ON students.year_id=year.id
INNER JOIN
sections ON students.section_id=sections.id
INNER JOIN 
courses ON students.course_id = courses.id
INNER JOIN 
staffs ON students.creator_id = staffs.id
INNER JOIN 
accountlist ON accountlist.account_id= students.id
WHERE accountlist.account_type=1 ORDER by students.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$info = [];
if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>

    <?php

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_row()) {
            $info['id'] = $row[0];
            $info['fname'] = $row[1];
            $info['mname'] = $row[2];
            $info['lname'] = $row[3];
            $info['year_id'] = $row[4];
            $info['section_id'] = $row[5];
            $info['course_id'] = $row[6];
            $imgPath =  $studentsPath . $row[0] . "." . displayFileExtension($row[0], 1);
            $totalRecordsInDB = totalStudentRecords($row[0]);

            if (!$row[15]) { //!active
                continue;
            }
            echo "<tr>";
            if (isset($account_type) && $account_type == 4) {

    ?>
                <td>
                    <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'students')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row[0] ?>"><i class="fas fa-edit"></i></button>

                    <?php

                    if ($totalRecordsInDB == 0) {
                    ?>
                        <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this, 'list', 'students')" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row[0] ?>"><i class="fas fa-trash-alt"></i></button>

                    <?php
                    }

                    ?>
                </td>
            <?php
            }


            ?>
            <td><img style="height:9rem;" src="<?php echo $imgPath ?>" alt="<?php echo $fullname = fullName($row[1], $row[2], $row[3]) ?>">
            </td>
            <td>
                <?php
                if ($qrCodeName = hasQrcodeName($row[0])) {
                    echo '<img style="height:9rem;" src="' . $qrcodesPath . $qrCodeName . ".png" . '" alt="' . $fullname . ' QrCode"';
                }
                ?>
            </td>
            <td> <a href="viewRecords.php?stud_id=<?php echo $row[0] ?>"><?php

                                                                            if ($qrCodeName) {
                                                                                echo '<i class="text-success fa-lg fas fa-qrcode"></i>';
                                                                            }
                                                                            $info['account_type'] =  displayAccountBadge('', $fullname, true, $row[15]);
                                                                            ?></a></td>

            <td><?php echo $row[9]; ?></td>
            <td><?php echo $row[10]; ?></td>
            <td><?php echo $row[11]; ?></td>
            <td><?php
                echo fullName($row[12], $row[13], $row[14]);
                ?>
            </td>
            <td><?php echo readableDate($row[8]); ?></td>
            <input type="hidden" value='<?php echo json_encode($info); ?>'>


    <?php
            echo "</tr>";
        }
    }

    ?>


<?php
}

?>