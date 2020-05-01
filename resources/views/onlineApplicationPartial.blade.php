    <div class="row">
        @foreach ($supportedTypes as $item)  
        <div class="col-4">
            <form action="user/addOnlineApplication" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$item->id}}"/> 
                <button class="bg-transparent border-0">
                <img style="width:100%; height:auto" src="{{$item->thumbnail}}"/>
                <div class="text-center">{{$item->name}}</div>
            </button>
            </form>
        </div>
        @endforeach
    </div>