<?php
include('../../../private/config.php');

$sql = "SELECT * FROM subjects ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$info = [];

if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $info['id'] = $row['id'];
            $info['name_of_subject'] = $row['name_of_subject'];
            $info['description'] = $row['description'];
            $subjectCount = 0;
            $subject[0] = countData('enrolled_subjects', $row['id'], 'subject_id');
            $subject[1] = countData('subject_attendance', $row['id'], 'subject_id');
            $subject[2] = countData('professors_subject_list', $row['id'], 'subject_id');
            foreach ($subject as $num) {
                $subjectCount += $num;
            }

            echo "<tr>";

    ?>
            <td class="text-danger">
                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'subjects')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                <?php
                if ($subjectCount == 0) {

                ?>
                    <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this, 'list','subjects')" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                <?php
                } else {
                    echo "<br>";
                    echo  $subject[0] .  ' <i class="fas fa-database "></i> in Enrolled Subjects Table <br>';
                    echo $subject[1] .  ' <i class="fas fa-database "></i> in Subject Attendance Table <br>';
                    echo $subject[2] .  ' <i class="fas fa-database "></i> in Professor Subject List Table <br>';
                }

                ?>
            </td>

            <td><?php echo $row['name_of_subject']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php
                displayCreator($row['creator_id']);
                ?>
            </td>
            <td><?php echo readableDate($row['date_created']); ?></td>
            <input type="hidden" value='<?php echo json_encode($info); ?>'>
<?php
            echo "</tr>";
        }
    }
}

?>