@extends('layouts.app')

@section('content')
<?php $pass_token = csrf_token();?>
<cart token="{{ $pass_token }}"></cart>
    

@endsection

</body>
</html>