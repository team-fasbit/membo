<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

// echo '<p>SOMETHING TO DISPLAY</p>';
// echo '<p>ON</p>';
// echo '<p>VERIFICATION PAGE</p>';

echo ossn_view_form('verification/verification', array(
    'action' => ossn_site_url() . 'action/goblueadvancesettings/verification',
));