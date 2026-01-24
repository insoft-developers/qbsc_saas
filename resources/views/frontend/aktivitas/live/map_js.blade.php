<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const userId = "{{ $satpam_id }}";
    const lat = "{{ $lat }}";
    const lng = "{{ $lng }}";

    const map = L.map('map').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    let marker = L.marker([lat, lng]).addTo(map);

    function updateLocation() {
        fetch(`{{ url('update_live_location') }}/${userId}`)
            .then(res => res.json())
            .then(data => {
                if (!data) return;

                const latlng = [data.last_latitude, data.last_longitude];

                marker.setLatLng(latlng);
                map.panTo(latlng, {
                    animate: true
                });
            });
    }

    // refresh tiap 3 detik
    setInterval(updateLocation, 5000);
</script>
