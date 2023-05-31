<div id="page-loader" data-role="page-loader-container"
    class="fixed z-50 flex flex-wrap items-center justify-center w-screen h-screen overflow-hidden text-black bg-gray-500 bg-opacity-50 backdrop-blur-sm"
    style="display: none">
    <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
        role="status">
        <span
            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
    </div>
</div>

@pushOnce('component')
    <script>
        class PageLoader {
            static loaderElement = document.getElementById("page-loader");
            static isLoading = false;

            static show(){
                PageLoader.isLoading = true;
                PageLoader.loaderElement.style.display= 'flex';
            };

            static close(){
                PageLoader.isLoading = false;
                PageLoader.loaderElement.style.display= 'none';
            };
        }
    </script>
@endPushOnce
