<?php

namespace Tests\Unit\Model;

use Hydrofon\Object;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Object can have bookings.
     *
     * @return void
     */
    public function testObjectCanHaveBookings()
    {
        $object = factory(Object::class)->create();

        $this->assertInstanceOf(Collection::class, $object->bookings);
    }

    /**
     * Object can belong to categories.
     *
     * @return void
     */
    public function testObjectCanBelongToCategories()
    {
        $object = factory(Object::class)->create();

        $this->assertInstanceOf(Collection::class, $object->categories);
    }
}
