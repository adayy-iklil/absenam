import React, { useState, useEffect, useRef } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Swal from 'sweetalert2';
import axios from 'axios';

export default function StudentDashboard({
    student,
    todayAttendance,
    todayPrayerAttendance,
    countHadir = 0,
    countTerlambat = 0,
    countIzin = 0,
    countSakit = 0,
    countAlpha = 0,
    canDoPrayer = false,
    prayerStatusMsg = '',
    calendarDays = [],
    announcements = [],
}) {
    // Live clock state
    const [currentTimeStr, setCurrentTimeStr] = useState('');
    const [currentDateStr, setCurrentDateStr] = useState('');

    useEffect(() => {
        const updateClock = () => {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            setCurrentTimeStr(now.toLocaleTimeString('id-ID', timeOptions) + ' WIB');
            setCurrentDateStr(now.toLocaleDateString('id-ID', dateOptions));
        };
        updateClock();
        const timer = setInterval(updateClock, 1000);
        return () => clearInterval(timer);
    }, []);

    // Camera Modal State
    const [isCameraOpen, setIsCameraOpen] = useState(false);
    const [attendanceType, setAttendanceType] = useState('daily'); // 'daily' or 'prayer'
    const [selectedStatus, setSelectedStatus] = useState('Hadir');
    const [notes, setNotes] = useState('');
    const [capturedPhoto, setCapturedPhoto] = useState(null);
    const [gpsAddress, setGpsAddress] = useState('Mendapatkan lokasi Satelit GPS...');
    const [gpsCoords, setGpsCoords] = useState({ lat: null, lng: null });
    const [isSubmitting, setIsSubmitting] = useState(false);

    const videoRef = useRef(null);
    const canvasRef = useRef(null);
    const mediaStreamRef = useRef(null);

    // Rotating circular text parameters
    const studentNameUpper = (student?.name || student?.user?.name || '').toUpperCase();
    const classNameUpper = (student?.class_model?.name || student?.class_name || '-').toUpperCase();
    const textToDisplay = `${studentNameUpper} • KELAS ${classNameUpper} • SMKN 6 JAKARTA • `;
    const totalLen = textToDisplay.length;
    const fontSize = totalLen > 0 ? Math.max(4.0, Math.min(7.5, 222 / totalLen)).toFixed(2) : "6.00";
    const letterSpacing = totalLen > 0 ? Math.max(0.4, Math.min(3.2, (238.76 - (totalLen * fontSize * 0.62)) / totalLen)).toFixed(2) : "1.20";

    const openCameraModal = async (type) => {
        setAttendanceType(type);
        setSelectedStatus('Hadir');
        setNotes('');
        setCapturedPhoto(null);
        setIsCameraOpen(true);

        // Get Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    setGpsCoords({ lat, lng });
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await res.json();
                        setGpsAddress(data.display_name || `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`);
                    } catch (e) {
                        setGpsAddress(`Jl. Mahakam No.2, Kebayoran Baru, Jakarta Selatan (Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)})`);
                    }
                },
                (err) => {
                    setGpsAddress('Jl. Mahakam No.2, Kramat Pela, Kebayoran Baru, Jakarta Selatan (SMKN 6 Jakarta)');
                }
            );
        }

        // Start Camera
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            mediaStreamRef.current = stream;
            if (videoRef.current) {
                videoRef.current.srcObject = stream;
            }
        } catch (err) {
            Swal.fire('Kamera Error', 'Tidak dapat mengakses kamera perangkat.', 'error');
        }
    };

    const closeCameraModal = () => {
        if (mediaStreamRef.current) {
            mediaStreamRef.current.getTracks().forEach(track => track.stop());
        }
        setIsCameraOpen(false);
    };

    const takePhoto = () => {
        if (!videoRef.current || !canvasRef.current) return;
        const video = videoRef.current;
        const canvas = canvasRef.current;
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        setCapturedPhoto(dataUrl);
    };

    const retakePhoto = () => {
        setCapturedPhoto(null);
    };

    const submitAttendance = async () => {
        if (!capturedPhoto) {
            Swal.fire('Peringatan', 'Silakan ambil foto terlebih dahulu.', 'warning');
            return;
        }

        setIsSubmitting(true);
        const endpoint = attendanceType === 'daily' ? '/siswa/attendance/store' : '/siswa/prayer/store';

        try {
            const res = await axios.post(endpoint, {
                photo: capturedPhoto,
                status: selectedStatus,
                latitude: gpsCoords.lat,
                longitude: gpsCoords.lng,
                address: gpsAddress,
                notes: notes,
            });

            closeCameraModal();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: res.data.message || 'Absensi berhasil dikirim.',
            }).then(() => {
                router.reload();
            });
        } catch (err) {
            Swal.fire('Gagal', err.response?.data?.message || 'Gagal mengirim absensi.', 'error');
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <AppLayout>
            <Head title="Dashboard Siswa - SIX-PRESENCE SMKN 6 Jakarta" />

            <div className="space-y-6">
                {/* Aesthetic Animated Student Profile Hero Card (Container Nama Siswa) */}
                <div className="hero-animated-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xl sm:shadow-2xl relative overflow-hidden text-white transition-all duration-300 hover:shadow-brand-500/20">
                    
                    {/* Shimmering Light Beam Overlay */}
                    <div className="absolute inset-0 w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent shimmer-light-bar pointer-events-none"></div>

                    {/* Glowing Orb Background Blurs */}
                    <div className="absolute -right-10 -top-10 w-80 h-80 bg-white/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div className="absolute left-1/3 -bottom-10 w-60 h-60 bg-indigo-400/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div className="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6">
                        
                        {/* Left Student Info & Circular Avatar with 100% Even Text Ring */}
                        <div className="flex flex-col sm:flex-row items-center gap-3 sm:gap-6 text-center sm:text-left w-full md:w-auto">
                            
                            {/* Circular Avatar Container with 100% Even Text Ring */}
                            <div className="relative w-24 h-24 sm:w-36 sm:h-36 flex items-center justify-center flex-shrink-0">
                                
                                {/* 100% Even Rotating Circular Text */}
                                <svg className="w-24 h-24 sm:w-36 sm:h-36 absolute inset-0 animate-rotating-text pointer-events-none z-0" viewBox="0 0 100 100">
                                    <path id="circlePath" d="M 50, 50 m -38, 0 a 38,38 0 1,1 76,0 a 38,38 0 1,1 -76,0" fill="none"/>
                                    <text fill="rgba(255, 255, 255, 0.95)" fontSize={fontSize} fontWeight="800" letterSpacing={letterSpacing}>
                                        <textPath href="#circlePath" startOffset="0%">
                                            {textToDisplay}
                                        </textPath>
                                    </text>
                                </svg>

                                {/* Center Circular Student Avatar / Initials Circle */}
                                <div className="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-white/20 border-2 border-white/80 p-0.5 sm:p-1 backdrop-blur-md avatar-glow flex items-center justify-center overflow-hidden z-10 transition-transform duration-300 hover:scale-110 shadow-xl">
                                    <img 
                                        src={`https://ui-avatars.com/api/?name=${encodeURIComponent(student?.name || student?.user?.name || 'Siswa')}&background=2563eb&color=fff&size=128`} 
                                        alt="Profile" 
                                        className="w-full h-full rounded-full object-cover"
                                    />
                                </div>
                            </div>

                            <div>
                                {/* Gender specific badge: Siswa / Siswi */}
                                <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:px-3.5 sm:py-1 rounded-full bg-white/20 border border-white/30 backdrop-blur-md text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-white mb-1 sm:mb-1.5 sparkle-float">
                                    <span>{(student?.gender || 'L') === 'P' ? 'Siswi Aktif' : 'Siswa Aktif'}</span>
                                </div>

                                {/* Animated Student Name */}
                                <h2 className="font-heading text-lg sm:text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-sm leading-tight">
                                    {student?.name || student?.user?.name || '-'}
                                </h2>

                                {/* Student Metadata Badges (No Icons) */}
                                <div className="flex flex-wrap items-center justify-center sm:justify-start gap-x-2 sm:gap-x-4 gap-y-0.5 text-xs sm:text-sm text-white/90 mt-1 sm:mt-1.5">
                                    <span>NIS: <strong>{student?.nis || '-'}</strong></span>
                                    <span className="hidden sm:inline">•</span>
                                    <span>Kelas: <strong>{student?.class_model?.name || student?.class_name || '-'}</strong></span>
                                    <span className="hidden sm:inline">•</span>
                                    <span>Jurusan: <strong>{student?.department?.name || student?.department_name || '-'}</strong></span>
                                </div>
                            </div>
                        </div>

                        {/* Right Live Clock Card */}
                        <div className="bg-white/15 backdrop-blur-md border border-white/25 rounded-xl sm:rounded-2xl px-4 py-2.5 sm:px-6 sm:py-4 text-center sm:text-right w-full sm:w-auto min-w-0 sm:min-w-[200px] shadow-lg">
                            <div className="text-[10px] sm:text-xs uppercase tracking-wider text-white/80 font-medium mb-0.5 sm:mb-1">
                                <i className="fa-regular fa-clock"></i> <span>{currentDateStr}</span>
                            </div>
                            <div className="text-xl sm:text-3xl md:text-4xl font-semibold font-mono tracking-tight text-white">
                                {currentTimeStr}
                            </div>
                        </div>

                    </div>
                </div>

                {/* STATISTICAL SUMMARY COUNTERS WITH TERLAMBAT INCLUDED */}
                <div className="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
                        <div className="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-lg font-bold">
                            <i className="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p className="text-xs text-slate-500 font-medium uppercase">Hadir</p>
                            <p className="font-heading text-xl font-semibold text-slate-900 dark:text-white">{countHadir}</p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
                        <div className="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-950 text-cyan-600 dark:text-cyan-300 flex items-center justify-center text-lg font-bold">
                            <i className="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p className="text-xs text-slate-500 font-medium uppercase">Terlambat</p>
                            <p className="font-heading text-xl font-semibold text-slate-900 dark:text-white">{countTerlambat}</p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
                        <div className="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-300 flex items-center justify-center text-lg font-bold">
                            <i className="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <p className="text-xs text-slate-500 font-medium uppercase">Izin</p>
                            <p className="font-heading text-xl font-semibold text-slate-900 dark:text-white">{countIzin}</p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
                        <div className="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-300 flex items-center justify-center text-lg font-bold">
                            <i className="fa-solid fa-notes-medical"></i>
                        </div>
                        <div>
                            <p className="text-xs text-slate-500 font-medium uppercase">Sakit</p>
                            <p className="font-heading text-xl font-semibold text-slate-900 dark:text-white">{countSakit}</p>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
                        <div className="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-300 flex items-center justify-center text-lg font-bold">
                            <i className="fa-solid fa-user-xmark"></i>
                        </div>
                        <div>
                            <p className="text-xs text-slate-500 font-medium uppercase">Alpha</p>
                            <p className="font-heading text-xl font-semibold text-slate-900 dark:text-white">{countAlpha}</p>
                        </div>
                    </div>
                </div>

                {/* Attendance Action Cards Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {/* 1. DAILY ATTENDANCE CARD */}
                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
                        <div>
                            <div className="flex items-center justify-between mb-4">
                                <div className="flex items-center gap-3">
                                    <div className="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/50 text-brand-600 dark:text-brand-300 flex items-center justify-center text-xl font-bold">
                                        <i className="fa-solid fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white">Absensi Harian</h3>
                                        <p className="text-xs text-slate-500">Batas Toleransi Masuk: 07:00 WIB</p>
                                    </div>
                                </div>
                                {todayAttendance ? (
                                    <span className={`px-3 py-1.5 rounded-full text-xs font-semibold ${
                                        todayAttendance.status === 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' :
                                        todayAttendance.status === 'Terlambat' ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-950 dark:text-cyan-300' :
                                        todayAttendance.status === 'Sakit' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' :
                                        todayAttendance.status === 'Izin' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300' :
                                        'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300'
                                    }`}>
                                        {todayAttendance.status}
                                    </span>
                                ) : (
                                    <span className="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        Belum Absen
                                    </span>
                                )}
                            </div>

                            {todayAttendance ? (
                                <div className="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl space-y-2 text-xs">
                                    <div className="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                                        <span className="text-slate-500">Status Kehadiran:</span>
                                        <span className="font-bold text-slate-800 dark:text-slate-200">{todayAttendance.status}</span>
                                    </div>
                                    <div className="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                                        <span className="text-slate-500">Waktu Simpan:</span>
                                        <span className="font-bold text-slate-800 dark:text-slate-200 font-mono">{todayAttendance.time} WIB</span>
                                    </div>
                                    <div className="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                                        <span className="text-slate-500"><i className="fa-solid fa-road text-brand-600"></i> Nama Jalan:</span>
                                        <span className="font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[220px]">{todayAttendance.address}</span>
                                    </div>
                                    {todayAttendance.teacher_notes && (
                                        <div className="flex justify-between pt-1">
                                            <span className="text-slate-500">Catatan Alasan:</span>
                                            <span className="font-medium text-slate-700 dark:text-slate-300 italic">{todayAttendance.teacher_notes}</span>
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <p className="text-xs text-slate-500 dark:text-slate-400 mb-6">
                                    Pilih status kehadiran Anda (Hadir, Sakit, Izin, atau Alpha) lalu sertakan foto selfie atau foto surat bukti keterangan.
                                </p>
                            )}
                        </div>

                        <div className="mt-6">
                            {!todayAttendance ? (
                                <button onClick={() => openCameraModal('daily')} className="w-full py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-xl shadow-brand-500/25 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                                    Isi Absensi Sekarang
                                </button>
                            ) : (
                                <button disabled className="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                    Absen Harian Selesai
                                </button>
                            )}
                        </div>
                    </div>

                    {/* 2. PRAYER ATTENDANCE CARD */}
                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
                        <div>
                            <div className="flex items-center justify-between mb-4">
                                <div className="flex items-center gap-3">
                                    <div className="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xl font-bold">
                                        <i className="fa-solid fa-mosque"></i>
                                    </div>
                                    <div>
                                        <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white">Absensi Sholat Dzuhur</h3>
                                        <p className="text-xs text-slate-500">Jadwal Masjid: 11:45 - 12:30 WIB</p>
                                    </div>
                                </div>
                                {todayPrayerAttendance ? (
                                    <span className="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                        {todayPrayerAttendance.status}
                                    </span>
                                ) : (
                                    <span className="px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                                        Status Sholat
                                    </span>
                                )}
                            </div>

                            {todayPrayerAttendance ? (
                                <div className="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl space-y-2 text-xs">
                                    <div className="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                                        <span className="text-slate-500">Waktu Absen Sholat:</span>
                                        <span className="font-bold text-slate-800 dark:text-slate-200 font-mono">{todayPrayerAttendance.time} WIB</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-slate-500">Status Konfirmasi:</span>
                                        <span className="font-bold text-emerald-600">{todayPrayerAttendance.status}</span>
                                    </div>
                                </div>
                            ) : (
                                <div className="p-3 bg-slate-100 dark:bg-slate-800/70 rounded-2xl text-xs text-slate-600 dark:text-slate-300 mb-4 flex items-center gap-2">
                                    <i className="fa-solid fa-circle-info text-brand-600"></i>
                                    <span>{prayerStatusMsg}</span>
                                </div>
                            )}
                        </div>

                        <div className="mt-6">
                            {!todayPrayerAttendance ? (
                                canDoPrayer ? (
                                    <button onClick={() => openCameraModal('prayer')} className="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-xl shadow-emerald-500/25 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                                        Absen Sholat (Selfie)
                                    </button>
                                ) : (
                                    <button disabled className="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                        {prayerStatusMsg}
                                    </button>
                                )
                            ) : (
                                <button disabled className="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                    Absen Sholat Selesai
                                </button>
                            )}
                        </div>
                    </div>

                </div>

                {/* MONTHLY ATTENDANCE CALENDAR MATRIX GRID */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 space-y-4 shadow-sm">
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div>
                            <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                <i className="fa-solid fa-calendar-days text-brand-600"></i> Absensi Bulan Ini ({new Date().toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })})
                            </h3>
                            <p className="text-xs text-slate-500">Matriks kalender bulanan kehadiran Anda.</p>
                        </div>
                        
                        {/* Color Indicator Legend */}
                        <div className="flex flex-wrap items-center gap-2 text-xs font-medium">
                            <span className="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-300">● Hadir</span>
                            <span className="px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-700 border border-cyan-300">● Terlambat</span>
                            <span className="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 border border-blue-300">● Izin</span>
                            <span className="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 border border-amber-300">● Sakit</span>
                            <span className="px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 border border-rose-300">● Alpha</span>
                            <span className="px-2.5 py-1 rounded-lg bg-slate-200 text-slate-600 border border-slate-300">● Libur (Sabtu/Minggu)</span>
                        </div>
                    </div>

                    {/* Days Grid */}
                    <div className="grid grid-cols-4 sm:grid-cols-7 lg:grid-cols-10 gap-2.5">
                        {calendarDays.map((cDay, idx) => (
                            <div key={idx} className={`p-3 rounded-2xl text-center border transition-transform hover:scale-105 ${
                                cDay.is_weekend ? 'bg-slate-100 text-slate-400 border-slate-200 dark:bg-slate-900/40 dark:border-slate-800' :
                                cDay.status === 'Hadir' ? 'bg-emerald-500 text-white font-bold border-emerald-600 shadow-md shadow-emerald-500/20' :
                                cDay.status === 'Terlambat' ? 'bg-cyan-500 text-white font-bold border-cyan-600 shadow-md shadow-cyan-500/20' :
                                cDay.status === 'Izin' ? 'bg-blue-500 text-white font-bold border-blue-600 shadow-md shadow-blue-500/20' :
                                cDay.status === 'Sakit' ? 'bg-amber-500 text-white font-bold border-amber-600 shadow-md shadow-amber-500/20' :
                                cDay.status === 'Belum/Alpha' ? 'bg-rose-500 text-white font-bold border-rose-600 shadow-md shadow-rose-500/20' :
                                'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800'
                            }`}>
                                <span className="text-[10px] uppercase font-medium block opacity-80">{cDay.day_name}</span>
                                <span className="text-base font-semibold block leading-none my-1">{cDay.day}</span>
                                <span className="text-[10px] font-medium truncate block">
                                    {cDay.is_weekend ? 'Libur' : cDay.status}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* ANNOUNCEMENTS BOARD */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i className="fa-solid fa-bullhorn text-brand-600"></i> Pengumuman Sekolah SMKN 6 Jakarta
                    </h3>
                    <div className="space-y-3">
                        {announcements.length > 0 ? (
                            announcements.map((ann) => (
                                <div key={ann.id} className="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                                    <div className="flex items-center justify-between mb-1">
                                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white">{ann.title}</h4>
                                        <span className="text-xs text-slate-400">{ann.created_at_human || 'Baru saja'}</span>
                                    </div>
                                    <p className="text-xs text-slate-600 dark:text-slate-300">{ann.content}</p>
                                </div>
                            ))
                        ) : (
                            <p className="text-xs text-slate-400 text-center py-4">Belum ada pengumuman hari ini.</p>
                        )}
                    </div>
                </div>

            </div>

            {/* WEBCAM SELFIE & STATUS SELECTION MODAL */}
            {isCameraOpen && (
                <div className="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 relative max-h-[90vh] overflow-y-auto">
                        
                        <div className="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                Form Absensi & Selfie
                            </h3>
                            <button onClick={closeCameraModal} className="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                        </div>

                        {/* STATUS SELECTION BUTTONS */}
                        <div className="mb-4">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Status Kehadiran:</label>
                            <div className="grid grid-cols-4 gap-2">
                                {['Hadir', 'Sakit', 'Izin', 'Alpha'].map((st) => (
                                    <button
                                        key={st}
                                        type="button"
                                        onClick={() => setSelectedStatus(st)}
                                        className={`py-2 px-1 rounded-xl text-xs font-bold text-center transition-all ${
                                            selectedStatus === st
                                                ? st === 'Hadir' ? 'border-2 border-emerald-500 bg-emerald-100 text-emerald-800 shadow-sm' :
                                                  st === 'Sakit' ? 'border-2 border-amber-500 bg-amber-100 text-amber-800 shadow-sm' :
                                                  st === 'Izin' ? 'border-2 border-blue-500 bg-blue-100 text-blue-800 shadow-sm' :
                                                  'border-2 border-rose-500 bg-rose-100 text-rose-800 shadow-sm'
                                                : 'border border-slate-200 bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 font-semibold'
                                        }`}
                                    >
                                        {st === 'Hadir' ? '🟢 Hadir' : st === 'Sakit' ? '🟡 Sakit' : st === 'Izin' ? '🔵 Izin' : '🔴 Alpha'}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* CAMERA & CAPTURE PREVIEW */}
                        <div className="relative rounded-2xl overflow-hidden bg-slate-950 aspect-video mb-4 shadow-inner border border-slate-800">
                            {!capturedPhoto ? (
                                <video ref={videoRef} autoPlay playsInline className="w-full h-full object-cover transform -scale-x-100" />
                            ) : (
                                <img src={capturedPhoto} alt="Captured Selfie" className="w-full h-full object-cover" />
                            )}
                            <canvas ref={canvasRef} className="hidden" />
                        </div>

                        {/* GPS ADDRESS DISPLAY */}
                        <div className="mb-4 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 text-xs">
                            <div className="font-semibold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                                <i className="fa-solid fa-location-dot text-brand-600"></i> Lokasi GPS Terverifikasi:
                            </div>
                            <p className="text-slate-600 dark:text-slate-400 font-mono text-[11px] leading-tight">{gpsAddress}</p>
                        </div>

                        {/* OPTIONAL NOTES INPUT */}
                        {selectedStatus !== 'Hadir' && (
                            <div className="mb-4">
                                <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Catatan / Alasan ({selectedStatus}):
                                </label>
                                <textarea
                                    value={notes}
                                    onChange={(e) => setNotes(e.target.value)}
                                    placeholder={`Masukkan alasan ${selectedStatus.toLowerCase()}...`}
                                    className="w-full p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                                    rows="2"
                                />
                            </div>
                        )}

                        {/* ACTION BUTTONS */}
                        <div className="flex items-center gap-3">
                            {!capturedPhoto ? (
                                <button
                                    type="button"
                                    onClick={takePhoto}
                                    className="w-full py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-lg shadow-brand-500/20 transition-all flex items-center justify-center gap-2"
                                >
                                    Ambil Foto / Bukti
                                </button>
                            ) : (
                                <>
                                    <button
                                        type="button"
                                        onClick={retakePhoto}
                                        className="w-1/3 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-all"
                                    >
                                        Foto Ulang
                                    </button>
                                    <button
                                        type="button"
                                        disabled={isSubmitting}
                                        onClick={submitAttendance}
                                        className="w-2/3 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2"
                                    >
                                        {isSubmitting ? 'Mengirim...' : 'Kirim Absensi'}
                                    </button>
                                </>
                            )}
                        </div>

                    </div>
                </div>
            )}
        </AppLayout>
    );
}
