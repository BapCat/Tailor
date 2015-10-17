@php

@class(Nom)
  public @func(__construct)
    echo '{{ $hello }}';

    echo '{! $special !}';

    @if($bool)
      echo '{{ '$bool was true' }}';
    @else
      die('bad 1')
    @endif

    @if(!$bool)
      die('bad 2')
    @else($bool)
      echo '{{ '$bool was REALLY true' }}';
    @endif

    echo 'Values:';
    @each($arr as $v)
      echo '{{ $v }}';
    @endeach

    echo 'Keys and values:';
    @each($arr as $k,v)
      echo '{{ $k }} => {{ $v }}';
    @endeach
  @endfunc
@endclass
