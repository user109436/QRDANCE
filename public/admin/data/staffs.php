<?php
include('../../../private/config.php');

$sql = "SELECT staffs.id, staffs.fname, staffs.mname, staffs.lname, staffs.tags, staffs.about, staffs.creator_id, staffs.date_created, accountlist.active, accountlist.account_type
FROM staffs
INNER JOIN accountlist ON staffs.id = accountlist.account_id
WHERE accountlist.account_type>=2;";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$info = [];
if (isset($_POST['view']) && $_POST['view'] == 'grid') {
?>
    <div class="row">
        <?php

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $info['id'] = $row['id'];
                $info['fname'] = $row['fname'];
                $info['mname'] = $row['mname'];
                $info['lname'] = $row['lname'];
                $info['tags'] = $row['tags'];
                $info['about'] = $row['about'];
                $imgPath =  $staffsPath . $row['id'] . "." . displayFileExtension($row['id']);
        ?>
                <div class="col-md-3 col-lg-2 col-sm-4 col-6 m-0 mt-3 p-1">
                    <!-- Card Regular -->
                    <div class="card card-cascade">
                        <!-- Card image -->
                        <!-- TODO: data-target="#modalAdd" onclick="edit(id, this,'grid')" -->
                        <div class="view view-cascade overlay">
                            <img class="card-img-top" data-target="#modalAdd" src="<?php echo $imgPath; ?>" alt="<?php echo $fullname = fullName($row['fname'], $row['mname'], $row['lname']) ?>" />
                            <a>
                                <div class="mask rgba-white-slight"></div>
                            </a>
                        </div>

                        <!-- Card content -->
                        <div class="card-body card-body-cascade text-center p-1">
                            <!-- Title -->
                            <h6 class="card-title"><strong><?php
                                                            $info['account_type'] =  displayAccountBadge($row['id'], $fullname, false, $row['active']);
                                                            ?></strong></h6>
                            <!-- Subtitle -->
                            <p class="blue-text m-0"><?php echo $row['tags'] ?></p>
                            <!-- Text -->
                            <p class="card-text">
                                <?php echo $row['about'] ?>
                            </p>
                            <p class="card-text">
                                <?php
                                displayCreator($row['creator_id']);
                                ?>
                            </p>
                            <div class="container">
                                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this,'grid')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this,'grid')" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                                <input type="hidden" id="<?php echo $row['id'] ?>" value='<?php echo json_encode($info); ?>'>
                            </div>
                        </div>
                    </div>
                    <!-- Card Regular -->
                </div>
        <?php
            }
        }
        ?>
    </div>
<?php
}

if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>

    <?php

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $info['id'] = $row['id'];
            $info['fname'] = $row['fname'];
            $info['mname'] = $row['mname'];
            $info['lname'] = $row['lname'];
            $info['tags'] = $row['tags'];
            $info['about'] = $row['about'];
            $imgPath =  $staffsPath . $row['id'] . "." . displayFileExtension($row['id']);
            if (!$row['active']) { //!active
                continue;
            }
            echo "<tr>";

            if (isset($account_type) && $account_type == 4) {

    ?>
                <td>
                    <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this)" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                    <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this)" class=" btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                </td>
            <?php
            }

            ?>
            <td><img class="profileImg" src="<?php echo $imgPath ?>" alt="<?php echo $fullname = fullName($row['fname'], $row['mname'], $row['lname']) ?>"></td>
            <td>
                <a href="viewStaffRecords.php?staff_id=<?php echo $row['id'] ?>">
                    <?php
                    $info['account_type'] =  $row['account_type'];
                    echo accountBadge($row['account_type'], $row['active']) . " " . $fullname;
                    ?>
                </a>
            </td>
            <td><?php
                echo accountLabel($row['account_type']);
                ?></td>

            <td><?php echo $row['tags']; ?></td>
            <td><?php echo $row['about']; ?></td>
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