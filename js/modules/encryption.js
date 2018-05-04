<?php
use \Catalyst\Secrets;
?>
window.encryption = new JSEncrypt();
window.encryption.setPublicKey(<?= json_encode(Secrets::RSA_PUBLIC) ?>);

window._randomByte = function() {
	return Math.floor(Math.random()*(0xff+1));
}

window.dec2hex = function(i) {
	return (i + 0x100).toString(16).substr(-2).toLowerCase();
}

window.encryptString = function(a) {
	var inputAsBytes = aesjs.utils.utf8.toBytes(a);
	var paddedInput /* owo */ = aesjs.padding.pkcs7.pad(inputAsBytes);

	// thanks to toish for, once again, fixing my shit JS
	var aesKey = [];
	for (var i = 0; i < 32; i++) {
		aesKey.push(_randomByte());
	}

	var iv = [];
	for (var i = 0; i < 16; i++) {
		iv.push(_randomByte());
	}

	var aesCbc = new aesjs.ModeOfOperation.cbc(aesKey, iv);

	var output = aesCbc.encrypt(paddedInput);
	var safeOutput = aesjs.utils.hex.fromBytes(output);

	var binaryKey = "";
	for (var i = 0; i < aesKey.length; i++) {
		binaryKey += ("" + dec2hex(aesKey[i]));
	}
	var binaryIv = "";
	for (var i = 0; i < iv.length; i++) {
		binaryIv += ("" + dec2hex(iv[i]));
	}

	var finalResult = {
		aesKey: window.encryption.encrypt(binaryKey),
		aesIv: window.encryption.encrypt(binaryIv),
		rawKey: binaryKey,
		rawIv: binaryIv,
		arrKey: aesKey,
		arrIv: iv,
		cipherText: safeOutput
	};

	return JSON.stringify(finalResult);
}
