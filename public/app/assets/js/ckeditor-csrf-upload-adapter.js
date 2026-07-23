(function () {
    function CsrfUploadAdapter(loader) {
        this.loader = loader;
        this.xhr = null;
    }

    CsrfUploadAdapter.prototype.upload = function () {
        var config = window.ckUploadConfig || {};
        var loader = this.loader;

        return loader.file.then(function (file) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                var data = new FormData();

                this.xhr = xhr;
                xhr.open('POST', config.url, true);
                xhr.responseType = 'json';
                xhr.setRequestHeader('X-CSRF-TOKEN', config.token || '');
                xhr.onerror = function () { reject('Network error'); };
                xhr.onabort = function () { reject('Upload aborted'); };
                xhr.onload = function () {
                    var res = xhr.response || {};
                    if (xhr.status >= 200 && xhr.status < 300 && res.uploaded) {
                        resolve({ default: res.url });
                        return;
                    }

                    reject((res.error && res.error.message) || 'Upload failed');
                };

                data.append('upload', file);
                xhr.send(data);
            }.bind(this));
        }.bind(this));
    };

    CsrfUploadAdapter.prototype.abort = function () {
        if (this.xhr) {
            this.xhr.abort();
        }
    };

    window.CsrfUploadAdapterPlugin = function (editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
            return new CsrfUploadAdapter(loader);
        };
    };
})();
