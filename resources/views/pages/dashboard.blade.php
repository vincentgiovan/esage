
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body{
                background-color: rgb(224, 224, 224)
            }
        .chart-container {
            position: relative;
            height: 40vh;
            width: 80vw;
        }
        @media (max-width: 768px) {
            .chart-container {
                width: 100vw;
                height: 50vh;
            }
        }
    </style>
</head>
<body>

    <div style="min-height: 100vh;">
        <!-- Navbar -->
        @auth
            @include("component.navbar")
        @endauth

        <!-- Main content -->
        <div class="px-4 py-2">
            @yield("content")
        </div>
    </div>

    <h5>welcome back, {{ Auth::user()->name }}! </h5>
    {{-- <div class="container mt-5">
        <h1 class="mb-4">Warehouse Management Dashboard</h1>

        <div class="card mt-3">
            <div class="card-header">
                Inventory Overview
            </div>
            <div class="card-body">
                <p>Total Items: {{ $totalItems }}</p>
                <h5>Low Stock Items</h5>
                <ul>
                    @foreach ($lowStockItems as $item)
                        <li>{{ $item->name }} ({{ $item->quantity }})</li>
                    @endforeach
                </ul>
                <div class="chart-container">
                    <canvas id="inventoryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                Order Overview
            </div>
            <div class="card-body">
                <h5>Recent Orders</h5>
                <ul>
                    @foreach ($recentOrders as $order)
                        <li>{{ $order->customer_name }} ({{ $order->status }})</li>
                    @endforeach
                </ul>
                <h5>Pending Orders</h5>
                <ul>
                    @foreach ($pendingOrders as $order)
                        <li>{{ $order->customer_name }}</li>
                    @endforeach
                </ul>
                <div class="chart-container">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                Shipment Overview
            </div>
            <div class="card-body">
                <h5>Recent Shipments</h5>
                <ul>
                    @foreach ($recentShipments as $shipment)
                        <li>{{ $shipment->tracking_number }} ({{ $shipment->status }})</li>
                    @endforeach
                </ul>
                <h5>Delayed Shipments</h5>
                <ul>
                    @foreach ($delayedShipments as $shipment)
                        <li>{{ $shipment->tracking_number }}</li>
                    @endforeach
                </ul>
                <div class="chart-container">
                    <canvas id="shipmentChart"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h5>Quick Actions</h5>
            <a href="#" class="btn btn-primary">Add New Item</a>
            <a href="#" class="btn btn-secondary">Create Order</a>
            <a href="#" class="btn btn-success">Create Shipment</a>
        </div>
    </div>

    <script>
        // Inventory Chart
        var ctxInventory = document.getElementById('inventoryChart').getContext('2d');
        var inventoryChart = new Chart(ctxInventory, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inventoryData->pluck('name')) !!},
                datasets: [{
                    label: 'Inventory Quantity',
                    data: {!! json_encode($inventoryData->pluck('quantity')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Order Chart
        var ctxOrder = document.getElementById('orderChart').getContext('2d');
        var orderChart = new Chart(ctxOrder, {
            type: 'bar',
            data: {
                labels: {!! json_encode($orderData->pluck('status')) !!},
                datasets: [{
                    label: 'Order Status',
                    data: {!! json_encode($orderData->pluck('total')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Shipment Chart
        var ctxShipment = document.getElementById('shipmentChart').getContext('2d');
        var shipmentChart = new Chart(ctxShipment, {
            type: 'bar',
            data: {
                labels: {!! json_encode($shipmentData->pluck('status')) !!},
                datasets: [{
                    label: 'Shipment Status',
                    data: {!! json_encode($shipmentData->pluck('total')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script> --}}


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>


<div class=""></div>
