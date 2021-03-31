<div>
    <a href="#" wire:click="isActive({{ $rfq->id }})" id="{{ $rfq->id }}" >
        <i class="fas {{ ($rfq->isActive) ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-3x"></i>
    </a>
</div>
