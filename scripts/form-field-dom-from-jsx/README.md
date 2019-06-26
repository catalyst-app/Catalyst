## nativejsx usage in Catalyst

The `nativejsx` package is currently used as an integral part of our form library.
This library empowers us to directly convery HTML-esque code with embedded JavaScript (JSX) into standalone DOM API calls.

Although we are not planning on moving entirely to an entirely JS solution throughout our stack, this helps to improve our form library by moving the rendering to the client, preventing unecessary (and sloppy!) code duplication.

To use this, run:
```sh
$ npm install
$ mkdir out
$ node run.js
```

This will compile all of the JSX in the src/ folder and place similarly-named filies in the out/ folder.

Please note, I am no expert in Node by any means, so I am not terribly sure what the requirements for versioning are.  I do know it works on node version 6.1.0, so I have been using that.
