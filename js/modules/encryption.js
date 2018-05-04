window.encryption = new JSEncrypt();
window.encryption.setPublicKey(<?= json_encode(Secrets::RSA_PUBLIC) ?>);
