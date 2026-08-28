<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tableName }}</title>
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
</head>

<body>
    <div class="container-fluid my-2 p-4">
        <div class="d-flex justify-content-between">
            <h1 class="mb-4">Table: {{ str_replace('_', ' ', ucWords($tableName)) }}</h1>
            <div>
                <a href="{{ route('panel.admin.debug-jobs.destroy', ['table_name' => request()->get('table_name')]) }}"
                    class="btn btn-outline-danger">@lang('ui.clear_all')</a>
            </div>
        </div>
        @if ($columns)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ ucWords(str_replace('_', ' ', $column)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                @foreach ($columns as $column)
                                    <td>{{ $row->{$column} !== null ? $row->{$column} : '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $data->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @else
            <p>@lang('ui.no_columns_found_in_the_table.') </p>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
