<?php
use \Catalyst\Secrets;
?>
window.encryption = new JSEncrypt();
window.encryption.setPublicKey(<?= json_encode(Secrets::RSA_PUBLIC) ?>);

window.dec2hex = function(i) {
	return (i + 0x100).toString(16).substr(-2).toLowerCase();
}

window.encryptString = function(a) {
	var inputAsBytes = aesjs.utils.utf8.toBytes(a);
	var paddedInput /* owo */ = aesjs.padding.pkcs7.pad(inputAsBytes);

	// thanks to toish for, once again, fixing my shit JS
	var aesKeyWords = sjcl.random.randomWords(32/4, 10);
	var aesKey = [];
	for (var i = 0; i < aesKeyWords.length; i++) {
		for (var j = 0; j < 4; j++) {
			var byte = aesKeyWords[i] & 0xff;
			aesKey.push(byte);
			aesKeyWords[i] = (aesKeyWords[i] - byte) / 256;
		}
	}

	var ivWords = sjcl.random.randomWords(16/4, 10);
	var iv = [];
	for (var i = 0; i < ivWords.length; i++) {
		for (var j = 0; j < 4; j++) {
			var byte = ivWords[i] & 0xff;
			iv.push(byte);
			ivWords[i] = (ivWords[i] - byte) / 256;
		}
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
