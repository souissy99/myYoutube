/* eslint-disable */
const ffmpeg = require('ffmpeg');
const chokidar = require('chokidar');
const util = require('util');
const execFile = util.promisify(require('child_process').execFile);
const fileName = require('path');
const { encode } = require('punycode');

chokidar.watch('./video', {
    ignored: /(^|[\/\\])\../,
    persistent: true
  })
  .on('add', (event, path) => {
    try {
        var process = new ffmpeg(event);
        process.then(function (video) {

            let newName = fileName.basename(event).split('.').slice(0, -1).join('.');
            encode360(newName, event);
            encode480(newName, event);
            encode720(newName, event);
            encode1080(newName, event);

        }, function (err) {
            console.log(`Error: ${err}`);
        });
    } catch (e) {
        console.log(e.code);
        console.log(e.msg);
    }
  });

async function encode360(videoName, event) {
            if (!event.includes("480p") && !event.includes("720p") && !event.includes("1080p") && !event.includes("360p")) {
               const { stdout } = await execFile('ffmpeg', [
                '-i', event,
                '-preset', 'slow',
                '-codec:a', 'aac',
                '-b:a', '128k',
                '-c:v', 'libx264',
                '-pix_fmt', 'yuv420p',
                '-b:v', '750k',
                '-minrate', '400k',
                '-maxrate', '1000k',
                '-bufsize', '1500k',
                '-vf', 'scale=-1:360',
                'video/360p' + videoName + '.mp4'
            ]);
                console.log("Encodage 360p terminé");
            } else 
                console.log('no' + event)


}

async function encode480(videoName, event) {
       if (!event.includes("480p") && !event.includes("720p") && !event.includes("1080p") && !event.includes("360p")) {
        const { stdout } = await execFile('ffmpeg', [
            '-i', event,
            '-preset', 'slow',
            '-codec:a', 'aac',
            '-b:a', '128k',
            '-c:v', 'libx264',
            '-pix_fmt', 'yuv420p',
            '-b:v', '1000k',
            '-minrate', '500k',
            '-maxrate', '2000k',
            '-bufsize', '2000k',
            '-vf', 'scale=854:480',
            'video/480p' + videoName + '.mp4'
        ]);
        console.log("Encodage 480p terminé");
    } else 
         console.log('no' + event)
}

async function encode720(videoName, event) {
       if (!event.includes("480p") && !event.includes("720p") && !event.includes("1080p") && !event.includes("360p")) {
        const { stdout } = await execFile('ffmpeg', [
            '-i', event,
            '-preset', 'slow',
            '-codec:a', 'aac',
            '-b:a', '128k',
            '-c:v', 'libx264',
            '-pix_fmt', 'yuv420p',
            '-b:v', '2500k',
            '-minrate', '1500k',
            '-maxrate', '4000k',
            '-bufsize', '5000k',
            '-vf', 'scale=-1:720',
            'video/720p' + videoName + '.mp4'
        ]);
        console.log("Encodage 720p terminé");
    } else 
         console.log('no' + event)
}

async function encode1080(videoName, event) {
       if (!event.includes("480p") && !event.includes("720p") && !event.includes("1080p") && !event.includes("360p")) {
        const { stdout } = await execFile('ffmpeg', [
            '-i', event,
            '-preset', 'slow',
            '-codec:a', 'aac',
            '-b:a', '128k',
            '-c:v', 'libx264',
            '-pix_fmt', 'yuv420p',
            '-b:v', '4500k',
            '-minrate', '4500k',
            '-maxrate', '9000k',
            '-bufsize', '9000k',
            '-vf', 'scale=-1:1080',
            'video/1080p' + videoName + '.mp4'
        ]);
        console.log("Encodage 1080p terminé");
    } else 
         console.log('no' + event)
}