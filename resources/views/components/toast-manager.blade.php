<div data-role="toast-view-manager" id="notification-container"
    class="fixed bottom-0 right-0 flex flex-col items-end justify-end bg-red-200 max-h-0 w-96 empty:hidden">
</div>

@pushOnce('component')
    <script>
        console.log("Toast View manager is loaded ...👍");
        class ToastView {
            static #COUNT_LIMIT = 3;
            static #NOTIF_DUARTION = 5000; //2.5 seconds

            static #toastList = [];
            static #newId = 0;
            static #container = document.getElementById("notification-container");

            static notif(title, content) {
                if (this.#toastList.length >= this.#COUNT_LIMIT)
                    this.#removeOldest();

                let toastObject = this.#buildToastObject(title, content);
                let toastElement = this.#createEl(toastObject);
                this.#toastList.push({
                    ...toastObject,
                    ref: toastElement
                });

                this.#container.append(toastElement);
                setTimeout(() => {
                    toastElement.classList.remove("translate-x-full");
                    toastElement.classList.remove("opacity-0");
                }, 150)
                this.#newId = this.#newId + 1;
                this.#initRemoveTimeout(toastObject.id);
            }

            static #removeOldest() {
                let lastToast = this.#toastList.shift();
                if (!lastToast) return;
                this.removeById(lastToast.id);

            }

            static #initRemoveTimeout(toastId) {
                if (this.#NOTIF_DUARTION > 0) {
                    setTimeout(() => {
                        this.removeById(toastId);
                    }, this.#NOTIF_DUARTION);
                }
            }

            static #createEl(toast) {
                let temp = document.createElement("div");
                const notificationTemplate = `
                    <div id="${toast.id}"
                        onclick="ToastView.removeById('${toast.id}')"
                        class="flex flex-row items-center justify-center w-full gap-4 p-2 mb-4 mr-6 transition-all translate-x-full bg-white border-2 border-black rounded-lg opacity-0 cursor-pointer select-none">

                            <x-fas-circle-info class="w-8 h-8" />

                        <div class="flex flex-col flex-grow gap-1">
                            <p class="text-base font-semibold">${toast.title}</p>
                            <p class="text-sm">${toast.content}</p>
                        </div>
                    </div>
                `;
                temp.innerHTML = notificationTemplate;
                return temp.children[0];
            }

            static #buildToastObject(title, content) {
                return {
                    id: this.#newId + "-notif",
                    title,
                    content,
                };
            }

            static removeById(toastId) {
                this.#toastList = this.#toastList.filter((n) => n.id !== toastId);
                let toastElement = document.getElementById(toastId);
                toastElement?.removeAttribute("onclick");
                toastElement?.classList.add("translate-x-full");
                toastElement?.classList.add("opacity-0");
                if (toastElement)
                    toastElement.ontransitionend = function() {
                        toastElement?.remove();
                    };
            }
        }
    </script>
@endPushOnce
