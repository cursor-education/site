var WebpackStripLoader = require('strip-loader')
  , devConfig = require('./webpack.config.js');

var stripLoader = {
  test: /\.(js|es6)$/,
  exclude: /node_modules/,
  loader: WebpackStripLoader.loader('console.log')
}

devConfig.module.loaders.push(stripLoader);

module.exports = devConfig;
