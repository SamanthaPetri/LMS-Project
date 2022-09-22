<?php
// This file is part of Moodle - http://moodle.org/
//
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
 * html render class
 *
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_testblock
 **/
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/repository/lib.php');

/**
 * Class block_testblock_renderer
 *
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_testblock
 */
class block_testblock_renderer extends plugin_renderer_base {
    /**
     * add_javascript_module
     *
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
  /*  public function add_javascript_module() : void {
	
		$repositories = repository::get_instances(
				['type' => 'upload', 'currentcontext' => $options->context]);
		if (empty($repositories)) {
			throw new moodle_exception('errornouploadrepo', 'moodle');
		}
		$uploadrepository = reset($repositories); // Get the first (and only) upload repo.
		$setting = [
				'audioBitRate' => 50,
				'videoBitRate' => 60,
				'maxUploadSize' => 70,
				'uploadRepositoryId' => (int) $uploadrepository->id,
				'contextId' => $options->context->id,
				'draftItemId' => 1,
		];
		
            $this->page->requires->js_call_amd('qtype_recordrtc/avrecording', 'init',
                    [1, $setting]);
					
	}*/
					
	public function add_javascript_module() : void {
        global $CFG, $USER;

        $jsmodule = [
            'name' => 'block_testblock',
            'fullpath' => '/blocks/testblock/module.js',
            'requires' => ['io-base'],
        ];
		
        $this->page->requires->js_init_call('M.block_testblock.init', [
            [
                'sessionid' => $USER->sesskey
                //'uploadPath' => $CFG->wwwroot . '/blocks/mfavatar/ajax.php',
                //'text_select_device' => get_string('flash:textselectdevice', 'block_mfavatar'),
                //'text_make_snapshot' => get_string('flash:text_make_snapshot', 'block_mfavatar'),
                //'text_result_field' => get_string('flash:text_result_field', 'block_mfavatar'),
                //'text_feed_field' => get_string('flash:text_feed_field', 'block_mfavatar'),
                //'failed_saving' => get_string('flash:failed_saving', 'block_mfavatar'),
                //'success_saving' => get_string('flash:success_saving', 'block_mfavatar'),
            ],
        ], false, $jsmodule);		
	}
	
	/**
     * Add the snapshot tool
     *
     * @return string
     * @throws coding_exception
     */
	 
	 
    public function snapshot_tool() : string {
        // TODO Convert to mustache.
        global $USER, $CFG; // Used for the profile link.
		
		return '<style>
    html, body {
        margin: 0 !important;
        padding: 0 !important;
    }
</style>

<title>Video Recording | RecordRTC</title>
<h1>Simple Video Recording using RecordRTC</h1>

<br>

<button id="btn-start-recording">Start Recording</button>
<button id="btn-stop-recording" disabled>Stop Recording</button>

<hr>
<video controls autoplay playsinline></video>

<script src="https://www.webrtc-experiment.com/RecordRTC.js"></script>
<script src="static/js/jquery-1.9.0.js" type="text/javascript"></script>
<script>
var video = document.querySelector(\'video\');

function captureCamera(callback) {
    navigator.mediaDevices.getUserMedia({ audio: true, video: true }).then(function(camera) {
        callback(camera);
    }).catch(function(error) {
        alert(\'Unable to capture your camera. Please check console logs.\');
        console.error(error);
    });
}

function stopRecordingCallback() {
	var videoblob = recorder.getBlob();

    var formData = new FormData();
    formData.append(\'video-filename\', \'testvideo.mp4\');
    formData.append(\'video-blob\', videoblob);

	$.ajax({
		type: "POST",
		enctype: \'multipart/form-data\',
		url: "http://127.0.0.1:5000/uploadVideo",
        data: formData,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000,
        success: onUploadSuccess(),
        error: function (e) {
            console.log("ERROR : ", e);
        }
	});

    recorder.camera.stop();
    recorder.destroy();
    recorder = null;
}

function stopRecordingCallback_original() {
	var videoblob = recorder.getBlob();

    video.src = video.srcObject = null;
    video.muted = false;
    video.volume = 1;
    video.src = URL.createObjectURL(videoblob);

    var data = {};
	data.video = videoblob;
    data.metadata="test metadata";
    data.action = "upload_video";

	jQuery.post("http://127.0.0.1:5000/uploadVideo", data, onUploadSuccess);

    recorder.camera.stop();
    recorder.destroy();
    recorder = null;
}

function onUploadSuccess() {
    alert (\'video uploaded\');
}

var recorder; // globally accessible

document.getElementById(\'btn-start-recording\').onclick = function() {
    this.disabled = true;
    captureCamera(function(camera) {
        video.muted = true;
        video.volume = 0;
        video.srcObject = camera;

        recorder = RecordRTC(camera, {
            type: \'video\'
        });

        recorder.startRecording();

        // release camera on stopRecording
        recorder.camera = camera;

        document.getElementById(\'btn-stop-recording\').disabled = false;
    });
};

document.getElementById(\'btn-stop-recording\').onclick = function() {
    this.disabled = true;
    recorder.stopRecording(stopRecordingCallback);
};
</script>

<footer style="margin-top: 20px;"><small id="send-message"></small></footer>
<script src="https://www.webrtc-experiment.com/common.js"></script> ' ;
		
/*
        return '<div id="snapshotholder_webrtc" style="display: none;">
                    <video autoplay></video>
                    <div id="previewholder">
                        <canvas id="render"></canvas>
                        <canvas id="preview"></canvas>
                    </div>
                 </div>
                 <div class="pt-3 clearboth">
                    <button id="snapshot" class="btn btn-primary">' .
                        get_string('flash:text_make_snapshot', 'block_testblock') . '</button>
					<button id="record" class="btn btn-primary">Record</button>
                    <a href="' . $CFG->wwwroot . '/my" class="btn btn-info">' .
                        get_string('returntodashboard', 'block_testblock') . '</a>
                 </div>';*/
    }
	
}
