<?php
  $types = isset( $types ) ? ( is_array( $types ) ? $types : [$types] ) : Message::getTypes();
  $group = isset( $group ) ? $group : Message::getDefaultGroup();
?>
<div class="messages">
  @foreach( $types as $type )
    <div class="alert alert-{{$type}}" role="alert">
      @foreach( Message::get( $type, $group )->all() as $message )
        <div>
          {{ $message }}
        </div>
      @endforeach
    </div>
  @endforeach
</div>