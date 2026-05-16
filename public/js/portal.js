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

    function updateGpsUI(lat, lng, accuracy) {
        const el = document.getElementById('gps-status');
        if (!el) return;
        if (lat != null && lng != null) {
            el.innerHTML = '<i class="fas fa-map-marker-alt"></i> Lokasi terdeteksi (' +
                Number(lat).toFixed(6) + ', ' + Number(lng).toFixed(6) + ')';
            el.classList.add('ok');
        } else {
            el.innerHTML = '<i class="fas fa-map-marker-alt"></i> Menunggu GPS...';
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
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
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
        let pos = { latitude: 0, longitude: 0, accuracy: 0 };
        try {
            pos = await getCurrentPosition();
            updateGpsUI(pos.latitude, pos.longitude, pos.accuracy);
        } catch (e) {
            console.warn('Gagal mendapatkan lokasi:', e.message);
        }

        // Coba verifikasi biometrik
        const bio = await verifyBiometric();

        const payload = {
            tipe_absen: tipeAbsen,
            latitude: pos.latitude,
            longitude: pos.longitude,
            accuracy: pos.accuracy,
            device_fingerprint: getDeviceFingerprint(),
            biometric_credential_id: bio.credentialId,
            biometric_verified: bio.verified,
            lokasi_dinas: extras?.lokasi_dinas || false,
            catatan: extras?.catatan || null,
            offline_queue_id: 'q-' + Date.now(),
        };

        saveOfflineQueue(payload);

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
                throw new Error(data.message || 'Gagal absen');
            }
            clearOfflineQueueItem(payload.offline_queue_id);
            return data;
        } catch (e) {
            if (!navigator.onLine) {
                throw new Error('Offline: data tersimpan lokal, akan dikirim saat online.');
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
        if (!navigator.geolocation) return;
        navigator.geolocation.watchPosition(
            (pos) => updateGpsUI(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy),
            () => updateGpsUI(null, null),
            { enableHighAccuracy: true, maximumAge: 10000 }
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        initClock();
        initGpsWatch();
        syncOfflineQueue();
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
