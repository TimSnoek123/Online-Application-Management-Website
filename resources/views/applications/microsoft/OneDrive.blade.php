@extends('layouts.app')


@section('head')
<link href="{{ asset('css/microsoft/OneDrive.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="container-fluid" >
    <h1>OneDrive</h1>
    <p id="fileHistory" style="width: 100%"><a class="current-file file-history-link" id="root" href="">My Drive</a> </p>
    <h4 style="display: none" id="loadingIcon">Loading.......</h4>
    <div id="myFiles" class="row">
        <div id="folder" style="min-width: 100%" class="onedrive-item-row" ></div>
        <div id="file"  style="min-width: 100%" class="onedrive-item-row"></div>
        <div id="image"  style="min-width: 100%" class="onedrive-item-row"></div>
    </div>
</div>


@endsection

@section('scripts')
<script>
Element.prototype.getElementById = function(id) {
    return document.getElementById(id);
}

String.prototype.trunc = 
      function(n){
          return this.substr(0,n-1)+(this.length>n?'&hellip;':'');
      };

    var fileHistory = document.getElementById('fileHistory');
    $(document).ready(function(){
        var fileFacets = ["folder", "file", "image"];

async function getAccessTokenValue(){
    var token;
   await $.ajax({
        url: '/application/getCookieValue?sourceCompany=microsoft',
        type: 'get',
        dataType: 'json',
        complete: function(response) {
        if (response.status === 200){
           token = response.responseText;
        }
    }
});
return token;
}


async function getAllFiles(callback = null){ 
    var token = await getAccessTokenValue();
$.ajax({
   url: '/application/command/GetAllOneDriveFiles',
   type: 'get',
   beforeSend: function(request){
       request.setRequestHeader('Authorization', 'Bearer ' + token);
       token = "";
   },
   complete: function(response){
       console.log(response);
       var files = document.getElementById('myFiles');
        setFileList(response.responseJSON);

        if (callback != null){
            callback();
        }
   }
})
}

function setFileList(filesJSON){
    $('#folder, #file, #image').html('');
    filesJSON.value.forEach(element => {
               fileFacets.forEach(facet => {
                   if (facet in element){
                       var parentDiv = document.createElement("div");
                      

                    var thumbnail = document.createElement('img');
                    thumbnail.classList.add('img-fluid')
                    if (facet != "folder" && element.thumbnails.length > 0){
                        thumbnail.src = element.thumbnails[0].small.url;
                        parentDiv.appendChild(thumbnail);
                    }
                    else{

                        thumbnail.src = "https://png.pngtree.com/element_our/png/20181229/vector-folder-icon-png_302656.jpg";
                        thumbnail.style.width = "176px";
                        thumbnail.style.height = "176px";
                        parentDiv.appendChild(thumbnail);
                    }

                    var newDiv = document.createElement('div');
                       parentDiv.classList.add("onedrive-item-container");
                       parentDiv.classList.add('text-center');
                       parentDiv.id = element.id;
                       newDiv.innerHTML =  element.name.trunc(18);
                       parentDiv.classList.add(facet);
                       parentDiv.appendChild(newDiv);
                        
                       $('#' + facet).append(parentDiv);
                   }
                   })
       });
}

    $('#myFiles').on("click", ".folder",function(){
        setFilesInsideFolder(this.id, () =>  addToFileHistory(this.innerText, this.id));
    })

    async function setFilesInsideFolder(FolderId, callback){
        var token = await getAccessTokenValue();
        $('#myFiles').toggle();
        $('#loadingIcon').toggle();
        $.ajax({
            url: 'https://graph.microsoft.com/v1.0/me/drive/items/' + FolderId + '/children?$expand=thumbnails',
            type: 'get',
            beforeSend: function(request){
       request.setRequestHeader('Authorization', 'Bearer ' + token);
       token = "";
   },
            complete: function(response){
                setFileList(response.responseJSON);
                $('#loadingIcon').toggle();
                $('#myFiles').toggle();
                callback();
            }
        });
    }

    function addToFileHistory(value, id){
        var slashSign = document.createElement("div");
        slashSign.style.display = 'inline';
        $(slashSign).html(' / ')
        fileHistory.appendChild(slashSign);
        var link = document.createElement('a');
        link.innerHTML = value;
        link.id = id;
        link.href = "";
        fileHistory.appendChild(link);
      

        $('#' + fileHistory.id + ' a').removeClass('current-file');
        var currentFile = fileHistory.getElementById(id);
        $(currentFile).addClass("current-file file-history-link");
    }

    $('#myFiles').on("click", ".file",function(){
    })


    $('#myFiles').on("click", ".image",function(){
    })

    $(fileHistory).on('click', '.file-history-link', function(event){
        _this = this;
        event.preventDefault();
        if (this.id == "root"){
            getAllFiles(() => $(this).nextAll().remove());
            $(this).addClass("current-file");
        }
        else{
            setFilesInsideFolder(this.id, () =>  {
            $(_this).nextAll().remove();
            });
            $(this).addClass("current-file");
        }
    })



    getAllFiles();
})
</script>

@endsection
