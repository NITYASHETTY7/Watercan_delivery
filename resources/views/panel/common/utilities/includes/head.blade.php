<style>
    @media print {
        .printHeader {
            display: none;
        }
    }

    .hoc {
        max-width: 1319px !important;
    }

    @media print {
        #printPageButton {
            display: none;
        }

        #resolte-contaniner {
            /* Change from overflow: hidden to overflow: auto */
            overflow-y: auto;
        }

        .container {
            max-width: 95vh;
        }

    }

    .wrapper {
        max-width: 100%;
    }

    #resolte-contaniner {
        /* Change from width: -webkit-fill-available to width: 100% */
        width: 100% !important;
        max-width: 100% !important;
        overflow: auto;
    }

    #resolte-contaniner img {
        max-width: 100% !important;
        height: auto;
        object-fit: contain;
    }
</style>
<div style="position: relative" class="mb-2">
    <div class="printHeader" style="padding: 10px 25px; background-color: #f9f9f9;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <!-- Make sure getBackendLogo and getSetting functions are defined and returning correct values -->
                <img height="85px" src="{{ getBackendLogo(getSetting('app_logo')) }}" class="header-brand-img"
                    title="zStarter">
            </div>
            <div>
                <a title="Print" href="javascript:void(0)" id="printBtn" onclick="window.print();"
                    class="btn btn-primary printPageButton"
                    style="
                    background-color: transparent;
                    border: none;
                    color: #222;
                    padding: 5px 10px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 12px;
                    position: relative;
            ">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path
                            d="M640-640v-120H320v120h-80v-200h480v200h-80Zm-480 80h640-640Zm560 100q17 0 28.5-11.5T760-500q0-17-11.5-28.5T720-540q-17 0-28.5 11.5T680-500q0 17 11.5 28.5T720-460Zm-80 260v-160H320v160h320Zm80 80H240v-160H80v-240q0-51 35-85.5t85-34.5h560q51 0 85.5 34.5T880-520v240H720v160Zm80-240v-160q0-17-11.5-28.5T760-560H200q-17 0-28.5 11.5T160-520v160h80v-80h480v80h80Z" />
                    </svg>
                </a>

                <a title=" Download Original File" href="{{ $path }}" class="btn btn-primary printPageButton"
                    style="
                    background-color: transparent;
                    border: none;
                    color: #222;
                    padding: 5px 10px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 12px;
                    position: relative;
            ">

                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path
                            d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                </a>

            </div>
        </div>
    </div>
</div>
