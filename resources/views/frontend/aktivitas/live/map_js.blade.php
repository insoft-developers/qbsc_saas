<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ================= DATA =================
    const satpams = @json($satpams);
    const patroli = @json($patroli);

    // ================= MAP =================
    const map = L.map('map').setView(
        [
            parseFloat(satpams[0].last_latitude),
            parseFloat(satpams[0].last_longitude)
        ],
        16
    );

    // ================= TILE (SATELLITE) =================
    L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        {
            maxZoom: 19,
            attribution: '© Esri, Maxar'
        }
    ).addTo(map);

    // ================= ICONS =================
    const satpamIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const patrolIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // ================= STORAGE =================
    let markers = {};
    let polylines = {};

    // ================= MARKER PATROLI (MERAH) =================
    patroli.forEach(p => {

        const latlng = [
            parseFloat(p.latitude),
            parseFloat(p.longitude)
        ];

        if (isNaN(latlng[0]) || isNaN(latlng[1])) return;

        L.marker(latlng, {
                icon: patrolIcon,
                zIndexOffset: 500
            })
            .addTo(map)
            .bindTooltip(
                `<b>${p.nama_lokasi}</b>`,
                {
                    permanent: true,
                    direction: 'right',
                    offset: [10, 0]
                }
            );

        // (opsional) radius titik patroli
        L.circle(latlng, {
            radius: 20,
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.2
        }).addTo(map);
    });

    // ================= INIT SATPAM =================
    satpams.forEach(sp => {

        const latlng = [
            parseFloat(sp.last_latitude),
            parseFloat(sp.last_longitude)
        ];

        if (isNaN(latlng[0]) || isNaN(latlng[1])) return;

        // MARKER SATPAM
        markers[sp.id] = L.marker(latlng, {
                icon: satpamIcon,
                zIndexOffset: 1000
            })
            .addTo(map)
            .bindTooltip(
                `<b>${sp.name ?? 'Satpam #' + sp.id}</b>`,
                {
                    permanent: true,
                    direction: 'top',
                    offset: [0, -15]
                }
            );

        // TRACKING LINE
        polylines[sp.id] = L.polyline([latlng], {
            color: 'blue',
            weight: 4
        }).addTo(map);
    });

    // ================= LIVE UPDATE =================
    setInterval(() => {
        fetch('/update_live_location')
            .then(res => res.json())
            .then(data => {
                data.forEach(sp => {

                    const latlng = [
                        parseFloat(sp.last_latitude),
                        parseFloat(sp.last_longitude)
                    ];

                    if (isNaN(latlng[0]) || isNaN(latlng[1])) return;

                    if (markers[sp.id]) {
                        markers[sp.id].setLatLng(latlng);
                        polylines[sp.id].addLatLng(latlng);

                        // limit 100 titik biar ringan
                        if (polylines[sp.id].getLatLngs().length > 100) {
                            polylines[sp.id].getLatLngs().shift();
                        }

                    } else {
                        markers[sp.id] = L.marker(latlng, {
                                icon: satpamIcon,
                                zIndexOffset: 1000
                            })
                            .addTo(map)
                            .bindTooltip(
                                `<b>${sp.name ?? 'Satpam #' + sp.id}</b>`,
                                {
                                    permanent: true,
                                    direction: 'top',
                                    offset: [0, -15]
                                }
                            );

                        polylines[sp.id] = L.polyline([latlng], {
                            color: 'blue',
                            weight: 4
                        }).addTo(map);
                    }
                });
            });
    }, 5000);
</script>
