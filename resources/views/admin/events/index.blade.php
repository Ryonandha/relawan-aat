<div class="mt-4 flex gap-2">
    <a href="{{ route('admin.events.participants', $event->id) }}" class="bg-aat-yellow text-aat-text px-3 py-1 rounded text-sm font-bold shadow-sm">
        Lihat Peserta ({{ $event->registrations_count }})
    </a>
</div>