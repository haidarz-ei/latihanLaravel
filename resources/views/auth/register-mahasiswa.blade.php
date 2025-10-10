<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-6">Register Calon Mahasiswa</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm list-disc ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.mahasiswa') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input id="name" type="text" name="name" class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required/>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input id="email" type="email" name="email" class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200"  required />
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input id="password" type="password" name="password" class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200"  required />
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required/>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                Daftar Sekarang
            </button>

            <p class="mt-4 text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk di sini</a>
            </p>

        </form>
    </div>
</x-guest-layout>