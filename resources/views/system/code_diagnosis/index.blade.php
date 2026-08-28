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

        .reference {
            background-color: #f9f9f9;
            border-left: 4px solid #007BFF;
            padding: 10px 15px;
            margin: 10px 0;
            font-size: 14px;
            color: #555;
            font-family: 'Arial', sans-serif;
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
    <h1>Code Diagnosis Report </h1>

    @foreach ($diagnosis as $category => $issuesByType)
        @php
            $issue_count = getTotalCount($issuesByType);
            $total_issue += $issue_count;
        @endphp
        <!-- Category Accordion -->
        <button class="accordion">
            <span>{{ @$category ?? '' }}</span>
            <span class="count">{{ @$issue_count ?? '' }}</span>
        </button>
        <div class="panel">
            @if (count($issuesByType) > 0)
                @foreach ($issuesByType as $type => $issues)
                    <!-- Type Accordion -->
                    <button class="accordion">
                        <span>{{ @$type ?? '' }}</span>
                        <span class="count">{{ count($issues) }}</span>
                    </button>
                    <div class="panel">
                        @if (count($issues) > 0)
                            @if (isset($issues[0]['reference']))
                                <div class="reference">
                                    <strong>Reference:</strong> {{ $issues[0]['reference'] }}
                                </div>
                            @endif
                            <table>
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($issues as $issue)
                                        <tr>
                                            <td>{{ isset($issue['file']) ? $issue['file'] : ' ' }}</td>
                                            <td>{{ isset($issue['details']) ? $issue['details'] : ' ' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="no-issues">No issues found in {{ @$type ?? '' }}.</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="no-issues">No issues found in {{ @$category ?? '' }}.</p>
            @endif
        </div>
    @endforeach
    <h4>Total Issue: {{ $total_issue ?? 0 }}</h4>
</body>

</html>
