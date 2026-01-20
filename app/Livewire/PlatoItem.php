<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use Livewire\Attributes\On;

class PlatoItem extends Component
{
    public Plato $plato;
    public $cantidad = 0;
    public $showControls = false;

    public function mount()
    {
        $this->syncQuantity();
    }

    #[On('cart-updated')]
    public function syncQuantity()
    {
        $cart = session('carrito', []);
        if (isset($cart[$this->plato->id])) {
            $this->cantidad = $cart[$this->plato->id]['cantidad'];
            $this->showControls = true;
        } else {
            $this->cantidad = 0;
            $this->showControls = false;
        }
    }

    public function addToCart()
    {
        $this->showControls = true;
        $this->cantidad = 1;
        $this->dispatch('add-to-cart', platoId: $this->plato->id);
    }

    public function increment()
    {
        $this->dispatch('update-quantity', platoId: $this->plato->id, delta: 1);
    }

    public function decrement()
    {
        $this->dispatch('update-quantity', platoId: $this->plato->id, delta: -1);
    }

    public function render()
    {
        return view('livewire.plato-item');
    }
}
