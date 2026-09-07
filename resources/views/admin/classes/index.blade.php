@extends('layouts.app')

@section('title', 'Kelola Data Kelas - SMKN 6 Jakarta')

@section('header_title', 'Kelola Data Kelas')
@section('header_subtitle', 'Manajemen data kelas tingkat X, XI, dan XII SMKN 6 Jakarta')

@section('header_action')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-2 transition-all">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    <button onclick="document.getElementById('addClassModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs flex items-center gap-2 shadow-md shadow-brand-500/20 transition-all">
        <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Sequential Grade Badge Info Header -->
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400">
            <span><i class="fa-solid fa-layer-group text-brand-600"></i> Total {{ count($classes) }} Kelas Terdaftar</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">Tingkat X (10)</span>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300">Tingkat XI (11)</span>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300">Tingkat XII (12)</span>
        </div>
    </div>

    <!-- Table of Classes (Ordered X -> XI -> XII) -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-800">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Tingkat</th>
                        <th class="py-3.5 px-4">Nama Kelas</th>
                        <th class="py-3.5 px-4">Jurusan</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-3.5 px-4 text-right">Aksi / Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($classes as $index => $cls)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-500">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            @if($cls->grade == 'X')
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-black bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300">Tingkat X</span>
                            @elseif($cls->grade == 'XI')
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-black bg-indigo-100 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300">Tingkat XI</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-black bg-purple-100 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300">Tingkat XII</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-black text-slate-900 dark:text-white text-sm">{{ $cls->name }}</td>
                        <td class="py-3 px-4 font-semibold text-slate-600 dark:text-slate-300">
                            {{ $cls->department->name ?? '-' }} ({{ $cls->department->code ?? '-' }})
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                {{ $cls->students_count ?? \App\Models\Student::where('class_id', $cls->id)->count() }} Siswa
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="editClassModal('{{ $cls->id }}', '{{ $cls->name }}', '{{ $cls->department_id }}', '{{ $cls->grade }}')" class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 transition-colors mr-1">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data kelas terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Class Modal -->
<div id="addClassModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Tambah Kelas Baru</h3>
            <button onclick="document.getElementById('addClassModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Tingkat Kelas</label>
                <select name="grade" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                    <option value="X">Tingkat X (10)</option>
                    <option value="XI">Tingkat XI (11)</option>
                    <option value="XII">Tingkat XII (12)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Jurusan</label>
                <select name="department_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->code }} — {{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Nama Kelas (Contoh: X RPL 1)</label>
                <input type="text" name="name" required placeholder="X RPL 1" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('addClassModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold shadow-md shadow-brand-500/20">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Class Modal -->
<div id="editClassModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Edit Data Kelas</h3>
            <button onclick="document.getElementById('editClassModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form id="editClassForm" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Tingkat Kelas</label>
                <select id="editGrade" name="grade" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                    <option value="X">Tingkat X (10)</option>
                    <option value="XI">Tingkat XI (11)</option>
                    <option value="XII">Tingkat XII (12)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Jurusan</label>
                <select id="editDepartmentId" name="department_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->code }} — {{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Nama Kelas</label>
                <input type="text" id="editName" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('editClassModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold shadow-md shadow-brand-500/20">Perbarui Kelas</button>
            </div>
        </form>
    </div>
</div>

<script>
function editClassModal(id, name, department_id, grade) {
    document.getElementById('editClassForm').action = "/admin/classes/" + id + "/update";
    document.getElementById('editName').value = name;
    document.getElementById('editDepartmentId').value = department_id;
    document.getElementById('editGrade').value = grade;
    document.getElementById('editClassModal').classList.remove('hidden');
}
</script>
@endsection
