jQuery(function($){

function plugin_dir_url(){
  return path_arr.plugin_dir_url;
}

function plugin_dir_path(){
  return path_arr.plugin_dir_path;
}

function home_url(){
  return path_arr.home_url;
}

function ajaxurl(){
  return path_arr.ajaxurl;
}

function getParam(name, url) {
  //http://www-creators.com/archives/4463 様から拝借
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
  results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

var WindowWidth = $(window).width();

$(window).on('load',function(){

/* ##################################
    隊員管理画面
  ################################## */

if (getParam('page') == "sti_admin"){

  var file_frame;
  $('.upload_image_button').on('click', function( event ){
    event.preventDefault();

    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: '隊員の画像をアップロードしてください(1枚まで)',
      button: {
      text: '画像を選択します',
      },
      multiple: false
    });

    file_frame.on( 'select', function() {
      attachment = file_frame.state().get('selection').first().toJSON();
      $( '#image').val( attachment.url );
      $( '#modal_image').val( attachment.url );
      if($("#image")){
        $(".upload_image_button").text("選択されました(編集)");
      }

      if($("#modal_image")){
        if($("#modal_image").val() !== ""){
          $("#modal_image_button").text("新たに選択されました(編集)");
        }else{
          $("#modal_image_button").text("画像をアップロードする");
        }
      }
    });

    file_frame.open();
  });

  $("#posion_add_button").click(function(){
    var val = $('#posion_add').val();
    $.ajax({
      type: 'POST',
      url: ajaxurl(),
      data: {
        "val":val,
        'action': 'ajaxAdd',
      },
    }).done(function(data){
      /* 通信成功時 */
      $("#notice2").html("<div class='updated fade'><p><strong>役職のみ保存しました</strong></p></div>");
      $("#posion").append($('<option>').html(val).val(val));
      $("#posion_remove").append($('<option>').html(val).val(val));
      $("#modal_posion").append($('<option>').html(val).val(val));
      $("#posion_add").val("");

    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
    });
  });

  $("#posion_remove_button").click(function(){
    var val = $('#posion_remove').val();
    $.ajax({
      type: 'POST',
      url: ajaxurl(),
      data: {
        "val":val,
        'action': 'ajaxRemove',
      },
    }).done(function(data){
      /* 通信成功時 */
      $("#notice2").html("<div class='updated fade'><p><strong>役職のみ削除しました</strong></p></div>");
      $('select#posion option[value='+val+']').remove();
      $('select#posion_remove option[value='+val+']').remove();
      $('select#modal_posion option[value='+val+']').remove();

    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
    });
  });

  $(".fix").change(function(){
    var val;
    var id = $(this).data('id');
    if($(this).prop('checked')){
      var val = 1;
    }
    $.ajax({
      type: 'POST',
      url: ajaxurl(),
      data: {
        "val":val,
        "id":id,
        'action': 'ajaxFix',
      },
    }).done(function(data){
      /* 通信成功時 */
      $("#notice").html("<div class='updated fade'><p><strong>固定を変更しました</strong></p></div>");
      $
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
    });
  });

  $(".honorific").change(function(){
    var val;
    var id = $(this).data('id');
    if($(this).prop('checked')){
      var val = 1;
    }
    $.ajax({
      type: 'POST',
      url: ajaxurl(),
      data: {
        "val":val,
        "id":id,
        'action': 'ajaxHonorific',
      },
    }).done(function(data){
      /* 通信成功時 */
      $("#notice").html("<div class='updated fade'><p><strong>敬称を変更しました</strong></p></div>");
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
    });
  });

  $('.js-modal-open').on('click',function(){
      $('.js-modal').fadeIn();
      var id = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: ajaxurl(),
        data: {
          "val":id,
          'action': 'ajaxEdit',
        },
      }).done(function(data){
        /* 通信成功時 */
        var json = JSON.parse(data);
        $('#modal_id').val(json[0].id);
        $('#modal_name').val(json[0].name);
        $('#modal_day').val(json[0].created.slice(0,-9));
        $('#modal_posion').val(json[0].posion);
        $('#modal_sex').val(json[0].sex);
        $('#modal_age').val(json[0].age);
        $('#modal_image').val(json[0].image);
        $('#modal_comment').val(json[0].comment);
        if($("#modal_image").val() !== ""){
          $("#modal_image_button").text("すでに投稿済みです(編集)");
        }else{
          $("#modal_image_button").text("画像をアップロードする");
        }
      }).fail(function(XMLHttpRequest, textStatus, errorThrown){
          alert(errorThrown);
      });

      var modal_width = $('.modal__content').width();
      $('#modal_comment,#sti_modal_submit,#sti_1').css('width',modal_width );



      if(WindowWidth < 991){
        //ここに書いた処理はスマホ閲覧時のみ有効となる
      }else{
        $("#control-group-2,#control-group-3,#control-group-4,#control-group-5").css('width',modal_width/4 );
      }

      return false;
  });

  $('.js-modal-close').on('click',function(){
      $('.js-modal').fadeOut();
      return false;
  });

  var checkElm = $("*"),
    elmArray = [],
    i;

  for (i = 0; i < checkElm.length; i++) {
    // text-overflow: ellipsis;が当たっている要素を配列に挿入
    if($(checkElm[i]).css("text-overflow") == "ellipsis") {
      elmArray.push(checkElm[i]);
    }
  }

  // 要素に当たっている横幅を"数値で"取得
  var cssSetWidth = parseInt($(elmArray).css("width")),
      strArray = [];

  for (i = 0; i < elmArray.length; i++) {
    // text-overflow: ellipsis;が当たっている要素の文字列を取得
    var getStr = $(elmArray[i]).text();

    // ダミーのspan要素に文字列を挿入し、横幅を取得
    $("body").append("<span class='dummy" + [i] + "'>" + getStr + "</span>");
    var strWidth = $(".dummy" + i).width();

    // 文字幅がCSS指定の横幅を超えたら、title属性(値: 取得した文字列)を挿入
    if(strWidth > cssSetWidth) {
      $(elmArray[i]).data('display','none');
    }
    $(".dummy" + i).remove();
  }

  for(var i=0;i<$(".over-text").length;i++){
    if($(".over-text").eq(i).data("display") !== "none"){
      $(".allText").eq(i +2).text("");
    }
  }

  $("#sortable").sortable();

  $('#sortable').bind('sortstop',function(){

    var id=[];

    for(var i=0;i<$(".sort").length;i++){
      id[i]=$(".sort").eq(i).attr("data-id");
    }

    $.ajax({
      type: 'POST',
      url: ajaxurl(),
      data: {
        "id":id,
        'action': 'ajaxSort',
      },
    }).done(function(data){
      /* 通信成功時 */
      $("#notice").html("<div class='updated fade'><p><strong>並び順が変更されました</strong></p></div>");
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      alert(errorThrown);
    });
  });

}

/* ##################################
    活動予定
  ################################## */

if (getParam('page') == "sti_schedule"){

  $('.js-modal-open').on('click',function(){
      $('.js-modal').fadeIn();
      var id = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: ajaxurl(),
        data: {
          "val":id,
          'action': 'ajaxEdit2',
        },
      }).done(function(data){
        /* 通信成功時 */
        var json = JSON.parse(data);
        $('#modal_id').val(json[0].id);
        $('#modal_day').val(json[0].day);
        $('#modal_field').val(json[0].field);
        $('#modal_fee').val(json[0].fee);
      }).fail(function(XMLHttpRequest, textStatus, errorThrown){
          alert(errorThrown);
      });

      if(WindowWidth < 991){
        //ここに書いた処理はスマホ閲覧時のみ有効となる
      }else{
        var modal_width = $('.modal__content').width();
        $('#modal_day,#modal_field,#modal_fee').css('width',modal_width / 3 -9);
        $('#sti_modal_submit').css('width',modal_width);
      }

      return false;
  });

  $('.js-modal-close').on('click',function(){
      $('.js-modal').fadeOut();
      return false;
  });
}

});

});
