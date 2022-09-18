import os
from flask import Flask,render_template, request,json
from werkzeug.utils import secure_filename
from flask_uploads import UploadSet, configure_uploads

app = Flask(__name__)

#video_file = UploadSet('media',()
#app.config['UPLOADED_FILES_DEST'] = 'static/files/'
#configure_uploads(app, video_file)

UPLOAD_FOLDER = 'static/files'
ALLOWED_EXTENSIONS = {'mp4'}
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/')
def hello():
    return 'Welcome to Python Flask!'

# FLASK TEST SITES
'''
@app.route('/signUp')
def signUp():
    return render_template('signUp.html')

@app.route('/signUpUser', methods=['POST'])
def signUpUser():
    user =  request.form['username'];
    password = request.form['password'];
    return json.dumps({'status':'OK','user':user,'pass':password});
'''

# Video capture debug
@app.route('/video')
def video():
    return render_template('video.html')

# Handler for photo uploads
@app.route('/uploadPhoto', methods=['POST'])
def uploadPhoto():
     return json.dumps({'status':'NOK','error': 'no file' });

# Handler for video uploads       
@app.route('/uploadVideo', methods=['POST'])
def uploadVideo():
    student_id = request.form['student-id'];
    course_id = request.form['course-id'];
    quiz_id = request.form['quiz-id'];
    timestamp = request.form['timestamp'];
    video_filename = request.form['video-filename'];
    
    if allowed_file(video_filename) and request.method == 'POST' and 'video-blob' in request.files:
        video_file = request.files['video-blob'];
        filename = secure_filename(video_filename); # Secure the filename to prevent some kinds of attack
        video_file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename));    
        return json.dumps({'status':'OK','video-filename': video_filename });

    return json.dumps({'status':'NOK','error': 'no file' });

# Handler for image analysis request

if __name__=="__main__":
    app.run()   