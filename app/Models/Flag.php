<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Flag extends Model
{
    use Sushi;

    /**
     * Flags available for resources.
     * Colors defined in array need to be safelisted in Tailwind CSS configuration.
     *
     * @var array
     */
    protected array $rows = [
        ['abbr' => 'broken', 'name' => 'Broken', 'color' => 'text-red-600'],
        ['abbr' => 'dirty', 'name' => 'Dirty', 'color' => 'text-yellow-600'],
        ['abbr' => 'in-repair', 'name' => 'In repair', 'color' => 'text-orange-600'],
        ['abbr' => 'missing', 'name' => 'Missing', 'color' => 'text-gray-600'],
    ];
}
