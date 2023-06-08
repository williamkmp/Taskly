<script>
    function jsonToFormData(jsonData) {
        const formData = new FormData();
        for (let key in jsonData) {
            if (jsonData.hasOwnProperty(key)) {
                formData.append(key, jsonData[key]);
            }
        }
        return formData;
    }

    function getCropperImageBlob(cropper) {
        return new Promise((resolve, reject) => {
            cropper.result('blob').then(function(blob) {
                resolve(blob);
            });
        });
    }

    function getResponseError(error) {
        if (!error) return null;
        let response = error.response;
        if (!response) return null;
        let firstError = response[Object.keys(response)[0]];
        return firstError?.pop();
    }

    class ServerRequest {
        static config = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            }
        };

        static get(url) {
            return new Promise((resolve, reject) => {
                axios.get(url, this.config)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        }

        static post(url, data) {
            let formData = jsonToFormData(data);
            formData.append('_token', `{{ csrf_token() }}`);
            return new Promise((resolve, reject) => {
                axios.post(url, data, this.config)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        }
    }

    class DOM {
        static id = 0;

        static create(template) {
            let temp = document.createElement("DIV");
            temp.innerHTML = template;
            return temp.children[0];
        }

        static find(selector) {
            return document.querySelector(selector);
        }

        static findAll(selector) {
            return Array.from(document.querySelectorAll(selector));
        }

        static log(a) {
            console.log(a)
        }

        static newid() {
            this.id++;
            return this.id;
        }
    }
</script>
