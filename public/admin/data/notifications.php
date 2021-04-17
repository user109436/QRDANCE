<?php
include('../../../private/config.php');

$notifications = findAll('notifications');
$info = [];

if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>
    <div class="container">
        <div class="row">
            <?php

            if ($notifications) {
                foreach ($notifications as $row) {
                    $info['id'] = $row['id'];
                    $info['subject'] = $row['subject'];
                    $info['message'] = $row['message'];
                    $info['account_id'] = $row['account_id'];
            ?>
                    <div class="col-12 mb-2 p-3" style="box-shadow:0 1px 2px grey; background-color:white">
                        <p class="font-weight-bold">Subject: <?php echo $row['subject'] ?></h6>
                        <p class="font-weight-bold">To: <?php echo recipient($row['account_type']) ?></p>

                        <div class="container">
                            <blockquote>
                                <p><?php echo $row['message'] ?></p>
                            </blockquote>
                        </div>
                        <p class="float-right">From: <?php
                                                        displayAccountBadge($row['creator_id'], displayCreator($row['creator_id']));
                                                        echo "<br> " . readableDate($row['date_created']);
                                                        ?></p>


                        <!-- if i'm the author allow me to edit and delete -->
                        <?php
                        if ($row['creator_id'] == $creator_id) {

                        ?>
                            <div class="col-12">
                                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'notifications')" class="btn-sm btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                <button data-toggle="modal" data-target="#modalDelete" onclick="del(this.value, this, 'list','notifications')" class=" btn-sm btn btn-danger btn-sm" id="delete" value="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        <?php
                        }

                        ?>
                        <input type="hidden" value='<?php echo json_encode($info); ?>'>
                    </div>


            <?php
                }
            } else {
                echo message("No Record Found In Important Announcement", 4);
            }
            ?>
        </div>
    </div>
<?php
}

?>