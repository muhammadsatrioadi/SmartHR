(function () {
    const STORAGE_DEVICE = 'hr_device_fingerprint';
    const STORAGE_BIO = 'hr_biometric_credential';
    const STORAGE_QUEUE = 'hr_offline_attendance_queue';

    function getDeviceFingerprint() {
        let fp = localStorage.getItem(STORAGE_DEVICE);
        if (!fp) {
            const raw = [
                navigator.userAgent,
                navigator.language,
                screen.width,
                screen.height,
                screen.colorDepth,
                Intl.DateTimeFormat().resolvedOptions().timeZone,
            ].join('|');
            fp = btoa(raw).replace(/[^a-zA-Z0-9]/g, '').slice(0, 64);
            localStorage.setItem(STORAGE_DEVICE, fp);
        }
        return fp;
    }

    function getPlatformLabel() {
        const ua = navigator.userAgent;
        let browser = 'Browser';
        if (ua.includes('Firefox')) browser = 'Firefox';
        else if (ua.includes('Chrome')) browser = 'Chrome';
        else if (ua.includes('Safari')) browser = 'Safari';
        return browser + '/' + (navigator.platform || 'Unknown') + '/' + screen.width + 'x' + screen.height;
    }

    async function registerDevice() {
        const fp = getDeviceFingerprint();
        const res = await fetch(window.PORTAL_ROUTES.deviceRegister, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.PORTAL_CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                device_fingerprint: fp,
                device_label: getPlatformLabel(),
                platform: navigator.platform,
            }),
        });
        const data = await res.json();
        if (!data.success) {
            throw new Error(data.message || 'Gagal verifikasi perangkat');
        }
        return data;
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius bumi dalam meter
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function geolocationErrorMessage(err) {
        if (!err || !err.code) {
            return 'Gagal mendapatkan lokasi. Coba refresh halaman.';
        }
        switch (err.code) {
            case 1:
                return 'Izin lokasi ditolak. Aktifkan di Pengaturan > Safari > Lokasi.';
            case 2:
                return 'Lokasi tidak tersedia. Matikan mode pesawat dan nyalakan GPS.';
            case 3:
                return 'GPS timeout. Pindah ke area terbuka lalu refresh halaman.';
            default:
                return 'Gagal mendapatkan lokasi GPS.';
        }
    }

    function updateGpsUI(lat, lng, accuracy, errorMessage) {
        const el = document.getElementById('gps-status');
        if (!el) return;
        if (errorMessage) {
            el.classList.remove('ok');
            el.classList.add('text-danger');
            el.innerHTML = '<i class="fas fa-map-marker-alt"></i> <span>' + errorMessage + '</span>';
            return;
        }
        if (lat != null && lng != null) {
            let statusText = 'Lokasi terdeteksi (' + Number(lat).toFixed(6) + ', ' + Number(lng).toFixed(6) + ')';
            
            if (window.ATTENDANCE_LOCATIONS && window.ATTENDANCE_LOCATIONS.length > 0) {
                let nearestDist = null;
                let nearestLoc = null;
                let isWithinAny = false;

                window.ATTENDANCE_LOCATIONS.forEach(loc => {
                    const dist = calculateDistance(lat, lng, loc.lat, loc.lng);
                    if (nearestDist === null || dist < nearestDist) {
                        nearestDist = dist;
                        nearestLoc = loc;
                    }
                    if (dist <= loc.radius) {
                        isWithinAny = true;
                    }
                });

                const isDinas = document.getElementById('lokasi_dinas')?.checked || false;
                
                if (isWithinAny || isDinas) {
                    el.classList.add('ok');
                    el.classList.remove('text-danger');
                    const infoText = isDinas ? 'Mode Dinas Luar Aktif' : 'Dalam jangkauan (' + Math.round(nearestDist) + 'm)';
                    statusText += '<br><small class="text-success"><i class="fas fa-check"></i> ' + infoText + '</small>';
                } else {
                    el.classList.remove('ok');
                    el.classList.add('text-danger');
                    statusText += '<br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Di luar jangkauan (' + Math.round(nearestDist) + 'm dari ' + nearestLoc.nama + ')</small>';
                }
            } else {
                el.classList.add('ok');
            }
            
            el.innerHTML = '<i class="fas fa-map-marker-alt"></i> <span>' + statusText + '</span>';
        } else {
            el.innerHTML = '<i class="fas fa-map-marker-alt"></i> <span>Menunggu GPS...</span>';
            el.classList.remove('ok');
        }
        const dev = document.getElementById('device-status');
        if (dev) {
            dev.innerHTML = '<i class="fas fa-check-circle"></i> ' + getPlatformLabel();
        }
    }

    function getCurrentPosition() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Browser tidak mendukung GPS'));
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => resolve({
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                    accuracy: pos.coords.accuracy,
                }),
                (err) => reject(err),
                { enableHighAccuracy: true, timeout: 20000, maximumAge: 5000 }
            );
        });
    }

    function requestGpsUpdate() {
        if (!navigator.geolocation) {
            updateGpsUI(null, null, null, 'Browser tidak mendukung GPS.');
            return;
        }
        updateGpsUI(null, null, null, null);
        const waitingEl = document.getElementById('gps-status');
        if (waitingEl) {
            waitingEl.classList.remove('text-danger', 'ok');
            waitingEl.innerHTML = '<i class="fas fa-map-marker-alt"></i> <span>Mencari lokasi GPS...</span>';
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => updateGpsUI(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy),
            (err) => updateGpsUI(null, null, null, geolocationErrorMessage(err)),
            { enableHighAccuracy: true, timeout: 20000, maximumAge: 5000 }
        );
    }

    async function verifyBiometric() {
        if (!window.PublicKeyCredential) {
            const stored = localStorage.getItem(STORAGE_BIO);
            if (stored) {
                return { verified: true, credentialId: stored };
            }
            return { verified: false, credentialId: null };
        }

        try {
            const challenge = new Uint8Array(32);
            crypto.getRandomValues(challenge);
            const credential = await navigator.credentials.create({
                publicKey: {
                    challenge,
                    rp: { name: 'HR Karyawan', id: window.location.hostname },
                    user: {
                        id: new TextEncoder().encode(getDeviceFingerprint()),
                        name: window.PORTAL_USER_EMAIL || 'user',
                        displayName: 'Karyawan',
                    },
                    pubKeyCredParams: [{ type: 'public-key', alg: -7 }],
                    authenticatorSelection: {
                        authenticatorAttachment: 'platform',
                        userVerification: 'required',
                    },
                    timeout: 60000,
                },
            });
            const id = credential.id;
            localStorage.setItem(STORAGE_BIO, id);
            return { verified: true, credentialId: id };
        } catch (e) {
            console.warn('Biometric error:', e);
            const stored = localStorage.getItem(STORAGE_BIO);
            if (stored) {
                return { verified: true, credentialId: stored };
            }
            return { verified: false, credentialId: null };
        }
    }

    function saveOfflineQueue(payload) {
        const queue = JSON.parse(localStorage.getItem(STORAGE_QUEUE) || '[]');
        queue.push({ ...payload, queued_at: new Date().toISOString() });
        localStorage.setItem(STORAGE_QUEUE, JSON.stringify(queue));
    }

    function clearOfflineQueueItem(id) {
        const queue = JSON.parse(localStorage.getItem(STORAGE_QUEUE) || '[]');
        const next = queue.filter((q) => q.offline_queue_id !== id);
        localStorage.setItem(STORAGE_QUEUE, JSON.stringify(next));
    }

    async function syncOfflineQueue() {
        const queue = JSON.parse(localStorage.getItem(STORAGE_QUEUE) || '[]');
        for (const item of queue) {
            try {
                const res = await fetch(window.PORTAL_ROUTES.checkin, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.PORTAL_CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(item),
                });
                const data = await res.json();
                if (data.success) {
                    clearOfflineQueueItem(item.offline_queue_id);
                }
            } catch (_) { /* retry later */ }
        }
    }

    async function submitCheckin(tipeAbsen, extras) {
        await registerDevice();
        
        // Coba ambil lokasi
        let pos = null;
        try {
            pos = await getCurrentPosition();
            updateGpsUI(pos.latitude, pos.longitude, pos.accuracy);
        } catch (e) {
            console.warn('Gagal mendapatkan lokasi:', e.message);
        }

        const isDinas = extras?.lokasi_dinas || false;

        // Jika bukan dinas luar, lokasi wajib didapatkan
        if (!pos && !isDinas) {
            throw new Error('Gagal mendapatkan lokasi GPS. Pastikan izin lokasi aktif dan GPS menyala.');
        }

        // Coba verifikasi biometrik
        const bio = await verifyBiometric();

        const payload = {
            tipe_absen: tipeAbsen,
            latitude: pos ? pos.latitude : 0,
            longitude: pos ? pos.longitude : 0,
            accuracy: pos ? pos.accuracy : 0,
            device_fingerprint: getDeviceFingerprint(),
            biometric_credential_id: bio.credentialId,
            biometric_verified: bio.verified,
            lokasi_dinas: isDinas,
            catatan: extras?.catatan || null,
            offline_queue_id: 'q-' + Date.now(),
        };

        // Simpan ke queue hanya jika kita offline
        if (!navigator.onLine) {
            saveOfflineQueue(payload);
            throw new Error('Offline: data tersimpan lokal, akan dikirim saat online.');
        }

        try {
            const res = await fetch(window.PORTAL_ROUTES.checkin, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.PORTAL_CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (!data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                // Jika error karena lokasi (422), jangan simpan di queue karena akan gagal terus
                throw new Error(data.message || 'Gagal absen');
            }
            return data;
        } catch (e) {
            // Jika error jaringan (bukan 422), simpan ke queue
            if (e.message.includes('fetch') || e.message.includes('Network')) {
                saveOfflineQueue(payload);
                throw new Error('Gangguan jaringan: data tersimpan untuk sinkronisasi otomatis.');
            }
            throw e;
        }
    }

    function initClock() {
        const el = document.getElementById('live-clock');
        if (!el) return;
        function tick() {
            const now = new Date();
            el.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        }
        tick();
        setInterval(tick, 1000);
    }

    function initGpsWatch() {
        requestGpsUpdate();
        if (!navigator.geolocation) return;
        navigator.geolocation.watchPosition(
            (pos) => updateGpsUI(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy),
            (err) => updateGpsUI(null, null, null, geolocationErrorMessage(err)),
            { enableHighAccuracy: true, timeout: 20000, maximumAge: 10000 }
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        initClock();
        initGpsWatch();
        syncOfflineQueue();

        const lokasiDinasCb = document.getElementById('lokasi_dinas');
        if (lokasiDinasCb) {
            lokasiDinasCb.addEventListener('change', function() {
                requestGpsUpdate();
            });
        }

        const gpsRefreshBtn = document.getElementById('gps-refresh-btn');
        if (gpsRefreshBtn) {
            gpsRefreshBtn.addEventListener('click', requestGpsUpdate);
        }
        registerDevice().catch(function (e) {
            const dev = document.getElementById('device-verify-status');
            if (dev) {
                dev.innerHTML = '<i class="fas fa-times-circle"></i> ' + e.message;
                dev.classList.remove('ok');
            }
        });

        const btn = document.getElementById('btn-checkin');
        if (btn) {
            btn.addEventListener('click', async function () {
                const tipe = btn.dataset.tipe;
                if (!tipe) return;
                btn.disabled = true;
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                try {
                    const extras = {
                        lokasi_dinas: document.getElementById('lokasi_dinas')?.checked || false,
                        catatan: document.getElementById('catatan')?.value || null,
                    };
                    const result = await submitCheckin(tipe, extras);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: e.message
                    });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = orig;
                }
            });
        }
    });

    window.PortalAttendance = {
        getDeviceFingerprint,
        registerDevice,
        verifyBiometric,
        submitCheckin,
    };
})();
