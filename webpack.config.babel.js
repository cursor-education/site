import webpack from 'webpack';
import path from 'path';
import CleanWebpackPlugin from 'clean-webpack-plugin';
import CopyWebpackPlugin from 'copy-webpack-plugin';
import HtmlWebpackPlugin from 'html-webpack-plugin';
import ExtractTextPlugin from "extract-text-webpack-plugin";
import autoprefixer from 'autoprefixer';

const IS_DEVELOPMENT = 'development' === process.env.NODE_ENV;

let config = {
  cache: true,

  // set context where sources are located
  context: path.join(__dirname, 'src'),

  // get entry points of app
  entry: (() => {
    let entries = {};

    if (IS_DEVELOPMENT) {
      entries['dev'] = 'webpack-dev-server/client?http://0.0.0.0:8080';
      entries['dev-hot'] = 'webpack/hot/only-dev-server';
    }

    require('glob')
      .sync('./scripts/pages/page-*.js', { cwd:'./src' })
      .forEach((x) => entries[path.basename(x, '.js')] = x);

    return entries;
  })(),

  //
  output: {
    path: path.resolve(__dirname, "dist"),
    publicPath: "/",
    filename: "[name].[hash:6].js"
  },

  devtool: 'source-map',

  devServer: (() => {
    if (IS_DEVELOPMENT) {
      return {
        host: '0.0.0.0',
        port: '8080',
        colors: true,
        inline: true,
        historyApiFallback: true,
        hot: true,
        quiet: false,
        progress: true,
        contentBase: path.join(__dirname, '/dist'),
        // publicPath
        outputPath: path.join(__dirname, 'dist'),
        stats: {
          colors: true
        }
      }
    }
  })(),

  // watch: true,

  plugins: [
    new ExtractTextPlugin("[name].[contenthash:6].css"),
    new HtmlWebpackPlugin({
      title: 'test 123',
      inject: !false,
      template: './index.html'
    }),
    new webpack.HotModuleReplacementPlugin(),
    // new CopyWebpackPlugin([
    //   { from: 'index.html' }
    // ]),
    new webpack.DefinePlugin({
      '__DEV__': true,
      'process.env': {
        'NODE_ENV': JSON.stringify('production')
      }
    }),
    // new webpack.optimize.CommonsChunkPlugin({
    //   name: "commons"
    // })
  ],
  module: {
    noParse: [
      /angular/,
    ],
    // preLoaders: [
    //   {
    //     test: /\.js$/,
    //     exclude: /node_modules/,
    //     loader: 'eslint-loader'
    //   }
    // ],
    loaders: [
      // { test: /\.html$/, loader: "html" },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel',
        query: {
          presets: ['es2015']
        }
      },
      // { test: /\.html$/, exclude: /tmp/, loader: "ng-cache-loader" },
      // { test: /\.svg/, loader: 'file?name=/img/[hash].[ext]?' },
      // { test: /\.(png|jpg|woff|woff2|eot|ttf|otf)/, loader: 'url-loader' },
      { test: /\.json$/, loader: 'json' },
      {
        test: /\.scss$/,
        loader: ExtractTextPlugin.extract('style', 'css!sass!postcss')
      },
    ]
  },
  postcss: function () {
    return {
      defaults: [autoprefixer],
      cleaner: [autoprefixer({ browsers: [] })]
    }
  },
  resolve: {
    root: path.join(__dirname, 'src'),
    extensions: ['', '.js']
  },
}

if (IS_DEVELOPMENT) {
  // config.plugins.push(
  //   new CleanWebpackPlugin(['dist/*'], {
  //     root: __dirname,
  //     verbose: true,
  //     dry: false,
  //   })
  // );
}

// sassLoader: {
//     data: "$env: " + process.env.NODE_ENV + ";"
//   }

// loader: "ng-annotate?add=true!babel"


export default config;
