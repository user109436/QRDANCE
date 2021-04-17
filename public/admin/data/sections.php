<?php
include('../../../private/config.php');

$sql = "SELECT * FROM sections ";
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
            $info['section'] = $row['section'];
            $sectionCount = countData('students', $row['id'], 'section_id');

            echo "<tr>";

    ?>
            <td class="text-danger">
                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'sections')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                <?php
                if ($sectionCount == 0) {

                ?>
                    <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this, 'list','sections')" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                <?php
                } else {
                    echo $sectionCount . ' <i class="fas fa-database "></i> in Students Table';
                }
                ?>
            </td>

            <td><?php echo $row['section']; ?></td>
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