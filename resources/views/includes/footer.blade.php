<script src="{{ asset('assets/js/main.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/map-init.js') }}"></script>

<script src="{{ asset('js/progress-bar.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8c55_YHLvDHGACkQscgbGLtLRdxBDCfI">
</script>
<script>
    //comments on post ajax
    $('#comment').keypress(function (event) {

    
        if (event.keyCode == 13 && event.shiftKey) {
          
            var content = this.value;
            var caret = getCaret(this);
            this.value = content.substring(0,caret)+"\n"+content.substring(carent,content.length-1);
            event.stopPropagation();    
        }else if(event.keyCode == 13)
        {
            $('#comment-form').submit();
        }
    });
    function getCaret(el) { 

        if (el.selectionStart) { 
            return el.selectionStart; 
        } else if (document.selection) { 
            el.focus(); 

            var r = document.selection.createRange(); 
            if (r == null) { 
            return 0; 
            } 

            var re = el.createTextRange(), 
                rc = re.duplicate(); 
            re.moveToBookmark(r.getBookmark()); 
            rc.setEndPoint('EndToStart', re); 

            return rc.text.length; 
        }  
        return 0; 
    }

</script>
<script>
    $('.post-opt').click(function(){
        $('.opt-list').toggle();
    });

    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();   
    });
</script>
<script>
    $(document).ready(function(){     
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // like dislike and reaction emoticons
        $(".reaction i img, #dislikeId").click(function(){
            
            var id ='';
            var reaction ='';
            var data = $(this).attr('class').split(' ')[0];
            if(data == 'emoji'){
                id = $(this).parent().parent().attr('value');
                reaction = $(this).parent().attr('id');
              }else{
                id = $(this).attr('value');
              }
            $.ajax({
                type:'POST',
                url:'{{ url("like") }}',
                data:{
                        post_id : id,
                        reaction : reaction,
                        data	:data
                    },
                success:function(data){
                    
                }
            });
        });
    });


    function  seen_notification(){

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            var id = $(this).attr('value');;
            $.ajax({
                type:'POST',
                url:'{{ url("seen") }}',
                data:{
                        post_id : id,
                    
                    },
                success:function(data){
                    
                }
            });

    }

</script>
<script>
    
    function myFunction(){
        var post_id = $('#edit-post').attr('value');
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        //edit posts description
        $.ajax({
            type:'POST',
            url:'{{ url("get-post") }}',
            data:{
                    post_id : post_id
                },
            success:function(data){
                // console.log(data);
                $('#editpost').modal('toggle')
                                            
                $('#post_contet').append('<textarea class="form-control" name="content" id="content" rows="3">'+ data.content +'</textarea><input type="hidden" name="post_id" value="'+ data.post_id +'">')
                $('.post-card-img').append('<img class="card-img-top" src="upload/images/'+ data.image +'" alt="Card image cap">')
            }
        });
        //emoticons listing
       
    }
    $("#editpost").on("hidden.bs.modal", function () {
        $('#content').remove();
        $('.card-img-top').remove();
    });

    $('#edit-post-submit').click(function(){
        $("#edit_form").submit();
    });

    $('#editpost').on('show.bs.modal', function() {
        $('.ti-more-alt').toggle();
    });

</script>
<script>
    // reactions on post
    $('#likeId').click(function(){
        $('.reaction').toggle();
        $('.reaction').delay(10000).fadeOut();
    });

    $('#fileid').change(function() {
			$('#profile_form').submit();
		});


 /// page post comment

 $('#page_post_comments').submit(function (){


     var formdata = new FormData();

     $.ajax({
        type:'POST',
            url:'{{ url("fav-page") }}',
            data:{
                    data : formdata
                },
     });
});


$('.we-reply').click(function (){

 
    alert('efdkld');
      $("#new_cmt_box0").toggle();
     


});




</script>
<script src="https://cdn.tiny.cloud/1/ueqxpvc9gv7hzpnpf4jj0kq9h4n1c3ijg41bb4aqnlyvfa28/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
      selector: "#page_post_comment",
      plugins: "emoticons",
      height :100,
      toolbar: "emoticons",
      toolbar_location: "bottom",
      menubar: false
    });

  </script>

<script>
    
    tinymce.init({
      selector: "#page_post_reply_comment",
      plugins: "emoticons",
      height :100,
      toolbar: "emoticons",
      toolbar_location: "bottom",
      menubar: false
    });


      
    



    // add remove active class form navtabs in reaction listing 
    $('.tab-a').click(function(){
        $('.nav-tabs .nav-link').removeClass('active');
        $(this).parent().addClass('active');
    });
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    $('.rec').click(function(){
        $.ajax({
            type:'POST',
            url:'{{ url("get-reaction") }}',
            data:{
                    post_id : $(this).attr('data_id')
                },
            success:function(data){
                $('.user_card').remove();
                console.log(data);
                if(data.allReaction){
                    var selector = '';
                    var count=[];
                    var count_like_rc = '';
                    var count_love_rc = '';
                    var count_haha_rc = '';
                    var count_angry_rc = '';
                    var count_care_rc = '';
                    var count_wow_rc = '';
                    var count_sad_rc = '';

                    $.each(data.allReaction, function(key,val) {
                        var html_add = (val.is_frnd_status==1)?(''):('<a href="see_friend/'+ val.id +'" class="btn btn-primary btn-sm add_rc_frnd">Add Friends</a>');
                        $('.allfrnd').append('<div class="user_card"><div class="row"><div class="col-sm-6 text-left"><img src="upload/images/'+ val.profile_photo +'.jpg" class="rc_profile_pic" style="max-width: 60px" alt=""><span class="rc_name">'+ val.name +'</span></div><div class="col-sm-6 text-right">'+ html_add +'</div></div></div>');


                        if(val.reaction==1){
                            var selector = 'like_rc';
                            count_like_rc++;
                        }
                        if(val.reaction==2){
                            var selector = 'love_rc';
                            count_love_rc++;
                        }
                        if(val.reaction==3){
                            var selector = 'haha_rc';
                            count_haha_rc++;
                        }
                        if(val.reaction==4){
                            var selector = 'angry_rc';
                            count_angry_rc++;
                        }
                        if(val.reaction==5){
                            var selector = 'care_rc';
                            count_care_rc++;
                        }
                        if(val.reaction==6){
                            var selector = 'wow_rc';
                            count_wow_rc++;
                        }
                        if(val.reaction==7){
                            var selector = 'sad_rc';
                            count_sad_rc++;
                        }
                      
                        $('.'+selector).append('<div class="user_card"><div class="row"><div class="col-sm-6 text-left"><img src="upload/images/'+ val.profile_photo +'.jpg" class="rc_profile_pic" style="max-width: 60px" alt=""><span class="rc_name">'+ val.name +'</span></div><div class="col-sm-6 text-right">'+ html_add +'</div></div></div>');
                    });
                    $('.ins_like').html('&nbsp;'+count_like_rc);
                    $('.ins_love').html('&nbsp;'+count_love_rc);
                    $('.ins_haha').html('&nbsp;'+count_haha_rc);
                    $('.ins_angry').html('&nbsp;'+count_angry_rc);
                    $('.ins_care').html('&nbsp;'+count_care_rc);
                    $('.ins_wow').html('&nbsp;'+count_wow_rc);
                    $('.ins_sad').html('&nbsp;'+count_sad_rc);
                    if(count_like_rc == ''){
                        $('.ins_like').parent().parent().remove()
                    }if(count_love_rc == ''){
                        $('.ins_love').parent().parent().remove()
                    }if(count_haha_rc == ''){
                        $('.ins_haha').parent().parent().remove()
                    }if(count_angry_rc == ''){
                        $('.ins_angry').parent().parent().remove()
                    }if(count_care_rc == ''){
                        $('.ins_care').parent().parent().remove()
                    }if(count_wow_rc == ''){
                        $('.ins_wow').parent().parent().remove()
                    }if(count_sad_rc == ''){
                        $('.ins_sad').parent().parent().remove()
                    }
                    var count = [count_like_rc, count_love_rc, count_haha_rc, count_angry_rc, count_care_rc, count_wow_rc, count_sad_rc];
                    console.log(count.sort().reverse());


                }
                
                // trigger modal 
                $('#reaction').modal('toggle');
               
            }
        });
        // alert();
    });

    function send_invitation(){

       
        var friend_id = $('#friend_id').val();
        var post_id = $('#post_id').val();

       $.ajax({
        type:'POST',
            url:'{{ url("invite-friend") }}',
            data:{
                    friend_id : friend_id,
                    post_id : post_id
                },
            success:function(data){

               if(data['status'] == 1){

                    $('#invitation_cancel').show();
                    $('#send_invitation').hide();

               }
              

            },
       });

    }

    $('#invitation_cancel').click(function (){
       
          
        var friend_id = $('#friend_id').val();
        var post_id = $('#post_id').val();

       $.ajax({
        type:'POST',
            url:'{{ url("cancel_invitation") }}',
            data:{
                    friend_id : friend_id,
                    post_id : post_id
                },
            success:function(data){

               if(data['status'] == 1){

                    $('#invitation_cancel').hide();
                    $('#send_invitation').show();

               }
              

            },
       });

    });

     $('#like_page').click(function(){
         var page_id =  $('#like_page_id').val();
         var friend_id  = $('#like_friend_id').val();
          $.ajax({
            type:'POST',
            url:'{{ url("like_page") }}',
            data:{
                    page_id : page_id,
                    friend_id: friend_id
                }, 
            success:function(data){

                if(data['status'] == 1){

                      $('#like_page').hide();
                      $('#unlike_page').show();
                }

            },

          });
     });

     $('#unlike_page').click(function(){
        var page_id =  $('#like_page_id').val();
        var friend_id  = $('#like_friend_id').val();
         
        $.ajax({
            type:'POST',
            url:'{{ url("unlike_page") }}',
            data:{
                    page_id : page_id,
                    friend_id: friend_id
                }, 
            success:function(data){

                if(data['status'] == 1){

                      $('#like_page').show();
                      $('#unlike_page').hide();
                }

            },

          });
     });

    
</script>

<script>
  tinymce.init({
      selector: ".comment_1243",
      plugins: "emoticons",
      height :100,
      toolbar: "emoticons",
      toolbar_location: "bottom",
      menubar: false
    });
</script>
