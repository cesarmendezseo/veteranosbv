<div>
    <div class="mb-4 no-print">
        <button wire:click="imprimir" class="cursor-pointer bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            🖨️ Imprimir Planilla
        </button>
        <button onclick="window.history.back()"
            class="cursor-pointer bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 ml-2">
            ← Volver
        </button>
    </div>

    <div id="planilla-imprimible" class="bg-white">
        <style>
            @media print {
                @page {
                    size: legal;
                    margin: 0.3in;
                }

                body * {
                    visibility: hidden;
                }

                #planilla-imprimible,
                #planilla-imprimible * {
                    visibility: visible;
                }

                #planilla-imprimible {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                }
            }

            .planilla-container {
                font-family: Arial, sans-serif;
                max-width: 8.5in;
                margin: 0 auto;
                background: white;
                padding: 10px;
                /* Reducido */
                font-size: 11px;
            }

            .header-logo {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
                /* Reducido */
                padding: 5px 0;
            }

            .header-logo img {
                height: 50px;
                /* Reducido */
            }

            .header-logo .header-text {
                text-align: right;
                font-weight: bold;
                font-size: 11px;
                line-height: 1.2;
            }

            .titulo-torneo {
                background: #2C5282;
                color: white;
                text-align: center;
                padding: 6px;
                /* Reducido */
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 8px;
            }

            .info-partido {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                margin-bottom: 8px;
            }

            .info-fecha {
                background: transparent !important;
                /* Quitamos el fondo rojo */
                color: black !important;
                /* Cambiamos el texto a negro para que se vea */
                padding: 2px 0 !important;
                /* Reducimos el relleno */
                font-size: 12px;
                font-weight: bold;
                text-align: left !important;
                /* Alineamos a la izquierda */

                /* Opcional: una línea fina abajo para que no quede "en el aire" */
            }

            .info-cancha {
                padding: 2px 0;
                font-size: 12px;

            }

            .info-equipo {
                background: linear-gradient(to bottom, #E26B0A, #974706);
                color: white;
                padding: 4px 8px !important;
                /* Reducimos el padding (antes era 8px o 6px) */
                font-size: 13px !important;
                /* Achicamos un poco la letra */
                font-weight: bold;
                text-align: center;
                margin-top: 5px;
                /* Espacio respecto a la fecha/cancha */
                width: fit-content;
                /* Opcional: la barra solo ocupará lo que mide el nombre */
                min-width: 200px;
            }

            .info-partido-detalle {
                display: flex;
                flex-direction: column;
                gap: 2px;
                font-size: 12px;
            }

            .info-item {
                padding: 4px;
            }

            .fila-sancionada td {
                background-color: #ffe6e6 !important;
                /* Un rojo muy claro de fondo para legibilidad */
                color: #cc0000 !important;
                /* Texto en rojo oscuro */
            }

            /* Específicamente para la celda roja de 'SUSPENDIDO' */
            .celda-roja-total {
                background-color: #ff0000 !important;
                /* Rojo puro */
                color: white !important;
                font-weight: bold;
                text-align: center;
                text-transform: uppercase;
                vertical-align: middle;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            table th {
                background: #3490DC;
                color: black;
                font-weight: bold;
                padding: 4px;
                border: 1px solid black;
                text-align: center;
                font-size: 10px;
            }

            table td {
                border: 1px solid black;
                padding: 2px 4px !important;
                /* Padding mínimo para que entren las 30 filas */
                text-align: left;
                height: 18px !important;
                font-size: 10px;
            }

            table td:nth-child(1),
            table td:nth-child(2),
            table td:nth-child(3),
            table td:nth-child(7),
            table td:nth-child(8) {
                text-align: center;
            }

            .celda-suspendido {
                background: #FF0000 !important;
                color: white !important;
                font-weight: bold;
                text-align: center;
                font-size: 9px;
            }

            .leyenda-sancion {
                font-size: 9px;
                line-height: 0.9;
                display: block;
            }

            small {
                font-size: 0.85em;
                opacity: 0.9;
            }

            .titulo-cuerpo-tecnico {
                background: #F0F0F0;
                text-align: center;
                padding: 5px;
                font-weight: bold;
                font-size: 12px;
                border: 1px solid black;
            }

            .cuerpo-tecnico-headers th {
                background: #3490DC;
                font-size: 10px;
                padding: 4px;
            }

            .imagen-cambios {
                margin-top: 5px;
                text-align: right;
            }

            .imagen-cambios img {
                height: 130px;
                /* Reducido para asegurar el fin de hoja */
            }
        </style>

        <div class="planilla-container">
            <div class="header-logo">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
                <div class="header-text">
                    ASOCIACIÓN CIVIL<br>
                    DE FÚTBOL DE VETERANOS<br>
                    BELLA VISTA - CORRIENTES
                </div>
            </div>

            <div class="titulo-torneo">
                {{ strtoupper($torneoNombre) }}
            </div>

            <div class="info-partido">
                <div style="grid-column: 1 / 2;">
                    <div class="info-fecha">FECHA:________</div>
                    <div class="info-cancha">CANCHA:________________ {{ $cancha }}</div>
                    <div class="info-equipo">{{ strtoupper($equipoNombre) }}</div>
                </div>

                <div class="info-partido-detalle" style="grid-column: 2 / 3;">
                    <div class="info-item">EL DÍA: __/__/___/</div>
                    <div class="info-item">GOLES: _____</div>
                    <div class="info-item">CONDICIÓN: _________</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 3%;">#</th>
                        <th style="width: 4%;">N°</th>
                        <th style="width: 10%;">DNI</th>
                        <th style="width: 20%;">Apellido</th>
                        <th style="width: 20%;">Nombre</th>
                        <th style="width: 12%;">Firmas</th>
                        <th style="width: 5%;">Gol</th>
                        <th style="width: 5%;">Tarj.</th>
                        <th>Sanción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jugadores as $jugador)
                    {{-- Aplicamos la clase a TODA la fila si tiene sanción --}}
                    <tr class="{{ $jugador['tieneSancion'] ? 'fila-sancionada' : '' }}">
                        <td>{{ $jugador['numero'] }}</td>
                        <td></td>
                        <td>{{ $jugador['dni'] }}</td>
                        <td>{{ $jugador['apellido'] }}</td>
                        <td>{{ $jugador['nombre'] }}</td>

                        {{-- Celda de Firma/Estado --}}
                        @if($jugador['tieneSancion'])
                        <td class="celda-roja-total">SUSPENDIDO</td>
                        @else
                        <td></td>
                        @endif

                        <td></td>
                        <td></td>

                        {{-- Columna Sanción --}}
                        <td style="text-align: center;">
                            <span class="leyenda-sancion">
                                {!! $jugador['sancion'] !!}
                            </span>
                        </td>
                    </tr>
                    @endforeach

                    @for($i = count($jugadores); $i < 30; $i++) <tr>
                        <td>{{ $i + 1 }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                        @endfor
                </tbody>
            </table>

            <div class="titulo-cuerpo-tecnico">CUERPO TÉCNICO Y AUTORIDADES</div>
            <table>
                <thead class="cuerpo-tecnico-headers">
                    <tr>
                        <th colspan="2">Cargo</th>
                        <th>DNI</th>
                        <th colspan="2">Apellido y Nombre</th>
                        <th colspan="2">Firma</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['Capitán', 'Técnico', 'Ayudante 1', 'Ayudante 2'] as $cargo)
                    <tr>
                        <td colspan="2">{{ $cargo }}</td>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="imagen-cambios">
                <img src="{{ asset('images/cambios.png') }}" alt="Cambios">
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('imprimir-planilla', () => {
            window.print();
        });
    </script>
    @endscript
</div>