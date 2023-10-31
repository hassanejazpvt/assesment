const path = require('path');

module.exports = {
    entry: [
        __dirname + '/resources/js/app.js',
        __dirname + '/resources/scss/app.scss'
    ],
    output: {
        path: path.resolve(__dirname, 'public'), 
        filename: 'js/app.min.js',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [],
            }, {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'file-loader',
                        options: { outputPath: 'css/', name: '[name].min.css'}
                    },
                    'sass-loader'
                ]
            }
        ]
    }
};