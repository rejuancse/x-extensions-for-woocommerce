# X-Extensions for WooCommerce

## Getting Started

- You can make sure that the necessary dependencies are correctly installed by doing `npm install`
- After installing your NPM dependencies, within the project, you can run the following commands to work with the developer tools bootstrapped to the project:
- Start development environment and watch file changes `npm start`
- Build all assets of the project for production `npm run build`

## Structure

We're using [Gulp](https://gulpjs.com/) to generate our static files into the `assets/dist` folder.

Any assets that need to be added to the project will need to be added into the `assets/src` folder within the project.

- CSS/SASS files are imported through `index.scss` within the `assets/src/scss` folder. All CSS is being compiled with auto-generated prefixes for all CSS elements using [CSSNext](https://cssnext.github.io/)
- JS files are imported through `index.js` within the `/assets/src/js` folder. All JS is being compiled to a browser compatible format using [Babel](https://babeljs.io/). As a result, you can employ the use of ES6+ features if desired.
- Images are also being minified and can be placed within the `/assets/src/images` folder to take advantage of this feature of the project.

## Troubleshooting

### Terminal error output when running `npm start` or `npm run build`.

The Replit environment sometimes throws an error when trying to run the image optimization process, if this happens;
follow these steps:

- Go to `gulpfile.babel.js` and comment out lines `192` through `196`
- Run `npm start` or `npm run build` again.
- Uncomment the lines again and run the build again.
- After run `npm run build` and then delete `.map` files

Good luck!
