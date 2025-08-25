<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Super Admin Users</h2>
            <p class="text-sm text-gray-600 mb-6">
                Manage users with super admin access. Super admins have full access to all system features and can manage other users' permissions.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
