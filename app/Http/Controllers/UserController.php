public function edit(User $user) {
        $currentUser = auth()->user();

        // Keamanan: Jika Admin Sekre, HANYA boleh edit relawan di sekrenya sendiri
        if ($currentUser->hasRole('Admin Sekre')) {
            if (!$user->hasRole('Relawan') || $user->secretariat_id != $currentUser->secretariat_id) {
                abort(403, 'Anda tidak memiliki izin untuk mengedit pengguna ini.');
            }
        }

        $secretariats = Secretariat::all();
        return view('admin.users.edit', compact('user', 'secretariats'));
    }

    public function update(Request $request, User $user) {
        $currentUser = auth()->user();

        // Keamanan: Jika Admin Sekre, HANYA boleh edit relawan di sekrenya sendiri
        if ($currentUser->hasRole('Admin Sekre')) {
            if (!$user->hasRole('Relawan') || $user->secretariat_id != $currentUser->secretariat_id) {
                abort(403, 'Anda tidak memiliki izin untuk mengedit pengguna ini.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'secretariat_id' => 'required|exists:secretariats,id',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email, 'secretariat_id' => $request->secretariat_id]);

        if ($request->filled('password')) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }