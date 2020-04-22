@extends('layouts.app')



@section('content')
<div class="container-fluid">
    @if($errors->any())
        <h5 class="text-center" style="color: red">{{$errors->first()}}</h5>
    @endif
<button type="button" class="btn btn-secondary" data-popover-content="#a1" data-container="body" data-toggle="popover" data-placement="bottom" >
    Popover on bottom
</button>
<div class="hidden" id="a1">
    <div class="popover-body">
      @include("onlineApplicationPartial")
    </div>
  </div>
</div>

<div id="userchosenapplications" class="container">
    <div class="row">
        <div class="col-12">
            <input style="width: 20%" class="form-control" placeholder="Sort...."/>
        </div>
    </div>
    <div class="row">
    @foreach ($userChosenTypes as $item)
    <div class="col-2">
        <img style="width:100%; height:auto" src="{{$item->thumbnail}}"/>
        <p class="text-center">{{$item->name}}</p>
    </div>
    @endforeach
    </div>
</div>


@endsection

@section('scripts')
<script>
    $("[data-toggle=popover]").popover({
        html : true,
        //Make a whitelist out of this later
        sanitize: false,
        content: function() {
          var content = $(this).attr("data-popover-content");
          return $(content).children(".popover-body").html();
        },
    });
</script>
@endsection

<style>
    .popover{
        width: 100%;
        max-width: 400 !important;
        height: 40vh;
    }

    .hidden{
        display: none;
    }
</style>