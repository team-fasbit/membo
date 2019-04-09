<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

$verify = new Verification;
$list = $verify->getProofs();
$count = $verify->getProofs([
    'count' => true,
]);
?>
<table class="table table-striped">
    <tr>
        <th scope="col">User</th>
        <th scope="col">Address Proof</th>
        <th scope="col">ID Proof</th>
        <th scope="col">Action</th>
    </tr>
    <?php
    if ($list) {
        foreach ($list as $item) {
            $user = ossn_user_by_guid($item->owner_guid);
            if (!$user) {
                $verify->deleteProof($item->guid);
                continue;
            }
    ?>
    <tr>
        <td><a target="_blank" href="<?php echo $user->profileURL(); ?>"><?php echo $user->fullname; ?></a></td>
        <td><a class="label label-default" href="<?php echo ossn_site_url("action/verification/download?guid={$item->guid}&type=address_proof", true); ?>">Download Address Proof</a></td>
        <td><a class="label label-default" href="<?php echo ossn_site_url("action/verification/download?guid={$item->guid}&type=id_proof", true); ?>">Download ID Proof</a></td>
        <td><a class="label label-success" href="<?php echo ossn_site_url("action/verification/approve?guid={$item->guid}", true); ?>">Approve</a> &nbsp; <a class="label label-danger" href="<?php echo ossn_site_url("action/verification/deny?guid={$item->guid}", true); ?>">Deny</a></td>
    </tr>
    <?php
        }
    }
    ?>
</table>
<?php
echo ossn_view_pagination($count);
