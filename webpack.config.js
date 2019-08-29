const fs = require('fs');
const notifier = require('node-notifier');
const path = require('path');
const webpack = require('webpack');
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin');

module.exports = (env, argv) => {
  const isProd = argv.mode === 'production';
  const mode = isProd ? 'production' : 'development';

  process.env.BABEL_ENV = mode;
  process.env.NODE_ENV = mode;

  return {
    mode,
    devtool: !isProd ? 'cheap-module-source-map' : false,
    entry: path.resolve('assets/src/js/index.js'),
    output: {
      filename: `[name]${isProd ? '.min' : ''}.js`,
      chunkFilename: `[id]${isProd ? '.[chunkhash].min' : ''}.js`,
      path: path.resolve('assets/build/'),
    },
    plugins: [
      new webpack.HashedModuleIdsPlugin(),
      new FriendlyErrorsWebpackPlugin({
        onErrors(severity, errors) {
          if (severity !== 'error') {
            return;
          }

          const error = errors[0];

          notifier.notify({
            title: error.name,
            message: error.message || '',
            subtitle: error.file || '',
            icon: 'icon.png',
          });
        },
      }),
    ],
    module: {
      rules: [
        {
          oneOf: [
            {
              test: /\.js$/,
              include: path.resolve('assets/src/js/'),
              use: [
                {
                  loader: 'babel-loader',
                },
                {
                  loader: 'eslint-loader',
                  options: {
                    fix: true,
                    format: 'pretty',
                  },
                },
              ],
            },
            {
              include: path.resolve('assets/src/'),
              loader: 'file-loader',
              options: {
                outputPath: path.resolve('assets/build/'),
                name: `[name]${isProd ? '.[hash]' : ''}.[ext]`,
              },
            },
          ],
        },
      ],
    },
    watchOptions: {
      ignored: /(node_modules|bower_components|jspm_packages)/,
      poll: 1000,
    },
  };
};
