<?php

namespace Startupful\WebpageManager\Http\Livewire;

use Livewire\Component;
use Startupful\StartupfulPlugin\Models\PluginSetting;

class MenuManagerComponent extends Component
{
    public $menuItems = [];
    public $newMenuItem = ['label' => '', 'url' => '', 'target' => '_self'];

    protected $rules = [
        'newMenuItem.label' => 'required',
        'newMenuItem.url' => 'required',
        'newMenuItem.target' => 'required',
    ];

    public function mount()
    {
        $this->loadMenuItems();
    }

    public function loadMenuItems()
    {
        $menuSetting = PluginSetting::where('plugin_name', 'webpage-manager')
            ->where('key', 'menu')
            ->first();

        $this->menuItems = $menuSetting ? json_decode($menuSetting->value, true) : [];
        if (!is_array($this->menuItems)) {
            $this->menuItems = [];
        }
    }

    public function addMenuItem()
    {
        $this->validate();

        $this->menuItems[] = $this->newMenuItem;
        $this->newMenuItem = ['label' => '', 'url' => '', 'target' => '_self'];

        $this->saveMenuItems();
    }

    public function updateMenuItem($index)
    {
        $this->saveMenuItems();
    }

    public function deleteMenuItem($index)
    {
        unset($this->menuItems[$index]);
        $this->menuItems = array_values($this->menuItems);
        $this->saveMenuItems();
    }

    public function reorderMenuItems($orderedIds)
    {
        $this->menuItems = collect($orderedIds)->map(function ($id) {
            return $this->menuItems[$id];
        })->toArray();

        $this->saveMenuItems();
    }

    public function saveMenuItems()
    {
        PluginSetting::updateOrCreate(
            ['plugin_name' => 'webpage-manager', 'key' => 'menu'],
            ['value' => json_encode(array_values($this->menuItems))]
        );

        $this->loadMenuItems(); // Reload items after saving
    }

    public function render()
    {
        return view('webpage-manager::livewire.menu-manager');
    }
}