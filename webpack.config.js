const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    entry: "./src/main/resources/assets/script/main.js",
    output: {
        path: path.resolve(__dirname, "httpdocs/assets"),
        filename: "bundle.js"
    },
    devtool: "source-map",
    plugins: [
        new MiniCssExtractPlugin()
    ],
    module: {
        rules: [
            {
                test: /\.(scss)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    {
                        loader: "postcss-loader",
                        options: {
                            plugins: function() {
                                return [
                                    require("autoprefixer")
                                ];
                            }
                        }
                    },
                    "sass-loader"
                ]
            }
        ]
    }
};