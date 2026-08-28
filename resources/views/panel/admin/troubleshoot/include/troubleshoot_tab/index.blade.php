<div class="form-group row">
    @if (env('IS_DEV') === 1)
        <div class="col-md-4">
            <div class="card troubleshoot bg-light">
                <h5 class="ml-4 mt-2"> @lang('ui.storage_link') </h5>
                <p class="ml-4"> @lang('ui.storage_subheading')
                </p>

                <a href="{{ route('panel.admin.general.storage-link') }}" class="btn btn-outline-dark mt-4">
                    @lang('ui.storage_link')
                </a>
            </div>
        </div>
    @endif
    <div class="col-md-4">
        <div class="card troubleshoot bg-light">
            <h5 class="ml-4 mt-2"> @lang('ui.optimize_clear') </h5>
            <p class="ml-4"> @lang('ui.optimize_subheading')
            </p>
            <a href="{{ route('panel.admin.general.optimize-clear') }}" class="btn btn-outline-dark mt-4">
                @lang('ui.optimize_clear')
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card troubleshoot bg-light">
            <h5 class="ml-4 mt-2"> @lang('ui.session_clear') </h5>
            <p class="ml-4"> @lang('ui.session_subheading')
            </p>
            <a href="{{ route('panel.admin.general.session-clear') }}" class="btn btn-outline-dark mt-4">
                @lang('ui.session_clear')
            </a>
        </div>
    </div>

    @foreach ($logFiles as $file)
        <div class="col-md-4">
            <div class="card troubleshoot bg-light">
                <h5 class="ml-4 mt-2">{{ ucwords(str_replace('.', ' ', $file)) }}</h5>
                <p class="ml-4">View and Manage Logs</p>
                <a href="{{ url(env('PROJECT_URL') . '/logger.php?file_name=' . e($file)) }}"
                    class="btn btn-outline-dark mt-4">
                    {{ ucwords(str_replace('.', ' ', $file)) }} View
                </a>

            </div>
        </div>
    @endforeach


    <div class="col-md-4">
        <div class="card troubleshoot bg-light">
            <h5 class="ml-4 mt-2"> @lang('ui.debug_jobs') </h5>
            <p class="ml-4"> @lang('ui.debug_jobs_subheading')
            </p>
            <a href="{{ route('panel.admin.debug-jobs.index', ['table_name' => 'jobs']) }}"
                class="btn btn-outline-dark mt-4">
                @lang('ui.debug_jobs_view')
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card troubleshoot bg-light">
            <h5 class="ml-4 mt-2"> @lang('ui.debug_failed_jobs') </h5>
            <p class="ml-4"> @lang('ui.debug_failed_jobs_subheading')
            </p>
            <a href="{{ route('panel.admin.debug-jobs.index', ['table_name' => 'failed_jobs']) }}"
                class="btn btn-outline-dark mt-4">
                @lang('ui.debug_failed_view')
            </a>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchLogs();

        function fetchLogs() {
            fetch("{{ route('panel.admin.logs.list') }}")
                .then(response => response.json())
                .then(files => {
                    let logList = document.getElementById("log-list");
                    logList.innerHTML = "";
                    files.forEach(file => {
                        let li = document.createElement("li");
                        li.innerHTML = `<a href="#" onclick="viewLog('${file}')">${file}</a>`;
                        logList.appendChild(li);
                    });
                });
        }

        window.viewLog = function(file) {
            fetch("{{ route('panel.admin.logs.view', '') }}/" + file)
                .then(response => response.text())
                .then(content => {
                    document.getElementById("log-content").textContent = content;
                });
        };
    });
</script>
