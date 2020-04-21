const path = require("path");

module.exports = {
    entry: "./src/main/resources/assets/script/main.js",
    output: {
        path: path.resolve(__dirname, "httpdocs/assets"),
        filename: "bundle.js"
    },
    devtool: "source-map",
    module: {
        rules: [
            {
                test: /\.(scss)$/,
                use: [
                    {
                        loader: "style-loader"
                    },
                    {
                        loader: "css-loader"
                    }, {
                        loader: "postcss-loader",
                        options: {
                            plugins: function() {
                                return [
                                    require("autoprefixer")
                                ];
                            }
                        }
                    }, {
                        loader: "sass-loader"
                    }
                ]
            }
        ]
    }
};