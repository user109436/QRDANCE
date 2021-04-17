<?php
include('../../../private/config.php');

$courses = findAll('courses');
$info = [];

if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>

    <?php

    if ($courses) {
        foreach ($courses as $row) {
            $info['id'] = $row['id'];
            $info['course_acronym'] = $row['course_acronym'];
            $info['course'] = $row['course'];
            $courseCount = countData('students', $row['id'], 'course_id');

            echo "<tr>";

    ?>
            <td class="text-danger">
                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'courses')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>

                <?php
                if ($courseCount == 0) {

                ?>
                    <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this, 'list','courses')" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                <?php
                } else {
                    echo $courseCount . ' <i class="fas fa-database "></i> in Students Table';
                }

                ?>
            </td>

            <td><?php echo $row['course_acronym']; ?></td>
            <td><?php echo $row['course']; ?></td>
            <td><?php
                displayCreator($row['creator_id']);
                ?>
            </td>
            <td><?php echo readableDate($row['date_created']); ?></td>
            <input type="hidden" value='<?php echo json_encode($info); ?>'>
<?php
            echo "</tr>";
        }
    } else {
        echo "<h5>0 Results in Courses</h5>";
    }
}

?>