<?php

function ajaxAddFunc(){
  $posion_name = $_POST['val'];

  //データベースから情報を$existに代入
  $exist = get_option('sti_posion_name');

  //配列でデータベースに情報を登録
  array_push( $exist, $posion_name);
  update_option('sti_posion_name', $exist);
  die();
}
add_action( 'wp_ajax_ajaxAdd', 'ajaxAddFunc' );

function ajaxRemoveFunc(){
  $posion_name = $_POST['val'];

  //データベースから情報を$existに代入
  $exist = get_option('sti_posion_name');

  //配列でデータベースに情報を登録
  for($i = 0; $i < count( $exist ); $i++ ){
    if( $exist[$i] == $posion_name ){
      unset( $exist[$i]);
      $exist = array_values($exist);
      update_option('sti_posion_name', $exist);
    }
  }
  die();
}
add_action( 'wp_ajax_ajaxRemove', 'ajaxRemoveFunc' );

function ajaxEditFunc(){
  global $wpdb;
  global $sti;

  $sql="SELECT * FROM ".$sti->table_name." where id =  ".$_POST['val'];
  $editName = $wpdb->get_results($sql,'ARRAY_A');
  echo json_encode($editName);
  die();
}
add_action( 'wp_ajax_ajaxEdit', 'ajaxEditFunc' );

function ajaxFixFunc(){

  global $wpdb;
  global $sti;

  $wpdb->update(
    $sti->table_name,
    array(
      'fix' => $_POST['val'],
    ),
    array(
      'id' => $_POST['id'],
    )
  );

  die();
}
add_action( 'wp_ajax_ajaxFix', 'ajaxFixFunc' );

function ajaxHonorificFunc(){

  global $wpdb;
  global $sti;

  $wpdb->update(
    $sti->table_name,
    array(
      'honorific' => $_POST['val'],
    ),
    array(
      'id' => $_POST['id'],
    )
  );

  die();
}
add_action( 'wp_ajax_ajaxHonorific', 'ajaxHonorificFunc' );

function ajaxSortFunc(){

  global $wpdb;
  global $sti;
  $id = $_POST['id'];
  $count = $wpdb->get_var("SELECT COUNT(*) FROM ".$sti->table_name);

  for($i=0;$i<$count;$i++){
    $wpdb->update(
      $sti->table_name,
      array(
        'sort' => $count -$i,
      ),
      array(
        'id' => $id[$i],
      )
    );
  }

  die();
}
add_action( 'wp_ajax_ajaxSort', 'ajaxSortFunc' );

function ajaxEditFunc2(){
  global $wpdb;
  global $sti2;

  $sql="SELECT * FROM ".$sti2->table_name." where id =  ".$_POST['val'];
  $editName = $wpdb->get_results($sql,'ARRAY_A');
  echo json_encode($editName);
  die();
}
add_action( 'wp_ajax_ajaxEdit2', 'ajaxEditFunc2' );
