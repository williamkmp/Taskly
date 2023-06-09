<div id="modal-view" data-role="modal-manager-container"
    class="fixed z-40 flex flex-wrap items-center justify-center w-screen h-screen overflow-hidden bg-black bg-opacity-50 backdrop-blur-sm"
    style="display: none">

    <div class="min-w-[40rem] max-w-[70%] max-h-[70%] flex flex-col bg-white rounded-xl overflow-hidden">
        <header class="flex items-center justify-between flex-grow-0 w-full px-4 py-2 bg-black">
            <h1 class="text-2xl font-bold text-white" id="modal-title"></h1>
            <x-fas-square-xmark class="w-8 h-8 text-white" onclick="ModalView.close()" />
        </header>
        <div class="flex-grow w-full h-full p-4" id="modal-content">
        </div>
    </div>
</div>

@pushOnce('component')
    <script>
        class ModalView {
            static el = document.getElementById("modal-view");
            static current_modal_key = null;
            static payload = null;
            static modal_template = {};
            static modal_init_callback = {};
            static modal_close_callback = {};
            static container = document.getElementById("modal-view");
            static modal_body = document.getElementById("modal-content");

            static init() {
                ModalView.clear();
                let modalTemplates = document.querySelectorAll("template[is-modal]");
                for (const template of modalTemplates) {
                    let key = template?.attributes["is-modal"].value;
                    if (key == null) continue;
                    ModalView.modal_template[key] = template;
                }
                console.log("Modal View is loaded ... 👍");
            }

            static clear() {
                while (ModalView.modal_body.hasChildNodes()) {
                    ModalView.modal_body.removeChild(ModalView.modal_body.firstChild)
                }
            }

            static async show(key, payload) {
                this.payload = payload;
                const template = ModalView.modal_template[key];
                if (!template) return;
                this.current_modal_key = key;
                ModalView.clear();
                const callbackFn = ModalView.modal_init_callback[key];
                const content = template.content.cloneNode(true);
                ModalView.modal_body.append(content);
                if (callbackFn) await callbackFn(ModalView.modal_body, payload);
                ModalView.el.style.display = "flex";
            }

            static async close() {
                if(this.current_modal_key){
                    const callbackFn = ModalView.modal_close_callback[this.current_modal_key];
                    if (callbackFn) await callbackFn(ModalView.modal_body, this.payload);
                }
                ModalView.clear();
                ModalView.el.style.display = "none";
                this.current_modal_key = null;
            }

            static onShow(key, callback) {
                ModalView.modal_init_callback[key] = callback;
            }

            static onClose(key, callback) {
                ModalView.modal_close_callback[key] = callback;
            }
        }
        ModalView.init();
    </script>
@endPushOnce
