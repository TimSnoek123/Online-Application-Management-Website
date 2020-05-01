@extends('layouts.app')


@section('head')
<link href="{{ asset('css/microsoft/OneDrive.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="container-fluid" >
    <h1>OneDrive</h1>
    <p id="fileHistory" style="width: 100vw"><a class="current-file file-history-link" id="root" href="">My Drive</a> </p>
    <h4 style="display: none" id="loadingIcon">Loading.......</h4>
    <div id="myFiles" class="row"></div>
</div>


@endsection

@section('scripts')
<script>
Element.prototype.getElementById = function(id) {
    return document.getElementById(id);
}

    var fileHistory = document.getElementById('fileHistory');
    $(document).ready(function(){
        var fileFacets = ["folder", "file", "image"];

$.ajax({
   url: '/application/getCookieValue?sourceCompany=microsoft',
   type: 'get',
   dataType: 'json',
   complete: function(response) {
       if (response.status === 200){
           localStorage.setItem('token', response.responseJSON);
           getAllFiles();
       }
   }
});

function getAllFiles(callback = null){
$.ajax({
   url: 'https://graph.microsoft.com/v1.0/me/drive/root/children?$expand=thumbnails',
   type: 'get',
   beforeSend: function(request){
       request.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token'));
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
    var files = document.getElementById('myFiles');
    files.innerHTML = "";
    filesJSON.value.forEach(element => {
               fileFacets.forEach(facet => {
                   if (facet in element){
                       var parentDiv = document.createElement("div");
                       var newDiv = document.createElement('div');
                       parentDiv.classList.add("col-3")
                       parentDiv.id = element.id;
                       parentDiv.innerHTML = element.name;
                       parentDiv.classList.add(facet);
                       parentDiv.appendChild(newDiv);

                    if (element.thumbnails.length > 0){
                        var thumbnail = document.createElement('img');
                        thumbnail.src = element.thumbnails[0].medium.url;
                        parentDiv.appendChild(thumbnail);
                    }
                    else{

                        var thumbnail = document.createElement('img');
                        thumbnail.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAsVBMVEX/////yij/oAD/ngD/yBj/3pb/mgD/vB7/7NP/t2H/nAD/z5b/zCn/xwD/mwD/vh//qDD/sRX/wiL/3bb/thn/pAj/xyX/8N7/rBD/qAv/+vL/9ef/xoD/4pn/343/2HL/56z/5aT/6bT/9Nr/2ar/zpP/4sD/qCX/wXX/xX3/tVL/yor/rj3/skf/pjH/zTn/0Ej/7cD/89b/9+L/3IP/2nn/013/7cT/0VL/9Nf/15fJTF12AAAExklEQVR4nO2di1rTQBBGk2xcSspW1JZiKwqKXATF++39H8wK8onQzEyyi5nZ7z9PkPNtkj3bTZqiAAAAAAAAAAAAAAAAAAAAAAAAAADQw2J0OO7G66Ohj7kDy/G295OmI/7J0MctZb7pG1f1wIriM9/00TOjOD+e9PWzoTjqd37aURz5OEH1isvIEdSvuB0vqFtx7BMIalZcxNxFTSie9Z4HrSgmE9SqeJTmKlSseJJwDHUqHqeYKjQrLpIOoUbFZcrLUKXiPLmhNsVResNqspmS08O5OsPKpaSZ+OpZhOS9GKbGNf5Nb0cThiucP8vccHV1ny8yN6yaapm5YeVmfUbRkmHltnM3rCY9bje2DCvffdIwZuje5m5Y+VHuhs1J7oZVk71h59PUnGEzzt6w64VozrDzfGHPsGu5wVAdMIShfmAIQ/38B8OkP2j3+An83f0aOjfbnU43BmXv+d7LV/dj6JrZdGdra6sclK16RahffEhv6GYPh7a7wUryIK2ha6Z69K4IFx8TGrrZY22CK8J+MkM3G1pmPeF5IkOtghJFoeHO0CathJcpDJsNhdfgNeFTvKF7qliwLN8nMNR4G/1LoOd+gaHbVS3IDaLAsHk8tAJDICd+yRgqH8Ky/hxnqP4kXcV4pOF0aAGWQD3BIDB8OLQAS6CWiwJDvT1zDTlfCO406i/DsqbKjTVUHjSX1FFj6B7pNyQnRN5wY+jj5yHjmzVsdEfpJYEQFBgOffgC4mb8mf4hrF/EGBpotrLeizLU32xl+BZlqL/ZykA+O8wa6m+2sqQE+TuN/suw/B5jaKLZyAUwa2ig2er9KEMLzUbvzzCGJpqNfkeBG8OhD18A2WycoTPfbKyhhWZjdp8YQwPNRi7weUMLzRa192Sh2WrmdS/mXqr/MuQ212hDE832JcrQQrNxT9XQhhaajVz+coYmmo17dZYew6EPX0DNCJKGFpqt/BplmEGzMYYWmo17YIg2tNBs7LO0pGEGzcYY6r8MywtOkDI00WzM8pcxtNBs5JYFa5hDs5GGJpqNWf4yYzj04Qtgm40yNNFs9JYFZ2ih2egtC85Q3fsVd2G2LDhDC80meKWk3bAx0GzMlgU3hvpPUsmttN3QRLOxy1/S0EKz8e8EUYYGmo3bsqANM2k2agwN3Er55S9laKHZuC0L0tBEs3FbFrShhWYTvQjcamih2fjlL2FootlE//bZeqfRf5LSDz9zhhaaTbL8JQxzabZ2QwvNxm5ZUIYmmu1H1BgOffgCRM3Wamih2fgtC8LQRLPxWxaUoYVm47csKMNsmq3N0ESzSZa/rYb5NFvLN0oyaraiWKw1tNBsgi2LK8N1H0My0Wz7QsO1nwY00WySf8G65HTNB58yarZi/XfXLDSb5Af9P9z9RKeJZpMtnS65+/1DE80mF1zz5TUDzSaN0isOb1+JFpqti2BRnN++EtWfpNLqvmbh/lHU32yh0zn6m/nkpqL6ZpOuff9RvDllaG+20EOwKJY3vq2u/Q/oOp+ifzjz17OG6mYLFx3+Qvj2MG76q3NVb7PV4aJDyqxzHJ97P/G7tUpCCF/E6wmC+dHhzwfq2Ds42P8m/F0GAAAAAAAAAAAAAAAAAAAAAAAAAOA/8QtWysSdQy406wAAAABJRU5ErkJggg==";
                        thumbnail.style.width = "176px";
                        thumbnail.style.height = "176px";
                        parentDiv.appendChild(thumbnail);
       }
                        
                       files.appendChild(parentDiv);
                   }
                   })
       });
}

    $('#myFiles').on("click", ".folder",function(){
        setFilesInsideFolder(this.id, () =>  addToFileHistory(this.innerText, this.id));
    })

    function setFilesInsideFolder(FolderId, callback){
        $('#myFiles').toggle();
        $('#loadingIcon').toggle();
        $.ajax({
            url: 'https://graph.microsoft.com/v1.0/me/drive/items/' + FolderId + '/children?$expand=thumbnails',
            type: 'get',
            beforeSend: function(request){
       request.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token'));
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
        }
        else{
            setFilesInsideFolder(this.id, () =>  {
            $(_this).nextAll().remove();
            });
        }
    })




})
</script>

@endsection
