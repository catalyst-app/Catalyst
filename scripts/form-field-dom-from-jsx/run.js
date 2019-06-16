var nativejsx = require("nativejsx");
var fs = require('fs');

fs.readdir("./src", function(err, items) {
  for (let i=0; i<items.length; i++) {
    console.log("Parsing ./src/"+items[i]);

    nativejsx.parse("src/"+items[i], {
      declarationType: "var",
      variablePrefix: "$$"
    }).then(function(output) {
      fs.writeFile("./out/"+items[i]+".out.js", output);
      console.log("Wrote ./out/"+items[i]+".out.js !");
    });
  }
});
