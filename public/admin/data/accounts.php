<?php
include('../../../private/config.php');

$accountlist = findAll('accountlist');
$info = [];

if (isset($_POST['view']) && $_POST['view'] == 'list') {
?>

    <?php

    if ($accountlist) {
        foreach ($accountlist as $row) {

            $info['id'] = $row['id'];
            $info['username'] = $row['username'];
            $info['encrypted_password'] = $row['encrypted_password'];
            $info['email'] = $row['email'];
            $info['active'] = $row['active'];
            $info['account_id'] = $row['account_id'];
            echo "<tr>";
    ?>
            <td>
                <button data-toggle="modal" data-target="#modalAdd" onclick="edit(this.value, this, 'list', 'accounts')" class="btn btn-info btn-sm" id="edit" value="<?php echo $row['id'] ?>"><i class="fas fa-edit"></i></button>
                <button onclick="sendEmail(<?php echo $row['id'] ?>)" class="btn btn-purple btn-sm"><i class="fas fa-paper-plane"></i></button>
            </td>
            <td><?php

                $user = getAccountDetails($row['account_id'], $row['account_type']);
                $name = $user->fetch_assoc();
                if (hasQrcodeName($row['account_id']) && $row['account_type'] == 1) {
                    echo '<i class="text-success fa-lg fas fa-qrcode"></i>';
                }
                echo accountBadge($row['account_type'], $row['active']) . " " . fullName($name['fname'], $name['mname'], $name['lname']);

                ?></td>
            <td><?php
                echo accountLabel($row['account_type']);
                ?></td>
            <td>
                <?php
                echo accountActive($row['active']);

                ?>
            </td>

            <td><?php echo !empty($row['username']) ? $row['username'] : '<i class=" text-danger animated pulse infinite fa-lg fas fa-user-edit"></i>'; ?></td>
            <td><?php echo !empty($row['encrypted_password']) ? $row['encrypted_password'] : '<i class="text-danger animated pulse infinite fa-lg fas fa-user-lock"></i>'; ?></td>
            <td><?php echo !empty($row['email']) ? $row['email'] : '<i class=" text-danger animated pulse infinite fa-lg far fa-envelope"></i>' ?></td>
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