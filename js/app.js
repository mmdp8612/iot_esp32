let graficoTemperatura = null;
let graficoHumedad = null;


/*
|--------------------------------------------------------------------------
| Cargar última medición
|--------------------------------------------------------------------------
*/

async function cargarUltimaMedicion() {

    try {

        const response = await fetch('api/ultima.php');

        const resultado = await response.json();

        if (!resultado.success) {
            return;
        }

        const data = resultado.data;


        document.getElementById('temperatura').textContent =
            parseFloat(data.temperatura).toFixed(1);


        document.getElementById('humedad').textContent =
            parseFloat(data.humedad).toFixed(0);


        document.getElementById('deviceId').textContent =
            data.device_id;


        document.getElementById('ultimaLectura').textContent =
            formatearFecha(data.fecha);


        actualizarEstadoDispositivo(data.fecha);

    } catch (error) {

        console.error(
            'Error cargando última medición:',
            error
        );

        mostrarOffline();

    }

}



/*
|--------------------------------------------------------------------------
| Cargar historial
|--------------------------------------------------------------------------
*/

async function cargarHistorial() {

    try {

        const response = await fetch('api/historial.php');

        const resultado = await response.json();

        if (!resultado.success) {
            return;
        }

        const datos = resultado.data;


        document.getElementById('totalMediciones').textContent = resultado.total;


        cargarTabla(datos);

        actualizarGraficos(datos);

    } catch (error) {

        console.error(
            'Error cargando historial:',
            error
        );

    }

}



/*
|--------------------------------------------------------------------------
| Tabla
|--------------------------------------------------------------------------
*/

function cargarTabla(datos) {

    const tbody =
        document.getElementById('tablaMediciones');


    tbody.innerHTML = '';


    const datosInvertidos =
        [...datos].reverse();


    datosInvertidos.forEach(item => {

        const fila = document.createElement('tr');

        fila.className =
            'hover:bg-slate-50 transition';


        fila.innerHTML = `

            <td class="px-6 py-4">
                ${item.id}
            </td>

            <td class="px-6 py-4 font-medium">
                ${item.device_id}
            </td>

            <td class="px-6 py-4">
                ${parseFloat(item.temperatura).toFixed(1)} °C
            </td>

            <td class="px-6 py-4">
                ${parseFloat(item.humedad).toFixed(0)} %
            </td>

            <td class="px-6 py-4 text-slate-500">
                ${formatearFecha(item.fecha)}
            </td>

        `;


        tbody.appendChild(fila);

    });

}



/*
|--------------------------------------------------------------------------
| Gráficos
|--------------------------------------------------------------------------
*/

function actualizarGraficos(datos) {

    const labels = datos.map(item => {

        const fecha = new Date(
            item.fecha.replace(' ', 'T')
        );

        return fecha.toLocaleTimeString(
            'es-AR',
            {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }
        );

    });


    const temperaturas =
        datos.map(item =>
            parseFloat(item.temperatura)
        );


    const humedades =
        datos.map(item =>
            parseFloat(item.humedad)
        );


    /*
    |--------------------------------------------------------------------------
    | Temperatura
    |--------------------------------------------------------------------------
    */

    if (!graficoTemperatura) {

        const ctxTemperatura =
            document
                .getElementById('graficoTemperatura')
                .getContext('2d');


        graficoTemperatura =
            new Chart(ctxTemperatura, {

                type: 'line',

                data: {

                    labels: labels,

                    datasets: [{

                        label: 'Temperatura °C',

                        data: temperaturas,

                        borderWidth: 2,

                        tension: 0.3,

                        pointRadius: 2

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            beginAtZero: false

                        }

                    }

                }

            });

    } else {

        graficoTemperatura.data.labels =
            labels;

        graficoTemperatura.data.datasets[0].data =
            temperaturas;

        graficoTemperatura.update();

    }



    /*
    |--------------------------------------------------------------------------
    | Humedad
    |--------------------------------------------------------------------------
    */

    if (!graficoHumedad) {

        const ctxHumedad =
            document
                .getElementById('graficoHumedad')
                .getContext('2d');


        graficoHumedad =
            new Chart(ctxHumedad, {

                type: 'line',

                data: {

                    labels: labels,

                    datasets: [{

                        label: 'Humedad %',

                        data: humedades,

                        borderWidth: 2,

                        tension: 0.3,

                        pointRadius: 2

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            min: 0,
                            max: 100

                        }

                    }

                }

            });

    } else {

        graficoHumedad.data.labels =
            labels;

        graficoHumedad.data.datasets[0].data =
            humedades;

        graficoHumedad.update();

    }

}



/*
|--------------------------------------------------------------------------
| Estado ESP32
|--------------------------------------------------------------------------
*/

function actualizarEstadoDispositivo(fechaUltimaLectura) {

    const fecha =
        new Date(
            fechaUltimaLectura.replace(' ', 'T')
        );


    const ahora =
        new Date();


    const diferencia =
        (ahora - fecha) / 1000;


    /*
     * Como actualmente el ESP32 envía
     * datos cada 10 segundos,
     *
     * si pasaron más de 30 segundos
     * lo consideramos offline.
     */

    if (diferencia <= 30) {

        mostrarOnline();

    } else {

        mostrarOffline();

    }

}



function mostrarOnline() {

    const led =
        document.getElementById('estadoLed');

    const texto =
        document.getElementById('estadoTexto');


    led.className =
        'w-3 h-3 rounded-full bg-green-500';

    texto.textContent =
        'Online';

}



function mostrarOffline() {

    const led =
        document.getElementById('estadoLed');

    const texto =
        document.getElementById('estadoTexto');


    led.className =
        'w-3 h-3 rounded-full bg-red-500';

    texto.textContent =
        'Offline';

}



/*
|--------------------------------------------------------------------------
| Fecha
|--------------------------------------------------------------------------
*/

function formatearFecha(fecha) {

    const date =
        new Date(
            fecha.replace(' ', 'T')
        );


    return date.toLocaleString(
        'es-AR',
        {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        }
    );

}



/*
|--------------------------------------------------------------------------
| Inicialización
|--------------------------------------------------------------------------
*/

async function cargarDashboard() {

    await cargarUltimaMedicion();

    await cargarHistorial();

}


/*
 * Primera carga
 */

cargarDashboard();


/*
 * Actualizar automáticamente
 * cada 5 segundos
 */

setInterval(
    cargarDashboard,
    5000
);