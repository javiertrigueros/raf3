/*!
 * Webogram v0.0.17 - messaging web application for MTProto
 * https://github.com/zhukov/webogram
 * Copyright (C) 2014 Igor Zhukov <igor.beatle@gmail.com>
 * https://github.com/zhukov/webogram/blob/master/LICENSE
 */

importScripts(
  '/arquematicsTelegramPlugin/js/vendor/console-polyfill/console-polyfill.js',
  '/arquematicsTelegramPlugin/js/mtproto.js',
  '/arquematicsTelegramPlugin/js/vendor/cryptoJS/crypto.js'
);

onmessage = function (e) {
  var taskID = e.data.taskID;

  postMessage({taskID: taskID, result: sha1Hash(e.data.bytes)});
};
