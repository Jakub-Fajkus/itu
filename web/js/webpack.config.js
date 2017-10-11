const path = require('path');
const JS_ROOT = path.resolve(__dirname);

module.exports = {
  entry: path.join(JS_ROOT, 'main.js'),
  output: {
    path: path.join(JS_ROOT, 'dist'),
    filename: 'bundle.js'
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        use: [{
	  loader: 'babel-loader',
          options: {presets: [
            'es2015'
          ]}
        }]
      }
    ]
  }
};
