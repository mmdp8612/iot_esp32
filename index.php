<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESP32 Monitor Ambiental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="min-h-screen">
        <header class="bg-slate-900 text-white shadow">
            <div class="max-w-7xl mx-auto px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">ESP32 Monitor Ambiental</h1>
                        <p class="text-slate-400 text-sm mt-1">
                            Monitoreo de temperatura y humedad
                        </p>
                    </div>
                    <div id="estadoDispositivo" class="flex items-center gap-2 bg-slate-800 px-4 py-2 rounded-lg">
                        <span id="estadoLed" class="w-3 h-3 rounded-full bg-gray-500"></span>
                        <span id="estadoTexto">
                            Verificando...
                        </span>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-7xl mx-auto px-6 py-8">
            <div class="mb-6">
                <p class="text-sm text-slate-500">
                    Dispositivo
                </p>
                <p id="deviceId" class="font-semibold text-lg">-</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500">
                        Temperatura
                    </p>
                    <div class="flex items-end gap-2 mt-3">
                        <span id="temperatura" class="text-4xl font-bold">
                            --
                        </span>
                        <span class="text-xl text-slate-500 mb-1">
                            °C
                        </span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500">
                        Humedad
                    </p>
                    <div class="flex items-end gap-2 mt-3">
                        <span id="humedad" class="text-4xl font-bold">
                            --
                        </span>
                        <span class="text-xl text-slate-500 mb-1">
                            %
                        </span>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500">
                        Última lectura
                    </p>
                    <p id="ultimaLectura" class="text-lg font-semibold mt-3">
                        --
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500">
                        Mediciones cargadas
                    </p>
                    <p id="totalMediciones" class="text-4xl font-bold mt-3">
                        --
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold mb-5">
                        Historial de temperatura
                    </h2>
                    <div class="h-72">
                        <canvas id="graficoTemperatura"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold mb-5">
                        Historial de humedad
                    </h2>
                    <div class="h-72">
                        <canvas id="graficoHumedad"></canvas>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-semibold">
                        Últimas mediciones
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-6 py-3">ID</th>
                            <th class="text-left px-6 py-3">Dispositivo</th>
                            <th class="text-left px-6 py-3">Temperatura</th>
                            <th class="text-left px-6 py-3">Humedad</th>
                            <th class="text-left px-6 py-3">Fecha</th>
                        </tr>
                        </thead>
                        <tbody id="tablaMediciones" class="divide-y divide-slate-100">

                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="js/app.js"></script>
</body>
</html>