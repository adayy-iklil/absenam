@extends('layouts.app')

@section('title', 'Kelola Data Siswa SMKN 6 Jakarta')

@section('header_title', 'Kelola Data Siswa')
@section('header_subtitle', 'Manajemen 970 data siswa resmi SMKN 6 Jakarta (Tingkat X, XI, XII)')

@section('header_action')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-2 transition-all">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    <button onclick="document.getElementById('addStudentModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs flex items-center gap-2 shadow-md shadow-brand-500/20 transition-all">
        <i class="fa-solid fa-user-plus"></i> Tambah Siswa Baru
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
        <form method="GET" action="{{ route('admin.students') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <select name="class_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-bold text-xs">
                <option value="">-- Semua Kelas (X, XI, XII Berurutan) --</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                        [{{ $cls->grade }}] {{ $cls->name }}
                    </option>
                @endforeach
            </select>

            <div class="relative flex-1 sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau Nama Siswa..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-semibold text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs">Filter</button>
            @if(request()->hasAny(['class_id', 'search']))
                <a href="{{ route('admin.students') }}" class="px-3 py-2 text-xs font-bold text-red-500 hover:underline">Reset Filter</a>
            @endif
        </form>

        <div class="text-xs font-bold text-slate-500">
            Total Siswa Terdaftar: <span class="text-brand-600 font-extrabold text-sm">{{ $students->total() }}</span> Siswa
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-800">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">NIS / User</th>
                        <th class="py-3.5 px-4">Nama Siswa</th>
                        <th class="py-3.5 px-4">Jenis Kelamin</th>
                        <th class="py-3.5 px-4">Tingkat & Kelas</th>
                        <th class="py-3.5 px-4">Jurusan</th>
                        <th class="py-3.5 px-4 text-right">Aksi / Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($students as $index => $st)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $students->firstItem() + $index }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-brand-600 dark:text-brand-400">{{ $st->nis }}</td>
                        <td class="py-3 px-4">
                            <div class="font-extrabold text-slate-900 dark:text-white text-sm">{{ $st->name }}</div>
                            <div class="text-[11px] text-slate-400 font-medium">{{ $st->user->email ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-bold">
                            @if($st->gender == 'P')
                                <span class="px-2 py-0.5 rounded-lg bg-pink-100 text-pink-700 dark:bg-pink-950/60 dark:text-pink-300 text-[10px]"><i class="fa-solid fa-venus"></i> Siswi (Perempuan)</span>
                            @else
                                <span class="px-2 py-0.5 rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 text-[10px]"><i class="fa-solid fa-mars"></i> Siswa (Laki-laki)</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-black bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                {{ $st->classModel->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-600 dark:text-slate-400">
                            {{ $st->department->code ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="editStudentModal('{{ $st->id }}', '{{ $st->nis }}', '{{ addslashes($st->name) }}', '{{ $st->user->email ?? '' }}', '{{ $st->gender }}', '{{ $st->class_id }}', '{{ $st->department_id }}', '{{ $st->phone }}')" class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.students.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-bold text-xs hover:bg-red-100 transition-colors">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 font-medium">Tidak ada data siswa ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Tambah Siswa Baru</h3>
            <button onclick="document.getElementById('addStudentModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">NIS Siswa</label>
                    <input type="text" name="nis" required placeholder="Contoh: 20250" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Jenis Kelamin</label>
                    <select name="gender" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                        <option value="L">Laki-laki (Siswa)</option>
                        <option value="P">Perempuan (Siswi)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1">Nama Lengkap Siswa</label>
                <input type="text" name="name" required placeholder="Nama Siswa Lengkap" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Email Siswa</label>
                    <input type="email" name="email" required placeholder="20250@absenam.sch.id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Password</label>
                    <input type="text" name="password" value="Rahasia6#" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Tingkat & Kelas</label>
                    <select name="class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">[{{ $cls->grade }}] {{ $cls->name }}</option>
                        @endforeach
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
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('addStudentModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold shadow-md shadow-brand-500/20">Simpan Data Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Edit Data Siswa</h3>
            <button onclick="document.getElementById('editStudentModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form id="editStudentForm" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">NIS Siswa</label>
                    <input type="text" id="editNis" name="nis" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Jenis Kelamin</label>
                    <select id="editGender" name="gender" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                        <option value="L">Laki-laki (Siswa)</option>
                        <option value="P">Perempuan (Siswi)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1">Nama Lengkap Siswa</label>
                <input type="text" id="editName" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Email Siswa</label>
                    <input type="email" id="editEmail" name="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Password Baru (Opsional)</label>
                    <input type="text" name="password" placeholder="Isi hanya jika ubah password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Tingkat & Kelas</label>
                    <select id="editClassId" name="class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-bold">
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">[{{ $cls->grade }}] {{ $cls->name }}</option>
                        @endforeach
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
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('editStudentModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold shadow-md shadow-brand-500/20">Perbarui Data Siswa</button>
            </div>
        </form>
    </div>
</div>

<script>
function editStudentModal(id, nis, name, email, gender, class_id, department_id, phone) {
    document.getElementById('editStudentForm').action = "/admin/students/" + id + "/update";
    document.getElementById('editNis').value = nis;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editGender').value = gender;
    document.getElementById('editClassId').value = class_id;
    document.getElementById('editDepartmentId').value = department_id;
    document.getElementById('editStudentModal').classList.remove('hidden');
}
</script>
@endsection
