<?php
include('../../../private/config.php');

$years = findAll('year');
$info = [];

if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>

    <?php

    if ($years) {
        foreach ($years as $row) {
            $info['id'] = $row['id'];
            $info['year'] = $row['year'];
            $yearCount = countData('students', $row['id'], 'year_id');

            echo "<tr>";
    ?>
            <td class="text-danger">
                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'year')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                <?php
                if ($yearCount == 0) {


                ?>
                    <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this, 'list','year')" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                <?php
                } else {
                    echo $yearCount . ' <i class="fas fa-database "></i> in Students Table';
                }

                ?>
            </td>

            <td><?php echo $row['year']; ?></td>
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
        echo "<h5>0 Results in Year</h5>";
    }
}

?>