@extends('layouts.app')
@section('header', 'Manajemen Pengguna')
@section('content')

    <div class="mb-6 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Pengguna Sistem</h2>
            <p class="text-sm text-gray-500 mt-1">Atur hak akses (Role) untuk Admin, Operator, dan User biasa.</p>
        </div>
        <div class="relative">
            <input type="text" placeholder="Cari pengguna..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-unpad-blue focus:ring-1 focus:ring-unpad-blue bg-gray-50 w-64">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-extrabold">
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Email / ID</th>
                        <th class="px-6 py-4">Role Saat Ini</th>
                        <th class="px-6 py-4 text-right">Ubah Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-unpad-blue flex items-center justify-center font-bold text-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->role === 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">Super Admin</span>
                                @elseif ($user->role === 'operator')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">Operator</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">Mahasiswa / User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <select name="role" class="text-sm border-gray-200 rounded-lg bg-gray-50 py-1.5 pl-3 pr-8 focus:ring-unpad-blue focus:border-unpad-blue font-semibold">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="p-1.5 bg-white border border-gray-200 text-unpad-blue hover:bg-blue-50 rounded-lg shadow-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic font-semibold">Akun Anda Sendiri</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
