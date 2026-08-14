<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommandPaletteController extends Controller
{
    public function search(Request $request)
    {
        $query = strtolower($request->input('q', ''));
        
        // Define all possible commands
        $commands = [
            ['title' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'bi-speedometer2'],
            ['title' => 'New Order', 'url' => route('orders.create'), 'icon' => 'bi-plus-circle'],
            ['title' => 'Manage Menu', 'url' => route('menu.index'), 'icon' => 'bi-menu-button'],
            ['title' => 'Tenants', 'url' => route('tenants.index'), 'icon' => 'bi-clouds'],
            ['title' => 'Branches', 'url' => route('branches.index'), 'icon' => 'bi-building'],
            ['title' => 'POS', 'url' => route('pos.index'), 'icon' => 'bi-display'],
            ['title' => 'Settings', 'url' => '#', 'icon' => 'bi-gear'],
            ['title' => 'Generate QR', 'url' => route('qr.generate'), 'icon' => 'bi-qr-code'],
            ['title' => 'Reports', 'url' => route('reports.index'), 'icon' => 'bi-graph-up'],
        ];

        // Filter commands based on query
        $results = array_filter($commands, function($command) use ($query) {
            return str_contains(strtolower($command['title']), $query);
        });

        return response()->json(array_values($results));
    }
}
