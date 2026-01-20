<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Categoria;
use Illuminate\Support\Facades\Cache;

class MenuList extends Component
{
    public $search = '';
    public $categorias = [];

    public function mount()
    {
        $this->loadPlatos();
    }

    public function updatedSearch()
    {
        $this->loadPlatos();
    }

    private function loadPlatos()
    {
        $query = Plato::where('disponible', true);

        if ($this->search) {
            $searchTerms = explode(' ', strtolower(trim($this->search)));

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->whereRaw('LOWER(nombre) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(descripcion) LIKE ?', ["%{$term}%"]);
                    });
                }
            });
        }

        $platos = $query->orderBy('categoria')->orderBy('nombre')->get();

        // Organizar por categorías según lo definido en el proyecto
        $this->categorias = [
            'Bebidas' => [],
            'Empanadas' => [],
            'Pizzas' => [],
            'Tartas' => [],
            'Postres' => []
        ];

        foreach ($platos as $plato) {
            $cat = $plato->categoria;
            if (isset($this->categorias[$cat])) {
                $this->categorias[$cat][] = $plato;
            } else {
                $this->categorias['Otros'][] = $plato;
            }
        }

        // Filtrar categorías vacías
        $this->categorias = array_filter($this->categorias, fn($items) => !empty($items));
    }

    public function render()
    {
        return view('livewire.menu-list');
    }
}
