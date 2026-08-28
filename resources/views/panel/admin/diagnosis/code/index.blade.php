<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Diagnosis Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .no-issues {
            color: #888;
            font-style: italic;
        }

        .accordion {
            background-color: #f4f4f4;
            color: #333;
            cursor: pointer;
            padding: 10px;
            border: none;
            text-align: left;
            outline: none;
            width: 100%;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            transition: background-color 0.3s ease;
        }

        .accordion:hover {
            background-color: #ddd;
        }

        .panel {
            padding: 0 20px;
            display: none;
            overflow: hidden;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .count {
            font-weight: bold;
            color: #555;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion');
            accordions.forEach(accordion => {
                accordion.addEventListener('click', function() {
                    this.classList.toggle('active');
                    const panel = this.nextElementSibling;
                    if (panel.style.display === 'block') {
                        panel.style.display = 'none';
                    } else {
                        panel.style.display = 'block';
                    }
                });
            });
        });
    </script>
</head>

<body>
    <h1>{{ ucwords($role) }} @lang('ui.code_diagnosis_report')</h1>

    @foreach ($diagnosis as $category => $issuesByType)
        <!-- Category Accordion -->
        <x-button class="accordion">
            <span>{{ @$category ?? '' }}</span>
            <span class="count">{{ count($issuesByType) }}</span>
        </x-button>
        <div class="panel">
            @if (count($issuesByType) > 0)
                @foreach ($issuesByType as $type => $issues)
                    <!-- Type Accordion -->
                    <x-button class="accordion">
                        <span>{{ @$type ?? '' }}</span>
                        <span class="count">{{ count($issues) }}</span>
                    </x-button>
                    <div class="panel">
                        @if (count($issues) > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>@lang('ui.files')</th>
                                        <th>@lang('ui.details') </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($issues as $issue)
                                        <tr>
                                            <td>{{ isset($issue['file']) ? $issue['file'] : 'default_value' }}
                                            </td>
                                            <td>{{ isset($issue['details']) ? $issue['details'] : 'default_value' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="no-issues">@lang('ui.no_issues_found_in') {{ @$type ?? '' }}.</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="no-issues">@lang('ui.no_issues_found_in') {{ @$category ?? '' }}.</p>
            @endif
        </div>
    @endforeach
</body>

</html>
