/**
 * Adding the flash container to view page also this will try to update img.profilepic
 * Added support for detecting webrtc most modern browser will support this
 *
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package block_testblock
 **/
// @TODO rewrite to amd.
M.block_testblock = {

    /**
     * Logging.
     * @param val
     */
    log: function(val) {
        try {
            console.log(val);
        } catch (e) {

        }
    },

    /**
     * Init
     *
     * @param Y
     * @param applicationpath
     * @param expresspath
     * @param options
     * @param supportwebrtc
     */
    init: function(Y, options) {


        if (location.protocol != 'https:') {
            alert('Microphone and Camera access no longer works on insecure origins. ' +
                'To use this feature, you should consider switching your application to a secure origin, ' +
                'such as HTTPS. See https://goo.gl/rStTGz for more details.');
        }

        if (this.webrtc_is_supported() === false) {
            alert('WebRTC is not supported');
            return;
        }

        M.block_testblock.log('We have support for Webrtc');
        Y.one('#snapshotholder_webrtc').setStyle('display', 'block');

        this.webrtc_load(options);
    },

    /**
     *
     * @param options
     */
    webrtc_load: function(options) {
        var snapshotButton = document.querySelector('button#snapshot');
		var recordButton = document.querySelector('button#record');
        var video = window.video = document.querySelector('video');
        var canvasrender = window.canvas = document.querySelector('canvas#render');
        var canvaspreview = window.canvas = document.querySelector('canvas#preview');

		recordButton.onclick = function() { };

        snapshotButton.onclick = function() {
            canvasrender.width = video.videoWidth;
            canvasrender.height = video.videoHeight;

            // video size
            canvasrender.getContext('2d').drawImage(video, 0, 0, canvasrender.width, canvasrender.height);

            // preview small
            canvaspreview.getContext('2d').drawImage(video, 0, 0, canvaspreview.width, canvaspreview.height);
            // set saved text
            canvaspreview.getContext('2d').font = "30px Comic Sans MS";
            canvaspreview.getContext('2d').fillStyle = "white";
            canvaspreview.getContext('2d').textAlign = "center";
            canvaspreview.getContext('2d').fillText("Saved!", canvas.width / 2, canvas.height / 2);
			
			//const stream = canvasElt.captureStream(25); // 25 FPS
			//stream.getTracks().forEach((track) => pc.addTrack(track, stream));
            
			//var data = canvasrender.captureStream(25);
		
			var data = canvasrender.toDataURL('image/png');
            YUI().use('io-base', function(Y) {
                // Saving the file.
                var cfg = {
                    method: 'POST',
                    data: {
                        'sesskey': options.sessionid,
                        'file': data
                    }
                };
                var request = Y.io(options.uploadPath, cfg);

                // On completed request.
                Y.on('io:complete', onComplete, Y);
            });
        };

        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

        var constraints = {
            audio: false,
            "video": {
                "mandatory": {
                    "minWidth": "480",
                    "minHeight": "480",
                    "minFrameRate": "30",
                    "minAspectRatio": "1",
                    "maxWidth": "480",
                    "maxHeight": "480",
                    "maxFrameRate": "30",
                    "maxAspectRatio": "1"
                },
                "optional": []
            }
        };

        /**
         *
         * @param stream
         */
        function successCallback(stream) {

            window.stream = stream; // make stream available to browser console
            if (window.URL) {
                try {
                    video.srcObject = stream;
                } catch (e) {
                    video.src = window.URL.createObjectURL(stream);
                }
            } else {
                video.srcObject = stream;
            }
        }

        /**
         * onComplete
         *
         * @param transactionid
         * @param response
         * @param arguments
         */
        function onComplete(transactionid, response, arguments) {
            try {
                var json = JSON.parse(response.response);

                if (json.status) {
                    // Reload profile picture.
                    M.block_testblock.saved(json.img);
                }

                M.block_testblock.log(json);
            } catch (exc) {
                console.log(exc);
            }
        }

        function errorCallback(error) {
            console.log('navigator.getUserMedia error: ', error);
        }

        navigator.getUserMedia(constraints, successCallback, errorCallback);
    },

    /**
     * Check if webrtc is supported.
     * HTTPS also needed to be enabled.
     *
     * @returns {boolean}
     */
    webrtc_is_supported: function() {
        return !!(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia ||
            navigator.msGetUserMedia) && location.protocol == 'https:';
    },

    /**
     * Called when avatar is saved.
     */
    saved: function(srcimg) {
        this.log('Saved!!!');
        var profilePicture = Y.one('img.userpicture');
        if (profilePicture) {
            profilePicture.setAttribute('src', '');
            setTimeout(function() {
                var now = new Date().getTime() / 1000;
                profilePicture.setAttribute('src', srcimg + '&c=' + now);
            }, 500);
        }
    },

    /**
     * Error message.
     * @param err
     */
    error: function(err) {
        M.block_testblock.log('Error!');
        M.block_testblock.log(err);
    }
};

