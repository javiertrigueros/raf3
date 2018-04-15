/*!
 * Webogram v0.0.17 - messaging web application for MTProto
 * https://github.com/zhukov/webogram
 * Copyright (C) 2014 Igor Zhukov <igor.beatle@gmail.com>
 * https://github.com/zhukov/webogram/blob/master/LICENSE
 */

importScripts(
  '/arquematicsTelegramPlugin/js/vendor/console-polyfill/console-polyfill.js',
  '/arquematicsTelegramPlugin/js/mtproto.js',
  '/arquematicsTelegramPlugin/js/vendor/jsbn/jsbn_combined.js',
  '/arquematicsTelegramPlugin/js/vendor/cryptoJS/crypto.js'
);

onmessage = function (e) {
  // console.log('AES worker in', e.data);
  var taskID = e.data.taskID,
      result;

  if (e.data.task === 'encrypt') {
    result = aesEncrypt(e.data.bytes, e.data.keyBytes, e.data.ivBytes);
  } else {
    result = aesDecrypt(e.data.encryptedBytes, e.data.keyBytes, e.data.ivBytes);
  }
  postMessage({taskID: taskID, result: result});
};
