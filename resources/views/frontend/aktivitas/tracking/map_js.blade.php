<script>
document.addEventListener('DOMContentLoaded', function () {

    const tracks = @json($row_data);
    if (!tracks.length) return;

    tracks.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));

    const latlngs = tracks.map(t => [
        parseFloat(t.latitude),
        parseFloat(t.longitude)
    ]);

    // ================= MAP =================
    const map = L.map('map', {
        zoomControl: true
    }).setView(latlngs[0], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    const polyline = L.polyline(latlngs, {
        color: '#2563eb',
        weight: 4
    }).addTo(map);

    map.fitBounds(polyline.getBounds());

    // ================= ICON =================
    function getColor(type) {
        if (type.includes('Absen')) return 'green';
        if (type.includes('Patroli')) return 'orange';
        if (type.includes('Suhu')) return 'red';
        if (type.includes('Kipas')) return 'purple';
        if (type.includes('Alarm')) return 'black';
        return 'blue';
    }

    function dotIcon(color, size = 10) {
        return L.divIcon({
            html: `<div style="
                width:${size}px;
                height:${size}px;
                background:${color};
                border-radius:50%;
                border:2px solid white;"></div>`
        });
    }

    // ================= POINT MARKERS =================
    const pointMarkers = [];

    tracks.forEach((t, i) => {
        const m = L.marker([t.latitude, t.longitude], {
            icon: dotIcon(getColor(t.keterangan))
        }).addTo(map);

        m.bindPopup(`
            <b>${t.keterangan}</b><br>
            👮 ${t.satpam_name}<br>
            🕒 ${t.tanggal}
        `);

        pointMarkers.push(m);
    });

    // ================= MAIN MARKER =================
    const movingMarker = L.marker(latlngs[0], {
        icon: dotIcon(getColor(tracks[0].keterangan), 14)
    }).addTo(map);

    // ================= STATE =================
    let index = 0;
    let isPlaying = false;
    let speed = 4500;
    let animId = null;
    let lastPopupIndex = null;
    let popupTimeout = null;

    const slider = document.getElementById('timeline');
    slider.max = latlngs.length - 1;

    const sound = document.getElementById('checkpointSound');

    // ================= HELPERS =================
    function haversine(a, b) {
        const R = 6371;
        const dLat = (b[0] - a[0]) * Math.PI / 180;
        const dLng = (b[1] - a[1]) * Math.PI / 180;
        const x =
            Math.sin(dLat / 2) ** 2 +
            Math.cos(a[0] * Math.PI / 180) *
            Math.cos(b[0] * Math.PI / 180) *
            Math.sin(dLng / 2) ** 2;
        return 2 * R * Math.asin(Math.sqrt(x));
    }

    function showPopup(i) {
        if (lastPopupIndex !== null) {
            pointMarkers[lastPopupIndex].closePopup();
        }

        pointMarkers[i].openPopup();
        sound.play();

        if (popupTimeout) clearTimeout(popupTimeout);
        popupTimeout = setTimeout(() => {
            pointMarkers[i].closePopup();
        }, 3000);

        lastPopupIndex = i;
    }

    function updateMarker(i) {
        movingMarker.setLatLng(latlngs[i]);
        movingMarker.setIcon(dotIcon(getColor(tracks[i].keterangan), 14));

        map.flyTo(latlngs[i], map.getZoom(), {
            animate: true,
            duration: 0.5
        });

        showPopup(i);
        slider.value = i;

        // ===== ANOMALY DETECTION =====
        if (i > 0) {
            const distance = haversine(latlngs[i - 1], latlngs[i]);
            if (distance > 0.5) {
                console.warn('ANOMALI: loncat lokasi', distance, 'km');
            }
        }
    }

    function smoothMove(start, end, duration, cb) {
        let startTime = null;

        function animate(time) {
            if (!isPlaying) return;
            if (!startTime) startTime = time;

            let p = (time - startTime) / duration;
            if (p > 1) p = 1;

            const lat = start[0] + (end[0] - start[0]) * p;
            const lng = start[1] + (end[1] - start[1]) * p;

            movingMarker.setLatLng([lat, lng]);

            if (p < 1) {
                animId = requestAnimationFrame(animate);
            } else {
                cb && cb();
            }
        }

        animId = requestAnimationFrame(animate);
    }

    function play() {
        if (!isPlaying || index >= latlngs.length - 1) {
            isPlaying = false;
            return;
        }

        updateMarker(index);

        smoothMove(latlngs[index], latlngs[index + 1], speed, () => {
            index++;
            play();
        });
    }

    function reset() {
        if (animId) cancelAnimationFrame(animId);
        index = 0;
        isPlaying = false;
        updateMarker(0);
    }

    // ================= CONTROLS =================
    document.getElementById('btnPlay').onclick = () => {
        if (!isPlaying) {
            isPlaying = true;
            play();
        }
    };

    document.getElementById('btnPause').onclick = () => {
        isPlaying = false;
    };

    document.getElementById('btnReplay').onclick = () => {
        reset();
        isPlaying = true;
        play();
    };

    document.getElementById('speedControl').onchange = e => {
        speed = parseInt(e.target.value);
    };

    slider.oninput = e => {
        isPlaying = false;
        index = parseInt(e.target.value);
        updateMarker(index);
    };

});
</script>
