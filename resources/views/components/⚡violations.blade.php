<?php
use Livewire\Component;
use App\Services\GPIOService;

new class extends Component {
    public int $angle = 0;

    public function moveTo0(): void
    {
        GPIOService::move(18, 0);
        $this->angle = 0;
    }

    public function moveTo90(): void
    {
        GPIOService::move(18, 90);
        $this->angle = 90;
    }

    public function moveTo180(): void
    {
        GPIOService::move(18, 180);
        $this->angle = 180;
    }
};
?>

<div>
    <button wire:click="moveTo0" class="btn btn-danger">0°</button>
    <button wire:click="moveTo90" class="btn btn-warning">90°</button>
    <button wire:click="moveTo180" class="btn btn-success">180°</button>

    <p>Current Angle: {{ $angle }}°</p>
</div>