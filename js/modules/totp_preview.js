<?php
header("Content-Type: application/javascript; charset=UTF-8", true);
?>
function totp(K,t) {
    function sha1(C){
        function L(x,b){
            return x<<b|x>>>32-b;
        }
        var l=C.length;
        var D=C.concat([1<<31]);
        var V=0x67452301;
        var W=0x88888888;
        var Y=271733878;
        var X=Y^W;
        var Z=0xC3D2E1F0;
        W^=V;
        do {
            D.push(0);
        } while (D.length+1&15);
        D.push(32*l);
        while (D.length) {
            var E=D.splice(0,16);
            var a=V;
            var b=W;
            var c=X;
            var d=Y;
            var e=Z;
            var f;
            var k;
            var i=12;
            function I(x){
                var t=L(a,5)+f+e+k+E[x];
                e=d;
                d=c;
                c=L(b,30);
                b=a;
                a=t;
            }
            for(;++i<77;) {
                E.push(L(E[i]^E[i-5]^E[i-11]^E[i-13],1));
            }
            k=0x5A827999;
            for (i=0;i<20;I(i++)) {
                f=b&c|~b&d;
            }
            k=0x6ED9EBA1;
            for (;i<40;I(i++)) {
                f=b^c^d;
            }
            k=0x8F1BBCDC;
            for (;i<60;I(i++)) {
                f=b&c|b&d|c&d;
            }
            k=0xCA62C1D6;
            for (;i<80;I(i++)) {
                f=b^c^d;
            }
            V+=a;
            W+=b;
            X+=c;
            Y+=d;
            Z+=e;
        }
        return[V,W,X,Y,Z];
    }
    var k=[];
    var l=[];
    var i=0;
    var j=0;
    var c=0;
    for (;i<K.length;) {
        c=c*32+'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'.indexOf(K.charAt(i++).toUpperCase());
        if ((j+=5)>31) {
            k.push(Math.floor(c/(1<<(j-=32)))),c&=31;
        }
    }
    j&&k.push(c<<(32-j));
    for(i=0;i<16;++i) {
        l.push(0x6A6A6A6A^(k[i]=k[i]^0x5C5C5C5C));
    }
    var s=sha1(k.concat(sha1(l.concat([0,t]))));
    var o=s[4]&0xF;
    var out=""+(((s[o>>2]<<8*(o&3)|(o&3?s[(o>>2)+1]>>>8*(4-o&3):0))&-1>>>1)%1000000);
    while ((out).length < 6) {
        out = "0"+out;
    }

    window.log(<?= json_encode(basename(__FILE__)) ?>, "totp - getting code for "+K+" at t="+t+": "+out);

    return out;
}
