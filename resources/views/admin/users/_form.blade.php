<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border rounded px-3 py-2" required />
    </div>

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border rounded px-3 py-2" required />
    </div>

    <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" class="w-full border rounded px-3 py-2" {{ isset($user) ? '' : 'required' }} />
        @if(isset($user))
            <p class="text-xs text-gray-500">Leave blank to keep current password.</p>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" {{ isset($user) ? '' : 'required' }} />
    </div>

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin ?? false) ? 'checked' : '' }} />
            <span class="ml-2">Admin</span>
        </label>
    </div>

    <div>
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
    </div>
</div>
