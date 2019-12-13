// Node
const path = require("path");

// Webpack
const webpack = require("webpack");
const merge = require("webpack-merge");

// Webpack plugins
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const {VueLoaderPlugin} = require("vue-loader");
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin;
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin');

// Vue
const VUE_VERSION = require("vue/package.json").version;
const VUE_LOADER_VERSION = require("vue-loader/package.json").version;

// Other
const devMode = process.env.NODE_ENV !== "production";

// Webpack abilities
const WEBPACK_DEV_SERVER_HOST = process.env.WEBPACK_DEV_SERVER_HOST || "localhost";
const WEBPACK_DEV_SERVER_PORT = parseInt(process.env.WEBPACK_DEV_SERVER_PORT, 10) || 8080;
const WEBPACK_DEV_SERVER_PROXY_HOST = process.env.WEBPACK_DEV_SERVER_PROXY_HOST || "localhost";
const WEBPACK_DEV_SERVER_PROXY_PORT = parseInt(process.env.WEBPACK_DEV_SERVER_PROXY_PORT, 10) || 8000;
const WEBPACK_REPORT = process.env.WEBPACK_REPORT || false;

// Config
const ROOT_PATH = __dirname;
const CACHE_PATH = ROOT_PATH + "/temp/webpack";

module.exports = {
	mode: devMode ? "development" : "production",
	context: path.join(ROOT_PATH, "app/assets"),
	entry: {
		front: path.join(ROOT_PATH, "app/assets/front.js"),
		admin: path.join(ROOT_PATH, "app/assets/admin.js"),
	},
	output: {
		path: path.join(ROOT_PATH, "www/dist"),
		publicPath: "/dist/",
		filename: '[name].bundle.js',
	},
	devtool: 'cheap-module-eval-source-map',
	node: {
		setImmediate: false,
		process: 'mock',
		dgram: 'empty',
		fs: 'empty',
		net: 'empty',
		tls: 'empty',
		child_process: 'empty'
	},
	module: {
		noParse: /^(vue|vue-router|vuex|vuex-router-sync)$/,
		rules: [
			{
				test: /\.vue$/,
				use: [
					...!devMode ? [] : [
						{
							loader: 'cache-loader',
							options: {
								cacheDirectory: path.join(CACHE_PATH, "vue-loader"),
								cacheIdentifier: [
									process.env.NODE_ENV || 'development',
									webpack.version,
									VUE_VERSION,
									VUE_LOADER_VERSION,
								].join('|'),
							}
						}
					],
					...[{
						loader: 'vue-loader',
						options: {
							compilerOptions: {
								preserveWhitespace: false
							},
							cacheDirectory: path.join(CACHE_PATH, "vue-loader"),
							cacheIdentifier: [
								process.env.NODE_ENV || 'development',
								webpack.version,
								VUE_VERSION,
								VUE_LOADER_VERSION,
							].join('|'),
						}
					}],
				]
			},
			{
				test: /\.js$/,
				exclude: file => (
					/node_modules/.test(file) &&
					!/\.vue\.js/.test(file)
				),
				use: [
					...!devMode ? [] : [
						{
							loader: 'cache-loader',
							options: {
								cacheDirectory: path.join(CACHE_PATH, "babel-loader"),
							}
						},
						{
							loader: 'thread-loader',
							options: {
								workers: require('os').cpus().length - 1,
							},
						},
					],
					...[{
						loader: 'babel-loader',
					}],
				]
			},
			{
				test: /\.tsx?$/,
				exclude: /node_modules/,
				use: [
					...!devMode ? [] : [
						{
							loader: 'cache-loader',
							options: {
								cacheDirectory: path.join(CACHE_PATH, "ts-loader"),
							}
						},
					],
					...[{
						loader: 'awesome-typescript-loader',
					}],
				]
			},
			{
				test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/i,
				use: [
					{
						loader: 'url-loader',
						options: {
							limit: 4096,
							fallback: {
								loader: 'file-loader',
								options: {
									name: 'fonts/[name].[hash:8].[ext]'
								}
							}
						}
					}
				]
			},
			{
				test: /\.(svg)(\?.*)?$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: 'imgs/[name].[hash:8].[ext]'
						}
					}
				]
			},
			{
				test: /\.(png|jpe?g|gif|webp|ico)(\?.*)?$/,
				use: [
					{
						loader: 'url-loader',
						options: {
							limit: 4096,
							fallback: {
								loader: 'file-loader',
								options: {
									name: 'imgs/[name].[hash:8].[ext]'
								}
							}
						}
					}
				]
			},
			{
				test: /\.less$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: false,
							importLoaders: 2,
							modules: false
						}
					},
					{
						loader: "postcss-loader",
						options: {
							ident: "postcss",
							plugins: [require("autoprefixer")]
						}
					},
					"less-loader"
				],
			},
			{
				test: /\.(css|scss|sass)$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: false,
							importLoaders: 2,
							modules: false
						}
					},
					{
						loader: "postcss-loader",
						options: {
							ident: "postcss",
							plugins: [require("autoprefixer")]
						}
					},
					"sass-loader"
				],
			},
		]
	},
	resolve: {
		alias: {
			"vue$": "vue/dist/vue.esm.js",
			"@": path.resolve(__dirname, "app/assets"),
		},
		extensions: [".js", ".vue", ".ts", ".tsx"],
		modules: [
			'node_modules',
		],
	},
	plugins: [
		// enable vue-loader to use existing loader rules for other module types
		new VueLoaderPlugin(),

		// fix legacy jQuery plugins which depend on globals
		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery",
			"window.jQuery": "jquery",
			"window.$": "jquery",
			Popper: ["popper.js", "default"],
		}),

		// prevent pikaday from including moment.js
		new webpack.IgnorePlugin(/moment/, /pikaday/),

		// ignore locales from moment.js
		new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),

		// extract css
		new MiniCssExtractPlugin({
			filename: !devMode ? "[name].[chunkhash:8].bundle.css" : "[name].bundle.css",
		}),

		// human webpack errors
		new FriendlyErrorsWebpackPlugin(),
	],
};

if (WEBPACK_REPORT) {
	module.exports.plugins.push(
		new BundleAnalyzerPlugin({
			analyzerMode: "static",
			generateStatsFile: true,
			openAnalyzer: false,
			reportFilename: path.join(CACHE_PATH, "webpack-report/index.html"),
			statsFilename: path.join(CACHE_PATH, "webpack-report/stats.json"),
		})
	);
}

if (process.env.NODE_ENV === "development") {
	const development = {
		output: {
			globalObject: 'this'
		},
		devServer: {
			host: WEBPACK_DEV_SERVER_HOST,
			port: WEBPACK_DEV_SERVER_PORT,
			disableHostCheck: true,
			contentBase: path.join(ROOT_PATH, "www"),
			headers: {
				"Access-Control-Allow-Origin": "*",
				"Access-Control-Allow-Headers": "*",
			},
			stats: "errors-only",
			hot: true,
			inline: true,
			proxy: {
				"/": `http://${WEBPACK_DEV_SERVER_PROXY_HOST}:${WEBPACK_DEV_SERVER_PROXY_PORT}`
			}
		},
	};

	module.exports = merge(module.exports, development);
}

if (process.env.NODE_ENV === "production") {
	const production = {
		output: {
			filename: '[name].[contenthash:8].bundle.js',
			chunkFilename: '[name].[contenthash:8].chunk.js'
		},
		devtool: "none",
		optimization: {
			minimizer: [
				new TerserPlugin({
					test: /\.m?js(\?.*)?$/i,
					chunkFilter: () => true,
					warningsFilter: () => true,
					extractComments: false,
					sourceMap: true,
					cache: true,
					cacheKeys: defaultCacheKeys => defaultCacheKeys,
					parallel: true,
					include: undefined,
					exclude: undefined,
					minify: undefined,
					terserOptions: {
						output: {
							comments: /^\**!|@preserve|@license|@cc_on/i
						},
						compress: {
							arrows: false,
							collapse_vars: false,
							comparisons: false,
							computed_props: false,
							hoist_funs: false,
							hoist_props: false,
							hoist_vars: false,
							inline: false,
							loops: false,
							negate_iife: false,
							properties: false,
							reduce_funcs: false,
							reduce_vars: false,
							switches: false,
							toplevel: false,
							typeofs: false,
							booleans: true,
							if_return: true,
							sequences: true,
							unused: true,
							conditionals: true,
							dead_code: true,
							evaluate: true
						},
						mangle: {
							safari10: true
						}
					}
				})
			],
		},
		plugins: [
			// optimize CSS files
			new OptimizeCSSAssetsPlugin(),
		],
	};

	module.exports = merge(module.exports, production);
}
