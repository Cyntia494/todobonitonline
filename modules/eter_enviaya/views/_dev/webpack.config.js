module.exports = (env = {}) => {
	return {
		entry: ['./css/module.scss'],
		output: {
			filename: './js/module.js',
		},
		module: {
			rules: [
				{
					test: /\.scss$/,
					use: [
						{
							loader: 'file-loader',
							options: {
								name: '[name].css',
								outputPath: '../assets/css/'
							}
						},
						{
							loader: 'extract-loader'
						},
						{
							loader: 'css-loader',
							options: {
								minimize: true
							}
						},
						{
							loader: 'postcss-loader'
						},
						{
							loader: 'sass-loader'
						}
					]
				},
				{
			      test: /.(png|woff(2)?|eot|ttf|svg)(\?[a-z0-9=\.]+)?$/,
			      use: [
						{
							loader: 'file-loader',
							options: {
								name: '[name].[ext]',
								outputPath: '../../assets/images/'
							}
						}
					]
			    }
			]
		}
	}
};