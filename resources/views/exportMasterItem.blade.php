<!DOCTYPE html>
<html>
<head>
    <title>Master Item Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h3 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #d3d3d3; /* Gray background for header */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Even row background */
        }
    </style>
</head>
<body>
    <h3>Master Item</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product No.</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Item Group</th>
                <th>Item Unit</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->itemgroupnumber }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->is_active == 1 ? 'Active' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
