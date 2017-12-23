<section class="objectlist">
    <ul>
        @foreach(\Hydrofon\Object::all() as $object)
            <li class="objectlist-object">{{ $object->name }}</li>
        @endforeach
    </ul>

    <a href="#" id="objectlist-toggle" class="objectlist-toggle"></a>
</section>