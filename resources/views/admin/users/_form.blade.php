<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border rounded px-3 py-2" required />
    </div>

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border rounded px-3 py-2" required />
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input id="admin-password" type="password" name="password" class="w-full border rounded px-3 py-2" {{ isset($user) ? '' : 'required' }} />
            @if(isset($user))
                <p class="text-xs text-gray-500">Leave blank to keep current password.</p>
            @endif
            <div id="password-strength" class="mt-2 hidden">
                <div class="w-full bg-gray-200 rounded h-2 overflow-hidden">
                    <div id="password-strength-bar" class="h-2 bg-red-500 w-0"></div>
                </div>
                <div id="password-strength-text" class="text-xs mt-1 text-gray-600"></div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input id="admin-password-confirm" type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" {{ isset($user) ? '' : 'required' }} />
        </div>
    </div>

    <div class="mt-3">
        <label class="inline-flex items-center">
            <input id="send-reset" type="checkbox" name="send_reset" value="1" {{ old('send_reset') ? 'checked' : '' }} />
            <span class="ml-2">Generate temporary password and email a reset link instead of setting a password</span>
        </label>
    </div>

    <div class="mt-3">
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin ?? false) ? 'checked' : '' }} />
            <span class="ml-2">Admin</span>
        </label>
    </div>

    <div>
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
    </div>

@once
    @push('scripts')
    <script>
    (function(){
        const pwd = document.getElementById('admin-password');
        const pwdConfirm = document.getElementById('admin-password-confirm');
        const strength = document.getElementById('password-strength');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');
        const sendReset = document.getElementById('send-reset');

        function scorePassword(s) {
            let score = 0;
            if (!s) return score;
            // length
            if (s.length >= 8) score += 1;
            if (s.length >= 12) score += 1;
            // variety
            if (/[A-Z]/.test(s)) score += 1;
            if (/[0-9]/.test(s)) score += 1;
            if (/[^A-Za-z0-9]/.test(s)) score += 1;
            return score; // 0-5
        }

        function updateStrength() {
            const val = pwd.value || '';
            const s = scorePassword(val);
            if (val.length === 0) { strength.classList.add('hidden'); return; }
            strength.classList.remove('hidden');
            const pct = Math.min(100, Math.round((s/5)*100));
            strengthBar.style.width = pct + '%';
            if (s <= 1) { strengthBar.className = 'h-2 bg-red-500 w-0'; strengthText.textContent = 'Very weak'; }
            else if (s === 2) { strengthBar.className = 'h-2 bg-orange-500 w-0'; strengthText.textContent = 'Weak'; }
            else if (s === 3) { strengthBar.className = 'h-2 bg-yellow-400 w-0'; strengthText.textContent = 'Fair'; }
            else if (s === 4) { strengthBar.className = 'h-2 bg-green-400 w-0'; strengthText.textContent = 'Good'; }
            else { strengthBar.className = 'h-2 bg-green-600 w-0'; strengthText.textContent = 'Strong'; }
            // set width after class change
            setTimeout(()=> strengthBar.style.width = pct + '%', 0);
        }

        if (pwd) pwd.addEventListener('input', updateStrength);

        function togglePasswordFields() {
            const checked = sendReset && sendReset.checked;
            if (pwd) { pwd.disabled = checked; }
            if (pwdConfirm) { pwdConfirm.disabled = checked; }
            if (checked) { strength.classList.add('hidden'); }
        }

        if (sendReset) {
            sendReset.addEventListener('change', togglePasswordFields);
            // initialize
            togglePasswordFields();
        }
    })();
    </script>
    @endpush
@endonce
</div>
