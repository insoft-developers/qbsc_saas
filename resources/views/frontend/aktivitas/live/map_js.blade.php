<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const userId = "{{ $satpam_id }}";
    const lat = parseFloat("{{ $lat }}");
    const lng = parseFloat("{{ $lng }}");

    const map = L.map('map').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    let marker = L.marker([lat, lng]).addTo(map);

    // 1️⃣ polyline dibuat sekali
    let polyline = L.polyline([], { color: 'blue' }).addTo(map);

    function updateLocation() {
        fetch(`{{ url('update_live_location') }}/${userId}`)
            .then(res => res.json())
            .then(data => {
                if (!data || !data.last_latitude || !data.last_longitude) return;

                const latlng = [
                    parseFloat(data.last_latitude),
                    parseFloat(data.last_longitude)
                ];

                // update marker
                marker.setLatLng(latlng);

                // 2️⃣ tambah jalur
                polyline.addLatLng(latlng);
            });
    }

    setInterval(updateLocation, 5000);
</script>

