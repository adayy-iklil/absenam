@extends('layouts.app')

@section('title', 'Kelola Data Jurusan - SMKN 6 Jakarta')

@section('header_title', 'Kelola Data Jurusan')
@section('header_subtitle', 'Manajemen 7 Program Keahlian / Jurusan di SMKN 6 Jakarta')

@section('header_action')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-2 transition-all">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    <button onclick="document.getElementById('addDeptModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs flex items-center gap-2 shadow-md shadow-brand-500/20 transition-all">
        <i class="fa-solid fa-plus"></i> Tambah Jurusan Baru
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($departments as $dept)
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-black bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
                        {{ $dept->code }}
                    </span>
                    <button onclick="editDeptModal('{{ $dept->id }}', '{{ $dept->code }}', '{{ addslashes($dept->name) }}')" class="px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 transition-colors">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                </div>

                <h3 class="text-base font-extrabold text-slate-900 dark:text-white leading-snug mb-2">
                    {{ $dept->name }}
                </h3>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 font-semibold mt-4">
                <span><i class="fa-solid fa-users text-brand-500"></i> Total Siswa Terdaftar:</span>
                <span class="font-extrabold text-slate-900 dark:text-white text-sm bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-full">
                    {{ $dept->students_count ?? \App\Models\Student::where('department_id', $dept->id)->count() }} Siswa
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Dept Modal -->
<div id="addDeptModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Tambah Jurusan Baru</h3>
            <button onclick="document.getElementById('addDeptModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Kode Jurusan (Contoh: RPL, DKV)</label>
                <input type="text" name="code" required placeholder="RPL" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold uppercase">
            </div>
            <div>
                <label class="block font-bold mb-1">Nama Program Keahlian / Jurusan</label>
                <input type="text" name="name" required placeholder="Rekayasa Perangkat Lunak" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('addDeptModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold shadow-md shadow-brand-500/20">Simpan Jurusan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Dept Modal -->
<div id="editDeptModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Edit Data Jurusan</h3>
            <button onclick="document.getElementById('editDeptModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form id="editDeptForm" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Kode Jurusan</label>
                <input type="text" id="editCode" name="code" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold uppercase">
            </div>
            <div>
                <label class="block font-bold mb-1">Nama Program Keahlian / Jurusan</label>
                <input type="text" id="editName" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('editDeptModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold shadow-md shadow-brand-500/20">Perbarui Jurusan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editDeptModal(id, code, name) {
    document.getElementById('editDeptForm').action = "/admin/departments/" + id + "/update";
    document.getElementById('editCode').value = code;
    document.getElementById('editName').value = name;
    document.getElementById('editDeptModal').classList.remove('hidden');
}
</script>
@endsection
