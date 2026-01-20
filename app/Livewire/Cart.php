<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use Livewire\Attributes\On;

class Cart extends Component
{
    public $items = [];
    public $totalItems = 0;
    public $totalPrice = 0;

    public function mount()
    {
        // Cargar carrito de la sesión
        $this->items = session('carrito', []);
        $this->calculateTotals();
    }

    #[On('add-to-cart')]
    public function addItem($platoId)
    {
        $plato = Plato::find($platoId);

        if (!$plato || !$plato->disponible) {
            $this->dispatch('toast', message: 'Plato no disponible', type: 'error');
            return;
        }

        // Validar stock
        if ($plato->stock_ilimitado == 0) {
            $currentQty = $this->items[$platoId]['cantidad'] ?? 0;
            if ($currentQty >= $plato->stock) {
                $this->dispatch('stock-error', platoId: $platoId, stock: $plato->stock);
                return;
            }
        }

        if (!isset($this->items[$platoId])) {
            $this->items[$platoId] = [
                'nombre' => $plato->nombre,
                'precio' => $plato->precio,
                'cantidad' => 0,
                'stock' => $plato->stock,
                'stock_ilimitado' => $plato->stock_ilimitado
            ];
        }

        $this->items[$platoId]['cantidad']++;
        $this->saveCart();
        $this->calculateTotals();

        $this->dispatch('cart-updated');
    }

    #[On('update-quantity')]
    public function updateQuantity($platoId, $delta)
    {
        if (!isset($this->items[$platoId])) {
            return;
        }

        $newQuantity = $this->items[$platoId]['cantidad'] + $delta;

        // Validar stock
        if ($delta > 0 && $this->items[$platoId]['stock_ilimitado'] == 0) {
            if ($newQuantity > $this->items[$platoId]['stock']) {
                $this->dispatch(
                    'stock-error',
                    platoId: $platoId,
                    stock: $this->items[$platoId]['stock']
                );
                return;
            }
        }

        if ($newQuantity <= 0) {
            unset($this->items[$platoId]);
        } else {
            $this->items[$platoId]['cantidad'] = $newQuantity;
        }

        $this->saveCart();
        $this->calculateTotals();
        $this->dispatch('cart-updated');
    }

    private function saveCart()
    {
        session(['carrito' => $this->items]);
    }

    private function calculateTotals()
    {
        $this->totalItems = 0;
        $this->totalPrice = 0;

        foreach ($this->items as $item) {
            $this->totalItems += $item['cantidad'];
            $this->totalPrice += $item['precio'] * $item['cantidad'];
        }
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
