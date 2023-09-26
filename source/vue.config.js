const frontUrl = process.env.VUE_APP_FRONT_URL;

module.exports = {
    devServer: {
        disableHostCheck: true,
        host: '0.0.0.0',
        public: frontUrl
    },
}