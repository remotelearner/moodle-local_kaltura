<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Kaltura check video conversion status AND returns the embedded video markup
 *
 *
 * @package    local
 * @subpackage kaltura
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/local/kaltura/locallib.php');

$entry_id   = required_param('entry_id', PARAM_TEXT);
$height     = optional_param('height', 0, PARAM_INT);
$width      = optional_param('width', 0, PARAM_INT);
$uiconfid   = optional_param('uiconf_id', 0, PARAM_INT);
$title      = optional_param('video_title', '', PARAM_TEXT);
$widget     = optional_param('widget', 'kdp', PARAM_TEXT);
$courseid   = required_param('courseid', PARAM_INT);

require_login();

$thumbnail    = '';
$data         = '';
$entry_obj    = null;

//$myFile = "/tmp/A.txt";
//$fh = fopen($myFile, 'w');
//$stringData = $entry_obj->status;
//fwrite($fh, $stringData);
//fclose($fh);

// If request is for a kaltura dynamic player get the entry object disregarding
// the entry object status
if (0 == strcmp($widget, 'kdp')) {

    $entry_obj = get_ready_entry_object($entry_id, false);

    // Determine the type of video (See KALDEV-28)
    if (!video_type_valid($entry_obj)) {
        $entry_obj = get_ready_entry_object($entry_obj->id, false);
    }

    $entry_obj->height = !empty($height) ? $height : $entry_obj->height;
    $entry_obj->width = !empty($width) ? $width : $entry_obj->width;

    $data = $entry_obj;

    if (KalturaEntryStatus::READY == (string) $entry_obj->status) {

        // Create the user KS session
        $session  = generate_kaltura_session(array($entry_obj->id));

        $data->markup = get_kdp_code($entry_obj, $uiconfid, $courseid, $session);

        if (has_mobile_flavor_enabled() && get_enable_html5()) {
            $data->script = 'kAddedScript = false; kCheckAddScript();';
        }

    } else {
        switch ((string) $entry_obj->status) {
            case KalturaEntryStatus::ERROR_IMPORTING:
                $data->markup = get_string('video_error', 'local_kaltura');
                break;
            case KalturaEntryStatus::ERROR_CONVERTING:
                $data->markup = get_string('video_error', 'local_kaltura');
                break;
            case KalturaEntryStatus::INFECTED:
                $data->markup = get_string('video_bad', 'local_kaltura');
                break;
        }
    }

} elseif (0 == strcmp($widget, 'kpdp')) {
// If request is for a kaltura presentation dynamic player, get the entry object only
// when it is ready
    $entry_obj  = get_ready_entry_object($entry_id);

    $admin_mode = optional_param('admin_mode', 0, PARAM_INT);
    $admin_mode = empty($admin_mode) ? false : true;

    $data->markup = get_kdp_presentation_player($entry_obj, $admin_mode);

    // Pre-set the height and width of the video presentation popup panel
    $data->height = 400;
    $data->width  = 780;

}

$data = json_encode($data);

echo $data;