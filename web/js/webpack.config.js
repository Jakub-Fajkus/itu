const path = require('path');
const JS_ROOT = path.resolve(__dirname);

module.exports = {
    entry: ['babel-polyfill', path.join(JS_ROOT, 'main.js')],
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
                    options: {
                        presets: [
                            'es2015',
                            'stage-2'
                        ],
                        plugins: ['transform-es2015-spread']
                    }
                }]
            },
        ],
    },
    resolve: {
        mainFields: ['browser', 'module', 'main'],
        modules: ['node_modules'],
    },
};
