@extends('layouts.app')

@section('title', 'Manajemen Guru - Absenam SMKN 6 Jakarta')

@section('header_title', 'Kelola Data Guru SMKN 6 Jakarta')
@section('header_subtitle', 'Kelola data guru, hak akses, dan tugas wali kelas.')

@section('header_action')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-300 flex items-center gap-2 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    <button onclick="document.getElementById('addTeacherModal').classList.remove('hidden')" class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-lg shadow-emerald-500/25 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Guru Baru
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase">
                        <th class="py-3 px-4">NIP</th>
                        <th class="py-3 px-4">Nama Guru</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Wali Kelas</th>
                        <th class="py-3 px-4 text-center">Aksi (Edit / Hapus)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($teachers as $t)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                        <td class="py-3 px-4 font-mono font-bold text-emerald-600">{{ $t->nip }}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900 dark:text-white">{{ $t->name ?? ($t->user->name ?? '-') }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $t->user->email ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($t->teacherClassModel)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-[11px]">
                                    <i class="fa-solid fa-user-shield"></i> Wali {{ $t->teacherClassModel->name }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium">Pengajar / Piket</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditTeacherModal({{ json_encode($t) }})" class="p-2 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-600 hover:bg-amber-200 font-bold text-xs" title="Edit Data Guru">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.teachers.delete', $t->id) }}" method="POST" onsubmit="return confirm('Hapus data guru ini?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl bg-rose-100 text-rose-600 font-bold text-xs" title="Hapus Guru">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Belum ada data guru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $teachers->links() }}
        </div>
    </div>

</div>

<!-- Modal Add Teacher -->
<div id="addTeacherModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Tambah Guru SMKN 6 Jakarta</h3>
            <button onclick="document.getElementById('addTeacherModal').classList.add('hidden')" class="text-slate-400 text-xl">&times;</button>
        </div>

        <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="name" required placeholder="Budi Santoso, S.Pd." class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">NIP</label>
                    <input type="text" name="nip" required placeholder="198503152010011002" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                </div>
                <div>
                    <label class="block font-bold mb-1">Jenis Kelamin</label>
                    <select name="gender" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                        <option value="L">Guru (Bapak)</option>
                        <option value="P">Guru (Ibu)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1">Email Guru</label>
                <input type="email" name="email" required placeholder="budi.guru@absenam.sch.id" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div>
                <label class="block font-bold mb-1">Password Initial</label>
                <input type="password" name="password" required value="password123" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Jurusan</label>
                    <select name="department_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                        <option value="">Pengajar Umum</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Penugasan Wali Kelas</label>
                    <select name="teacher_class_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                        <option value="">Bukan Wali Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">Wali Kelas {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold">Simpan Data Guru</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Teacher -->
<div id="editTeacherModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Edit Data Guru</h3>
            <button onclick="document.getElementById('editTeacherModal').classList.add('hidden')" class="text-slate-400 text-xl">&times;</button>
        </div>

        <form id="editTeacherForm" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="name" id="edit_tname" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">NIP</label>
                    <input type="text" name="nip" id="edit_tnip" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                </div>
                <div>
                    <label class="block font-bold mb-1">Jenis Kelamin</label>
                    <select name="gender" id="edit_tgender" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                        <option value="L">Guru (Bapak)</option>
                        <option value="P">Guru (Ibu)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1">Email Guru</label>
                <input type="email" name="email" id="edit_temail" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div>
                <label class="block font-bold mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Jurusan</label>
                    <select name="department_id" id="edit_tdepartment_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                        <option value="">Pengajar Umum</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Penugasan Wali Kelas</label>
                    <select name="teacher_class_id" id="edit_tclass_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                        <option value="">Bukan Wali Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">Wali Kelas {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold">Perbarui Data Guru</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditTeacherModal(teacher) {
        document.getElementById('editTeacherForm').action = "/admin/teachers/" + teacher.id + "/update";
        document.getElementById('edit_tname').value = teacher.name || (teacher.user ? teacher.user.name : '');
        document.getElementById('edit_tnip').value = teacher.nip;
        document.getElementById('edit_tgender').value = teacher.gender;
        document.getElementById('edit_temail').value = teacher.user ? teacher.user.email : '';
        document.getElementById('edit_tdepartment_id').value = teacher.department_id || '';
        document.getElementById('edit_tclass_id').value = teacher.teacher_class_id || '';
        
        document.getElementById('editTeacherModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
